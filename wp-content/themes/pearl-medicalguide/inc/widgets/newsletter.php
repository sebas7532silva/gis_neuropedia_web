<?php

/**
 * Adds Pearl_Newsletter widget.
 */
class Pearl_Newsletter extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'pearl_newsletter', // Base ID
            esc_html__('Pearl - Newsletter', 'pearl-medicalguide'), // Name
            array('description' => esc_html__('A widget to display newsletter sign up form.', 'pearl-medicalguide'),) // Args
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
        if (!empty($instance['text'])) {
            ?>
            <div class="signup-text clearfix">
                <i class="icon-dollar"></i>
                <span><?php echo esc_html($instance['text']) ?></span>
            </div>
            <?php
        }
        ?>
        <div class="form">
            <form name="pearl_newsletter" id="pearl_newsletter" method="post" action="<?php echo esc_attr($instance['action']); ?>">
                <input type="text" data-delay="300" placeholder="<?php esc_html_e( 'Your Name', 'pearl-medicalguide' ) ?>"
                       name="<?php echo esc_attr($instance['name_name']); ?>" id="subscribe_name" class="input">
                <input type="text" data-delay="300" placeholder="<?php esc_html_e( 'Email Address', 'pearl-medicalguide' ) ?>"
                       name="<?php echo esc_attr($instance['email_name']); ?>" id="subscribe_email" class="input">
                <input name="Subscribe" type="submit" value="<?php esc_html_e( 'Subscribe', 'pearl-medicalguide' ) ?>">
            </form>
        </div>
        <?php
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     */
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Newsletter', 'pearl-medicalguide');
        $text = !empty($instance['text']) ? $instance['text'] : esc_html__('Sign up with your name and email to get updates fresh updates.', 'pearl-medicalguide');
        $action = !empty($instance['action']) ? $instance['action'] : '';
        $name_name = !empty($instance['name_name']) ? $instance['name_name'] : esc_html__('nl_name', 'pearl-medicalguide');
        $email_name = !empty($instance['email_name']) ? $instance['email_name'] : esc_html__('nl_email', 'pearl-medicalguide');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'pearl-medicalguide'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('text')); ?>"><?php esc_attr_e('Form Text:', 'pearl-medicalguide'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('text')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('text')); ?>" type="text"
                   value="<?php echo esc_attr($text); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('action')); ?>"><?php esc_attr_e('Form Action URL:', 'pearl-medicalguide'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('action')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('action')); ?>" type="text"
                   value="<?php echo esc_attr($action); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('name_name')); ?>"><?php esc_attr_e('Name Field Name:', 'pearl-medicalguide'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('name_name')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('name_name')); ?>" type="text"
                   value="<?php echo esc_attr($name_name); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('email_name')); ?>"><?php esc_attr_e('Email Field Name:', 'pearl-medicalguide'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('email_name')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('email_name')); ?>" type="text"
                   value="<?php echo esc_attr($email_name); ?>">
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
        $instance['text'] = (!empty($new_instance['text'])) ? strip_tags($new_instance['text']) : '';
        $instance['action'] = (!empty($new_instance['action'])) ? strip_tags($new_instance['action']) : '';
        $instance['name_name'] = (!empty($new_instance['name_name'])) ? strip_tags($new_instance['name_name']) : '';
        $instance['email_name'] = (!empty($new_instance['email_name'])) ? strip_tags($new_instance['email_name']) : '';

        return $instance;
    }

} // class Pearl_Newsletter

// register Pearl_Newsletter widget
function register_pearl_newsletter()
{
    register_widget('Pearl_Newsletter');
}

add_action('widgets_init', 'register_pearl_newsletter');
?>