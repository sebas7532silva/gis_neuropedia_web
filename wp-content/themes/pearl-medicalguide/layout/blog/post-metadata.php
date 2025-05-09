<span>
    <?php
    // post date
    esc_html_e('Posted On', 'pearl-medicalguide');
    ?>
    <a href="<?php the_permalink(); ?>"><?php the_date('F j, Y'); ?></a> /
    <?php
    // post author
    echo esc_html__('By', 'pearl-medicalguide') . ' ';
    the_author_posts_link();
    echo ' / ';

    // post category
    $category = get_the_category();
    if (!empty($category)) {

        esc_html_e('Posted in', 'pearl-medicalguide');
        $name = $category[0]->cat_name;
        $cat_id = get_cat_ID($name);
        $link = get_category_link($cat_id);
        echo ' <a href="' . esc_url($link) . '" rel="category">' . esc_html($name) . '</a>';
    }
    ?>
</span>