<?php
$timetable_heading = get_option('pearl_timetable_heading');
$days_1 = get_option('pearl_timetable_days_1');
$days_time_1 = get_option('pearl_timetable_days_time_1');
$days_2 = get_option('pearl_timetable_days_2');
$days_time_2 = get_option('pearl_timetable_days_time_2');
$days_3 = get_option('pearl_timetable_days_3');
$days_time_3 = get_option('pearl_timetable_days_time_3');
$icon_style = get_option('pearl_color_scheme');

if (empty($icon_style)) {
    $icon_style = 'default-color';
}
?>
<div class="container">
    <div class="time-table-sec">
        <ul id="accordion2" class="accordion2">
            <li>
                <ul class="submenu time-table">


                    <?php

                    if (!empty($timetable_heading)) {
                        echo '<li class="tit"><h5>' . sanitize_text_field($timetable_heading) . '</h5></li>';
                    }

                    if (!empty($days_1) && !empty($days_time_1)) {
                        echo '<li><span class="day">' . sanitize_text_field($days_1) . '</span> <span class="divider">-</span> <span class="time">' . sanitize_text_field($days_time_1) . '</span></li>';
                    }

                    if (!empty($days_2) && !empty($days_time_2)) {
                        echo '<li><span class="day">' . sanitize_text_field($days_2) . '</span> <span class="divider">-</span> <span class="time">' . sanitize_text_field($days_time_2) . '</span></li>';
                    }

                    if (!empty($days_3) && !empty($days_time_3)) {
                        echo '<li><span class="day">' . sanitize_text_field($days_3) . '</span> <span class="divider">-</span> <span class="time">' . sanitize_text_field($days_time_3) . '</span></li>';
                    }
                    ?>
                </ul>
                <div class="link">
                    <img class="time-tab" src="<?php echo get_template_directory_uri(); ?>/css/theme-colors/images/<?php echo 'timetable-menu-' . esc_attr($icon_style); ?>.png" alt="">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="21px" height="21px" class="pearl-accordion-clock"><path fill="white" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z" class=""></path></svg>
                </div>
            </li>
        </ul>
    </div>
</div>