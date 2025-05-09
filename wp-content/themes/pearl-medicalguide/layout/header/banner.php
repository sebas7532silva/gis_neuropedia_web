<div class="sub-banner">
	<?php
	$pearl_banner_title = get_the_title();
	// Banner Image
	$banner_image    = '';
	$banner_image_id = '';

	global $post;

	if ( ! is_object( $post ) || ! property_exists( $post, 'ID' ) ) {
		$banner_image_id = '';
	} elseif ( is_home() ) {
		$news_page_id    = get_option( 'page_for_posts' );
		$banner_image_id = get_post_meta( $news_page_id, 'pearl_banner_image', true );
	} else {
		$banner_image_id = get_post_meta( $post->ID, 'pearl_banner_image', true );
	}

	if ( $banner_image_id ) {
		$banner_image = wp_get_attachment_url( $banner_image_id );
	} else {
		$banner_image = get_pearl_default_banner();
	}

	echo '<img class="banner-img" src="' . esc_url( $banner_image ) . '" alt="' . get_bloginfo( 'name' ) . '"/>';

	if ( is_404() ) {
		$pearl_banner_title = get_the_title();
	} elseif ( is_home() ) {
		$blog_page_id       = get_option( 'page_for_posts' );
		$pearl_banner_title = get_the_title( $blog_page_id );

	} elseif ( is_page() ) {
		$pearl_banner_title = get_the_title();
	} elseif ( is_single() ) {
		$pearl_banner_title = get_the_title();

	} elseif ( is_search() ) {
		$pearl_banner_title = sprintf( esc_html__( 'Search Results for: %s', 'pearl-medicalguide' ), get_search_query() );

	} elseif ( is_author() ) {
		global $wp_query;
		$current_author = $wp_query->get_queried_object();
		if ( ! empty( $current_author->display_name ) ) {
			$pearl_banner_title = esc_html__( 'Posts By:', 'pearl-medicalguide' ) . ' ' . $current_author->display_name;
		}

	} elseif ( is_archive() && ( is_woocommerce_activated() && ! is_shop() ) ) {
		$pearl_banner_title = get_the_archive_title();

	} else if ( is_woocommerce_activated() && is_shop() ) {
		$pearl_banner_title = apply_filters( 'pearl_shop_title', esc_html__( 'Our Shop', 'pearl-medicalguide' ) );

	} else if ( is_404() ) {
		$pearl_banner_title = esc_html__( 'No Page Found', 'pearl-medicalguide' );

	}elseif ( is_tax() ) {
        $tax = get_taxonomy( get_queried_object()->taxonomy );
        $pearl_banner_title = sprintf( '%1$s: %2$s', $tax->labels->singular_name, single_term_title( '', false ) );
    }

    $pearl_breadcrumb_display = get_option('pearl_breadcrumb_display', 'true');
	if ( ! is_front_page() ) {
		?>
		<div class="detail">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="paging">
							<h2><?php echo sanitize_text_field( $pearl_banner_title ); ?></h2>
							<?php
                            if( 'true' === $pearl_breadcrumb_display ){
                                pearl_bread_crumb();
                            }
                            ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	?>
</div>