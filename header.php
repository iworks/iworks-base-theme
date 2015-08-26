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
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <link href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico" rel="Bookmark" />
    <link href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico" rel="shortcut icon" />
    <meta name="viewport" content="width=device-width" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
