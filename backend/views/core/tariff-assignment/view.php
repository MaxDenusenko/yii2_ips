<?php

use core\forms\manage\Core\TariffAssignmentFormEditRenewal;
use core\helpers\TariffAssignmentHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\TariffAssignment */
/* @var $model_help TariffAssignmentFormEditRenewal */

$this->title = $model->tariff->name . " - ". $model->user->username;
$this->params['breadcrumbs'][] = ['label' => 'Tariff Assignments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tariff-assignment-view">

    <p>
        <?php if ($model->isActive()): ?>
            <?= Html::a('Draft', ['draft',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
        <?php else: ?>
            <div class="row">
                <div class="col-md-12">
                    <?= Html::a('Применить дефолт (данные + дата)',
                        ['apply-default',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id, 'overwrite' => true],
                        ['class' => 'btn btn-warning', 'data-method' => 'post']) ?>
                    <?= Html::a('Применить дефолт (триал) (данные + дата)',
                        ['apply-default-trial',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id, 'overwrite' => true],
                        ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= Html::a('Применить дефолт (данные)',
                        ['apply-default',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id, 'overwrite' => true, 'set_date' => false],
                        ['class' => 'btn btn-warning', 'data-method' => 'post']) ?>
                    <?= Html::a('Применить дефолт (триал) (данные)',
                        ['apply-default-trial',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id, 'overwrite' => true, 'set_date' => false],
                        ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= Html::a('Применить дефолт (дата)',
                        ['apply-default',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id, 'overwrite' => false, 'set_date' => true],
                        ['class' => 'btn btn-warning', 'data-method' => 'post']) ?>
                    <?= Html::a('Применить дефолт (триал) (дата)',
                        ['apply-default-trial',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id, 'overwrite' => false, 'set_date' => true],
                        ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= Html::a('Активировать',
                        ['activate',  'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
                </div>
            </div>
        <?php endif; ?>
        <?= Html::a('Update', ['update', 'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'tariff_id' => $model->tariff_id, 'user_id' => $model->user_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box box-default">
        <div class="box-header with-border">Common</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'tariff.name',
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
                    'date_to',
                    'time_to',
                    'discount',
                    'tariff.price_for_additional_ip',
                    [
                        'attribute' => 'tariff.price',
                        'value' => function($price) use ($model) {
                            return $model->getPrice();
                        },
                        'format' => 'raw',
                    ],
                    'hash',
                ],
            ]) ?>
        </div>
    </div>


    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border">Renewal</div>
        <div class="box-body">
            <?= $form->field($model_help, 'extend_days')->textInput() ?>
            <?= $form->field($model_help, 'extend_hours')->textInput() ?>
            <?= $form->field($model_help, 'extend_minutes')->textInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Renewal', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
