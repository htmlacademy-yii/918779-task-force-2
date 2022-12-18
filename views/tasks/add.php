<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Создание задания';

?>

<main class="main-content main-content--center container">
    <div class="add-task-form regular-form">
        <?php $form = ActiveForm::begin([
            'id' => 'add-task-form',
        ]);
        ?>
            <h3 class="head-main head-main">Публикация нового задания</h3>
            <?php echo $form->field($model, 'title', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
                ->textInput();
            ?>
            <?php echo $form->field($model, 'description', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
                ->textarea();
            ?>
            <?php echo $form->field($model, 'category_id', ['template' => "{label}\n{input}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])->dropDownList($categories); ?>
            <div class="form-group">
                <label class="control-label" for="location">Локация</label>
                <input class="location-icon" id="location" type="text">
                <span class="help-block">Error description is here</span>
            </div>
            <div class="half-wrapper">
                <?php echo $form->field($model, 'estimate', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
                    ->textInput(['class' => 'budget-icon']);
                ?>
                <?php echo $form->field($model, 'runtime', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
                    ->textInput(['type' => 'date']);
                ?>
            </div>
            <p class="form-label">Файлы</p>
            <?php
              echo $form->field($model, 'imageFiles[]', ['template' => "{label}{input}", 'options' => ['class' => 'new-file']])->fileInput(['multiple' => 'multiple', 'style' => 'opacity:0; position: absolute;']);
            ?>

            <?= Html::submitInput('Опубликовать', ['class' => 'button button--blue']); ?>
        <?php ActiveForm::end(); ?>
    </div>
</main>
