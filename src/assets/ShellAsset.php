<?php
/**
 * @link https://skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\brand\assets;

use yii\web\AssetBundle;

/**
 * Shared site shell: brand tokens, header, search and drawer. Registered by the
 * SiteHeader widget. No jQuery, Bootstrap or Unify dependency.
 */
class ShellAsset extends AssetBundle
{
    public $sourcePath = __DIR__.'/src';

    public $css = ['css/shell.css'];

    public $js = ['js/shell.js'];

    public $depends = [ProductSwitcherAsset::class];
}
