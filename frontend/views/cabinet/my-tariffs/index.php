<?php

/** @var View $this */
/** @var array $infoAr */
/** @var TariffAssignment[] $tariffs */

use core\entities\Core\TariffAssignment;
use yii\web\View;

$this->title = \Yii::t('frontend', 'Control Panel');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Personal')];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col s12">

        <?php if (count($infoAr)) : ?>
            <script>
                document.addEventListener('DOMContentLoaded', function(){
                    <?php foreach ($infoAr as $str): ?>
                    M.toast({html: '<?= $str; ?>'})
                    <?php endforeach; ?>
                });
            </script>
        <?php endif; ?>

        <?php if (count($tariffs)) : ?>

            <?= $this->render('_tariff_list', [
                'tariffs' => $tariffs
            ]) ?>

        <?php else: ?>

            <p><?=\Yii::t('frontend', 'You have no tariffs')?></p>

        <?php endif; ?>
    </div>
</div>
