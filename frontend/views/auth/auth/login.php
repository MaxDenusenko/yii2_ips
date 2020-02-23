<?php

/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $model LoginForm */

use core\forms\auth\LoginForm;
use himiklab\yii2\recaptcha\ReCaptcha2;
use macgyer\yii2materializecss\lib\Html;
use macgyer\yii2materializecss\widgets\form\ActiveForm;

$this->title = \Yii::t('frontend', 'Sign In');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?=\Yii::t('frontend', 'Please fill in the following entry fields')?>:</p>
    <br>

    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'rememberMe')->checkbox() ?>

    <?= $form->field($model, 'reCaptcha')->widget(ReCaptcha2::className()) ?>

    <div style="color:#999;margin:1em 0">
        <?=\Yii::t('frontend', 'If you have forgotten your password, you can')?> <?= Html::a(\Yii::t('frontend', 'reset him'), ['/auth/reset/request-password-reset']) ?>.
        <br>
        <?=\Yii::t('frontend', 'Need a new confirmation email?')?> <?= Html::a(\Yii::t('frontend', 'Send'), ['/auth/signup/resend-verification-email']) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(\Yii::t('frontend', 'Sign In'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
