<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\PaymentMethod */

$this->title = 'Update Payment Method: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Payment Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payment-method-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
