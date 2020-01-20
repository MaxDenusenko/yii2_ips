<?php

use core\forms\manage\Core\TariffForm;

/* @var $this yii\web\View */
/* @var $model TariffForm */

$this->title = 'Create Tariff';
$this->params['breadcrumbs'][] = ['label' => 'Tariffs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tariff-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
