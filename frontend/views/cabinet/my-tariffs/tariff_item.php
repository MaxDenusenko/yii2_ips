<?php

/** @var TariffAssignment $tariff */

use core\entities\Core\TariffAssignment;
use core\helpers\TariffAssignmentHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

?>
<h1>Тариф: <?=$tariff->tariff->name?></h1>

<div class="row">
    <div class="col-lg-12">
        <?= DetailView::widget([
            'model' => $tariff,
            'attributes' => [
                [
                    'label' => 'Тариф',
                    'attribute' => 'tariff.name',
                ],
//                [
//                    'attribute' => 'tariff.price',
//                    'value' => function($price) use ($tariff) {
//                        return $tariff->getPrice(). ' '.$tariff->tariff->currency;
//                    },
//                    'format' => 'raw',
//                ],
                'tariff.qty_proxy',
                'tariff.price_for_additional_ip',
                [
                    'attribute' => 'tariff.proxy_link',
                    'value' => function($model) {
                        return Html::a($model->tariff->proxy_link, $model->tariff->proxy_link);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'status',
                    'value' => TariffAssignmentHelper::statusLabel($tariff->status),
                    'format' => 'raw',
                ],
                'ip_quantity',
                [
                    'label' => 'IPs',
                    'value' => implode(
                        "<br>",
                        array_map(
                            function ($str) use ($tariff) {
                                return Html::tag('span', $str, ['class' => 'label label-success',]);
                            },
                            $tariff->getIPs()
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

        <div class="panel panel-default">
            <div class="panel-heading">Оплата</div>
            <div class="panel-body">
                <?php if ($tariff->isPaid()): ?>
                    <p>Оплачено</p>
                <?php else: ?>
                    <p>Не оплачено</p>
                    <?= Html::a('Оплатить', ['pay',  'id' => $tariff->tariff_id, 'hash' => $tariff->hash], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
                <?php endif; ?>
            </div>
        </div>

<!--        --><?//= $tariff->ip_quantity >= 1 ? Html::a('Изменить IP', ['edit',  'id' => $tariff->tariff_id, 'hash' => $tariff->hash], ['class' => 'btn btn-primary']) : '' ; ?>
<!--        --><?//= $tariff->isDeactivated() ? Html::a('Запрос на продление тарифа', ['renewal',  'id' => $tariff->tariff_id, 'hash' => $tariff->hash], ['class' => 'btn btn-primary', 'data-method' => 'post']) : '' ?>
<!--        --><?//= !$tariff->isRequestCancel() ? Html::a('Отменить тариф', ['cancel',  'id' => $tariff->tariff_id, 'hash' => $tariff->hash], ['class' => 'btn btn-warning', 'data-method' => 'post']) : '' ?>
    </div>
</div>
