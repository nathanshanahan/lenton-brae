<?php

namespace App\Bone;

class CookieNotice
{
	public function __construct()
	{
		add_action('wp_footer', [__CLASS__, 'displayNotice']);
	}

	public static function displayNotice()
	{
		if( !function_exists('get_field') || isset($_COOKIE['cookie-notice']) )
		{
			//ACF not enabled or cookie notice popup already exists
			return;
		}

		$display = get_field('enable_cookie_notice_popup', 'options');
		if( $display )
		{
			$cookie_message = get_field('cookie_notice_text', 'options');
			$button_text = get_field('cookie_notice_button_text', 'options');
			$read_more_field = get_field('cookie_notice_read_more', 'options');
			$read_more_link = '';
			$read_more_text = '';
			if( !empty($read_more_field) && is_array($read_more_field) )
			{
				$read_more_link = $read_more_field['url'];
				$read_more_text = $read_more_field['title'];
			}

			echo '<div class="cookie-notice">';
			echo $cookie_message;
			echo '<button id="button--accept-cookies" class="button button--accept-cookies">' . $button_text . '</button>';
			if( !empty($read_more_link) )
			{
				echo '<a target="_blank" class="cookie-notice__read-more" href="' . $read_more_link . '" title="View Cookie Policy">';
				echo $read_more_text;
				echo '</a>';
			}
			echo '</div>';
		}
	}
}
