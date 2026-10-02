/*
 * SkeekS product switcher. Plain JS, no dependencies.
 * Site scripts coordinate through window.sxProductSwitcher:
 *   close(restoreFocus) / isOpen() — e.g. close it before opening a drawer or search;
 *   document event "sx:product-switcher-open" — close own overlays when the list opens.
 */
(function () {
    'use strict';
    var root = document.querySelector('[data-sx-product-switcher]');
    if (!root) return;
    var button = root.querySelector('.sx-product-switcher__toggle');
    var list = root.querySelector('.sx-product-switcher__panel');

    document.addEventListener('pointerdown', function () { document.documentElement.setAttribute('data-sx-input', 'pointer'); }, true);
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Tab' || event.key.indexOf('Arrow') === 0) document.documentElement.setAttribute('data-sx-input', 'keyboard');
    }, true);

    function isOpen() { return !list.hidden; }
    function links() { return Array.prototype.slice.call(list.querySelectorAll('a[href]')); }
    function set(open, restoreFocus) {
        if (open === isOpen()) { if (!open && restoreFocus) button.focus(); return; }
        list.hidden = !open;
        button.setAttribute('aria-expanded', String(open));
        if (open) document.dispatchEvent(new CustomEvent('sx:product-switcher-open'));
        if (restoreFocus) button.focus();
    }

    button.addEventListener('click', function () { set(!isOpen(), false); });
    button.addEventListener('keydown', function (event) {
        if (event.key !== 'ArrowDown') return;
        event.preventDefault();
        set(true, false);
        links()[0].focus();
    });
    list.addEventListener('keydown', function (event) {
        var keys = ['ArrowDown', 'ArrowUp', 'Home', 'End'];
        if (keys.indexOf(event.key) < 0) return;
        event.preventDefault();
        var items = links(), index = items.indexOf(document.activeElement);
        if (event.key === 'Home') index = 0;
        else if (event.key === 'End') index = items.length - 1;
        else index = (index + (event.key === 'ArrowDown' ? 1 : -1) + items.length) % items.length;
        items[index].focus();
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && isOpen()) { event.stopImmediatePropagation(); set(false, true); }
    }, true);
    document.addEventListener('click', function (event) { if (isOpen() && !root.contains(event.target)) set(false, false); });
    document.addEventListener('focusin', function (event) { if (isOpen() && !root.contains(event.target)) set(false, false); });

    window.sxProductSwitcher = { close: function (restoreFocus) { set(false, !!restoreFocus); }, isOpen: isOpen };
}());
