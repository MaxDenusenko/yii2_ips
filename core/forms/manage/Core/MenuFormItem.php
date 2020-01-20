<?php


namespace core\forms\manage\Core;


use core\entities\Core\Menu;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class MenuFormItem extends Model
{
    public $name;
    public $url;
    public $font_awesome_icon_class;
    public $parentId;

    private $_menu;

    public function __construct(Menu $menu = null, $config = [])
    {
        if ($menu) {
            $this->name = $menu->name;
            $this->url = $menu->url;
            $this->font_awesome_icon_class = $menu->font_awesome_icon_class;
            $this->parentId = $menu->parent ? $menu->parent->id : null;
            $this->_menu = $menu;
        }

        parent::__construct($config);
    }

    public function parentCategoriesList($root_id): array
    {
        $root = Menu::findOne($root_id);
        $array = ArrayHelper::map($root->getDescendants($root->menu_depth)->asArray()->all(), 'id', function (array $menu) {
            return ($menu['depth'] > 1 ? str_repeat('-- ', $menu['depth'] - 1) . ' ': '') . $menu['name'];
        });
        $array[$root->id] = "-- In root {$root->name} --";
        $tempArr[$root->id] = array_pop($array);
        $array = array_replace($tempArr, $array);

        return $array;
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'url', 'font_awesome_icon_class'], 'string', 'max' => 255],
            [['parentId'], 'integer'],
        ];
    }
}
