<?php

/** @var TariffAssignment $tariff */
/** @var RenewalForm $orderForm */
/** @var AdditionalIpOrderForm $orderFormAddIP */
/** @var RenewalOrderItem[] $renewal_items */
/** @var AdditionalOrderItem[] $additional_items */
/** @var array $infoAr */

use core\entities\Core\AdditionalOrderItem;
use core\entities\Core\RenewalOrderItem;
use core\entities\Core\TariffAssignment;
use core\forms\manage\Core\AdditionalIpOrderForm;
use core\forms\manage\Core\RenewalForm;
use core\helpers\CurrencyHelper;
use core\helpers\OrderHelper;
use core\helpers\TariffAssignmentHelper;
use macgyer\yii2materializecss\lib\Html;
use macgyer\yii2materializecss\widgets\data\DetailView;
use macgyer\yii2materializecss\widgets\Modal;
use macgyer\yii2materializecss\widgets\form\ActiveForm;

$this->title = $tariff->tariff->name;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Personal')];
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Control Panel'), 'url' => ['cabinet/my-tariffs']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php if (count($infoAr)) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
        <?php foreach ($infoAr as $str): ?>
            M.toast({html: "<?= $str; ?>"})
        <?php endforeach; ?>
        });
    </script>
<?php endif; ?>

<h4><?=\Yii::t('frontend', 'Tariff')?>: <?=$tariff->tariff->name?></h4>

