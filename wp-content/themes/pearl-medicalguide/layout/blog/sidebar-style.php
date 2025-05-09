<div class="col-md-8">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			?>
			<div id="post-<?php the_ID(); ?>" <?php post_class( 'news-sec' ); ?>>

				<?php
				if ( has_post_thumbnail() ) {
					echo '<a href="' . get_the_permalink( get_the_ID() ) . '">';
					the_post_thumbnail();
					echo '</a>';
				}
				?>
				<div class="detail">
					<?php get_template_part( 'layout/blog/post-metadata' ); ?>
					<h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<p><?php the_pearl_excerpt( 32, '' ); ?></p>
					<a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e( 'read more', 'pearl-medicalguide' ); ?></a>
				</div>
			</div>
			<?php
		}
	} else {
		get_template_part( 'layout/blog/no', 'posts' );
	}
    ?>
    <div class="row">
        <div class="col-md-12 pagination">
            <?php the_posts_pagination(); ?>
        </div>
    </div>
</div>
<div class="col-md-4">
	<?php get_sidebar(); ?>
</div>