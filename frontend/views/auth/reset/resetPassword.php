<?php

/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $model ResetPasswordForm */

use core\forms\auth\ResetPasswordForm;
use kartik\password\PasswordInput;
use macgyer\yii2materializecss\lib\Html;
use macgyer\yii2materializecss\widgets\form\ActiveForm;

$this->title = \Yii::t('frontend', 'Password reset');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?=\Yii::t('frontend', 'Please select a new password')?>:</p>

    <br>

    <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

    <?= $form->field($model, 'password')->widget(PasswordInput::classname(), [
        'pluginOptions' => [
            'showMeter' => true,
            'toggleMask' => false
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(\Yii::t('frontend', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
