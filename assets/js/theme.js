(function () {
	'use strict';

	var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	// Soft scroll reveals.
	var revealEls = document.querySelectorAll('.serene-reveal');
	if (revealEls.length && !reduceMotion && 'IntersectionObserver' in window) {
		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					observer.unobserve(entry.target);
				}
			});
		}, { threshold: 0.12 });
		revealEls.forEach(function (el) { observer.observe(el); });
	} else {
		revealEls.forEach(function (el) { el.classList.add('is-visible'); });
	}

	// Eased count-up for stat numbers.
	var counters = document.querySelectorAll('[data-count-up]');
	counters.forEach(function (el) {
		var target = parseFloat(el.getAttribute('data-count-up'));
		if (isNaN(target)) { return; }
		if (reduceMotion) { el.textContent = target; return; }
		var suffix = el.getAttribute('data-suffix') || '';
		var started = false;
		var run = function () {
			if (started) { return; }
			started = true;
			var start = null;
			var duration = 1600;
			var step = function (ts) {
				if (!start) { start = ts; }
				var p = Math.min((ts - start) / duration, 1);
				var eased = 1 - Math.pow(1 - p, 3);
				el.textContent = Math.round(target * eased) + suffix;
				if (p < 1) { requestAnimationFrame(step); }
			};
			requestAnimationFrame(step);
		};
		if ('IntersectionObserver' in window) {
			var io = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) { run(); io.disconnect(); }
				});
			}, { threshold: 0.4 });
			io.observe(el);
		} else {
			run();
		}
	});

	// Back to top.
	var toTop = document.querySelector('.serene-to-top');
	if (toTop) {
		window.addEventListener('scroll', function () {
			toTop.classList.toggle('is-visible', window.scrollY > 600);
		}, { passive: true });
		toTop.addEventListener('click', function () {
			window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
		});
	}
})();
