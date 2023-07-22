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

        /* @var $auth Auth */
        $auth = Auth::find()->where([
        'source' => $client->getId(),
        'source_id' => $attributes['id'],
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // Авторизация
                $user = $auth->user;
                Yii::$app->user->login($user);
                $this->redirect('/tasks');
            } else { // регистрация
                if (isset($attributes['email']) && User::find()->where(['email' => $attributes['email']])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "Пользователь с такой электронной почтой как в {client} уже существует,
                        но с ним не связан. Для начала войдите на сайт использую электронную почту для того,
                        чтобы связать её.", ['client' => $client->getTitle()]),
                    ]);
                } else {
                    $password = Yii::$app->security->generateRandomString(6);
                    $user = new User();
                    if (isset($attributes['first_name'], $attributes['last_name'])) {
                        $user->name = implode(' ', array($attributes['first_name'], $attributes['last_name']));
                    }
                    if (isset($attributes['email'])) {
                        $user->email = $attributes['email'];
                    } else {
                        $user->email = $attributes['id'] . '@taskforce.com';
                    }
                    $user->password = Yii::$app->security->generatePasswordHash($password);
                    $user->role = 'customer';
                    $user->city_id = $attributes['city']['id'];
                    $user->avatar = $attributes['photo'];
                    $user->contacts = User::SHOW_CONTACTS;
                    $user->token = $attributes['id'];
                    $birthdayDate = \DateTime::createFromFormat('d.m.Y', $attributes['bdate']);
                    $user->birthday = $birthdayDate ? $birthdayDate->format('Y-m-d') : '2000-01-01';

                    if ($user->save()) {
                        $auth = new Auth([
                            'user_id' => $user->id,
                            'source' => $client->getId(),
                            'source_id' => $attributes['id'],
                            ]);

                        if ($auth->save()) {
                            Yii::$app->user->login($user);
                            $this->redirect('/tasks');
                        }
                    }
                }
            }
        }
    }
}
