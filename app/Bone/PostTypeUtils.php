<?php

namespace App\Bone;

use App;

class PostTypeUtils
{
	/**
	 *
	 * @param string $singular
	 * @param string $plural
	 * @param string $domain
	 *
	 * @return array<string>
	 */
	public static function makePostTypeLabels( string $singular, string $plural, string $domain = 'default' ) {
		// $domain = $domain;

		$labels = array(
			'name'                  => _x( $plural, 'Post Type General Name', $domain ),
			'singular_name'         => _x( $singular, 'Post Type Singular Name', $domain ),
			'add_new'               => __( "Add New", $domain ),
			'add_new_item'          => __( "Add New $singular", $domain ),
			'edit_item'             => __( "Edit $singular", $domain ),
			'new_item'              => __( "New $singular", $domain ),
			'update_item'           => __( "Update $singular", $domain ),
			'view_item'             => __( "View $singular", $domain ),
			'view_items'            => __( "View $plural", $domain ),
			'search_items'          => __( "Search $plural", $domain ),
			'not_found'             => __( "Not found", $domain ),
			'not_found_in_trash'    => __( "No $plural found in Trash", $domain ),
			'parent_item_colon'     => __( "Parent $singular:", $domain ),
			'all_items'             => __( "All $plural", $domain ),
			'archives'              => __( "$singular Archives", $domain ),
			'attributes'            => __( "$singular Attributes", $domain ),
			'insert_into_item'      => __( "Insert into this $singular", $domain ),
			'uploaded_to_this_item' => __( "Uploaded to this $singular", $domain ),
			'featured_image'        => __( "Featured Image", $domain ),
			'set_featured_image'    => __( "Set featured image", $domain ),
			'remove_featured_image' => __( "Remove featured image", $domain ),
			'use_featured_image'    => __( "Use as featured image", $domain ),
			'menu_name'             => __( $plural, $domain ),
			'name_admin_bar'        => __( "Post Type", $domain ),

			/* Not sure exactly how these work */
			// 'filter_items_list'     => __( "", $domain ),
			// 'items_list_navigation' => __( "", $domain ),
			// 'items_list'            => __( "", $domain ),
			// 'name_admin_bar'        => __( "", $domain ),
		);

		return $labels;
	}


	/**
	 *
	 * @param string $singular
	 * @param string $plural
	 * @param string $domain
	 *
	 * @return array<string>
	 */
	public static function makeTaxonomyLabels( string $singular, string $plural, string $domain = 'default' ) {

		$labels = array(
			'name'                  => _x( $plural, 'Post Type General Name', $domain ),
			'singular_name'         => _x( $singular, 'Post Type Singular Name', $domain ),
			'menu_name'             => __( $plural, $domain ),
			'all_items'             => __( "All $plural", $domain ),
			'edit_item'             => __( "Edit $singular", $domain ),
			'view_item'             => __( "View $singular", $domain ),
			'update_item'           => __( "Update $singular", $domain ),
			'add_new_item'          => __( "Add New $singular", $domain ),
			'new_item_name'         => __( "New $singular Name", $domain ),
			'parent_item'           => __( "Parent $singular", $domain ),
			'parent_item_colon'     => __( "Parent $singular:", $domain ),
			'search_items'          => __( "Search $plural", $domain ),
			'popular_items'         => __( "Popular $plural", $domain ),
			'separate_items_with_commas'  => __( "Separate $plural with commas", $domain ),
			'add_or_remove_items'   => __( "Add or remove $plural", $domain ),
			'choose_from_most_used' => __( "Choose from the most used $plural", $domain ),
			'back_to_items'         => __( "Not found", $domain ),
			'not_found'             => __( "← Back to $plural", $domain ),

			// 'name_admin_bar'        => __( "Post Type", $domain ),
			// 'archives'              => __( "$singular Archives", $domain ),
			// 'attributes'            => __( "$singular Attributes", $domain ),
			// 'add_new'               => __( "Add New", $domain ),
			// 'new_item'              => __( "New $singular", $domain ),
			// // 'view_items'            => __( "View $plural", $domain ),
		);

		return $labels;
	}
}
