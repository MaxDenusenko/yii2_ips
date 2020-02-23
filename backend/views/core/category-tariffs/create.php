<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\CategoryTariffs */

$this->title = \Yii::t('frontend', 'Create Category Tariffs');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Category Tariffs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-tariffs-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
