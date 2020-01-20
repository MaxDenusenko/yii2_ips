<?php
use yii\web\View;

/**
 * @var $this View;
 * @var $content string;
 */
?>

<?php $this->beginContent('@frontend/views/layouts/main.php') ?>

<?= $content ?>

<?php $this->endContent() ?>
