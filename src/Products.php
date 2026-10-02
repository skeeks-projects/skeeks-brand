<?php
/**
 * @link https://skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\brand;

use yii\helpers\ArrayHelper;

/**
 * The single list of SkeekS products: the product switcher, logos and favicons
 * read names, links and accent colors from here instead of copying them per site.
 *
 * A project may adjust entries through Yii params:
 * 'skeeksBrand' => ['products' => ['ai' => ['url' => 'https://…', 'caption' => '…']]]
 */
class Products
{
    const SKEEKS = 'skeeks';
    const PLATFORM = 'platform';
    const GOODS = 'goods';
    const AI = 'ai';

    /**
     * Order is the order shown in the switcher. A product without `url` is
     * listed as not launched yet; do not invent URLs for such products.
     * `accent` matches the product favicon (brand/favicons/README.md).
     */
    public static function defaults(): array
    {
        return [
            self::SKEEKS   => [
                'name'    => 'SkeekS',
                'caption' => 'Сайт компании',
                'url'     => 'https://skeeks.com/',
                'icon'    => 'far fa-building',
                'accent'  => null,
            ],
            self::PLATFORM => [
                'name'    => 'SkeekS Платформа',
                'caption' => 'Сайты, торговля и управление компанией',
                'url'     => 'https://skeeks-platform.ru/',
                'icon'    => 'fas fa-layer-group',
                'accent'  => '#a6de58',
            ],
            self::GOODS    => [
                'name'    => 'SkeekS Товары',
                'caption' => 'Товарный контент и данные поставщиков',
                'url'     => 'https://skeeks-market.ru/',
                'icon'    => 'fas fa-box',
                'accent'  => '#efd740',
            ],
            self::AI       => [
                'name'    => 'SkeekS AI',
                'caption' => 'Готовится к запуску',
                'url'     => null,
                'icon'    => 'fas fa-brain',
                'accent'  => '#64dcdb',
            ],
        ];
    }

    public static function all(): array
    {
        $overrides = (array) ArrayHelper::getValue(\Yii::$app->params, 'skeeksBrand.products', []);
        return ArrayHelper::merge(static::defaults(), $overrides);
    }

    public static function get(string $id): ?array
    {
        return static::all()[$id] ?? null;
    }
}
