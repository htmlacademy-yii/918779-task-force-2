<?php

namespace app\controllers;

use yii;

use yii\web\Controller;

use app\models\RegistrationForm;
use app\models\City;

use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

class RegistrationController extends Controller {

    public function actionIndex()
    {
        $form = new RegistrationForm();

        $city = ArrayHelper::map(City::find()->all(), 'id', 'title');

        if (Yii::$app->request->getIsPost()) {
            $form->load(Yii::$app->request->post());

            if ($form->validate()) {
                $form->registration();
                $this->goHome();
            }
        }

        return $this->render('index', [
            'model' => $form,
            'city' => $city,
        ]);
    }
}
