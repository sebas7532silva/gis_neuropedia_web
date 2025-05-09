<div id="header-2">

	<?php
	$sticky_header = get_option( 'pearl_sticky_header' );
	?>
	<header class="header header2" <?php echo ( $sticky_header ) ? 'id="stikcy-header"' : ''; ?>>

		<div class="container">
			<div class="row">

				<?php get_template_part( 'layout/header/logo' ); ?>

				<div class="col-md-9">
					<nav class="menu-2">
						<?php
						wp_nav_menu( array(
							'theme_location' => 'main-menu',
							'container'      => false,
							'menu_class'     => 'nav plus-menu'
						) );
						?>
					</nav>
				</div>
			</div> <!-- end .row -->
		</div><!-- end .container -->
	</header>
</div>