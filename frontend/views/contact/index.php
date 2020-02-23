<?php

/* @var $this yii\web\View */
/* @var $content Fragments */

use core\entities\Fragments;
use macgyer\yii2materializecss\lib\Html;

$this->title = \Yii::t('frontend', 'Contacts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">

    <h1><?= Html::encode($this->title) ?></h1>
    <?=$content->text?>

</div>
