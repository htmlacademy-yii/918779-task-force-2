<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Регистрация';

?>
<main class="container container--registration">
    <div class="center-block">
        <div class="registration-form regular-form">
            <?php $form = ActiveForm::begin([
                    'id' => 'registration-form',
                ]);
                ?>
                    <h3 class="head-main head-task">Регистрация нового пользователя</h3>
                    <?php echo $form->field($model, 'name', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
            ->textInput();
                    ?>
                    <div class="half-wrapper">
                    <?php echo $form->field($model, 'email', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label'], 'errorOptions' => ['class' => 'help-block',]])
            ->input('email');
                    ?>
                        <div class="form-group">
                            <?php echo $form->field($model, 'city_id', ['template' => "{label}\n{input}"])->dropDownList($city); ?>
                        </div>
                    </div>
                    <div class="half-wrapper">
                        <?php echo $form->field($model, 'password', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
                ->passwordInput();
                        ?>
                    </div>
                    <div class="half-wrapper">
                        <?php echo $form->field($model, 'repeat_password', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
                    ->passwordInput();
                        ?>
                    </div>
                    <?php echo $form->field($model, 'role', ['options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label checkbox-label']])
                    ->checkbox(['template' => "{input}\n{label}", 'checked' => true]);
                        ?>
                    <?= Html::submitInput('Создать аккаунт', ['class' => 'button button--blue']); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</main>
