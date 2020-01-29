<?php

use core\entities\Core\Tariff;
use core\entities\Core\TariffAssignment;
use core\entities\User\User;
use core\helpers\TariffAssignmentHelper;
use core\helpers\UserHelper;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $user core\entities\User\User */
/* @var $modificationsProvider ActiveDataProvider */

$this->title = $user->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="user-view">

    <p>
        <?php if ($user->isActive()): ?>
            <?= Html::a('To ban', ['to-ban',  'id' => $user->id], ['class' => 'btn btn-warning', 'data-method' => 'post']) ?>
        <?php elseif ($user->isBanned()): ?>
            <?= Html::a('Unban', ['unban',  'id' => $user->id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
        <?php else: ?>
            <?= Html::a('Activate', ['activate',  'id' => $user->id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
        <?php endif; ?>

        <?= Html::a('Update', ['update', 'id' => $user->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $user->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $user,
                'attributes' => [
                    'id',
                    'username',
                    'full_name',
                    'telegram',
                    'gabber',
                    'email:email',
                    'tariff_reminder',
                    [
                        'attribute' => 'status',
                        'filter' => UserHelper::statusList(),
                        'value' => function (User $user) {
                            return UserHelper::statusLabel($user->status);
                        },
                        'format' => 'raw'
                    ],
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
        </div>
    </div>

    <div class="box" id="modifications">
        <div class="box-header with-border">Tariffs</div>
        <div class="box-body">
            <p>
                <?= Html::a('Add Tariff', ['update', 'id' => $user->id], ['class' => 'btn btn-success']) ?>
            </p>
            <?= GridView::widget([
                'dataProvider' => $modificationsProvider,
                'columns' => [
                    [
                        'attribute' => 'tariff_id',
                        'value' => function (TariffAssignment $user) {
                            return Html::a(Html::encode($user->tariff->name), ['core/tariff/view', 'id' => $user->tariff->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'user_id',
                        'value' => function (TariffAssignment $user) {
                            return Html::a(Html::encode($user->user->username), ['user/view', 'id' => $user->user->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function (TariffAssignment $user) {
                            return TariffAssignmentHelper::statusLabel($user->status);
                        },
                        'format' => 'raw',
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'urlCreator' => function ($action, $model, $key, $index) use($user) {
                            return Url::to(['core/tariff-assignment/'.$action, 'user_id' => $model->user_id, 'tariff_id' => $model->tariff_id, 'hash_id' => $model->hash]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>

</div>
