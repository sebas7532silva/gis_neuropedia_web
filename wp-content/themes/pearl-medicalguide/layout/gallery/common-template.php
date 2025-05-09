<?php

get_header();

/* Header Banner */
get_template_part('layout/header/header-banner');
?>

<!--Start Content-->
<div class="content">

    <div class="gallery">
        <!--Start Team Detail-->
        <div class="cbp-panel" style="max-width:1170px;">

            <div id="filters-container" class="cbp-l-filters-list ">
                <div data-filter="*"
                     class="cbp-filter-item-active cbp-filter-item cbp-l-filters-list-first"><?php esc_html_e('All Galleries', 'pearl-medicalguide'); ?> </div>
                <?php
                $types = get_terms('gallery-type');
                if (!empty($types) && !is_wp_error($types)) {
                    foreach ($types as $type) {
                        echo '<div data-filter=".' . esc_attr($type->slug) . '" class="cbp-filter-item">' . esc_html($type->name) . '</div>';

                    }
                }
                ?>
            </div>

            <?php
            $column = '3';
            $image_size = 'pearl_image_size_762_700';

            if (is_page_template('page-templates/gallery-2-column.php')) {
                $column = '2';
            } else if (is_page_template('page-templates/gallery-4-column.php')) {
                $column = '4';
            }
            ?>

            <div id="grid-container<?php echo sanitize_html_class($column); ?>" class="cbp main-gallery">

                <?php
                $gallery_args = array(
                    'post_type' => 'gallery',
                    'posts_per_page' => -1,
                );

                // The Query
                $galleries = new WP_Query($gallery_args);

                // The Loop
                if ($galleries->have_posts()) :
                    while ($galleries->have_posts()) :
                        $galleries->the_post();

                        $gallery_items = get_the_terms($post->ID, 'gallery-type');
                        if (!empty($gallery_items)) {
                            $gallery_items_slugs = '';
                            foreach ($gallery_items as $item) {
                                if (!empty($gallery_items_slugs))
                                    $gallery_items_slugs .= ' ';

                                $gallery_items_slugs .= $item->slug;
                            }
                        }

                        $meta_data = get_post_custom();
                        $gallery_icon = 'icon-image2';
                        $container_icon = 'photo-icon';
                        $fancybox = '';
                        ?>

                        <div class="cbp-item <?php echo sanitize_html_class($gallery_items_slugs); ?>">
                            <?php
                            if (has_post_thumbnail()) {
                                $image_id = get_post_thumbnail_id();
                            }

                            if (!empty($meta_data['PEARL_META_gallery_video'])) {
                                $src_url = $meta_data['PEARL_META_gallery_video'][0];
                                $gallery_icon = 'icon-video2';
                                $container_icon = 'video-icon';
                                $fancybox = '-media';
                            } else {
                                $src_url = wp_get_attachment_url($image_id);
                            }
                            ?>
                            <a class="gallery-sec fancybox<?php echo sanitize_html_class($fancybox); ?> <?php echo sanitize_html_class($container_icon); ?>"
                               href="<?php echo esc_url($src_url); ?>" data-fancybox-group="gallery">
                                <div class="image-hover img-layer-slide-left-right">
                                    <?php
                                    if (has_post_thumbnail()) :
                                        the_post_thumbnail($image_size);
                                    endif;
                                    ?>
                                    <div class="layer"><i class="<?php echo sanitize_html_class($gallery_icon); ?>"></i>
                                    </div>
                                </div>

                                <div class="detail">
                                    <h6><?php the_title(); ?></h6>
                                    <?php
                                    if (!empty($meta_data['PEARL_META_gallery_desc'])) :
                                        echo '<span>' . esc_html($meta_data['PEARL_META_gallery_desc'][0]) . '</span>';
                                    endif;
                                    ?>
                                </div>
                            </a>
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


<?php

get_footer();

?>
