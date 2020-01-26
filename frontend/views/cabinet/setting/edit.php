<?php

/** @var UserEditForm $form */
/** @var User $user */

use core\entities\User\User;
use core\forms\manage\User\UserEditForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>

<div class="row">
    <div class="col-lg-12">
        <?php $form = ActiveForm::begin(['id' => 'user-edit-form']); ?>

        <?= $form->field($model, 'username')->textInput(['maxLength' => true]) ?>
        <?= $form->field($model, 'email')->textInput(['maxLength' => true]) ?>

        <?= $form->field($model, 'telegram')->textInput(['maxLength' => true]) ?>
        <?= $form->field($model, 'gabber')->textInput(['maxLength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
