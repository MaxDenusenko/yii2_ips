<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;

use macgyer\yii2materializecss\lib\Html;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        <?=\Yii::t('frontend', 'The above error occurred when the web server was processing your request.')?>
    </p>
    <p>
        <?=\Yii::t('frontend', 'Please contact us if you think this is a server error. Thanks.')?>
    </p>

</div>
