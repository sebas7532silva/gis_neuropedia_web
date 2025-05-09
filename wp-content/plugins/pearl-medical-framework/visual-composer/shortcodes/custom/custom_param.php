<?php
if( ! function_exists( 'pearl_clone_one_settings_field' ) ) {
    function pearl_clone_one_settings_field($settings, $value) {
        $skills = explode( '*', $value );
        $fields = '';

        $skills = array_filter($skills);
        if(!empty($skills) && is_array($skills)){
            $fields .= '<div class="clone_one_block_wrap"><div class="clone_one_block">';
            foreach($skills as $skill){
                $fields .= '<div class="input_field">'
                    .'<input name="'.$settings['param_name'].'" class="text_clone_one fajar_clone wpb_vc_param_value wpb-textinput '
                    .$settings['param_name'].' '.$settings['type'].'_field" type="text" value="'
                    .$skill.'"/>'
                    .'<button type="button" class="vc_btn remove_clone_one vc_btn-primary">-</button>'
                    .'</div>';
            }

            $fields .= '<button type="button" class="vc_btn add_clone_one vc_btn-primary">'. esc_html__('Add New Field') .'</button>'
                .'</div></div>';


        }else{
            $fields = '<div class="clone_one_block_wrap"><div class="clone_one_block">'
                .'<div class="input_field">'
                .'<input name="'.$settings['param_name'].'" class="text_clone_one fajar_clone wpb_vc_param_value wpb-textinput '
                .$settings['param_name'].' '.$settings['type'].'_field" type="text" value="'
                .$value.'"/>'
                .'<button type="button" class="vc_btn remove_clone_one vc_btn-primary">-</button>'
                .'</div>'
                .'<button type="button" class="vc_btn add_clone_one vc_btn-primary">'. esc_html__('Add New Field') .'</button>'
                .'</div></div>';
        }

        return $fields;
    }

    vc_add_shortcode_param(
        'pearl_clone_one',
        'pearl_clone_one_settings_field',
        plugin_dir_url( __FILE__ ) . 'call-back.js'
    );
}