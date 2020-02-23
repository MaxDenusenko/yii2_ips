<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\CategoryTariffs */

$this->title = \Yii::t('frontend', 'Update Category Tariffs').': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Category Tariffs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = \Yii::t('frontend', 'Update');
?>
<div class="category-tariffs-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
