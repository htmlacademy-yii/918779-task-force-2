<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\widgets\ActionWidget;
use yii\widgets\ListView;
use kartik\rating\StarRating;

$this->registerJsFile('https://api-maps.yandex.ru/2.1/?apikey=e666f398-c983-4bde-8f14-e3fec900592a&lang=ru_RU');
$this->registerJsFile('/js/map.js');

$this->title = 'Просмотр задания';
?>

<main class="main-content container">
    <div class="left-column">
        <div class="head-wrapper">
            <h3 class="head-main"><?= $task->title; ?></h3>
            <p class="price price--big"><?= Html::encode($task->estimate) ?> ₽</p>
        </div>
        <p class="task-description">
            <?= Html::encode($task->description) ?>
        </p>
        <?php if(!empty($action))
            {
            echo ActionWidget::widget(['action' => $action]);
            }
        ?>

        <div class="task-map">
            <div class="map" id="map" style="width: 725px; height: 346px"></div>
            <input id="lat" type="hidden" value="<?= HTML::encode($task->lat); ?>">
            <input id="lng" type="hidden" value="<?= HTML::encode($task->lng); ?>">
            <p class="map-address town"><?= HTML::encode($task->city); ?></p>
            <p class="map-address"><?= HTML::encode($task->location); ?></p>
        </div>

        <?php
            echo ListView::widget([
                'dataProvider' => $responses,
                'itemView' => 'responsesList',
                'layout' =>  '<h4 class="head-regular">Отклики на задание</h4>{items}',
                'emptyText' => false,
            ]);
        ?>
    </div>

    <div class="right-column">
        <div class="right-card black info-card">
            <h4 class="head-card">Информация о задании</h4>
            <dl class="black-list">
                <dt>Категория</dt>
                <dd><?= Html::encode($task->category->title) ?></dd>
                <dt>Дата публикации</dt>
                <dd><?= Yii::$app->formatter->asRelativeTime($task->creation); ?></dd>
                <dt>Срок выполнения</dt>
                <!-- <dd>15 октября, 13:00</dd> -->
                <dd><?= Yii::$app->formatter->asDateTime($task->runtime); ?></dd>
                <dt>Статус</dt>
                <!-- <dd>Новое</dd> -->
                <dd><?= Html::encode($status) ?></dd>
            </dl>
        </div>
        <div class="right-card white file-card">
            <h4 class="head-card">Файлы задания</h4>
            <ul class="enumeration-list">
                <?php foreach($attachments as $attachment): ?>
                <li class="enumeration-item">
                    <a href="<?= Url::to(['/uploads', 'id' => $attachment->path]); ?>" class="link link--block link--clip"><?= Html::encode($attachment->title) ?></a>
                    <p class="file-size"><?= Yii::$app->formatter->asShortSize($attachment->size); ?></p>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</main>

<section class="pop-up pop-up--refusal pop-up--close">
    <div class="pop-up--wrapper">
    <?php $form = ActiveForm::begin([
                    'id' => 'pop-up--refusal',
                ]);
                ?>
        <h4>Отказ от задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отказаться от выполнения этого задания.<br>
            Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
        </p>
        <a href="<?= Url::to(['tasks/reject', 'id' => $task->id]); ?>" class="button button--pop-up button--orange">Отказаться</a>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    <?php ActiveForm::end(); ?>
    </div>
</section>

<section class="pop-up pop-up--completion pop-up--close">
    <div class="pop-up--wrapper">
    <?php $form = ActiveForm::begin([
                    'id' => 'pop-up--completion',
                ]);
                ?>
        <h4>Завершение задания</h4>
        <p class="pop-up-text">
            Вы собираетесь отметить это задание как выполненное.
            Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
        </p>
        <div class="completion-form pop-up--form regular-form">
            <form>
            <?php echo $form->field($newReview, 'comment', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
            ->textArea(); ?>
                <p class="completion-head control-label">Оценка работы</p>

                <?php echo StarRating::widget([
                    'model' => $newReview,
                    'attribute' => 'stats',
                    'pluginOptions' => [
                        'step' => '1',
                        'filledStar' => '<img src="/img/star-fill.svg"></img>',
                        'emptyStar' => '<img src="/img/star-empty.svg"></img>',
                        'showClear' => false,
                        'showCaption' => false,
                    ], ]);?>
                <?=$form->field($newReview, 'task_id', ['template' => '{input}', 'options' => ['tag' => false]])->hiddenInput(['value' => $task->id]);?>
                <?= Html::submitInput('Завершить', ['class' => 'button button--pop-up button--pink']); ?>
            </form>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</section>

<section class="pop-up pop-up--act_response pop-up--close">
    <div class="pop-up--wrapper">
    <?php $form = ActiveForm::begin([
                    'id' => 'pop-up--act_response',
                ]);
                ?>
        <h4>Добавление отклика к заданию</h4>
        <p class="pop-up-text">
            Вы собираетесь оставить свой отклик к этому заданию.
            Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
        </p>
        <div class="addition-form pop-up--form regular-form">
            <form>
            <?php echo $form->field($newResponse, 'comment', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
            ->textArea(); ?>
                <?php echo $form->field($newResponse, 'price', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
            ->textInput(); ?>
                <?= Html::submitInput('Откликнуться', ['class' => 'button button--pop-up button--blue']); ?>
            </form>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    <?php ActiveForm::end(); ?>
    </div>
</section>
<div class="overlay"></div>
