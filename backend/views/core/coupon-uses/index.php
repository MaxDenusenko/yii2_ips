<?php

use core\entities\Core\CouponUses;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\core\CouponUsesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = \Yii::t('frontend', 'Coupon Uses');
$this->params['breadcrumbs'][] = $this->title;

$amount = 0;
if (!empty($dataProvider->getModels())) {
    foreach ($dataProvider->getModels() as $key => $val) {
        $amount += $val->sum;
    }
}

?>
<div class="coupon-uses-index">

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [

                    [
                        'attribute' => 'date_use',
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'date_from',
                            'attribute2' => 'date_to',
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
                        'attribute' => 'sum',
                        'footer' => \Yii::t('frontend', 'Total').': '.$amount,
                    ],
                    [
                        'attribute' => 'tariff_assignment_hash_id',
                        'value' => function (CouponUses $model) {
                            return Html::a(Html::encode($model->tariffAssignment->tariff->name),
                                ['core/tariff-assignment/view', 'tariff_id' => $model->tariffAssignment->tariff_id,
                                    'hash_id' => $model->tariffAssignment->hash_id, 'user_id' => $model->tariffAssignment->user_id]);
                        },
                        'format' => 'raw',
                    ],

                    [
                        'attribute' => 'coupon_id',
                        'value' => function (CouponUses $model) {
                            return Html::a(Html::encode($model->coupon->code), ['core/coupons/view', 'id' => $model->coupon->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'user_id',
                        'value' => function (CouponUses $model) {
                            return Html::a(Html::encode($model->user->username), ['user/view', 'id' => $model->user->id]);
                        },
                        'format' => 'raw',
                    ],

                    ['class' => 'yii\grid\ActionColumn'],
                ],
                'showFooter' => true,
            ]); ?>
        </div>
    </div>


</div>
