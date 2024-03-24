<?php

namespace App;

class SiteOptions {

	public function __construct() {

		if (!function_exists('acf_add_options_page')) {
			return;
		}

		$this->registerSiteOptionsPage();
		$this->optBugherdScript();
		$this->optDisablePasswordChangeNotifications();
	}

	public function registerSiteOptionsPage(): void {
		add_action('init', function() {
			acf_add_options_page([
				'page_title' => 'Site Options',
				'post_id' => 'options',
				'menu_slug' => 'site-options',
				'redirect' => false,
			]);
		});
	}

	public function optBugherdScript(): void {

		// only enable on staging
		if (WP_ENV !== 'staging') {
			return;
		}

		add_action('init', function() {
			$key = get_field('bugherd_key', 'option');
			if (!empty(get_field('bugherd_enabled', 'option')) && !empty($key)) {
				add_action('wp_print_footer_scripts', function() use ($key) {
					$src = 'https://www.bugherd.com/sidebarv2.js?apikey=' . $key;

					echo '<script type="text/javascript" src="'. $src . '" async="true"></script>';
				}, 99999);
			}
		});
	}

	public function optDisablePasswordChangeNotifications(): void {
		/**
		 * TODO: Fix this
		 * Temp fix to remove warning
		 */
		return;

		if(!empty(get_field('disable_send_password_change_notifications', 'options'))) {
			if(!function_exists('wp_password_change_notification')) {
				/**
				 * AY:
				 * I believe this works by registering a function before WP so that the WP
				 * functionality never fires. James would know the actual answer.
				 */
				function wp_password_change_notification($user) {
					return;
				}
			}
		}
	}

	/**
	 * Retrieves all tracking platforms.
	 *
	 * If a platform name/key is passed in only that platform is returned, unwrapped
	 * (i.e. without its key).
	 */
	public static function socialPlatforms(string $platform = ''): array {
		$platforms = get_field('social_platforms', 'options') ?: [];

		if ($platform) {
			return $platforms[$platform] ?? [];
		}

		return $platforms;
	}

	/**
	 * Retrieves all tracking platforms.
	 *
	 * If a platform name/key is passed in only that platform is returned, unwrapped
	 * (i.e. without its key).
	 */
	public static function trackingPlatforms(string $platform = ''): array {
		$platforms = get_field('tracking_platforms', 'options') ?: [];

		if ($platform) {
			return $platforms[$platform] ?? [];
		}

		return $platforms;
	}
}
