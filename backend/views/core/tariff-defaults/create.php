<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\TariffDefaults */

$this->title = 'Create Tariff Defaults';
$this->params['breadcrumbs'][] = ['label' => 'Tariff Defaults', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tariff-defaults-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
