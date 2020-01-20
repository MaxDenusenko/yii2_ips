<?php

/** @var TariffAssignment $tariff */

use core\entities\Core\TariffAssignment;
use himiklab\yii2\recaptcha\ReCaptcha2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="row">
    <div class="col-lg-12">

        <div class="alert alert-info">
            <p>Количество доступных IP: <b><?= $tariff->ip_quantity ?></b></p>
            <p><strong><i>Каждый новый IP записывайте с новой строки</i></strong></p>
        </div>

        <?php $form = ActiveForm::begin(['id' => 'tariff-ips-edit-form']); ?>

        <?= $form->field($model, 'IPs')->textarea(['rows' => 10]) ?>

        <?= $form->field($model, 'reCaptcha')->widget(ReCaptcha2::className()) ?>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
