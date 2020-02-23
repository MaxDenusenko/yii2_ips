<?php

use core\entities\Core\CategoryTariffs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\core\CategoryTariffsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = \Yii::t('frontend', 'Category Tariffs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-tariffs-index">

    <p>
        <?= Html::a(\Yii::t('frontend', 'Create Category Tariffs'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [

                    [
                        'attribute' => 'name',
                        'value' => function(CategoryTariffs $model) {
                            $indent = ($model->depth > 1 ? str_repeat('&nbsp;&nbsp;', $model->depth - 1) . ' ': '');
                            return $indent . Html::a(Html::encode($model->name), ['view' , 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],

                    [
                        'value' => function(CategoryTariffs $model) {
                            return
                                Html::a('<span class="glyphicon glyphicon-arrow-up"></span>', ['move-up', 'id' => $model->id], [
                                    'data-method' => 'post'
                                ]).
                                Html::a('<span class="glyphicon glyphicon-arrow-down"></span>', ['move-down', 'id' => $model->id], [
                                    'data-method' => 'post'
                                ]);
                        },
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'text-align: center'],
                    ],

                    'slug',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>

        </div>
    </div>

</div>
