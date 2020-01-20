<?php

use core\entities\Core\Menu;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $root core\entities\Core\Menu */
/* @var $item core\entities\Core\Menu */
/* @var $parent core\entities\Core\Menu */

$this->title = "Menu: {$root->name} > Item: {$item->name}";
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index-root-node']];
$this->params['breadcrumbs'][] = ['label' => $root->name, 'url' => ['view-root-node', 'id' => $root->id]];
$this->params['breadcrumbs'][] = $item->name;
\yii\web\YiiAsset::register($this);
?>
<div class="menu-view">

    <p>
        <?= Html::a('Update', ['update-root-item','root_id' => $root->id, 'id' => $item->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete-root-item','root_id' => $root->id, 'id' => $item->id], [
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
                'model' => $item,
                'attributes' => [
                    'id',
                    'name',
                    'tree',
                    'url',
                    [
                        'attribute' => 'font_awesome_icon_class',
                        'value' => function(Menu $model) {
                            if ($model->font_awesome_icon_class)
                                return '<i class="fa '.$model->font_awesome_icon_class.'"></i>';
                            return '';
                        },
                        'format' => 'raw',
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Root</div>
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
        <div class="box-header with-border">Parent</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $parent,
                'attributes' => [
                    'id',
                    'tree',
                    'name',
                    'url',
                    [
                        'attribute' => 'font_awesome_icon_class',
                        'value' => function(Menu $model) {
                            if ($model->font_awesome_icon_class)
                                return '<i class="fa '.$model->font_awesome_icon_class.'"></i>';
                            return '';
                        },
                        'format' => 'raw',
                    ],
                ],
            ]) ?>
        </div>
    </div>

</div>
