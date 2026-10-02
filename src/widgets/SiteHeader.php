<?php
/**
 * @link https://skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\brand\widgets;

use skeeks\brand\assets\ShellAsset;
use skeeks\brand\Products;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Shared SkeekS site header: menu button, product logo with the product switcher,
 * main navigation, search, account and call to action, plus the slide-out drawer.
 * Sites pass data only; markup, styles and behavior live here (ShellAsset).
 *
 * Font Awesome 5 is provided by the site.
 */
class SiteHeader extends Widget
{
    /** @var string Product id of this site, see Products. */
    public $product = Products::SKEEKS;

    /** @var string Ready HTML of the logo link in the header (class sx-site-header__logo). */
    public $brand = '';

    /** @var string|null Ready HTML of the logo link in the drawer (class sx-full-menu__logo); defaults to $brand. */
    public $drawerBrand;

    /** @var array Main navigation: [[url, label], ...]. */
    public $menu = [];

    /** @var array Drawer groups: ['Title' => [[url, label, iconClass], ...], ...]. */
    public $drawerGroups = [];

    /** @var array|null Search form: ['action' => route, 'param' => name, 'value' => current]; null hides search. */
    public $search;

    /** @var string|null Personal account URL; null hides the account icon and drawer link. */
    public $cabinetUrl;

    /** @var string */
    public $cabinetLabel = 'Личный кабинет';

    /** @var array|null Call to action: ['label' => ..., 'url' => ..., 'options' => [html attributes]]. */
    public $cta;

    /** @var bool Repeat the call to action inside the drawer. */
    public $drawerCta = false;

    /** @var array Drawer contacts: [['url' => 'tel:…', 'icon' => 'fas fa-phone', 'label' => …], ...]. */
    public $contacts = [];

    /** @var array Drawer social links: [['url' => …, 'icon' => …, 'label' => …], ...]. */
    public $socials = [];

    /** @var array Extra attributes of the <header> element. */
    public $headerOptions = [];

    public function init()
    {
        parent::init();
        if (!$this->brand) {
            throw new InvalidConfigException('SiteHeader::$brand is required.');
        }
        $this->drawerBrand = $this->drawerBrand ?? $this->brand;
        $this->headerOptions = array_merge(['id' => 'js-header'], $this->headerOptions);
        $class = trim('sx-site-header '.($this->headerOptions['class'] ?? ''));
        $this->headerOptions['class'] = $class;
    }

    public function run()
    {
        ShellAsset::register($this->view);
        return $this->render('site-header', ['widget' => $this]);
    }

    /** A menu item is active on its own path and on nested paths. */
    public function isActive(string $url): bool
    {
        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');
        if ($path === '' || parse_url($url, PHP_URL_HOST)) {
            return false;
        }
        $current = trim(\Yii::$app->request->pathInfo, '/');
        return $current === $path || strpos($current, $path.'/') === 0;
    }
}
