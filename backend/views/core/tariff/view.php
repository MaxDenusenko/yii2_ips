<?php

use core\entities\Core\TariffDefaults;
use core\helpers\CurrencyHelper;
use core\helpers\TariffDefaultsHelper;
use core\helpers\TariffHelper;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\Tariff */
/* @var $default TariffDefaults */
/* @var $defaultTrial TariffDefaults */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tariffs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="tariff-view">

    <p>
        <?php if ($model->isActive()): ?>
            <?= Html::a('Draft', ['draft', 'id' => $model->id], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
        <?php else: ?>
            <?= Html::a('Activate', ['activate', 'id' => $model->id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
        <?php endif; ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box box-default">
        <div class="box-header with-border">Common</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'number',
                    'name',
                    [
                        'attribute' => 'price',
                        'value' => function($model) {
                            return Yii::$app->formatter->asCurrency($model->getPrice(), CurrencyHelper::getActiveCode());
                        },
                    ],
                    'price_for_additional_ip',
                    'qty_proxy',
                    [
                        'attribute' => 'category.name',
                        'label' => 'Категория'
                    ],
                    [
                        'attribute' => 'proxy_link',
                        'value' => function($model) {
                            return Html::a($model->proxy_link, $model->proxy_link);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'status',
                        'value' => TariffHelper::statusLabel($model->status),
                        'format' => 'raw',
                    ],
                    'description:html',
                ],
            ]) ?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Default</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $default,
                'attributes' => [
                    'mb_limit',
                    'ip_quantity',
                    [
                        'label' => 'file_path',
                        'value' => implode(
                            "<br>",
                            array_map(
                                function ($str) use ($default) {
                                    return Html::tag('span', $str, ['class' => 'label label-success',]);
                                },
                                $default->getFiles()
                            )
                        ),
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'type',
                        'value' => TariffDefaultsHelper::statusLabel($default->type),
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

    <div class="box box-default">
        <div class="box-header with-border">Default trial</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $defaultTrial,
                'attributes' => [
                    'mb_limit',
                    'ip_quantity',
                    [
                        'label' => 'file_path',
                        'value' => implode(
                            "<br>",
                            array_map(
                                function ($str) use ($defaultTrial) {
                                    return Html::tag('span', $str, ['class' => 'label label-success',]);
                                },
                                $defaultTrial->getFiles()
                            )
                        ),
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'type',
                        'value' => TariffDefaultsHelper::statusLabel($defaultTrial->type),
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
