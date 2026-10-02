# skeeks/skeeks-brand

Общий фирменный слой сайтов SkeekS: skeeks.com, skeeks-platform.ru, skeeks-market.ru и
будущих продуктов. Это не тема и не наследник Unify: пакет подключается к любой теме
сайта и не зависит от `cms-theme-unify-v2`, jQuery и Bootstrap.

## Состав

| Часть | Что это | Как подключать |
| --- | --- | --- |
| `skeeks\brand\Products` | Реестр продуктов: название, подпись, URL, иконка, акцент | Читается виджетами; правки — `params['skeeksBrand']['products']` |
| `skeeks\brand\widgets\SiteHeader` | Оболочка: шапка, поиск, выдвижное меню, знак продукта | Вместо своей шапки сайта, регистрирует `ShellAsset` |
| `skeeks\brand\widgets\SiteFooter` | Подвал: призыв, навигация, соцсети, юридическая строка; вид плашки cookie | Вместо своего подвала, регистрирует `FooterAsset` (только CSS) |
| `skeeks\brand\widgets\ProductSwitcher` | Логотип + стрелка «Выбрать продукт SkeekS» | Внутри `SiteHeader` или отдельно, сам регистрирует свой ассет |
| `skeeks\brand\assets\RevealAsset` | Плавное появление `[data-sx-reveal]` | Только на страницах, где есть такие блоки |
| `brand/favicons` | Favicon продуктов и генератор | См. `brand/favicons/README.md` |
| `brand/logos` | Логотипы продуктов и генераторы | См. `brand/logos/README.md` |

## Шапка сайта (оболочка)

```php
use skeeks\brand\widgets\SiteHeader;

echo SiteHeader::widget([
    'product'      => 'platform',
    'brand'        => Html::a($logo, Url::home(), ['class' => 'sx-site-header__logo']),
    'drawerBrand'  => Html::a($logo, Url::home(), ['class' => 'sx-full-menu__logo']),
    'menu'         => [['/features', 'Возможности'], ['/blog', 'Блог']],
    'drawerGroups' => ['Раздел' => [['/features', 'Возможности', 'fas fa-th-large']]],
    'search'       => ['action' => ['/cmsSearch/result/index'], 'param' => 'q', 'value' => ''], // null — без поиска
    'cabinetUrl'   => 'https://skeeks.com/~login',
    'cabinetLabel' => 'Личный кабинет SkeekS',
    'cta'          => ['label' => 'Начать использовать', 'url' => '/start', 'options' => []],
    'drawerCta'    => true,  // повторить CTA в меню
    'contacts'     => [['url' => 'tel:+7…', 'icon' => 'fas fa-phone', 'label' => '+7 …']],
    'socials'      => [['url' => 'https://…', 'icon' => 'fab fa-vk', 'label' => 'ВКонтакте']],
]);
```

- Сайт передаёт только данные; разметка, стили (`shell.css`, ~11 КБ) и поведение
  (`shell.js`, ~5 КБ) — в пакете. Знак продукта «лампочка + skeeks + название» —
  классы `sx-product-brand*` (см. шапку skeeks-platform.ru).
- Поведение: меню — диалог с ловушкой фокуса, `inert` для фона (кроме контейнера, в
  котором лежит само меню), Escape и возврат фокуса; поиск закрывается кликом снаружи;
  шапка прячется при прокрутке вниз (на ≤991px — никогда). Переключатель, поиск и меню
  взаимно закрывают друг друга. `window.sxShell.closeAll()` — закрыть всё (например,
  перед своим модальным окном).
- Токены (`--sx-pink`, `--sx-gold`, `--sx-line`, `--sx-muted`, `--px120`, высоты шапки)
  объявлены через `:where(:root)`: значения сайта в его `:root` всегда главнее.
  Отступ под фиксированной шапкой на мобильных — `--sx-header-spacer-mobile`
  (по умолчанию высота шапки; skeeks.com задаёт `0`).
- Шрифт и Font Awesome подключает сайт.

## Подвал сайта

```php
use skeeks\brand\widgets\SiteFooter;

echo SiteFooter::widget([
    'eyebrow'  => 'Начнём с вашего проекта',
    'title'    => 'Есть задача?<br>Давайте обсудим',   // доверенный HTML
    'text'     => 'Расскажите о проекте…',
    // правая часть призыва: контакты + кнопка…
    'contacts' => [['url' => 'tel:+7…', 'icon' => 'fas fa-phone', 'label' => '+7 …']],
    'button'   => ['label' => 'Оставить заявку', 'url' => '#sx-callback', 'options' => ['data-toggle' => 'modal']],
    // …или одна большая анимированная кнопка вместо них:
    // 'start' => ['label' => 'Начать использовать', 'url' => '/start'],
    'logo'     => Html::a(Html::img($logo), Url::home(), ['class' => 'sx-new-footer__logo']),
    'menu'     => [['/services', 'Услуги'], ['/blog', 'Блог']],
    'socials'  => [['url' => 'https://t.me/…', 'icon' => 'fab fa-telegram-plane', 'label' => 'Telegram']],
    // 'copyright', 'legalLinks' — по умолчанию © SkeekS и две юридические страницы CMS
]);
```

- `footer.css` (~8 КБ) содержит и вид плашки cookie: её разметку выводит
  `LegalComponent` из `skeeks/cms`, пакет только перекрашивает (поэтому `!important`).
  Если подвал на странице не выводится (например, режим мобильного приложения), а
  плашка нужна в фирменном виде — зарегистрировать `FooterAsset` вручную.
- Цвет юридической строки — переменная `--sx-footer-legal-color` (по умолчанию `--sx-faint`).

## Переключатель продуктов

```php
use skeeks\brand\widgets\ProductSwitcher;

echo ProductSwitcher::widget([
    'current' => 'skeeks',  // id продукта этого сайта
    'brand'   => Html::a(Html::img($logo, ['alt' => 'SkeekS']), Url::home(), ['class' => 'sx-site-header__logo']),
]);
```

- Иконки — классы Font Awesome 5 из реестра; Font Awesome подключает сайт.
- Оболочка шапки должна быть `position:relative`: на ширине до 575px панель
  растягивается на ширину шапки.
- Цвета и отступ панели — переменные `--sx-switcher-accent`, `--sx-switcher-muted`,
  `--sx-switcher-bg`, `--sx-switcher-offset` на `.sx-product-switcher`.
- Связь со своими меню и поиском сайта:
  - перед открытием меню или поиска: `window.sxProductSwitcher && sxProductSwitcher.close()`;
  - при открытии списка приходит событие `sx:product-switcher-open` на `document` —
    закрыть свои панели;
  - `sxProductSwitcher.isOpen()` — например, не прятать шапку при прокрутке.
- Escape закрывает список первым и возвращает фокус на стрелку; остальные
  обработчики Escape в этот момент не срабатывают.
- После клика мышью рамки фокуса нет (`html[data-sx-input="pointer"]`), при
  работе с клавиатуры она видна.

## Плавное появление блоков

```php
\skeeks\brand\assets\RevealAsset::register($this);
```

```html
<section data-sx-reveal>…</section>
<div data-sx-reveal style="--sx-reveal-delay:.1s">…</div>
```

Без JS, без IntersectionObserver и при `prefers-reduced-motion` всё видно сразу.
Блоки, вставленные позже (кнопка «Показать ещё»), — `window.sxReveal(container)`
или событие `sx:reveal` на контейнере. Заголовок первого экрана не помечать.
