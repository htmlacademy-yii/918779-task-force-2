<?php
use yii\helpers\Html;
use yii\helpers\Url;
use Taskforce\Tasks;

$this->title = 'Мои задания';
$head_new = 'Новые задания';
$head_working = 'Задания в процессе выполнения';
$head_overdue = 'Просроченные задания';
$head_closed = 'Завершенные задания';
?>

<main class="main-content container">
    <div class="left-menu">
        <h3 class="head-main head-task"><?= $this->title; ?></h3>
        <ul class="side-menu-list">
            <?php if (Yii::$app->user->identity->role === Tasks::CUSTOMER) : ?>
            <li class="side-menu-item <?= $filter === Tasks::FILTER_NEW || empty($filter) ? 'side-menu-item--active' : ''; ?>">
                <a href = "<?= Url::to(['/tasks/my', 'filter' =>Tasks::FILTER_NEW]); ?>" class="link link--nav">Новые</a>
            </li>
            <?php endif; ?>
            <li class="side-menu-item <?= $filter === Tasks::FILTER_WORKING ? 'side-menu-item--active' : ''; ?>">
                <a href = "<?= Url::to(['/tasks/my', 'filter' => Tasks::FILTER_WORKING]); ?>" class="link link--nav">В процессе</a>
            </li>
            <?php if (Yii::$app->user->identity->role === Tasks::EXECUTOR) : ?>
            <li class="side-menu-item <?= $filter === Tasks::FILTER_OVERDUE ? 'side-menu-item--active' : ''; ?>">
                <a href = "<?= Url::to(['/tasks/my', 'filter' => Tasks::FILTER_OVERDUE]); ?>" class="link link--nav">Просрочено</a>
            </li>
            <?php endif; ?>
            <li class="side-menu-item <?= $filter === Tasks::FILTER_CLOSED ? 'side-menu-item--active' : ''; ?>">
                <a href = "<?= Url::to(['/tasks/my', 'filter' => Tasks::FILTER_CLOSED]); ?>" class="link link--nav">Закрытые</a>
            </li>
        </ul>       
    </div>
    <div class="left-column left-column--task">
        <?php if ($filter === Tasks::FILTER_NEW) : ?>
            <h3 class="head-main head-regular"><?= $head_new; ?></h3>
        <?php endif; ?>
        <?php if ($filter === Tasks::FILTER_WORKING) : ?>
            <h3 class="head-main head-regular"><?= $head_working; ?></h3>
        <?php endif; ?>
        <?php if ($filter === Tasks::FILTER_OVERDUE) : ?>
            <h3 class="head-main head-regular"><?= $head_overdue; ?></h3>
        <?php endif; ?>
        <?php if ($filter === Tasks::FILTER_CLOSED) : ?>
            <h3 class="head-main head-regular"><?= $head_closed; ?></h3>
        <?php endif; ?>

        <?php foreach ($tasks as $task): ?>
            <div class="task-card">
                <div class="header-task">
                    <a  href="<?= Url::to(['/tasks/view', 'id' => $task->id]); ?>" class="link link--block link--big"><?= Html::encode($task->title) ?></a>
                    <p class="price price--task"><?= Html::encode($task->estimate) ?> ₽</p>
                </div>
                <p class="info-text"><span class="current-time">
                    <?= Yii::$app->formatter->format(
                        $task->creation,
                        'relativeTime'
                    ) ?></p>
                <p class="task-text"><?= Html::encode($task->description) ?></p>
                <div class="footer-task">
                    <p class="info-text town-text">
                        <?php if (isset($task->city->title)): ?>
                            <?= Html::encode($task->city->title); ?>
                        <?php else: ?>
                            Удаленная работа
                        <?php endif; ?></p>
                    <p class="info-text category-text"><?= Html::encode($task->category->title) ?></p>
                    <a href="<?= Url::to(['/tasks/view', 'id' => $task->id]); ?>" class="button button--black">Смотреть Задание</a>
                </div>
            </div>  
        <?php endforeach; ?>
    </div>
</main>