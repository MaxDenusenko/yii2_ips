<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\FragmentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = \Yii::t('frontend', 'Pages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fragments-index">

    <p>
        <?= Html::a(\Yii::t('frontend', 'Create Fragments'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [

                    'name',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}',
                    ],
                ],
            ]); ?>

        </div>
    </div>

</div>
