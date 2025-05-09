<?php
/**
 * The template part for top header
 *
 * @package VW Medical Care 
 * @subpackage vw_medical_care
 * @since VW Medical Care 1.0
 */
?>

<div id="topbar">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-md-6">
        <?php if( get_theme_mod( 'vw_medical_care_header_text') != '') { ?>
          <p><?php echo esc_html(get_theme_mod('vw_medical_care_header_text',''));?></p>
        <?php }?>
      </div>      
      <div class="col-lg-5 col-md-5">
        <?php dynamic_sidebar('social-links'); ?>
      </div>
      <div class="col-lg-1 col-md-1">
        <?php if( get_theme_mod( 'vw_medical_care_header_search') != '') { ?>
          <div class="search-box">
            <span><i class="fas fa-search"></i></span>
          </div>
        <?php }?>
      </div>
      <div class="serach_outer">
        <div class="closepop"><i class="far fa-window-close"></i></div>
        <div class="serach_inner">
          <?php get_search_form(); ?>
        </div>
      </div>
    </div>
  </div>
</div>