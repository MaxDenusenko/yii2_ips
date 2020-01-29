<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\CategoryTariffs */

$this->title = 'Update Category Tariffs: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Category Tariffs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="category-tariffs-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
