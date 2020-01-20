<?php


namespace core\entities\Core;


use paulzi\nestedsets\NestedSetsBehavior;
use core\entities\Core\queries\MenuQuery;
use yii\db\ActiveRecord;

/**
 * Menu model
 *
 * @property integer $id
 * @property string $name
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property int $tree [int(11)]
 * @property string $url [varchar(255)]
 * @property int $menu_depth [int(11)]
 * @property string $font_awesome_icon_class [varchar(255)]
 */
class Menu extends ActiveRecord
{
    public static function createRootNode($name, $menu_depth): self
    {
        $menu = new static();
        $menu->name = $name;
        $menu->menu_depth = $menu_depth;
        return $menu;
    }

    public static function createRootItem($name, $url, $font_awesome_icon_class): self
    {
        $menu = new static();
        $menu->name = $name;
        $menu->url = $url;
        $menu->font_awesome_icon_class = $font_awesome_icon_class;
        return $menu;
    }

    public function editRootItem($name, $url, $font_awesome_icon_class): void
    {
        $this->name = $name;
        $this->url = $url;
        $this->font_awesome_icon_class = $font_awesome_icon_class;
    }

    public function editRootNode($name, $menu_depth): void
    {
        $this->name = $name;
        $this->menu_depth = $menu_depth;
    }

    public function behaviors() {
        return [
            [
                'class' => NestedSetsBehavior::class,
                'treeAttribute' => 'tree',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find(): MenuQuery
    {
        return new MenuQuery(static::class);
    }

    public function isRoot(): bool
    {
        return $this->depth == 0;
    }

    public static function tableName()
    {
        return '{{%shop_menus}}';
    }
}
