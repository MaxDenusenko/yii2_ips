<?php

/** @var TariffAssignment $tariff */

use core\entities\Core\TariffAssignment;
use macgyer\yii2materializecss\lib\Html;
use macgyer\yii2materializecss\widgets\form\ActiveForm;

$this->title = \Yii::t('frontend', 'IP Editing');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Personal')];
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Control Panel'), 'url' => ['cabinet/my-tariffs']];
$this->params['breadcrumbs'][] = ['label' => $tariff->tariff->name, 'url' => ['cabinet/my-tariffs/view', 'id' => $tariff->tariff_id, 'hash' => $tariff->hash]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col s12">

        <h4><?=\Yii::t('frontend', 'Number of IPs Available')?>: <b><?= $tariff->ip_quantity ?></b></h4>

        <h4 style="color: red"><?=\Yii::t('frontend', 'Record each new IP from a new line')?></h4>
        <br>
        <div class="card-panel">
            <?php $form = ActiveForm::begin(['id' => 'tariff-ips-edit-form']); ?>

            <?= $form->field($model, 'IPs')->textarea(['rows' => $tariff->ip_quantity, 'placeholder' => '000.000.000.000', 'autofocus' => 'autofocus']) ?>

            <div class="form-group">
                <?= Html::submitButton(\Yii::t('frontend', 'Save'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
