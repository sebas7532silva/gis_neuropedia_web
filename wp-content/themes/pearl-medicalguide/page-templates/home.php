<?php
/*
 * Template Name: Home Template
 */
get_header();

/*
* Homepage Slider or Banner
*/
$slider_type = get_option( 'pearl_slider_type' );

$slider_type = get_post_meta( get_the_ID(), 'pearl_slider_type', true );

switch ( $slider_type ) {
	case 'static':
		get_template_part( 'layout/slider/static' );
		break;
	case 'revolution':
		get_template_part( 'layout/slider/revolution' );
		break;
	case 'banner':
		get_template_part( 'layout/header/banner' );
		break;
	default:
		get_template_part( 'layout/header/banner' );
}
?>

	<div class="home-content content">

		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();

				the_content();
			}
		}
		?>

	</div>

<?php

get_footer();

?>