<?php

get_header();

/* Header Banner */
get_template_part( 'layout/header/header-banner' );
?>

<!--Start Content-->
<div class="content">

	<div class="all-team-members">
		<div class="cbp-panel" style="max-width:1170px;">

			<div id="grid-container3" class="cbp">
				<?php

				// The Loop
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();

						$meta_data = get_post_custom();
						?>

						<div class="cbp-item">
							<div class="cbp-caption">
								<div class="cbp-caption-defaultWrap">
									<?php
									if ( has_post_thumbnail() ) :
										the_post_thumbnail( 'pearl_image_size_762_700' );
									endif;
									?>
								</div>
								<div class="cbp-caption-activeWrap">
									<div class="cbp-l-caption-alignCenter">
										<div class="cbp-l-caption-body">

											<div class="cbp-l-caption-text">
												<?php
												if ( ! empty( $meta_data['PEARL_META_facebook_url'] ) ) :
													echo '<a href="' . esc_url( $meta_data['PEARL_META_facebook_url'][0] ) . '" target="_blank"><i class="icon-euro"></i></a> ';
												endif;
												if ( ! empty( $meta_data['PEARL_META_twitter_url'] ) ) :
													echo '<a href="' . esc_url( $meta_data['PEARL_META_twitter_url'][0] ) . '" target="_blank"><i class="icon-yen"></i></a> ';
												endif;
												if ( ! empty( $meta_data['PEARL_META_google_url'] ) ) :
													echo '<a href="' . esc_url( $meta_data['PEARL_META_google_url'][0] ) . '" target="_blank"><i class="icon-caddieshoppingstreamline"></i></a> ';
												endif;
												?>
											</div>

										</div>
									</div>
								</div>
							</div>

							<div class="detail">
								<h6><?php the_title(); ?></h6>
								<span><?php the_terms( $post->ID, 'doctor-department', ' ', ', ', ' ' ); ?></span>
								<p><?php the_pearl_excerpt( 18 ) ?></p>
								<a href="<?php the_permalink(); ?>"><?php esc_html_e( '- View Profile', 'pearl-medicalguide' ); ?></a>
							</div>

						</div>

					<?php
					endwhile;
				endif;
				?>
			</div>
		</div>

	</div>
</div><!--End Content-->

<?php

get_footer();

?>
