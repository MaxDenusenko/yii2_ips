<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\CouponUses */

$this->title = \Yii::t('frontend', 'Create Coupon Uses');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Coupon Uses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-uses-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
