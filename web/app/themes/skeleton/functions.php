<?php

namespace App;

include_once 'includes/helpers.php';
include_once 'includes/template-functions.php';

/**
 * MAIN THEME SETUP
 *
 * If you're trying to find where some theme functionality is being added / run,
 * start by looking here.
 */
new \App\ThemeSetup();


function enqueue_scripts_styles() {

	global $assets;

	wp_enqueue_script_module('app-main', $assets->uri('main'), [], null);
	wp_enqueue_style('app-main-css', $assets->uri('main-css'), [], null);
}
add_action('wp_enqueue_scripts', __NAMESPACE__.'\\enqueue_scripts_styles', 20);


function enqueue_admin_scripts() {
	global $assets;

	wp_enqueue_style('app-admin-css', $assets->uri('admin-css'), [], null);
	wp_enqueue_script('app-admin', $assets->uri('admin'), [], null);
}
add_action('admin_enqueue_scripts', __NAMESPACE__.'\\enqueue_admin_scripts', 20);


// note: deactivate_plugins() may not exist if no plugins are installed
if (WP_ENV !== 'production' && function_exists('\deactivate_plugins')) {

	function deactivate_non_prod_plugins() {
		$to_deactivate = [];

		if (defined('BONE_DISABLE_MAILGUN') && BONE_DISABLE_MAILGUN) {
			$to_deactivate[] = 'mailgun/mailgun.php';
		}

		/**
		 * Note: activating a plugin that is disabled this way will appear to work - but if you
		 * refresh a couple times the Plugins page will show the plugins deactivated
		 */
		if ($to_deactivate) {
			\deactivate_plugins($to_deactivate);
		}
	}
	add_action('admin_init', __NAMESPACE__.'\\deactivate_non_prod_plugins');
}
