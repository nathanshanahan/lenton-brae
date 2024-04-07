<?php
/**
 * Configuration overrides for WP_ENV === 'staging'
 */

use Roots\WPConfig\Config;
use function Env\env;

/**
 * You should try to keep staging as close to production as possible. However,
 * should you need to, you can always override production configuration values
 * with `Config::define`.
 *
 * Example: `Config::define('WP_DEBUG', true);`
 * Example: `Config::define('DISALLOW_FILE_MODS', false);`
 */

Config::define('DISALLOW_INDEXING', true);

Config::define('DEV_URL', env('DEV_URL') ?? '');

// Support Wordfence env disabling, default to enabled
Config::define('WFWAF_ENABLED', env('WFWAF_ENABLED') ?? true);
