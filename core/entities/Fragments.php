<?php

namespace core\entities;

use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "fragments".
 *
 * @property int $id
 * @property string|null $name
 */
class Fragments extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%fragments}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['text'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => \Yii::t('frontend', 'Name'),
            'text' => Yii::t('frontend', 'Text'),
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
                'langClassName' => FragmentsLang::className(),
                'defaultLanguage' => Yii::$app->sourceLanguage,
                'langForeignKey' => 'fragment_id',
                'tableName' => "{{%fragments_lang}}",
                'attributes' => [
                    'text',
                ]
            ],
        ];
    }

    public static function find()
    {
        return new MultilingualQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
}
