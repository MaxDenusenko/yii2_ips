<?php

use core\entities\Core\Menu;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\Menu */
/* @var $form yii\widgets\ActiveForm */
/* @var $root Menu */

$this->title = "Create Menu {$root->name} Item";
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index-root-node']];
$this->params['breadcrumbs'][] = ['label' => $root->name, 'url' => ['view-root-node', 'id' => $root->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-create">

    <?= $this->render('_form', [
        'model' => $model,
        'root' => $root,
    ]) ?>

</div>
