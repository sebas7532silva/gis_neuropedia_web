<div class="col-md-12">
    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            ?>
            <div id="post-<?php the_ID(); ?>" <?php post_class('post-sec'); ?>>
                <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
                <p><?php the_pearl_excerpt(40); ?></p>
                <ul>
                    <li><?php echo get_avatar(get_the_author_meta('ID'), 112); ?>
                        <span><?php the_author_link(); ?></span></li>
                    <?php
                    if (is_sticky()) {
                        ?>
                        <li><i class="icon-pin2"></i>
                            <span><?php esc_html_e('Sticky', 'pearl-medicalguide'); ?></span></a></li>
                        <?php
                    }
                    ?>
                    <li><a href="<?php the_permalink(); ?>"><i class="icon-clock3"></i>
                            <span><?php the_date('F j, Y'); ?></span></a></li>
                    <li><a href="<?php comments_link(); ?>"><i class="icon-icons206"></i>
                            <span><?php comments_number( __('no comments', 'pearl-medicalguide'), __('one comment', 'pearl-medicalguide'), __('% comments', 'pearl-medicalguide')); ?></span></a></li>
                </ul>
            </div>
            <?php
        }
        ?>
        <div class="next-pre">
            <span class="prev-posts-link"><?php previous_posts_link('<i class="icon-chevron-small-left"></i> ' . esc_html__('Newest Posts', 'pearl-medicalguide')); ?></span>
            <span class="next-posts-link"><?php next_posts_link(esc_html__('Older Posts', 'pearl-medicalguide') . ' <i class="icon-chevron-small-right"></i>'); ?></span>
        </div>
        <?php
    } else {
        get_template_part('layout/blog/no', 'posts');
    }
    ?>
</div>