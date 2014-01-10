<?php
/**
 * lazyload
 */

class iWorks_Lazyload_Class
{
    private $version = '1.8.4';

    function __construct()
    {
        add_action( 'wp_enqueue_scripts', array( &$this, 'wp_enqueue_script' ) );
        add_filter( 'post_thumbnail_html', array( &$this, 'post_thumbnail_html' ) );
        $this->root = get_template_directory_uri();
    }

    public function wp_enqueue_script()
    {
        $filename = $this->root.'/add-ons/iworks-lazyload/jquery.lazyload.min.js';
        wp_enqueue_script(
            'lazyload',
            $filename,
            array( 'jquery' ),
            $this->version
        );
    }

    public function post_thumbnail_html($html)
    {
        $to = sprintf( 'src="%s/images/blank.png" data-original="', $this->root );
        $new_html = preg_replace( '/src="/', $to, $html );
        $new_html .= sprintf( '<noscript>%s</noscript>', $html);
        return $new_html;
    }

}
