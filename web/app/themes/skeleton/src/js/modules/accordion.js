export function initAccordion() {
	const accordionWrappers = document.querySelectorAll('.p-accordion__accordion-wrapper');

	if (!accordionWrappers.length) {
		return;
	}

	accordionWrappers.forEach(accordionWrapper => {
		const multiple = accordionWrapper.getAttribute('data-multiple') === 'true';

		const buttons = accordionWrapper.querySelectorAll('[role="tab"]');
		const contents = accordionWrapper.querySelectorAll('[role="tabpanel"]');

		buttons.forEach(button => {
			button.addEventListener('click', function () {

				console.log('button clicked');

				const isExpanded = button.getAttribute('aria-expanded') === 'true';
				const content = document.getElementById(button.getAttribute('aria-controls'));

				// If multiple accordions can't be open, close all other accordions within this wrapper
				if (!multiple) {
					buttons.forEach(btn => {
						btn.setAttribute('aria-expanded', 'false');
					});
					contents.forEach(ctn => {
						ctn.setAttribute('aria-hidden', 'true');
					});
				}

				// Toggle the clicked accordion
				button.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
				content.setAttribute('aria-hidden', isExpanded ? 'true' : 'false');
			});
		});
	});
}

