<?php

use core\entities\Core\TariffAssignment;
use kartik\widgets\DatePicker;
use kartik\widgets\TimePicker;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $tariff_model core\entities\Core\TariffAssignment */
/* @var $form yii\widgets\ActiveForm */
/* @var $tariff TariffAssignment */
?>

<div class="tariff-assignment-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border">Common</div>
        <div class="box-body">
            <?= $form->field($tariff_model, 'file_path')->textarea(['rows' => 4]) ?>
            <?= $form->field($tariff_model, 'ip_quantity')->textInput() ?>
            <?= $form->field($tariff_model, 'IPs')->textarea(['rows' => 4]) ?>
            <?= $form->field($tariff_model, 'mb_limit')->textInput() ?>
            <?= $form->field($tariff_model, 'quantity_incoming_traffic')->textInput() ?>
            <?= $form->field($tariff_model, 'quantity_outgoing_traffic')->textInput() ?>
            <?= $form->field($tariff_model, 'discount')->textInput() ?>
            <?= $form->field($tariff_model, 'date_to')->widget(DatePicker::classname(), [
                'pluginOptions' => [
                    'autoclose'=>true,
                    'todayHighlight' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]); ?>
            <?= $form->field($tariff_model, 'time_to')->widget(TimePicker::classname(), [
                'pluginOptions' => [
                    'showMeridian' => false,
                ]
            ]); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
