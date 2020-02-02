<?php

use core\entities\Core\Currency;
use core\forms\manage\Core\TariffForm;
use core\helpers\CurrencyHelper;
use kartik\money\MaskMoney;
use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model TariffForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tariff-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border">Common</div>
        <div class="box-body">
            <?= $form->field($model, 'number')->textInput() ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'price')->widget(MaskMoney::classname(), [
                'pluginOptions' => [
                    'prefix' => CurrencyHelper::getBaseSymbol().' ',
                    'thousands' => '.',
                    'decimal' => ',',
                    'precision' => 2,
                    'allowZero' => false,
                    'allowEmpty' => false
                ],
            ]); ?>
            <?= $form->field($model, 'proxy_link')->textInput() ?>
            <?= $form->field($model, 'price_for_additional_ip')->textInput() ?>
            <?= $form->field($model, 'qty_proxy')->textInput() ?>
            <?= $form->field($model, 'category_id')->dropDownList($model->categoryList(), ['prompt'=>'Выберете категорию']) ?>

            <?= $form->field($model, 'description')->widget(Widget::className(), [
                'settings' => [
                    'lang' => 'ru',
                    'minHeight' => 200,
                    'plugins' => [
                        'clips',
                        'fullscreen',
                    ],
                    'clips' => [
                        ['red', '<span class="label-red">red</span>'],
                        ['green', '<span class="label-green">green</span>'],
                        ['blue', '<span class="label-blue">blue</span>'],
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Simple default</div>
        <div class="box-body">

            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model->default, 'mb_limit')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->default, 'quantity_incoming_traffic')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->default, 'quantity_outgoing_traffic')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->default, 'ip_quantity')->textInput() ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model->default, 'file_path')->textarea(['rows' => 4]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model->default, 'extend_days')->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model->default, 'extend_hours')->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model->default, 'extend_minutes')->textInput() ?>
                </div>
            </div>

        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Trial default</div>
        <div class="box-body">

            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model->defaultTrial, 'mb_limit')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->defaultTrial, 'quantity_incoming_traffic')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->defaultTrial, 'quantity_outgoing_traffic')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->defaultTrial, 'ip_quantity')->textInput() ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model->defaultTrial, 'file_path')->textarea(['rows' => 4]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model->defaultTrial, 'extend_days')->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model->defaultTrial, 'extend_hours')->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model->defaultTrial, 'extend_minutes')->textInput() ?>
                </div>
            </div>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
