<?php


namespace core\helpers;


use core\entities\Core\Menu;
use core\services\NestedSetsTreeMenu;

class MenuArrayHelper
{
    /**
     * @param $rootName string
     * @param null $urlAttribute
     * @return array
     */
    static function getData(string $rootName, $urlAttribute = null): array
    {
        $menu = [];

        if (!$root = Menu::findOne(['name' => $rootName]))
            return $menu;

        $root->isRoot();
        $collection = $root->getDescendants($root->menu_depth+1)->asArray()->all();

        if($collection){
            $nsTree = new NestedSetsTreeMenu();
            $urlAttribute == null ? : $nsTree->urlAttribute = $urlAttribute;
            $menu = $nsTree->tree($collection); //создаем дерево в виде массива
        }

        return $menu;
    }
}
