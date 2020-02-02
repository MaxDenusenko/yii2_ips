<?php

use core\helpers\CurrencyHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\Currency */

$this->title = $model->code;
$this->params['breadcrumbs'][] = ['label' => 'Currencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="currency-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <p>
        <?= Html::a('Set active', ['activate', 'id' => $model->id], ['class' => 'btn btn-primary',
            'data' => [
                'confirm' => 'Are you sure you want to activate this item?',
                'method' => 'post',
            ]
        ]) ?>
        <?= Html::a('Set base', ['set-base', 'id' => $model->id], ['class' => 'btn btn-primary',
            'data' => [
                'confirm' => 'Are you sure you want to set base this item?',
                'method' => 'post',
            ]
        ]) ?>
    </p>

    <div class="box box-default">
        <div class="box-header with-border">Common</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'code',
                    'symbol',
                    [
                        'attribute' => 'active',
                        'value' => CurrencyHelper::statusLabel($model->active),
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'base',
                        'value' => CurrencyHelper::baseLabel($model->base),
                        'format' => 'raw',
                    ],
                ],
            ]) ?>
        </div>
    </div>

</div>
