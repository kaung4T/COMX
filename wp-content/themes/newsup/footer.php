<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package Newsup
 */
$you_missed_enable = esc_attr(get_theme_mod('you_missed_enable','true'));
            if($you_missed_enable == true){
?>
  <div class="container-fluid mr-bot40 mg-posts-sec-inner">
        <div class="missed-inner">
        <div class="row">
            <?php $you_missed_title = get_theme_mod('you_missed_title', esc_html('You missed','newsup'));
            if($you_missed_title) { ?>
            <div class="col-md-12">
                <div class="mg-sec-title">
                    <!-- mg-sec-title -->
                    <h4><?php echo esc_html($you_missed_title); ?></h4>
                </div>
            </div>
            <?php } 
            $newsup_you_missed_loop = new WP_Query(array( 'post_type' => 'post', 'posts_per_page' => 4, 'order' => 'DESC',  'ignore_sticky_posts' => true));
            if ( $newsup_you_missed_loop->have_posts() ) :
            while ( $newsup_you_missed_loop->have_posts() ) : $newsup_you_missed_loop->the_post(); 
            $url = newsup_get_freatured_image_url($post->ID, 'newsup-featured'); 
              ?>
                <!--col-md-3-->
                <div class="col-md-3 col-sm-6 pulse animated">
               <div class="mg-blog-post-3 minh back-img" 
                            <?php if(has_post_thumbnail()) { ?>
                            style="background-image: url('<?php echo esc_url($url); ?>');" <?php } ?>>
                    <div class="mg-blog-inner">
                      <div class="mg-blog-category">
                      <?php newsup_post_categories(); ?>
                      </div>
                      <h4 class="title"> <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute( array('before' => 'Permalink to: ','after'  => '') ); ?>"> <?php the_title(); ?></a> </h4>
                      <?php newsup_post_meta(); ?>
                    </div>
                </div>
            </div>
            <!--/col-md-3-->
         <?php endwhile; endif; wp_reset_postdata(); ?>
            

                </div>
            </div>
        </div>
<?php } ?>
<!--==================== FOOTER AREA ====================-->
    <?php $newsup_footer_widget_background = get_theme_mod('newsup_footer_widget_background');
    $newsup_footer_overlay_color = get_theme_mod('newsup_footer_overlay_color'); 
   if($newsup_footer_widget_background != '') { ?>
    <footer style="background-image:url('<?php echo esc_url($newsup_footer_widget_background);?>');">
     <?php } else { ?>
    <footer> 
    <?php } ?>
        <div class="overlay" style="background-color: <?php echo esc_attr($newsup_footer_overlay_color);?>;">
                <!--Start mg-footer-widget-area-->
                 <?php if ( is_active_sidebar( 'footer_widget_area' ) ) { ?>
                <div class="mg-footer-widget-area">
                    <div class="container-fluid">
                        <div class="row">
                          <?php  dynamic_sidebar( 'footer_widget_area' ); ?>
                        </div>
                        <!--/row-->
                    </div>
                    <!--/container-->
                </div>
                 <?php } ?>
                <!--End mg-footer-widget-area-->
                <!--Start mg-footer-widget-area-->
                <div class="mg-footer-bottom-area">
                    <div class="container-fluid">
                        <div class="divide-line"></div>
                        <div class="row">
                            <!--col-md-4-->
                            <div class="col-md-6">
                               <?php the_custom_logo(); 
                               if (display_header_text()) : ?>
                              <div class="site-branding-text">
                              <h1 class="site-title"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                              <p class="site-description"><?php bloginfo('description'); ?></p>
                              </div>
                              <?php endif; ?>
                            </div>

                             <?php 
                              $footer_social_icon_enable = esc_attr(get_theme_mod('footer_social_icon_enable','true'));
                              if($footer_social_icon_enable == true)
                              {
                              $newsup_footer_fb_link = get_theme_mod('newsup_footer_fb_link');
                              $newsup_footer_fb_target = esc_attr(get_theme_mod('newsup_footer_fb_target','true'));
                              $newsup_footer_twt_link = get_theme_mod('newsup_footer_twt_link');
                              $newsup_footer_twt_target = esc_attr(get_theme_mod('newsup_footer_twt_target','true'));
                              $newsup_footer_lnkd_link = get_theme_mod('newsup_footer_lnkd_link');
                              $newsup_footer_lnkd_target = esc_attr(get_theme_mod('newsup_footer_lnkd_target','true'));
                              $newsup_footer_insta_link = get_theme_mod('newsup_footer_insta_link');
                              $newsup_footer_insta_target = esc_attr(get_theme_mod('newsup_footer_insta_target','true'));
                              $newsup_footer_youtube_link = get_theme_mod('newsup_footer_youtube_link');
                              $newsup_footer_youtube_target = esc_attr(get_theme_mod('newsup_footer_youtube_target','true'));
                              $newsup_footer_pinterest_link = get_theme_mod('newsup_footer_pinterest_link');
                              $newsup_footer_pinterest_target = esc_attr(get_theme_mod('newsup_footer_pinterest_target','true'));
                              ?>

                            <div class="col-md-6 text-right text-xs">
                                
                            <ul class="mg-social">
                                    <?php if($newsup_footer_fb_link !=''){?>
                                    <li><span class="icon-soci facebook"><a <?php if($newsup_footer_fb_target) { ?> target="_blank" <?php } ?>href="<?php echo esc_url($newsup_footer_fb_link); ?>"><i class="fa fa-facebook"></i></a></span> </li>
                                    <?php } if($newsup_footer_twt_link !=''){ ?>
                                    <li><span class="icon-soci twitter"><a <?php if($newsup_footer_twt_target) { ?>target="_blank" <?php } ?>href="<?php echo esc_url($newsup_footer_twt_link);?>"><i class="fa fa-twitter"></i></a></span></li>
                                    <?php } if($newsup_footer_lnkd_link !=''){ ?>
                                    <li><span class="icon-soci linkedin"><a <?php if($newsup_footer_lnkd_target) { ?>target="_blank" <?php } ?> href="<?php echo esc_url($newsup_footer_lnkd_link); ?>"><i class="fa fa-linkedin"></i></a></span></li>
                                    <?php } 
                                    if($newsup_footer_insta_link !=''){ ?>
                                    <li><span class="icon-soci instagram"><a <?php if($newsup_footer_insta_target) { ?>target="_blank" <?php } ?> href="<?php echo esc_url($newsup_footer_insta_link); ?>"><i class="fa fa-instagram"></i></a></span></li>
                                    <?php }
                                    if($newsup_footer_youtube_link !=''){ ?>
                                    <li><span class="icon-soci youtube"><a <?php if($newsup_footer_youtube_target) { ?>target="_blank" <?php } ?> href="<?php echo esc_url($newsup_footer_youtube_link); ?>"><i class="fa fa-youtube"></i></a></span></li>
                                    <?php } 
                                    if($newsup_footer_pinterest_link !=''){ ?>
                                    <li><span class="icon-soci pinterest"><a <?php if($newsup_footer_pinterest_target) { ?>target="_blank" <?php } ?> href="<?php echo esc_url($newsup_footer_pinterest_link); ?>"><i class="fa fa-pinterest-p"></i></a></span></li>
                                    <?php } ?>
                             </ul>


                            </div>
                            <!--/col-md-4-->  
                            <?php } ?> 
                        </div>
                        <!--/row-->
                    </div>
                    <!--/container-->
                </div>
                <!--End mg-footer-widget-area-->

                <div class="mg-footer-copyright">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6 text-xs">
                                <p>
                                <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'newsup' ) ); ?>">
								<?php
								/* translators: placeholder replaced with string */
								printf( esc_html__( 'Proudly powered by %s', 'newsup' ), 'WordPress' );
								?>
								</a>
								<span class="sep"> | </span>
								<?php
								/* translators: placeholder replaced with string */
								printf( esc_html__( 'Theme: %1$s by %2$s.', 'newsup' ), 'Newsup', '<a href="' . esc_url( __( 'https://themeansar.com/', 'newsup' ) ) . '" rel="designer">Themeansar</a>' );
								?>
								</p>
                            </div>



                            <div class="col-md-6 text-right text-xs">
                                <?php wp_nav_menu( array(
        								'theme_location' => 'footer',
        								'container'  => 'nav-collapse collapse navbar-inverse-collapse',
        								'menu_class' => 'info-right',
        								'fallback_cb' => 'newsup_fallback_page_menu',
        								'walker' => new newsup_nav_walker()
        							) ); 
        						?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/overlay-->
        </footer>
        <!--/footer-->
    </div>
    <!--/wrapper-->
    <!--Scroll To Top-->
    <a href="#" class="ta_upscr bounceInup animated"><i class="fa fa-angle-up"></i></a>
    <!--/Scroll To Top-->
<!-- /Scroll To Top -->
<?php wp_footer(); ?>
</body>
</html>