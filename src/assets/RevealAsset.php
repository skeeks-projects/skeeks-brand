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
 * Smooth appearance of blocks marked with data-sx-reveal. Register it only on
 * pages that use the attribute: RevealAsset::register($this).
 */
class RevealAsset extends AssetBundle
{
    public $sourcePath = __DIR__.'/src';

    public $css = ['css/reveal.css'];

    public $js = ['js/reveal.js'];
}
