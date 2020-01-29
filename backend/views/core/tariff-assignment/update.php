<?php

use core\entities\Core\TariffAssignment;
use core\forms\manage\Core\TariffAssignmentForm;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $tariff_model TariffAssignmentForm */
/* @var $tariff TariffAssignment */
/* @var $dataProviderDefaults ActiveDataProvider */

$this->title = 'Update Tariff Assignment: ' . $tariff->tariff_id;
$this->params['breadcrumbs'][] = ['label' => 'Tariff Assignments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $tariff->tariff_id, 'url' => ['view', 'tariff_id' => $tariff->tariff_id, 'user_id' => $tariff->user_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tariff-assignment-update">

    <?= $this->render('_form', [
        'tariff_model' => $tariff_model,
        'tariff' => $tariff,
    ]) ?>

</div>
