<?php
	
	/*---------------------------First highlight color-------------------*/

	$vw_medical_care_first_color = get_theme_mod('vw_medical_care_first_color');

	$custom_css = '';

	if($vw_medical_care_first_color != false){
		$custom_css .='#topbar, .main-navigation ul.sub-menu li:hover, .view-more, .info, .location, #slider .carousel-control-prev-icon:hover, #slider .carousel-control-next-icon:hover, .scrollup i, input[type="submit"], .footer .tagcloud a:hover, .footer-2, .pagination span, .pagination a, .sidebar .tagcloud a:hover, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, nav.woocommerce-MyAccount-navigation ul li{';
			$custom_css .='background-color: '.esc_html($vw_medical_care_first_color).';';
		$custom_css .='}';
	}
	if($vw_medical_care_first_color != false){
		$custom_css .='#comments input[type="submit"].submit{';
			$custom_css .='background-color: '.esc_html($vw_medical_care_first_color).'!important;';
		$custom_css .='}';
	}
	if($vw_medical_care_first_color != false){
		$custom_css .='a, .main-navigation a:hover, .footer li a:hover, .post-main-box:hover h3, .post-navigation a:hover .post-title, .post-navigation a:focus .post-title, .entry-content a, .main-navigation .current_page_item > a, .main-navigation .current-menu-item > a{';
			$custom_css .='color: '.esc_html($vw_medical_care_first_color).';';
		$custom_css .='}';
	}
	if($vw_medical_care_first_color != false){
		$custom_css .='#slider .carousel-control-prev-icon:hover, #slider .carousel-control-next-icon:hover{';
			$custom_css .='border-color: '.esc_html($vw_medical_care_first_color).';';
		$custom_css .='}';
	}
	if($vw_medical_care_first_color != false){
		$custom_css .='.post-info hr{';
			$custom_css .='border-top-color: '.esc_html($vw_medical_care_first_color).';';
		$custom_css .='}';
	}
	if($vw_medical_care_first_color != false){
		$custom_css .='.main-header{';
			$custom_css .='border-bottom-color: '.esc_html($vw_medical_care_first_color).';';
		$custom_css .='}';
	}
	if($vw_medical_care_first_color != false){
		$custom_css .='.post-main-box, .sidebar .widget{
		box-shadow: 0px 15px 10px -15px '.esc_html($vw_medical_care_first_color).';
		}';
	}

	/*---------------------------Width Layout -------------------*/

	$theme_lay = get_theme_mod( 'vw_medical_care_width_option','Full Width');
    if($theme_lay == 'Boxed'){
		$custom_css .='body{';
			$custom_css .='max-width: 1140px; width: 100%; padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto;';
		$custom_css .='}';
	}else if($theme_lay == 'Wide Width'){
		$custom_css .='body{';
			$custom_css .='width: 100%;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;';
		$custom_css .='}';
	}else if($theme_lay == 'Full Width'){
		$custom_css .='body{';
			$custom_css .='max-width: 100%;';
		$custom_css .='}';
	}

	/*--------------------------- Slider Opacity -------------------*/

	$theme_lay = get_theme_mod( 'vw_medical_care_slider_opacity_color','0.5');
	if($theme_lay == '0'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0';
		$custom_css .='}';
		}else if($theme_lay == '0.1'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.1';
		$custom_css .='}';
		}else if($theme_lay == '0.2'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.2';
		$custom_css .='}';
		}else if($theme_lay == '0.3'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.3';
		$custom_css .='}';
		}else if($theme_lay == '0.4'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.4';
		$custom_css .='}';
		}else if($theme_lay == '0.5'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.5';
		$custom_css .='}';
		}else if($theme_lay == '0.6'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.6';
		$custom_css .='}';
		}else if($theme_lay == '0.7'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.7';
		$custom_css .='}';
		}else if($theme_lay == '0.8'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.8';
		$custom_css .='}';
		}else if($theme_lay == '0.9'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.9';
		$custom_css .='}';
		}

	/*---------------------------Slider Content Layout -------------------*/

	$theme_lay = get_theme_mod( 'vw_medical_care_slider_content_option','Left');
    if($theme_lay == 'Left'){
		$custom_css .='#slider .carousel-caption, #slider .inner_carousel, #slider .inner_carousel h2{';
			$custom_css .='text-align:left; left:10%; right:40%;';
		$custom_css .='}';
	}else if($theme_lay == 'Center'){
		$custom_css .='#slider .carousel-caption, #slider .inner_carousel, #slider .inner_carousel h2{';
			$custom_css .='text-align:center; left:20%; right:20%;';
		$custom_css .='}';
	}else if($theme_lay == 'Right'){
		$custom_css .='#slider .carousel-caption, #slider .inner_carousel, #slider .inner_carousel h2{';
			$custom_css .='text-align:right; left:40%; right:10%;';
		$custom_css .='}';
	}