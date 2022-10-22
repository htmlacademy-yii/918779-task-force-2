<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\helpers\ArrayHelper;
    use yii\widgets\ActiveForm;

    $this->title = 'Задания';
?>
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
            <ul class="pagination-list">
                <li class="pagination-item mark">
                    <a href="#" class="link link--page"></a>
                </li>
                <li class="pagination-item">
                    <a href="#" class="link link--page">1</a>
                </li>
                <li class="pagination-item pagination-item--active">
                    <a href="#" class="link link--page">2</a>
                </li>
                <li class="pagination-item">
                    <a href="#" class="link link--page">3</a>
                </li>
                <li class="pagination-item mark">
                    <a href="#" class="link link--page"></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <div class="search-form">
            <?php $form = ActiveForm::begin([
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
