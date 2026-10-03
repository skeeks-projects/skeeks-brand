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
| `skeeks\brand\widgets\Scene` | CSS-3D сцена первого экрана | Сам регистрирует `SceneAsset` |
| `BlocksAsset`, `TilesAsset`, `ToneAsset` | Блоки страниц и карточки в стиле платформы | Только на страницах с этими блоками, см. «Блоки страниц» |
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
- Якоря: ссылки `#id` на странице прокручиваются плавно и останавливаются под
  фиксированной шапкой (`scroll-behavior` и `scroll-padding-top` на `html` в `shell.css`,
  при `prefers-reduced-motion` — без анимации). Блокам не нужен свой `scroll-margin-top`:
  он сложится с общим отступом. Своя jQuery-прокрутка (`sx-scroll-to` из Unify) с этим
  правилом дёргается — для новых ссылок достаточно обычного `href="#id"`.
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

## Блоки страниц

Единый вид страниц SkeekS в стиле платформы. Каждая часть — свой маленький бандл,
страница подключает только нужное (общего `brand.css` нет).

| Бандл | Что внутри | Кто подключает |
| --- | --- | --- |
| `ToneAsset` | Тона `data-sx-tone="lime|pink|gold|cyan"` → `--sx-tone`, `--sx-tone-rgb` | Зависимость остальных бандлов |
| `BlocksAsset` | Страница, первый экран, секции, текстовые элементы, сплит, шаги, вопросы | Шаблон страницы с этими блоками (CSS) |
| `TilesAsset` | Карточки-панели `sx-tile` в сетке `sx-tiles` | Шаблон, который выводит карточки (CSS) |
| `SceneAsset` | CSS-3D сцена и её запуск только на экране | Сам виджет `Scene` |

Разметка блоков — договорённость: эти классы стоят в текстах разделов CMS
(`description_full`, правятся через REST API). Классы не переименовывать без
переноса текстов. Блоки рассчитаны на тёмный фон.

| Класс | Назначение |
| --- | --- |
| `sx-landing` | Обёртка страницы: тёмный фон, шрифт, без горизонтальной прокрутки |
| `sx-wrap` | Контентная колонка с полями `--px120` |
| `sx-hero`, `sx-hero__grid`, `sx-hero--short` | Первый экран: текст + сцена; короткий — для заглушек. На сайтах без мобильного отступа под шапку (`--sx-header-spacer-mobile:0`) сам отступает под шапку |
| `sx-kicker`, `sx-lead` | Надзаголовок с полоской, ведущий абзац |
| `sx-cta-row`, `sx-btn`, `sx-text-link` | Ряд действий, градиентная кнопка, ссылка со стрелкой |
| `sx-chips` | Метки списком `<ul>` |
| `sx-symbol` | Квадрат с иконкой в цвете тона |
| `sx-band` + `--lit`, `--warm`, `--plain`, `--grid` | Секция и её фон; `--grid` — засветки и сетка, как у вопросов на платформе |
| `sx-band-head`, `sx-band-head--row` | Заголовок секции с абзацем; `--row` — со ссылкой справа |
| `sx-split`, `sx-points`, `sx-stack` | Текст + визуал; список со стрелками; лесенка панелей (`<div data-sx-tone><strong>…</strong><span>…</span></div>`) |
| `sx-step-list` | Шаги 01, 02…: `<ol class="sx-step-list"><li><h3>…</h3><p>…</p></li></ol>` |
| `sx-plans`, `sx-plan` (`--accent`, `__name`, `__price`, `__note`, `__list`) | Тарифы: карточки с ценой, примечанием, списком и кнопкой; ширина колонки — `--sx-plan-min` |
| `sx-faq`, `sx-faq__aside`, `sx-ask`, `sx-faq-list` | Вопросы и ответы: слева заголовок и карточка «Не нашли ответ?», справа `<details>` |
| `sx-tiles`, `sx-tile` (`__icon`, `__media`, `__body`, `__text`, `__points`, `__arrow`) | Карточки со свечением; ширина колонки — `--sx-tile-min`, фиксированные колонки — `sx-tiles--cols` и `--sx-tile-cols`; `sx-tile--lg` — крупная иконка или логотип, `sx-tile--stack` — иконка над текстом (в теле можно `<h3>` и `<p>`) |

Пример вопросов и ответов:

```html
<section class="sx-band sx-band--grid"><div class="sx-wrap sx-faq">
  <div class="sx-faq__aside" data-sx-reveal><h2>Вопросы и ответы</h2><p>Коротко перед стартом.</p>
    <div class="sx-ask"><span class="sx-ask__icon" aria-hidden="true"><i class="far fa-comments"></i></span>
      <div><strong>Не нашли ответ?</strong><span>Расскажите о задаче.</span>
        <a class="sx-text-link" href="/contacts">Задать вопрос <span aria-hidden="true">↗</span></a></div></div></div>
  <div class="sx-faq-list" data-sx-reveal><details><summary>Вопрос?</summary><p>Ответ.</p></details></div>
</div></section>
```

Сцена:

```php
echo \skeeks\brand\widgets\Scene::widget([
    'core'    => ['icon' => 'fas fa-layer-group', 'title' => 'SkeekS', 'sub' => 'веб-студия'],
    'nodes'   => [['url' => '#dev', 'icon' => 'fas fa-code', 'label' => 'Сайты', 'tone' => 'lime']], // до 6
    'tone'    => 'lime',
    'label'   => 'Направления услуг',
    'caption' => 'Подпись под сценой',
]);
```

До трёх узлов — треугольник, от четырёх до шести — кольцо. Узел без `url` — не ссылка.
Анимация идёт только на экране и на видимой вкладке, при `prefers-reduced-motion` её нет.
Font Awesome и шрифт подключает сайт.
