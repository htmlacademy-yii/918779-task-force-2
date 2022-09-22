<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;

use app\models\Task;
use app\models\Category;
use app\models\City;
use app\models\TaskFilterForm;
use app\models\Response;
use yii\helpers\ArrayHelper;

use yii\db\Expression;
use yii\web\NotFoundHttpException;

class TasksController extends Controller {

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
}
