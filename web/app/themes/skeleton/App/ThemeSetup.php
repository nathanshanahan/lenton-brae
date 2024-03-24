<?php

namespace App;

use \App\Bone\ViteAssets;

class ThemeSetup {

	public function __construct() {

		global $assets;
		$assets = new ViteAssets();

		add_filter('use_block_editor_for_post', '__return_false');

		add_action( 'after_setup_theme', function() {
			$this->addThemeSupports();
			$this->removeThemeSupports();
			$this->registerMenus();
			// $this->enqueueFrontenedAssets();
		});

		$this->setupThemeClasses(); // should this be in after_setup_theme?

		add_action( 'init', self::class . '::registerPostTypes' );
	}

	public static function setupThemeClasses() {
		new \App\ACF();
		new \App\SiteOptions();
		// new \App\Bone\Theme\MigratedThemeFeatures();
		// new \App\Bone\Theme\Security();
		// new \App\Bone\Theme\Maintenance();
		// new \App\Bone\Theme\JWTHelper();
		// new \App\Bone\Theme\Instagram();
		// new \App\Bone\Theme\Headless();
		// new \App\Bone\Theme\Emails();
		// new \App\Bone\Theme\CookieNotice();
		// new \App\MFW\TinyMCE();
	}

	public static function registerPostTypes() {
		// \App\MFW\Events::setup();
		// \App\MFW\CommercialPartners::setup();
		// \App\MFW\Campaigns::setup();
		// \App\MFW\FestivalArchives::setup();

		self::addPageSupports();
	}

	private static function addPageSupports() {
		add_post_type_support('page', 'excerpt');
		register_taxonomy_for_object_type( 'post_tag', 'page' );
	}

	public static function addThemeSupports() {
		add_theme_support('title-tag');
		add_theme_support('post-thumbnails');
		add_theme_support('responsive-embeds');
		add_theme_support('html5', [
			'caption',
			'comment-form',
			'comment-list',
			'gallery',
			'search-form',
			'script',
			'style',
		]);
	}

	public static function removeThemeSupports() {
		remove_theme_support('block-templates');
		remove_theme_support('core-block-patterns');
	}

	public static function registerMenus() {
		register_nav_menus([
			'primary_navigation' => __('Primary Navigation'),
		]);
	}
}
