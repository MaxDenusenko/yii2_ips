<?php

/* @var $this yii\web\View */
/* @var $other_tariffs Tariff[] */
/* @var $orderForm OrderForm */
/* @var $categories CategoryTariffs[] */
/* @var $news News[] */

$this->title = 'Proxy';

use core\entities\Core\CategoryTariffs;
use core\entities\Core\Tariff;
use core\entities\News;
use core\forms\manage\Core\OrderForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="row">
    <div class="col s12">

        <h3><?=\Yii::t('frontend', 'Tariffs')?></h3>

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


<?php if (count($news)) : ?>
    <div class="row">
        <h3><?=\Yii::t('frontend', 'News')?></h3>
            <?php foreach ($news as $new) : ?>
                <div class="col s12">
                    <div class="card blue-grey lighten-1">
                        <div class="card-content">
                            <?= Html::a($new->title, Url::to(['news/view', 'id' => $new->id]), ['class' => 'white-text']) ?>
                            <br>
                            <br>
                            <p><?= $new->created_at ?></p>
                        </div>
                        <div class="card-action">
                            <?= Html::a('Detail', Url::to(['news/view', 'id' => $new->id]), ['class' => 'white-text']) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
    </div>
<?php endif; ?>
