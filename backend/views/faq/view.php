<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model core\entities\Faq */

$this->title = $model->question;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Faqs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="faq-view">

    <p>
        <?= Html::a(\Yii::t('frontend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(\Yii::t('frontend', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => \Yii::t('frontend', 'Are you sure you want to delete this item?'),
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
                                    "question_$language",
                                    "answer_$language:html",
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
