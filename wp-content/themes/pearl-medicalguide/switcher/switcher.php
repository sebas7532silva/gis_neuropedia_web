<div class="color-switcher" id="choose_color">
	<a class="picker_close">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="28px" height="28px" class="pearl-cog rotating">
			<path fill="white" d="M487.4 315.7l-42.6-24.6c4.3-23.2 4.3-47 0-70.2l42.6-24.6c4.9-2.8 7.1-8.6 5.5-14-11.1-35.6-30-67.8-54.7-94.6-3.8-4.1-10-5.1-14.8-2.3L380.8 110c-17.9-15.4-38.5-27.3-60.8-35.1V25.8c0-5.6-3.9-10.5-9.4-11.7-36.7-8.2-74.3-7.8-109.2 0-5.5 1.2-9.4 6.1-9.4 11.7V75c-22.2 7.9-42.8 19.8-60.8 35.1L88.7 85.5c-4.9-2.8-11-1.9-14.8 2.3-24.7 26.7-43.6 58.9-54.7 94.6-1.7 5.4.6 11.2 5.5 14L67.3 221c-4.3 23.2-4.3 47 0 70.2l-42.6 24.6c-4.9 2.8-7.1 8.6-5.5 14 11.1 35.6 30 67.8 54.7 94.6 3.8 4.1 10 5.1 14.8 2.3l42.6-24.6c17.9 15.4 38.5 27.3 60.8 35.1v49.2c0 5.6 3.9 10.5 9.4 11.7 36.7 8.2 74.3 7.8 109.2 0 5.5-1.2 9.4-6.1 9.4-11.7v-49.2c22.2-7.9 42.8-19.8 60.8-35.1l42.6 24.6c4.9 2.8 11 1.9 14.8-2.3 24.7-26.7 43.6-58.9 54.7-94.6 1.5-5.5-.7-11.3-5.6-14.1zM256 336c-44.1 0-80-35.9-80-80s35.9-80 80-80 80 35.9 80 80-35.9 80-80 80z"></path>
		</svg>
	</a>
	<h5>STYLE SWITCHER</h5>
	<div class="theme-colours">
		<p>Choose Colour style</p>
		<ul>
			<li><a class="light-blue" id="light-blue"></a></li>
			<li><a class="red" id="red"></a></li>
			<li><a class="green" id="green"></a></li>
			<li><a class="light-green" id="light-green"></a></li>
			<li><a class="dark-blue" id="dark-blue"></a></li>
			<li><a class="orange" id="orange"></a></li>
			<li><a class="yellow" id="yellow"></a></li>
			<li><a class="pink" id="pink"></a></li>
			<li><a class="purple" id="purple"></a></li>
			<li><a class="brown" id="brown"></a></li>
		</ul>
	</div>

	<div class="choose-header">
		<p>Choose Header</p>
		<?php
		$url = get_permalink();

		if ( is_home() ) {
			$page_for_posts = get_option( 'page_for_posts' );
			$url            = get_permalink( $page_for_posts );
		} else if ( is_woocommerce_activated() && is_shop() ) {
			$shop_page = get_option( 'woocommerce_shop_page_id' );
			$url       = get_permalink( $shop_page );
		}
		?>
		<a href="<?php echo esc_url( remove_query_arg( array( 'header' ), esc_url( $url ) ) ); ?>" id="header-one">One</a>
		<a href="<?php echo esc_url( add_query_arg( array( 'header' => 'simple' ), esc_url( $url ) ) ); ?>" id="header-two">Two</a>
	</div>
	<div class="choose-footer">
		<p>Choose Footer</p>
		<a id="dark">Dark</a>
		<a id="light">Light</a>
	</div>

	<div class="clr"></div>
</div>

