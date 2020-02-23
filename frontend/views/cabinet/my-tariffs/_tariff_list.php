<?php
/** @var TariffAssignment[] $tariffs */

use core\entities\Core\TariffAssignment;

?>

<table class="striped highlight centered responsive-table">
    <thead>
        <tr>
            <th>№</th>
            <th><?=$tariffs[0]->getAttributeLabel('name')?></th>
            <th><?=$tariffs[0]->tariff->getAttributeLabel('qty_proxy')?></th>
            <th><?=$tariffs[0]->tariff->getAttributeLabel('price')?></th>
            <th><?=$tariffs[0]->getAttributeLabel('status')?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tariffs as $tariff): ?>
            <?= $this->render('_tariff', [
                'tariff' => $tariff
            ]) ?>
        <?php endforeach; ?>
    </tbody>
</table>
