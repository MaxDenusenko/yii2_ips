<?php

use core\forms\manage\User\UserCreateForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model UserCreateForm */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box">
        <div class="box-body">

            <?= $form->field($model, 'username')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'email')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'password')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'full_name')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'telegram')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'gabber')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'tariff_reminder')->textInput(['maxLength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
