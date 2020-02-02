<?php
/** @var TariffAssignment $tariff */

use core\entities\Core\TariffAssignment;
use core\helpers\TariffAssignmentHelper;
use yii\helpers\Html;
use yii\helpers\Url; ?>

<tr>
    <td><?= Html::a($tariff->tariff->number, Url::to(['cabinet/my-tariffs/view', 'id' => $tariff->tariff_id, 'hash' => $tariff->hash]))?></td>
    <td><?= $tariff->tariff->name?></td>
    <td><?= $tariff->tariff->qty_proxy?></td>
<!--    <td>--><?//= $tariff->getPrice() .' '.$tariff->tariff->currency?><!--</td>-->
    <td style="text-align: center"><?= TariffAssignmentHelper::statusLabel($tariff->status)?></td>
    <td style="text-align: center">
        <a href="<?= Url::to(['cabinet/my-tariffs/view', 'id' => $tariff->tariff_id, 'hash' => $tariff->hash])?>" class="btn btn-success btn-sm" role="button">Детали</a>
    </td>
</tr>
