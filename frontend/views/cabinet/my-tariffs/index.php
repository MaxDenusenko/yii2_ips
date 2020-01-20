<?php

/** @var View $this */
/** @var array $infoAr */
/** @var ActiveDataProvider $tariffDataProvider */

use yii\data\ActiveDataProvider;
use yii\web\View;

?>

<div class="row">
    <div class="col-lg-12">

        <?php if (count($infoAr)) : ?>
        <div class="alert alert-info">
            <?php foreach ($infoAr as $str): ?>
            <?= $str; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?= $this->render('_tariff_list', [
            'tariffDataProvider' => $tariffDataProvider
        ]) ?>
    </div>
</div>
