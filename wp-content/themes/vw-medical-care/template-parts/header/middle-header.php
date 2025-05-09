<?php
/**
 * The template part for header
 *
 * @package VW Medical Care 
 * @subpackage vw_medical_care
 * @since VW Medical Care 1.0
 */
?>

<div class="main-header">
  <div class="container">
    <div class="row m-0">      
      <div class="col-lg-3 col-md-3 col-6">
        <div class="logo">
          <?php if( has_custom_logo() ){ vw_medical_care_the_custom_logo();
            }else{ ?>
              <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?><span class="screen-reader-text"><?php the_title(); ?></span></a></h1>
              <?php $description = get_bloginfo( 'description', 'display' );
              if ( $description || is_customize_preview() ) : ?>
              <p class="site-description"><?php echo esc_html($description); ?></p>            
          <?php endif; } ?>
        </div>
      </div>
      <div class="col-lg-9 col-md-9 col-6">
        <?php get_template_part( 'template-parts/header/navigation' ); ?>
      </div>
    </div>
  </div>
</div>