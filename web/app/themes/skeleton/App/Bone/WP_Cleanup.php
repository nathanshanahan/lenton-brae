<?php

namespace App\Bone;

class WP_Cleanup
{
	private $disable_comments = true;
	private $admin_branding = true;
	private $disable_emojis = true;

	/**
	 * Comments constructor.
	 */
	public function __construct()
	{
		/**
		 * Disables Comments
		 */
		if ($this->disable_comments) {
			//Redirect users accessing comments.php
			add_action('admin_init', [$this, 'AdminInit']);

			// Close comments on the front-end
			add_filter('comments_open', '__return_false', 20, 2);
			add_filter('pings_open', '__return_false', 20, 2);

			// Hide existing comments
			add_filter('comments_array', '__return_empty_array', 10, 2);

			// Remove comments page in menu
			add_action('admin_menu', function () {
				remove_menu_page('edit-comments.php');
			});

			// Remove comments links from admin bar
			add_action('init', function () {
				if (is_admin_bar_showing()) {
					remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
				}
			});
		}

		/**
		 * Force Yoast to the bottom of screen
		 */
		add_filter('wpseo_metabox_prio', function ($priority) {
			return 'low';
		});

		/**
		 * Change the Rank Math Metabox Priority
		 *
		 * @param array $priority Metabox Priority.
		 */
		add_filter('rank_math/metabox/priority', function ($priority) {
			return 'low';
		});

		/**
		 * Modify MIME types to include SVGs
		 */
		add_filter('upload_mimes', [$this, 'ModifyMimeTypes']);

		/**
		 * Loads a custom admin.css file on the login page
		 */
		if ($this->admin_branding) {
			//Add in a stylesheet to the login page
			add_action('login_enqueue_scripts', [$this, 'LoginStyleSheet']);

			//Change the footer text - 'Powered by WordPress' - done as Kinsta sometimes change it
			add_filter('admin_footer_text', [$this, 'adminFooter'], 11);

			//Add in site options logo and bg colour
			add_action('login_head', [$this, 'LoadLoginPageACFOptions']);
		}

		/**
		 * Disable Emojis
		 */
		if ($this->disable_emojis) {
			add_action('init', [$this, 'DisableEmojis']);
		}
	}

	/**
	 * Removes commens from the admin area
	 */
	public function AdminInit()
	{
		global $page_now;

		if ('edit-comments.php' == $page_now) {
			wp_redirect(admin_url());
			exit;
		}

		// Remove comments metabox from dashboard
		remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

		// Disable support for comments and trackbacks in post types
		foreach (get_post_types() as $post_type) {
			if (post_type_supports($post_type, 'comments')) {
				remove_post_type_support($post_type, 'comments');
				remove_post_type_support($post_type, 'trackbacks');
			}
		}
	}

	/**
	 * Modifies the allowed MIME types to include SVG files
	 */
	public function ModifyMimeTypes($allowed)
	{
		$allowed['svg'] = 'image/svg+xml';
		return $allowed;
	}

	/**
	 * Loads login page options from site options
	 */
	public function LoadLoginPageACFOptions()
	{
		if (!function_exists('get_field')) {
			//ACF not active
			return;
		}

		$active = get_field('use_custom_branding', 'options');
		$logo = get_field('custom_login_logo', 'options');
		$background_colour = get_field('custom_login_background', ' options');
		$image_url = '';
		if (!empty($logo) && is_array($logo)) {
			$image_url = $logo['sizes']['large'];
		}
		if (false == $active) {
			//Not set to be active in site options
			return;
		}

		//Output the background colour + logo
?>
		<style>
			<?php if (!empty($background_colour)) : ?>body {
				background: <?php echo $background_colour; ?>;
			}

			<?php endif; ?><?php if (!empty($image_url)) : ?>#login h1 a,
			.login h1 a {
				background-image: url(<?php echo $image_url; ?>);
			}

			<?php endif; ?>
		</style>
<?php
	}

	/**
	 * Loads a style sheet for the login page
	 */
	public function LoginStyleSheet()
	{
		global $assets;
		wp_enqueue_style('app-main-css', $assets->uri('main-css'), [], null);
		// wp_enqueue_style('sage/login.css', asset_path('styles/login.css'), false);
	}

	/**
	 * Changes the 'Powered by' text in the admin footer
	 * @return string
	 */
	public function adminFooter()
	{
		return 'Site powered by <a href="https://wordpress.org">WordPress</a>';
	}


	/**
	 * Disable the emoji's
	 */
	public function DisableEmojis()
	{
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('wp_print_styles', 'print_emoji_styles');
		remove_action('admin_print_styles', 'print_emoji_styles');
		remove_filter('the_content_feed', 'wp_staticize_emoji');
		remove_filter('comment_text_rss', 'wp_staticize_emoji');
		remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

		// Remove from TinyMCE
		add_filter('tiny_mce_plugins', [$this, 'DisableEmojisTinyMCE']);
	}

	/**
	 * Filter out the tinymce emoji plugin.
	 */
	public function DisableEmojisTinyMCE($plugins)
	{
		if (is_array($plugins)) {
			return array_diff($plugins, array('wpemoji'));
		} else {
			return array();
		}
	}
}
