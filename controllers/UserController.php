<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\Task;
use app\models\Category;
use app\models\Response;
use app\models\TaskFilterForm;
use app\models\City;
use app\models\Auth;
use yii\authclient\clients\VKontakte;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['view'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

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

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect('/landing');
    }

 }
