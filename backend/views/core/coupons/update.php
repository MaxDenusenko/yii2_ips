<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\Coupons */

$this->title = \Yii::t('frontend', 'Update Coupons').': ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Coupons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="coupons-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
