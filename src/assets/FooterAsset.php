<?php
/**
 * @link https://skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\brand\assets;

use yii\web\AssetBundle;

/** Footer and cookie notice styles; registered by the SiteFooter widget. CSS only. */
class FooterAsset extends AssetBundle
{
    public $sourcePath = __DIR__.'/src';

    public $css = ['css/footer.css'];
}
