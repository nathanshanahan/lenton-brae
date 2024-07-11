<?php

namespace App\PostTypes;

use App\Bone\PostTypeUtils;
use App\REST;
use \WP_REST_Request;
use \WP_REST_Response;

class Events
{
	const HANDLE = 'event';
	const ARCHIVE_PER_PAGE = 12;
	const API_BASE = REST::API_BASE . '/events';

	private static $setup_complete = false;

	public function __construct()
	{
		add_action('init', [self::class, 'registerCPT']);
		add_action('init', [self::class, 'registerTaxonomy']);
		self::setup();
	}

	public static function setup(): void
	{
		if (self::$setup_complete) {
			return;
		}

		self::registerEndpoints();
		self::$setup_complete = true;
	}

	public static function registerCPT()
	{
		$labels = array(
			'name'                  => _x('Events', 'Post Type General Name', 'text_domain'),
			'singular_name'         => _x('Event', 'Post Type Singular Name', 'text_domain'),
			'menu_name'             => __('Events', 'text_domain'),
			'name_admin_bar'        => __('Event', 'text_domain'),
			'archives'              => __('Event Archives', 'text_domain'),
			'attributes'            => __('Event Attributes', 'text_domain'),
			'parent_item_colon'     => __('Parent Events:', 'text_domain'),
			'all_items'             => __('All Events', 'text_domain'),
			'add_new_item'          => __('Add New Event', 'text_domain'),
			'add_new'               => __('Add New Event', 'text_domain'),
			'new_item'              => __('New Event', 'text_domain'),
			'edit_item'             => __('Edit Event', 'text_domain'),
			'update_item'           => __('Update Event', 'text_domain'),
			'view_item'             => __('View Event', 'text_domain'),
			'view_items'            => __('View Events', 'text_domain'),
			'search_items'          => __('Search Events', 'text_domain'),
			'not_found'             => __('Not found', 'text_domain'),
			'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
			'featured_image'        => __('Featured Image', 'text_domain'),
			'set_featured_image'    => __('Set featured image', 'text_domain'),
			'remove_featured_image' => __('Remove featured image', 'text_domain'),
			'use_featured_image'    => __('Use as featured image', 'text_domain'),
			'insert_into_item'      => __('Insert into event', 'text_domain'),
			'uploaded_to_this_item' => __('Uploaded to this event', 'text_domain'),
			'items_list'            => __('Events list', 'text_domain'),
			'items_list_navigation' => __('Events list navigation', 'text_domain'),
			'filter_items_list'     => __('Filter events list', 'text_domain'),
		);
		$args = array(
			'label'                 => __('event', 'text_domain'),
			'description'           => __('', 'text_domain'),
			'labels'                => $labels,
			'supports'              => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
		);
		register_post_type(self::HANDLE, $args);
	}

	// Register Custom Taxonomy
	public static function registerTaxonomy()
	{
		$labels = array(
			'name'                       => _x('Event Categories', 'Taxonomy General Name', 'text_domain'),
			'singular_name'              => _x('Event Category', 'Taxonomy Singular Name', 'text_domain'),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
		);
		register_taxonomy('event_cat', array('event'), $args);
	}

	private static function registerEndpoints()
	{
		add_action('rest_api_init', function () {
			register_rest_route(self::API_BASE, '/archive', [
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
	): array {

		$core = [
			'post_status'	=> 'publish',
			'post_type' => 'event',
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
}
