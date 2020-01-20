<?php

use core\entities\Core\Menu;
use core\forms\manage\Core\MenuFormItem;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model MenuFormItem */
/* @var $menu Menu */
/* @var $root Menu */

$this->title = "Update Menu: {$root->name} > Item: {$menu->name}";
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index-root-node']];
$this->params['breadcrumbs'][] = ['label' => $root->name, 'url' => ['view-root-node', 'id' => $root->id]];
$this->params['breadcrumbs'][] = ['label' => $menu->name, 'url' => ['view-root-item', 'root_id' => $root->id, 'id' => $menu->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="menu-update">

    <?= $this->render('_form', [
        'model' => $model,
        'root' => $root,
    ]) ?>

</div>
