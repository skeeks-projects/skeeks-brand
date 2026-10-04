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
 * Scripted page demonstrations: on-screen switch for [data-sx-live] sections and the
 * window.sxDemo scenario runner with cursor helpers. Scenarios and their markup stay
 * in the site; register this bundle as a dependency of the site's demo script.
 */
class DemoAsset extends AssetBundle
{
    public $sourcePath = __DIR__.'/src';

    public $css = ['css/demo.css'];

    public $js = ['js/demo.js'];
}
