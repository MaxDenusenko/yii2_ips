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
                'qty_proxy',
                'price',
            ],
        ]) ?>

        <?php if ($tariff->description) : ?>
            <div class="panel panel-default">
                <div class="panel-heading"><?=$tariff->name?></div>
                <div class="panel-body">
                    <?=$tariff->description?>
                </div>
            </div>
        <?php endif; ?>

        <?= Html::a('Попробовать', ['order', 'id' => $tariff->id, 'trial' => true], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
        <?= Html::a('Заказать', ['order', 'id' => $tariff->id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
    </div>
</div>
