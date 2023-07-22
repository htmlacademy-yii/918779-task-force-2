<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use kartik\rating\StarRating;

$this->title = 'Профиль пользователя';

?>
<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main"><?= Html::encode($user->name); ?></h3>
        <div class="user-card">
            <div class="photo-rate">
                <img class="card-photo" src="/<?= Html::encode(Yii::$app->user->getIdentity()->avatar); ?>"
                width="191" height="191" alt="Фото пользователя">
                <div class="card-rate">
                    <?php echo StarRating::widget([
                        'name' => 'stars-rating-big',
                        'value' => $user->stats,
                        'pluginOptions' => [
                            'filledStar' => '<img src="/img/star-fill.svg"></img>',
                            'emptyStar' => '<img src="/img/star-empty.svg"></img>',
                            'size' => 'sm',
                            'step' => 0.1,
                            'readonly' => true,
                            'showClear' => false,
                            'showCaption' => false,
                        ],
                    ]); ?>
                    <span class="current-rate"><?= Html::encode($user->stats); ?></span>
                </div>
            </div>
            <p class="user-description">
            <?= Html::encode($user->info); ?>
            </p>
        </div>
        <div class="specialization-bio">
            <div class="specialization">
                <p class="head-info">Специализации</p>
                <ul class="special-list">
                    <?php foreach ($user->categories as $category) : ?>
                        <li class="special-item">
                            <a href="<?= Url::to(['tasks/', 'TaskFilterForm[categories][]' => $category->id]); ?>"
                            class="link link--regular"><?= Html::encode($category->title); ?></a>
                        </li>
                    <?php endforeach; ?>                    
                </ul>
            </div>
            <div class="bio">
                <p class="head-info">Био</p>
                <p class="bio-info"><span class="country-info">Россия</span>, 
                <span class="town-info"><?= Html::encode($user->city->title); ?></span>,
                <span class="age-info"><?= Html::encode($user->userAge);?></span> лет</p>
            </div>
        </div>
        <?php
            echo ListView::widget([
                'dataProvider' => $reviews,
                'itemView' => 'reviewsList',
                'layout' =>  '<h4 class="head-regular">Отзывы заказчиков</h4>{items}',
                'emptyText' => false,
            ]);
            ?>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <h4 class="head-card">Статистика исполнителя</h4>
            <dl class="black-list">
                    <dt>Всего заказов</dt>
                    <dd><?= Html::encode($user->userStats['count']); ?> выполнено, 
                    <?= Html::encode($user->userStats['failed']); ?> провалено</dd>
                    <dt>Место в рейтинге</dt>
                    <dd><?= Html::encode($user->userStats['position']); ?> место</dd>
                    <dt>Дата регистрации</dt>
                    <dd><?= Yii::$app->formatter->asDatetime($user->registration); ?></dd>
                    <dt>Статус</dt>
                    <dd><?= Html::encode($user->userStatus); ?></dd>
            </dl>
        </div>
        <?php if (Yii::$app->user->getIdentity()->contacts !== 'hide') : ?>
        <div class="right-card white">
            <h4 class="head-card">Контакты</h4>
            <ul class="enumeration-list">
                <li class="enumeration-item">
                    <a href="tel:<?= Html::encode($user->phone); ?>" 
                    class="link link--block link--phone"><?= Html::encode($user->phone); ?></a>
                </li>
                <li class="enumeration-item">
                    <a href="mailto:<?= Html::encode($user->email); ?>" 
                    class="link link--block link--email"><?= Html::encode($user->email); ?></a>
                </li>
                <li class="enumeration-item">
                    <a href="https://t.me/<?= Html::encode($user->telegram); ?>" 
                    class="link link--block link--tg"><?= Html::encode($user->telegram); ?></a>
                </li>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</main>
