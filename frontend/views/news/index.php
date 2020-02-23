<?php

/* @var $this yii\web\View */
/* @var $news News[] */

$this->title = \Yii::t('frontend', 'News');

use core\entities\News;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = \Yii::t('frontend', 'News');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php if (count($news)) : ?>
    <div class="row">
        <?php foreach ($news as $new) : ?>


            <div class="col s12">
                <div class="card blue-grey lighten-1">
                    <div class="card-content">
                        <?= Html::a($new->title, Url::to(['news/view', 'id' => $new->id]), ['class' => 'white-text']) ?>
                        <br>
                        <br>
                        <p><?= $new->created_at ?></p>
                    </div>
                    <div class="card-action">
                        <?= Html::a('Detail', Url::to(['news/view', 'id' => $new->id]), ['class' => 'white-text']) ?>
                    </div>
                </div>
            </div>


        <?php endforeach; ?>
    </div>
<?php endif; ?>
