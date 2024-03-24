<?php

namespace App\Bone;

class Instagram
{
	//When to update
	private static $updateInMinutes = 30;

	//Number of images to return
	private static $limit = 4;


	private static function getApiKey() {
		$platform_data = \App\SiteOptions::socialPlatforms('instagram');
		$token = $platform_data['api_token'] ?? '';
		return $token;
	}

	/**
	 * Refreshes the Instagram token - should be done within 60 days
	 */
	public static function refreshToken()
	{
		$token = self::getApiKey();
		if (!$token) {
			// should do something here
			return;
		}

		$url = 'https://instagram.bone.digital/auth/?refresh_token=' . $token;
		$response = wp_remote_get( $url );
		if ( is_wp_error( $response ) )
		{
			set_transient('refresh_token', true, DAY_IN_SECONDS * 60 );
		}
	}

	/**
	 * Gets the images from the WP transient
	 */
	public static function getImages()
	{
		$feed = get_transient('instagram_feed');
		$refresh = get_transient('refresh_token');
		if( false === $refresh )
		{
			//Need to refresh the token
			self::refreshtoken();
		}

		//Set this to false to force an update
		$feed = get_transient('instagram_feed');

		if( false === $feed )
		{
			$feed = self::getInstagramFeed();
			set_transient('instagram_feed', $feed, MINUTE_IN_SECONDS * self::$updateInMinutes);
		}
		return $feed;
	}

	/**
	 * Pulls the images from the Instagram API
	 */
	private static function getInstagramFeed()
	{
		$token = self::getApiKey();
		if(empty($token))
		{
			//No key exists
			return false;
		}
		$instagram_contents = file_get_contents('https://graph.instagram.com/me/media/?fields=media_url,permalink,media_type,thumbnail_url&access_token=' . $token . '&amp;count=' . self::$limit);

		if( !$instagram_contents )
		{
			return false;
		}

		$media = json_decode($instagram_contents);
		$items = $media->data;
		$counter = 0;
		$feed = [];
		foreach( $items as $item )
		{
			$counter++;

			$image_url = $item->media_url;
			if( $item->media_type == 'VIDEO' )
			{
				$image_url = $item->thumbnail_url;
			}

			//Add image to feed
			$feed[] = [
				'link_url' => $item->permalink,
				'image_url' => $image_url,
			];

			if($counter >= self::$limit)
			{
				break;
			}
		}

		return $feed;
	}

}
