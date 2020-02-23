<?php

use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model core\entities\Faq */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="faq-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border"><?=\Yii::t('frontend', 'Lang attribute')?></div>
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
                            <?= $form->field($model, $language == Yii::$app->sourceLanguage ? 'question' : "question_$language")->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, $language == Yii::$app->sourceLanguage ? 'answer' : "answer_$language")->widget(Widget::className(), [
                                'settings' => [
                                    'lang' => 'ru',
                                    'minHeight' => 200,
                                    'plugins' => [
                                        'clips',
                                        'fullscreen',
                                    ],
                                    'clips' => [
                                        ['red', '<span class="label-red">red</span>'],
                                        ['green', '<span class="label-green">green</span>'],
                                        ['blue', '<span class="label-blue">blue</span>'],
                                    ],
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

    <div class="form-group">
        <?= Html::submitButton(\Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
