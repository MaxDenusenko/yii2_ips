<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Faq */

$this->title = \Yii::t('frontend', 'Create Faq');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Faqs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
