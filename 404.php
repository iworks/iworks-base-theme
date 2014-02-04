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
<div id="post-0" class="post error404 not-found hentry">
    <div class="entry-content">
        <h1 class="entry-title"><?php _e( 'Page not found', IWORKS_THEME_NAME ); ?></h1>
        <p><?php _e('Sorry, no posts matched your criteria.', IWORKS_THEME_NAME ); ?></p>
        <?php get_search_form(); ?>
    </div>
</div>
<?php get_footer();
