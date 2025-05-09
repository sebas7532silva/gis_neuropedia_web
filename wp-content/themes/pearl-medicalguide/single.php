<?php
get_header();
?>
	<div class="content">
		<div class="news-posts">
			<div class="container">
				<div class="row">
					<div class="col-md-8">
						<?php
						if ( have_posts() ) {
							while ( have_posts() ) {
								the_post();
								?>
								<div class="news-detail">
									<?php
									if ( has_post_thumbnail() ) {
										the_post_thumbnail();
									}
									?>
									<div class="detail detail-content">
										<div class="post-meta">
                                            <span class="date">
                                                <?php the_date( 'F j, Y' ); ?> /
	                                            <?php
	                                            // post author
	                                            echo esc_html__( 'By', 'pearl-medicalguide' ) . ' ';
	                                            the_author_posts_link();
	                                            echo ' / ';

	                                            // post category
	                                            $category = get_the_category();
	                                            if ( ! empty( $category ) ) {

		                                            esc_html_e( 'Posted in', 'pearl-medicalguide' );
		                                            $name   = $category[0]->cat_name;
		                                            $cat_id = get_cat_ID( $name );
		                                            $link   = get_category_link( $cat_id );
		                                            echo ' <a href="' . esc_url( $link ) . '" rel="category">' . esc_html( $name ) . '</a>';
	                                            }
	                                            ?>
                                            </span>
										</div>
										<div class="post-content clearfix">
											<?php the_content(); ?>
											<div class="post-tags">
												<?php the_tags( '', '' ); ?>
											</div>
										</div>
									</div>
								</div>
								<div class="share-post">
									<span><?php esc_html_e( 'Share this Post!', 'pearl-medicalguide' ); ?></span>
									<div class="social-icons">
										<a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank" class="fb"><i class="icon-euro"></i></a>
										<a href="https://twitter.com/share?url=<?php the_permalink(); ?>" target="_blank" class="tw"><i class="icon-yen"></i></a>
										<a href="https://plus.google.com/share?url={<?php the_permalink(); ?>}" onclick="javascript:window.open(this.href,  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes')" target="_blank" class="gp"><i class="icon-google-plus"></i></a>
									</div>
								</div>
								<?php
								// If comments are open or we have at least one comment, load up the comment template
								if ( comments_open() || '0' != get_comments_number() ) :
									comments_template();
								endif;
							}
						}
						?>
					</div>
					<div class="col-md-4">
						<?php get_sidebar(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
get_footer();
?>