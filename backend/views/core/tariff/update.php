<?php

use core\entities\Core\Tariff;
use core\forms\manage\Core\TariffForm;

/* @var $this yii\web\View */
/* @var $model TariffForm */
/* @var $tariff Tariff */

$this->title = 'Update Tariff: ' . $tariff->name;
$this->params['breadcrumbs'][] = ['label' => 'Tariffs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $tariff->name, 'url' => ['view', 'id' => $tariff->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tariff-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
