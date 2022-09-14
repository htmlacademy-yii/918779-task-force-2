<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use app\models\TaskFilterForm;
use app\models\Category;
use yii\web\NotFoundHttpException;

class TasksController extends Controller {

    public function actionIndex() {

        $filter = new TaskFilterForm();

        $tasks = $filter->getTasks()->all();

        $request = Yii::$app->request->getIsPost();

        if ($request) {

            $filter->load(Yii::$app->request->post());

            if ($filter->validate()) {

               $tasks = $filter->apply();

            }
        }

        return $this->render('index', [
            'tasks' => $tasks,
            'filter' => $filter,
            'categories' => TaskFilterForm::getCategories(),
            'period_values' => TaskFilterForm::PERIOD_VALUES
        ]);
    }
}
