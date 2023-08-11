<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\User;
use app\models\Auth;
use yii\authclient\clients\VKontakte;
use Taskforce\VK;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        return $this->redirect('/landing');
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Auth action.
     *
     * @return string
     */

    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();

        $vk = new Vk();
        $auth = $vk->auth($client, $attributes);

        if (Yii::$app->user->isGuest) {
            if ($auth) {
                $user = $auth->user;
                Yii::$app->user->login($user);
                $this->redirect('/tasks');
            }

            if (isset($attributes['email']) && User::find()->where(['email' => $attributes['email']])->exists()) {
                Yii::$app->getSession()->setFlash('error', [
                    Yii::t('app', "Пользователь с такой электронной почтой как в {client} уже существует,
                    но с ним не связан. Для начала войдите на сайт использую электронную почту для того,
                    чтобы связать её.", ['client' => $client->getTitle()]),
                ]);
            }

            $auth = $vk->registration($client, $attributes);
            Yii::$app->user->login($user);
            $this->redirect('/tasks');
        }
    }
}
