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
<?php do_action( 'iworks_before_header' ); ?>
<div id="header" class="clearfix" <?php if ( !is_front_page() ) { ?>style="background-image:url(<?php header_image(); ?>);"<?php } ?>>
    <div class="mask">
        <nav id="access" role="navigation">
        <?php wp_nav_menu( array( 'theme_location'=>'menu_default', 'depth' => 1, 'container_class' => 'wide', 'menu_class' => 'clearfix', ) ); ?>
        </nav>
        <?php do_action( 'iworks_after_menu_default' ); ?>
        <?php if ( is_home() || is_front_page() ) { ?>
        <div class="wide">
            <div id="logo"><a href="<?php echo site_url(); ?>"><span><?php bloginfo( 'title' ); ?></span></a></div>
            <?php do_action('iworks_after_title' ); ?>
        </div>
        <?php } ?>
    </div>
</div>
<?php do_action( 'iworks_before_stream' );
