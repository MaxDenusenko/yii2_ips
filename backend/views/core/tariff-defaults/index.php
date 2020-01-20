<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\core\TariffDefaultsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tariff Defaults';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tariff-defaults-index">

    <p>
        <?= Html::a('Create Tariff Defaults', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [

                    'name',
                    'mb_limit',
                    'quantity_incoming_traffic',
                    'quantity_outgoing_traffic',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>


</div>
