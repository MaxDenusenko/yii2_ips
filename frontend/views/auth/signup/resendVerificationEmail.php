<?php
/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $model ResetPasswordForm */

use core\forms\auth\ResetPasswordForm;
use himiklab\yii2\recaptcha\ReCaptcha2;
use macgyer\yii2materializecss\lib\Html;
use macgyer\yii2materializecss\widgets\form\ActiveForm;

$this->title = \Yii::t('frontend', 'Send confirmation email');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-resend-verification-email">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?=\Yii::t('frontend', 'Please fill in your email. A confirmation email will be sent there.')?></p>

    <br>

    <?php $form = ActiveForm::begin(['id' => 'resend-verification-email-form']); ?>

    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'reCaptcha')->widget(ReCaptcha2::className()) ?>

    <div class="form-group">
        <?= Html::submitButton(\Yii::t('frontend', 'Send'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
