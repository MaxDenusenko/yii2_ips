<?php
/** @var ActiveDataProvider $tariffDataProvider */

use yii\data\ActiveDataProvider;

?>

<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>№</th>
            <th>Название</th>
            <th>Колличество</th>
            <th>Цена</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tariffDataProvider->getModels() as $tariff): ?>
            <?= $this->render('_tariff', [
                'tariff' => $tariff
            ]) ?>
        <?php endforeach; ?>
    </tbody>
</table>
