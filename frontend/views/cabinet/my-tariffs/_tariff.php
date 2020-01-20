<?php
/** @var TariffAssignment $tariff */

use core\entities\Core\TariffAssignment;
use core\helpers\TariffAssignmentHelper;
use yii\helpers\Html;
use yii\helpers\Url; ?>

<tr>
    <td><?= Html::a($tariff->tariff->number, Url::to(['cabinet/my-tariffs/tariff', 'id' => $tariff->tariff_id, 'u' => $tariff->user_id]))?></td>
    <td><?= $tariff->tariff->name?></td>
    <td><?= $tariff->tariff->quantity?></td>
    <td><?= $tariff->tariff->price?></td>
    <td><?= TariffAssignmentHelper::statusLabel($tariff->status)?></td>
</tr>
