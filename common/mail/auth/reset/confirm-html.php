<?php

use core\entities\User\User;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/reset/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Здравствуйте <?= Html::encode($user->username) ?>,</p>

    <p>Перейдите по ссылке ниже, чтобы сбросить пароль:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
