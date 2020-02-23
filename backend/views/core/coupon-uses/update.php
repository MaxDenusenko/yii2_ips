<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\CouponUses */

$this->title = \Yii::t('frontend', 'Update Coupon Uses').': ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Coupon Uses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="coupon-uses-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
