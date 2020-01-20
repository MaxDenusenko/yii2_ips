<?php


namespace core\repositories\Core;


use core\entities\Core\Menu;
use core\repositories\NotFoundException;
use yii\db\StaleObjectException;

class MenuRepository
{
    /**
     * @param $id
     * @return Menu
     */
    public function get($id): Menu
    {
        if (!$menu = Menu::findOne($id)) {
            throw new NotFoundException('Menu is not found.');
        }
        return $menu;
    }

    /**
     * @param Menu $menu
     */
    public function save(Menu $menu): void
    {
        if (!$menu->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param Menu $menu
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function remove(Menu $menu): void
    {
        if (!$menu->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}
