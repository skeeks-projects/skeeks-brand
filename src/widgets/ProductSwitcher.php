<?php
/**
 * @link https://skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\brand\widgets;

use skeeks\brand\assets\ProductSwitcherAsset;
use skeeks\brand\Products;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Product logo plus a separate arrow listing the SkeekS products.
 * The logo stays a link to the site home; only the arrow opens the list.
 *
 * <?= ProductSwitcher::widget(['current' => 'skeeks', 'brand' => $logoLinkHtml]); ?>
 *
 * Icons use Font Awesome 5 classes from the product registry; the site provides
 * Font Awesome. The site header shell must be `position:relative`: below 576px
 * the panel spans the header width.
 */
class ProductSwitcher extends Widget
{
    /** @var string Product id of this site, see Products. */
    public $current = Products::SKEEKS;

    /** @var string Ready HTML of the logo link placed before the arrow. */
    public $brand = '';

    /** @var string */
    public $title = 'SkeekS и продукты';

    /** @var array|null Product list; defaults to Products::all(). */
    public $products;

    public function run()
    {
        ProductSwitcherAsset::register($this->view);

        $listId = $this->getId().'-products';
        $items = [Html::tag('p', Html::encode($this->title))];
        foreach ($this->products ?? Products::all() as $id => $product) {
            $items[] = $this->renderItem($id, $product);
        }

        return Html::tag('div',
            $this->brand
            .Html::button('<i class="fas fa-chevron-down" aria-hidden="true"></i>', [
                'class'         => 'sx-product-switcher__toggle',
                'aria-label'    => 'Выбрать продукт SkeekS',
                'aria-expanded' => 'false',
                'aria-controls' => $listId,
            ])
            .Html::tag('nav', implode('', $items), [
                'id'         => $listId,
                'class'      => 'sx-product-switcher__panel',
                'aria-label' => 'Продукты SkeekS',
                'hidden'     => true,
            ]),
            ['class' => 'sx-product-switcher', 'data-sx-product-switcher' => true]
        );
    }

    protected function renderItem(string $id, array $product): string
    {
        $text = Html::tag('span',
            Html::tag('strong', Html::encode($product['name'])).Html::tag('small', Html::encode($product['caption']))
        );
        $icon = Html::tag('i', '', ['class' => $product['icon'], 'aria-hidden' => 'true']);

        if ($id === $this->current) {
            return Html::a($icon.$text.Html::tag('span', '✓', ['class' => 'sx-product-switcher__current', 'aria-label' => 'Текущий продукт']),
                Url::home(), ['aria-current' => 'true']);
        }
        if (empty($product['url'])) {
            return Html::tag('div', $icon.$text, ['class' => 'sx-product-switcher__soon']);
        }
        return Html::a($icon.$text.Html::tag('span', '↗', ['aria-hidden' => 'true']), $product['url']);
    }
}
