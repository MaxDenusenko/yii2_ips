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

        <?//= Html::a('Попробовать', ['order', 'id' => $tariff->id, 'trial' => true], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
<!--        --><?//= Html::a('Заказать', ['basket/add', 'id' => $tariff->id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>

        <?php
        Modal::begin([
            'header' => '<h2>Заказ тарифа '.$tariff->name.'</h2>',
            'toggleButton' => [
                'label' => 'Заказать',
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
            <?= $form->field($orderForm, 'comment')->textarea(['autofocus' => true]) ?>
            <?= $form->field($orderForm->product, 'product_id')->hiddenInput(['value' => $tariff->id]) ?>
        </div>
        <br>
        <?= Html::submitButton('Оформить', ['class' => 'btn btn-primary', 'name' => 'basket-button']) ?>

        <?php ActiveForm::end();

        Modal::end();
        ?>

        <?php /*$form = ActiveForm::begin([
            'id' => 'basket-form',
            'action' => ['basket/add'],
            'options' => [
                'class' => 'form-inline add-to-basket',
                'method' => 'post',
            ]
        ]); */?><!--

        <div class="form-group form-group">
            <?/*= $form->field($addToBasketForm, 'count')->textInput(['autofocus' => true]) */?>
            <?/*= $form->field($addToBasketForm, 'id_product')->hiddenInput(['value' => $tariff->id]) */?>
        </div>
        <br>
        <?/*= Html::submitButton('В корзину', ['class' => 'btn btn-primary', 'name' => 'basket-button']) */?>

        --><?php /*ActiveForm::end(); */?>

    </div>
</div>
