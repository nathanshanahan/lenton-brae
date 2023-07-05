<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Roots\Acorn\Sage\SageServiceProvider;

class ACFServiceProvider extends SageServiceProvider
{
    /**
     * Register theme services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        add_action('init', [$this, 'registerSiteOptionsPage']);

        // Only allow fields to be edited on development
        if (env('WP_ENV') !== 'development')
        {
            add_filter( 'acf/settings/show_admin', '__return_false' );
        }
    }

    /**
     *
     * @return void
     */
    public function registerSiteOptionsPage()
    {
        if( function_exists('acf_add_options_page') )
        {
            acf_add_options_page( array(
                'page_title' => 'Site Options',
                'post_id' => 'options',
            ));
        }
    }
}
