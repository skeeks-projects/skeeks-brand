<?php
/**
 * @link https://skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\brand\widgets;

use skeeks\brand\assets\SceneAsset;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * CSS 3D scene for a hero: a lit core on a tilted floor and up to six linked nodes.
 * No libraries; the animation runs only on screen and stops with reduced motion.
 *
 * <?= Scene::widget([
 *     'core'    => ['icon' => 'fas fa-layer-group', 'title' => 'SkeekS', 'sub' => 'веб-студия'],
 *     'nodes'   => [['url' => '#dev', 'icon' => 'fas fa-code', 'label' => 'Сайты', 'tone' => 'lime']],
 *     'tone'    => 'lime',
 *     'label'   => 'Направления услуг',
 *     'caption' => 'Одна команда — от макета до работы сайта.',
 * ]); ?>
 *
 * Up to three nodes form a triangle, four to six a ring. A node without url is not a link.
 * Icons are Font Awesome classes; the site provides Font Awesome.
 */
class Scene extends Widget
{
    /** @var array ['icon' => string, 'title' => string, 'sub' => string] */
    public $core = [];

    /** @var array Nodes: ['url' => ?string, 'icon' => string, 'label' => string, 'tone' => ?string] */
    public $nodes = [];

    /** @var string Tone of the scene: lime, pink, gold, cyan. */
    public $tone = 'lime';

    /** @var string Accessible name of the scene. */
    public $label = '';

    /** @var string Text under the scene; empty — none. */
    public $caption = '';

    public function run()
    {
        SceneAsset::register($this->view);

        $nodes = array_slice(array_values($this->nodes), 0, 6);
        $coords = count($nodes) <= 3
            ? [[23, 24], [81, 44], [39, 83]]
            : [[25, 20], [75, 20], [87, 51], [73, 83], [27, 83], [13, 51]];

        $paths = '';
        $links = '';
        foreach ($nodes as $i => $node) {
            [$x, $y] = $coords[$i];
            $paths .= '<path d="M50 50 L'.$x.' '.$y.'"/><path class="sx-scene__signal" d="M50 50 L'.$x.' '.$y.'"/>';
            $content = Html::tag('span', Html::tag('i', '', ['class' => $node['icon'] ?? '', 'aria-hidden' => 'true']), ['class' => 'sx-scene__node-icon'])
                .Html::tag('span', Html::encode($node['label'] ?? ''), ['class' => 'sx-scene__node-label']);
            $options = [
                'class'        => 'sx-scene__node',
                'style'        => '--node-x:'.$x.'%;--node-y:'.$y.'%',
                'data-sx-tone' => $node['tone'] ?? null,
            ];
            $links .= empty($node['url'])
                ? Html::tag('span', $content, $options)
                : Html::a($content, $node['url'], $options);
        }

        $core = $this->core;
        $world = '<div class="sx-scene__floor"></div><div class="sx-scene__orbit"></div><div class="sx-scene__orbit sx-scene__orbit--outer"></div>'
            .'<div class="sx-scene__layer sx-scene__layer--lower"></div><div class="sx-scene__layer sx-scene__layer--middle"></div><div class="sx-scene__layer sx-scene__layer--top"></div>';

        return Html::tag('figure',
            Html::tag('div',
                '<div class="sx-scene__aura" aria-hidden="true"></div>'
                .'<svg class="sx-scene__connections" viewBox="0 0 100 100" aria-hidden="true">'.$paths.'</svg>'
                .Html::tag('div', $world, ['class' => 'sx-scene__world', 'aria-hidden' => 'true'])
                .Html::tag('div',
                    Html::tag('i', '', ['class' => $core['icon'] ?? '', 'aria-hidden' => 'true'])
                    .Html::tag('strong', Html::encode($core['title'] ?? ''))
                    .(empty($core['sub']) ? '' : Html::tag('span', Html::encode($core['sub']))),
                    ['class' => 'sx-scene__core', 'aria-hidden' => 'true'])
                .$links,
                ['class' => 'sx-scene__canvas'])
            .($this->caption === '' ? '' : Html::tag('figcaption', Html::encode($this->caption))),
            [
                'class'         => 'sx-scene',
                'data-sx-scene' => true,
                'data-sx-tone'  => $this->tone,
                'aria-label'    => $this->label ?: null,
            ]);
    }
}
