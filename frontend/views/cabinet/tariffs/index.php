<?php

/* @var $this yii\web\View */
/* @var $other_tariffs Tariff[] */
/* @var $orderForm OrderForm */
/* @var $categories CategoryTariffs[] */

use core\entities\Core\CategoryTariffs;
use core\entities\Core\Tariff;
use core\forms\manage\Core\OrderForm;
use yii\web\View;

$this->title = \Yii::t('frontend', 'Tariffs');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Personal')];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col s12">

        <?php if(count($categories)) : ?>
            <ul class="collapsible popout collapsible-main-tariffs">
                <?php foreach ($categories as $category) : ?>

                    <?php if (!count($category->tariffs) && !count($category->children)) continue; ?>
                    <?= $this->render('/cabinet/tariffs/_tariff_list', [
                        'categories' => $category,
                        'other_tariffs' => [],
                        'orderForm' => $orderForm,
                    ]) ?>

                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php if (count($other_tariffs)) : ?>
            <div class="card-panel">

                <table class="striped highlight centered responsive-table">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th><?=$other_tariffs[0]->getAttributeLabel('name')?></th>
                        <th><?=$other_tariffs[0]->getAttributeLabel('qty_proxy')?></th>
                        <th><?=$other_tariffs[0]->getAttributeLabel('price')?></th>
                        <th><?=$other_tariffs[0]->default[0]->getAttributeLabel('ip_quantity')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($other_tariffs as $tariff): ?>
                        <?= $this->render('/cabinet/tariffs/_tariff', [
                            'tariff' => $tariff,
                            'orderForm' => $orderForm,
                        ]) ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>
</div>
