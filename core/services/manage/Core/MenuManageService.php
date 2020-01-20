<?php


namespace core\services\manage\Core;


use core\entities\Core\Menu;
use core\forms\manage\Core\MenuForm;
use core\forms\manage\Core\MenuFormItem;
use core\repositories\Core\MenuRepository;

class MenuManageService
{
    private $menus;

    public function __construct(MenuRepository $menus)
    {
        $this->menus = $menus;
    }

    public function addItem($id, MenuFormItem $form): void
    {
        $parent = $this->menus->get($form->parentId);
        $item = Menu::createRootItem(
            $form->name,
            $form->url,
            $form->font_awesome_icon_class
        );

        $item->appendTo($parent);
        $this->menus->save($item);
    }

    public function editItem($id, MenuFormItem $form): void
    {
        $item = $this->menus->get($id);
        $this->assertIsNotRoot($item);

        $item->editRootItem(
            $form->name,
            $form->url,
            $form->font_awesome_icon_class
        );

        if ($form->parentId != $item->parent->id) {
            $parent = $this->menus->get($form->parentId);
            $item->appendTo($parent);
        }
        $this->menus->save($item);
    }

    public function moveUp($id): void
    {
        $menuItem = $this->menus->get($id);
        $this->assertIsNotRoot($menuItem);
        if ($prev = $menuItem->prev) {
            $menuItem->insertBefore($prev);
        }
        $this->menus->save($menuItem);
    }

    public function moveDown($id): void
    {
        $menuItem = $this->menus->get($id);
        $this->assertIsNotRoot($menuItem);
        if ($next = $menuItem->next) {
            $menuItem->insertAfter($next);
        }
        $this->menus->save($menuItem);
    }

    public function createRootNode(MenuForm $form): Menu
    {
        $menu = Menu::createRootNode(
            $form->name,
            $form->menu_depth
        );

        $menu->makeRoot();
        $this->menus->save($menu);
        return $menu;
    }

    public function editRootNode($id, MenuForm $form): void
    {
        $menu = $this->menus->get($id);

        $menu->editRootNode(
            $form->name,
            $form->menu_depth
        );
        $this->menus->save($menu);
    }

    private function assertIsNotRoot(Menu $menu): void
    {
        if ($menu->isRoot()) {
            throw new \DomainException('Unable to manage the root category.');
        }
    }

    private function assertIsRoot(Menu $menu): void
    {
        if (!$menu->isRoot()) {
            throw new \DomainException('Unable to manage the root category.');
        }
    }
}