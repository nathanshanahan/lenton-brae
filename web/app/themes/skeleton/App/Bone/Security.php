<?php

namespace App\Bone;

class Security
{
    public function __construct()
    {
        //Remove WP version
        add_filter('the_generator', function() {
            return '';
        });

        //Remove RSD and WLW links
        remove_action ('wp_head', 'rsd_link');
        remove_action ('wp_head', 'wlwmanifest_link');

        //Disable XML RPC
        add_filter( 'xmlrpc_enabled', '__return_false' );

        remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
        remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
        remove_action( 'template_redirect', 'rest_output_link_header', 11 );

        //Remove WordPress Version number from assets
        add_filter( 'style_loader_src', array($this, 'RemoveWpVersion'), 9999 );
        add_filter( 'script_loader_src', array($this, 'RemoveWpVersion'), 9999 );
    }

    /**
     * Removes the WP version number form assets (JS and CSS files)
     */
    function RemoveWpVersion($src)
    {
        if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) )
        {
            $src = remove_query_arg( 'ver', $src );
        }
        return $src;
    }
}
