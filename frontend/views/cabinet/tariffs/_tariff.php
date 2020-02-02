<?php
/** @var Tariff $tariff */

use core\entities\Core\Tariff;
use core\helpers\CurrencyHelper;
use yii\helpers\Url; ?>

<tr>
    <td><?= $tariff->number?></td>
    <td><?= $tariff->name?></td>
    <td><?= $tariff->qty_proxy?></td>
    <td><?= Yii::$app->formatter->asCurrency($tariff->getPrice(), CurrencyHelper::getActiveCode());?></td>
    <td style="text-align: center">
        <a href="<?= Url::to(['/cabinet/tariffs/view', 'id' => $tariff->id])?>" class="btn btn-success btn-sm" role="button">Заказать</a>
    </td>
</tr>
