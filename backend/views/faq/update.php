<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Faq */

$this->title = \Yii::t('frontend', 'Update Faq').': ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Faqs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = \Yii::t('frontend', 'Update');
?>
<div class="faq-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
