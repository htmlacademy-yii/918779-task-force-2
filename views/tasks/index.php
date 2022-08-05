<?php
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use yii\widgets\ActiveForm;

    $this->title = 'Задания';
?>
   <div class="left-column">
      <h3 class="head-main head-task">Новые задания</h3>
      <?php if (count($tasks) > 0): ?>
         <?php foreach ($tasks as $task): ?>
         <div class="task-card">
            <div class="header-task">
               <a  href="#" class="link link--block link--big">
                  <?= $task->title ?>
               </a>
               <p class="price price--task"><?= $task->estimate ?> ₽</p>
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
               <?= $task->description ?>
            </p>
            <div class="footer-task">
                <p class="info-text town-text"><?= $task->city->title ?></p>
                <p class="info-text category-text"><?= $task->category->title ?></p>
                <a href="#" class="button button--black">Смотреть Задание</a>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

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
                'fieldConfig' => [
                    'template' => "{input}\n{label}"
                ]
            ]); ?>
                    <h4 class="head-card">Категории</h4>
                    <div class="form-group">
                    <?php foreach ($categories as $category): ?>
                       <?= $form->field($filter, 'categories[]')->checkbox(['value' => $category->id,'checked' => ArrayHelper::isIn($category->id, $filter->categories)], $enclosedByLabel = false)->label($category->title) ?>
                       <?php endforeach; ?>
                    </div>
                    <h4 class="head-card">Дополнительно</h4>
                    <div class="form-group">
                    <?= $form->field($filter, 'remoteWork')->checkbox(['value' => 1], $enclosedByLabel = false) ?>
                    <?= $form->field($filter, 'noResponse')->checkbox(['value' => 1], $enclosedByLabel = false) ?>
                    </div>
                    <h4 class="head-card">Период</h4>
                    <div class="form-group">
                    <?= $form->field($filter, 'period', ['template' => "{input}"])->dropDownList($period_values, ['id' => 'period-value']) ?>
                    </div>
                    <?= Html::submitButton('Искать', ['class' => 'button button--blue']) ?>
                    <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
