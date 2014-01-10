<?php

/*

Copyright 2013 Marcin Pietrzak (marcin@iworks.pl)

this program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */

if ( !defined( 'WPINC' ) ) {
die;
}

if ( class_exists( 'IworksColumnsShortcode' ) ) {
    return;
}

class IworksColumnsShortcode
{

    public function __construct()
    {
        add_shortcode( 'iworks_columns', array( $this, 'shortcode' ) );
    }

    public function shortcode($atts)
    {
        $defaults = array(
            'columns' => 3,
            'rows' => 1,
            'ids' => array(),
            'thumbnail_size' => 'full',
        );
        extract( shortcode_atts( $defaults, $atts ) );
        if ( empty( $ids ) ) {
            return;
        }
        /**
         * sanitze columns
         */
        $columns = intval( $columns );
        if ( empty( $columns ) ) {
            $columns = 3;
        }
        /**
         * sanitze rows
         */
        $rows = intval( $rows );
        if ( empty( $rows ) ) {
            $rows = 1;
        }
        /**
         * sanitze ids
         */
        $ids = preg_split( '/[^\d]+/',  $ids );
        if ( empty( $ids ) ) {
            return;
        }
        /**
         * produce
         */
        $i = 0;
        $content = sprintf( '<div class="clearfix iworks_columns columns-%d">', $columns );
        foreach( $ids as $page_ID ) {
            $args = array(
                'page_id' => $page_ID,
            );
            $the_query = new WP_Query( $args );
            if ( $the_query->have_posts() ) {
                while ( $the_query->have_posts() ) {
                    $the_query->the_post();
                    $classes = array(
                        'column',
                    );
                    $classes[] = sprintf( 'column-%d', $i++%3 );
                    $content .= sprintf( '<div class="%s">', implode( ' ', get_post_class( $classes ) ) );
                    $content .= sprintf(
                        '<h2 class="entry-title"><a href="%s">%s</a></h2>',
                        get_permalink( $page_ID ),
                        get_the_title()
                    );
                    if ( has_post_thumbnail( $page_ID ) ) {
                        $content .= sprintf(
                            '<a href="%s">%s</a>',
                            get_permalink( $page_ID ),
                            get_the_post_thumbnail( $page_ID, $thumbnail_size )
                        );
                    }
                    $content .= sprintf(
                        '<p class="excerpt">%s %s</p>',
                        get_the_excerpt(),
                        sprintf(
                            '<a class="read-more" href="%s">%s</a>',
                            get_permalink( $page_ID ),
                            __( 'Read more', IWORKS_THEME_NAME )
                        )
                    );
                    $content .= '</div>';
                    $posts[get_the_ID()] = $content;
                }
            }
            /* Restore original Post Data */
            wp_reset_postdata();
        }
        wp_reset_query();

        $content .= '</div>';
        return $content;
    }

}
