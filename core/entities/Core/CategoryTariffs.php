<?php

namespace core\entities\Core;

use Yii;

/**
 * This is the model class for table "category_tariffs".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 */
class CategoryTariffs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category_tariffs}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'description' => 'Описание',
        ];
    }

    public function getTariffs()
    {
        return $this->hasMany(Tariff::class, ['category_id' => 'id']);
    }

    public function beforeDelete()
    {
        $tariffs = $this->tariffs;

        if(count($tariffs)) {
            Yii::$app->session->setFlash(
                'warning',
                'Нельзя удалить категорию, который имеет связи'
            );
            return false;
        }

        return parent::beforeDelete();
    }
}
