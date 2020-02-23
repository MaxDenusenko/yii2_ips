<?php

/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $model SignupForm */

use core\forms\auth\SignupForm;
use himiklab\yii2\recaptcha\ReCaptcha2;
use kartik\password\PasswordInput;
use macgyer\yii2materializecss\lib\Html;
use macgyer\yii2materializecss\widgets\form\ActiveForm;

$this->title = \Yii::t('frontend', 'Sign Up');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?=\Yii::t('frontend', 'Please fill in the following fields for registration')?>:</p>

    <br>

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
    <?= $form->field($model, 'email') ?>
    <?= $form->field($model, 'password')->widget(PasswordInput::classname(), [
        'pluginOptions' => [
            'showMeter' => true,
            'toggleMask' => false
        ]
    ]) ?>
    <?= $form->field($model, 'password_repeat')->passwordInput() ?>
    <?= $form->field($model, 'telegram')->textInput(['autofocus' => true]) ?>
    <?= $form->field($model, 'gabber')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'reCaptcha')->widget(ReCaptcha2::className()) ?>

    <div class="form-group">
        <?= Html::submitButton(\Yii::t('frontend', 'Sign Up'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
