<?php

namespace App\PostTypes;

use App\Bone\PostTypeUtils;
use App\REST;
use \WP_REST_Request;
use \WP_REST_Response;

class Posts
{
	const HANDLE = 'post';
	const ARCHIVE_PER_PAGE = 12;

	private static $setup_complete = false;

	public function __construct() {
		// Empty.
	}

	public static function setup() : void {
		if (self::$setup_complete) {
			return;
		}

		self::registerEndpoints();
		self::$setup_complete = true;
	}

	private static function registerEndpoints() {
		$api_base = REST::API_BASE . '/posts';

		add_action( 'rest_api_init', function () {
			register_rest_route( $api_base, '/archive', [
				'methods' => 'GET',
				'callback' => [self::class, 'handleArchiveQuery'],
				'permission_callback' => '__return_true',
			]);
		});
	}

	public static function getArchivePosts(
		int|string $page = 1,
		int|string $per_page = self::ARCHIVE_PER_PAGE,
		array $args = []
	) : array {

		$core = [
			'post_type' => 'post',
			'paged' => $page,
			'posts_per_page' => $per_page,
		];

		$merged_args = wp_parse_args($args, $core);
		$query = new \WP_Query($merged_args);

		return [
			'posts' => $query->posts,
			'max_pages' => $query->max_num_pages,
			'total_posts' => $query->found_posts,
		];
	}

	public static function handleArchiveQuery(WP_REST_Request $req) : WP_REST_Response {
		$params = $req->get_query_params();
		$page = !empty($params['page']) ? filter_var($params['page'], FILTER_SANITIZE_NUMBER_INT) : 1;
		$per_page = !empty($params['per_page']) ? filter_var($params['per_page'], FILTER_SANITIZE_NUMBER_INT) : self::ARCHIVE_PER_PAGE;
		$category = !empty($params['category']) ? htmlspecialchars($params['category']) : '';

		$posts = self::getArchivePosts($page, $per_page, ['category_name' => $category]);

		if (empty($posts['posts'])) {
			return new WP_REST_Response( [
				'html' => '',
				'max_pages' => $posts['max_pages'],
				'total_posts' => $posts['total_posts'],
			], 200 );
		}

		ob_start();

		foreach ($posts['posts'] as $post) {
			echo "<div>";
			echo "TODO! Posts::handleArchiveQuery";
			echo "</div>";
		}

		$html = ob_get_clean();

		$return_data = [
			'html' => $html,
			'max_pages' => $posts['max_pages'],
			'total_posts' => $posts['total_posts'],
		];

		return new WP_REST_Response( $return_data, 200 );
	}
}
