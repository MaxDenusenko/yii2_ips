<?php

use core\entities\User\User;
use core\forms\manage\User\UserEditForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model UserEditForm */
/* @var $user User */

$this->title = \Yii::t('frontend', 'Update User').': ' . $user->username;
$this->params['breadcrumbs'][] = ['label' =>  \Yii::t('frontend', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->id, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = \Yii::t('frontend', 'Update');
?>
<div class="user-update">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box">
        <div class="box-header with-border"><?=\Yii::t('frontend', 'Common')?></div>
        <div class="box-body">

            <?= $form->field($model, 'username')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'email')->textInput(['maxLength' => true]) ?>

            <?= $form->field($model, 'full_name')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'telegram')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'gabber')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'tariff_reminder')->textInput(['maxLength' => true]) ?>

        </div>
    </div>

    <div class="box">
        <div class="box-header with-border"><?=\Yii::t('frontend', 'Add Tariff')?></div>
        <div class="box-body">

            <?= $form->field($model->tariffs, 'list')->dropDownList($model->tariffs->tariffsList(), ['prompt'=>'Выберете тариф']); ?>
            <?= $form->field($model->tariffs, 'payment_method_id')->dropDownList($model->tariffs->paymentMethodList(), ['prompt'=>'Выберете метод оплаты']); ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(\Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
