<?php /*** Option Panel
 *
 * @package Newsup
 */

$newsup_default = newsup_get_default_theme_options();
/*theme option panel info*/
require get_template_directory() . '/inc/ansar/customize/frontpage-options.php';

// Add Theme Options Panel.
$wp_customize->add_panel('theme_option_panel',
    array(
        'title' => esc_html__('Theme Options', 'newsup'),
        'priority' => 20,
        'capability' => 'edit_theme_options',
    )
);


$wp_customize->add_section( 'header_options' , array(
        'title' => __('Header Options', 'newsup'),
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
        'priority' => 10,
    ) );

    
    $wp_customize->add_setting('header_data_enable',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'header_data_enable', 
        array(
            'label' => esc_html__('Hide / Show Date', 'newsup'),
            'type' => 'toggle',
            'section' => 'header_options',
        )
    ));

    $wp_customize->add_setting('header_time_enable',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'header_time_enable', 
        array(
            'label' => esc_html__('Hide / Show Time', 'newsup'),
            'type' => 'toggle',
            'section' => 'header_options',
        )
    ));

    // date in header display type
    $wp_customize->add_setting( 'newsup_date_time_show_type', array(
        'default'           => 'newsup_default',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'newsup_sanitize_select',
    ) );

    $wp_customize->add_control( 'newsup_date_time_show_type', array(
        'type'     => 'radio',
        'label'    => esc_html__( 'Date / Time in header display type:', 'newsup' ),
        'choices'  => array(
            'newsup_default'          => esc_html__( 'Theme Default Setting', 'newsup' ),
            'wordpress_date_setting' => esc_html__( 'From WordPress Setting', 'newsup' ),
        ),
        'section'  => 'header_options',
        'settings' => 'newsup_date_time_show_type',
    ) );

    $wp_customize->add_setting('header_social_icon_enable',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'header_social_icon_enable', 
        array(
            'label' => esc_html__('Hide / Show Social Icon', 'newsup'),
            'type' => 'toggle',
            'section' => 'header_options',
        )
    ));
    

    // Soical facebook link
    $wp_customize->add_setting(
    'newsup_header_fb_link',
    array(
        'sanitize_callback' => 'esc_url_raw',
    )
    
    );
    $wp_customize->add_control(
    'newsup_header_fb_link',
    array(
        'label' => __('Facebook URL','newsup'),
        'section' => 'header_options',
        'type' => 'url',
    )
    );

    

    $wp_customize->add_setting('newsup_header_fb_target',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_header_fb_target', 
        array(
            'label' => esc_html__('Open link in a new tab', 'newsup'),
            'type' => 'toggle',
            'section' => 'header_options',
        )
    ));
    
    
    //Social Twitter link
    $wp_customize->add_setting(
    'newsup_header_twt_link',
    array(
        'sanitize_callback' => 'esc_url_raw',
    )
    
    );
    $wp_customize->add_control(
    'newsup_header_twt_link',
    array(
        'label' => __('Twitter URL','newsup'),
        'section' => 'header_options',
        'type' => 'url',
    )
    );

    $wp_customize->add_setting('newsup_header_twt_target',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_header_twt_target', 
        array(
            'label' => esc_html__('Open link in a new tab', 'newsup'),
            'type' => 'toggle',
            'section' => 'header_options',
        )
    ));
    
    //Soical Linkedin link
    $wp_customize->add_setting(
    'newsup_header_lnkd_link',
    array(
        'sanitize_callback' => 'esc_url_raw',
    )
    
    );
    $wp_customize->add_control(
    'newsup_header_lnkd_link',
    array(
        'label' => __('Linkedin URL','newsup'),
        'section' => 'header_options',
        'type' => 'url',
    )
    );

    
    $wp_customize->add_setting('newsup_header_lnkd_target',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_header_lnkd_target', 
        array(
            'label' => esc_html__('Open link in a new tab', 'newsup'),
            'type' => 'toggle',
            'section' => 'header_options',
        )
    ));


    //Soical Instagram link
    $wp_customize->add_setting(
    'newsup_header_insta_link',
    array(
        'sanitize_callback' => 'esc_url_raw',
    )
    
    );
    $wp_customize->add_control(
    'newsup_header_insta_link',
    array(
        'label' => __('Instagram URL','newsup'),
        'section' => 'header_options',
        'type' => 'url',
    )
    );

   $wp_customize->add_setting('newsup_insta_insta_target',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_insta_insta_target', 
        array(
            'label' => esc_html__('Open link in a new tab', 'newsup'),
            'type' => 'toggle',
            'section' => 'header_options',
        )
    ));

    //Soical youtube link
    $wp_customize->add_setting(
    'newsup_header_youtube_link',
    array(
        'sanitize_callback' => 'esc_url_raw',
    )
    
    );
    $wp_customize->add_control(
    'newsup_header_youtube_link',
    array(
        'label' => __('Youtube URL','newsup'),
        'section' => 'header_options',
        'type' => 'url',
    )
    );

    $wp_customize->add_setting('newsup_header_youtube_target',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_header_youtube_target', 
        array(
            'label' => esc_html__('Open link in a new tab', 'newsup'),
            'type' => 'toggle',
            'section' => 'header_options',
        )
    ));

    //Soical Pintrest link
    $wp_customize->add_setting(
    'newsup_header_pintrest_link',
    array(
        'sanitize_callback' => 'esc_url_raw',
    )
    
    );
    $wp_customize->add_control(
    'newsup_header_pintrest_link',
    array(
        'label' => __('Pintrest URL','newsup'),
        'section' => 'header_options',
        'type' => 'url',
    )
    );

    
    $wp_customize->add_setting('newsup_header_pintrest_target',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_header_pintrest_target', 
        array(
            'label' => esc_html__('Open link in a new tab', 'newsup'),
            'type' => 'toggle',
            'section' => 'header_options',
        )
    ));
    
    

    
    function newsup_header_info_sanitize_text( $input ) {

    return wp_kses_post( force_balance_tags( $input ) );

    }
    
    if ( ! function_exists( 'newsup_sanitize_text_content' ) ) :

    /**
     * Sanitize text content.
     *
     * @since 1.0.0
     *
     * @param string               $input Content to be sanitized.
     * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
     * @return string Sanitized content.
     */
    function newsup_sanitize_text_content( $input, $setting ) {

        return ( stripslashes( wp_filter_post_kses( addslashes( $input ) ) ) );

    }
