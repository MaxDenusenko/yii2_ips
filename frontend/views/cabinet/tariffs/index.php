<?php

/** @var View $this */
/** @var ActiveDataProvider $tariffDataProvider */

use yii\data\ActiveDataProvider;
use yii\web\View;
?>
<div class="cabinet-index">

    <?= $this->render('_tariff_list', [
        'tariffDataProvider' => $tariffDataProvider
    ]) ?>
    
</div>
