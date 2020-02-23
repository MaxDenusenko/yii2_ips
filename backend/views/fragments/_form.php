<?php

use common\modules\languages\models\LanguageKsl;
use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model core\entities\Fragments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fragments-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border"><?=\Yii::t('frontend', 'Common')?></div>
        <div class="box-body">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?php foreach (Yii::$app->getModule('languages')->languages as $language) : ?>

                <?= $form->field($model, $language == Yii::$app->sourceLanguage ? 'text' : "text_$language")->widget(Widget::className(), [
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

            <?php endforeach; ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(\Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
