/*
 * Scenes of the Scene widget animate only while on screen, on a visible tab and
 * without prefers-reduced-motion: the CSS keys off data-sx-running="true".
 */
(function () {
    'use strict';
    var preference = window.matchMedia('(prefers-reduced-motion: reduce)');
    document.querySelectorAll('[data-sx-scene]').forEach(function (scene) {
        var visible = !('IntersectionObserver' in window);
        function update() {
            scene.dataset.sxRunning = String(visible && !document.hidden && !preference.matches);
        }
        if (!visible) {
            new IntersectionObserver(function (entries) { visible = entries[0].isIntersecting; update(); }, {threshold: 0.05}).observe(scene);
        }
        document.addEventListener('visibilitychange', update);
        if (preference.addEventListener) preference.addEventListener('change', update);
        update();
    });
}());
