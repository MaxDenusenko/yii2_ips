<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\core\PaymentMethodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = \Yii::t('frontend', 'Payment Methods');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-method-index">

    <!--<p>
        <?/*= Html::a('Create Payment Method', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->

    <div class="box">
        <div class="box-body">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [

                    'name',
                    'label',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}'
                    ],
                ],
            ]); ?>

        </div>
    </div>

</div>
