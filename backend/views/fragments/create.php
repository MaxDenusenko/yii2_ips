<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Fragments */

$this->title = 'Create Fragments';
$this->params['breadcrumbs'][] = ['label' => 'Fragments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fragments-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
