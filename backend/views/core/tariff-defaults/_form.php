<?php

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
        <div class="box-header with-border">Common</div>
        <div class="box-body">
            <?= $form->field($model, 'name')->textInput() ?>
            <?= $form->field($model, 'mb_limit')->textInput() ?>
            <?= $form->field($model, 'quantity_incoming_traffic')->textInput() ?>
            <?= $form->field($model, 'quantity_outgoing_traffic')->textInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
