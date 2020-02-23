<?php
/** @var TariffAssignment $tariff */

use core\entities\Core\TariffAssignment;
use core\helpers\CurrencyHelper;
use core\helpers\TariffAssignmentHelper;
use yii\helpers\Html;
use yii\helpers\Url; ?>

<tr>
    <td><?= Html::a($tariff->tariff->number, Url::to(['cabinet/my-tariffs/view', 'id' => $tariff->tariff_id, 'hash' => $tariff->hash]))?></td>
    <td><?= $tariff->tariff->name ? $tariff->tariff->name : '-' ?></td>
    <td><?= $tariff->tariff->qty_proxy ? $tariff->tariff->qty_proxy : '-' ?></td>
    <td><?= TariffAssignmentHelper::getFrontPrice($tariff)?></td>
    <td><?= TariffAssignmentHelper::frontStatusLabel($tariff->status)?></td>
    <td>
        <a href="<?= Url::to(['cabinet/my-tariffs/view', 'id' => $tariff->tariff_id, 'hash' => $tariff->hash])?>" class="btn btn-success btn-sm" role="button"><?=\Yii::t('frontend', 'Details')?></a>
    </td>
</tr>
