/**
 * Practice Card Mobile Hover Effect
 * Добавляет hover эффекты при появлении карточки в viewport на мобильных устройствах
 */
(function() {
	'use strict';

	// Проверяем, что это мобильное устройство (только для мобильных)
	function isMobileDevice() {
		return window.matchMedia('(max-width: 991.98px)').matches;
	}

	// Функция для добавления hover эффекта
	function addHoverEffect(element) {
		if (!element.classList.contains('practice-card-in-viewport')) {
			element.classList.add('practice-card-in-viewport');
		}
	}

	// Функция для удаления hover эффекта
	function removeHoverEffect(element) {
		element.classList.remove('practice-card-in-viewport');
	}

	// Инициализация Intersection Observer
	function initPracticeCardMobileHover() {
		// Только на мобильных устройствах
		if (!isMobileDevice()) {
			return;
		}

		const practiceCards = document.querySelectorAll('.practice-card');

		if (practiceCards.length === 0) {
			return;
		}

		// Настройки Intersection Observer
		const options = {
			root: null, // viewport
			rootMargin: '0px',
			threshold: 0.5 // элемент должен быть виден на 50%
		};

		// Callback функция
		const observerCallback = (entries) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					// Элемент появился в viewport - добавляем hover эффект
					addHoverEffect(entry.target);
				} else {
					// Элемент ушел из viewport - удаляем hover эффект
					removeHoverEffect(entry.target);
				}
			});
		};

		// Создаем Intersection Observer
		const observer = new IntersectionObserver(observerCallback, options);

		// Наблюдаем за каждой карточкой
		practiceCards.forEach(card => {
			observer.observe(card);
		});
	}

	// Инициализация при загрузке DOM
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initPracticeCardMobileHover);
	} else {
		initPracticeCardMobileHover();
	}

	// Повторная инициализация при изменении размера окна (если нужно)
	// Это нужно, если карточки добавляются динамически (например, через AJAX)
	let resizeTimer;
	window.addEventListener('resize', function() {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function() {
			// Переинициализируем только если изменился тип устройства
			if (isMobileDevice()) {
				const cards = document.querySelectorAll('.practice-card:not(.practice-card-in-viewport)');
				if (cards.length > 0) {
					const options = {
						root: null,
						rootMargin: '0px',
						threshold: 0.5
					};
					const observer = new IntersectionObserver((entries) => {
						entries.forEach(entry => {
							if (entry.isIntersecting) {
								addHoverEffect(entry.target);
							} else {
								removeHoverEffect(entry.target);
							}
						});
					}, options);
					cards.forEach(card => observer.observe(card));
				}
			}
		}, 250);
	});
})();

