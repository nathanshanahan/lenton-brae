<?php

/**
 * Your base production configuration goes in this file. Environment-specific
 * overrides go in their respective config/environments/{{WP_ENV}}.php file.
 *
 * A good default policy is to deviate from the production config as little as
 * possible. Try to define as much of your configuration in this file as you
 * can.
 */

use Roots\WPConfig\Config;

use function Env\env;

// phpcs:disable

/**
 * Directory containing all of the site's files
 *
 * @var string
 */
$root_dir = dirname(__DIR__);

/**
 * Document Root
 *
 * @var string
 */
$webroot_dir = $root_dir . '/public';

/**
 * Use Dotenv to set required environment variables and load .env file in root
 * .env.local will override .env if it exists
 */
$env_files = file_exists($root_dir . '/.env.local')
    ? ['.env', '.env.local']
    : ['.env'];

$dotenv = Dotenv\Dotenv::createUnsafeImmutable($root_dir, $env_files, false);
if (file_exists($root_dir . '/.env')) {
    $dotenv->load();
    $dotenv->required(['WP_HOME', 'WP_SITEURL']);
    if (!env('DATABASE_URL')) {
        $dotenv->required(['DB_NAME', 'DB_USER', 'DB_PASSWORD']);
    }
}

/**
 * Set Acorn base path
 */
define('ACORN_BASEPATH', $root_dir);

/**
 * Set up our global environment constant and load its config first
 * Default: production
 */
define('WP_ENV', env('WP_ENV') ?: 'production');

/**
 * URLs
 */
Config::define('WP_HOME', env('WP_HOME'));
Config::define('WP_SITEURL', env('WP_SITEURL'));

/**
 * Custom Content Directory
 */
Config::define('CONTENT_DIR', '/content');
Config::define('WP_CONTENT_DIR', $webroot_dir . Config::get('CONTENT_DIR'));
Config::define('WP_CONTENT_URL', Config::get('WP_HOME') . Config::get('CONTENT_DIR'));

/**
 * DB settings
 */
Config::define('DB_NAME', env('DB_NAME'));
Config::define('DB_USER', env('DB_USER'));
Config::define('DB_PASSWORD', env('DB_PASSWORD'));
Config::define('DB_HOST', env('DB_HOST') ?: 'localhost');
Config::define('DB_CHARSET', 'utf8mb4');
Config::define('DB_COLLATE', '');
$table_prefix = env('DB_PREFIX') ?: 'wp_';

if (env('DATABASE_URL')) {
    $dsn = (object) parse_url(env('DATABASE_URL'));

    Config::define('DB_NAME', substr($dsn->path, 1));
    Config::define('DB_USER', $dsn->user);
    Config::define('DB_PASSWORD', isset($dsn->pass) ? $dsn->pass : null);
    Config::define('DB_HOST', isset($dsn->port) ? "{$dsn->host}:{$dsn->port}" : $dsn->host);
}

/**
 * Authentication Unique Keys and Salts
 */
Config::define('AUTH_KEY', env('AUTH_KEY'));
Config::define('SECURE_AUTH_KEY', env('SECURE_AUTH_KEY'));
Config::define('LOGGED_IN_KEY', env('LOGGED_IN_KEY'));
Config::define('NONCE_KEY', env('NONCE_KEY'));
Config::define('AUTH_SALT', env('AUTH_SALT'));
Config::define('SECURE_AUTH_SALT', env('SECURE_AUTH_SALT'));
Config::define('LOGGED_IN_SALT', env('LOGGED_IN_SALT'));
Config::define('NONCE_SALT', env('NONCE_SALT'));

/**
 * Custom Settings
 */
Config::define('AUTOMATIC_UPDATER_DISABLED', true);
Config::define('DISABLE_WP_CRON', env('DISABLE_WP_CRON') ?: false);

// Disable the plugin and theme file editor in the admin
Config::define('DISALLOW_FILE_EDIT', true);

