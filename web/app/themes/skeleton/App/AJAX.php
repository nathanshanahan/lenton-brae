<?php

namespace App;

class Ajax
{

	public static function load_more_posts()
	{
		$paged = $_POST['page'] + 1;
		$featured_post_ids = isset($_POST['featured_post_ids']) ? array_map('intval', $_POST['featured_post_ids']) : array();

		$args = array(
			'post_type' => 'post',
			'posts_per_page' => 9,
			'paged' => $paged,
			'orderby' => 'menu_order',
			'order' => 'DESC',
			'post__not_in' => $featured_post_ids
		);

		$query = new \WP_Query($args);

		if ($query->have_posts()) :
			while ($query->have_posts()) : $query->the_post();
				partial('partials/card-news', '', [
					'post' => get_post(),
				]);
			endwhile;
		endif;

		wp_reset_postdata();
		die();
	}

	public static function register_ajax_actions()
	{
		add_action('wp_ajax_load_more', [self::class, 'load_more_posts']);
		add_action('wp_ajax_nopriv_load_more', [self::class, 'load_more_posts']);
	}
}

// Register the AJAX actions
Ajax::register_ajax_actions();
