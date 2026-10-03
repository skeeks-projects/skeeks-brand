<?php
/**
 * @link https://skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\brand\assets;

use yii\web\AssetBundle;

/** CSS 3D scene and its on-screen animation switch; registered by the Scene widget. */
class SceneAsset extends AssetBundle
{
    public $sourcePath = __DIR__.'/src';

    public $css = ['css/scene.css'];

    public $js = ['js/scene.js'];

    public $depends = [ToneAsset::class];
}
