<?php

namespace App\Bone;

use \App\Bone\Manifest;

class ViteAssets
{
	private Manifest $vm;
	const VITE_HMR_CLIENT_HANDLE = 'vite-client';
	public static $server_host = '';

	public function __construct(string $basePath = "", string $manifestFile = "", string $algorithm = "sha256")
	{
		self::$server_host = self::serverHost();

		// assume defaults
		if (!$basePath) {
			$basePath = get_stylesheet_directory_uri();
		}

		// assume defaults
		if (!$manifestFile) {
			$manifestFile = self::getManifestPath();
		}

		// Let ViteManifest handle errors
		$this->vm = new \App\Bone\Manifest($manifestFile, $basePath, $algorithm);

		// if dev server is set and available to this server, enqueue the vite client script
		if (self::$server_host) {
			if (self::isViteAvailableFromWP()) {
				add_action('wp_enqueue_scripts', [$this, 'enqueueClientScript']);
			}
			else if (self::isNonLocalServer()) {
				// TODO: enqueue custom script to check for the dev server on the frontend
			}
		}
	}

	public static function getManifestPath(): string {
		$baseUrl = get_stylesheet_directory_uri();
		$path = get_stylesheet_directory() . "/dist/manifest.json";
		return $path;
	}

	public static function serverHost(): string {
		return defined('DEV_URL') ? DEV_URL : '';
	}

	public static function isViteAvailableFromWP(): bool {
		$server = self::serverHost();
		if (!$server) {
			return false;
		}

		// do test fetch of client script
		$vite_client_url = $server . '/@vite/client';
		$response = wp_remote_get($vite_client_url);
		$status = wp_remote_retrieve_response_code($response);

		return $status === 200;
	}

	public static function isNonLocalServer(): bool {
		return !empty(self::serverHost()) && WP_ENV !== 'development';
	}

	public static function enqueueClientScript() {
		wp_enqueue_script(self::VITE_HMR_CLIENT_HANDLE, self::serverHost() . '/@vite/client', array(), null);
		self::scriptAsModule(self::VITE_HMR_CLIENT_HANDLE);
	}

	public function get(string $entrypoint): array | null {
		$manifest = $this->vm->getManifest();
		$raw_entry = $manifest[$entrypoint] ?? null;

		if (empty($raw_entry)) {
			return null;
		}

		$computed = $this->vm->getEntrypoint($entrypoint, true);

		$entry['url'] = $computed['url'];
		$entry['hash'] = $computed['hash'];
		$entry['source'] = $computed['source'];

		if (str_ends_with($computed['url'], '.css')) {
			$entry['hmr'] = self::$server_host . '/src/scss/' . $entry['source'];
		}
		else if (str_ends_with($computed['url'], '.js')) {
			$entry['hmr'] = self::$server_host . '/src/js/' . $entry['source'];
		}
		else {
			$entry['hmr'] = $computed['url'];
		}

		return $entry;
	}


	public function uri(string $entrypoint): string {
		$entry = $this->get($entrypoint);
		if (empty($entry)) {
			return '';
		}

		if (self::isViteAvailableFromWP()) {
			return $entry['hmr'];
		}

		return $entry['url'];
	}


	private static function scriptAsModule(string $script_handle) {
		add_filter(
			'script_loader_tag', function ($tag, $handle, $src) use ($script_handle) {
				if ($script_handle === $handle ) {
					return sprintf(
						'<script type="module" src="%s"></script>',
						esc_url($src)
					);
				}
				return $tag;
			}, 10, 3
		);
	}
}
