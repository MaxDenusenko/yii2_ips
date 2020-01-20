<?php


namespace core\forms\manage\Core;


use core\entities\Core\Menu;
use yii\base\Model;

class MenuForm extends Model
{
    public $name;
    public $menu_depth;

    private $_menu;

    public function __construct(Menu $menu = null, $config = [])
    {
        if ($menu) {
            $this->name = $menu->name;
            $this->menu_depth = $menu->menu_depth;
            $this->_menu = $menu;
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'menu_depth'], 'required'],
            [['menu_depth'], 'integer', 'min' => 1],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique', 'targetClass' => Menu::class, 'filter' => $this->_menu ? ['<>', 'id', $this->_menu->id] : null]
        ];
    }
}
