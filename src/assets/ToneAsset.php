<?php
/**
 * @link https://skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\brand\assets;

use yii\web\AssetBundle;

/** Accent tones (data-sx-tone → --sx-tone, --sx-tone-rgb). A dependency of the bundles that use them. CSS only. */
class ToneAsset extends AssetBundle
{
    public $sourcePath = __DIR__.'/src';

    public $css = ['css/tones.css'];
}
