<?php

/* @var $this yii\web\View */
/* @var $user \shop\entities\User\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['reset/reset-password', 'token' => $user->password_reset_token]);
?>
Здравствуйте <?= $user->username ?>,

Перейдите по ссылке ниже, чтобы сбросить пароль:

<?= $resetLink ?>
