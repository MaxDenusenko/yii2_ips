<?php

namespace core\entities;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "news_lang".
 *
 * @property int $id
 * @property int $news_id
 * @property string $language
 * @property string $title
 * @property string $body
 *
 * @property News $news
 */
class NewsLang extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%news_lang}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['news_id', 'language', 'title', 'body'], 'required'],
            [['news_id'], 'integer'],
            [['body'], 'string'],
            [['language', 'title'], 'string', 'max' => 255],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'news_id' => Yii::t('frontend', 'News ID'),
            'language' => Yii::t('frontend', 'Language'),
            'title' => Yii::t('frontend', 'Title'),
            'body' => Yii::t('frontend', 'Body'),
        ];
    }

    /**
     * Gets query for [[News]].
     *
     * @return ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::className(), ['id' => 'news_id']);
    }
}
