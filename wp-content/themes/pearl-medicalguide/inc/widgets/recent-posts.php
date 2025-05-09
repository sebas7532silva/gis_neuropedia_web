<?php

/**
 * Adds Pearl_Recent_Posts widget.
 */
class Pearl_Recent_Posts extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'pearl_recent_posts', // Base ID
            esc_html__('Pearl - Recent Posts', 'pearl-medicalguide'), // Name
            array('description' => esc_html__('A Foo Widget', 'pearl-medicalguide'),) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        ?>
        <div class="recent-posts">
            <?php
            $posts_args = array(
                'post_type' => 'post',
                'posts_per_page' => 3
            );

            $posts = new WP_Query($posts_args);

            if ($posts->have_posts()) {
                while ($posts->have_posts()) {
                    $posts->the_post();
                    ?>
                    <div class="post-sec clearfix">
                        <?php
                        if (has_post_thumbnail()) {
                            echo '<a href="' . get_the_permalink() . '">' . get_the_post_thumbnail(get_the_ID(), 'thumbnail', array(164, 146)) . '</a>';
                        }
                        ?>
                        <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
                        <span class="date"><?php printf(_x('%s ago', '%s = human-readable time difference', 'pearl-medicalguide'), human_time_diff(get_the_time('U'), current_time('timestamp'))); ?></span>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <?php
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     */
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Recent Posts', 'pearl-medicalguide');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'pearl-medicalguide'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

        return $instance;
    }

} // class Pearl_Recent_Posts

// register Pearl_Recent_Posts widget
function register_pearl_recent_posts()
{
    register_widget('Pearl_Recent_Posts');
}

add_action('widgets_init', 'register_pearl_recent_posts');