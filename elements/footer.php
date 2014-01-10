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
<?php do_action( 'iworks_before_footer' ); ?>
<?php if ( is_active_sidebar( 'sidebar-footer-1' ) ) { ?>
<?php do_action( 'iworks_before_footer1' ); ?>
<aside>
    <div id="footer-before">
        <div class="mask">
            <div class="wide">
                <ul class="widgets clearfix"><?php if ( function_exists('dynamic_sidebar') ) { dynamic_sidebar('sidebar-footer-1'); } ?></ul>
            </div>
        </div>
    </div>
</aside>
<?php } ?>
<?php do_action( 'iworks_before_footer' ); ?>
<footer>
<div id="footer">
    <nav id="access" role="navigation">
        <?php wp_nav_menu( array( 'theme_location'=>'menu_default', 'depth' => 1, 'container_class' => 'wide', 'menu_class' => 'clearfix', ) ); ?>
    </nav>
<?php if ( is_active_sidebar( 'sidebar-footer-2' ) ) { ?>
    <div class="wide"><?php if ( function_exists('dynamic_sidebar') ) { dynamic_sidebar('sidebar-footer-2'); } ?></div>
<?php } ?>
</div>
<?php wp_footer(); ?>
</footer>

