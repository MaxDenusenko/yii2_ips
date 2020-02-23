<?php

namespace core\entities;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "fragments_lang".
 *
 * @property int $id
 * @property int $fragment_id
 * @property string $language
 * @property string|null $text
 *
 * @property Fragments $fragment
 */
class FragmentsLang extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%fragments_lang}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['language'], 'required'],
            [['fragment_id'], 'integer'],
            [['text'], 'string'],
            [['language'], 'string', 'max' => 255],
            [['fragment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fragments::className(), 'targetAttribute' => ['fragment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'fragment_id' => Yii::t('frontend', 'Fragment'),
            'language' => Yii::t('frontend', 'Language'),
            'text' => Yii::t('frontend', 'Text'),
        ];
    }

    /**
     * Gets query for [[Fragment]].
     *
     * @return ActiveQuery
     */
    public function getFragment()
    {
        return $this->hasOne(Fragments::className(), ['id' => 'fragment_id']);
    }
}
