<?php

/* @var $this yii\web\View */
/* @var $user User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/signup/verify-email', 'token' => $user->verification_token]);

use core\entities\User\User; ?>

Здравствуйте <?= $user->username ?>,

Перейдите по ссылке ниже, чтобы подтвердить свою электронную почту:

<?= $verifyLink ?>
