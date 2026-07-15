/* ============================================================
   HDTT STORE — Dark Premium interactions (SPA-like feel)
   Vanilla JS, no build step. Loaded at end of _footer.php.
   ============================================================ */
(function () {
    'use strict';

    /* -------- 0. Inject progress bar element -------- */
    var bar = document.getElementById('hdtt-progress');
    if (!bar) {
        bar = document.createElement('div');
        bar.id = 'hdtt-progress';
        document.body.appendChild(bar);
    }
    var progT;
    function startProgress() {
        bar.classList.add('active');
        var w = 8;
        bar.style.width = w + '%';
        clearInterval(progT);
        progT = setInterval(function () {
            w += (90 - w) * 0.12;
            bar.style.width = w.toFixed(1) + '%';
        }, 180);
    }
    function doneProgress() {
        clearInterval(progT);
        bar.style.width = '100%';
        setTimeout(function () {
            bar.classList.remove('active');
            setTimeout(function () { bar.style.width = '0'; }, 250);
        }, 200);
    }

    /* -------- 1. Sticky header shadow on scroll -------- */
    var appbar = document.querySelector('.hdtt-appbar');
    function onScroll() {
        if (!appbar) return;
        appbar.classList.toggle('scrolled', window.scrollY > 8);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    /* -------- 2. Wrap main content so we can transition it -------- */
    // Mark the first big content block as a page for the enter animation.
    document.addEventListener('DOMContentLoaded', function () {
        // Add enter animation class to body children between header and footer.
        var main = document.querySelector('.hdtt-pageview');
        if (!main) {
            // Fallback: animate the first container after the navbar.
            var nav = document.querySelector('.nav-bar');
            if (nav && nav.nextElementSibling) {
                nav.nextElementSibling.classList.add('hdtt-pageview');
            }
        }
        revealInit();
    });

    /* -------- 3. Smooth page-leave transition on internal links -------- */
    function isInternal(a) {
        if (!a || !a.getAttribute) return false;
        var href = a.getAttribute('href');
        if (!href) return false;
        if (a.target && a.target === '_blank') return false;
        if (a.hasAttribute('download')) return false;
        if (href.indexOf('#') === 0) return false;
        if (href.indexOf('javascript:') === 0) return false;
        if (href.indexOf('tel:') === 0 || href.indexOf('mailto:') === 0) return false;
        if (a.dataset.noTransition !== undefined) return false;
        // same-origin only
        try {
            var u = new URL(a.href, location.href);
            if (u.origin !== location.origin) return false;
        } catch (e) { return false; }
        return true;
    }
    document.addEventListener('click', function (e) {
        var a = e.target.closest ? e.target.closest('a') : null;
        if (!a || !isInternal(a)) return;
        // Let modified clicks (new tab) behave normally
        if (e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;
        // Skip dropdown togglers etc.
        if (a.getAttribute('data-bs-toggle')) return;
        e.preventDefault();
        startProgress();
        document.body.classList.add('hdtt-leaving');
        var dest = a.href;
        setTimeout(function () { window.location.href = dest; }, 240);
    });
    // Also handle form submits with a progress cue
    document.addEventListener('submit', function () { startProgress(); });
    window.addEventListener('pageshow', function () {
        document.body.classList.remove('hdtt-leaving');
        doneProgress();
    });
    window.addEventListener('beforeunload', startProgress);

    /* -------- 4. Scroll reveal -------- */
    function revealInit() {
        var els = document.querySelectorAll('[data-reveal]');
        if (!('IntersectionObserver' in window) || !els.length) {
            els.forEach(function (el) { el.classList.add('in'); });
            return;
        }
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) {
                    en.target.classList.add('in');
                    io.unobserve(en.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        els.forEach(function (el) { io.observe(el); });
    }

    /* -------- 5. Toast helper (for AJAX cart) -------- */
    function toast(msg, type) {
        var wrap = document.getElementById('hdtt-toasts');
        if (!wrap) {
            wrap = document.createElement('div');
            wrap.id = 'hdtt-toasts';
            wrap.style.cssText = 'position:fixed;right:20px;bottom:22px;z-index:100001;display:flex;flex-direction:column;gap:10px;pointer-events:none';
            document.body.appendChild(wrap);
        }
        var t = document.createElement('div');
        var color = type === 'error' ? '#FB7185' : (type === 'info' ? '#38BDF8' : '#34D399');
        t.style.cssText = 'pointer-events:auto;min-width:220px;max-width:340px;padding:13px 16px;border-radius:14px;' +
            'background:rgba(16,16,24,.92);backdrop-filter:blur(14px);border:1px solid rgba(255,255,255,.14);' +
            'color:#ECECF5;font:600 13.5px Inter,sans-serif;box-shadow:0 16px 40px rgba(0,0,0,.5);' +
            'display:flex;align-items:center;gap:10px;transform:translateX(120%);transition:transform .4s cubic-bezier(.22,1,.36,1)';
        t.innerHTML = '<span style="width:9px;height:9px;border-radius:50%;background:' + color + ';box-shadow:0 0 10px ' + color + ';flex-shrink:0"></span>' +
            '<span>' + msg + '</span>';
        wrap.appendChild(t);
        requestAnimationFrame(function () { t.style.transform = 'translateX(0)'; });
        setTimeout(function () {
            t.style.transform = 'translateX(120%)';
            setTimeout(function () { t.remove(); }, 400);
        }, 2600);
    }
    window.hdttToast = toast;

    /* -------- 6. AJAX add-to-cart (progressive enhancement) --------
       Any form with data-ajax-cart posts in background and shows a toast
       instead of a full navigation. Falls back to normal submit if fetch fails. */
    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form.matches || !form.matches('form[data-ajax-cart]')) return;
        e.preventDefault();
        startProgress();
        var fd = new FormData(form);
        fetch(form.action, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.text(); })
            .then(function () {
                doneProgress();
                toast('Đã thêm vào giỏ hàng ✓', 'success');
                bumpCart();
            })
            .catch(function () { form.submit(); });
    });
    function bumpCart() {
        var badge = document.querySelector('[data-cart-count]');
        if (badge) {
            var n = parseInt(badge.textContent || '0', 10) + 1;
            badge.textContent = n;
            badge.style.transform = 'scale(1.4)';
            setTimeout(function () { badge.style.transform = 'scale(1)'; }, 220);
        }
    }

    /* -------- 7. Tilt / spotlight on product cards -------- */
    document.addEventListener('mousemove', function (e) {
        var card = e.target.closest ? e.target.closest('.hdtt-pcard') : null;
        if (!card) return;
        var r = card.getBoundingClientRect();
        var x = ((e.clientX - r.left) / r.width * 100).toFixed(1);
        var y = ((e.clientY - r.top) / r.height * 100).toFixed(1);
        card.style.setProperty('--mx', x + '%');
        card.style.setProperty('--my', y + '%');
    });
})();
