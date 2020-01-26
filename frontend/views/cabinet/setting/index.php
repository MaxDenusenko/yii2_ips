<?php

/** @var User $user */

use core\entities\User\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

?>

<div class="row">
    <div class="col-lg-12">
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
            ],
        ]) ?>
        <?= Html::a('Изменить', ['edit'], ['class' => 'btn btn-primary']) ?>
    </div>
</div>
