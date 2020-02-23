<?php


namespace core\services\manage\Core;


use core\entities\Core\CategoryTariffs;
use core\repositories\Core\CategoryTariffsRepository;
use core\repositories\Core\TariffRepository;

class CategoryTariffsManageService
{
    private $categoryTariffsRepository;
    private $tariffsRepository;

    public function __construct(
        CategoryTariffsRepository $categoryTariffsRepository,
        TariffRepository $tariffRepository
    )
    {
        $this->categoryTariffsRepository = $categoryTariffsRepository;
        $this->tariffsRepository = $tariffRepository;
    }

    public function create(CategoryTariffs $model): CategoryTariffs
    {
        $parent = $this->categoryTariffsRepository->get($model->parentId);

        $model->appendTo($parent);
        $this->categoryTariffsRepository->save($model);
        return $model;
    }

    public function edit($id, CategoryTariffs $model): void
    {
        $this->assertIsNotRoot($model);

        if ($model->parentId != $model->parent->id) {
            $parent = $this->categoryTariffsRepository->get($model->parentId);
            $model->appendTo($parent);
        }
        $this->categoryTariffsRepository->save($model);
    }

    public function moveUp($id): void
    {
        $tariffCategory = $this->categoryTariffsRepository->get($id);
        $this->assertIsNotRoot($tariffCategory);
        if ($prev = $tariffCategory->prev) {
            $tariffCategory->insertBefore($prev);
        }
        $this->categoryTariffsRepository->save($tariffCategory);
    }

    public function moveDown($id): void
    {
        $tariffCategory = $this->categoryTariffsRepository->get($id);
        $this->assertIsNotRoot($tariffCategory);
        if ($next = $tariffCategory->next) {
            $tariffCategory->insertAfter($next);
        }
        $this->categoryTariffsRepository->save($tariffCategory);
    }

    public function remove($id): void
    {
        $tariffCategory = $this->categoryTariffsRepository->get($id);
        $this->assertIsNotRoot($tariffCategory);
        if ($this->tariffsRepository->existsByMainCategory($tariffCategory->id)) {
            throw new \DomainException('Unable to remove category with tariffs.');
        }
        $this->categoryTariffsRepository->remove($tariffCategory);
    }

    private function assertIsNotRoot(CategoryTariffs $tariffCategory): void
    {
        if ($tariffCategory->isRoot()) {
            throw new \DomainException('Unable to manage the root category.');
        }
    }

}
