<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\CouponUses */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-uses-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border"><?=\Yii::t('frontend', 'Common')?></div>
        <div class="box-body">

        <?= $form->field($model, 'date_use')->textInput() ?>
        <?= $form->field($model, 'coupon_id')->textInput() ?>
        <?= $form->field($model, 'user_id')->textInput() ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
