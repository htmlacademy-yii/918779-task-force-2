<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\helpers\ArrayHelper;
    use yii\widgets\ActiveForm;
    use yii\widgets\LinkPager;

    $this->title = 'Задания';
?>
<main class="main-content container">
<div class="left-column">
      <h3 class="head-main head-task">Новые задания</h3>

         <?php foreach ($tasks as $task): ?>
         <div class="task-card">
            <div class="header-task">
                <a  href="<?= Url::to(['/tasks/view', 'id' => $task->id]); ?>" class="link link--block link--big">
                <?= Html::encode($task->title) ?>
               </a>
               <p class="price price--task"><?= Html::encode($task->estimate); ?> ₽</p>
            </div>
            <p class="info-text">
               <span class="current-time">
                  <?= Yii::$app->formatter->format(
                     $task->creation,
                     'relativeTime'
                  ) ?>
               </span>
            </p>
            <p class="task-text">
                <?= Html::encode($task->description); ?>
            </p>
            <div class="footer-task">
                <p class="info-text town-text">
                    <?php if (isset($task->city->title)): ?>
                        <?= Html::encode($task->city->title); ?>
                    <?php else: ?>
                        Удаленная работа
                    <?php endif; ?>
                </p>
                <p class="info-text category-text"><?= Html::encode($task->category->title); ?></p>
                <a href="<?= Url::to(['/tasks/view', 'id' => $task->id]); ?>" class="button button--black">Смотреть Задание</a>
            </div>
        </div>
        <?php endforeach; ?>



        <div class="pagination-wrapper">
            <?php echo LinkPager::widget([
                'pagination' => $pagination,
                'options' => ['class' => 'pagination-list'],
                'linkContainerOptions' => ['class' => 'pagination-item'],
                'linkOptions' => ['class' => 'link link--page'],
                'activePageCssClass' => 'pagination-item--active',
                'prevPageCssClass' => 'mark',
                'nextPageCssClass' => 'mark',
                'nextPageLabel' => '',
                'prevPageLabel' => '',
            ]);
            ?>
        </div>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <div class="search-form">
            <?php $form = ActiveForm::begin([
                'method' => 'post',
                'id' => 'search-form',
            ]);
            ?>
                    <h4 class="head-card">Категории</h4>
                    <div class="form-group">
                        <?php echo $form->field($filter, 'categories', ['template' => '{input}'])->checkboxList(
                            ArrayHelper::map($categories, 'id', 'title'),
                        [
                            'class' => 'checkbox-wrapper',
                            'itemOptions' => [
                                'labelOptions' => [
                                    'class' => 'control-label',
                                ],
                            ],
                        ]) ?>
                    </div>
                    <h4 class="head-card">Дополнительно</h4>
                    <div class="form-group">
                        <?php echo $form->field($filter, 'noResponse', [])->checkbox(
                        [
                            'labelOptions' => [
                            'class' => 'control-label',
                            ]
                        ]);
                        ?>
                    </div>
                    <h4 class="head-card">Период</h4>
                    <div class="form-group">
                    <?php echo $form->field($filter, 'period', ['template' => "{input}"])->dropDownList($period_values, ['id' => 'period-value']) ?>
                    </div>
                    <input type="submit" class="button button--blue" value="Искать">
                    <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</main>
