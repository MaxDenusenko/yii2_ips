<?php

use core\entities\Core\Tariff;
use core\helpers\TariffHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\core\TariffSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tariffs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tariff-index">

    <p>
        <?= Html::a('Create Tariff', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
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
                    'number',
                    'quantity',
                    'price',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>

        </div>
    </div>

</div>
