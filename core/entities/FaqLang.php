<?php

namespace core\entities;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "faq_lang".
 *
 * @property int $id
 * @property int $faq_id
 * @property string $language
 * @property string $question
 * @property string $answer
 *
 * @property Faq $faq
 */
class FaqLang extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%faq_lang}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['language', 'question', 'answer'], 'required'],
            [['faq_id'], 'integer'],
            [['answer'], 'string'],
            [['language', 'question'], 'string', 'max' => 255],
            [['faq_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faq::className(), 'targetAttribute' => ['faq_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'faq_id' => Yii::t('frontend', 'Faq'),
            'language' => Yii::t('frontend', 'Language'),
            'question' => Yii::t('frontend', 'Question'),
            'answer' => Yii::t('frontend', 'Answer'),
        ];
    }

    /**
     * Gets query for [[Faq]].
     *
     * @return ActiveQuery
     */
    public function getFaq()
    {
        return $this->hasOne(Faq::className(), ['id' => 'faq_id']);
    }
}
