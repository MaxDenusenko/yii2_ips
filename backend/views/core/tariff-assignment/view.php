<?php

use core\helpers\TariffAssignmentHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\TariffAssignment */

$this->title = $model->tariff->name . " - ". $model->user->username;
$this->params['breadcrumbs'][] = ['label' => 'Tariff Assignments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tariff-assignment-view">

    <p>
        <?php if ($model->isActive()): ?>
            <?= Html::a('Draft', ['draft',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
        <?php else: ?>
            <?= Html::a('Activate', ['activate',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
            <?= Html::a('Activate trial', ['activate-trial',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
        <?php endif; ?>
        <?= Html::a('Update', ['update', 'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id], [
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
                    'tariff.name',
                    'user.username',
                    [
                        'label' => 'file_path',
                        'value' => implode(
                            "<br>",
                            array_map(
                                function ($str) use ($model) {
                                    return Html::tag('span', $str, ['class' => 'label label-success',]);
                                },
                                $model->getFiles()
                            )
                        ),
                        'format' => 'raw',
                    ],
                    'ip_quantity',
                    [
                        'attribute' => 'status',
                        'value' => TariffAssignmentHelper::statusLabel($model->status),
                        'format' => 'raw',
                    ],
                    [
                        'label' => 'IPs',
                        'value' => implode(
                            "<br>",
                            array_map(
                                function ($str) use ($model) {
                                    return Html::tag('span', $str, ['class' => 'label label-success',]);
                                },
                                $model->getIPs()
                            )
                        ),
                        'format' => 'raw',
                    ],
                    'mb_limit',
                    'quantity_incoming_traffic',
                    'quantity_outgoing_traffic',
                    'date_to',
                    'time_to',
                ],
            ]) ?>
        </div>
    </div>

</div>
