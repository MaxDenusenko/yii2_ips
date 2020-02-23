<?php

/** @var ProfileEditForm $model */
/** @var User $user */

use core\entities\User\User;
use core\forms\user\ProfileEditForm;
use macgyer\yii2materializecss\lib\Html;
use macgyer\yii2materializecss\widgets\form\ActiveForm;

$this->title = \Yii::t('frontend', 'Editing');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Personal')];
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Profile'), 'url' => ['cabinet/setting']];
$this->params['breadcrumbs'][] = $this->title;

?>

<br>
<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin(['id' => 'user-edit-form']); ?>

        <?= $form->field($model, 'username')->textInput(['maxLength' => true]) ?>
        <?= $form->field($model, 'email')->textInput(['maxLength' => true]) ?>

        <?= $form->field($model, 'telegram')->textInput(['maxLength' => true]) ?>
        <?= $form->field($model, 'gabber')->textInput(['maxLength' => true]) ?>
        <?= $form->field($model, 'tariff_reminder')->textInput(['maxLength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton(\Yii::t('frontend', 'Save'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
