<?php

use core\entities\Core\TariffDefaults;
use core\forms\manage\Core\TariffDefaultsForm;

/* @var $this yii\web\View */
/* @var $model TariffDefaultsForm */
/* @var $tariff TariffDefaults */

$this->title = \Yii::t('frontend', 'Update Tariff Default').': ' . $tariff->id;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Tariff Default'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $tariff->id, 'url' => ['view', 'id' => $tariff->id]];
$this->params['breadcrumbs'][] = \Yii::t('frontend', 'Update');
?>
<div class="tariff-assignment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
