<?php

get_header();

/* Header Banner */
get_template_part( 'layout/header/header-banner' );
?>

	<!--Start Content-->
	<div class="content">

		<div class="services-content">
			<div class="container">
				<div class="row">
					<?php

					// The Loop
					if ( have_posts() ) :
						while ( have_posts() ) :
							the_post();
							?>
							<div id="post-<?php the_ID(); ?>" <?php post_class( 'col-md-12' ); ?>>
								<div class="serv-sec">
									<div class="row">
										<div class="col-md-4">
											<?php
											if ( has_post_thumbnail() ) :
												the_post_thumbnail( 'pearl_image_size_762_700', array( 'class' => 'banner-img' ) );
											endif;
											?>
										</div>
										<div class="col-md-8">
											<div class="detail detail-content">
												<h5><?php the_title(); ?></h5>
												<p><?php the_content(); ?></p>
											</div>
										</div>
									</div>
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