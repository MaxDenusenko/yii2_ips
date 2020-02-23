<?php
/** @var News $news */

use core\entities\News;


$this->title = $news->title;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'News'), 'url' => ['/news']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-lg-12">

        <h3><?= $news->title ?></h3>

        <small><?= $news->created_at ?></small>
        <br>
        <?= $news->body ?>

    </div>
</div>
