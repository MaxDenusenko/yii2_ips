<?php

use core\entities\Core\Coupons;
use core\helpers\CouponsHelper;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\Coupons */

$this->title = $model->code;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Coupons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

YiiAsset::register($this);

?>
<div class="coupons-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => \Yii::t('frontend', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box box-default">
        <div class="box-header with-border"><?=\Yii::t('frontend', 'Common')?></div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'number',
                    'code',
                    'per_cent',
                    [
                        'attribute' => 'type',
                        'value' => function(Coupons $model) {
                            return CouponsHelper::typeLabel($model->type);
                        },
                        'format' => 'raw',
                    ],
                ],
            ]) ?>
        </div>
    </div>

</div>