<div class="row">
    <div class="col s12">

        <?php if (!$tariff->isPaid()): ?>
            <p>
                <?= Html::a(\Yii::t('frontend', 'Pay'), ['pay',  'id' => $tariff->tariff_id, 'hash' => $tariff->hash],
                    ['class' => 'btn btn-primary', 'data-method' => 'post', 'target' => "_blank"]) ?>
                <?php Html::a('Проверить статус платежа', ['pay-update',  'id' => $tariff->tariff_id, 'hash' => $tariff->hash],
                    ['class' => 'btn btn-success btn-xs', 'data-method' => 'post']) ?>
            </p>
        <?php endif; ?>

        <?php if ($tariff->isDraft() && $tariff->isPaid()): ?>
            <p>
                <?= Html::a(\Yii::t('frontend', 'Activate'), ['activate',  'id' => $tariff->tariff_id, 'hash' => $tariff->hash],
                    ['class' => 'btn btn-primary green',
                    'data' => [
                        'confirm' => \Yii::t('frontend', 'Activate tariff ?'),
                        'method' => 'post',
                    ]]); ?>
            </p>
        <?php endif; ?>

        <?php if ($tariff->isActive() && $tariff->isPaid() && $tariff->canDraft() ): ?>
            <p>
                <?= Html::a(\Yii::t('frontend', 'Pause'), ['draft',  'id' => $tariff->tariff_id, 'hash' => $tariff->hash],
                    ['class' => 'btn btn-primary yellow text-darken-4',
                    'data' => [
                        'confirm' => \Yii::t('frontend', 'Pause the tariff ?'),
                        'method' => 'post',
                    ]]); ?>
            </p>
        <?php endif; ?>

        <?= DetailView::widget([
            'model' => $tariff,
            'attributes' => [
                [
                    'attribute' => 'tariff.price',
                    'value' => function($price) use ($tariff) {
                        return TariffAssignmentHelper::getFrontPrice($tariff);
                    },
                    'format' => 'raw',
                ],
                'tariff.qty_proxy',
                'tariff.price_for_additional_ip',
                [
                    'attribute' => 'status',
                    'value' => TariffAssignmentHelper::frontStatusLabel($tariff->status),
                    'format' => 'raw',
                ],

            ],
        ]) ?>

        <br>
        <br>

        <?php if ($tariff->isActive() && $tariff->isPaid()): ?>
            <div class="card-panel">
                <?= DetailView::widget([
                    'model' => $tariff,
                    'attributes' => [
                        [
                            'attribute' => 'time_left',
                            'value' => function($model) use ($tariff) {
                                $frontTimeLeft = $tariff->getFrontTimeLeft();
                                return $frontTimeLeft ? $frontTimeLeft : '-';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'tariff.proxy_link',
                            'value' => function($model) {
                                return Html::a($model->tariff->proxy_link, $model->tariff->proxy_link);
                            },
                            'format' => 'raw',
                        ],
                        'ip_quantity',
                        [
                            'label' => 'IP',
                            'value' => implode(
                                "<br>",
                                array_map(
                                    function ($str) use ($tariff) {
                                        return Html::tag('span', $str, ['class' => 'label label-success',]);
                                    },
                                    $tariff->getIPs()
                                )
                            ),
                            'format' => 'raw',
                        ],
                        'mb_limit',
                        'quantity_incoming_traffic',
                        'quantity_outgoing_traffic',
                    ],
                ]) ?>
            </div>

            <br>
            <br>

            <?php if ($tariff->tariff->price_for_additional_ip) : ?>
                <?php
                Modal::begin([
                    'closeButton' => [
                        'label' => \Yii::t('frontend', 'Cancel'),
                        'tag' => 'span'
                    ],
                    'toggleButton' => [
                        'label' => \Yii::t('frontend', 'Order additional IP')
                    ],
                    'modalType' => Modal::TYPE_LEAN,
                ]);
                ?>
                <h2><?=\Yii::t('frontend', 'Order additional IP for tariff')?> <?=$tariff->tariff->name?></h2>

                <?php if ($tariff->ip_quantity) : ?>
                    <h4><?=\Yii::t('frontend', 'You already have')?> <?=$tariff->ip_quantity?> IP</h4>
                    <br>
                <?php endif; ?>

                <?php
                $form = ActiveForm::begin([
                    'id' => 'order-form',
                    'action' => ['order/additional-ip'],
                    'options' => [
                        'method' => 'post',
                    ]
                ]); ?>

                <div class="form-group form-group">
                    <?= $form->field($orderFormAddIP, 'payment_method_id')->dropDownList($orderForm->getPaymentList()) ?>
                    <?= $form->field($orderFormAddIP, 'additional_ip')->textInput(['value' => 1]) ?>
                    <?= $form->field($orderFormAddIP->assignment, 'product_hash')->hiddenInput(['value' => $tariff->hash_id]) ?>
                </div>
                <br>
                <?= Html::submitButton('Заказать', ['class' => 'btn btn-primary', 'name' => 'renewal-button']) ?>

                <?php ActiveForm::end();?>

                <?php Modal::end(); ?>
            <?php endif; ?>
        <?php endif; ?>

        <br>
        <br>

        <?php if ($tariff->isPaid()): ?>

            <?php
            Modal::begin([
                'closeButton' => [
                    'label' => \Yii::t('frontend', 'Cancel'),
                    'tag' => 'span'
                ],
                'toggleButton' => [
                    'label' => \Yii::t('frontend', 'Order renewal')
                ],
                'modalType' => Modal::TYPE_LEAN,
            ]);
            ?>

                <h2><?=\Yii::t('frontend', 'Renewal for tariff')?> <?=$tariff->tariff->name?></h2>

                <?php
                $form = ActiveForm::begin([
                    'id' => 'order-form',
                    'action' => ['order/renewal'],
                    'options' => [
                        'method' => 'post',
                    ]
                ]); ?>

                    <div class="form-group form-group">
                        <?= $form->field($orderForm, 'payment_method_id')->dropDownList($orderForm->getPaymentList()) ?>
                        <?= $form->field($orderForm, 'renew_with_additional_ip')->checkbox(['checked '=>true]) ?>
                        <?= $form->field($orderForm, 'comment')->hiddenInput(['autofocus' => true]) ?>
                        <?= $form->field($orderForm->assignment, 'product_hash')->hiddenInput(['value' => $tariff->hash_id]) ?>
                    </div>
                    <br>
                    <?= Html::submitButton(\Yii::t('frontend', 'To order'), ['class' => 'btn btn-primary', 'name' => 'renewal-button']) ?>

                <?php ActiveForm::end(); ?>

            <?php Modal::end(); ?>

            <?= $tariff->ip_quantity >= 1 ? Html::a(\Yii::t('frontend', 'Change IP'), ['edit',  'id' => $tariff->tariff_id, 'hash' => $tariff->hash], ['class' => 'btn btn-primary']) : '' ; ?>


        <?php endif; ?>

        <?php if (is_array($renewal_items) && count($renewal_items)) : ?>
            <br>
            <br>
            <div class="card-panel">
                <div class="panel panel-default">
                    <h5><?=\Yii::t('frontend', 'Tariff extension')?></h5>
                    <table class="striped highlight centered responsive-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?=\Yii::t('frontend', 'Price')?></th>
                                <th><?=\Yii::t('frontend', 'Currency')?></th>
                                <th><?=\Yii::t('frontend', 'Status')?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($renewal_items as $k => $renewal_item) : ?>
                        <?php if ($renewal_item->order->isCanceled()) continue; ?>
                            <tr>
                                <td><?=$k+1?></td>
                                <td><?=$renewal_item->price?></td>
                                <td><?=$renewal_item->currency?></td>
                                <td><?= OrderHelper::statusLabel($renewal_item->order->status)?></td>
                                <?php if (!$renewal_item->order->isPaid()) : ?>
                                <td>
                                    <?php Html::a('Обновить статус', ['pay-update',  'id' => $tariff->tariff_id, 'hash' => $tariff->hash , 'idOrder' => $renewal_item->order_id],
                                        ['class' => 'btn btn-success btn-xs', 'data-method' => 'post']) ?>
                                    <?= Html::a(\Yii::t('frontend', 'Pay'), ['pay-link',  'id' => $tariff->tariff_id, 'hash' => $tariff->hash , 'idOrder' => $renewal_item->order_id],
                                        ['class' => 'btn btn-success btn-xs', 'data-method' => 'post', 'target' => "_blank"]) ?> </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <?php if (is_array($additional_items) && count($additional_items)) : ?>
            <br>
            <br>
            <div class="card-panel">
                <div class="panel panel-default">
                    <h5><?=\Yii::t('frontend', 'Add. IP')?></h5>
                    <table class="striped highlight centered responsive-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>IP</th>
                                <th><?=\Yii::t('frontend', 'Price')?></th>
                                <th><?=\Yii::t('frontend', 'Currency')?></th>
                                <th><?=\Yii::t('frontend', 'Status')?></th>
                                <th><?=\Yii::t('frontend', 'Order time left')?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($additional_items as $k => $additional_item) : ?>
                        <?php if ($additional_item->order->isCanceled()) continue; ?>
                            <tr>
                                <td><?=$k+1?></td>
                                <td><?=$additional_item->additional_ip?></td>
                                <td><?=$additional_item->price?></td>
                                <td><?=$additional_item->currency?></td>
                                <td><?= OrderHelper::statusLabel($additional_item->order->status)?></td>
                                <?php if (!$additional_item->order->isPaid()) : ?>
                                <td><?=$additional_item->order->getFrontTimeLeft()?></td>
                                <td>
                                    <?php Html::a('Обновить статус', ['pay-update',  'id' => $tariff->tariff_id, 'hash' => $tariff->hash , 'idOrder' => $additional_item->order_id],
                                        ['class' => 'btn btn-success btn-xs', 'data-method' => 'post']) ?>
                                    <?= Html::a(\Yii::t('frontend', 'Pay'), ['pay-link',  'id' => $tariff->tariff_id, 'hash' => $tariff->hash , 'idOrder' => $additional_item->order_id],
                                        ['class' => 'btn btn-success btn-xs', 'data-method' => 'post', 'target' => "_blank"]) ?> </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
