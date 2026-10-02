/*
 * Scroll reveal for [data-sx-reveal]. Content stays visible without JS, without
 * IntersectionObserver and with prefers-reduced-motion.
 * Blocks inserted later (AJAX "show more") are revealed by calling
 * window.sxReveal(container) or by dispatching "sx:reveal" on document.
 */
(function () {
    'use strict';
    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduced || !('IntersectionObserver' in window)) {
        window.sxReveal = function () {};
        return;
    }
    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) { entry.target.classList.add('is-revealed'); observer.unobserve(entry.target); }
        });
    }, {threshold: 0.12, rootMargin: '0px 0px -8% 0px'});

    function observe(container) {
        var items = (container || document).querySelectorAll('[data-sx-reveal]:not(.is-revealed)');
        if (!items.length) return;
        document.documentElement.classList.add('sx-reveal-ready');
        items.forEach(function (item) { observer.observe(item); });
    }

    window.sxReveal = observe;
    document.addEventListener('sx:reveal', function (event) { observe(event.target === document ? null : event.target); });
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', function () { observe(); });
    else observe();
}());
