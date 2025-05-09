<div id="header-1">
    <!--Start Top Bar-->
    <?php

    $header_top_bar = get_option('pearl_header_top_bar');
    $sticky_header = get_option('pearl_sticky_header');

    if ($header_top_bar == 'true') {
        get_template_part('layout/header/top-bar');
    }
    ?>
    <!--Top Bar End-->


    <header class="header" <?php echo ($sticky_header) ? 'id="stikcy-header"' : ''; ?>>
        <div class="container">
            <div class="row">

                <?php get_template_part('layout/header/logo'); ?>
                <div class="col-md-9">
                    <nav class="menu-2">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'main-menu',
                            'container' => false,
                            'menu_class' => 'nav plus-menu'
                        ));
                        ?>
                    </nav>
                </div>

            </div><!-- end .row -->
        </div><!-- end .container -->
    </header>

</div><!-- end #header-1 -->