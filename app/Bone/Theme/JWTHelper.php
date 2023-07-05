<?php

namespace App\Bone\Theme;

class JWTHelper
{
	public function __construct() {
		$this->setup_filters();
	}

	private function setup_filters() {
		/**
		 * This is important! This makes the algorithm used within the plugin
		 * consistent with our usage, which is necessary in order to successfully
		 * decode any manually-generated tokens (e.g. in Headless.php)
		 */
		add_filter( 'jwt_auth_algorithm', fn($algo) => self::getAlgorithm() );
	}

	/**
	 *
	 * @param WP_User|null user
	 * @param int|null $ttl
	 * @param string|null $secret
	 * @return array | false
	 */
	public static function createToken($user = null, $ttl = null, $secret = null) {
		$tokenData = JWTHelper::createTokenData($user, $ttl);
		$token = JWTHelper::encodeToken($tokenData, $secret);

		return $token;
	}


	/**
	 *
	 * @param WP_User|null user
	 * @param int|null $ttl
	 * @return array | false
	 */
	public static function createTokenData($user = null, $ttl = null) {
		if (empty($user) || !is_a($user, 'WP_User')) {
			$user = wp_get_current_user();
		}

		if ($user->ID === 0) {
			return false;
		}

		if (empty($ttl)) {
			$ttl = DAY_IN_SECONDS * 7;
		}

		$issuedAt = time();
		$notBefore = apply_filters('jwt_auth_not_before', $issuedAt, $issuedAt);
		$expire = apply_filters('jwt_auth_expire', $issuedAt + $ttl, $issuedAt);

		$token = [
			'iss' => get_bloginfo('url'),
			'iat' => $issuedAt,
			'nbf' => $notBefore,
			'exp' => $expire,
			'data' => [
				'user' => [
					'id' => $user->ID,
				],
			],
		];

		return $token;
	}

	/**
	 *
	 * @param array $token
	 * @param string|null $secret
	 * @return string
	 */
	public static function encodeToken($tokenData, $secret = null) {
		if ( !class_exists('\Firebase\JWT\JWT') ) {
			return '';
		}

		if ( empty($secret) ) {
			$secret = defined('JWT_AUTH_SECRET_KEY') ? JWT_AUTH_SECRET_KEY : false;
		}

		/**
		 * The signature for this function changed ~Oct 2022 to require an additional
		 * argument. If you are using _older_ versions of the plugin use the function call
		 * in this comment instead.
		 *
		 *
		 * $token = \Firebase\JWT\JWT::encode(apply_filters('jwt_auth_token_before_sign', $token, $user), $secret_key);
		 */
		$encoded_token = \Firebase\JWT\JWT::encode(
			$tokenData,
			$secret,
			self::getAlgorithm()
		);

		return $encoded_token;
	}

	public static function getAlgorithm() {
		return 'HS512';

		/**
		 * Algos supported by the plug / JWT. See plugin file class-jwt-auth-public.php
		 *
		 * $supported_algos = [
		 * 	'HS256', // plugin default
		 * 	'HS384',
		 * 	'HS512',
		 * 	'RS256',
		 * 	'RS384',
		 * 	'RS512',
		 * 	'ES256',
		 * 	'ES384',
		 * 	'ES512',
		 * 	'PS256',
		 * 	'PS384',
		 * 	'PS512'
		 * ];
		 */
	}
}
