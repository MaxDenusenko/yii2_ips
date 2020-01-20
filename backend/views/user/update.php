<?php

use core\entities\User\User;
use core\forms\manage\User\UserEditForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model UserEditForm */
/* @var $user User */

$this->title = 'Update User: ' . $user->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->id, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box">
        <div class="box-header with-border">Common</div>
        <div class="box-body">

            <?= $form->field($model, 'username')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'email')->textInput(['maxLength' => true]) ?>

            <?= $form->field($model, 'full_name')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'telegram')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'gabber')->textInput(['maxLength' => true]) ?>

        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">Tariffs</div>
        <div class="box-body">

            <?= $form->field($model->tariffs, 'list')->widget(\kartik\select2\Select2::classname(), [
                'data' => $model->tariffs->tariffsList(),
                'options' => ['placeholder' => 'Select a tariff ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true
                ],
            ]);
            ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
