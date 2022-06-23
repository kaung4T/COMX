<?php
$newsup_background_image = get_theme_support( 'custom-header', 'default-image' );

if ( has_header_image() ) {
  $newsup_background_image = get_header_image();
}
?>
<div class="mg-breadcrumb-section" style='background: url("<?php echo esc_url( $newsup_background_image ); ?>" ) repeat scroll center 0 #143745;'>
<?php $newsup_remove_header_image_overlay = get_theme_mods('remove_header_image_overlay',true);
if($newsup_remove_header_image_overlay == true){ ?>
  <div class="overlay">
<?php } ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 col-sm-12">
			    <div class="mg-breadcrumb-title">
            <?php
            if( class_exists( 'WooCommerce' ) && is_shop() ) { ?>
            <h1>
            <?php woocommerce_page_title(); ?>
            </h1>
            <?php    
          }
          elseif(is_archive()) {
          the_archive_title( '<h1>', '</h1>' );
          the_archive_description( '<div class="archive-description">', '</div>' );
          } else { ?>
          <h1><?php the_title(); ?></h1>
         <?php } ?>
          </div>
        </div>
      </div>
    </div>
  <?php $newsup_remove_header_image_overlay = get_theme_mods('remove_header_image_overlay',true);
if($newsup_remove_header_image_overlay == true){ ?>
  </div>
<?php } ?>
</div>
<div class="clearfix"></div>