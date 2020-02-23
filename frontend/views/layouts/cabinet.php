<?php $this->beginContent('@frontend/views/layouts/main.php');

/* @var $this View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

?>

<br>

<div class="row">
    <div class="col s12 center">
        <a href="<?= Html::encode(Url::to(['cabinet/tariffs/index'])) ?>" class="waves-effect waves-light btn"><i class="material-icons left">home</i><?=\Yii::t('frontend', 'Tariffs')?></a>
        <a href="<?= Html::encode(Url::to(['cabinet/setting/index'])) ?>" class="waves-effect waves-light btn"><i class="material-icons left">person</i><?=\Yii::t('frontend', 'Profile')?></a>
        <a href="<?= Html::encode(Url::to(['cabinet/my-tariffs/index'])) ?>" class="waves-effect waves-light btn"><i class="material-icons left">dashboard</i><?=\Yii::t('frontend', 'Control Panel')?></a>
    </div>
</div>

<?= $content ?>

<?php $this->endContent() ?>
