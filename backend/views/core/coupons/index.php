<?php

use core\entities\Core\Coupons;
use core\helpers\CouponsHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\core\CouponsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = \Yii::t('frontend', 'Coupons');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupons-index">

    <p>
        <?= Html::a(\Yii::t('frontend', 'Create Coupons'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [

                'number',
                'code',
                'per_cent',
                [
                    'attribute' => 'type',
                    'filter' => $searchModel->typeList(),
                    'value' => function (Coupons $model) {
                        return CouponsHelper::typeLabel($model->type);
                    },
                    'format' => 'raw',
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

        </div>
    </div>

</div>
