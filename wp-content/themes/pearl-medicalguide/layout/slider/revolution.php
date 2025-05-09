<?php
$revolution_alias = get_post_meta( get_the_ID(), 'pearl_rv_slider_alias', true );
$banner_timetable = get_post_meta( get_the_ID(), 'pearl_static_banner_timetable', true );
$banner_app_form  = get_post_meta( get_the_ID(), 'pearl_static_banner_app_form', true );

if ( $banner_timetable == 'show' ) {
	get_template_part( 'layout/slider/time-table' );
}

if ( ! empty( $revolution_alias ) ) :
	echo do_shortcode( '[rev_slider alias="' . sanitize_text_field( $revolution_alias ) . '"]' );
else :
	echo '<p class="error">' . esc_html__( 'Please enter a Revolution Slider Alias code!', 'pearl-medicalguide' ) . '</p>';
endif;

if ( $banner_app_form == 'show' ) {
	get_template_part( 'layout/slider/appointment-form' );
}
?>