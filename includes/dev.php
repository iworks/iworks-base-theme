<?php
if ( !defined( 'IWORKS_DEV_MODE' ) or !IWORKS_DEV_MODE ) {
    return;
}

if ( !function_exists( 'd' ) ) {
    function d($array, $params = null)
    {
        $www = isset ( $_SERVER['HTTP_HOST'] );
        if ($www) {
            print '<hr /><pre>';
        }
        print_r( $array );
        if ( isset( $params ) and count ( $params ) ) {
            foreach ( $params as $one ) {
                if ( preg_match ( '/^\d+$/', $one ) ) {
                    $array = preg_replace ( '/\?/', $one, $array, 1 );
                } else {
                    $array = preg_replace ( '/\?/', "'".$one."'", $array, 1 );
                }
            }
            print ($www)? '<hr />':"\n";
            print_r( $array );
        }
        print ($www)? '</pre><hr />':"\n\n";
    }
}

add_filter( 'stylesheet_uri', 'iworks_stylesheet_uri', 999, 2 );

if ( !function_exists( 'iworks_stylesheet_uri' ) ) {
    function iworks_stylesheet_uri($stylesheet_uri, $stylesheet_dir_uri)
    {
        if ( preg_match( '/style.css$/', $stylesheet_uri ) ) {
            $stylesheet_uri = preg_replace( '/style.css$/', 'style.dev.css', $stylesheet_uri );
            $stylesheet_uri .= '?'.md5_file( dirname( dirname( __FILE__ ) ) . '/style.dev.css' );
        }
        return $stylesheet_uri;
    }
}
