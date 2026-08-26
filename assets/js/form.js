/*!
 * form.js — gửi mọi form lead qua AJAX tới REST /prometal/v1/lead.
 * Localize sẵn: PROMETAL.rest (endpoint) + PROMETAL.nonce (wp_rest).
 * Hợp đồng markup: input name="ten|sdt|noidung|src", honeypot name="website",
 *                  thông báo <p class="pm-form-msg"> (thêm class is-ok / is-error).
 * @owner Session B · vanilla, không phụ thuộc thư viện.
 */
(function () {
	'use strict';

	var CFG = window.PROMETAL || {};
	if (!CFG.rest) {
		return; // Chưa localize → không làm gì (tránh lỗi).
	}

	/*
	 * .pm-form là markup landing cũ được viết trực tiếp trong Page content.
	 * Bắt ở capture phase để chặn script inline cũ gửi trùng/sai giao thức.
	 */
	document.addEventListener('submit', function (e) {
		var form = e.target;
		if (!form || !form.matches || !form.matches('form.pm-lead-form, form.pm-form')) {
			return;
		}
		e.preventDefault();
		e.stopImmediatePropagation();
		onSubmit(form);
	}, true);

	function onSubmit(form) {
		var btn = form.querySelector('[type="submit"]');
		var msg = getMsg(form);

		// Chống double-submit.
		if (form.dataset.pmBusy === '1') {
			return;
		}
		form.dataset.pmBusy = '1';
		setBusy(btn, true);
		setMsg(msg, '', '');

		// Gom dữ liệu form → application/x-www-form-urlencoded.
		var body = new URLSearchParams(new FormData(form));
		if (!body.get('noidung') && body.get('nhucau')) {
			body.set('noidung', body.get('nhucau'));
		}
		appendExtraFields(form, body);
		if (!body.get('src')) {
			body.set('src', form.getAttribute('data-pm-source') || pageSource());
		}
		body.set('_wpnonce', CFG.nonce || '');

		fetch(CFG.rest, {
			method: 'POST',
			credentials: 'same-origin',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
				'X-WP-Nonce': CFG.nonce || '',
				'Accept': 'application/json'
			},
			body: body.toString()
		})
			.then(function (res) {
				return res.json().catch(function () { return {}; }).then(function (data) {
					return { status: res.status, data: data };
				});
			})
			.then(function (out) {
				var data = out.data || {};
				var ok = data.ok === true;
				var text = data.message || (ok
					? 'Đã gửi thành công!'
					: 'Có lỗi xảy ra, vui lòng thử lại hoặc gọi hotline.');

				if (ok) {
					setMsg(msg, text, 'is-ok');
					form.reset();
					focusFirst(form);
				} else {
					setMsg(msg, text, 'is-error');
				}
			})
			.catch(function () {
				setMsg(msg, 'Không kết nối được máy chủ. Vui lòng thử lại hoặc gọi hotline.', 'is-error');
			})
			.then(function () {
				form.dataset.pmBusy = '';
				setBusy(btn, false);
			});
	}

	/* ---- helpers ---- */

	function setBusy(btn, busy) {
		if (!btn) {
			return;
		}
		btn.disabled = busy;
		btn.classList.toggle('is-loading', busy);
		btn.setAttribute('aria-busy', busy ? 'true' : 'false');
	}

	function setMsg(el, text, state) {
		if (!el) {
			return;
		}
		el.textContent = text;
		el.classList.remove('is-ok', 'is-error');
		if (state) {
			el.classList.add(state);
		}
	}

	function getMsg(form) {
		var msg = form.querySelector('.pm-form-msg');
		if (msg) {
			return msg;
		}
		msg = document.createElement('p');
		msg.className = 'pm-form-msg';
		msg.setAttribute('role', 'status');
		msg.setAttribute('aria-live', 'polite');
		form.appendChild(msg);
		return msg;
	}

	function pageSource() {
		var title = (document.title || '').replace(/\s+/g, ' ').trim();
		return ('Landing: ' + title + ' (' + window.location.pathname + ')').slice(0, 190);
	}

	function appendExtraFields(form, body) {
		var ignored = { ten: true, sdt: true, noidung: true, nhucau: true, src: true, website: true, _wpnonce: true };
		var extras = [];
		Array.prototype.forEach.call(new FormData(form).entries(), function (entry) {
			var name = entry[0];
			var value = String(entry[1] || '').trim();
			if (!value || ignored[name]) {
				return;
			}
			var field = form.querySelector('[name="' + cssEscape(name) + '"]');
			var label = field && field.id ? form.querySelector('label[for="' + cssEscape(field.id) + '"]') : null;
			extras.push((label ? label.textContent.trim() : name) + ': ' + value);
		});
		if (extras.length) {
			body.set('noidung', [body.get('noidung') || '', extras.join('\n')].filter(Boolean).join('\n'));
		}
	}

	function cssEscape(value) {
		return window.CSS && window.CSS.escape ? window.CSS.escape(value) : String(value).replace(/[^a-zA-Z0-9_-]/g, '\\$&');
	}

	function focusFirst(form) {
		var first = form.querySelector('input:not([type="hidden"]):not([tabindex="-1"]), textarea, select');
		if (first) {
			try { first.focus(); } catch (err) {}
		}
	}
})();
