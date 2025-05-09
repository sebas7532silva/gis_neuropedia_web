<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content-vw">
 *
 * @package VW Medical Care
 */

?><!DOCTYPE html>

<html <?php language_attributes(); ?>>

	<head>
	  <meta charset="<?php bloginfo( 'charset' ); ?>">
	  <meta name="viewport" content="width=device-width">
	  <link rel="profile" href="<?php echo esc_url( __( 'http://gmpg.org/xfn/11', 'vw-medical-care' ) ); ?>">
	  <?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>

		<header role="banner">
    		<a class="screen-reader-text skip-link" href="#maincontent"><?php esc_html_e( 'Skip to content', 'vw-medical-care' ); ?></a>
			<div class="home-page-header">
				<?php get_template_part('template-parts/header/top-header'); ?>
				<?php get_template_part('template-parts/header/middle-header'); ?>
			</div>
		</header>