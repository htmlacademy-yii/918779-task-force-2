<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;
use Taskforce\Tasks;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<header class="page-header">
    <nav class="main-nav">
        <a href='<?= Url::to(['/tasks']); ?>' class="header-logo">
            <img class="logo-image" src="/img/logotype.png" width=227 height=60 alt="taskforce">
        </a>
        <?php if (!Yii::$app->user->isGuest) : ?>
        <div class="nav-wrapper">
            <?php echo Menu::widget([
            'items' => [
                ['label' => 'Новое', 'url' => ['tasks/index']],
                ['label' => 'Мои задания', 'url' => ['tasks/my']],
                ['label' => 'Создать задание', 'url' => [
                'tasks/add'], 'visible' => Yii::$app->user->identity->role === Tasks::CUSTOMER],
                ['label' => 'Настройки', 'url' => ['user/settings']],
            ],
            'options' => ['class' => 'nav-list'],
            'itemOptions' => ['class' => 'list-item'],
            'activeCssClass' => 'list-item--active',
            'linkTemplate' => '<a class="link link--nav" href="{url}">{label}</a>',
        ]);
            ?>
        </div>
        <?php endif; ?>
    </nav>
    <?php if (!Yii::$app->user->isGuest) : ?>
    <div class="user-block">
        <a href="<?= Url::to([
        '/user/view', 'id' => Yii::$app->user->getId()]); ?>">
            <img class="user-photo" src="<?= Html::encode(Yii::$app->user->getIdentity()->avatar); ?>" 
            width="55" height="55" alt="Аватар">
        </a>
        <div class="user-menu">
            <p class="user-name">
            <?php if (isset(Yii::$app->user->identity->name)) : ?>
                <?= Html::encode(Yii::$app->user->identity->name); ?>
            <?php endif; ?>
            </p>
            <div class="popup-head">
                <ul class="popup-menu">
                    <li class="menu-item">
                        <a href="<?= Url::to(['user/settings']); ?>" class="link">Настройки</a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= Url::to(['user/logout']) ?>" class="link">Выход из системы</a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>
</header>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
