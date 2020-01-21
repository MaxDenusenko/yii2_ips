<?php

/* @var $this yii\web\View */
/* @var $user \shop\entities\User\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['signup/verify-email', 'token' => $user->verification_token]);
?>
Здравствуйте <?= $user->username ?>,

Перейдите по ссылке ниже, чтобы подтвердить свою электронную почту:

<?= $verifyLink ?>
