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
 * Page blocks in the SkeekS platform style: sx-landing, hero, sections, kicker, lead,
 * buttons, chips, split, points, stack, steps and FAQ. Register it only on pages whose
 * template or CMS text uses these classes: BlocksAsset::register($this). CSS only.
 */
class BlocksAsset extends AssetBundle
{
    public $sourcePath = __DIR__.'/src';

    public $css = ['css/blocks.css'];

    public $depends = [ToneAsset::class];
}
