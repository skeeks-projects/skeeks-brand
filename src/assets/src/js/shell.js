/*
 * SkeekS site shell: drawer, search, header on scroll. Plain JS, no dependencies.
 * Works with the product switcher (product-switcher.js): opening one closes the other.
 * window.sxShell.closeAll() closes the drawer and search, e.g. before a site modal.
 */
(function () {
    'use strict';
    var header = document.querySelector('.sx-site-header');
    var drawer = document.getElementById('sx-full-menu');
    var toggle = document.querySelector('.sx-menu-toggle');
    if (!header || !drawer || !toggle) return;
    var search = document.getElementById('sx-site-search');
    var searchToggle = header.querySelector('.sx-search-btn');
    var previousFocus = null;

    document.addEventListener('pointerdown', function () { document.documentElement.setAttribute('data-sx-input', 'pointer'); }, true);
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Tab' || event.key.indexOf('Arrow') === 0) document.documentElement.setAttribute('data-sx-input', 'keyboard');
    }, true);

    function closeProducts() { if (window.sxProductSwitcher) window.sxProductSwitcher.close(); }
    function productsOpen() { return !!(window.sxProductSwitcher && window.sxProductSwitcher.isOpen()); }
    function isMenuOpen() { return document.body.classList.contains('sx-full-menu-open'); }
    function isSearchOpen() { return !!search && search.classList.contains('is-open'); }

    function setSearch(open, restoreFocus) {
        if (!search || !searchToggle) return;
        if (open) closeProducts();
        search.classList.toggle('is-open', open);
        search.setAttribute('aria-hidden', String(!open));
        search.inert = !open;
        searchToggle.setAttribute('aria-expanded', String(open));
        if (open) { search.getBoundingClientRect(); search.querySelector('input').focus(); }
        else if (restoreFocus) searchToggle.focus();
    }

    // Everything except the drawer becomes inert while it is open. A layout may render the
    // drawer inside <main>, so containers that hold the drawer itself are never made inert.
    function background() {
        return [header, document.querySelector('main'), document.querySelector('footer')]
            .filter(function (element) { return element && !element.contains(drawer); });
    }

    function setMenu(open, restoreFocus) {
        if (open === isMenuOpen()) return;
        if (open) { closeProducts(); setSearch(false, false); previousFocus = document.activeElement; header.classList.remove('is-hidden'); }
        document.body.classList.toggle('sx-full-menu-open', open);
        drawer.setAttribute('aria-hidden', String(!open));
        drawer.inert = !open;
        toggle.setAttribute('aria-expanded', String(open));
        background().forEach(function (element) { element.inert = open; });
        if (open) { drawer.getBoundingClientRect(); drawer.querySelector('[data-sx-menu-close]').focus(); }
        else if (restoreFocus && previousFocus) previousFocus.focus();
    }

    toggle.addEventListener('click', function () { setMenu(true); });
    document.querySelectorAll('[data-sx-menu-close]').forEach(function (button) {
        button.addEventListener('click', function () { setMenu(false, true); });
    });
    drawer.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () { setMenu(false, false); });
    });
    if (searchToggle) searchToggle.addEventListener('click', function (event) {
        event.preventDefault();
        setSearch(!isSearchOpen(), false);
    });
    document.addEventListener('sx:product-switcher-open', function () { setSearch(false, false); header.classList.remove('is-hidden'); });
    document.addEventListener('click', function (event) { if (isSearchOpen() && !header.contains(event.target)) setSearch(false, false); });

    document.addEventListener('keydown', function (event) {
        var open = isMenuOpen();
        if (event.key === 'Escape') {
            if (open) setMenu(false, true);
            else if (isSearchOpen()) setSearch(false, true);
            return;
        }
        if (open && event.key === 'Tab') {
            var items = drawer.querySelectorAll('a[href],button:not([disabled])');
            var first = items[0], last = items[items.length - 1];
            if (!drawer.contains(document.activeElement)) { event.preventDefault(); first.focus(); }
            else if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
            else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
        }
    });

    var lastY = window.scrollY;
    window.addEventListener('scroll', function () {
        var nextY = window.scrollY;
        header.classList.toggle('is-scrolled', nextY > 30);
        header.classList.toggle('is-hidden', nextY > 180 && nextY > lastY
            && !header.contains(document.activeElement) && !isSearchOpen() && !productsOpen() && !isMenuOpen());
        lastY = nextY;
    }, {passive: true});
    header.addEventListener('focusin', function () { header.classList.remove('is-hidden'); });

    window.sxShell = { closeAll: function () { setMenu(false, false); setSearch(false, false); closeProducts(); } };
}());
