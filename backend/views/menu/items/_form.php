<?php

use core\entities\Core\Menu;
use core\forms\manage\Core\MenuFormItem;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model MenuFormItem */
/* @var $form yii\widgets\ActiveForm */
/* @var $root Menu */
?>

<div class="menu-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border">Common</div>
        <div class="box-body">
            <?= $form->field($model, 'parentId')->dropDownList($model->parentCategoriesList($root->id)) ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'font_awesome_icon_class')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
