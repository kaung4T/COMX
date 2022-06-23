<?php 
/**
PHP functions & Hooks:
Theme URL: https://wordpress.org/themes/newsphere/, 
http://afthemes.com/, (C) 2020 AF Themes, GPLv2
*/
if (!function_exists('newsup_banner_trending_posts')):
    /**
     *
     * @since newsup 1.0.0
     *
     */
    function newsup_banner_exclusive_posts()  { 
            if (is_front_page() || is_home()) {
                $show_flash_news_section = newsup_get_option('show_flash_news_section');
            if ($show_flash_news_section): 
        ?>
            <section class="mg-latest-news-sec">
                <?php
                $category = newsup_get_option('select_flash_news_category');
                $number_of_posts = newsup_get_option('number_of_flash_news');
                $newsup_ticker_news_title = newsup_get_option('flash_news_title');

                $all_posts = newsup_get_posts($number_of_posts, $category);
                $show_trending = true;
                $count = 1;
                ?>
                <div class="container-fluid">
                    <div class="mg-latest-news">
                         <div class="bn_title">
                            <h2>
                                <?php if (!empty($newsup_ticker_news_title)): ?>
                                    <?php echo esc_html($newsup_ticker_news_title); ?><span></span>
                                <?php endif; ?>
                            </h2>
                        </div>
                        <?php if(is_rtl()){ ?> 
                        <div class="mg-latest-news-slider marquee" data-direction='right' dir="ltr">
                        <?php } else { ?> 
                        <div class="mg-latest-news-slider marquee">
                        <?php } ?>
                            <?php
                            if ($all_posts->have_posts()) :
                                while ($all_posts->have_posts()) : $all_posts->the_post();
                                    ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <span><?php the_title(); ?></span>
                                     </a>
                                    <?php
                                    $count++;
                                endwhile;
                                endif;
                                wp_reset_postdata();
                                ?>
                        </div>
                    </div>
            </div>
            </section>
            <!-- Excluive line END -->
        <?php endif;
         }
    }
endif;
add_action('newsup_action_banner_exclusive_posts', 'newsup_banner_exclusive_posts', 10);


//Banner Tabed Section
if (!function_exists('newsup_banner_tabbed_posts')):
    /**
     *
     * @since Newsup 1.0.0
     *
     */
    function newsup_banner_tabbed_posts()
    {
        
            $show_excerpt = 'false';
            $excerpt_length = '20';
            $number_of_posts = '4';

            $enable_categorised_tab = 'true';
            $latest_title = newsup_get_option('latest_tab_title');
            $popular_title = newsup_get_option('popular_tab_title');
            $categorised_title = newsup_get_option('trending_tab_title');
            $category = newsup_get_option('select_trending_tab_news_category');
            $tab_id = 'tan-main-banner-latest-trending-popular'
            ?>
            <div class="col-md-4 top-right-area">
                    <div id="exTab2" >
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#<?php echo esc_attr($tab_id); ?>-recent"
                               aria-controls="<?php esc_attr_e('Recent', 'newsup'); ?>">
                               <i class="fa fa-clock-o"></i><?php echo esc_html($latest_title); ?>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#<?php echo esc_attr($tab_id); ?>-popular"
                               aria-controls="<?php esc_attr_e('Popular', 'newsup'); ?>">
                                <i class="fa fa-fire"></i> <?php echo esc_html($popular_title); ?>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#<?php echo esc_attr($tab_id); ?>-categorised"
                               aria-controls="<?php esc_attr_e('Categorised', 'newsup'); ?>">
                                <i class="fa fa-bolt"></i> <?php echo esc_html($categorised_title); ?>
                            </a>
                        </li>

                    </ul>
                <div class="tab-content">
                    <div id="<?php echo esc_attr($tab_id); ?>-recent" role="tabpanel" class="tab-pane active">
                        <?php
                        newsup_render_posts('recent', $show_excerpt, $excerpt_length, $number_of_posts);
                        ?>
                    </div>


                    <div id="<?php echo esc_attr($tab_id); ?>-popular" role="tabpanel" class="tab-pane">
                        <?php
                        newsup_render_posts('popular', $show_excerpt, $excerpt_length, $number_of_posts);
                        ?>
                    </div>

                    <?php if ($enable_categorised_tab == 'true'): ?>
                        <div id="<?php echo esc_attr($tab_id); ?>-categorised" role="tabpanel" class="tab-pane ">
                            <?php
                            newsup_render_posts('categorised', $show_excerpt, $excerpt_length, $number_of_posts, $category);
                            ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        <?php

    }
endif;

add_action('newsup_action_banner_tabbed_posts', 'newsup_banner_tabbed_posts', 10);

