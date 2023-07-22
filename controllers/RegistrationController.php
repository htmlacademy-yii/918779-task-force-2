<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\RegistrationForm;
use app\models\City;
use yii\web\Response;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;

class RegistrationController extends Controller
{
    public function actionIndex()
    {

        $city = ArrayHelper::map(City::find()->all(), 'id', 'title');

        $form = new RegistrationForm();

        if (Yii::$app->request->getIsPost()) {
            $form->load(Yii::$app->request->post());
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($form);
            }
            if ($form->validate()) {
                $form->registration();
                return $this->goHome();
            }
        }

        return $this->render('index', [
            'model' => $form,
            'city' => $city,
        ]);
    }
}
