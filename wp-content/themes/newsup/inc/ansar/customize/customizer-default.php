<?php
/**
 * Default theme options.
 *
 * @package Newsup
 */

if (!function_exists('newsup_get_default_theme_options')):

/**
 * Get default theme options
 *
 * @since 1.0.0
 *
 * @return array Default theme options.
 */
function newsup_get_default_theme_options() {

    $defaults = array();

    
    // Header options section
    $defaults['header_layout'] = 'header-layout-1';
    $defaults['banner_advertisement_section'] = '';
    $defaults['banner_advertisement_section_url'] = '';
    $defaults['banner_advertisement_scope'] = 'front-page-only';


    // Frontpage Section.
    $defaults['show_popular_tags_title'] = __('Top Tags', 'newsup');
    $defaults['number_of_popular_tags'] = 7;
    $defaults['select_popular_tags_mode'] = 'post_tag';

    $defaults['show_flash_news_section'] = 1;
    $defaults['flash_news_title'] = __('Latest Post', 'newsup');
    $defaults['select_flash_news_category'] = 0;
    $defaults['number_of_flash_news'] = 5;
    $defaults['select_flash_new_mode'] = 'flash-slide-left';
    $defaults['banner_flash_news_scope'] = 'front-page-only';
    $defaults['show_main_news_section'] = 1;
    $defaults['select_main_banner_section_mode'] = 'default';
    $defaults['select_vertical_slider_news_category'] = 0;
    $defaults['vertical_slider_number_of_slides'] = 5;
    $defaults['select_slider_news_category'] = 0;
    $defaults['select_tabbed_thumbs_section_mode'] = 'tabbed';
    $defaults['select_tab_section_mode'] = 'default';
    $defaults['latest_tab_title'] = __("Latest", 'newsup');
    $defaults['popular_tab_title'] = __("Popular", 'newsup');
    $defaults['trending_tab_title'] = __("Trending", 'newsup');
    $defaults['select_trending_tab_news_category'] = 0;
    $defaults['select_thumbs_news_category'] = 0;
    $defaults['number_of_slides'] = 5;
    $defaults['show_featured_news_section'] = 1;
    $defaults['featured_news_section_title'] = __('Featured Story', 'newsup');
    $defaults['select_featured_news_category'] = 0;
    $defaults['number_of_featured_news'] = 6;
    $defaults['main_banner_section_background_image']= '';
    $defaults['remove_header_image_overlay'] = 0;




    $defaults['show_editors_pick_section'] = 1;
    $defaults['frontpage_content_alignment'] = 'align-content-left';

    //layout options
    $defaults['newsup_content_layout'] = 'align-content-left';
    $defaults['global_post_date_author_setting'] = 'show-date-author';
    $defaults['global_hide_post_date_author_in_list'] = 1;
    $defaults['global_widget_excerpt_setting'] = 'trimmed-content';
    $defaults['global_date_display_setting'] = 'theme-date';
    
    $defaults['frontpage_latest_posts_section_title'] = __('You may have missed', 'newsup');
    $defaults['frontpage_latest_posts_category'] = 0;
    $defaults['number_of_frontpage_latest_posts'] = 4;

    //Single
    $defaults['single_show_featured_image'] = true;
    $defaults['single_show_share_icon'] = true;


    // filter.
    $defaults = apply_filters('newsup_filter_default_theme_options', $defaults);

	return $defaults;

}

endif;