<?php
/*
 * Template Name: Full Width Template
 */
get_header();

?>

    <div class="full-width content site-pages">
        <div class="container">
            <?php
            if (have_posts()) {

                the_post(); ?>
                <div class="post-content clearfix detail-content">
                    <?php the_content(); ?>
                </div>
                <?php
                if (pearl_is_paginated_page()) {
                    ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 pagination">

                                <nav class="navigation pagination" role="navigation">
                                    <?php
                                    $args = array(
                                        'before' => '<div class="nav-links">',
                                        'after' => '</div>',
                                        'echo' => 1,
                                        'link_before' => '<span>',
                                        'link_after' => '</span>',
                                    );

                                    wp_link_pages($args);
                                    ?>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>

<?php get_footer(); ?>