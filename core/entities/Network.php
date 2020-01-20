<?php


namespace core\entities;


use Webmozart\Assert\Assert;
use yii\db\ActiveRecord;

/**
 * Class Network
 * @package core\entities
 * @property integer $user_id
 * @property string $identity
 * @property string $network
 * @property int $id [int(11)]
 */
class Network extends ActiveRecord
{
    /**
     * @param $network
     * @param $identity
     * @return static
     */
    public static function create($network, $identity): self
    {
        Assert::notEmpty($network);
        Assert::notEmpty($identity);

        $item = new static();
        $item->network = $network;
        $item->identity = $identity;
        return $item;
    }

    /**
     * @param $network
     * @param $identity
     * @return bool
     */
    public function isFor($network, $identity): bool
    {
        return $this->network === $network && $this->identity === $identity;
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%user_networks}}';
    }
}