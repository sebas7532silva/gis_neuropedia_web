<?php
if (!class_exists('Pearl_Separator_Control'))
    return NULL;

/**
 * Class Pearl_Separator_Control
 *
 * Custom control to display separator
 */
class Pearl_Separator_Control extends WP_Customize_Control
{
    public function render_content()
    {
        ?>
        <label>
            <br>
            <hr>
            <br>
        </label>
        <?php
    }
}
