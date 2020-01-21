<?php

/* @var $this yii\web\View */
/* @var $tariffDataProvider ActiveDataProvider */

$this->title = 'My Yii Application';

use yii\data\ActiveDataProvider; ?>

<div class="index">

    <?= $this->render('/cabinet/tariffs/_tariff_list', [
        'tariffDataProvider' => $tariffDataProvider
    ]) ?>

</div>
