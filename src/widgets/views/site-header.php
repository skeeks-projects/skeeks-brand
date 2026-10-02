<?php
/**
 * @link https://skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

use skeeks\brand\widgets\ProductSwitcher;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $widget skeeks\brand\widgets\SiteHeader */

$cta = $widget->cta ? $widget->cta + ['options' => []] : null;
$ctaOptions = $cta ? array_merge(['class' => 'sx-site-header__cta'], $cta['options']) : [];
?>
<?= Html::beginTag('header', $widget->headerOptions); ?>
    <div class="sx-site-header__shell">
        <button class="sx-site-header__icon sx-menu-toggle" type="button" aria-label="Открыть полное меню" aria-controls="sx-full-menu" aria-expanded="false">
            <i class="fas fa-bars" aria-hidden="true"></i>
        </button>

        <?= ProductSwitcher::widget(['current' => $widget->product, 'brand' => $widget->brand]); ?>

        <?php if ($widget->menu) : ?>
            <nav class="sx-site-header__nav" aria-label="Основная навигация">
                <?php foreach ($widget->menu as [$url, $label]) : ?>
                    <a class="<?= $widget->isActive($url) ? 'is-active' : ''; ?>" href="<?= Url::to($url); ?>"><?= Html::encode($label); ?></a>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>

        <div class="sx-site-header__actions">
            <?php if ($widget->search) : ?>
                <button class="sx-site-header__icon sx-search-btn" type="button" aria-label="Поиск" aria-controls="sx-site-search" aria-expanded="false"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><circle cx="10.5" cy="10.5" r="7.5"/><path d="m16 16 5 5"/></svg></button>
            <?php endif; ?>
            <?php if ($widget->cabinetUrl) : ?>
                <a class="sx-site-header__icon" href="<?= Html::encode($widget->cabinetUrl); ?>" aria-label="<?= Html::encode($widget->cabinetLabel); ?>"><i class="far fa-user" aria-hidden="true"></i></a>
            <?php endif; ?>
            <?php if ($cta) : ?>
                <?= Html::a(Html::encode($cta['label']), $cta['url'], $ctaOptions); ?>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($widget->search) : ?>
        <form id="sx-site-search" class="sx-site-search" action="<?= Url::to($widget->search['action']); ?>" method="get" aria-hidden="true" inert>
            <input type="search" autocomplete="off" name="<?= Html::encode($widget->search['param']); ?>" value="<?= Html::encode($widget->search['value'] ?? ''); ?>" placeholder="Поиск по сайту..." aria-label="Поиск по сайту">
            <button type="submit">Найти</button>
        </form>
    <?php endif; ?>
<?= Html::endTag('header'); ?>
<div class="sx-site-header-spacer" aria-hidden="true"></div>

<div class="sx-menu-backdrop" data-sx-menu-close></div>
<aside id="sx-full-menu" class="sx-full-menu" role="dialog" aria-modal="true" aria-hidden="true" aria-label="Полное меню" inert>
    <div class="sx-full-menu__head">
        <?= $widget->drawerBrand; ?>
        <button class="sx-full-menu__close" type="button" data-sx-menu-close aria-label="Закрыть меню"><i class="fas fa-times" aria-hidden="true"></i></button>
    </div>
    <div class="sx-full-menu__scroll">
        <?php foreach ($widget->drawerGroups as $title => $items) : ?>
            <section class="sx-full-menu__group">
                <h2><?= Html::encode($title); ?></h2>
                <?php foreach ($items as [$url, $label, $icon]) : ?>
                    <a href="<?= Url::to($url); ?>">
                        <i class="<?= Html::encode($icon); ?>" aria-hidden="true"></i>
                        <span><?= Html::encode($label); ?></span>
                        <i class="fas fa-chevron-right sx-full-menu__arrow" aria-hidden="true"></i>
                    </a>
                <?php endforeach; ?>
            </section>
        <?php endforeach; ?>

        <?php if ($widget->contacts) : ?>
            <div class="sx-full-menu__contacts">
                <?php foreach ($widget->contacts as $contact) : ?>
                    <a href="<?= Html::encode($contact['url']); ?>"><i class="<?= Html::encode($contact['icon']); ?>" aria-hidden="true"></i><?= Html::encode($contact['label']); ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if ($widget->socials) : ?>
            <div class="sx-full-menu__socials">
                <?php foreach ($widget->socials as $social) : ?>
                    <a href="<?= Html::encode($social['url']); ?>" target="_blank" rel="noopener" aria-label="<?= Html::encode($social['label']); ?>"><i class="<?= Html::encode($social['icon']); ?>" aria-hidden="true"></i></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if ($cta && $widget->drawerCta) : ?>
            <?= Html::a(Html::encode($cta['label']), $cta['url'], array_merge($ctaOptions, ['class' => 'sx-site-header__cta sx-full-menu__start'])); ?>
        <?php endif; ?>
        <?php if ($widget->cabinetUrl) : ?>
            <a class="sx-full-menu__login" href="<?= Html::encode($widget->cabinetUrl); ?>"><i class="far fa-user" aria-hidden="true"></i><?= Html::encode($widget->cabinetLabel); ?></a>
        <?php endif; ?>
    </div>
</aside>
