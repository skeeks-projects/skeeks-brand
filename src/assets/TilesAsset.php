<?php
/**
 * @link https://skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\brand\assets;

use yii\web\AssetBundle;

/** Lit panel cards (sx-tiles, sx-tile). Register only on pages that render tiles. CSS only. */
class TilesAsset extends AssetBundle
{
    public $sourcePath = __DIR__.'/src';

    public $css = ['css/tiles.css'];

    public $depends = [ToneAsset::class];
}
