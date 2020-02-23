<?php

use core\entities\Core\TariffAssignment;
use core\helpers\TariffAssignmentHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\core\TariffAssignmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tariff Assignments';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="tariff-assignment-index">

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'tariff_id',
                        'value' => function (TariffAssignment $model) {
                            return Html::a(Html::encode($model->tariff->name), ['core/tariff/view', 'id' => $model->tariff->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'user_id',
                        'value' => function (TariffAssignment $model) {
                            return Html::a(Html::encode($model->user->username), ['user/view', 'id' => $model->user->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => $searchModel->statusList(),
                        'value' => function (TariffAssignment $model) {
                            return TariffAssignmentHelper::statusLabel($model->status);
                        },
                        'format' => 'raw',
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}{update}',
                    ],
                ],
            ]); ?>
        </div>
    </div>

</div>
