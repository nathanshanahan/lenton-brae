<?php

namespace App\Bone;

class Maintenance
{
    public function __construct()
    {
        add_action('get_header', array($this, 'check_maintenance_mode'), 1000);
    }

    /**
     * Checks if the site is in maintenance mode, if so, display the message and stop the site running
     */
    public function check_maintenance_mode()
    {
        $maintenance_mode = get_field('enable_maintenance_mode', 'options');
        if( false == $maintenance_mode || ( is_user_logged_in() && is_super_admin() ) )
        {
            //Maintenance mode not on or super admin
            return;
        }

        $message = get_field('maintenance_message', 'options');
        if(empty($message))
        {
            $message = "We're currently performing maintenance, please check back shortly.";
        }

        echo $message;
        die();
    }
}
