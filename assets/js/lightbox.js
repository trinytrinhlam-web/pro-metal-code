/*!
 * lightbox.js — xem ảnh phóng to dùng chung (gallery).
 * Kích hoạt tự động cho:
 *   - .pm-gallery  → mỗi <a href="ảnh-lớn"> bên trong là 1 item (nhóm theo gallery).
 *   - [data-lightbox="tên-nhóm"] trên <a> hoặc <img> (gom theo tên nhóm).
 * Điều hướng: ← →, Esc, bấm nền, nút đóng/trước/sau. A11y: khoá tiêu điểm, trả tiêu điểm.
 * @owner Session B · vanilla, không thư viện.
 */
(function () {
	'use strict';

	var groups = {};   // name → [items]
	var overlay, imgEl, capEl, counterEl, btnPrev, btnNext;
	var current = { name: null, index: 0 };
	var lastFocus = null;

	document.addEventListener('DOMContentLoaded', collect);

	function collect() {
		var gi = 0;

		// 1) .pm-gallery
		Array.prototype.forEach.call(document.querySelectorAll('.pm-gallery'), function (gal) {
			var name = gal.getAttribute('data-lightbox') || ('pm-gallery-' + (gi++));
			Array.prototype.forEach.call(gal.querySelectorAll('a[href]'), function (a) {
				addItem(name, a);
			});
		});

		// 2) [data-lightbox] rời rạc (chưa nằm trong .pm-gallery đã xử lý)
		Array.prototype.forEach.call(document.querySelectorAll('[data-lightbox]'), function (el) {
			if (el.closest('.pm-gallery')) {
				return;
			}
			addItem(el.getAttribute('data-lightbox') || 'pm-lightbox', el);
		});

		if (!Object.keys(groups).length) {
			return;
		}
		build();
	}

	function addItem(name, el) {
		var full, thumb, caption;

		if (el.tagName === 'A') {
			full = el.getAttribute('href');
			thumb = el.querySelector('img');
			caption = el.getAttribute('data-caption') ||
				(thumb && thumb.getAttribute('alt')) || el.getAttribute('title') || '';
		} else if (el.tagName === 'IMG') {
			full = el.getAttribute('data-full') || el.getAttribute('src');
			caption = el.getAttribute('data-caption') || el.getAttribute('alt') || '';
		}
		if (!full || !/\.(jpe?g|png|gif|webp|avif|svg)(\?|#|$)/i.test(full)) {
			return; // Bỏ qua link không phải ảnh.
		}

		if (!groups[name]) {
			groups[name] = [];
		}
		var index = groups[name].length;
		groups[name].push({ full: full, caption: caption });

		el.classList.add('pm-lb-trigger');
		el.setAttribute('role', 'button');
		el.addEventListener('click', function (e) {
			e.preventDefault();
			open(name, index);
		});
	}

	function build() {
		overlay = document.createElement('div');
		overlay.className = 'pm-lightbox-overlay';
		overlay.setAttribute('role', 'dialog');
		overlay.setAttribute('aria-modal', 'true');
		overlay.setAttribute('aria-label', 'Xem ảnh');
		overlay.hidden = true;
		overlay.innerHTML =
			'<button type="button" class="pm-lb__close" aria-label="Đóng">&times;</button>' +
			'<button type="button" class="pm-lb__nav pm-lb__prev" aria-label="Ảnh trước">&#8249;</button>' +
			'<figure class="pm-lb__figure">' +
			'<img class="pm-lb__img" alt="">' +
			'<figcaption class="pm-lb__cap"></figcaption>' +
			'</figure>' +
			'<button type="button" class="pm-lb__nav pm-lb__next" aria-label="Ảnh sau">&#8250;</button>' +
			'<span class="pm-lb__counter" aria-hidden="true"></span>';
		document.body.appendChild(overlay);

		imgEl = overlay.querySelector('.pm-lb__img');
		capEl = overlay.querySelector('.pm-lb__cap');
		counterEl = overlay.querySelector('.pm-lb__counter');
		btnPrev = overlay.querySelector('.pm-lb__prev');
		btnNext = overlay.querySelector('.pm-lb__next');

		overlay.querySelector('.pm-lb__close').addEventListener('click', close);
		btnPrev.addEventListener('click', function () { step(-1); });
		btnNext.addEventListener('click', function () { step(1); });
		overlay.addEventListener('click', function (e) {
			if (e.target === overlay || e.target.classList.contains('pm-lb__figure')) {
				close();
			}
		});
		document.addEventListener('keydown', onKey);
	}

	function open(name, index) {
		current.name = name;
		current.index = index;
		lastFocus = document.activeElement;
		show();
		overlay.hidden = false;
		document.body.classList.add('pm-lb-open');
		btnNext.focus();
	}

	function close() {
		if (!overlay || overlay.hidden) {
			return;
		}
		overlay.hidden = true;
		document.body.classList.remove('pm-lb-open');
		imgEl.removeAttribute('src');
		if (lastFocus && lastFocus.focus) {
			lastFocus.focus();
		}
	}

	function step(dir) {
		var items = groups[current.name];
		if (!items) {
			return;
		}
		current.index = (current.index + dir + items.length) % items.length;
		show();
	}

	function show() {
		var items = groups[current.name];
		var item = items[current.index];
		var many = items.length > 1;

		imgEl.setAttribute('src', item.full);
		imgEl.setAttribute('alt', item.caption || '');
		capEl.textContent = item.caption || '';
		capEl.style.display = item.caption ? '' : 'none';
		counterEl.textContent = (current.index + 1) + ' / ' + items.length;
		counterEl.style.display = many ? '' : 'none';
		btnPrev.style.display = many ? '' : 'none';
		btnNext.style.display = many ? '' : 'none';
	}

	function onKey(e) {
		if (overlay.hidden) {
			return;
		}
		if (e.key === 'Escape') {
			close();
		} else if (e.key === 'ArrowLeft') {
			step(-1);
		} else if (e.key === 'ArrowRight') {
			step(1);
		} else if (e.key === 'Tab') {
			// Khoá tiêu điểm trong overlay.
			var focusables = overlay.querySelectorAll('button:not([style*="display: none"])');
			if (!focusables.length) {
				return;
			}
			var first = focusables[0];
			var last = focusables[focusables.length - 1];
			if (e.shiftKey && document.activeElement === first) {
				e.preventDefault();
				last.focus();
			} else if (!e.shiftKey && document.activeElement === last) {
				e.preventDefault();
				first.focus();
			}
		}
	}
})();
