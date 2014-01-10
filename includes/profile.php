<?php

class iWorks_Profile
{
    public function __construct()
    {
        add_action( 'after_setup_theme', array( &$this, 'after_setup_theme' ) );
    }

    public function after_setup_theme()
    {
        add_filter( 'user_contactmethods', array( &$this, 'user_contactmethods' ) );
    }

    public function user_contactmethods($contactmethods)
    {
        $contactmethods['google_profile']   = __( 'Google Profile URL', IWORKS_THEME_NAME );
        $contactmethods['twitter_profile']  = __( 'Twitter Profile URL', IWORKS_THEME_NAME );
        $contactmethods['facebook_profile'] = __( 'Facebook Profile URL', IWORKS_THEME_NAME );
        $contactmethods['linkedin_profile'] = __( 'LinkedIn Profile URL', IWORKS_THEME_NAME );
        return $contactmethods;
    }
}

new iWorks_Profile();