endif;
    
    function newsup_header_sanitize_checkbox( $input ) {
            // Boolean check 
    return ( ( isset( $input ) && true == $input ) ? true : false );
    
    }

/**
 * Layout options section
 *
 * @package newsup
 */

// Layout Section.
$wp_customize->add_section('site_layout_settings',
    array(
        'title' => esc_html__('Content Layout Settings', 'newsup'),
        'priority' => 9,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);
    
$wp_customize->add_setting('newsup_archive_page_heading',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control(
    new Newsup_Section_Title(
        $wp_customize,
        'newsup_archive_page_heading',
        array(
            'label' => esc_html__('Archive Blog Pages', 'newsup'),
            'section' => 'site_layout_settings',

        )
    )
);
    
    $wp_customize->add_setting(
        'newsup_content_layout', array(
        'default'           => 'align-content-right',
        'sanitize_callback' => 'newsup_sanitize_radio'
    ) );
    
    
    $wp_customize->add_control(
        new Newsup_Radio_Image_Control( 
            // $wp_customize object
            $wp_customize,
            // $id
            'newsup_content_layout',
            // $args
            array(
                'settings'      => 'newsup_content_layout',
                'section'       => 'site_layout_settings',
                'label'         => __( 'Layout', 'newsup' ),
                'choices'       => array(
                    'align-content-right'    => get_template_directory_uri() . '/images/right-sidebar.png',
                    'align-content-left' => get_template_directory_uri() . '/images/fullwidth-left-sidebar.png',
                    'full-width-content'    => get_template_directory_uri() . '/images/fullwidth.png',
                )
            )
        )
    );


$wp_customize->add_setting('newsup_single_post_page_heading',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control(
    new Newsup_Section_Title(
        $wp_customize,
        'newsup_single_post_page_heading',
        array(
            'label' => esc_html__('Single Blog Pages', 'newsup'),
            'section' => 'site_layout_settings',

        )
    )
);
    
    $wp_customize->add_setting(
        'newsup_single_page_layout', array(
        'default'           => 'single-align-content-right',
        'sanitize_callback' => 'newsup_sanitize_radio'
    ) );
    
    
    $wp_customize->add_control(
        new Newsup_Radio_Image_Control( 
            // $wp_customize object
            $wp_customize,
            // $id
            'newsup_single_page_layout',
            // $args
            array(
                'settings'      => 'newsup_single_page_layout',
                'section'       => 'site_layout_settings',
                'label'         => __( 'Layout', 'newsup' ),
                'choices'       => array(
                    'single-align-content-right'    => get_template_directory_uri() . '/images/right-sidebar.png',
                    'single-align-content-left' => get_template_directory_uri() . '/images/fullwidth-left-sidebar.png',
                   'single-full-width-content'    => get_template_directory_uri() . '/images/fullwidth.png',
                )
            )
        )
    );



//========== date and author options ===============

// Global Section.
$wp_customize->add_section('site_post_date_author_settings',
    array(
        'title' => esc_html__('Date and Author', 'newsup'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);

// Setting - global content alignment of news.
$wp_customize->add_setting('global_post_date_author_setting',
    array(
        'default' => $newsup_default['global_post_date_author_setting'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'newsup_sanitize_select',
    )
);


$wp_customize->add_control('global_post_date_author_setting',
    array(
        'label' => esc_html__('Date and Author', 'newsup'),
        'section' => 'site_post_date_author_settings',
        'type' => 'select',
        'choices' => array(
            'show-date-author' => esc_html__('Show Date and Author', 'newsup'),
            'show-date-only' => esc_html__('Show Date Only', 'newsup'),
            'show-author-only' => esc_html__('Show Author Only', 'newsup'),
            'hide-date-author' => esc_html__('Hide All', 'newsup'),
        ),
        'priority' => 130,
    ));

//========== single posts options ===============

// Single Section.
$wp_customize->add_section('site_single_posts_settings',
    array(
        'title' => esc_html__('Single Post', 'newsup'),
        'priority' => 50,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);



$wp_customize->add_setting('newsup_single_page_heading',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control(
    new Newsup_Section_Title(
        $wp_customize,
        'newsup_single_page_heading',
        array(
            'label' => esc_html__('Single Post', 'newsup'),
            'section' => 'site_single_posts_settings',

        )
    )
);


    // Setting - Single posts.
    $wp_customize->add_setting('newsup_single_post_category',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_single_post_category', 
        array(
            'label' => esc_html__('Hide/Show Categories', 'newsup'),
            'type' => 'toggle',
            'section' => 'site_single_posts_settings',
        )
    ));


    $wp_customize->add_setting('newsup_single_post_admin_details',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_single_post_admin_details', 
    array(
        'label' => esc_html__('Hide/Show Author Details', 'newsup'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
    )
));


    $wp_customize->add_setting('newsup_single_post_date',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_single_post_date', 
    array(
        'label' => esc_html__('Hide/Show Date', 'newsup'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
    )
));


$wp_customize->add_setting('newsup_single_post_tag',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_single_post_tag', 
    array(
        'label' => esc_html__('Hide/Show Tag', 'newsup'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
    )
));

    $wp_customize->add_setting('single_show_featured_image',
    array(
        'default' => $newsup_default['single_show_featured_image'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'single_show_featured_image', 
        array(
            'label' => __('Hide/Show Featured Image', 'newsup'),
            'type' => 'toggle',
            'section' => 'site_single_posts_settings',
        )
    ));

    $wp_customize->add_setting('single_show_share_icon',
    array(
        'default' => $newsup_default['single_show_share_icon'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'single_show_share_icon', 
        array(
            'label' => __('Hide/Show Sharing Icons', 'newsup'),
            'type' => 'toggle',
            'section' => 'site_single_posts_settings',
        )
    ));

    $wp_customize->add_setting('newsup_enable_single_post_admin_details',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_enable_single_post_admin_details', 
    array(
        'label' => esc_html__('Hide/Show Author Details', 'newsup'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
    )
));

    

$wp_customize->add_setting('newsup_related_post_heading',
    array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control(
    new Newsup_Section_Title(
        $wp_customize,
        'newsup_related_post_heading',
        array(
            'label' => esc_html__('Related Post', 'newsup'),
            'section' => 'site_single_posts_settings',

        )
    )
);





$wp_customize->add_setting('newsup_enable_related_post',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_enable_related_post', 
    array(
        'label' => esc_html__('Enable Related Posts', 'newsup'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
    )
));

$wp_customize->add_setting('newsup_related_post_title', 
    array(
        'default' => esc_html__('Related Posts', 'newsup'),
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control('newsup_related_post_title', 
    array(
        'label' => esc_html__('Title', 'newsup'),
        'type' => 'text',
        'section' => 'site_single_posts_settings',
    )
);

/************************* Meta Hide Show *********************************/
$wp_customize->add_setting('newsup_enable_single_post_category',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_enable_single_post_category', 
    array(
        'label' => esc_html__('Hide/Show Categories', 'newsup'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
    )
));

$wp_customize->add_setting('newsup_enable_single_post_date',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_enable_single_post_date', 
    array(
        'label' => esc_html__('Hide/Show Date', 'newsup'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
    )
));

$wp_customize->add_setting('newsup_enable_single_post_admin',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_enable_single_post_admin', 
    array(
        'label' => esc_html__('Hide/Show Author Name', 'newsup'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
    )
));


$wp_customize->add_setting('newsup_enable_single_post_comments',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
);
$wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_enable_single_post_comments', 
    array(
        'label' => esc_html__('Hide/Show Comments', 'newsup'),
        'type' => 'toggle',
        'section' => 'site_single_posts_settings',
    )
));


$wp_customize->add_section('you_missed_section',
    array(
        'title' => esc_html__('You Missed Section', 'newsup'),
        'priority' => 100,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);

    $wp_customize->add_setting('you_missed_enable',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'you_missed_enable', 
        array(
            'label' => esc_html__('Hide / Show You Missed Section', 'newsup'),
            'type' => 'toggle',
            'section' => 'you_missed_section',
        )
    ));


    // You Misses Title
    $wp_customize->add_setting(
    'you_missed_title',
    array(
        'default' => esc_html__('You Missed','newsup'),
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => $selective_refresh
    )
    
    );
    $wp_customize->add_control(
    'you_missed_title',
    array(
        'label' => __('Title','newsup'),
        'section' => 'you_missed_section',
        'type' => 'text',
    )
    );

//========== footer latest blog carousel options ===============

// Footer Section.
    $wp_customize->add_section('footer_options', array(
        'title' => __('Footer Options','newsup'),
        'priority' => 200,
        'panel' => 'theme_option_panel',
    ) );
    

    //Footer Background image
    $wp_customize->add_setting( 
        'newsup_footer_widget_background', array(
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'newsup_footer_widget_background', array(
        'label'    => __( 'Background Image', 'newsup' ),
        'section'  => 'footer_options',
        'settings' => 'newsup_footer_widget_background',
    ) ) );


    //Bqckground Overlay 
   $wp_customize->add_setting(
        'newsup_footer_overlay_color', array( 'sanitize_callback' => 'newsup_alpha_color_custom_sanitization_callback',
        
    ) );
    
    $wp_customize->add_control(new Newsup_Customize_Alpha_Color_Control( $wp_customize,'newsup_footer_overlay_color', array(
       'label'      => __('Overlay Color', 'newsup' ),
        'palette' => true,
        'section' => 'footer_options')
    ) );

    
    $wp_customize->add_setting(
                'newsup_footer_column_layout', array(
                'default' => 3,
                'sanitize_callback' => 'newsup_sanitize_select',
            ) );

            $wp_customize->add_control(
                'newsup_footer_column_layout', array(
                'type' => 'select',
                'label' => __('Select Column Layout','newsup'),
                'section' => 'footer_options',
                'choices' => array(1=>1, 2=>2,3=>3,4=>4),
    ) );
   
    //Enable and disable social icon
    $wp_customize->add_setting('footer_social_icon_enable',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'footer_social_icon_enable', 
        array(
            'label' => esc_html__('Hide / Show Social Icon', 'newsup'),
            'type' => 'toggle',
            'section' => 'footer_options',
        )
    ));


    // Soical facebook link
    $wp_customize->add_setting(
    'newsup_footer_fb_link',
    array(
        'sanitize_callback' => 'esc_url_raw',
    )
    
    );
    $wp_customize->add_control(
    'newsup_footer_fb_link',
    array(
        'label' => __('Facebook URL','newsup'),
        'section' => 'footer_options',
        'type' => 'text',
    )
    );

   $wp_customize->add_setting('newsup_footer_fb_target',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_social_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_footer_fb_target', 
        array(
            'label' => esc_html__('Open link in a new tab', 'newsup'),
            'type' => 'toggle',
            'section' => 'footer_options',
        )
    ));

    //Social Twitter link
    $wp_customize->add_setting(
    'newsup_footer_twt_link',
    array(
        'sanitize_callback' => 'esc_url_raw',
    )
    
    );
    $wp_customize->add_control(
    'newsup_footer_twt_link',
    array(
        'label' => __('Twitter URL','newsup'),
        'section' => 'footer_options',
        'type' => 'text',
    )
    );

    
    $wp_customize->add_setting('newsup_footer_twt_target',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_social_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_footer_twt_target', 
        array(
            'label' => esc_html__('Open link in a new tab', 'newsup'),
            'type' => 'toggle',
            'section' => 'footer_options',
        )
    ));
    
    //Soical Linkedin link
    $wp_customize->add_setting(
    'newsup_footer_lnkd_link',
    array(
        'sanitize_callback' => 'esc_url_raw',
    )
    
    );
    $wp_customize->add_control(
    'newsup_footer_lnkd_link',
    array(
        'label' => __('Linkedin URL','newsup'),
        'section' => 'footer_options',
        'type' => 'text',
    )
    );

    $wp_customize->add_setting('newsup_footer_lnkd_target',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_social_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_footer_lnkd_target', 
        array(
            'label' => esc_html__('Open link in a new tab', 'newsup'),
            'type' => 'toggle',
            'section' => 'footer_options',
        )
    ));
    
    
    //Soical Instagram link
    $wp_customize->add_setting(
    'newsup_footer_insta_link',
    array(
        'sanitize_callback' => 'esc_url_raw',
    )
    
    );
    $wp_customize->add_control(
    'newsup_footer_insta_link',
    array(
        'label' => __('Instagram URL','newsup'),
        'section' => 'footer_options',
        'type' => 'text',
    )
    );

    $wp_customize->add_setting('newsup_footer_insta_target',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_social_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_footer_insta_target', 
        array(
            'label' => esc_html__('Open link in a new tab', 'newsup'),
            'type' => 'toggle',
            'section' => 'footer_options',
        )
    ));

    //Soical Youtube link
    $wp_customize->add_setting(
    'newsup_footer_youtube_link',
    array(
        'sanitize_callback' => 'esc_url_raw',
    )
    
    );
    $wp_customize->add_control(
    'newsup_footer_youtube_link',
    array(
        'label' => __('Youtube URL','newsup'),
        'section' => 'footer_options',
        'type' => 'text',
    )
    );

   $wp_customize->add_setting('newsup_footer_youtube_target',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_social_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_footer_youtube_target', 
        array(
            'label' => esc_html__('Open link in a new tab', 'newsup'),
            'type' => 'toggle',
            'section' => 'footer_options',
        )
    ));

    //Soical Pintrest link
    $wp_customize->add_setting(
    'newsup_footer_pinterest_link',
    array(
        'sanitize_callback' => 'esc_url_raw',
    )
    
    );
    $wp_customize->add_control(
    'newsup_footer_pinterest_link',
    array(
        'label' => __('Pinterest URL','newsup'),
        'section' => 'footer_options',
        'type' => 'text',
    )
    );

    $wp_customize->add_setting('newsup_footer_pinterest_target',
    array(
        'default' => true,
        'sanitize_callback' => 'newsup_social_sanitize_checkbox',
    )
    );
    $wp_customize->add_control(new Newsup_Toggle_Control( $wp_customize, 'newsup_footer_pinterest_target', 
        array(
            'label' => esc_html__('Open link in a new tab', 'newsup'),
            'type' => 'toggle',
            'section' => 'footer_options',
        )
    ));

     $wp_customize->add_section( 'post_image_options' , array(
        'title' => __('Post Image Settings', 'newsup'),
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
        'priority' => 350,
    ) );


    // date in header display type
    $wp_customize->add_setting( 'post_image_type', array(
        'default'           => 'newsup_post_img_hei',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'newsup_sanitize_select',
    ) );

    $wp_customize->add_control( 'post_image_type', array(
        'type'     => 'radio',
        'label'    => esc_html__( 'Post Image display type:', 'newsup' ),
        'choices'  => array(
            'newsup_post_img_hei'          => esc_html__( 'Fix Height Post Image', 'newsup' ),
            'newsup_post_img_acc' => esc_html__( 'Auto Height Post Image', 'newsup' ),
        ),
        'section'  => 'post_image_options',
        'settings' => 'post_image_type',
    ) );



   
    
    function newsup_social_sanitize_checkbox( $input ) {
            // Boolean check 
            return ( ( isset( $input ) && true == $input ) ? true : false );
            }
    
            
    if ( ! function_exists( 'newsup_sanitize_select' ) ) :

    /**
     * Sanitize select.
     *
     * @since 1.0.0
     *
     * @param mixed                $input The value to sanitize.
     * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
     * @return mixed Sanitized value.
     */
    function newsup_sanitize_select( $input, $setting ) {

        // Ensure input is a slug.
        $input = sanitize_key( $input );

        // Get list of choices from the control associated with the setting.
        $choices = $setting->manager->get_control( $setting->id )->choices;

        // If the input is a valid key, return it; otherwise, return the default.
        return ( array_key_exists( $input, $choices ) ? $input : $setting->default );

    }

endif;

function newsup_template_page_sanitize_text( $input ) {

            return wp_kses_post( force_balance_tags( $input ) );

}
