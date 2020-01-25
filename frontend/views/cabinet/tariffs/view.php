<?php
/** @var Tariff $tariff */
/** @var User $user */

use core\entities\Core\Tariff;
use core\entities\User\User;
use yii\helpers\Html;
use yii\widgets\DetailView; ?>

<div class="row">
    <div class="col-lg-12">
        <?= DetailView::widget([
            'model' => $tariff,
            'attributes' => [
                'number',
                'name',
                'quantity',
                'price',
            ],
        ]) ?>
        <?php if ($user->issetTariff($tariff->id, $user->id)) : ?>
            <div class="alert alert-warning">
                Этот тариф уже заказан
            </div>
        <?php else: ?>
            <?= Html::a('Попробовать', ['order', 'id' => $tariff->id, 'trial' => true], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
            <?= Html::a('Заказать', ['order', 'id' => $tariff->id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
        <?php endif; ?>
    </div>
</div>
