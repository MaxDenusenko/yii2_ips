<?php

namespace core\entities\Core;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tariffs_lang".
 *
 * @property int $id
 * @property int $tariffs_id
 * @property string $language
 * @property string|null $description
 * @property string $name
 *
 * @property Tariff $tariffs
 */
class TariffsLang extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{tariffs_lang}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['language'], 'required'],
            [['tariffs_id'], 'integer'],
            [['description'], 'string'],
            [['language', 'name'], 'string', 'max' => 255],
            [['tariffs_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tariff::className(), 'targetAttribute' => ['tariffs_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'tariffs_id' => Yii::t('frontend', 'Tariffs ID'),
            'language' => Yii::t('frontend', 'Language'),
            'description' => Yii::t('frontend', 'Description'),
            'name' => Yii::t('frontend', 'Name'),
        ];
    }

    /**
     * Gets query for [[Tariffs]].
     *
     * @return ActiveQuery
     */
    public function getTariffs()
    {
        return $this->hasOne(Tariff::className(), ['id' => 'tariffs_id']);
    }
}
