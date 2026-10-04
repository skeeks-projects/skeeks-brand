/*
 * Scripted page demonstrations.
 *
 * [data-sx-live] sections get data-sx-running="true" only while they are on screen,
 * the tab is visible and prefers-reduced-motion is off; CSS loops key off it.
 * A site registers scenarios by name; every [data-sx-demo="name"] inside a
 * [data-sx-live] section is started with a helper object. Steps advance only while
 * the section is running, so a scenario pauses off screen and resumes where it was.
 * Without JS and with reduced motion the static markup stays as it is.
 *
 *   window.sxDemo.register('name', async function (h) { for (;;) { ... await h.wait(800); } });
 */
(function () {
    'use strict';
    var preference = window.matchMedia('(prefers-reduced-motion: reduce)');
    var scenarios = {};

    function watch(section) {
        if (section.dataset.sxLiveReady) return;
        section.dataset.sxLiveReady = '1';
        var visible = !('IntersectionObserver' in window);
        function update() {
            section.dataset.sxRunning = String(visible && !document.hidden && !preference.matches);
        }
        if (!visible) {
            new IntersectionObserver(function (entries) { visible = entries[0].isIntersecting; update(); }, {threshold: 0}).observe(section);
        }
        document.addEventListener('visibilitychange', update);
        if (preference.addEventListener) preference.addEventListener('change', update);
        update();
    }

    function helpers(root) {
        var live = root.closest('[data-sx-live]');
        var frame = root.querySelector('[data-sx-demo-frame]') || root;
        var cursor = root.querySelector('.sx-demo-cursor');
        var h = {root: root, frame: frame, cursor: cursor};
        function running() { return live.dataset.sxRunning === 'true' && !preference.matches; }
        h.running = running;
        h.q = function (name) { return root.querySelector('[data-demo="' + name + '"]'); };
        h.all = function (name) { return Array.prototype.slice.call(root.querySelectorAll('[data-demo="' + name + '"]')); };
        // Waits only count while the section is running: off screen the scenario holds its state.
        h.wait = function (ms) {
            return new Promise(function (resolve) {
                var left = ms, last = Date.now();
                (function tick() {
                    var now = Date.now();
                    if (running()) left -= Math.min(now - last, 250);
                    last = now;
                    if (left <= 0) resolve(); else setTimeout(tick, 50);
                }());
            });
        };
        function offset(el) {
            var x = 0, y = 0, node = el;
            while (node && node !== frame) { x += node.offsetLeft; y += node.offsetTop; node = node.offsetParent; }
            return {x: x, y: y};
        }
        h.visible = function (el) {
            if (!el || !el.offsetWidth) return false;
            var o = offset(el);
            return o.y + el.offsetHeight / 2 < frame.clientHeight && o.x < frame.clientWidth;
        };
        h.home = function (fx, fy) {
            if (cursor) cursor.style.transform = 'translate(' + (frame.clientWidth * (fx || 0.62)) + 'px,' + (frame.clientHeight * (fy || 0.18)) + 'px)';
        };
        h.move = async function (el, dx, dy) {
            if (!cursor || !h.visible(el)) return false;
            var o = offset(el);
            cursor.style.transform = 'translate(' + (o.x + el.offsetWidth * (dx || 0.5)) + 'px,' + (o.y + el.offsetHeight * (dy || 0.55)) + 'px)';
            await h.wait(800);
            return true;
        };
        h.click = async function (el, dx, dy) {
            if (!(await h.move(el, dx, dy))) return false;
            cursor.classList.add('is-click'); el.classList.add('is-pressed');
            await h.wait(250);
            cursor.classList.remove('is-click'); el.classList.remove('is-pressed');
            return true;
        };
        h.type = async function (el, text, speed) {
            for (var i = 1; i <= text.length; i++) { el.textContent = text.slice(0, i); await h.wait(speed || 45); }
        };
        h.format = function (n) { return String(Math.round(n)).replace(/\B(?=(\d{3})+(?!\d))/g, ' '); };
        // Counts a number up (or down) in the element's text.
        h.count = async function (el, from, to, ms) {
            var steps = Math.max(1, Math.round((ms || 900) / 60));
            for (var i = 1; i <= steps; i++) { el.textContent = h.format(from + (to - from) * i / steps); await h.wait(60); }
        };
        return h;
    }

    function start(root) {
        var scenario = scenarios[root.dataset.sxDemo];
        var live = root.closest('[data-sx-live]');
        if (!scenario || root.dataset.sxDemoStarted || !live || preference.matches) return;
        root.dataset.sxDemoStarted = '1';
        watch(live);
        root.classList.add('is-demo');
        scenario(helpers(root));
    }

    function scan() {
        document.querySelectorAll('[data-sx-live]').forEach(watch);
        document.querySelectorAll('[data-sx-demo]').forEach(start);
    }

    window.sxDemo = {
        register: function (name, scenario) {
            scenarios[name] = scenario;
            document.querySelectorAll('[data-sx-demo="' + name + '"]').forEach(start);
        },
        scan: scan
    };

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', scan);
    else scan();
}());
