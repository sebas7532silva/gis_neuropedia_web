<?php

$banner_image_id = get_post_meta( get_the_ID(), 'pearl_static_banner_image', true );
$banner_icon     = get_post_meta( get_the_ID(), 'pearl_static_banner_icon', true );
$banner_heading  = get_post_meta( get_the_ID(), 'pearl_static_banner_heading', true );
$banner_desc     = get_post_meta( get_the_ID(), 'pearl_static_banner_description', true );

$banner_timetable = get_post_meta( get_the_ID(), 'pearl_static_banner_timetable', true );
$banner_app_form  = get_post_meta( get_the_ID(), 'pearl_static_banner_app_form', true );

if ( ! empty( $banner_image_id ) ) {
	$banner_image = wp_get_attachment_image_url( $banner_image_id, 'full' );

	if ( empty( $banner_image ) ) {
		$banner_image = get_template_directory_uri() . '/images/header-banner.jpg';
	}
} else if ( empty( $banner_image ) ) {
	$banner_image = get_template_directory_uri() . '/images/header-banner.jpg';
}

if ( $banner_timetable == 'show' ) {
	get_template_part( 'layout/slider/time-table' );
}

?>
	<div class="banner-three" style="background: url(<?php echo esc_url( $banner_image ); ?>) no-repeat center top;">
		<div class="main-banner-three">
			<div class="container">
				<div class="detail">
					<?php
					if ( ! empty( $banner_icon ) ) :
						echo '<i class="' . sanitize_html_class( $banner_icon ) . '"></i>';
					endif;

					if ( ! empty( $banner_heading ) ) :
						echo '<span class="title">' . esc_html( $banner_heading ) . '</span>';
					endif;

					if ( ! empty( $banner_desc ) ) :
						echo '<p>' . esc_textarea( $banner_desc ) . '</p>';
					endif;
					?>
				</div>
			</div>
		</div>
	</div>

<?php
if ( $banner_app_form == 'show' ) {
	get_template_part( 'layout/slider/appointment', 'form' );
}
?>