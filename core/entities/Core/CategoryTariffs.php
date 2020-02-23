<?php

namespace core\entities\Core;

use core\entities\Core\queries\CategoryTariffsQuery;
use omgdef\multilingual\MultilingualBehavior;
use paulzi\nestedsets\NestedSetsBehavior;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category_tariffs".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 *
 * @property string $slug
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 */
class CategoryTariffs extends ActiveRecord
{
    public $parentId;

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
            [['name', 'slug'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['parentId'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('frontend', 'Title'),
            'description' => Yii::t('frontend', 'Description'),
        ];
    }

    public function afterFind()
    {
//        $this->parentId = $this->parent->id;

        parent::afterFind();
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
                Yii::t('frontend', 'You cannot delete a category that has links.')
            );
            return false;
        }

        return parent::beforeDelete();
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
                'langClassName' => CategoryTariffsLang::className(),
                'defaultLanguage' => Yii::$app->sourceLanguage,
                'langForeignKey' => 'category_tariffs_id',
                'tableName' => "{{%category_tariffs_lang}}",
                'attributes' => [
                    'name', 'description'
                ]
            ],
            NestedSetsBehavior::className(),
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
            ],
        ];
    }

    public static function find(): CategoryTariffsQuery
    {
        return new CategoryTariffsQuery(static::class);
    }

    public function isRoot(): bool
    {
        return $this->id == 1;
    }

    public static function parentCategoriesList(): array
    {
        $arr[1] = 'root';
        $result = ArrayHelper::map(CategoryTariffs::find()->joinWith(['translation'])->orderBy('lft')->asArray()->all(), 'id', function (array $category) {
            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth'] - 1) . ' ': '') . $category['translation']['name'];
        });
        $result = array_replace($arr, $result);

        return $result;
    }
}
