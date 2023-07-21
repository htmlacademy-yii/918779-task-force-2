<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\authclient\widgets\AuthChoice;

$this->title = 'Начальная страница';

?>

<section class="modal enter-form form-modal" id="enter-form">
    <h2>Вход на сайт</h2>
    <?php $form = ActiveForm::begin(['id' => 'login-form']) ?>
        <p>
         <?= $form->field($model, 'email', [
            'enableAjaxValidation' => true])->input('email', [
            'class' => 'enter-form-email input input-middle'])->label('Email', [
            'class' => 'form-modal-description']); ?>
         </p>
         <p>
         <?= $form->field($model, 'password', [
            'enableAjaxValidation' => true])->passwordInput([
            'class' => 'enter-form-email input input-middle'])->label('Пароль', [
            'class' => 'form-modal-description']); ?>
         </p>
         <?= Html::submitButton('Войти', ['class' => 'button']) ?>
    <?php ActiveForm::end() ?>
    <p>
        <?= yii\authclient\widgets\AuthChoice::widget([
            'baseAuthUrl' => ['site/auth'],
            'popupMode' => false,
        ]) ?>
    </p>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>
