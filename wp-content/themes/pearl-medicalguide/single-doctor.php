<?php

get_header();

/* Header Banner */
get_template_part( 'layout/header/header-banner' );
?>

<!--Start Content-->
<div class="content">

    <!--Start Team Detail-->
    <div class="member-detail">
        <div class="container">
			<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();

					$meta_data = get_post_custom();

					?>

                    <div class="row">
                        <div class="col-md-5 member-thumbnail">
							<?php
							if ( has_post_thumbnail() ) :
								the_post_thumbnail( 'pearl_image_size_762_700', true );
							endif;
							?>
                        </div>

                        <div class="col-md-7">
                            <div class="team-detail">

                                <div class="name">
                                    <h6><?php the_title(); ?></h6>
                                    <span><?php the_terms( $post->ID, 'doctor-department', ' ', ', ', ' ' ); ?></span>
                                </div>

                                <ul>
									<?php
									if ( ! empty( $meta_data['PEARL_META_speciality'] ) ) :
										echo '<li><span class="title">' . esc_html__( 'Speciality', 'pearl-medicalguide' ) . '</span> <span>' . esc_html( $meta_data['PEARL_META_speciality'][0] ) . '</span></li>';
									endif;

									if ( ! empty( $meta_data['PEARL_META_degree'] ) ) :
										echo '<li><span class="title">' . esc_html__( 'Degrees', 'pearl-medicalguide' ) . '</span> <span>' . esc_html( $meta_data['PEARL_META_degree'][0] ) . '</span></li>';
									endif;

									if ( ! empty( $meta_data['PEARL_META_experience'] ) ) :
										echo '<li><span class="title">' . esc_html__( 'Experience', 'pearl-medicalguide' ) . '</span> <span>' . esc_html( $meta_data['PEARL_META_experience'][0] ) . '</span></li>';
									endif;

									if ( ! empty( $meta_data['PEARL_META_training'] ) ) :
										echo '<li><span class="title">' . esc_html__( 'Training', 'pearl-medicalguide' ) . '</span> <span>' . esc_html( $meta_data['PEARL_META_training'][0] ) . '</span></li>';
									endif;

									if ( ! empty( $meta_data['PEARL_META_work_days'] ) ) :
										echo '<li><span class="title">' . esc_html__( 'Work days', 'pearl-medicalguide' ) . '</span> <span>' . esc_html( $meta_data['PEARL_META_work_days'][0] ) . '</span></li>';
									endif;
									?>
                                </ul>

                            </div>
                        </div>
                    </div>

                    <?php
                        $page_content = get_the_content();

                        if( ! empty( $page_content ) ) {
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="detail-content clearfix">
	                                    <?php the_content(); ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
				}
			}
			?>
        </div>
    </div>
    <!--End Team Detail-->

</div>
<!--End Content-->

<?php

get_footer();

?>
