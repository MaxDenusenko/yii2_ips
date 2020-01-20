<?php

/** @var TariffAssignment $tariff */

use core\entities\Core\TariffAssignment;
use core\helpers\TariffAssignmentHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

?>

<div class="row">
    <div class="col-lg-12">
        <?= DetailView::widget([
            'model' => $tariff,
            'attributes' => [
                [
                    'label' => 'Тариф',
                    'attribute' => 'tariff.name',
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
        <?= Html::a('Изменить IPs', ['edit',  'id' => $tariff->tariff_id, 'u' => $tariff->user_id], ['class' => 'btn btn-primary']) ?>
    </div>
</div>
