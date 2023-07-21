<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\SettingsForm;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Мой профиль';
?>

<main class="main-content main-content--left container">
    <div class="left-menu left-menu--edit">
        <h3 class="head-main head-task">Настройки</h3>
        <ul class="side-menu-list">
            <li class="side-menu-item <?= $type !== SettingsForm::SECURITY ? 'side-menu-item--active' : ''; ?>">
                <a href = "<?= Url::to(['/user/settings', 'type' => SettingsForm::PROFILE]); ?>" class="link link--nav">Мой профиль</a>
            </li>
            <li class="side-menu-item <?= $type === SettingsForm::SECURITY ? 'side-menu-item--active' : ''; ?>">
                <a href = "<?= Url::to(['/user/settings', 'type' => SettingsForm::SECURITY]); ?>" class="link link--nav">Безопасность</a>
            </li>
        </ul>
    </div>
    <div class="my-profile-form">
        <?php $form = ActiveForm::begin([
            'id' => 'edit-profile-form',
        ]);
        ?>
            <h3 class="head-main head-regular"><?= $type === SettingsForm::SECURITY ? 'Безопасность' : 'Мой профиль'; ?></h3>
            
            <?php if ($type !== SettingsForm::SECURITY) : ?>

            <div class="photo-editing">
                <div>
                    <p class="form-label">Аватар</p>
                    <img class="avatar-preview" src="/<?= Html::encode(Yii::$app->user->getIdentity()->avatar); ?>" width="83" height="83">
                </div>
                <?= $form->field($model, 'avatar', ['template' => '{input}{label}'])
                ->fileInput(['id' => 'button-input', 'hidden' => 'hidden'])
                ->label('Сменить аватар', ['for' => 'button-input', 'class' => 'button button--black']); ?>
            </div>
            
            <?php echo $form->field($model, 'name', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
            ->textInput();
            ?>
            <div class="half-wrapper">
                <?php echo $form->field($model, 'email', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label'], 'errorOptions' => ['class' => 'help-block',]])
                ->input('email');
                ?>
                <?php echo $form->field($model, 'birthday', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label'], 'errorOptions' => ['class' => 'help-block',]])
                ->input('date');
                ?>
            </div>
            <div class="half-wrapper">
                <?php echo $form->field($model, 'phone', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label'], 'errorOptions' => ['class' => 'help-block',]])
                ->input('tel');
                ?>
                <?php
                echo $form->field($model, 'telegram', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
                ->textInput();
                ?>
            </div>
            <?php 
            echo $form->field($model, 'info', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
            ->textarea();
            ?>
            <div class="form-group">
                <?php echo $form->field($model, 'categories', ['template' => '{input}'])->checkboxList(
                    ArrayHelper::map($categories, 'id', 'title'),
                    [
                        'class' => 'checkbox-profile',
                        'itemOptions' => [
                            'labelOptions' => [
                                'class' => 'control-label',
                            ],
                        ],
                    ])
                ?>
            </div>

            
            <?php endif; ?>

            <?php if ($type === SettingsForm::SECURITY) : ?>

                <?php echo $form->field($model, 'current_password', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
                ->passwordInput();
                ?>

                <?php echo $form->field($model, 'new_password', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
                ->passwordInput();
                ?>
                
                <?php echo $form->field($model, 'repeat_password', ['template' => "{label}\n{input}\n{error}", 'options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label']])
                ->passwordInput();
                ?>

                <?php echo $form->field($model, 'contacts', ['options' => ['class' => 'form-group'], 'labelOptions' => ['class' => 'control-label checkbox-label']])
                ->checkbox(['template' => "{input}\n{label}", 'checked' => false]);
                ?>

            <?php endif; ?>

            <?= Html::submitInput('Сохранить', ['class' => 'button button--blue']); ?>
        <?php ActiveForm::end(); ?>
    </div>
</main>