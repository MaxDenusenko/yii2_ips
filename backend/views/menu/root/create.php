<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\Menu */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Create Menu';
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index-root-node']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
