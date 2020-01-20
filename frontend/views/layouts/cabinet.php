<?php $this->beginContent('@frontend/views/layouts/main.php');

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;
?>


<h1><?= Html::encode($this->title) ?></h1>
<br>
<div class="row">
    <div class="col-sm-4 col-md-3 sidebar">
        <div class="mini-submenu">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </div>
        <div class="list-group">
            <a href="<?= Html::encode(Url::to(['cabinet/tariffs/index'])) ?>" class="list-group-item <?= Yii::$app->controller->id == 'cabinet/tariffs' ? 'active' : null ?>">Тарифы</a>
            <a href="<?= Html::encode(Url::to(['cabinet/setting/index'])) ?>" class="list-group-item <?= Yii::$app->controller->id == 'cabinet/setting' ? 'active' : null ?>">Настройки</a>
            <a href="<?= Html::encode(Url::to(['cabinet/my-tariffs/index'])) ?>" class="list-group-item <?= Yii::$app->controller->id == 'cabinet/my-tariffs' ? 'active' : null ?>">Мои тарифы</a>
        </div>
    </div>
    <div class="col-sm-8 col-md-9">
        <?= $content ?>
    </div>
</div>

<?php $this->endContent() ?>
