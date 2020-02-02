<?php

/* @var $this View */
/* @var $content string */

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\web\View;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);

    $menuItems = [
        ['label' => 'Главная', 'url' => ['/site/index']],
//        ['label' => 'О нас', 'url' => ['/site/about']],
    ];
    if (!Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Личный кабинет', 'url' => ['/cabinet/default/index'], 'active' => strpos(Yii::$app->controller->id, 'cabinet') !== false];
    }
    $menuItems[] = ['label' => 'FAQ', 'url' => ['/site/faq']];
    $menuItems[] = ['label' => 'Контакты', 'url' => ['/contact/index']];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Регистрация', 'url' => ['/auth/signup/index']];
        $menuItems[] = ['label' => 'Вход', 'url' => ['/auth/auth/login']];
    } else {
        $menuItems[] = ['label' => 'Корзина', 'url' => ['/basket/index']];
        $menuItems[] = '<li>'
            . Html::beginForm(['/auth/auth/logout'], 'post')
            . Html::submitButton(
                'Выход (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>


<?php
$footer =
    <<<FOOTER
<button type="button" class="btn btn-default" data-dismiss="modal">
    Продолжить покупки
</button>
<button type="button" class="btn btn-warning">
    Оформить заказ
</button>
FOOTER;
Modal::begin([
    'header' => '<h2>Корзина</h2>',
    'id' => 'basket-modal',
    'size'=>'modal-lg',
    'footer' => $footer
]);
Modal::end();
unset($footer);
?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
