<?php

use core\forms\manage\Core\TariffForm;

/* @var $this yii\web\View */
/* @var $model TariffForm */

$this->title = \Yii::t('frontend', 'Create Tariff');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('frontend', 'Tariffs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tariff-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
