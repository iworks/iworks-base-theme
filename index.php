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
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header>
        <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s'), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
        <?php iworks_posted_on(); ?>
    </header>
    <div class="entry-content">
    <?php iworks_thumbnail( false ); ?>
<?php
if ( is_single() || is_page() ) {
    echo '<section class="content">';
    the_content();
    echo '<section>';
} else {
    the_excerpt( __( '(more...)', IWORKS_THEME_NAME ) );
?>
        <p><a href="<?php the_permalink(); ?>"><?php _e( 'More...', 'IWORKS_THEME_NAME' ); ?></a></p>
<?php } ?>
    </div>
</article>
<?php endwhile; ?>
<nav class="posts_nav_link"><?php posts_nav_link(); ?></nav>
<?php else: get_template_part( 'empty'); endif; ?>
<?php get_footer();
