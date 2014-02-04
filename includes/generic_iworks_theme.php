<?php

include_once dirname( __FILE__ ) .'/pluggable.php';

if ( ! isset( $content_width ) ) {
    $content_width = 900;
}

add_action('wp_dashboard_setup', 'iworks_theme_action_wp_dashboard_setup' );
// Add a widget in WordPress Dashboard
if ( !function_exists( 'iworks_theme_action_wp_dashboard_setup_content' ) ) {
    function iworks_theme_action_wp_dashboard_setup_content()
    {
        // Entering the text between the quotes
        echo '<ul>
            <li>Release Date: %DATE%</li>
            <li>Author: <a href="http://iworks.pl/?utm_source=wordpress&utm_medium=theme&utm_campaign='.IWORKS_THEME_NAME.'">iWorks Marcin Pietrzak</a>.</li>
            </ul>';
    }
}
if ( !function_exists( 'iworks_theme_action_wp_dashboard_setup' ) ) {
    function iworks_theme_action_wp_dashboard_setup()
    {
        wp_add_dashboard_widget('wp_dashboard_widget', __( 'Technical information', IWORKS_THEME_NAME ), 'iworks_theme_action_wp_dashboard_setup_content');
    }
}

class Generic_iWorks_Theme_Class extends iWorks_Theme_Class
{
    public function __construct()
    {
        parent::__construct();
    }
}

