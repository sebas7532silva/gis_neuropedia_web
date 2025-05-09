<?php

get_header();

/* Header Banner */
get_template_part( 'layout/header/header-banner' );

?>
<div class="content site-pages">

	<div class="container">
		<div class="row">

			<?php

			$column = 9;
			if ( is_single() ) {
				$column = '12';
			}
			?>

			<div class="col-md-<?php echo $column; ?>">
				<?php woocommerce_content(); ?>
			</div>
			<?php if ( ! is_single() ) { ?>
				<div class="col-md-3">
					<?php get_sidebar( 'shop' ); ?>
				</div>
			<?php } ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
