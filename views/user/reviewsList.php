<?php
use yii\helpers\Html;
use yii\helpers\Url;
use Taskforce\Tasks;
use app\models\Review;
use kartik\rating\StarRating;
?>

<div class="response-card">
    <img class="customer-photo" src="/<?= Html::encode($model->task->user->avatar); ?>" width="120" height="127" alt="Фото заказчиков">
    <div class="feedback-wrapper">
        <p class="feedback">«<?= Html::encode($model->comment); ?>»</p>
        <p class="task">Задание «<a href="#" class="link link--small"><?= Html::encode($model->task->title); ?></a>» выполнено</p>
    </div>
    <div class="feedback-wrapper">
            <?php echo StarRating::widget([
                'name' => 'stars-rating-small',
                'value' => $model->stats,
                'pluginOptions' => [
                'filledStar' => '<img src="/img/star-fill.svg"></img>',
                'emptyStar' => '<img src="/img/star-empty.svg"></img>',
                'size' => 'xs',
                'step' => 0.1,
                'readonly' => true,
                'showClear' => false,
                'showCaption' => false,
                ],
            ]); ?>
        <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($model->creation); ?></p>
    </div>
</div>
