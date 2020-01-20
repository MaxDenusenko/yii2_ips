<?php


namespace core\entities\Core\queries;


use paulzi\nestedsets\NestedSetsQueryTrait;
use yii\db\ActiveQuery;

class MenuQuery extends ActiveQuery
{
    use NestedSetsQueryTrait;
}
