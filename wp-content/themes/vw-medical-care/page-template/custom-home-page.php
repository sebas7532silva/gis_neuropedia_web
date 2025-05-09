<?php
/**
 * Template Name: Custom Home Page
 */

get_header(); ?>

<main id="maincontent" role="main">
  <?php do_action( 'vw_medical_care_before_slider' ); ?>

  <?php if( get_theme_mod( 'vw_medical_care_slider_hide_show',true) != '') { ?>

  <section id="slider">
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel"> 
      <?php $slider_page = array();
        for ( $count = 1; $count <= 4; $count++ ) {
          $mod = intval( get_theme_mod( 'vw_medical_care_slider_page' . $count ));
          if ( 'page-none-selected' != $mod ) {
            $slider_page[] = $mod;
          }
        }
        if( !empty($slider_page) ) :
          $args = array(
            'post_type' => 'page',
            'post__in' => $slider_page,
            'orderby' => 'post__in'
          );
          $query = new WP_Query( $args );
          if ( $query->have_posts() ) :
            $i = 1;
      ?>     
      <div class="carousel-inner" role="listbox">
        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
          <div <?php if($i == 1){echo 'class="carousel-item active"';} else{ echo 'class="carousel-item"';}?>>
            <?php the_post_thumbnail(); ?>
            <div class="carousel-caption">
              <div class="inner_carousel">
                <h2><?php the_title(); ?></h2>
                <p><?php $excerpt = get_the_excerpt(); echo esc_html( vw_medical_care_string_limit_words( $excerpt, esc_attr(get_theme_mod('vw_medical_care_slider_excerpt_number','30')))); ?></p>
                <div class="more-btn">
                  <a class="view-more" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e( 'READ MORE', 'vw-medical-care' ); ?><i class="fa fa-angle-right"></i><span class="screen-reader-text"><?php esc_html_e( 'READ MORE','vw-medical-care' );?></span></a>
                </div>
              </div>
            </div>
          </div>
        <?php $i++; endwhile; 
        wp_reset_postdata();?>
      </div>
      <?php else : ?>
          <div class="no-postfound"></div>
      <?php endif;
      endif;?>
      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"><i class="fas fa-chevron-left"></i></span>
        <span class="screen-reader-text"><?php esc_attr_e( 'Previous','vw-medical-care' );?></span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
        <span class="screen-reader-text"><?php esc_attr_e( 'Next','vw-medical-care' );?></span>
      </a>
    </div>
    <div class="clearfix"></div>
  </section>

  <?php } ?>

  <?php do_action( 'vw_medical_care_after_slider' ); ?>

  <?php if( get_theme_mod( 'vw_medical_care_call_text') != '' || get_theme_mod( 'vw_medical_care_call') != '' || get_theme_mod( 'vw_medical_care_address_text') != '' || get_theme_mod( 'vw_medical_care_address') != '' || get_theme_mod( 'vw_medical_care_email_text') != '' || get_theme_mod( 'vw_medical_care_email_text') != '') { ?>
    <section id="contact-sec">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-md-4 p-0">
            <div class="info">
              <?php if( get_theme_mod( 'vw_medical_care_call_text') != '' || get_theme_mod( 'vw_medical_care_call') != '') { ?>
                <i class="fas fa-phone"></i><span><?php echo esc_html(get_theme_mod('vw_medical_care_call_text',''));?></span>
                <hr>
                <p><?php echo esc_html(get_theme_mod('vw_medical_care_call',''));?></p>
              <?php }?>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 p-0 loc-box">
            <div class="location">
              <?php if( get_theme_mod( 'vw_medical_care_address_text') != '' || get_theme_mod( 'vw_medical_care_address') != '') { ?>
                <i class="fas fa-map-marker-alt"></i><br>
                <span><?php echo esc_html(get_theme_mod('vw_medical_care_address_text',''));?></span>
                <hr>
                <p><?php echo esc_html(get_theme_mod('vw_medical_care_address',''));?></p>
              <?php }?>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 p-0">
            <div class="info">
              <?php if( get_theme_mod( 'vw_medical_care_email_text') != '' || get_theme_mod( 'vw_medical_care_email') != '') { ?>
                <i class="fas fa-envelope-open"></i><span><?php echo esc_html(get_theme_mod('vw_medical_care_email_text',''));?></span>
                <hr>
                <p><?php echo esc_html(get_theme_mod('vw_medical_care_email',''));?></p>
              <?php }?>
            </div>
          </div>
        </div>
      </div>
    </section>
  <?php } ?>

  <?php do_action( 'vw_medical_care_after_contact' ); ?>

  <section id="serv-section">
    <div class="container">
      <div class="row m-0">
        <?php
          $catData =  get_theme_mod('vw_medical_care_facilities','');
          if($catData){
          $page_query = new WP_Query(array( 'category_name' => esc_html($catData,'vw-medical-care'))); ?>
          <?php while( $page_query->have_posts() ) : $page_query->the_post(); ?>
          <div class="col-lg-4 col-md-6">
            <div class="serv-box">
              <?php the_post_thumbnail(); ?>
              <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?><span class="screen-reader-text"><?php the_title(); ?></span></a></h4>
              <p><?php $excerpt = get_the_excerpt(); echo esc_html( vw_medical_care_string_limit_words( $excerpt, esc_attr(get_theme_mod('vw_medical_care_facilities_excerpt_number','30')))); ?></p>
            </div>
          </div>
          <?php endwhile;
          wp_reset_postdata();
        } ?>
      </div>
    </div>
  </section>

  <?php do_action( 'vw_medical_care_after_services' ); ?>

  <div class="content-vw">
    <div class="container">
      <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
      <?php endwhile; // end of the loop. ?>
    </div>
  </div>
</main>

<?php get_footer(); ?>
