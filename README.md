# skeeks/skeeks-brand

Общий фирменный слой сайтов SkeekS: skeeks.com, skeeks-platform.ru, skeeks-market.ru и
будущих продуктов. Это не тема и не наследник Unify: пакет подключается к любой теме
сайта и не зависит от `cms-theme-unify-v2`, jQuery и Bootstrap.

## Состав

| Часть | Что это | Как подключать |
| --- | --- | --- |
| `skeeks\brand\Products` | Реестр продуктов: название, подпись, URL, иконка, акцент | Читается виджетами; правки — `params['skeeksBrand']['products']` |
| `skeeks\brand\widgets\ProductSwitcher` | Логотип + стрелка «Выбрать продукт SkeekS» | В шапке сайта, сам регистрирует свой ассет |
| `skeeks\brand\assets\RevealAsset` | Плавное появление `[data-sx-reveal]` | Только на страницах, где есть такие блоки |
| `brand/favicons` | Favicon продуктов и генератор | См. `brand/favicons/README.md` |
| `brand/logos` | Логотипы продуктов и генераторы | См. `brand/logos/README.md` |

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
