/*global renderRecaptcha*/
// window.onloadCallback = function () {
	// renderRecaptcha();
// }

export function rerunGravityFormsScripts()
{
	// Only find scripts within the main Swup container
	const forms = document.querySelectorAll('.swup-page-loader .gform_wrapper');
	if (!forms?.length) return;

	setTimeout(function () {
		const recaptcha_wrappers = document.querySelectorAll('.ginput_recaptcha');
		if (!recaptcha_wrappers) return;

		if (window.renderRecaptcha) {
			document.addEventListener('gform_post_render', window.renderRecaptcha);
			document.addEventListener('gform_post_render', () => console.log('gform_post_render'));
		}

		let og_script = 'https://www.google.com/recaptcha/api.js?hl=en&render=explicit&ver=5.2.2';
		let new_script = 'https://www.google.com/recaptcha/api.js?onload=onloadCallback&hl=en&render=explicit&ver=5.2.2';

		const hasLoadedIframe = Array.from(recaptcha_wrappers).some(wrapper => wrapper.querySelector('iframe'));
		if (hasLoadedIframe) return; // script already ran - nothing to do

		const existingScripts = document.querySelectorAll(`script[src="${og_script}"], script[src="${new_script}"]`);
		if (existingScripts) return;

		const scriptToInject = document.createElement('script');
		scriptToInject.src = new_script;
		document.body.append(scriptToInject);
	}, 250);

	for (const form of forms) {
		let scripts = form.parentElement?.querySelectorAll('script');
		if (!scripts.length) continue;

		for (const script of scripts) {
			indirectEval(script.text);
		}
	}
}


// Use indirect eval() of code to fix issues with scope and strict-mode. In essence, it
// executes the passed code as if it was in a <script> tag rather than in the calling
// scope / context.
//
// See MDN for details on the difference between direct and indirect eval
// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/eval#direct_and_indirect_eval
function indirectEval(codeString) {
	eval?.(codeString);
}
