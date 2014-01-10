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
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title><?php
/*
 * Print the <title> tag based on what is being viewed.
 */
global $page, $paged;

wp_title( '|', true, 'right' );

// Add the blog name.
bloginfo( 'name' );

// Add the blog description for the home/front page.
$site_description = get_bloginfo( 'description', 'display' );
if ( $site_description && ( is_home() || is_front_page() ) ) {
    echo " | $site_description";
}

// Add a page number if necessary:
if ( $paged >= 2 || $page >= 2 ) {
    echo ' | ' . sprintf( __( 'Page %s', IWORKS_THEME_NAME ), max( $paged, $page ) );
}

?></title>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <link href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico" rel="Bookmark" />
    <link href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico" rel="shortcut icon" />
    <meta name="viewport" content="width=device-width" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/scripts/html5.js"></script>
<![endif]-->
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php do_action( 'iworks_theme_after_body_tag_start' ); ?>
    <div id="container">
        <?php get_template_part( 'elements/header' ); ?>
        <?php do_action( 'iworks_theme_before_layout' ); ?>
        <div id="layout" class="wide">
            <?php do_action( 'iworks_theme_before_page' ); ?>
            <div id="page" class="clearfix">
                <?php do_action( 'iworks_theme_before_content' ); ?>
                <div id="content" role="main">
                    <main>
