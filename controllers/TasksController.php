<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\AddTaskForm;
use app\models\AddResponseForm;
use app\models\AddReviewForm;
use app\models\TaskFilterForm;
use app\models\Task;
use app\models\Attachment;
use app\models\Category;
use app\models\Response;
use app\models\Review;
use app\models\User;
use Taskforce\Tasks;
use Taskforce\TasksData;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;

class TasksController extends AccessController
{
    public function behaviors()
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['add', 'accept', 'reject'],
            'matchCallback' => function ($rule, $action) {
                return Yii::$app->user->identity->role !== 'customer';
            }
        ];

        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

    public function actionIndex()
    {
        $filter = new TaskFilterForm();
        $tasks = $filter->getTasks();
        $categories = Category::find()->all();

        if (Yii::$app->request->getIsget()) {
            $filter->load(Yii::$app->request->get());
            if ($filter->validate()) {
                $tasks = $filter->apply();
            }
        }

        $pagination = new Pagination(['totalCount' => $tasks->count(), 'pageSize' => 5]);
        $pagination->pageSizeParam = false;
        $pagination->forcePageParam = false;
        $tasks = $tasks->offset($pagination->offset)
        ->limit(5)
        ->all();

        return $this->render('index', [
            'tasks' => $tasks,
            'filter' => $filter,
            'pagination' => $pagination,
            'categories' => $categories,
            'period_values' => TaskFilterForm::PERIOD_VALUES
        ]);
    }

    public function actionView(int $id)
    {
        $model = new TasksData($id);
        $task = $model->getTask();
        $idCurrent = Yii::$app->user->getId();

        $attachments = $model->getAttachments();
        $responses = $model->getResponses();
        $reviewsCount = $model->getReviewsCount();
        $taskResponses = $model->getTaskResponses();
        $taskActions = $model->getTaskActions();

        $action = $taskResponses === TasksData::DEFAULT_RESPONSES ?
        $taskActions->getAvailableAction($idCurrent) : null;

        $status = $taskActions->getStatusName($task->status);

        $newResponse = new AddResponseForm();

        if (Yii::$app->request->getIsPost()) {
            $newResponse->load(Yii::$app->request->post());
            if ($newResponse->validate()) {
                $newResponse->addResponse($task->id);
                return $this->refresh();
            }
        }

        $newReview = new AddReviewForm();

        if (Yii::$app->request->getIsPost()) {
            $newReview->load(Yii::$app->request->post());
            if ($newReview->validate()) {
                $newReview->addReview($task->id);
                return $this->refresh();
            }
        }

        return $this->render('view', [
            'newResponse' => $newResponse,
            'newReview' => $newReview,
            'task' => $task,
            'attachments' => $attachments,
            'status' => $status,
            'action' => $action,
            'responses' => $responses,
            'reviewsCount' => $reviewsCount
        ]);
    }

    public function actionAccept(int $id)
    {
        $response = Response::findOne($id);
        $task = Task::findOne($response->task_id);
        $user = User::findOne($response->user_id);

        if (!$task || !$user || !$response) {
            throw new SourceDataException('Данное действие не может быть выполнено!');
        }

        $task->status = Tasks::STATUS_WORKING;
        $response->position = 'accepted';
        $task->save();
        $response->save();

        $this->redirect('/tasks/view/' . $response->task_id);
    }

    public function actionRefuse(int $id)
    {
        $response = Response::findOne($id);
        $response->position = 'refused';
        $response->save();

        return $this->redirect(['tasks/view', 'id' => $response->task_id]);
    }

    public function actionReject(int $id)
    {
        $task = Task::findOne($id);
        $task->status = Tasks::STATUS_FAILED;
        $task->save();
        $user = User::findOne($task->user_id);
        $user->updateStats();

        return $this->redirect(['tasks/view', 'id' => $task->id]);
    }

    public function actionAdd()
    {

        $categories = ArrayHelper::map(Category::find()->all(), 'id', 'title');

        $form = new AddTaskForm();

        if (Yii::$app->request->getIsPost()) {
            $form->load(Yii::$app->request->post());

            if ($form->validate()) {
                if ($form->location && !$form->lat && !$form->lng) {
                    $addresses = AutocompleteController::getGeocoder($form->location);

                    $form->lat = $addresses[0]['lat'];
                    $form->lng = $addresses[0]['lng'];
                    $form->location = $addresses[0]['location'];
                    $form->city = $addresses[0]['city'];
                }
                $newTask = $form->addTask();
                return $this->redirect(['tasks/view', 'id' => $newTask->id]);
            }
        }

        return $this->render('add', [
            'model' => $form,
            'categories' => $categories
        ]);
    }

    public function actionMy()
    {

        $idCurrent = Yii::$app->user->getId();

        if (Yii::$app->user->identity->role === Tasks::CUSTOMER) {
            $filter = !empty(Yii::$app->request->get('filter')) ?
            Yii::$app->request->get('filter') : Tasks::FILTER_NEW;

            $statusFilters = [
                'new' => Tasks::STATUS_NEW,
                'working' => Tasks::STATUS_WORKING,
                'closed' => [Tasks::STATUS_CANCELED, Tasks::STATUS_DONE, Tasks::STATUS_FAILED],
            ];

            $tasks = Task::find()
            ->where(['task.user_id' => $idCurrent, 'task.status' => $statusFilters[$filter]])
            ->joinWith(['category', 'city', 'responses'])
            ->all();
        }

        if (Yii::$app->user->identity->role === Tasks::EXECUTOR) {
            $filter = !empty(Yii::$app->request->get('filter')) ?
            Yii::$app->request->get('filter') : Tasks::FILTER_WORKING;

            $statusFilters = [
                'working' => Tasks::STATUS_WORKING,
                'overdue' => Tasks::STATUS_WORKING,
                'closed' => [Tasks::STATUS_DONE, Tasks::STATUS_FAILED],
            ];

            $tasks = Task::find()
            ->where(['response.user_id' => $idCurrent, 'task.status' => $statusFilters[$filter]])
            ->joinWith(['category', 'city', 'responses'])
            ->all();
        }

        if ($filter === Tasks::FILTER_OVERDUE) {
            $tasks = Task::find()
            ->where(['task.user_id' => $idCurrent, 'task.status' => $statusFilters[$filter]])
            ->joinWith(['category', 'city', 'responses'])
            ->andWhere('runtime < CURDATE()')
            ->all();
        }

        return $this->render('my', [
            'tasks' => $tasks,
            'filter' => $filter
        ]);
    }
}
