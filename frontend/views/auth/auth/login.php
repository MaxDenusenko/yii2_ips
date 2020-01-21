<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model LoginForm */

use core\forms\auth\LoginForm;
use himiklab\yii2\recaptcha\ReCaptcha2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста, заполните следующие поля для входа:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <?= $form->field($model, 'reCaptcha')->widget(ReCaptcha2::className()) ?>

                <div style="color:#999;margin:1em 0">
                    Если вы забыли свой пароль, вы можете <?= Html::a('сбросить его', ['/auth/reset/request-password-reset']) ?>.
                    <br>
                    Нужно новое письмо с подтверждением? <?= Html::a('Отправить', ['/auth/signup/resend-verification-email']) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Вход', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>

<!--            <h2>Socials</h2>-->
<!--            --><?//= AuthChoice::widget([
//                'baseAuthUrl' => ['auth/network/auth'],
//                'popupMode' => false,
//            ])?>

        </div>
    </div>
</div>
