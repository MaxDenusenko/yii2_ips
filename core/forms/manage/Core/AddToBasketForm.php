<?php


namespace core\forms\manage\Core;


use core\entities\Core\Tariff;
use yii\base\Model;

class AddToBasketForm extends Model
{
    public $count;
    public $id_product;

    private $_basket;

    public function __construct($config = [])
    {
        $this->count = 1;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id_product'], 'required'],
            [['id_product'], 'integer'],
            [['count'], 'integer', 'min' => 1],
            [['id_product'], 'exist', 'skipOnError' => false, 'targetClass' => Tariff::className(), 'targetAttribute' => ['id_product' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_product' => false,
            'count' => 'Количество',
        ];
    }
}
