<?php

/**
 * Option Panel
 *
 * @package Newsup
 */

$newsup_default = newsup_get_default_theme_options();

/**
 * Frontpage options section
 *
 * @package newsup
 */

 //Header Bqckground Overlay 
   $wp_customize->add_setting(
        'newsup_header_overlay_color', array( 'sanitize_callback' => 'newsup_alpha_color_custom_sanitization_callback','default' => 'rgba(32,47,91,0.4)'
        
    ) );
    
    $wp_customize->add_control(new Newsup_Customize_Alpha_Color_Control( $wp_customize,'newsup_header_overlay_color', array(
       'label'      => __('Overlay Color', 'newsup' ),
        'palette' => true,
        'section' => 'header_image')
    ) );

$wp_customize->add_setting('remove_header_image_overlay',
        array(
            'default'           => $newsup_default['remove_header_image_overlay'],
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'newsup_sanitize_checkbox',
        )
    );

    $wp_customize->add_control('remove_header_image_overlay',
        array(
            'label'    => esc_html__('Remove Image Overlay', 'newsup'),
            'section'  => 'header_image',
            'type'     => 'checkbox',
            'priority' => 50,
        )
    );


// Add Frontpage Options Panel.
$wp_customize->add_panel('frontpage_option_panel',
    array(
        'title' => esc_html__('Frontpage Options', 'newsup'),
        'priority' => 40,
        'capability' => 'edit_theme_options',
    )
);


