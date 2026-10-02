<?php
/**
 * @link https://skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\brand\widgets;

use skeeks\brand\assets\FooterAsset;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Shared SkeekS site footer: call-to-action block, navigation with logo and social
 * links, legal line. FooterAsset also styles the cookie notice of skeeks/cms.
 *
 * The call to action ends either with contacts plus a button, or with one large
 * animated start button ($start).
 */
class SiteFooter extends Widget
{
    /** @var string Small caption above the title. */
    public $eyebrow = '';

    /** @var string Title; trusted HTML (line breaks with <br>). */
    public $title = '';

    /** @var string Plain text under the title. */
    public $text = '';

    /** @var array Contacts: [['url' => 'tel:…', 'icon' => 'fas fa-phone', 'label' => …], ...]. */
    public $contacts = [];

    /** @var array|null Button: ['label' => …, 'url' => …, 'options' => [html attributes]]. */
    public $button;

    /** @var array|null Large animated start button: ['label' => …, 'url' => …]; replaces contacts and button. */
    public $start;

    /** @var string Ready HTML of the footer logo link (class sx-new-footer__logo). */
    public $logo = '';

    /** @var array Footer navigation: [[url, label], ...]. */
    public $menu = [];

    /** @var array Social links: [['url' => …, 'icon' => …, 'label' => …], ...]. */
    public $socials = [];

    /** @var string|null Copyright line; null gives "© 2010–<year> SkeekS. Все права защищены.". */
    public $copyright;

    /** @var array Legal links: [[url, label], ...]. */
    public $legalLinks = [
        ['/~legal-privacy-policy', 'Политика конфиденциальности'],
        ['/~legal-personal-data', 'Обработка персональных данных'],
    ];

    /** @var array Extra attributes of the <footer> element. */
    public $options = [];

    public function init()
    {
        parent::init();
        if (!$this->logo) {
            throw new InvalidConfigException('SiteFooter::$logo is required.');
        }
        $this->copyright = $this->copyright ?? '© 2010–'.date('Y').' SkeekS. Все права защищены.';
        $this->options = array_merge(['id' => 'contacts-section'], $this->options);
        $this->options['class'] = trim('sx-new-footer '.($this->options['class'] ?? ''));
    }

    public function run()
    {
        FooterAsset::register($this->view);
        return $this->render('site-footer', ['widget' => $this]);
    }
}
