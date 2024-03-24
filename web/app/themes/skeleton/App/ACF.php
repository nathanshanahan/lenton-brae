<?php

namespace App;

class ACF {

	public function __construct() {
        // Only allow fields to be edited on development
        if (WP_ENV !== 'development') {
            add_filter( 'acf/settings/show_admin', '__return_false' );
        }
	}
}
