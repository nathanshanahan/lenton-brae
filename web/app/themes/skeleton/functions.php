<?php

namespace App;

include_once 'includes/helpers.php';
include_once 'includes/template-functions.php';
include_once 'includes/gravity-forms.php';

/**
 * MAIN THEME SETUP
 *
 * If you're trying to find where some theme functionality is being added / run,
 * start by looking here.
 */
new \App\ThemeSetup();
new \App\PostTypes\Events();

function enqueue_scripts_styles()
{
	global $assets;

	wp_enqueue_script_module('app-main', $assets->uri('main'), [], null);
	wp_enqueue_style('app-main-css', $assets->uri('main-css'), [], null);
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts_styles', 20);

function enqueue_admin_scripts()
{
	global $assets;

	wp_enqueue_style('app-admin-css', $assets->uri('admin-css'), [], null);
	wp_enqueue_script('app-admin', $assets->uri('admin'), [], null);
}
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_admin_scripts', 20);

// note: deactivate_plugins() may not exist if no plugins are installed
if (WP_ENV !== 'production' && function_exists('\deactivate_plugins')) {

	function deactivate_non_prod_plugins()
	{
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
	add_action('admin_init', __NAMESPACE__ . '\\deactivate_non_prod_plugins');
}

/**
 * Add Distributor Role
 */
function add_distributor_role()
{
	add_role(
		'distributor',
		'Distributor',
		[
			'read' => true, // true allows this capability
			'edit_posts' => false, // false denies this capability
			'delete_posts' => false, // false denies this capability
			// Add more capabilities as needed
		]
	);
}
add_action('init', __NAMESPACE__ . '\\add_distributor_role');

/**
 * Redirect Distributor after login
 */
function distributor_login_redirect($redirect_to, $request, $user)
{
	// Check if the user is logged in and has the distributor role
	if (isset($user->roles) && in_array('distributor', $user->roles)) {
		// Redirect to the distributor login page
		return home_url('/distributors-login/');
	}
	// Otherwise, return the default redirect URL
	return $redirect_to;
}
add_filter('login_redirect', __NAMESPACE__ . '\\distributor_login_redirect', 10, 3);

/**
 * Wrap WordPress error messages in a custom class
 */
function wrap_wp_error_messages($buffer)
{
	$error_class = 'custom-error-class'; // Define your custom class here
	$pattern = '/<div class="(error|updated)">(.*?)<\/div>/s';
	$replacement = '<div class="$1 ' . $error_class . '">$2</div>';
	return preg_replace($pattern, $replacement, $buffer);
}

function buffer_start()
{
	ob_start(__NAMESPACE__ . '\\wrap_wp_error_messages');
}

function buffer_end()
{
	ob_end_flush();
}

add_action('wp_head', __NAMESPACE__ . '\\buffer_start', 1);
add_action('wp_footer', __NAMESPACE__ . '\\buffer_end', 1);

/**
 * Validate Media Component
 *
 * Checks if at least one of the media types (image, video mp4, or oembed) is not null or false.
 *
 * @param array $media The media component array.
 * @return bool True if at least one media type is valid, false otherwise.
 */
function validateMediaComponent($media)
{
	// Check if the media component array is set and has the required keys
	if (!isset($media)) {
		return false;
	}

	$mediaComponent = $media;

	// Check if image, video mp4, and oembed are all null or false
	$isImageValid = isset($mediaComponent['image']) && $mediaComponent['image'] !== false;
	$isVideoMp4Valid = isset($mediaComponent['video']['mp4']) && $mediaComponent['video']['mp4'] !== false;
	$isOembedValid = isset($mediaComponent['oembed']) && $mediaComponent['oembed'] !== null;

	// Return false if all three are null or false
	if (!$isImageValid && !$isVideoMp4Valid && !$isOembedValid) {
		return false;
	}

	return true;
}