<script>
    // Demo Source Switcher
    jQuery(document).ready(function ($) {

        var linkTarget = jQuery("#color-css");

        jQuery("#default-color").click(function () {
            linkTarget.attr("href", "<?php echo get_template_directory_uri() . '/'; ?>css/default-color.css");
            jQuery(".link img").attr("src", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/images/timetable-menu-brown.png");
            return false;
        });

        jQuery("#brown").click(function () {
            linkTarget.attr("href", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/brown.css");
            jQuery(".link img.time-tab").attr("src", "<?php echo get_template_directory_uri(); ?>/css/theme-colors/images/timetable-menu-brown.png");
            jQuery("img.logo-image").attr("src", "<?php echo get_template_directory_uri(); ?>/switcher/img/brown.png").attr("srcset", "<?php echo get_template_directory_uri(); ?>/switcher/img/brown.png");
            return false;
        });

        jQuery("#pink").click(function () {
            linkTarget.attr("href", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/pink.css");
            jQuery(".link img.time-tab").attr("src", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/images/timetable-menu-pink.png");
            jQuery("img.logo-image").attr("src", "<?php echo get_template_directory_uri(); ?>/switcher/img/pink.png").attr("srcset", "<?php echo get_template_directory_uri(); ?>/switcher/img/pink.png");
            return false;
        });

        jQuery("#dark-blue").click(function () {
            linkTarget.attr("href", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/dark-blue.css");
            jQuery(".link img.time-tab").attr("src", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/images/timetable-menu-dark-blue.png");
            jQuery("img.logo-image").attr("src", "<?php echo get_template_directory_uri(); ?>/switcher/img/dark-blue.png").attr("srcset", "<?php echo get_template_directory_uri(); ?>/switcher/img/dark-blue.png");
            return false;
        });


        jQuery("#green").click(function () {
            linkTarget.attr("href", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/green.css");
            jQuery(".link img.time-tab").attr("src", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/images/timetable-menu-green.png");
            jQuery("img.logo-image").attr("src", "<?php echo get_template_directory_uri(); ?>/switcher/img/green.png").attr("srcset", "<?php echo get_template_directory_uri(); ?>/switcher/img/green.png");
            return false;
        });

        jQuery("#light-green").click(function () {
            linkTarget.attr("href", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/light-green.css");
            jQuery(".link img.time-tab").attr("src", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/images/timetable-menu-light-green.png");
            jQuery("img.logo-image").attr("src", "<?php echo get_template_directory_uri(); ?>/switcher/img/light-green.png").attr("srcset", "<?php echo get_template_directory_uri(); ?>/switcher/img/light-green.png");
            return false;
        });


        jQuery("#orange").click(function () {
            linkTarget.attr("href", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/orange.css");
            jQuery(".link img.time-tab").attr("src", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/images/timetable-menu-orange.png");
            jQuery("img.logo-image").attr("src", "<?php echo get_template_directory_uri(); ?>/switcher/img/orange.png").attr("srcset", "<?php echo get_template_directory_uri(); ?>/switcher/img/orange.png");
            return false;
        });

        jQuery("#light-blue").click(function () {
            linkTarget.attr("href", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/light-blue.css");
            jQuery(".link img.time-tab").attr("src", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/images/timetable-menu-light-blue.png");
            jQuery("img.logo-image").attr("src", "<?php echo get_template_directory_uri(); ?>/switcher/img/light-blue.png").attr("srcset", "<?php echo get_template_directory_uri(); ?>/switcher/img/light-blue.png");
            return false;
        });

        jQuery("#purple").click(function () {
            linkTarget.attr("href", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/purple.css");
            jQuery(".link img.time-tab").attr("src", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/images/timetable-menu-purple.png");
            jQuery("img.logo-image").attr("src", "<?php echo get_template_directory_uri(); ?>/switcher/img/purple.png").attr("srcset", "<?php echo get_template_directory_uri(); ?>/switcher/img/purple.png");
            return false;
        });

        jQuery("#red").click(function () {
            linkTarget.attr("href", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/red.css");
            jQuery(".link img.time-tab").attr("src", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/images/timetable-menu-red.png");
            jQuery("img.logo-image").attr("src", "<?php echo get_template_directory_uri(); ?>/switcher/img/red.png").attr("srcset", "<?php echo get_template_directory_uri(); ?>/switcher/img/red.png");
            return false;
        });

        jQuery("#yellow").click(function () {
            linkTarget.attr("href", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/yellow.css");
            jQuery(".link img.time-tab").attr("src", "<?php echo get_template_directory_uri() . '/'; ?>css/theme-colors/images/timetable-menu-yellow.png");
            jQuery("img.logo-image").attr("src", "<?php echo get_template_directory_uri(); ?>/switcher/img/yellow.png").attr("srcset", "<?php echo get_template_directory_uri(); ?>/switcher/img/yellow.png");
            return false;
        });


        jQuery("#light").click(function () {
            jQuery("#footer").addClass("footer-light");
            jQuery("#footer").removeClass("footer");
//jQuery("#footer img" ).attr("src", "images/footer-logo.jpg");
        });
        jQuery("#dark").click(function () {
            jQuery("#footer").addClass("footer");
            jQuery("#footer").removeClass("footer-light");
//jQuery("#footer img" ).attr("src", "images/footer-logo-dark.jpg");
        });

//        jQuery("#header-one").click(function(){
//            jQuery("#header-1").show();
//            jQuery("#header-2").hide();
//        });
//        jQuery("#header-two").click(function(){
//            jQuery("#header-2").show();
//            jQuery("#header-1").hide();
//        });


// picker buttton
        jQuery(".picker_close").click(function () {

            jQuery(this).find('svg').toggleClass('rotating');
            jQuery("#choose_color").toggleClass("position");

        });


    });
</script>