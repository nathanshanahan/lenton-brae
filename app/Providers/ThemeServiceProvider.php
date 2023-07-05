<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Roots\Acorn\Sage\SageServiceProvider;
use App\Bone\Theme\CookieNotice;
use App\Bone\Theme\Maintenance;
use App\Bone\Theme\Security;
use App\Bone\Theme\Theme;
use App\Bone\Theme\Emails;
use App\Bone\Theme\Headless;
use App\Bone\Theme\JWTHelper;

class ThemeServiceProvider extends SageServiceProvider
{
    /**
     * Register theme services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        /**
         * Register theme support and navigation menus from the theme config.
         *
         * @return void
         */
        add_action('after_setup_theme', function (): void {
            Collection::make(config('theme.support'))
                ->map(fn ($params, $feature) => is_array($params) ? [$feature, $params] : [$params])
                ->each(fn ($params) => add_theme_support(...$params));

            Collection::make(config('theme.remove'))
                ->map(fn ($entry) => is_string($entry) ? [$entry] : $entry)
                ->each(fn ($params) => remove_theme_support(...$params));

            register_nav_menus(config('theme.menus'));

            Collection::make(config('theme.image_sizes'))
                ->each(fn ($params, $name) => add_image_size($name, ...$params));
        }, 20);

        /**
         * Register sidebars from the theme config.
         *
         * @return void
         */
        add_action('widgets_init', function (): void {
            Collection::make(config('theme.sidebar.register'))
                ->map(fn ($instance) => register_sidebar(
                    array_merge(config('theme.sidebar.config'), $instance)
                ));
        });


		/**
		 * Remove the global styles added by the theme.json file
		 */
		add_action( 'wp_enqueue_scripts', function() : void {
			wp_dequeue_style('global-styles');
		}, 100);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->initBoneHelperClasses();
		$this->legacyThemeSetups();
    }

    /**
     *
     */
    public function initBoneHelperClasses() {
        new Theme();
        new Security();
        new Emails();
        new Maintenance();
        new CookieNotice();
        new Headless();
        new JWTHelper();
    }

	/**
	 * A collection of hooks and filters from the Sage 9 theme that are likely still
	 * needed but haven't yet been converted to Sage 10.
	 *
	 * TODO - find a proper home for all of this functionality
	 */
	public function legacyThemeSetups() {

		/**
		 * Disable the block editor
		 */
		add_filter('use_block_editor_for_post', '__return_false');

		/**
		 * Adds a funciton missing for Captcha with Swup
		 */
		add_action('wp_head', function() {
			?>
				<script>
					function onloadCallback()
					{
						renderRecaptcha();
					}
				</script>
			<?php
		});

		/**
		 * Disables GravityForms Styling
		 */
		add_filter( 'gform_disable_form_legacy_css', '__return_true' );
		add_filter( 'gform_disable_form_theme_css', '__return_true' );
		add_filter( 'gform_init_scripts_footer', '__return_true' );

		/**
		 * Adds all Gravity Form scripts to all pages - needed for swup
		 */
		add_action('wp_head', function () {
			if( !class_exists('GFAPI') )
			{
				return;
			}

			// Add gravity scripts on load to accomodate swup
			$forms = \GFAPI::get_forms();

			foreach ($forms as $form)
			{
				gravity_form_enqueue_scripts( $form['id'], true );
			}
		});

		/**
		 * Used with the above to force JS output on pages
		 */
		add_filter( 'gform_force_hooks_js_output', '__return_true' );

		/**
		 * Remove Default Image Sizes
		 */
		add_filter('intermediate_image_sizes_advanced', function ( $sizes ) {
						// Keep the thumbnail size for the media library
			// unset( $sizes['thumbnail']);
			unset( $sizes['medium']);
			unset( $sizes['medium_large']);
			unset( $sizes['large']);

			return $sizes;
		});

		/**
		 * Provide the editor with access to manage the menus
		 */
		add_action( 'after_switch_theme', function() {
			$role = get_role('editor');
			$role->add_cap( 'gform_full_access' );
			$role->add_cap( 'edit_theme_options' );
			$role->add_cap( 'manage_options' );
		});

		/**
		 * Disables the password changed email to the administrator
		 */
		add_action('acf/init', function() {
			if( get_field('disable_send_password_change_notifications', 'options') )
			{
				if( !function_exists( 'wp_password_change_notification' ) )
				{
					function wp_password_change_notification($user)
					{
						return;
					}
				}
			}
		});
	}
}
