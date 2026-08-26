/*!
 * toc.js — mục lục dính + scroll-spy (dùng chung Landing & News).
 *  - Nếu .pm-toc đã có link (server render) → chỉ gắn scroll-spy vào các #target.
 *  - Nếu .pm-toc rỗng → tự sinh mục lục từ heading trong vùng nội dung.
 * Cấu hình qua data-attr trên .pm-toc:
 *   data-toc-content="<selector vùng nội dung>"  (mặc định: .pm-prose → .pm-article → #main)
 *   data-toc-headings="h2, h3"                   (mặc định)
 * @owner Session B · vanilla, không thư viện.
 */
(function () {
	'use strict';

	var OFFSET = 120; // Bù chiều cao header khi tính mục đang xem.

	document.addEventListener('DOMContentLoaded', function () {
		var tocs = document.querySelectorAll('.pm-toc');
		Array.prototype.forEach.call(tocs, initToc);
	});

	function initToc(toc) {
		var links = toc.querySelectorAll('a[href^="#"]');

		// Chưa có link → tự sinh từ heading.
		if (!links.length) {
			buildToc(toc);
			links = toc.querySelectorAll('a[href^="#"]');
		}
		if (!links.length) {
			return;
		}

		// Map link ↔ heading target.
		var items = [];
		Array.prototype.forEach.call(links, function (a) {
			var id = decodeURIComponent(a.getAttribute('href').slice(1));
			var target = id ? document.getElementById(id) : null;
			if (target) {
				items.push({ link: a, target: target });
			}
		});
		if (!items.length) {
			return;
		}

		// Cuộn mượt khi bấm (bổ trợ; CSS scroll-behavior đã smooth) + cập nhật hash.
		items.forEach(function (it) {
			it.link.addEventListener('click', function (e) {
				e.preventDefault();
				var y = window.pageYOffset + it.target.getBoundingClientRect().top - (OFFSET - 20);
				window.scrollTo({ top: y, behavior: 'smooth' });
				if (history.replaceState) {
					history.replaceState(null, '', '#' + it.target.id);
				}
				it.target.setAttribute('tabindex', '-1');
			});
		});

		// Scroll-spy: tô đậm mục có heading gần đỉnh nhất đã cuộn qua.
		var ticking = false;
		function spy() {
			ticking = false;
			var pos = window.pageYOffset + OFFSET;
			var current = items[0];
			for (var i = 0; i < items.length; i++) {
				var top = window.pageYOffset + items[i].target.getBoundingClientRect().top;
				if (top <= pos) {
					current = items[i];
				} else {
					break;
				}
			}
			setActive(items, current);
		}
		function onScroll() {
			if (!ticking) {
				ticking = true;
				window.requestAnimationFrame(spy);
			}
		}
		window.addEventListener('scroll', onScroll, { passive: true });
		window.addEventListener('resize', onScroll, { passive: true });
		spy();
	}

	function setActive(items, current) {
		items.forEach(function (it) {
			var on = it === current;
			it.link.classList.toggle('is-active', on);
			if (on) {
				it.link.setAttribute('aria-current', 'true');
			} else {
				it.link.removeAttribute('aria-current');
			}
		});
	}

	/* ---- Tự sinh mục lục từ heading ---- */
	function buildToc(toc) {
		var contentSel = toc.getAttribute('data-toc-content');
		var content = contentSel ? document.querySelector(contentSel) : null;
		if (!content) {
			content = document.querySelector('.pm-prose') ||
				document.querySelector('.pm-article') ||
				document.querySelector('#main') ||
				document.querySelector('main');
		}
		if (!content) {
			return;
		}

		var headingSel = toc.getAttribute('data-toc-headings') || 'h2, h3';
		var headings = content.querySelectorAll(headingSel);
		if (!headings.length) {
			toc.hidden = true;
			return;
		}

		var used = {};
		var list = document.createElement('ul');
		list.className = 'pm-toc__list';
		var subList = null;

		Array.prototype.forEach.call(headings, function (h) {
			var text = (h.textContent || '').trim();
			if (!text) {
				return;
			}
			if (!h.id) {
				h.id = uniqueId(slug(text), used);
			} else {
				used[h.id] = true;
			}

			var li = document.createElement('li');
			var a = document.createElement('a');
			a.className = 'pm-toc__link';
			a.href = '#' + h.id;
			a.textContent = text;
			li.appendChild(a);

			var isSub = h.tagName.toLowerCase() !== 'h2';
			if (isSub) {
				if (!subList) {
					subList = document.createElement('ul');
					subList.className = 'pm-toc__list';
					var last = list.lastElementChild;
					(last || list).appendChild(subList);
				}
				subList.appendChild(li);
			} else {
				subList = null;
				list.appendChild(li);
			}
		});

		if (!toc.querySelector('.pm-toc__title')) {
			var title = document.createElement('p');
			title.className = 'pm-toc__title';
			title.textContent = toc.getAttribute('data-toc-label') || 'Nội dung bài viết';
			toc.appendChild(title);
		}
		toc.appendChild(list);
	}

	function slug(text) {
		return text
			.toLowerCase()
			.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
			.replace(/đ/g, 'd')
			.replace(/[^a-z0-9\s-]/g, '')
			.trim().replace(/\s+/g, '-')
			.replace(/-+/g, '-') || 'muc';
	}

	function uniqueId(base, used) {
		var id = base, n = 2;
		while (used[id] || document.getElementById(id)) {
			id = base + '-' + n++;
		}
		used[id] = true;
		return id;
	}
})();
