<?php

use core\entities\Core\Currency;
use core\helpers\CurrencyHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\core\CurrencySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Currencies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="currency-index">

    <p>
        <?= Html::a('Create Currency', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [

                    'code',
                    'symbol',
                    [
                        'attribute' => 'active',
                        'filter' => $searchModel->statusList(),
                        'value' => function (Currency $model) {
                            return CurrencyHelper::statusLabel($model->active);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'base',
                        'filter' => $searchModel->baseList(),
                        'value' => function (Currency $model) {
                            return CurrencyHelper::baseLabel($model->base);
                        },
                        'format' => 'raw',
                    ],

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>

        </div>
    </div>

</div>
