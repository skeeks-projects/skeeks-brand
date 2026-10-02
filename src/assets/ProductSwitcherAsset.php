<?php
/**
 * @link https://skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\brand\assets;

use yii\web\AssetBundle;

/** Registered by the ProductSwitcher widget; no other dependencies. */
class ProductSwitcherAsset extends AssetBundle
{
    public $sourcePath = __DIR__.'/src';

    public $css = ['css/product-switcher.css'];

    public $js = ['js/product-switcher.js'];
}
