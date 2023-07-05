/*global renderRecaptcha*/
window.onloadCallback = function () {
	renderRecaptcha();
}

export function rerunGravityFormsScripts()
{
	//Only find scripts inside the app-container as they are within Swup
	let selector = $('.body-wrapper').find('.gform_wrapper');

	if(selector.length > 0)
	{
		setTimeout(function () {
			let recaptcha_wrap = $('.ginput_recaptcha');
			let recaptcha_iframe = recaptcha_wrap.find('iframe');
			let og_script = 'https://www.google.com/recaptcha/api.js?hl=en&render=explicit&ver=5.2.2';
			let new_script = 'https://www.google.com/recaptcha/api.js?onload=onloadCallback&hl=en&render=explicit&ver=5.2.2';

			if (recaptcha_iframe.length <= 0 && recaptcha_wrap.length > 0) {
				if ($('script[src="' + og_script + '"]').length <= 0 || $('script[src="' + new_script + '"]').length <= 0) {
					$('body').append('<script type="text/javascript" src="' + new_script + '"></script>');
				}

				$(document).on('gform_post_render', function () {
					renderRecaptcha();
				});

			}

		}, 250);

		selector.each(function(){
			let scripts = $(this).parent().find('script');
			scripts.each(function(){
				let text = $(this).text();
				eval(text);
			});
		});
	}
}
