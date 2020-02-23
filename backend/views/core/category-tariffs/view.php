<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model core\entities\Core\CategoryTariffs */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Category Tariffs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="category-tariffs-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box box-default">
        <div class="box-header with-border"><?=\Yii::t('frontend', 'Common')?></div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'slug',
                ],
            ]) ?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border"><?=\Yii::t('frontend', 'Lang attributes')?></div>
        <div class="box-body">

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <?php foreach (Yii::$app->getModule('languages')->languages as $k => $language) : ?>
                        <li class="<?=$language == Yii::$app->sourceLanguage ? 'active' : '';?>"><a href="#tab_<?=$k?>" data-toggle="tab"><?=$language?></a></li>
                    <?php endforeach; ?>
                </ul>
                <div class="tab-content">
                    <?php foreach (Yii::$app->getModule('languages')->languages as $k => $language) : ?>
                        <div class="tab-pane <?=$language == Yii::$app->sourceLanguage ? 'active' : '';?>" id="tab_<?=$k?>">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    "name_$language",
                                    "description_$language:html",
                                ],
                            ]) ?>
                        </div>
                    <?php endforeach; ?>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
        </div>
    </div>

</div>
