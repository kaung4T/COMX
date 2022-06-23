<?php
if (!function_exists('newsup_header_section')) :
/**
 *  Slider
 *
 * @since Newsup
 *
 */
function newsup_header_section()
{
?>
<div class="mg-head-detail hidden-xs">
    <div class="container-fluid">
        <div class="row">
            <?php
            $header_data_enable = esc_attr(get_theme_mod('header_data_enable','true'));
            $header_time_enable = esc_attr(get_theme_mod('header_time_enable','true'));
            $header_social_icon_enable = esc_attr(get_theme_mod('header_social_icon_enable','true'));
            $newsup_header_fb_link = get_theme_mod('newsup_header_fb_link');
            $newsup_header_fb_target = esc_attr(get_theme_mod('newsup_header_fb_target','true'));
            $newsup_header_twt_link = get_theme_mod('newsup_header_twt_link');
            $newsup_header_twt_target = esc_attr(get_theme_mod('newsup_header_twt_target','true'));
            $newsup_header_lnkd_link = get_theme_mod('newsup_header_lnkd_link');
            $newsup_header_lnkd_target = esc_attr(get_theme_mod('newsup_header_lnkd_target','true'));
            $newsup_header_insta_link = get_theme_mod('newsup_header_insta_link');
            $newsup_insta_insta_target = esc_attr(get_theme_mod('newsup_insta_insta_target','true'));
            $newsup_header_youtube_link = get_theme_mod('newsup_header_youtube_link');
            $newsup_header_youtube_target = esc_attr(get_theme_mod('newsup_header_youtube_target','true'));
            $newsup_header_pintrest_link = get_theme_mod('newsup_header_pintrest_link');
            $newsup_header_pintrest_target = esc_attr(get_theme_mod('newsup_header_pintrest_target','true')); ?>
            <div class="col-md-6 col-xs-12 col-sm-6">
            <?php if($header_data_enable == true)
            { ?>
                <ul class="info-left">
                    <li><?php
                        echo date_i18n('D. M jS, Y ', strtotime(current_time("Y-m-d"))); 
                        if($header_time_enable == true) { ?>
                        <span  id="time" class="time"></span>
                      <?php } ?>
                    </li>
                </ul>
            <?php } ?>  
            </div>
            <?php 
            if($header_social_icon_enable == true)
            {
            ?>
            <div class="col-md-6 col-xs-12">
                <ul class="mg-social info-right">
                    
                      <?php if($newsup_header_fb_link !=''){?>
                      <li><span class="icon-soci facebook"><a <?php if($newsup_header_fb_target) { ?> target="_blank" <?php } ?>href="<?php echo esc_url($newsup_header_fb_link); ?>"><i class="fa fa-facebook"></i></a></span> </li>
                      <?php } if($newsup_header_twt_link !=''){ ?>
                      <li><span class="icon-soci twitter"><a <?php if($newsup_header_twt_target) { ?>target="_blank" <?php } ?>href="<?php echo esc_url($newsup_header_twt_link);?>"><i class="fa fa-twitter"></i></a></span></li>
                      <?php } if($newsup_header_lnkd_link !=''){ ?>
                      <li><span class="icon-soci linkedin"><a <?php if($newsup_header_lnkd_target) { ?>target="_blank" <?php } ?> href="<?php echo esc_url($newsup_header_lnkd_link); ?>"><i class="fa fa-linkedin"></i></a></span></li>
                      <?php } 
                      if($newsup_header_insta_link !=''){ ?>
                      <li><span class="icon-soci instagram"><a <?php if($newsup_insta_insta_target) { ?>target="_blank" <?php } ?> href="<?php echo esc_url($newsup_header_insta_link); ?>"><i class="fa fa-instagram"></i></a></span></li>
                      <?php }
                      if($newsup_header_youtube_link !=''){ ?>
                      <li><span class="icon-soci youtube"><a <?php if($newsup_header_youtube_target) { ?>target="_blank" <?php } ?> href="<?php echo esc_url($newsup_header_youtube_link); ?>"><i class="fa fa-youtube"></i></a></span></li>
                      <?php }  if($newsup_header_pintrest_link !=''){ ?>
                      <li><span class="icon-soci pinterest"><a <?php if($newsup_header_pintrest_target) { ?>target="_blank" <?php } ?> href="<?php echo esc_url($newsup_header_pintrest_link); ?>"><i class="fa fa-pinterest-p"></i></a></span></li>
                      <?php } ?>
                </ul>
            </div>
            <?php }?>
        </div>
    </div>
</div>
<?php 
}
endif;
add_action('newsup_action_header_section', 'newsup_header_section', 5);