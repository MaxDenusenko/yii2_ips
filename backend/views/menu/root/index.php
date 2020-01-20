<?php

use backend\forms\MenuSearch;
use core\entities\Core\Menu;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Menus';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

    <p>
        <?= Html::a('Create Menu', ['create-root-node'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'name',
                        'value' => function(Menu $model) {
                            $indent = ($model->depth > 1 ? str_repeat('&nbsp;&nbsp;', $model->depth - 1) . ' ': '');
                            return $indent . Html::a(Html::encode($model->name), ['view-root-node' , 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    'menu_depth',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'urlCreator' => function ($action, $model, $key, $index) {
                            return Url::to([$action.'-root-node', 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>

        </div>
    </div>
</div>
