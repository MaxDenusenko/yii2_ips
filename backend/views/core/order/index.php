<?php

use core\entities\Core\Order;
use core\helpers\OrderHelper;
use core\services\PayService;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\core\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = \Yii::t('frontend', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <div class="box">
        <div class="box-body">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [

                    [
                        'attribute' => 'user_id',
                        'value' => function (Order $model) {
                            return Html::a(Html::encode($model->user->username), ['user/view', 'id' => $model->user->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'type',
                        'filter' => $searchModel->typeList(),
                        'value' => function (Order $model) {
                            return OrderHelper::typeLabel($model->type);
                        },
                        'format' => 'raw',
                    ],
                    'amount',

                    [
                        'label' => \Yii::t('frontend', 'Payment method'),
                        'attribute' => 'paymentMethod',
                        'value' => function (Order $model) {
                            return $model->paymentMethod->label;
                        },
                        'filter' => $searchModel->paymentList(),
                        'format' => 'raw',
                    ],

                    [
                        'label' => \Yii::t('frontend', 'Payment State'),
                        'attribute' => 'paymentStatus',
                        'value' => function (Order $model) {
                            return (new PayService())->getPaiStatus($model);
                        },
//                        'filter' => $searchModel->paymentList(),
                        'format' => 'raw',
                    ],

                    [
                        'label' => \Yii::t('frontend', 'Order status'),
                        'attribute' => 'status',
                        'filter' => $searchModel->statusList(),
                        'value' => function (Order $model) {
                            return OrderHelper::statusLabel($model->status);
                        },
                        'format' => 'raw',
                    ],

                    [
                        'attribute' => 'created',
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'created_date_from',
                            'attribute2' => 'created_date_to',
                            'type' => DatePicker::TYPE_RANGE,
                            'separator' => '-',
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ],
                        ]),
                        'format' => 'datetime'
                    ],
                    [
                        'attribute' => 'updated',
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'updated_date_from',
                            'attribute2' => 'updated_date_to',
                            'type' => DatePicker::TYPE_RANGE,
                            'separator' => '-',
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ],
                        ]),
                        'format' => 'datetime'
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{delete}'
                    ],
                ],
            ]); ?>

        </div>
    </div>

</div>
