<?php
/** @var Tariff $tariff */
/** @var User $user */
/** @var OrderForm $orderForm */

use core\entities\Core\Tariff;
use core\entities\User\User;
use core\forms\manage\Core\OrderForm;
use core\helpers\CurrencyHelper;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView; ?>

<div class="row">
    <div class="col-lg-12">
        <?= DetailView::widget([
            'model' => $tariff,
            'attributes' => [
                'number',
                'name',
                'qty_proxy',
                'category_id',
                'price_for_additional_ip',
                [
                    'label' => \Yii::t('frontend', 'Number of ip available'),
                    'value' => function($tariff) {
                        return $tariff->default[0]->ip_quantity;
                    },
                ],
                [
                    'attribute' => 'price',
                    'value' => function($tariff) {
                        return Yii::$app->formatter->asCurrency($tariff->getPrice(), CurrencyHelper::getActiveCode());
                    },
                ],
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


        <?php
        Modal::begin([
            'header' => '<h2>'.\Yii::t('frontend', 'Tariff order').' '.$tariff->name.'</h2>',
            'toggleButton' => [
                'label' => \Yii::t('frontend', 'To order'),
                'tag' => 'button',
                'class' => 'btn btn-success',
            ],
        ]);

        $form = ActiveForm::begin([
            'id' => 'order-form',
            'action' => ['order/create'],
            'options' => [
                'method' => 'post',
            ]
        ]); ?>

        <div class="form-group form-group">
            <?= $form->field($orderForm, 'payment_method_id')->dropDownList($orderForm->getPaymentList()) ?>
            <?= $form->field($orderForm, 'additional_id')->textInput(['autofocus' => false]) ?>
            <?= $form->field($orderForm, 'comment')->hiddenInput(['autofocus' => true]) ?>
            <?= $form->field($orderForm->product, 'product_id')->hiddenInput(['value' => $tariff->id]) ?>
        </div>
        <br>
        <?= Html::submitButton(\Yii::t('frontend', 'Checkout'), ['class' => 'btn btn-primary', 'name' => 'basket-button']) ?>

        <?php ActiveForm::end();

        Modal::end();
        ?>

    </div>
</div>
