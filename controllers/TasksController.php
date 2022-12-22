<?php

namespace app\controllers;

use yii;
use yii\web\Controller;

use app\models\AddTaskForm;
use app\models\TaskFilterForm;
use app\models\Task;
use app\models\Category;
use app\models\Response;

use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

use yii\web\NotFoundHttpException;
use Taskforce\Exceptions\NoAddTaskException;
use Taskforce\Exceptions\NoUploadFileException;

use yii\web\UploadedFile;
use yii\helpers\Url;

class TasksController extends AccessController {

    public function behaviors()
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['add'],
            'matchCallback' => function ($rule, $action) {
                return Yii::$app->user->identity->role !== 'customer';
            }
        ];

        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

    public function actionIndex() {

        $filter = new TaskFilterForm();

        $tasks = $filter->getTasks()->all();
        $categories = Category::find()->all();

        if (Yii::$app->request->getIsPost()) {
            $filter->load(Yii::$app->request->post());

            if ($filter->validate()) {
                $tasks = $filter->apply();
            }
        }

        return $this->render('index', [
            'tasks' => $tasks,
            'filter' => $filter,
            'categories' => $categories,
            'period_values' => TaskFilterForm::PERIOD_VALUES
        ]);
    }

    public function actionView ($id) {

        $task = Task::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException("Задача с ID $id не найдена");
        }

        $responses = Response::find()
            ->where(['task_id' => $id])
            ->all();

        return $this->render('view', [
            'task' => $task,
            'responses' => $responses,
            ]);
   }

    public function actionAdd() {

        $form = new AddTaskForm();

        $categories = ArrayHelper::map(Category::find()->all(), 'id', 'title');

        if (Yii::$app->request->getIsPost()) {
        $form->load(Yii::$app->request->post());

        $form->imageFiles = UploadedFile::getInstances($form, 'imageFiles');

            if ($form->validate()) {

                $newTask = $form->addTask();

                $transaction = \Yii::$app->db->beginTransaction();
                try {

                    $newTask->save();

                    if (!$newTask->save()){
                        throw new NoAddTaskException("Не удалось добавить задание");
                    }

                    $newAttaches = $form->uploads();
                    var_dump ($newAttaches);
                    $newAttaches->task_id = $newTask->id;
                    $newAttaches->save();

                    if (!$newAttaches->save()) {
                        throw new NoUploadFileException('Не удалось загрузить вложения');
                    }

                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    throw $e;
                }



                return $this->redirect(['tasks/view', 'id' => $newTask->id]);

            }
        }

        return $this->render('add', [
            'model' => $form,
            'categories' => $categories,
        ]);
    }
}
