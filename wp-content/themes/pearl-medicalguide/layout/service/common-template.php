<?php

get_header();

/* Header Banner */
get_template_part('layout/header/header-banner');
?>
    <!--Start Content-->
    <div class="content">

        <div class="services-content">
            <div class="container">

                <div class="row">
                    <?php
                    global $post;

                    $service_args = array(
                        'post_type' => 'service',
                        'posts_per_page' => -1,
                    );

                    // The Query
                    $services = new WP_Query($service_args);

                    $column = '4';
                    $image_size = 'pearl_image_size_762_700';

                    if (is_page_template('page-templates/service-2-column.php')) {
                        $column = '6';
                    } else if (is_page_template('page-templates/service-3-column.php')) {
                        $column = '4';
                    } else if (is_page_template('page-templates/service-4-column.php')) {
                        $column = '3';
                    }

                    // The Loop
                    if ($services->have_posts()) :
                        while ($services->have_posts()) :
                            $services->the_post();
                            ?>
                            <div id="post-<?php the_ID(); ?>" <?php post_class('col-md-' . $column); ?>>
                                <div class="serv-sec serv-list">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <a href="<?php the_permalink(); ?>">
                                           <?php the_post_thumbnail($image_size, array('class' => 'banner-img')); ?>
                                        </a>
                                    <?php endif; ?>
                                    <div class="detail">
                                        <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                        <p><?php the_pearl_excerpt(18); ?></p>
                                        <a href="<?php the_permalink(); ?>"><?php esc_html_e('- Read More', 'pearl-medicalguide'); ?></a>
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


    </div>
    <!--End Content-->

<?php

get_footer();

?>