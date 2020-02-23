<?php

namespace core\entities;

use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $slug
 * @property string $title
 * @property string $body
 *
 * @property NewsLang[] $newsLangs
 */
class News extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%news}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['slug'], 'string', 'max' => 255],
            [['body'], 'string'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'updated_at' => Yii::t('frontend', 'Updated At'),
            'slug' => Yii::t('frontend', 'Slug'),
        ];
    }

    /**
     * Gets query for [[NewsLangs]].
     *
     * @return ActiveQuery
     */
    public function getNewsLangs()
    {
        return $this->hasMany(NewsLang::className(), ['news_id' => 'id']);
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
                'langClassName' => NewsLang::className(),
                'defaultLanguage' => Yii::$app->sourceLanguage,
                'langForeignKey' => 'news_id',
                'tableName' => "{{%news_lang}}",
                'attributes' => [
                    'body', 'title'
                ],
            ],
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public static function find()
    {
        return new MultilingualQuery(get_called_class());
    }
}
