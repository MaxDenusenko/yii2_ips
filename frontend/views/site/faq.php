<?php
/** @var ActiveDataProvider $faqDataProvider */
/** @var Faq $question */

use core\entities\Faq;
use yii\data\ActiveDataProvider;

?>
<div class="panel-group" id="accordion">
    <?php foreach ($faqDataProvider->getModels() as $k => $question): ?>
        <!-- 1 панель -->
        <div class="panel panel-default">
            <!-- Заголовок 1 панели -->
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne<?=$k?>"><?=$question->question?></a>
                </h4>
            </div>
            <div id="collapseOne<?=$k?>" class="panel-collapse collapse">
                <!-- Содержимое 1 панели -->
                <div class="panel-body">
                    <?=$question->answer?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
