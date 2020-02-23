<?php

use core\entities\Core\Tariff;
use core\helpers\TariffHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\core\TariffSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = \Yii::t('frontend', 'Tariffs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tariff-index">

    <p>
        <?= Html::a(\Yii::t('frontend', 'Create Tariff'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'name',
                        'value' => function (Tariff $model) {
                            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => $searchModel->statusList(),
                        'value' => function (Tariff $model) {
                            return TariffHelper::statusLabel($model->status);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'category_id',
                        'filter' => $searchModel->categoryList(),
                        'value' => function (Tariff $model) {
                            return $model->category ? $model->category->name : '';
                        },
                        'format' => 'raw',
                    ],
                    'number',
                    'qty_proxy',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>

        </div>
    </div>

</div>
