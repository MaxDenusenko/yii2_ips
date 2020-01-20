<?php


namespace core\helpers\widget;


use Exception;
use yii\base\InvalidConfigException;
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\helpers\ArrayHelper;

class MainHorizontalMenu extends Nav
{
    public $urlAttribute = 'url';

    public function init()
    {
        parent::init();

        Html::removeCssClass($this->options, ['widget' => 'nav']);
        $this->dropDownCaret = '<span class="plus js-plus-icon"></span>';
    }

    /**
     * @param array|string $item
     * @return array|string
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function renderItem($item)
    {
        if (is_string($item)) {
            return $item;
        }
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }
        $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
        $label = $encodeLabel ? Html::encode($item['label']) : $item['label'];
        $options = ArrayHelper::getValue($item, 'options', []);
        $items = ArrayHelper::getValue($item, 'items');
        $url = ArrayHelper::getValue($item, $this->urlAttribute, '#');
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);

        if ($item['font_awesome_icon_class']) {
            $label = '<i class="fa '.$item['font_awesome_icon_class'].'"></i>' . $label;
        }

        if (isset($item['active'])) {
            $active = ArrayHelper::remove($item, 'active', false);
        } else {
            $active = $this->isItemActive($item);
        }

        if (empty($items)) {
            $items = '';
            $caret = '';
        } else {
            $item['options'] = ['class'=>'menu-item-has-child dropdown'];
            $item['dropDownOptions'] = ['class'=>'dropdown-menu menu-level'];
            $options = ArrayHelper::getValue($item, 'options', []);
            $caret = $this->dropDownCaret;

            if (is_array($items)) {
                $items = $this->isChildActive($items, $active);
                $items = $this->renderDropdown($items, $item);
            }
        }

        if ($active) {
            Html::addCssClass($options, 'active');
        }

        return Html::tag('li', Html::a($label, $url ? $url : false, $linkOptions) . $caret . $items, $options);

    }

    /**
     * Renders the given items as a dropdown.
     * This method is called to create sub-menus.
     * @param array $items the given items. Please refer to [[Dropdown::items]] for the array structure.
     * @param array $parentItem the parent item information. Please refer to [[items]] for the structure of this array.
     * @return string the rendering result.
     * @throws Exception
     * @since 2.0.1
     */
    protected function renderDropdown($items, $parentItem)
    {
        $result = '';
        foreach ($items as $k => $item) {
            $result .= $this->renderItem($item);
        }

        return Html::tag('ul', $result, ['class' => 'dropdown-menu menu-level']);
    }
}
