<?php
/** @var ActiveDataProvider $faqDataProvider */
/** @var Faq $question */

use core\entities\Faq;
use yii\data\ActiveDataProvider;

$this->title = 'FAQ';
$this->params['breadcrumbs'][] = $this->title;
?>

<ul class="collapsible popout">
    <?php foreach ($faqDataProvider->getModels() as $k => $question): ?>
        <li>
            <div class="collapsible-header"><?=$question->question?></div>
            <div class="collapsible-body"><span><?=$question->answer?></span></div>
        </li>
    <?php endforeach; ?>
</ul>
