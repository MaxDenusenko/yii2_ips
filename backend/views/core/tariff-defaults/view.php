<?php

use core\helpers\TariffDefaultsHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\TariffDefaults */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Tariff Defaults'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tariff-defaults-view">

    <p>
        <?= Html::a(\Yii::t('frontend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(\Yii::t('frontend', 'Delete'), ['delete', 'id' => $model->id], [
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
                    'mb_limit',
                    'ip_quantity',
                    [
                        'attribute' => 'type',
                        'value' => TariffDefaultsHelper::statusLabel($model->type),
                        'format' => 'raw',
                    ],
                    'extend_days',
                    'extend_hours',
                    'extend_minutes',
                    'quantity_incoming_traffic',
                    'quantity_outgoing_traffic',
                ],
            ]) ?>
        </div>
    </div>

</div>
