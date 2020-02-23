<?php

use core\entities\Core\AdditionalOrderItem;
use core\entities\Core\Coupons;
use core\entities\Core\OrderItem;
use core\entities\Core\RenewalOrderItem;
use core\forms\manage\Core\TariffAssignmentFormEditRenewal;
use core\helpers\CoinHelper;
use core\helpers\CouponsHelper;
use core\helpers\CurrencyHelper;
use core\helpers\OrderHelper;
use core\helpers\TariffAssignmentHelper;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\TariffAssignment */
/* @var $model_help TariffAssignmentFormEditRenewal */
/* @var $orderItem OrderItem */
/* @var $renewal_items RenewalOrderItem[] */
/* @var $additional_ip_items AdditionalOrderItem[] */

$this->title = $model->tariff->name . " - ". $model->user->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Tariff Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

YiiAsset::register($this);

?>

<div class="tariff-assignment-view">

    <p>
        <?php if ($model->isActive()): ?>
            <?= Html::a(Yii::t('frontend', 'Draft'), ['draft',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id, 'hash_id' => $model->hash_id], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
        <?php else: ?>
            <div class="row">
                <div class="col-md-12">
                    <?= Html::a(Yii::t('frontend', 'Apply default (data + date)'),
                        ['apply-default',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id, 'hash_id' => $model->hash_id, 'overwrite' => true],
                        ['class' => 'btn btn-warning', 'data-method' => 'post']) ?>
                    <?= Html::a(Yii::t('frontend', 'Apply default (trial) (data + date)'),
                        ['apply-default-trial',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id, 'hash_id' => $model->hash_id, 'overwrite' => true],
                        ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= Html::a(Yii::t('frontend', 'Apply default (data)'),
                        ['apply-default',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id, 'hash_id' => $model->hash_id, 'overwrite' => true, 'set_date' => false],
                        ['class' => 'btn btn-warning', 'data-method' => 'post']) ?>
                    <?= Html::a(Yii::t('frontend', 'Apply default (trial) (data)'),
                        ['apply-default-trial',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id, 'hash_id' => $model->hash_id, 'overwrite' => true, 'set_date' => false],
                        ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= Html::a(Yii::t('frontend', 'Apply default (date)'),
                        ['apply-default',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id, 'hash_id' => $model->hash_id, 'overwrite' => false, 'set_date' => true],
                        ['class' => 'btn btn-warning', 'data-method' => 'post']) ?>
                    <?= Html::a(Yii::t('frontend', 'Apply default (trial) (date)'),
                        ['apply-default-trial',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id, 'hash_id' => $model->hash_id, 'overwrite' => false, 'set_date' => true],
                        ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= Html::a(Yii::t('frontend', 'Activate'),
                        ['activate',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id, 'hash_id' => $model->hash_id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!$model->isCancel()) : ?>
            <?= Html::a(Yii::t('frontend', 'Cancel'), ['cancel',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id, 'hash_id' => $model->hash_id], ['class' => 'btn btn-danger', 'data-method' => 'post']) ?>
        <?php else: ?>
            <?= Html::a(Yii::t('frontend', 'Draft'), ['draft',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id, 'hash_id' => $model->hash_id], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
        <?php endif; ?>

        <?= Html::a(Yii::t('frontend', 'Update'), ['update', 'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id, 'hash_id' => $model->hash_id], ['class' => 'btn btn-primary']) ?>

    </p>


    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><?= Yii::t('frontend', 'Tariff')?></a></li>
            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><?= Yii::t('frontend', 'Payment')?></a></li>
            <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false"><?= Yii::t('frontend', 'Renewal')?></a></li>
            <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false"><?= Yii::t('frontend', 'Add. IP')?></a></li>
        </ul>
        <div class="tab-content">

            <div class="tab-pane active" id="tab_1">
                <div class="box box-default">
                    <div class="box-header with-border"><?= Yii::t('frontend', 'Common')?></div>
                    <div class="box-body">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'tariff.name',
                                [
                                    'attribute' => 'time_left',
                                    'value' => function($price) use ($model) {
                                        return $model->getFrontTimeLeft();
                                    },
                                ],
                                'user.username',
                                [
                                    'label' => 'file_path',
                                    'value' => implode(
                                        "<br>",
                                        array_map(
                                            function ($str) use ($model) {
                                                return Html::tag('span', $str, ['class' => 'label label-success',]);
                                            },
                                            $model->getFiles()
                                        )
                                    ),
                                    'format' => 'raw',
                                ],
                                'ip_quantity',
                                [
                                    'attribute' => 'status',
                                    'value' => TariffAssignmentHelper::statusLabel($model->status),
                                    'format' => 'raw',
                                ],
                                [
                                    'label' => 'IPs',
                                    'value' => implode(
                                        "<br>",
                                        array_map(
                                            function ($str) use ($model) {
                                                return Html::tag('span', $str, ['class' => 'label label-success',]);
                                            },
                                            $model->getIPs()
                                        )
                                    ),
                                    'format' => 'raw',
                                ],
                                'mb_limit',
                                'quantity_incoming_traffic',
                                'quantity_outgoing_traffic',

                                'tariff.price_for_additional_ip',
                                [
                                    'attribute' => 'tariff.price',
                                    'value' => function($price) use ($model) {
                                        return Yii::$app->formatter->asCurrency($model->getPrice(), CurrencyHelper::getActiveCode());
                                    },
                                    'format' => 'raw',
                                ],
                                'hash',
                            ],
                        ]) ?>
                    </div>
                </div>

                <?php if ($model->coupon) : ?>
                    <div class="box box-default">
                        <div class="box-header with-border"><?= Yii::t('frontend', 'Common')?></div>
                        <div class="box-body">

                            <?= DetailView::widget([
                                'model' => $model->coupon,
                                'attributes' => [
                                    'number',
                                    'code',
                                    'per_cent',
                                    [
                                        'attribute' => 'type',
                                        'filter' => CouponsHelper::typeList(),
                                        'value' => function (Coupons $model) {
                                            return CouponsHelper::typeLabel($model->type);
                                        },
                                        'format' => 'raw',
                                    ],
                                ],
                            ]) ?>

                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <div class="tab-pane" id="tab_2">
                <div class="box box-default">
                    <div class="box-header with-border"><?= Yii::t('frontend', 'Pay')?></div>
                    <div class="box-body">

                        <?= DetailView::widget([
                            'model' => $orderItem,
                            'attributes' => [
                                'name',
                                'cost',
                                'currency',
                                [
                                    'label' => Yii::t('frontend', 'Payment method'),
                                    'attribute' => 'order.paymentMethod.name',
                                ],
                                [
                                    'label' => Yii::t('frontend', 'Order status'),
                                    'attribute' => 'order.status',
                                    'value' => OrderHelper::statusLabel($orderItem->order->status),
                                    'format' => 'raw',
                                ],
                                [
                                    'label' => Yii::t('frontend', 'Payment State'),
                                    'value' =>function ($orderItem) {
                                        $helper = new CoinHelper();
                                        return $helper->getStatus($orderItem->order);

                                    },
                                    'format' => 'raw',
                                ],
                            ],
                        ]) ?>

                    </div>
                </div>
            </div>

            <div class="tab-pane" id="tab_3">
                <?php if (count($renewal_items)) : ?>
                    <div class="box box-default">
                        <div class="box-header with-border"><?= Yii::t('frontend', 'Renewal')?></div>
                        <?php foreach ($renewal_items as $renewal_item) : ?>
                            <div class="box box-default">
                                <div class="box-body">

                                    <?= DetailView::widget([
                                        'model' => $renewal_item,
                                        'attributes' => [
                                            'name',
                                            'cost',
                                            'currency',
                                            [
                                                'label' => Yii::t('frontend', 'Payment method'),
                                                'attribute' => 'order.paymentMethod.name',
                                            ],
                                            [
                                                'label' => Yii::t('frontend', 'Order status'),
                                                'attribute' => 'order.status',
                                                'value' => OrderHelper::statusLabel($renewal_item->order->status),
                                                'format' => 'raw',
                                            ],
                                            [
                                                'label' => Yii::t('frontend', 'Payment State'),
                                                'value' =>function ($renewal_item) {
                                                    $helper = new CoinHelper();
                                                    return $helper->getStatus($renewal_item->order);

                                                },
                                                'format' => 'raw',
                                            ],
                                        ],
                                    ]) ?>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="tab-pane" id="tab_4">
                <?php if (count($additional_ip_items)) : ?>
                <div class="box box-default">
                    <div class="box-header with-border"><?= Yii::t('frontend', 'Additional IP')?></div>
                        <?php foreach ($additional_ip_items as $additional_ip_item) : ?>
                            <div class="box box-default">
                                <div class="box-body">

                                    <?= DetailView::widget([
                                        'model' => $additional_ip_item,
                                        'attributes' => [
                                            'name',
                                            'cost',
                                            'additional_ip',
                                            'currency',
                                            [
                                                'label' => Yii::t('frontend', 'Payment method'),
                                                'attribute' => 'order.paymentMethod.name',
                                            ],
                                            [
                                                'label' => Yii::t('frontend', 'Order status'),
                                                'attribute' => 'order.status',
                                                'value' => OrderHelper::statusLabel($additional_ip_item->order->status),
                                                'format' => 'raw',
                                            ],
                                            [
                                                'label' => Yii::t('frontend', 'Payment State'),
                                                'value' =>function ($additional_ip_item) {
                                                    $helper = new CoinHelper();
                                                    return $helper->getStatus($additional_ip_item->order);

                                                },
                                                'format' => 'raw',
                                            ],
                                        ],
                                    ]) ?>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

        </div>
        <!-- /.tab-content -->
    </div>



    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border"><?= Yii::t('frontend', 'Renewal')?></div>
        <div class="box-body">
            <?= $form->field($model_help, 'extend_days')->textInput() ?>
            <?= $form->field($model_help, 'extend_hours')->textInput() ?>
            <?= $form->field($model_help, 'extend_minutes')->textInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Renewal'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
