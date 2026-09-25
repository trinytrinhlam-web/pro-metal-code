/*!
 * main.js — init nhẹ dùng chung mọi trang.
 *  - Progressive-enhance menu mobile (header dùng checkbox CSS-only): đồng bộ aria-expanded,
 *    đóng menu khi bấm Esc / bấm link / xoay sang desktop.
 *  - Đánh dấu <body class="pm-has-sticky-cta"> khi có thanh CTA dính (chừa chỗ đáy).
 *  - Nút "lên đầu trang" nếu có [data-pm-totop].
 * @owner Session B · vanilla.
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		mobileMenu();
		stickyCtaSpace();
		backToTop();
	});

	/* ---- Menu mobile: bổ trợ a11y cho toggle checkbox của header ---- */
	function mobileMenu() {
		var toggle = document.getElementById('pm-nav-toggle'); // <input type=checkbox>
		var burger = document.querySelector('.pm-burger');      // <label for=pm-nav-toggle>
		var nav = document.getElementById('pm-primary-nav');
		if (!toggle || !burger) {
			return;
		}

		// Checkbox (đã có aria-label) là điều khiển thật: nhận focus + Space bật/tắt sẵn,
		// trạng thái "đã chọn" được trợ lý đọc màn hình đọc lên. KHÔNG gắn role="button" /
		// tabindex cho <label> — label không được mang role (lỗi ARIA) và tạo 2 điểm Tab trùng.
		if (nav && nav.id) {
			toggle.setAttribute('aria-controls', nav.id);
		}

		function close() {
			if (toggle.checked) {
				toggle.checked = false;
			}
		}

		// Enter cũng bật/tắt menu như nút bấm.
		toggle.addEventListener('keydown', function (e) {
			if (e.key === 'Enter') {
				e.preventDefault();
				toggle.checked = !toggle.checked;
			}
		});

		// Esc để đóng.
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') {
				close();
			}
		});

		// Bấm 1 link trong menu → đóng (mobile).
		if (nav) {
			nav.addEventListener('click', function (e) {
				var a = e.target.closest('a');
				if (a && !a.parentNode.classList.contains('menu-item-has-children')) {
					close();
				}
			});
		}

		// Xoay/đổi kích thước sang desktop → reset trạng thái.
		var mq = window.matchMedia('(min-width:1200px)'); // khớp mốc menu ngang (header-footer.css)
		var onMq = function () { if (mq.matches) { close(); } };
		if (mq.addEventListener) {
			mq.addEventListener('change', onMq);
		} else if (mq.addListener) {
			mq.addListener(onMq);
		}
	}

	/* ---- Chừa khoảng đáy khi có sticky CTA mobile ---- */
	function stickyCtaSpace() {
		if (document.querySelector('.pm-sticky-cta')) {
			document.body.classList.add('pm-has-sticky-cta');
		}
	}

	/* ---- Nút lên đầu trang (tuỳ chọn) ---- */
	function backToTop() {
		var btn = document.querySelector('[data-pm-totop]');
		if (!btn) {
			return;
		}
		var show = function () {
			btn.classList.toggle('is-visible', window.pageYOffset > 480);
		};
		window.addEventListener('scroll', show, { passive: true });
		btn.addEventListener('click', function (e) {
			e.preventDefault();
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});
		show();
	}
})();
