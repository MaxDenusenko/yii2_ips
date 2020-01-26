<?php

/* @var $this yii\web\View */
/* @var $user User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/reset/reset-password', 'token' => $user->password_reset_token]);

use core\entities\User\User; ?>

Здравствуйте <?= $user->username ?>,

Перейдите по ссылке ниже, чтобы сбросить пароль:

<?= $resetLink ?>
