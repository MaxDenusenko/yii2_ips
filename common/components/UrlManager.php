<?php


namespace common\components;


use Yii;
use yii\web\UrlManager as YiiUrlManager;

class UrlManager extends YiiUrlManager
{
    public function createUrl($params) {

        //Получаем сформированную ссылку(без идентификатора языка)
        $url = parent::createUrl($params);

        if (empty($params['lang'])) {
            //текущий язык приложения
            $currentLang = Yii::$app->language;

            //Добавляем к URL префикс - буквенный идентификатор языка
            if ($url == '/') {
                return '/' . $currentLang;
            } else {
                return '/' . $currentLang . $url;
            }
        };

        return $url;
    }
}
