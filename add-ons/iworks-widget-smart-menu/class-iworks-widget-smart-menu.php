<?php

class iWorks_Smart_Menu_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'smart_menu',
            __( 'Smart Menu', IWORKS_THEME_NAME ),
            array(
                'description' => __( 'Smart Menu', IWORKS_THEME_NAME ),
            )
        );
    }

    public function widget($args, $instance)
    {
        $content = '';
        $post_ID = 0;

        /**
         * for pages
         */
        if ( is_singular() ) {
            $post_ID = get_the_ID();
        }

        /**
         * filter handler
         */
        $post_ID = apply_filters( 'iworks_smart_menu_widget_post_id', $post_ID );

        /**
         * check doc setup
         */
        if ( 'no' == get_post_meta( $post_ID, iWorks_Smart_Menu_Widget_Loader::$option_name, true ) ) {
            return;
        }

        /**
         * check page id
         */
        if ( empty( $post_ID ) ) {
            return;
        }

        /**
         * check parent
         */
        $parent_ID = wp_get_post_parent_id( $post_ID );
        if ( empty( $parent_ID ) ) {
            $parent_ID = $post_ID;
        }

        /**
         * check doc setup
         */
        if ( 'no' == get_post_meta( $parent_ID, iWorks_Smart_Menu_Widget_Loader::$option_name, true ) ) {
            return;
        }

        /**
         * get pages
         */
        $pages = get_pages(
            array(
                'parent' => $parent_ID,
                'hierarchical' => false,
                'sort_column' => 'menu_order',
            )
        );

        foreach( $pages as $page ) {
            if ( 'no' == get_post_meta( $page->ID, iWorks_Smart_Menu_Widget_Loader::$option_name, true ) ) {
                continue;
            }
            $content .= sprintf(
                '<li%s><a href="%s">%s</a></li>',
                $page->ID == $post_ID? ' class="current-menu-item"':'',
                get_permalink( $page->ID ),
                wptexturize( $page->post_title )
            );
        }

        /**
         * produce
         */
        if ( $content ) {
            echo $args['before_widget'];
            echo '<div class="mask">';
            echo $args['before_title'];
            if ( $parent_ID == $post_ID ) {
                echo '<span class="current">';
            }
            printf(
                '<a href="%s">%s</a>',
                get_permalink( $parent_ID ),
                get_the_title( $parent_ID )
            );
            if ( $parent_ID == $post_ID ) {
                echo '</span>';
            }
            echo $args['after_title'];
            echo '</div>';
            printf( '<ul>%s</ul>', $content );
            echo $args['after_widget'];
        }
    }

    public function form($instance)
    {
    }

    public function update($new_instance, $old_instance)
    {
    }
}
