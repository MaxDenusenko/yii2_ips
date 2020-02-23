<?php

namespace core\entities;

use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
use Yii;

/**
 * This is the model class for table "faq".
 *
 * @property int $id
 * @property string|null $question
 * @property string|null $answer
 */
class Faq extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%faq}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['answer'], 'string'],
            [['question'], 'string', 'max' => 255],
            [['question', 'answer'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question' => \Yii::t('frontend', 'Question'),
            'answer' => \Yii::t('frontend', 'Answer'),
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function behaviors()
    {
        return [
            'ml' => [
                'class' => MultilingualBehavior::className(),
                'languages' => Yii::$app->params['languages'],
                'languageField' => 'language',
                //'localizedPrefix' => '',
                //'requireTranslations' => false,
                'dynamicLangClass' => true,
                'langClassName' => FaqLang::className(),
                'defaultLanguage' => Yii::$app->sourceLanguage,
                'langForeignKey' => 'faq_id',
                'tableName' => "{{%faq_lang}}",
                'attributes' => [
                    'answer', 'question'
                ]
            ],
        ];
    }

    public static function find(): MultilingualQuery
    {
        return new MultilingualQuery(static::class);
    }
}
