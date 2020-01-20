<?php

use core\entities\Core\Menu;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $root core\entities\Core\Menu */

$this->title = $root->name;
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index-root-node']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="menu-view">

    <p>
        <?= Html::a('Update', ['update-root-node', 'id' => $root->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete-root-node', 'id' => $root->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box box-default">
        <div class="box-header with-border">Common</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $root,
                'attributes' => [
                    'id',
                    'tree',
                    'name',
                    'menu_depth',
                ],
            ]) ?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Menu items</div>
        <div class="box-body">
            <p>
                <?= Html::a('Add menu items', ['create-root-item', 'root_id' => $root->id], ['class' => 'btn btn-success']) ?>
            </p>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'name',
                        'value' => function(Menu $model) use($root) {
                            $indent = ($model->depth > 1 ? str_repeat('&nbsp;&nbsp;', $model->depth - 1) . ' ': '');
                            return $indent . Html::a(Html::encode($model->name), ['update-root-item' , 'root_id' => $root->id, 'id' => $model->id ]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'value' => function(Menu $model) use($root) {
                            return
                                Html::a('<span class="glyphicon glyphicon-arrow-up"></span>', ['move-up', 'root_id' => $root->id, 'id' => $model->id], [
                                    'data-method' => 'post'
                                ]).
                                Html::a('<span class="glyphicon glyphicon-arrow-down"></span>', ['move-down', 'root_id' => $root->id, 'id' => $model->id], [
                                    'data-method' => 'post'
                                ]);
                        },
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'text-align: center'],
                    ],
                    'url',
                    [
                        'value' => function(Menu $model) {
                            if ($model->font_awesome_icon_class)
                                return '<i class="fa '.$model->font_awesome_icon_class.'"></i>';
                            return '';
                        },
                        'format' => 'raw',
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
//                        'template' => '{update} {delete}',
                        'urlCreator' => function ($action, $model, $key, $index) use($root) {
                            return Url::to([$action.'-root-item', 'root_id' => $root->id, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>

</div>
