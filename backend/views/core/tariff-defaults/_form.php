<?php

use core\helpers\TariffDefaultsHelper;
use kartik\widgets\DatePicker;
use kartik\widgets\TimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\TariffDefaults */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tariff-defaults-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border"><?=\Yii::t('frontend', 'Common')?></div>
        <div class="box-body">
            <?= $form->field($model, 'mb_limit')->textInput() ?>
            <?= $form->field($model, 'quantity_incoming_traffic')->textInput() ?>
            <?= $form->field($model, 'quantity_outgoing_traffic')->textInput() ?>

            <?= $form->field($model, 'file_path')->textarea(['rows' => 4]) ?>
            <?= $form->field($model, 'ip_quantity')->textInput() ?>
            <?= $form->field($model, 'type')->dropDownList(TariffDefaultsHelper::statusList()) ?>
            <?= $form->field($model, 'extend_days')->textInput() ?>
            <?= $form->field($model, 'extend_hours')->textInput() ?>
            <?= $form->field($model, 'extend_minutes')->textInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(\Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
