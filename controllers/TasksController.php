<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;

use app\models\Task;
use app\models\Category;
use app\models\City;
use app\models\TaskFilterForm;
use app\models\Response;

use yii\db\Expression;
use yii\web\NotFoundHttpException;

class TasksController extends Controller {

    public function actionIndex() {

        $filter = new TaskFilterForm();

        $task = Task::find()
        ->where(['status' => Task::STATUS_NEW])
        ->joinWith(['category', 'city', 'responses'])
        ->orderBy(['creation' => SORT_DESC]);

        $request = Yii::$app->request->getIsPost();

        if ($request) {

            $filter->load(Yii::$app->request->post());
            $selected_categories = count($filter->categories);

            if ($filter->validate()) {

                if  ($selected_categories > 0) {
                    $task->andWhere(['in', 'category_id', $filter->categories]);
                }

                if ($filter->remoteWork) {
                    $task->andWhere(['city_id' => null]);
                }

                if ($filter->noResponse) {
                    $task->andWhere(['task_id' => null]);
                }

            }
        }

        settype($filter->period, 'integer');
        if ($filter->period > 0) {
            $task->andWhere(['>', 'creation', 'DATE_SUB(NOW(), INTERVAL {$filter->period} HOUR)']);
        }

        switch($filter->period) {
            case TaskFilterForm::PERIOD_HOUR:
                return $task->andWhere(['=', 'creation', 'DATE_SUB(NOW(), INTERVAL {$filter->period} HOUR)']);
                break;

            case TaskFilterForm::PERIOD_HALF_DAY:
                return $task->andWhere(['=', 'creation', 'DATE_SUB(NOW(), INTERVAL {$filter->period} HOUR)']);
                break;

            case TaskFilterForm::PERIOD_DAY:
                return $task->andWhere(['=', 'creation', 'DATE_SUB(NOW(), INTERVAL {$filter->period} HOUR)']);
                break;

          }

        $tasks = $task->all();
        $categories = Category::find()->all();

        return $this->render('index', [
            'tasks' => $tasks,
            'filter' => $filter,
            'categories' => $categories,
            'period_values' => TaskFilterForm::PERIOD_VALUES
        ]);
    }
}