// Advertisement Section.
$wp_customize->add_section('frontpage_advertisement_settings',
    array(
        'title' => esc_html__('Banner Advertisement', 'newsup'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'frontpage_option_panel',
    )
);




// Setting banner_advertisement_section.
$wp_customize->add_setting('banner_advertisement_section',
    array(
        'default' => $default['banner_advertisement_section'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
        'transport' => $selective_refresh
    )
);




$wp_customize->add_control(
    new WP_Customize_Cropped_Image_Control($wp_customize, 'banner_advertisement_section',
        array(
            'label' => esc_html__('Banner Section Advertisement', 'newsup'),
            'description' => sprintf(esc_html__('Recommended Size %1$s px X %2$s px', 'newsup'), 930, 100),
            'section' => 'frontpage_advertisement_settings',
            'width' => 930,
            'height' => 100,
            'flex_width' => true,
            'flex_height' => true,
            'priority' => 120,
        )
    )
);

/*banner_advertisement_section_url*/
$wp_customize->add_setting('banner_advertisement_section_url',
    array(
        'default' => $newsup_default['banner_advertisement_section_url'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    )
);
$wp_customize->add_control('banner_advertisement_section_url',
    array(
        'label' => esc_html__('URL Link', 'newsup'),
        'section' => 'frontpage_advertisement_settings',
        'type' => 'url',
        'priority' => 130,
    )
);

$wp_customize->add_setting('newsup_open_on_new_tab',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_open_on_new_tab', 
        array(
            'label' => esc_html__('Open link in a new tab', 'newsup'),
            'type' => 'toggle',
            'section' => 'frontpage_advertisement_settings',
            'priority' => 140,
        )
    ));




//=================================
//Top tags Section.
//=================================
$wp_customize->add_section('newsup_popular_tags_section_settings',
    array(
        'title' => esc_html__('Top Tags', 'newsup'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'frontpage_option_panel',
    )
);

$wp_customize->add_setting('show_popular_tags_section',
    array(
        'default' => true,
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'show_popular_tags_section', 
        array(
            'label' => __('Hide/Show Top Tags', 'newsup'),
            'type' => 'toggle',
            'section' => 'newsup_popular_tags_section_settings',
            'priority' => 100,
        )
    ));


// Setting - number_of_slides.
$wp_customize->add_setting('show_popular_tags_title',
    array(
        'default' => $newsup_default['show_popular_tags_title'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => $selective_refresh
    )
);

$wp_customize->add_control('show_popular_tags_title',
    array(
        'label' => esc_html__('Section Title', 'newsup'),
        'section' => 'newsup_popular_tags_section_settings',
        'type' => 'text',
        'priority' => 100,
        'active_callback' => 'newsup_popular_tags_section_status'

    )
);


//=================================
// Trending Posts Section.
//=================================
$wp_customize->add_section('newsup_flash_posts_section_settings',
    array(
        'title' => esc_html__('Latest Posts', 'newsup'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'frontpage_option_panel',
    )
);

$wp_customize->add_setting('show_flash_news_section',
    array(
        'default' => $newsup_default['show_flash_news_section'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
);

$wp_customize->add_control('show_flash_news_section',
    array(
        'label' => esc_html__('Enable Latest Posts Section', 'newsup'),
        'section' => 'newsup_flash_posts_section_settings',
        'type' => 'checkbox',
        'priority' => 22,

    )
);

// Setting - number_of_slides.
$wp_customize->add_setting('flash_news_title',
    array(
        'default' => $newsup_default['flash_news_title'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => $selective_refresh
    )
);

$wp_customize->add_control('flash_news_title',
    array(
        'label' => esc_html__('Latest Post Title', 'newsup'),
        'section' => 'newsup_flash_posts_section_settings',
        'type' => 'text',
        'priority' => 23,
        'active_callback' => 'newsup_flash_posts_section_status'

    )
);

// Setting - drop down category for slider.
$wp_customize->add_setting('select_flash_news_category',
    array(
        'default' => $newsup_default['select_flash_news_category'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);


$wp_customize->add_control(new Newsup_Dropdown_Taxonomies_Control($wp_customize, 'select_flash_news_category',
    array(
        'label' => esc_html__('Latest Posts Category', 'newsup'),
        'description' => esc_html__('Posts to be shown on trending posts ', 'newsup'),
        'section' => 'newsup_flash_posts_section_settings',
        'type' => 'dropdown-taxonomies',
        'taxonomy' => 'category',
        'priority' => 23,
        'active_callback' => 'newsup_flash_posts_section_status'
    )));




/**
 * Main Banner Slider Section
 * */

// Main banner Sider Section.
$wp_customize->add_section('frontpage_main_banner_section_settings',
    array(
        'title' => esc_html__('Slider Banner & Tab Section', 'newsup'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'frontpage_option_panel',
    )
);


// Setting - show_main_news_section.
$wp_customize->add_setting('show_main_news_section',
    array(
        'default' => $newsup_default['show_main_news_section'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
);

$wp_customize->add_control('show_main_news_section',
    array(
        'label' => esc_html__('Enable Slider Banner Section', 'newsup'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'checkbox',
        'priority' => 100,

    )
);



// Setting banner_advertisement_section.
$wp_customize->add_setting('main_banner_section_background_image',
    array(
        'default' => $newsup_default['main_banner_section_background_image'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);


$wp_customize->add_control(
    new WP_Customize_Cropped_Image_Control($wp_customize, 'main_banner_section_background_image',
        array(
            'label' => esc_html__('Background image', 'newsup'),
            'description' => sprintf(esc_html__('Recommended Size %1$s px X %2$s px', 'newsup'), 1200, 720),
            'section' => 'frontpage_main_banner_section_settings',
            'width' => 1200,
            'height' => 720,
            'flex_width' => true,
            'flex_height' => true,
            'priority' => 100,
            'active_callback' => 'newsup_main_banner_section_status'
        )
    )
);


//section title
$wp_customize->add_setting('main_slider_section_title',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control(
    new newsup_Section_Title(
        $wp_customize,
        'main_slider_section_title',
        array(
            'label' 			=> esc_html__( 'Slider Section ', 'newsup' ),
            'section' 			=> 'frontpage_main_banner_section_settings',
            'priority' 			=> 100,
            'active_callback' => 'newsup_main_banner_section_status'
        )
    )
);
// Setting - drop down category for slider.
$wp_customize->add_setting('select_slider_news_category',
    array(
        'default' => $newsup_default['select_slider_news_category'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);


$wp_customize->add_control(new Newsup_Dropdown_Taxonomies_Control($wp_customize, 'select_slider_news_category',
    array(
        'label' => esc_html__('Category', 'newsup'),
        'description' => esc_html__('Posts to be shown on banner slider section', 'newsup'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'dropdown-taxonomies',
        'taxonomy' => 'category',
        'priority' => 100,
        'active_callback' => 'newsup_main_banner_section_status'
    )));



//section title
$wp_customize->add_setting('tabbed_section_title',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control(
    new newsup_Section_Title(
        $wp_customize,
        'tabbed_section_title',
        array(
            'label' 			=> esc_html__( 'Tabbed Section ', 'newsup' ),
            'section' 			=> 'frontpage_main_banner_section_settings',
            'priority' 			=> 100,
            'active_callback' => 'newsup_main_banner_section_status'
        )
    )
);

// Setting - featured_news_section_title.
$wp_customize->add_setting('latest_tab_title',
    array(
        'default' => $newsup_default['latest_tab_title'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control('latest_tab_title',
    array(
        'label' => esc_html__('Latest Tab Title', 'newsup'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'text',
        'priority' => 100,
        'active_callback' => 'newsup_main_banner_section_status',
    )
);

// Setting - featured_news_section_title.
$wp_customize->add_setting('popular_tab_title',
    array(
        'default' => $default['popular_tab_title'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control('popular_tab_title',
    array(
        'label' => esc_html__('Popular Tab Title', 'newsup'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'text',
        'priority' => 100,
        'active_callback' => 'newsup_main_banner_section_status',
    )
);


// Setting - featured_news_section_title.
$wp_customize->add_setting('trending_tab_title',
    array(
        'default' => $default['trending_tab_title'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control('trending_tab_title',
    array(
        'label' => esc_html__('Trending Tab Title', 'newsup'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'text',
        'priority' => 100,
        'active_callback' => 'newsup_main_banner_section_status',
    )
);

// Setting - drop down category for slider.
$wp_customize->add_setting('select_trending_tab_news_category',
    array(
        'default' => $default['select_trending_tab_news_category'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);


$wp_customize->add_control(new Newsup_Dropdown_Taxonomies_Control($wp_customize, 'select_trending_tab_news_category',
    array(
        'label' => esc_html__('Category', 'newsup'),
        'description' => esc_html__('Posts to be shown on trending tab', 'newsup'),
        'section' => 'frontpage_main_banner_section_settings',
        'type' => 'dropdown-taxonomies',
        'taxonomy' => 'category',
        'priority' => 100,
        'active_callback' => 'newsup_main_banner_section_status'
    )));
