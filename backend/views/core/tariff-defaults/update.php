<?php

use core\entities\Core\TariffDefaults;
use core\forms\manage\Core\TariffDefaultsForm;

/* @var $this yii\web\View */
/* @var $model TariffDefaultsForm */
/* @var $tariff TariffDefaults */

$this->title = 'Update Tariff Default: ' . $tariff->name;
$this->params['breadcrumbs'][] = ['label' => 'Tariff Default', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $tariff->name, 'url' => ['view', 'id' => $tariff->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tariff-assignment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
