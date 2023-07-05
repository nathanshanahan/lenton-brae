<?php

namespace App\Bone\Theme;

class Emails
{
	/**
	 * Emails constructor.
	 */
	public function __construct()
	{
		//Disable Password Reset Notifications
		add_filter( 'send_password_change_email', [$this, 'SendPasswordChangeEmail'] );
		add_filter( 'send_password_change_email', [$this, 'SendPasswordChangeEmail'] );

		//Change From Name and Email
		add_filter( 'wp_mail_from', [$this, 'ChangeSenderEmail'] );
		add_filter( 'wp_mail_from_name', [$this, 'ChangeSenderName'] );
	}

	public function ChangeSenderEmail( $email )
	{
		if( function_exists('get_field') )
		{
			$new_email = get_field('default_from_email_address', 'options' );
			if( !empty($new_email) )
			{
				$email = $new_email;
			}
		}
		return $email;
	}

	public function ChangeSenderName( $name )
	{
		if( function_exists('get_field') )
		{
			$new_name = get_field('default_from_email_name', 'options' );
			if( !empty($new_name) )
			{
				$name = $new_name;
			}
		}
		return $name;
	}


	/**
	 * Determines whether or not to send a confirmation of password email change
	 *
	 * @param $send
	 * @return bool
	 */
	public function SendPasswordChangeEmail($send)
	{
		if( function_exists( 'get_field' ) )
		{
			if( get_field('disable_send_password_change_notifications', 'options') )
			{
				$send = false;
			}
		}

		return $send;
	}
}
