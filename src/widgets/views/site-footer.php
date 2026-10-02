<?php
/**
 * @link https://skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $widget skeeks\brand\widgets\SiteFooter */
?>
<?= Html::beginTag('footer', $widget->options); ?>
    <div class="sx-new-footer__panel">
        <div class="sx-new-footer__cta<?= $widget->start ? ' sx-new-footer__cta--start' : ''; ?>">
            <div>
                <?php if ($widget->eyebrow) : ?><span class="sx-new-footer__eyebrow"><?= Html::encode($widget->eyebrow); ?></span><?php endif; ?>
                <?php if ($widget->title) : ?><h2><?= $widget->title; ?></h2><?php endif; ?>
                <?php if ($widget->text) : ?><p><?= Html::encode($widget->text); ?></p><?php endif; ?>
            </div>
            <?php if ($widget->start) : ?>
                <a class="sx-new-footer__start" href="<?= Url::to($widget->start['url']); ?>"><span><?= Html::encode($widget->start['label']); ?></span><i class="fas fa-arrow-right" aria-hidden="true"></i></a>
            <?php else : ?>
                <?php if ($widget->contacts) : ?>
                    <div class="sx-new-footer__contacts">
                        <?php foreach ($widget->contacts as $contact) : ?>
                            <a href="<?= Html::encode($contact['url']); ?>"><i class="<?= Html::encode($contact['icon']); ?>" aria-hidden="true"></i><strong><?= Html::encode($contact['label']); ?></strong></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if ($widget->button) : ?>
                    <?= Html::a(Html::encode($widget->button['label']), $widget->button['url'], array_merge(['class' => 'sx-new-footer__button'], $widget->button['options'] ?? [])); ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="sx-new-footer__nav">
            <?= $widget->logo; ?>
            <?php if ($widget->menu) : ?>
                <nav aria-label="Навигация в подвале">
                    <?php foreach ($widget->menu as [$url, $label]) : ?><a href="<?= Url::to($url); ?>"><?= Html::encode($label); ?></a><?php endforeach; ?>
                </nav>
            <?php endif; ?>
            <?php if ($widget->socials) : ?>
                <div class="sx-new-footer__socials">
                    <?php foreach ($widget->socials as $social) : ?><a href="<?= Html::encode($social['url']); ?>" target="_blank" rel="noopener" aria-label="<?= Html::encode($social['label']); ?>"><i class="<?= Html::encode($social['icon']); ?>" aria-hidden="true"></i></a><?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="sx-new-footer__legal">
            <span><?= Html::encode($widget->copyright); ?></span>
            <?php if ($widget->legalLinks) : ?>
                <div><?php foreach ($widget->legalLinks as [$url, $label]) : ?><a href="<?= Url::to($url); ?>"><?= Html::encode($label); ?></a><?php endforeach; ?></div>
            <?php endif; ?>
        </div>
    </div>
<?= Html::endTag('footer'); ?>
