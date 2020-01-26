<?php
/** @var TariffAssignment $tariff */

use core\entities\Core\TariffAssignment;
use core\helpers\TariffAssignmentHelper;
use yii\helpers\Html;
use yii\helpers\Url; ?>

<tr>
    <td><?= Html::a($tariff->tariff->number, Url::to(['cabinet/my-tariffs/view', 'id' => $tariff->tariff_id]))?></td>
    <td><?= $tariff->tariff->name?></td>
    <td><?= $tariff->tariff->quantity?></td>
    <td><?= $tariff->getPrice()?></td>
    <td style="text-align: center"><?= TariffAssignmentHelper::statusLabel($tariff->status)?></td>
    <td style="text-align: center">
        <a href="<?= Url::to(['cabinet/my-tariffs/view', 'id' => $tariff->tariff_id])?>" class="btn btn-success btn-sm" role="button">Детали</a>
    </td>
</tr>
