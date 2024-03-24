<?php

namespace App;

class TinyMCE
{
	public function __construct() {
		if (!is_admin()) {
			return;
		}

		$user_can_edit = current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' );
		if (!$user_can_edit) {
			return;
		}

		$is_wysiwyg_enabled = 'true' === get_user_option( 'rich_editing' );
		if ( $is_wysiwyg_enabled ) {
			/**
			 * enqueue JS plugins for mce
			 */
			// add_filter( 'mce_external_plugins', [self::class, 'add_mce_plugins'] );

			/**
			 * globally customise first row of mce buttons
			 */
			// add_filter( 'mce_buttons' , [self::class, 'customise_mce_row_1'] );

			/*
				* globally customise second row of mce buttons
				*/
			// add_filter( 'mce_buttons_2' , [self::class, 'customise_mce_row_2'] );

			/**
			 * customise ACF-managed mce editor toolbars
			 */
			add_filter( 'acf/fields/wysiwyg/toolbars' , [self::class, 'customise_mce_toolbars'], 999, 1 );

			/**
			 * register custom style formats
			 */
			add_filter( 'tiny_mce_before_init', [self::class, 'customise_mce_insert_formats'] );
			add_filter( 'tiny_mce_before_init', [self::class, 'customise_mce_set_textcolors'] );
			add_filter( 'tiny_mce_before_init', [self::class, 'customise_mce_block_formats'] );



			/**
			 * register additional stylesheets and fonts to the editor
			 */
			add_filter( 'tiny_mce_before_init', [self::class, 'mce_add_styles'] );
		}
	}

	public static function mce_add_styles( $settings ) {

		$indexCSS = 'style.css';
		$stylesheet_uri = get_stylesheet_directory_uri() . $GLOBALS['manifest']->$indexCSS;
		if (isViteHMRAvailable()) {
			$stylesheet_uri = getViteDevServerAddress() . '/src/scss/style.scss';
		}


		$additions = [
			// ...stylesheets and fonts
			// 'https://use.typekit.net/xxxxxx.css', // e.g.
			$stylesheet_uri,
		];

		$separator = ',';
		$needs_leading_separator = !!$settings['content_css'];
		if ($needs_leading_separator) {
			$settings['content_css'] .= $separator;
		}

		$settings['content_css'] .= implode($separator, $additions);

		return $settings;
	}

	public static function customise_mce_row_1( $buttons ) {
		return [
			// probably don't use this, use customise_mce_toolbars instead
		];
	}

	public static function customise_mce_row_2( $buttons ) {
		return [
			// probably don't use this, use customise_mce_toolbars instead
		];
	}

	public static function customise_mce_toolbars( $toolbars ) {
		$toolbars = [
			'Standard' => [
				1 => [
					'formatselect',
					'styleselect',
					'bold',
					'italic',
					'strikethrough',
					'forecolor',
					'removeformat',
					'link',
					'bullist',
					'numlist',
					'outdent',
					'indent',
					'alignleft',
                    'aligncenter',
                    'alignright',
					'pastetext',
					'undo',
					'redo',
				],
			],


			'Basic' => [
				1 => [
					'bold',
					'italic',
					'strikethrough',
					'forecolor',
					'removeformat',
					'link',
					'bullist',
					'numlist',
					'outdent',
					'indent',
					'pastetext',
					'undo',
					'redo',
				],
			],

			'Heading' => [
				1 => [
					// 'bold',
					'italic',
					'removeformat',
					'link',
					'pastetext',
					'undo',
					'redo',
				],
			],
		];

		return $toolbars;
	}

	public static function customise_mce_set_textcolors( $settings ) {

		$colors = [
			'000000', 'Black',
			'FFFFFF', 'White',
		];

		// Insert the array, JSON ENCODED, into 'style_formats'
		$settings['textcolor_map'] = json_encode( $colors );

		return $settings;
	}

	public static function customise_mce_insert_formats( $settings ) {

		$type_styles = [
			[
				'title' => 'H1',
				'block' => 'h2',
				'classes' => 'type-style-h1',
			],
			[
				'title' => 'H2',
				'block' => 'h2',
				'classes' => 'type-style-h2',
			],
			[
				'title' => 'H3',
				'block' => 'h2',
				'classes' => 'type-style-h3',
			],
			[
				'title' => 'H4',
				'block' => 'h2',
				'classes' => 'type-style-h4',
			],
			[
				'title' => 'Small body / H4',
				'block' => 'h2',
				'classes' => 'type-style-h4',
			],
		];

		$formats = $type_styles;

		// Insert the array, JSON ENCODED, into 'style_formats'
		$settings['style_formats'] = json_encode( $formats );

		return $settings;
	}


	public static function customise_mce_block_formats( $settings ) {

		$settings['block_formats'] = 'Heading=h2;Text=p;Blockquote=blockquote;';

		return $settings;
	}
}
