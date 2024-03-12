<?php

namespace App;

include_once dirname(__FILE__) . "/App/Bone/ViteAssets.php";

$assets = new \App\Bone\ViteAssets();

function enqueue_scripts_styles() {

	global $assets;

	wp_enqueue_script('app-main', $assets->uri('main'), [], null);
	wp_enqueue_style('app-main-css', $assets->uri('main-css'), [], null);
}
add_action('wp_enqueue_scripts', __NAMESPACE__.'\\enqueue_scripts_styles', 20);


function enqueue_admin_scripts() {
	global $assets;

	wp_enqueue_style('app-admin-css', $assets->uri('admin-css'), [], null);
	wp_enqueue_script('app-admin', $assets->uri('admin'), [], null);
}
add_action('admin_enqueue_scripts', __NAMESPACE__.'\\enqueue_admin_scripts', 20);
