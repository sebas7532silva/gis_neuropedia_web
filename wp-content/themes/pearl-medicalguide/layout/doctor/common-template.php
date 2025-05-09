<?php

get_header();

/* Header Banner */
get_template_part('layout/header/header-banner');
?>

<!--Start Content-->
<div class="content">

    <div class="all-team-members">
        <!--Start Doctor Listing-->
        <div class="cbp-panel" style="max-width:1170px;">

            <div id="filters-container" class="cbp-l-filters-list ">
                <div data-filter="*"
                     class="cbp-filter-item-active cbp-filter-item cbp-l-filters-list-first"><?php esc_html_e('All Departments', 'pearl-medicalguide'); ?> </div>
                <?php
                $departments = get_terms('doctor-department');
                if (!empty($departments) && !is_wp_error($departments)) {
                    foreach ($departments as $department) {
                        echo '<div data-filter=".' . esc_attr($department->slug) . '" class="cbp-filter-item">' . esc_html($department->name) . '</div>';

                    }
                }
                ?>
            </div>

            <?php
            $column = '3';
            $image_size = 'pearl_image_size_762_700';
            $excerpt_length = 18;

            if (is_page_template('page-templates/doctor-2-column.php')) {
                $column = '2';
                $excerpt_length = 26;
            } else if (is_page_template('page-templates/doctor-4-column.php')) {
                $column = '4';
                $excerpt_length = 15;
            }
            ?>

            <div id="grid-container<?php echo sanitize_html_class($column); ?>" class="cbp">

                <?php

                $service_args = array(
                    'post_type' => 'doctor',
                    'posts_per_page' => -1,
                );

                // The Query
                $services = new WP_Query($service_args);

                // The Loop
                if ($services->have_posts()) :
                    while ($services->have_posts()) :
                        $services->the_post();

                        $doc_terms = get_the_terms($post->ID, 'doctor-department');
                        if (!empty($doc_terms)) {
                            $doc_terms_slugs = '';
                            foreach ($doc_terms as $term) {
                                if (!empty($doc_terms_slugs))
                                    $doc_terms_slugs .= ' ';

                                $doc_terms_slugs .= $term->slug;
                            }
                        }

                        $meta_data = get_post_custom();
                        ?>

                        <div class="cbp-item <?php echo esc_html($doc_terms_slugs); ?>">
                            <div class="cbp-caption">
                                <div class="cbp-caption-defaultWrap">
                                    <?php
                                    if (has_post_thumbnail()) :
                                        the_post_thumbnail($image_size);
                                    endif;
                                    ?>
                                </div>
                                <div class="cbp-caption-activeWrap">
                                    <div class="cbp-l-caption-alignCenter">
                                        <div class="cbp-l-caption-body">

                                            <div class="cbp-l-caption-text">
                                                <?php
                                                if (!empty($meta_data['PEARL_META_facebook_url'])) :
                                                    echo '<a href="' . esc_url($meta_data['PEARL_META_facebook_url'][0]) . '" target="_blank"><i class="icon-euro"></i></a> ';
                                                endif;
                                                if (!empty($meta_data['PEARL_META_twitter_url'])) :
                                                    echo '<a href="' . esc_url($meta_data['PEARL_META_twitter_url'][0]) . '" target="_blank"><i class="icon-yen"></i></a> ';
                                                endif;
                                                if (!empty($meta_data['PEARL_META_google_url'])) :
                                                    echo '<a href="' . esc_url($meta_data['PEARL_META_google_url'][0]) . '" target="_blank"><i class="icon-caddieshoppingstreamline"></i></a> ';
                                                endif;
                                                ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="detail">
                                <h6><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
                                <span><?php the_terms(get_the_ID(), 'doctor-department', ' ', ', ', ' '); ?></span>
                                <p><?php the_pearl_excerpt($excerpt_length); ?></p>
                                <a href="<?php the_permalink(); ?>"><?php esc_html_e('- View Profile', 'pearl-medicalguide'); ?></a>
                            </div>

                        </div>

                        <?php
                    endwhile;
                endif;
                ?>
            </div>

        </div>
        <!--End Team Detail-->

    </div>
</div>
<!--End Content-->

<?php get_footer(); ?>
