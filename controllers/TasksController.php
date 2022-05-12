<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

use app\models\Task;
use app\models\Category;
use app\models\City;

class TasksController extends Controller
{

   const STATUS_NEW = 'new';

   public function actionIndex() {

      $tasks = Task::find()
      ->where(['status' => self::STATUS_NEW])
      ->joinWith(['category', 'city'])
      ->orderBy(['creation' => SORT_DESC])
      ->all();

      return $this->render('index', ['tasks' => $tasks]);
   }
}
