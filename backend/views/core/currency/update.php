<?php

use core\forms\manage\Core\CurrencyForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model CurrencyForm */
/* @var $currency core\entities\Core\Currency */

$this->title = 'Update Currency: ' . $currency->code;
$this->params['breadcrumbs'][] = ['label' => 'Currencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $currency->code, 'url' => ['view', 'id' => $currency->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="currency-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
