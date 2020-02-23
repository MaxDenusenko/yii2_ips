<?php

namespace core\entities\Core;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "category_tariffs_lang".
 *
 * @property int $id
 * @property int $category_tariffs_id
 * @property string $language
 * @property string|null $description
 * @property string $name
 *
 * @property CategoryTariffs $categoryTariffs
 */
class CategoryTariffsLang extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category_tariffs_lang}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_tariffs_id', 'language', 'name'], 'required'],
            [['category_tariffs_id'], 'integer'],
            [['description'], 'string'],
            [['language', 'name'], 'string', 'max' => 255],
            [['category_tariffs_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryTariffs::className(), 'targetAttribute' => ['category_tariffs_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'category_tariffs_id' => Yii::t('frontend', 'Category Tariffs ID'),
            'language' => Yii::t('frontend', 'Language'),
            'description' => Yii::t('frontend', 'Description'),
            'name' => Yii::t('frontend', 'Name'),
        ];
    }

    /**
     * Gets query for [[CategoryTariffs]].
     *
     * @return ActiveQuery
     */
    public function getCategoryTariffs()
    {
        return $this->hasOne(CategoryTariffs::className(), ['id' => 'category_tariffs_id']);
    }
}
