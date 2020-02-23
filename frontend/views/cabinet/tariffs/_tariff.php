<?php
/** @var Tariff $tariff */
/** @var OrderForm $orderForm */

use core\entities\Core\Tariff;
use core\forms\manage\Core\OrderForm;
use core\helpers\CurrencyHelper;
use macgyer\yii2materializecss\widgets\Modal;
use macgyer\yii2materializecss\lib\Html;
use macgyer\yii2materializecss\widgets\form\ActiveForm; ?>

<tr>
    <td><?= $tariff->number ? $tariff->number : '-';?></td>
    <td><?= $tariff->name ? $tariff->name : '-' ?></td>
    <td><?= $tariff->qty_proxy ? $tariff->qty_proxy : '-'?></td>
    <td><?= Yii::$app->formatter->asCurrency($tariff->getPrice(), CurrencyHelper::getActiveCode());?></td>
    <td><?= $tariff->default[0]->ip_quantity ? $tariff->default[0]->ip_quantity : '-'; ?></td>
    <td>

        <?php
        Modal::begin([
            'closeButton' => [
                'label' => \Yii::t('frontend', 'Cancel'),
                'tag' => 'span'
            ],
            'toggleButton' => [
                'label' => \Yii::t('frontend', 'To order')
            ],
            'modalType' => Modal::TYPE_LEAN,
        ]);
        ?>

            <?php $form = ActiveForm::begin([
                'id' => 'order-form'.$tariff->id,
                'action' => ['order/create'],
                'options' => [
                    'method' => 'post',
                ]
            ]); ?>

                <br>
                <h3>Заказ тарифа <?=$tariff->name?></h3>
                <div class="form-group form-group">
                    <?= $form->field($orderForm, 'payment_method_id',
                        ['inputOptions' => ['id' => $form->getId() . '-payment_method_id']])->dropDownList($orderForm->getPaymentList()) ?>
                    <?= $form->field($orderForm, 'additional_id',
                        ['inputOptions' => ['id' => $form->getId() . '-additional_id']])->textInput(['autofocus' => false]) ?>
                    <?= $form->field($orderForm, 'coupon_code',
                        ['inputOptions' => ['id' => $form->getId() . '-coupon_code']])->textInput(['autofocus' => false]) ?>
                    <?= $form->field($orderForm, 'comment',
                        ['inputOptions' => ['id' => $form->getId() . '-comment']])->hiddenInput(['autofocus' => false]) ?>
                    <?= $form->field($orderForm->product, 'product_id')->hiddenInput(['value' => $tariff->id]) ?>
                </div>
                <br>
                <?= Html::submitButton(\Yii::t('frontend', 'Checkout'), ['class' => 'btn btn-primary', 'name' => 'basket-button']) ?>

            <?php ActiveForm::end();?>

        <?php Modal::end(); ?>

    </td>
</tr>
