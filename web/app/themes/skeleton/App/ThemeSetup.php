<?php

namespace App;

use \App\Bone\ViteAssets;

class ThemeSetup
{

	public function __construct()
	{

		global $assets;
		$assets = new ViteAssets();

		add_filter('use_block_editor_for_post', '__return_false');

		add_action('after_setup_theme', function () {
			$this->addThemeSupports();
			$this->removeThemeSupports();
			$this->registerMenus();
			// $this->enqueueFrontenedAssets();
		});

		$this->setupThemeClasses(); // should this be in after_setup_theme?

		add_action('init', self::class . '::registerPostTypes');

		/**
		 * Nav - clean up classes and IDs
		 */
		add_filter('nav_menu_css_class', [$this, 'navClassFilter'], 100, 4);
		add_filter('nav_menu_item_id', [$this, 'navIdFilter'], 100, 4);
	}

	public static function setupThemeClasses(): void
	{
		new \App\Bone\Security();
		new \App\Bone\Maintenance();
		new \App\Bone\JWTHelper();
		new \App\Bone\Instagram();
		new \App\Bone\Headless();
		new \App\Bone\Emails();
		new \App\Bone\CookieNotice();
		new \App\Bone\WP_Cleanup();

		new \App\ACF();
		new \App\SiteOptions();
		new \App\TinyMCE();
	}

	public static function registerPostTypes(): void
	{
		/**
		 * Add post type registrations here. E.g.:
		 *
		 * \App\PostTypes\Events::setup();
		 */
		\App\PostTypes\Posts::setup();
		self::addPageSupports();
	}

	private static function addPageSupports(): void
	{
		add_post_type_support('page', 'excerpt');
		register_taxonomy_for_object_type('post_tag', 'page');
	}

	public static function addThemeSupports(): void
	{
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

	public static function removeThemeSupports(): void
	{
		remove_theme_support('block-templates');
		remove_theme_support('core-block-patterns');
	}

	public static function registerMenus(): void
	{
		register_nav_menus([
			'primary_navigation' => __('Primary Navigation'),
			'footer_navigation' => __('Footer Navigation'),
		]);
	}

	/**
	 * Cleans the class, keeping current-menu-item only
	 *
	 * @param $var
	 * @return array|string
	 */
	public function navClassFilter(
		array $classes,
		\WP_Post $menu_item,
		\stdClass $args,
		int $depth
	): array {
		$allow_list = [
			'current-menu-item',
			'menu-item-has-children',
		];
		return is_array($classes) ? array_intersect($classes, $allow_list) : '';
	}

	/**
	 * Cleans the id of the menu back to the title
	 */
	public function navIdFilter(
		string $id,
		\WP_Post $item,
		\stdClass $args,
		int $depth
	): string {
		$base = $args->menu_id ?? 'menu';

		return "{$base}-{$item->post_name}";
	}
}
