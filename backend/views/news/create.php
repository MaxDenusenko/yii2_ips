<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\News */

$this->title = Yii::t('frontend', 'Create News');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