//Banner Advertisment
if (!function_exists('newsup_banner_advertisement')):
    /**
     *
     * @since Newsup 1.0.0
     *
     */
    function newsup_banner_advertisement()
    {

        if (('' != newsup_get_option('banner_advertisement_section')) ) { ?>
            <div class="col-md-9 col-sm-8">
                <?php if (('' != newsup_get_option('banner_advertisement_section'))):

                    $newsup_banner_advertisement = newsup_get_option('banner_advertisement_section');
                    $newsup_banner_advertisement = absint($newsup_banner_advertisement);
                    $newsup_banner_advertisement = wp_get_attachment_image($newsup_banner_advertisement, 'full');
                    $newsup_banner_advertisement_url = newsup_get_option('banner_advertisement_section_url');
                    $newsup_banner_advertisement_url = isset($newsup_banner_advertisement_url) ? esc_url($newsup_banner_advertisement_url) : '#';
                    $newsup_open_on_new_tab = get_theme_mod('newsup_open_on_new_tab',true);
                    ?>
                    <div class="header-ads">
                        <a class="pull-right" href="<?php echo esc_url($newsup_banner_advertisement_url); ?>" 
                            <?php if($newsup_open_on_new_tab) { ?>target="_blank" <?php } ?> >
                            <?php echo $newsup_banner_advertisement; ?>
                        </a>
                    </div>
                <?php endif; ?>                

            </div>
            <!-- Trending line END -->
            <?php
        }

         if (is_active_sidebar('home-advertisement-widgets')): ?>
            <div class="mg-ads-area">
                <?php dynamic_sidebar('home-advertisement-widgets'); ?>
            </div>
                <?php endif; 
    }
endif;

add_action('newsup_action_banner_advertisement', 'newsup_banner_advertisement', 10);

//Banner Featured Post
if (!function_exists('newsup_banner_featured_posts')):
    /**
     * Ticker Slider
     *
     * @since newsup 1.0.0
     *
     */
    function newsup_banner_featured_posts()
    {
        $color_class = 'category-color-1';
        ?>
        <?php
        $newsup_enable_featured_news = newsup_get_option('show_featured_news_section');
        if ($newsup_enable_featured_news):
            $newsup_featured_news_title = newsup_get_option('featured_news_section_title');
            $dir = 'ltr';
            if(is_rtl()){
                $dir = 'rtl';
            }
            ?>
            <div class="ta-main-banner-featured-posts featured-posts" dir="<?php echo esc_attr($dir);?>">
                <?php if (!empty($newsup_featured_news_title)): ?>
                    <h4 class="header-tater1 ">
                                <span class="header-tater <?php echo esc_attr($color_class); ?>">
                                    <?php echo esc_html($newsup_featured_news_title); ?>
                                </span>
                    </h4>
                <?php endif; ?>


                <div class="section-wrapper">
                    <div class="ta-double-column list-style ta-container-row clearfix">
                        <?php
                        $newsup_featured_category = newsup_get_option('select_featured_news_category');
                        $newsup_number_of_featured_news = newsup_get_option('number_of_featured_news');

                        $featured_posts = newsup_get_posts($newsup_number_of_featured_news, $newsup_featured_category);
                        if ($featured_posts->have_posts()) :
                            while ($featured_posts->have_posts()) :
                                $featured_posts->the_post();

                                global $post;
                                $url = newsup_get_freatured_image_url($post->ID, 'thumbnail');
                                ?>

                                <div class="col-3 pad float-l " data-mh="ta-feat-list">
                                    <div class="read-single color-pad">
                                        <div class="data-bg read-img pos-rel col-4 float-l read-bg-img"
                                             data-background="<?php echo esc_url($url); ?>">
                                            <img src="<?php echo esc_url($url); ?>">

                                            <span class="min-read-post-format">
                                        <?php echo newsup_post_format($post->ID); ?>
                                        <?php newsup_count_content_words($post->ID); ?>
                                        </span>

                                        </div>
                                        <div class="read-details col-75 float-l pad color-tp-pad">
                                            <div class="read-categories">
                                                <?php newsup_post_categories(); ?>
                                            </div>
                                            <div class="read-title">
                                                <h4>
                                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                </h4>
                                            </div>

                                            <div class="entry-meta">
                                                <?php newsup_post_meta(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endwhile;
                        endif;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </div>

        <?php endif;
    }
endif;

add_action('newsup_action_banner_featured_posts', 'newsup_banner_featured_posts', 10);


//Front Page Banner
if (!function_exists('newsup_front_page_banner_section')) :
    /**
     *
     * @since Newsup
     *
     */
    function newsup_front_page_banner_section()
    {
        if (is_front_page() || is_home()) {
        $newsup_enable_main_slider = newsup_get_option('show_main_news_section');
        $select_vertical_slider_news_category = newsup_get_option('select_vertical_slider_news_category');
        $vertical_slider_number_of_slides = newsup_get_option('vertical_slider_number_of_slides');
        $all_posts_vertical = newsup_get_posts($vertical_slider_number_of_slides, $select_vertical_slider_news_category);
        if ($newsup_enable_main_slider):  

            $main_banner_section_background_image = newsup_get_option('main_banner_section_background_image');
            $main_banner_section_background_image_url = wp_get_attachment_image_src($main_banner_section_background_image, 'full');
        if(!empty($main_banner_section_background_image)){ ?>
             <section class="mg-fea-area over" style="background-image:url('<?php echo $main_banner_section_background_image_url[0]; ?>');">
        <?php }else{ ?>
            <section class="mg-fea-area">
        <?php  } ?>
            <div class="overlay">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <div id="homemain"class="homemain owl-carousel mr-bot60 pd-r-10"> 
                                <?php newsup_get_block('list', 'banner'); ?>
                            </div>
                        </div> 
                        <?php do_action('newsup_action_banner_tabbed_posts');?>
                    </div>
                </div>
            </div>
        </section>
        <!--==/ Home Slider ==-->
        <?php endif; ?>
        <!-- end slider-section -->
        <?php }
    }
endif;
add_action('newsup_action_front_page_main_section_1', 'newsup_front_page_banner_section', 40);