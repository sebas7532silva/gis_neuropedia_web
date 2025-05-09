<?php
/*
 * Template Name: Fluid Width Template
 */
get_header();

/* Header Banner */
get_template_part('layout/header/header-banner');

?>

    <div class="fluid-width content site-pages">

        
        <?php
        if (have_posts()) {
            the_post();

            the_content();

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

<?php get_footer(); ?>