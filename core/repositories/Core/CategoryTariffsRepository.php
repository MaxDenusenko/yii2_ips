<?php


namespace core\repositories\Core;


use core\entities\Core\CategoryTariffs;
use core\repositories\NotFoundException;
use yii\db\StaleObjectException;

class CategoryTariffsRepository
{
    /**
     * @param $id
     * @return CategoryTariffs
     */
    public function get($id): CategoryTariffs
    {
        if (!$categoryTariffs = CategoryTariffs::findOne($id)) {
            throw new NotFoundException(\Yii::t('frontend', 'Tariff category is not found.'));
        }
        return $categoryTariffs;
    }

    /**
     * @param CategoryTariffs $categoryTariffs
     */
    public function save(CategoryTariffs $categoryTariffs): void
    {
        if (!$categoryTariffs->save()) {
            throw new \RuntimeException(\Yii::t('frontend', 'Saving error.'));
        }
    }

    /**
     * @param CategoryTariffs $categoryTariffs
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function remove(CategoryTariffs $categoryTariffs): void
    {
        if (!$categoryTariffs->delete()) {
            throw new \RuntimeException(\Yii::t('frontend', 'Removing error.'));
        }
    }
}
