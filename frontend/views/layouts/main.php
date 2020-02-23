<?php

/* @var $this View */
/* @var $content string */

use common\modules\languages\widgets\ListWidget;
use macgyer\yii2materializecss\lib\Html;
use macgyer\yii2materializecss\widgets\navigation\Nav;
use macgyer\yii2materializecss\widgets\navigation\Breadcrumbs;
use macgyer\yii2materializecss\widgets\Alert;

use yii\bootstrap\Modal;
use yii\web\View;
use frontend\assets\AppAsset;

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

<header class="page-header">

    <header class="page-header">
        <nav class="white nav-center">
            <div class="container">
                <div class="nav-wrapper">
                    <a href="/" class="brand-logo"><?=Yii::$app->name?></a>
                    <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                    <?php
                        $menuItems = [
//                            ['label' => 'Главная', 'url' => ['/site/index']],
                        ];
                        if (!Yii::$app->user->isGuest) {
                            $menuItems[] = ['label' => Yii::t('frontend', 'Personal'), 'url' => ['/cabinet/default/index'], 'active' => strpos(Yii::$app->controller->id, 'cabinet') !== false];
                        }
                        $menuItems[] = ['label' => Yii::t('frontend', 'News'), 'url' => ['/news']];
                        $menuItems[] = ['label' => Yii::t('frontend', 'FAQ'), 'url' => ['/site/faq']];
                        $menuItems[] = ['label' => Yii::t('frontend', 'Contacts'), 'url' => ['/contact/index']];

                        if (Yii::$app->user->isGuest) {
                            $menuItems[] = ['label' => Yii::t('frontend', 'Sign Up'), 'url' => ['/auth/signup/index']];
                            $menuItems[] = ['label' => Yii::t('frontend', 'Sign In'), 'url' => ['/auth/auth/login']];
                        } else {
                            $menuItems[] = '<li>'
                                . Html::beginForm(['/auth/auth/logout'], 'post')
                                . Html::submitButton(
                                    Yii::t('frontend', 'Log out').' (' . Yii::$app->user->identity->username . ')',
                                    ['class' => 'btn waves-light logout']
                                )
                                . Html::endForm()
                                . '</li>';
                        }

                        echo Nav::widget([
                            'options' => ['class' => 'right hide-on-med-and-down'],
                            'items' => $menuItems,
                        ]);
                        echo ListWidget::widget()
                    ?>
                </div>
            </div>
        </nav>

        <?=
        Nav::widget([
            'options' => ['class' => 'sidenav center', 'id' => 'mobile-demo'],
            'items' => $menuItems,
        ]);
        ?>

    </header>
</header>

<main class="content">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

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
