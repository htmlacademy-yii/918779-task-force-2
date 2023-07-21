<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->registerCssFile('https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('/js/autoComplete.js');

$this->title = 'Создание задания';

?>

<main class="main-content main-content--center container">
    <div class="add-task-form regular-form">
        <?php $form = ActiveForm::begin([
            'id' => 'add-task',
        ]);
        ?>
            <h3 class="head-main head-main">Публикация нового задания</h3>

            <?php
                echo $form->field($model, 'title', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
                ->textInput();
                echo $form->field($model, 'description', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
                ->textarea();
                echo $form->field($model, 'category_id', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])->dropDownList($categories);
                echo $form->field($model, 'location', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
                ->textInput(['class' => 'location-icon', 'id' => 'autoComplete', 'type' => 'search', 'dir' => 'ltr', 'spellcheck' => 'false', 'autocorrect' => 'off', 'autocomplete' => 'off', 'autocapitalize' => 'off']);
                echo $form->field($model, 'lat', ['template' => "{input}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])->hiddenInput();
                echo $form->field($model, 'lng', ['template' => "{input}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])->hiddenInput();
            ?>

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
