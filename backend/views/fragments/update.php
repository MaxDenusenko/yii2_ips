<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Fragments */

$this->title = 'Update Fragments: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Fragments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fragments-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
