<?php
/**
 * Generic iWorks Theme
 *
 * PHP version 5
 *
 * @category   WordPress_Themes
 * @package    WordPress
 * @subpackage Generic_iWorks_Theme
 * @author     Marcin Pietrzak <marcin@iworks.pl>
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version    3.0.0
 * @link       http://iworks.pl/
 *
 */

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'functions.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
    die ('seriously?');
}

define( 'IWORKS_THEME_NAME', 'Generic_iWorks_Theme' );
define( 'IWORKS_THEME_VERSION', '13.12.0' );

/**
 * prevent user from editing files
 */
if ( !defined( 'DISALLOW_FILE_EDIT' ) ) {
    define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * includes
 */

if ( !class_exists( 'IworksOptions' ) ) {
    require_once dirname(__FILE__).'/vendor/iworks/options.php';
}

foreach( array( 'class-iworks-theme', IWORKS_THEME_NAME, 'profile', 'dev' ) as $filename ) {
    $file = sprintf( '%s/includes/%s.php', dirname( __FILE__ ), strtolower( $filename ) );
    if ( is_file( $file ) && is_readable( $file ) ) {
        include_once $file;
    }
}

$class_name = IWORKS_THEME_NAME.'_Class';
if ( !class_exists( $class_name ) ) {
    $class_name = 'iWorks_Theme_Class';
}
$iworks_theme = new $class_name();

/**
 * helpers functions
 */

if ( !function_exists( 'iworks_posted_on' ) ) {
    function iworks_posted_on()
    {
        echo '<section class="entry-meta">';
        $author = sprintf
            (
                '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
                get_author_posts_url( get_the_author_meta( 'ID' )),
                sprintf( esc_attr__( 'View all posts by %s', IWORKS_THEME_NAME ), get_the_author() ),
                get_the_author()
            );
        $date_args = array();
        $date = sprintf
            (
                '<a href="%1$s" title="%2$s"><span class="entry-date updated" title="%3$s">%4$s</span></a>',
                get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) ),
                esc_attr( get_the_time() ),
                get_the_time( 'Y-m-d' ),
                get_the_date()
            );
        printf
            (
                __( '<span class="meta-sep">Published by</span> <i>%1$s</i> <span class="meta-sep">|</span> <span class="%2$s">Posted date</span> %3$s', IWORKS_THEME_NAME ),
                $author,
                'meta-prep meta-prep-date',
                $date
            );
        ?>
            <div class="entry-utility">
                <?php if ( count( get_the_category() ) ) : ?>
                    <span class="cat-links">
                        <?php printf( __( '<span class="%1$s">Posted in</span> %2$s', IWORKS_THEME_NAME ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?>
                    </span>
                    <span class="meta-sep">|</span>
                <?php endif; ?>
                <?php
                    $tags_list = get_the_tag_list( '', ', ' );
                    if ( $tags_list ):
                ?>
                    <span class="tag-links">
                        <?php printf( __( '<span class="%1$s">Tagged</span> %2$s', IWORKS_THEME_NAME ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?>
                    </span>
                    <span class="meta-sep">|</span>
                <?php endif; ?>
                <span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', IWORKS_THEME_NAME ), __( '1 Comment', IWORKS_THEME_NAME ), __( '% Comments', IWORKS_THEME_NAME ) ); ?></span>
                <?php edit_post_link( __( 'Edit', IWORKS_THEME_NAME ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
            </div><!-- .entry-utility -->
        <?php
        echo '</section>';
    }
}

if ( !function_exists( 'iworks_thumbnail' ) ) {
    function iworks_thumbnail($link_image = true, $name = 'post-thumbnail')
    {
        if (has_post_thumbnail()) {
            echo '<section class="image">';
            if ($link_image) {
                printf (
                    '<a href="%s" title="%s">%s</a>',
                    wp_get_attachment_url ( get_post_thumbnail_id( )) ,
                    get_the_title(),
                    get_the_post_thumbnail( get_the_ID(), $name )
                );
            } else {
                echo '<span class="thumbnail-icon">';
                echo get_the_post_thumbnail( get_the_ID(), $name);
                echo '</span>';
            }
            echo '</section>';
        }
    }
}

define( 'ICL_AFFILIATE_ID', 6814 );
define( 'ICL_AFFILIATE_KEY', 'aa49ecd05b511fb341f607b8045181dc' );
