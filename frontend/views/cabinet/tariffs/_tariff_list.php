<?php
/** @var CategoryTariffs[] $categories */
/** @var Tariff[] $other_tariffs */
/** @var Tariff[] $sortedTariffs */
/** @var OrderForm $orderForm */

use core\entities\Core\CategoryTariffs;
use core\entities\Core\Tariff;
use core\forms\manage\Core\OrderForm;

?>
<?php if ($categories->depth <= 1) : ?>

    <li>
        <div class="collapsible-header">

            <h3><?=$categories->name?></h3>
            <?php if ($categories->description) : ?>
                <?=$categories->description?>
            <?php endif; ?>

        </div>
        <div class="collapsible-body">

            <?php if (count($categories->tariffs)) : ?>

                <table class="striped highlight centered responsive-table">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th><?=$categories->tariffs[0]->getAttributeLabel('name')?></th>
                        <th><?=$categories->tariffs[0]->getAttributeLabel('qty_proxy')?></th>
                        <th><?=$categories->tariffs[0]->getAttributeLabel('price')?></th>
                        <th><?=$categories->tariffs[0]->default[0]->getAttributeLabel('ip_quantity')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($categories->tariffs as $tariff): ?>
                        <?= $this->render('_tariff', [
                            'tariff' => $tariff,
                            'orderForm' => $orderForm,
                        ]) ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php if (count($categories->children)) : ?>
                <?php foreach ($categories->children as $child) : ?>
                    <?= $this->render('/cabinet/tariffs/_tariff_list', [
                        'categories' => $child,
                        'other_tariffs' => [],
                        'orderForm' => $orderForm,
                    ]) ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </li>

<?php else: ?>

    <div class="<?=$categories->depth <= 1 ? 'card-panel' : ''?>">

        <?php if (count($categories->tariffs)) : ?>

            <h4><?=$categories->name?></h4>
            <?php if ($categories->description) : ?>
                <?=$categories->description?>
            <?php endif; ?>

            <table class="striped highlight centered responsive-table">
                <thead>
                <tr>
                    <th>№</th>
                    <th><?=$categories->tariffs[0]->getAttributeLabel('name')?></th>
                    <th><?=$categories->tariffs[0]->getAttributeLabel('qty_proxy')?></th>
                    <th><?=$categories->tariffs[0]->getAttributeLabel('price')?></th>
                    <th><?=$categories->tariffs[0]->default[0]->getAttributeLabel('ip_quantity')?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($categories->tariffs as $tariff): ?>
                    <?= $this->render('_tariff', [
                        'tariff' => $tariff,
                        'orderForm' => $orderForm,
                    ]) ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if (count($categories->children)) : ?>
            <?php foreach ($categories->children as $child) : ?>
                <?= $this->render('/cabinet/tariffs/_tariff_list', [
                    'categories' => $child,
                    'other_tariffs' => [],
                    'orderForm' => $orderForm,
                ]) ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

<?php endif; ?>