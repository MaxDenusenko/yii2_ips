<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\Coupons */

$this->title = \Yii::t('frontend', 'Create Coupons');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Coupons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupons-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
