<?php

get_header();

/* Header Banner */
get_template_part('layout/header/header-banner');

?>
<div class="content site-pages">

    <div class="container">
        <div class="row">

            <div class="col-md-8">
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

                    // If comments are open or we have at least one comment, load up the comment template
                    if (comments_open() || '0' != get_comments_number()) :
                        comments_template();
                    endif;
                }
                ?>
            </div>

            <div class="col-md-4">
                <?php get_sidebar('page'); ?>
            </div>

        </div>
    </div>
</div>

<?php get_footer(); ?>
