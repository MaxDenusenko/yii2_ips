<?php

use core\entities\Core\TariffDefaults;
use core\helpers\TariffDefaultsHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\core\TariffDefaultsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = \Yii::t('frontend', 'Tariff Defaults');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tariff-defaults-index">

    <p>
        <?= Html::a(\Yii::t('frontend', 'Create Tariff Defaults'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [

                    'mb_limit',
                    'ip_quantity',
                    [
                        'attribute' => 'type',
                        'filter' => $searchModel->statusList(),
                        'value' => function (TariffDefaults $model) {
                            return TariffDefaultsHelper::statusLabel($model->type);
                        },
                        'format' => 'raw',
                    ],
                    'extend_days',
                    'extend_hours',
                    'extend_minutes',
                    'quantity_incoming_traffic',
                    'quantity_outgoing_traffic',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>


</div>
