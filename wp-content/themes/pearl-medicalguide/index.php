<?php
get_header();

$news_style = get_option( 'pearl_news_style' );
$section    = 'news';

// only for demo purpose
if ( isset( $_GET['style'] ) && ! empty( $_GET['style'] ) ) {
	$news_style = $_GET['style'];
}

if ( $news_style == 'text' ) :
	$section = 'text';
endif;

?>
	<div class="content">
		<div class="<?php echo sanitize_html_class( $section ); ?>-posts">
			<div class="container">
				<div class="row">
					<?php
					switch ( $news_style ) {
						case 'sidebar':
							get_template_part( 'layout/blog/sidebar-style' );
							break;
						case 'double':
							get_template_part( 'layout/blog/double-style' );
							break;
						case 'fullwidth':
							get_template_part( 'layout/blog/fullwidth-style' );
							break;
						case 'text':
							get_template_part( 'layout/blog/text-style' );
							break;
						default:
							get_template_part( 'layout/blog/sidebar-style' );
					}
					?>
				</div>
			</div>
		</div>
	</div>
<?php
get_footer();
?>