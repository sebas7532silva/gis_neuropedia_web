<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<meta name="format-detection" content="telephone=no">

	<?php

	if ( is_singular() && pings_open( get_queried_object() ) ) {
		?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php
	}

	wp_head();
	?>
</head>
<body <?php body_class(); ?> >

<?php
// site design switcher
$design_switcher = get_option( 'pearl_design_switcher', 'no' );
if ( 'yes' == $design_switcher ) {
	include( get_template_directory() . '/switcher/switcher.php' );
}
?>

<div id="wrap">

	<?php
	$site_loader = get_option( 'pearl_theme_loader', 'pearl-medicalguide' );
	if ( $site_loader == 'yes' ) {
		?>
		<!--Start PreLoader-->
		<div id="preloader">
			<div id="status">&nbsp;</div>
			<div class="loader">
				<h1><?php esc_html_e( 'Loading...', 'pearl-medicalguide' ); ?></h1>
				<span></span>
				<span></span>
				<span></span>
			</div>
		</div><!--End PreLoader-->
		<?php
	}
	?>

	<!--Start Header-->
	<?php

	$header_variation = get_option( 'pearl_header_variation' );

	// only for demo purpose
	if ( ( isset( $_GET['header'] ) && ! empty( $_GET['header'] ) ) ) {
		$header_variation = $_GET['header'];
	}

	if ( $header_variation == 'simple' ) {
		get_template_part( 'layout/header/header-simple' );
	} else {
		get_template_part( 'layout/header/header-topbar' );
	}
	?>
	<!--End Header-->


	<!-- Mobile Menu Start -->
	<div class="container">
		<div id="page">
			<header class="header">
				<a href="#menu"></a>
			</header>
			<nav id="menu">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'main-menu',
					'container'      => false
				) );
				?>
			</nav>
		</div>
	</div>
	<!-- Mobile Menu End -->

<?php
if ( ! is_page_template( 'page-templates/home.php' ) ) :
	get_template_part( 'layout/header/banner' );
endif;
?>