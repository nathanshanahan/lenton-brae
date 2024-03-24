<?php

namespace App\Bone;

use App\Bone\JWTHelper;

class Headless
{
	/**
	 * Headless constructor.
	 */
	public function __construct()
	{
		if(!defined('BONE_HEADLESS') || ! BONE_HEADLESS)
		{
			//Not in headless mode
			return;
		}

		/**
		 * Setup the preview link to the frontend
		 */
		add_filter( 'preview_post_link', [$this, 'HandlePreviewLink'], 10, 2 );

		/**
		 * Redirect the front end
		 */
		add_action( 'get_header', function() {
			wp_redirect(BONE_HEADLESS_FRONTEND_URL);
			die();
		}, 10 );

		/**
		 * Extend the /search rest api endpoint
		 */
		add_action( 'rest_api_init', function () {
			add_filter( 'rest_post_dispatch', [$this, 'ExtendRestAPISearch'], 10, 3 );
		} );

		/**
		 * Add Breadcrumbs HTML to the Rest API if available
		 */
		add_action( 'rest_api_init', function () {
			register_rest_route( 'bone/v1', '/breadcrumbs/', array(
				'methods' => 'GET',
				'callback' => [$this, 'GetBreadcrumbsHtml'],
			) );
		} );

		add_action( 'get_header', [$this, 'GetBreadCrumbsFrontEnd'], 5);
	}

	/**
	 * Outputs the breadcrumb and kills the page, allows for retrieving breadcrumbs
	 */
	public function GetBreadCrumbsFrontEnd()
	{
		if( !isset($_GET['bone_get_breadcrumbs']) )
		{
			return;
		}

		if( function_exists('rank_math_get_breadcrumbs') )
		{
			echo rank_math_get_breadcrumbs();
		}
		die();
	}

	/**
	 * Returns the html of the bradcrumbs from RankMath
	 * @param $object
	 * @return string HTML of breadcrumbs
	 */
	public static function GetBreadcrumbsHtml(\WP_REST_Request $request)
	{
		$url = $request->get_param('url');
		$breadcrumbs = '';

		if( !$url )
		{
			//Check if the url doesn't exist OR the url doesn't start with this website url
			return '';
		}

		//Clean the URL - ensures no cross site exploits
		$url = substr($url, strlen(get_home_url()), strlen($url));
		$url = get_home_url() . $url;

		if( function_exists('rank_math_get_breadcrumbs'))
		{
			$url .= '/?bone_get_breadcrumbs';
			$url = str_replace('//?', '/?', $url);
			$data = wp_remote_get($url);
			$response = wp_remote_retrieve_body($data);

			//Clean the URLs, replacing the front end WP url with the frontend headless URL
			if( BONE_HEADLESS_FRONTEND_URL )
			{
				$response = str_replace(get_home_url(), BONE_HEADLESS_FRONTEND_URL, $response);
			}
			$breadcrumbs = $response;
		}
		return $breadcrumbs;
	}

	/**
	 * Changes the preview link in the WP admin for headless setups
	 *
	 * @param $link
	 * @param $post
	 * @return string
	 */
	public function HandlePreviewLink($link, $post)
	{
		$revisions = wp_get_post_revisions(get_the_ID());
		$revision_id = false;
		if( is_array($revisions) && !empty($revisions) )
		{
			foreach($revisions as $key => $revision)
			{
				$revision_id = $revision->ID;
				break;
			}
		}

		//Generate an auth token, otherwise fallback to nonce
		$user = wp_get_current_user();
		$token = JWTHelper::createToken($user);

		// Change the URL
		return BONE_HEADLESS_FRONTEND_URL
			. '/api/preview/?id=' . get_the_ID()
			. '&post_type=' . $post->post_type
			. '&preview_id=' . $revision_id
			. '&token=' . $token
			. '&slug=' . $post->post_name;
	}

	/**
	 * Adds the slug and excerpt to the RestAPI /search endpoint
	 * @param $response
	 * @param $server
	 * @param $request
	 * @return mixed
	 */
	public function ExtendRestAPISearch($response, $server, $request) {
		if ($request->get_route() !== '/wp/v2/search')
		{
			return $response;
		}
		if (is_array($response->data))
		{
			foreach ($response->data as &$post)
			{
				if (!is_array($post))
				{
					continue;
				}
				$post_id = $post['id'];
				$full_post = get_post($post_id);
				if ($full_post)
				{
					$excerpt = get_the_excerpt($post_id);
					$slug = $full_post->post_name;
					$post['excerpt'] = $excerpt;
					$post['slug'] = $slug;
				}
			}
			unset($post);
		}
		return $response;
	}
}
