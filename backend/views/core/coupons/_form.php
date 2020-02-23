<?php

use core\helpers\CouponsHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\Coupons */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupons-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border"><?=\Yii::t('frontend', 'Common')?></div>
        <div class="box-body">

        <?= $form->field($model, 'number')->textInput() ?>
        <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'per_cent')->textInput() ?>
        <?= $form->field($model, 'type')->dropDownList(CouponsHelper::typeList()) ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(\Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
