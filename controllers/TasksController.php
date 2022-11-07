<?php

namespace app\controllers;

use Yii;
use app\models\AddTaskForm;
use app\models\TaskFilterForm;
use app\models\Task;
use app\models\Category;
use app\models\Response;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class TasksController extends AccessController {

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

            if ($form->validate()) {
                $form->addTask();

                if (!$form->addTask()->save()) {
                    throw new NotAddTaskException('Не удалось загрузить обьявление');
                }

                $form->uploadAttachment();

                if (!$form->uploadAttachment()->save()) {
                    throw new NotUploadFileException('Не удалось загрузить вложение');
                }
            }
        }

        return $this->render('add', [
            'model' => $form,
            'categories' => $categories,
        ]);
    }
}
