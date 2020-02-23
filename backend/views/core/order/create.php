<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\Order */

$this->title = \Yii::t('frontend', 'Create Order');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
