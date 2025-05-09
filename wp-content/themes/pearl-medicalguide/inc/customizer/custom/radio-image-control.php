<?php

/**
 * Custom Radio Image Control
 */
class Pearl_Custom_Radio_Image_Control extends WP_Customize_Control
{

    /**
     * Declare the control type.
     * @var string
     */
    public $type = 'pearl-radio-image';

    /**
     * Enqueue scripts and styles for the custom control.
     *
     * Note, you can also enqueue stylesheets here as well. Stylesheets are hooked
     * at 'customize_controls_print_styles'.
     *
     */
    public function enqueue()
    {
        wp_enqueue_script('jquery-ui-button');
    }

    /**
     * Render the control to be displayed in the Customizer.
     */
    public function render_content()
    {
        if (empty($this->choices)) {
            return;
        }

        $name = '_customize-radio-' . $this->id;
        ?>
        <span class="customize-control-title">
			<?php echo esc_attr($this->label); ?>
            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>
		</span>
        <div id="input_<?php echo sanitize_html_class($this->id); ?>" class="image">
            <?php foreach ($this->choices as $value => $label) : ?>
                <input class="image-select" type="radio" value="<?php echo esc_attr($value); ?>"
                       id="<?php echo sanitize_html_class($this->id . $value); ?>"
                       name="<?php echo esc_attr($name); ?>" <?php $this->link();
                checked($this->value(), $value); ?>>
                <label for="<?php echo esc_attr($this->id . $value); ?>">
                    <img src="<?php echo esc_html($label); ?>" alt="<?php echo esc_attr($value); ?>"
                         class="<?php echo esc_attr($value); ?>"
                         title="<?php echo esc_attr(ucwords(str_replace('-', ' ', $value))); ?>">
                </label>
                </input>
            <?php endforeach; ?>
        </div>
        <script>jQuery(document).ready(function ($) {
                $('[id="input_<?php echo sanitize_html_class($this->id) ?>"]').buttonset();
            });</script>
        <?php
    }
}


/**
 * Add CSS for radio image control
 */
function pearl_customizer_custom_control_css()
{
    ?>
    <style>
        .customize-control-pearl-radio-image .image.ui-buttonset input[type=radio] {
            display: none;
        }

        .customize-control-pearl-radio-image .image.ui-buttonset label {
            display: inline-block;
            margin-right: 5px;
            margin-bottom: 10px;
            padding: 0;
            height: auto;
            box-shadow: none;
			border: 0;
        }

		.customize-control-pearl-radio-image .image.ui-buttonset label:nth-child(8n),
		.customize-control-pearl-radio-image .image.ui-buttonset label[for='pearl_color_schemedefault-color']{
			margin-right: 0;
		}

		.customize-control-pearl-radio-image .image.ui-buttonset label span {
			display: block;
			line-height: 0;
		}

        .customize-control-pearl-radio-image .image.ui-buttonset label.ui-state-active {
            background: none;
        }

        .customize-control-pearl-radio-image .customize-control-radio-buttonset label {
            padding: 5px 10px;
            background: #f7f7f7;
            border-left: 1px solid #dedede;
            line-height: 35px;
        }

        #customize-controls .customize-control-pearl-radio-image label img {
            max-width: 62px;
            height: auto;
        }

        #customize-controls .customize-control-pearl-radio-image label img.default-color {
            max-width: 133px;
			min-height: 62px;
        }

        .customize-control-pearl-radio-image label.ui-state-active img {
            background: #dedede;
            border-color: #000;
        }

        .customize-control-pearl-radio-image label.ui-state-hover img {
            border-color: #999;
        }

        .customize-control-radio-buttonset label.ui-corner-left {
            border-radius: 3px 0 0 3px;
            border-left: 0;
        }

        .customize-control-radio-buttonset label.ui-corner-right {
            border-radius: 0 3px 3px 0;
        }
    </style>
    <?php
}

add_action('customize_controls_print_styles', 'pearl_customizer_custom_control_css');
