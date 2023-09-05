<?php

namespace App\Bone;

class ThemeSetup {

	public function __construct() {

		add_filter('use_block_editor_for_post', '__return_false');

		add_action( 'after_setup_theme', function() {
			$this->addThemeSupports();
			$this->removeThemeSupports();
		});
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
}
