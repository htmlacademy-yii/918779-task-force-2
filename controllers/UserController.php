<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;

use app\models\User;
use app\models\Task;
use app\models\Category;
use app\models\Response;
use app\models\TaskFilterForm;

use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    public function actionView($id)
    {
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException("Пользователь с ID $id не найден");
        }

        return $this->render('view', [
            'user' => $user,
            ]);
    }

}
