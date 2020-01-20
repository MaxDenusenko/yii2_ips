<?php
/** @var Tariff $tariff */

use core\entities\Core\Tariff;
use yii\helpers\Url; ?>

<tr>
    <td><?= $tariff->number?></td>
    <td><?= $tariff->name?></td>
    <td><?= $tariff->quantity?></td>
    <td><?= $tariff->price?></td>
    <td style="text-align: center">
        <a href="<?= Url::to(['/cabinet/tariffs/view', 'id' => $tariff->id])?>" class="btn btn-success btn-sm" role="button">Заказать</a>
    </td>
</tr>
