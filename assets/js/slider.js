/*!
 * slider.js — Hero (cross-fade) + testimonial (scroll-snap) sliders trang chủ.
 * @owner Session C · vanilla, không phụ thuộc thư viện.
 *
 * Hooks:
 *   Hero:        [data-pm-hero]  (slide .pm-hero__slide, dot [data-pm-hero-dot],
 *                nút [data-pm-hero-prev] / [data-pm-hero-next]).
 *   Slider chung:[data-pm-slider] (viewport .pm-tm-slider__viewport cuộn snap,
 *                nút [data-pm-slider-prev/next], chấm [data-pm-slider-dots]).
 * Tôn trọng prefers-reduced-motion: tắt tự động chạy.
 */
(function () {
	'use strict';

	var REDUCE = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	document.addEventListener('DOMContentLoaded', function () {
		initHeroes();
		initSliders();
	});

	/* ============================================================= *
	 *  HERO — cross-fade
	 * ============================================================= */
	function initHeroes() {
		var heroes = document.querySelectorAll('[data-pm-hero]');
		Array.prototype.forEach.call(heroes, function (hero) {
			var slides = hero.querySelectorAll('.pm-hero__slide');
			var dots = hero.querySelectorAll('[data-pm-hero-dot]');
			if (slides.length < 2) {
				return;
			}

			var index = 0;
			var timer = null;
			var DELAY = 5000;

			function hydrate(slide) {
				if (!slide || slide.querySelector('img')) {
					return;
				}
				var src = slide.getAttribute('data-pm-hero-src');
				if (!src) {
					return;
				}
				var image = document.createElement('img');
				image.className = 'pm-hero__image';
				image.src = src;
				image.alt = '';
				image.width = 1600;
				image.height = 900;
				image.decoding = 'async';
				slide.appendChild(image);
				slide.removeAttribute('data-pm-hero-src');
			}

			function show(n) {
				index = (n + slides.length) % slides.length;
				hydrate(slides[index]);
				Array.prototype.forEach.call(slides, function (s, k) {
					s.classList.toggle('is-active', k === index);
				});
				Array.prototype.forEach.call(dots, function (d, k) {
					var on = k === index;
					d.classList.toggle('is-active', on);
					d.setAttribute('aria-selected', on ? 'true' : 'false');
				});
			}

			function next() { show(index + 1); }
			function prev() { show(index - 1); }

			function start() {
				if (REDUCE || timer) {
					return;
				}
				timer = window.setInterval(next, DELAY);
			}
			function stop() {
				if (timer) {
					window.clearInterval(timer);
					timer = null;
				}
			}
			function restart() { stop(); start(); }

			Array.prototype.forEach.call(dots, function (dot) {
				dot.addEventListener('click', function () {
					show(parseInt(dot.getAttribute('data-pm-hero-dot'), 10) || 0);
					restart();
				});
			});

			bindNav(hero, '[data-pm-hero-prev]', function () { prev(); restart(); });
			bindNav(hero, '[data-pm-hero-next]', function () { next(); restart(); });

			// Tạm dừng khi hover / focus để dễ đọc & thao tác.
			hero.addEventListener('mouseenter', stop);
			hero.addEventListener('mouseleave', start);
			hero.addEventListener('focusin', stop);
			hero.addEventListener('focusout', start);

			// Bàn phím: mũi tên trái/phải khi hero đang được focus.
			hero.addEventListener('keydown', function (e) {
				if (e.key === 'ArrowLeft') { prev(); restart(); }
				else if (e.key === 'ArrowRight') { next(); restart(); }
			});

			show(0);
			// Ảnh đầu đã được ưu tiên cho LCP. Các ảnh còn lại chỉ tải sau khi
			// lượt vẽ đầu ổn định hoặc ngay khi khách tương tác với slider.
			window.setTimeout(function () { hydrate(slides[1]); }, 6000);
			start();
		});
	}

	/* ============================================================= *
	 *  SLIDER CHUNG — cuộn ngang scroll-snap (testimonials)
	 * ============================================================= */
	function initSliders() {
		var sliders = document.querySelectorAll('[data-pm-slider]');
		Array.prototype.forEach.call(sliders, function (root) {
			var viewport = root.querySelector('.pm-tm-slider__viewport');
			var track = root.querySelector('.pm-tm-slider__track');
			var dotsWrap = root.querySelector('[data-pm-slider-dots]');
			if (!viewport || !track) {
				return;
			}

			var pages = 1;
			var page = 0;

			function pageCount() {
				// Bao nhiêu "trang" bằng chiều rộng khung nhìn.
				var cw = viewport.clientWidth;
				if (!cw) {
					return pages; // Chưa có layout (clientWidth = 0) → giữ nguyên, đo lại sau.
				}
				return Math.max(1, Math.round(track.scrollWidth / cw));
			}

			function goTo(p) {
				page = Math.max(0, Math.min(pages - 1, p));
				viewport.scrollTo({
					left: page * viewport.clientWidth,
					behavior: REDUCE ? 'auto' : 'smooth'
				});
			}

			function currentPage() {
				var cw = viewport.clientWidth;
				return cw ? Math.round(viewport.scrollLeft / cw) : 0;
			}

			function buildDots() {
				if (!dotsWrap) {
					return;
				}
				dotsWrap.innerHTML = '';
				for (var i = 0; i < pages; i++) {
					(function (i) {
						var b = document.createElement('button');
						b.type = 'button';
						b.className = 'pm-tm-slider__dot';
						b.setAttribute('role', 'tab');
						b.setAttribute('aria-label', 'Trang ' + (i + 1));
						b.addEventListener('click', function () { goTo(i); });
						dotsWrap.appendChild(b);
					})(i);
				}
			}

			function syncActive() {
				page = currentPage();
				if (dotsWrap) {
					var dots = dotsWrap.children;
					for (var i = 0; i < dots.length; i++) {
						var on = i === page;
						dots[i].classList.toggle('is-active', on);
						dots[i].setAttribute('aria-selected', on ? 'true' : 'false');
					}
				}
				toggleArrow('[data-pm-slider-prev]', page > 0);
				toggleArrow('[data-pm-slider-next]', page < pages - 1);
			}

			function toggleArrow(sel, enabled) {
				var btn = root.querySelector(sel);
				if (!btn) {
					return;
				}
				btn.disabled = !enabled;
			}

			function measure() {
				pages = pageCount();
				// Vừa khung (chỉ 1 trang) → chuyển sang trạng thái tĩnh, ẩn điều khiển.
				root.classList.toggle('is-static', pages <= 1);
				buildDots();
				syncActive();
			}

			bindNav(root, '[data-pm-slider-prev]', function () { goTo(currentPage() - 1); });
			bindNav(root, '[data-pm-slider-next]', function () { goTo(currentPage() + 1); });

			viewport.addEventListener('scroll', debounce(syncActive, 80), { passive: true });
			window.addEventListener('resize', debounce(measure, 150));
			// Đo lại khi layout ổn định (ảnh/webfont tải xong) — tránh đọc kích thước 0.
			window.addEventListener('load', measure);

			measure();
			// Khung hình kế tiếp: chắc chắn đã có layout để đo trang & sinh chấm.
			if (window.requestAnimationFrame) {
				window.requestAnimationFrame(measure);
			}
		});
	}

	/* ---- helpers ---- */
	function bindNav(root, selector, handler) {
		var btn = root.querySelector(selector);
		if (btn) {
			btn.addEventListener('click', handler);
		}
	}

	function debounce(fn, wait) {
		var t;
		return function () {
			var ctx = this, args = arguments;
			window.clearTimeout(t);
			t = window.setTimeout(function () { fn.apply(ctx, args); }, wait);
		};
	}
})();
