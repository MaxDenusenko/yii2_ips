<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\CouponUses */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Coupon Uses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="coupon-uses-view">

    <div class="box box-default">
        <div class="box-header with-border"><?=\Yii::t('frontend', 'Common')?></div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'date_use',
                    'coupon_id',
                    'user_id',
                ],
            ]) ?>
        </div>
    </div>

</div>
