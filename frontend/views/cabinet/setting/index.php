<?php

/** @var User $user */

use core\entities\User\User;
use macgyer\yii2materializecss\lib\Html;
use macgyer\yii2materializecss\widgets\data\DetailView;

$this->title = \Yii::t('frontend', 'Profile');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Personal')];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col s12">
        <?= DetailView::widget([
            'model' => $user,
            'attributes' => [
                'username',
                'telegram',
                'gabber',
                'email:email',
                [
                    'attribute' => 'created_at',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDateTime($model->created_at, 'php:m/d/Y H:m:s');
                    },
                ],
                [
                    'attribute' => 'updated_at',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDateTime($model->updated_at, 'php:m/d/Y H:m:s');
                    },
                ],
                'tariff_reminder',
            ],
        ]) ?>
        <br>
        <?= Html::a(\Yii::t('frontend', 'Edit'), ['edit'], ['class' => 'btn btn-primary']) ?>
    </div>
</div>
