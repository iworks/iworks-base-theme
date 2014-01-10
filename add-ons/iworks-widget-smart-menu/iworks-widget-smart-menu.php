<?php

if ( !class_exists( 'iWorks_Smart_Menu_Widget' ) ) {
    include_once dirname( __FILE__ ).'/class-iworks-widget-smart-menu.php';
}

class iWorks_Smart_Menu_Widget_Loader
{
    private $nonunce_name;
    private $options;

    public static $option_name = 'iworks_smart_menu_show_in';

    public function __construct()
    {
        $this->nonunce_name = crc32( __CLASS__ );
        $this->options[ self::$option_name ] = array(
                'yes' => __( 'Yes', IWORKS_THEME_NAME ),
                'no' => __( 'No', IWORKS_THEME_NAME ),
        );

        add_action( 'add_meta_boxes', array( &$this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( &$this, 'save_post' ) );
        add_action( 'widgets_init', array( &$this, 'widgets_init' ) );
    }

    public function widgets_init()
    {
        register_widget( 'iWorks_Smart_Menu_Widget' );
    }

    public function add_meta_boxes()
    {
        add_meta_box(
            'smart_menu',
            __( 'Show in Smart Menu', IWORKS_THEME_NAME ),
            array( &$this, 'inner' ),
            'page',
            'side'
        );
    }

    public function inner($post)
    {
        wp_nonce_field( plugin_basename( __FILE__ ), $this->nonunce_name );
        $current_value = $this->sanitize( get_post_meta( $post->ID, self::$option_name, true ), self::$option_name );
        echo '<ul>';
        foreach( $this->options[ self::$option_name ] as $key => $value ) {
            $id = sprintf( '%s_%s', self::$option_name, $key );
            echo '<li>';
            printf(
                '<input type="radio" name="%s" value="%s" id="%s" %s />',
                self::$option_name,
                $key,
                $id,
                checked( $key, $current_value, false )
            );
            printf(
                ' <label for="%s">%s</label>',
                $id,
                $value
            );

            echo '</li>';
        }
        echo '</ul>';
    }

    private function sanitize($value, $option_name)
    {
        if ( !empty( $value ) && is_string( $value ) && array_key_exists( $value, $this->options[ $option_name ] ) ) {
            return $value;
        }
        $keys = array_keys( $this->options[ $option_name ] );
        return $keys[0];
    }

    public function save_post($post_id)
    {

        // First we need to check if the current user is authorised to do this action.
        if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) )
                return;
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) )
                return;
        }

        // Secondly we need to check if the user intended to change this value.
        if ( ! isset( $_POST[$this->nonunce_name] ) || ! wp_verify_nonce( $_POST[$this->nonunce_name], plugin_basename( __FILE__ ) ) ) {
            return;
        }

        //if saving in a custom table, get post_ID
        $post_ID = $_POST['post_ID'];

        /**
         * save or update
         */
        $mydata = $this->sanitize( $_POST[self::$option_name ], self::$option_name );
        add_post_meta( $post_ID, self::$option_name, $mydata, true ) or update_post_meta( $post_ID, self::$option_name, $mydata );
    }
}

new iWorks_Smart_Menu_Widget_Loader();
