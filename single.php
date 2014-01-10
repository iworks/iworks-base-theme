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
 * @license    http://iworks.pl/ commercial
 * @version    3.0.0
 * @link       http://iworks.pl/
 *
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header>
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php iworks_posted_on(); ?>
    </header>
    <div class="entry-content">
        <?php the_content(); ?>
        <?php wp_link_pages(); ?>
        <nav>
            <ul id="nav-below" class="navigation">
                <li class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', IWORKS_THEME_NAME ) . '</span> %title' ); ?></li>
                <li class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', IWORKS_THEME_NAME ) . '</span>' ); ?></li>
            </ul>
        </nav>
        <?php comments_template( '', true ); ?>
    </div>
</article>
<?php endwhile; ?>
<nav class="posts_nav_link"><?php posts_nav_link(); ?></nav>
<?php else: get_template_part( 'empty'); endif; ?>
<?php get_footer();
