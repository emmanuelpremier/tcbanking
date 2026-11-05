(function () {
  'use strict';
  function init() {
    var toggle = document.querySelector('.nav-toggle') ||
      document.querySelector('[aria-controls="primary-nav"]') ||
      document.querySelector('.menu-toggle') || document.querySelector('.hamburger');
    var nav = document.getElementById('primary-nav') ||
      document.querySelector('.primary-nav') || document.querySelector('header nav');
    if (!toggle || !nav) { return; }

    var openClasses = ['is-open','open','active','show','is-active','nav-open','expanded'];
    var openY = window.pageYOffset || 0;
    var wasOpen = false;

    function isOpen() {
      var aria = toggle.getAttribute('aria-expanded');
      if (aria === 'true') { return true; }
      if (aria === 'false') { return false; }
      if (window.innerWidth >= 900) { return false; }
      var cs = window.getComputedStyle(nav);
      return cs.display !== 'none' && cs.visibility !== 'hidden' && nav.offsetHeight > 0;
    }
    function forceClose() {
      toggle.setAttribute('aria-expanded', 'false');
      openClasses.forEach(function (c) {
        nav.classList.remove(c); toggle.classList.remove(c);
        document.body.classList.remove(c); document.documentElement.classList.remove(c);
      });
    }
    function closeMenu() {
      if (!isOpen()) { return; }
      toggle.click();
      window.setTimeout(function () { if (isOpen()) { forceClose(); } }, 60);
    }
    function checkState() {
      var open = isOpen();
      if (open && !wasOpen) { openY = window.pageYOffset || 0; }
      wasOpen = open;
    }
    toggle.addEventListener('click', function () { window.setTimeout(checkState, 0); });
    new MutationObserver(checkState).observe(toggle,
      { attributes: true, attributeFilter: ['aria-expanded', 'class'] });
    new MutationObserver(checkState).observe(nav,
      { attributes: true, attributeFilter: ['class', 'style', 'hidden'] });

    function insidePanel(t) {
      return !!(t && t.nodeType === 1 && (nav.contains(t) || toggle.contains(t)));
    }
    function onScroll(e) {
      if (!isOpen()) { return; }
      if (e && insidePanel(e.target)) { return; }
      if (Math.abs((window.pageYOffset || 0) - openY) > 10) { closeMenu(); }
    }
    function onGesture(e) {
      if (isOpen() && !insidePanel(e.target)) { closeMenu(); }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    document.addEventListener('scroll', onScroll, { passive: true, capture: true });
    window.addEventListener('touchmove', onGesture, { passive: true });
    window.addEventListener('wheel', onGesture, { passive: true });
    window.addEventListener('resize', function () {
      if (window.innerWidth >= 900 && isOpen()) { closeMenu(); }
    });
    checkState();
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else { init(); }
})();
