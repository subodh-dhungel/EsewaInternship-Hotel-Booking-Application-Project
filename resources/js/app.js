const navigationButton = document.querySelector('.mobile-menu-button');
const navigationMenu = document.querySelector('.navbar-menu');

if (navigationButton && navigationMenu) {
	navigationButton.addEventListener('click', () => {
		const isOpen = navigationButton.getAttribute('aria-expanded') === 'true';

		navigationButton.setAttribute('aria-expanded', String(!isOpen));
		navigationButton.setAttribute('aria-label', isOpen ? 'Open navigation' : 'Close navigation');
		navigationButton.classList.toggle('is-open', !isOpen);
		navigationMenu.classList.toggle('is-open', !isOpen);
	});

	navigationMenu.addEventListener('click', (event) => {
		if (event.target.closest('a')) {
			navigationButton.setAttribute('aria-expanded', 'false');
			navigationButton.setAttribute('aria-label', 'Open navigation');
			navigationButton.classList.remove('is-open');
			navigationMenu.classList.remove('is-open');
		}
	});
}

document.querySelectorAll('.flash-message__close').forEach((button) => {
	button.addEventListener('click', () => {
		button.closest('.flash-message')?.remove();
	});
});
