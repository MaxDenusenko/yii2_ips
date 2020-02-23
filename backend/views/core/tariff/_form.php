<?php

use core\entities\Core\CategoryTariffs;
use core\entities\Core\Currency;
use core\entities\Core\Tariff;
use core\forms\manage\Core\TariffForm;
use core\helpers\CurrencyHelper;
use kartik\money\MaskMoney;
use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Tariff */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tariff-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border"><?=\Yii::t('frontend', 'Common')?></div>
        <div class="box-body">
            <?= $form->field($model, 'number')->textInput() ?>

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
            <?= $form->field($model, 'category_id')->dropDownList(CategoryTariffs::parentCategoriesList(), ['prompt' => \Yii::t('frontend', 'Choose a category')]) ?>

        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border"><?=\Yii::t('frontend', 'Lang attribute')?></div>
        <div class="box-body">

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <?php foreach (Yii::$app->getModule('languages')->languages as $k => $language) : ?>
                        <li class="<?=$language == Yii::$app->sourceLanguage ? 'active' : '';?>"><a href="#tab_<?=$k?>" data-toggle="tab"><?=$language?></a></li>
                    <?php endforeach; ?>
                </ul>
                <div class="tab-content">
                    <?php foreach (Yii::$app->getModule('languages')->languages as $k => $language) : ?>
                        <div class="tab-pane <?=$language == Yii::$app->sourceLanguage ? 'active' : '';?>" id="tab_<?=$k?>">
                            <?= $form->field($model, $language == Yii::$app->sourceLanguage ? 'name' : "name_$language")->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, $language == Yii::$app->sourceLanguage ? 'description' : "description_$language")->widget(Widget::className(), [
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
                    <?php endforeach; ?>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>


        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border"><?=\Yii::t('frontend', 'Simple default')?></div>
        <div class="box-body">

            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model->defaultComposite, 'mb_limit')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->defaultComposite, 'quantity_incoming_traffic')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->defaultComposite, 'quantity_outgoing_traffic')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->defaultComposite, 'ip_quantity')->textInput() ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model->defaultComposite, 'file_path')->textarea(['rows' => 4]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model->defaultComposite, 'extend_days')->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model->defaultComposite, 'extend_hours')->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model->defaultComposite, 'extend_minutes')->textInput() ?>
                </div>
            </div>

        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border"><?=\Yii::t('frontend', 'Trial default')?></div>
        <div class="box-body">

            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model->defaultTrialComposite, 'mb_limit')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->defaultTrialComposite, 'quantity_incoming_traffic')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->defaultTrialComposite, 'quantity_outgoing_traffic')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->defaultTrialComposite, 'ip_quantity')->textInput() ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model->defaultTrialComposite, 'file_path')->textarea(['rows' => 4]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model->defaultTrialComposite, 'extend_days')->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model->defaultTrialComposite, 'extend_hours')->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model->defaultTrialComposite, 'extend_minutes')->textInput() ?>
                </div>
            </div>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(\Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
