<?php

/* @var $this View */
/* @var $content string */

use core\helpers\MenuArrayHelper;
use core\helpers\widget\MainHorizontalMenu;
use yii\helpers\Html;
use yii\helpers\Url;
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
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <linl href="<?= Html::encode(Url::canonical()) ?>" rel="canonical"></linl>
    <?php $this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header>
    <?= MainHorizontalMenu::widget([
        'options' => ['class' => 'menubar js-menubar'],
        'items' => (new MenuArrayHelper)->getData('horizontal main menu'),
    ]);
    ?>
</header>
<!-- /header -->

<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