// Disable plugin and theme updates and installation from the admin
Config::define('DISALLOW_FILE_MODS', true);

// Limit the number of post revisions
Config::define('WP_POST_REVISIONS', env('WP_POST_REVISIONS') ?: true);

// Set the default theme to radicle
Config::define('WP_DEFAULT_THEME', 'radicle');

// Configure stage switcher
Config::define('ENVIRONMENTS', [
    'development' => 'http://example.dev',
    'staging'     => 'http://staging.example.com',
    'production'  => 'http://example.com'
]);

/**
 * Debugging Settings
 */
Config::define('WP_DEBUG_DISPLAY', false);
Config::define('WP_DEBUG_LOG', false);
Config::define('SCRIPT_DEBUG', false);
ini_set('display_errors', '0');

/**
 * Allow WordPress to detect HTTPS when used behind a reverse proxy or a load balancer
 * See https://codex.wordpress.org/Function_Reference/is_ssl#Notes
 */
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

$env_config = __DIR__ . '/environments/' . WP_ENV . '.php';

if (file_exists($env_config)) {
    require_once $env_config;
}

Config::apply();

/**
 * Bootstrap WordPress
 */
if (!defined('ABSPATH')) {
    define('ABSPATH', $webroot_dir . '/wp/');
}


/**
 * White Label Kinsta Plugin
 */
define('KINSTAMU_WHITELABEL', true);

/**
 * Whether or not to run in headless mode
 */
$bone_headless = env('BONE_HEADLESS')? : false;
define('BONE_HEADLESS', $bone_headless);

/**
 * Front end url for headless mode
 */
$bone_headless_url = env('BONE_HEADLESS_FRONTEND_URL')? : false;
define('BONE_HEADLESS_FRONTEND_URL', $bone_headless_url);

/**
 * Custom plugin licenses
 */
//Gravity Forms
$gravity_forms_license = env('WP_PLUGIN_GF_KEY') ?: '';
if(!empty($gravity_forms_license))
{
	define('GF_LICENSE_KEY', $gravity_forms_license);
}

//Migrate DB Pro
$wpmdbpro_key = env('WP_PLUGIN_MIGRATE_DB_PRO') ?: '';
if(!empty($wpmdbpro_key))
{
	define('WPMDB_LICENCE', $wpmdbpro_key);
}

//ACF Pro Key
$acf_pro_key = env('ACF_PRO_KEY') ?: '';
if(!empty($acf_pro_key))
{
	define('ACF_PRO_LICENSE', $acf_pro_key);
}

//Smush API Key - used for Smush Pro Plugin
$wpmudev_key = env('WP_PLUGIN_WPMUDEV') ?: '';
if(!empty($wpmudev_key))
{
	define('WPMUDEV_APIKEY', $wpmudev_key);
}

//SearchWP Key
$searchwp_key = env('WP_PLUGIN_SEARCH_WP_KEY') ?: '';
if(!empty($searchwp_key))
{
	define('SEARCHWP_LICENSE_KEY', $searchwp_key);
}

//JWT Auth Secret
$jwt_auth_secret = env('JWT_AUTH_SECRET_KEY') ?: '';
if(!empty($jwt_auth_secret))
{
	define('JWT_AUTH_SECRET_KEY', $jwt_auth_secret);
}

//Bugherd
$bugherd_api_key = env('BUGHERD_API_KEY') ?: '';
$bugherd_enabled = env('BUGHERD_ENABLED') ?: false;
if(!empty($bugherd_api_key))
{
	define('BUGHERD_API_KEY', $bugherd_api_key);
}

define('BUGHERD_ENABLED', !empty($bugherd_enabled) && !empty($bugherd_api_key));


// Google Places API
$google_places_api_key = env('PLACES_API_KEY') ?: '';
if(!empty($google_places_api_key))
{
	define('PLACES_API_KEY', $google_places_api_key);
}
