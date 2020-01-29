<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\core\CategoryTariffsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Category Tariffs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-tariffs-index">

    <p>
        <?= Html::a('Create Category Tariffs', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'name',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>

        </div>
    </div>

</div>
