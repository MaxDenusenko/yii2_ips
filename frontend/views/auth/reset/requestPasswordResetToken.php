<?php

/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $model PasswordResetRequestForm */

use core\forms\auth\PasswordResetRequestForm;
use himiklab\yii2\recaptcha\ReCaptcha2;
use macgyer\yii2materializecss\lib\Html;
use macgyer\yii2materializecss\widgets\form\ActiveForm;

$this->title = \Yii::t('frontend', 'Password Reset Request');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?=\Yii::t('frontend', 'Please fill in your email. A password reset link will be sent there.')?></p>

    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'reCaptcha')->widget(ReCaptcha2::className()) ?>

    <div class="form-group">
        <?= Html::submitButton(\Yii::t('frontend', 'Send'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
