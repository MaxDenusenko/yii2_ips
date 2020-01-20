<?php

use core\entities\Core\Menu;
use core\forms\manage\Core\MenuForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model MenuForm */
/* @var $menu Menu */

$this->title = 'Update Menu: ' . $menu->name;
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index-root-node']];
$this->params['breadcrumbs'][] = ['label' => $menu->name, 'url' => ['view-root-node', 'id' => $menu->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="menu-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
