<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\PaymentMethod */

$this->title = \Yii::t('frontend', 'Create Payment Method');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Payment Methods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-method-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
