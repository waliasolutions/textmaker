/**
 * teXtmaker — Frontend.
 *
 * Menü, Karussells, Lightbox, Scroll-Reveal und das Datei-Feld des
 * Kontaktformulars. Ohne Abhängigkeiten.
 */
(() => {
	'use strict';

	const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	const i18n = (window.textmakerData && window.textmakerData.i18n) || {};

	/* --- Stempel im Hero ------------------------------------------------- */
	requestAnimationFrame(() => document.body.classList.add('is-ready'));

	/* --- Kopfzeile: Zustand beim Scrollen -------------------------------- */
	const head = document.querySelector('.site-head');

	if (head) {
		const onScroll = () => {
			head.dataset.stuck = String(window.scrollY > 24);
		};

		onScroll();
		addEventListener('scroll', onScroll, { passive: true });
	}

	/* --- Mobiles Menü ----------------------------------------------------- */
	const burger = document.querySelector('.burger');
	const nav = document.getElementById('hauptmenue');

	if (burger && nav) {
		burger.addEventListener('click', () => {
			const open = burger.getAttribute('aria-expanded') === 'true';
			burger.setAttribute('aria-expanded', String(!open));
			nav.dataset.open = String(!open);
		});

		nav.addEventListener('click', event => {
			if (!event.target.closest('a')) {
				return;
			}

			burger.setAttribute('aria-expanded', 'false');
			nav.dataset.open = 'false';
		});
	}

	/* --- Karussells -------------------------------------------------------- */
	document.querySelectorAll('[data-carousel]').forEach(root => {
		const track = root.querySelector('[data-track]');
		const prev = root.querySelector('[data-prev]');
		const next = root.querySelector('[data-next]');

		if (!track || !prev || !next) {
			return;
		}

		const step = () => {
			const first = track.firstElementChild;

			if (!first) {
				return track.clientWidth;
			}

			const gap = parseFloat(getComputedStyle(track).columnGap) || 0;

			return first.getBoundingClientRect().width + gap;
		};

		const sync = () => {
			prev.disabled = track.scrollLeft < 4;
			next.disabled = track.scrollLeft > track.scrollWidth - track.clientWidth - 4;
		};

		const behavior = reduced ? 'auto' : 'smooth';

		prev.addEventListener('click', () => track.scrollBy({ left: -step(), behavior }));
		next.addEventListener('click', () => track.scrollBy({ left: step(), behavior }));
		track.addEventListener('scroll', sync, { passive: true });
		addEventListener('resize', sync);
		sync();
	});

	/* --- Lightbox ---------------------------------------------------------- */
	const lb = document.getElementById('lightbox');

	if (lb) {
		const stage = lb.querySelector('[data-lb-stage]');
		const caption = lb.querySelector('[data-lb-caption]');
		const counter = lb.querySelector('[data-lb-count]');
		let group = [];
		let index = 0;
		let restoreFocus = null;

		const render = () => {
			const trigger = group[index];
			const source = trigger.querySelector('img');

			stage.innerHTML = '';

			const image = document.createElement('img');
			image.src = trigger.dataset.full || (source ? source.currentSrc || source.src : '');
			image.alt = trigger.dataset.caption || '';
			stage.append(image);

			caption.textContent = trigger.dataset.caption || '';
			counter.textContent = `${index + 1} / ${group.length}`;
		};

		const open = trigger => {
			const track = trigger.closest('[data-track]');

			if (!track) {
				return;
			}

			group = [...track.querySelectorAll('[data-lightbox]')];
			index = group.indexOf(trigger);
			restoreFocus = trigger;
			lb.hidden = false;
			document.body.style.overflow = 'hidden';
			render();
			lb.querySelector('[data-lb-close]').focus();
		};

		const close = () => {
			lb.hidden = true;
			stage.innerHTML = '';
			document.body.style.overflow = '';

			if (restoreFocus) {
				restoreFocus.focus();
			}
		};

		const move = delta => {
			index = (index + delta + group.length) % group.length;
			render();
		};

		document.addEventListener('click', event => {
			const trigger = event.target.closest('[data-lightbox]');

			if (trigger) {
				open(trigger);
				return;
			}

			if (event.target.closest('[data-lb-close]')) {
				close();
			} else if (event.target.closest('[data-lb-prev]')) {
				move(-1);
			} else if (event.target.closest('[data-lb-next]')) {
				move(1);
			} else if (event.target === lb || event.target === stage) {
				close();
			}
		});

		document.addEventListener('keydown', event => {
			if (lb.hidden) {
				return;
			}

			if (event.key === 'Escape') {
				close();
			} else if (event.key === 'ArrowLeft') {
				move(-1);
			} else if (event.key === 'ArrowRight') {
				move(1);
			}
		});
	}

	/* --- Scroll-Reveal ------------------------------------------------------ */
	const revealables = document.querySelectorAll('[data-reveal]');

	if (reduced || !('IntersectionObserver' in window)) {
		revealables.forEach(element => element.classList.add('in'));
	} else {
		const observer = new IntersectionObserver(
			(entries, self) => {
				entries.forEach(entry => {
					if (!entry.isIntersecting) {
						return;
					}

					entry.target.classList.add('in');
					self.unobserve(entry.target);
				});
			},
			{ rootMargin: '0px 0px -8% 0px', threshold: 0.04 }
		);

		revealables.forEach(element => observer.observe(element));
	}

	/* --- Datei-Feld des Kontaktformulars ------------------------------------ */
	const fileInput = document.getElementById('f-datei');
	const fileLabel = document.querySelector('[data-file-label]');

	if (fileInput && fileLabel) {
		const initial = fileLabel.textContent;

		fileInput.addEventListener('change', () => {
			const count = fileInput.files.length;

			if (count === 0) {
				fileLabel.textContent = initial;
			} else if (count === 1) {
				fileLabel.textContent = fileInput.files[0].name;
			} else {
				fileLabel.textContent = (i18n.filesPick || '%d Dateien ausgewählt').replace('%d', String(count));
			}
		});
	}

	/* --- Absende-Button gegen Doppelklicks sichern -------------------------- */
	const form = document.getElementById('anfrage-formular');

	if (form) {
		form.addEventListener('submit', () => {
			const button = form.querySelector('button[type="submit"]');

			if (!button) {
				return;
			}

			// Erst nach dem Absenden sperren, damit der Wert mitgeschickt wird.
			setTimeout(() => {
				button.disabled = true;
				button.textContent = i18n.sending || 'Wird gesendet …';
			}, 0);
		});
	}
})();
