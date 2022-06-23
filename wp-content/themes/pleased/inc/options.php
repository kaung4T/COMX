<?php
/**
 * Theme Palace options
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

/**
 * List of pages for page choices.
 * @return Array Array of page ids and name.
 */
function pleased_page_choices() {
    $pages = get_pages();
    $choices = array();
    $choices[0] = esc_html__( '--Select--', 'pleased' );
    foreach ( $pages as $page ) {
        $choices[ $page->ID ] = $page->post_title;
    }
    return  $choices;
}

/**
 * List of trips for post choices.
 * @return Array Array of post ids and name.
 */
function pleased_trip_choices() {
    $posts = get_posts( array( 'post_type' => 'itineraries', 'numberposts' => -1 ) );
    $choices = array();
    $choices[0] = esc_html__( '--Select--', 'pleased' );
    foreach ( $posts as $post ) {
        $choices[ $post->ID ] = $post->post_title;
    }
    wp_reset_postdata();
    return  $choices;
}

if ( ! function_exists( 'pleased_site_layout' ) ) :
    /**
     * Site Layout
     * @return array site layout options
     */
    function pleased_site_layout() {
        $pleased_site_layout = array(
            'wide-layout'  => get_template_directory_uri() . '/assets/images/full.png',
            'boxed-layout' => get_template_directory_uri() . '/assets/images/boxed.png',
        );

        $output = apply_filters( 'pleased_site_layout', $pleased_site_layout );
        return $output;
    }
endif;

if ( ! function_exists( 'pleased_selected_sidebar' ) ) :
    /**
     * Sidebars options
     * @return array Sidbar positions
     */
    function pleased_selected_sidebar() {
        $pleased_selected_sidebar = array(
            'sidebar-1'             => esc_html__( 'Default Sidebar', 'pleased' ),
            'optional-sidebar'      => esc_html__( 'Optional Sidebar 1', 'pleased' ),
        );

        $output = apply_filters( 'pleased_selected_sidebar', $pleased_selected_sidebar );

        return $output;
    }
endif;


if ( ! function_exists( 'pleased_global_sidebar_position' ) ) :
    /**
     * Global Sidebar position
     * @return array Global Sidebar positions
     */
    function pleased_global_sidebar_position() {
        $pleased_global_sidebar_position = array(
            'right-sidebar' => get_template_directory_uri() . '/assets/images/right.png',
            'no-sidebar'    => get_template_directory_uri() . '/assets/images/full.png',
        );

        $output = apply_filters( 'pleased_global_sidebar_position', $pleased_global_sidebar_position );

        return $output;
    }
endif;


if ( ! function_exists( 'pleased_sidebar_position' ) ) :
    /**
     * Sidebar position
     * @return array Sidbar positions
     */
    function pleased_sidebar_position() {
        $pleased_sidebar_position = array(
            'right-sidebar' => get_template_directory_uri() . '/assets/images/right.png',
            'no-sidebar'    => get_template_directory_uri() . '/assets/images/full.png',
        );

        $output = apply_filters( 'pleased_sidebar_position', $pleased_sidebar_position );

        return $output;
    }
endif;


if ( ! function_exists( 'pleased_pagination_options' ) ) :
    /**
     * Pagination
     * @return array site pagination options
     */
    function pleased_pagination_options() {
        $pleased_pagination_options = array(
            'numeric'   => esc_html__( 'Numeric', 'pleased' ),
            'default'   => esc_html__( 'Default(Older/Newer)', 'pleased' ),
        );

        $output = apply_filters( 'pleased_pagination_options', $pleased_pagination_options );

        return $output;
    }
endif;

if ( ! function_exists( 'pleased_switch_options' ) ) :
    /**
     * List of custom Switch Control options
     * @return array List of switch control options.
     */
    function pleased_switch_options() {
        $arr = array(
            'on'        => esc_html__( 'Enable', 'pleased' ),
            'off'       => esc_html__( 'Disable', 'pleased' )
        );
        return apply_filters( 'pleased_switch_options', $arr );
    }
endif;

if ( ! function_exists( 'pleased_hide_options' ) ) :
    /**
     * List of custom Switch Control options
     * @return array List of switch control options.
     */
    function pleased_hide_options() {
        $arr = array(
            'on'        => esc_html__( 'Yes', 'pleased' ),
            'off'       => esc_html__( 'No', 'pleased' )
        );
        return apply_filters( 'pleased_hide_options', $arr );
    }
endif;

if ( ! function_exists( 'pleased_sortable_sections' ) ) :
    /**
     * List of sections Control options
     * @return array List of Sections control options.
     */
    function pleased_sortable_sections() {
        $sections = array(
            'banner'    => esc_html__( 'Banner', 'pleased' ),
            'about'     => esc_html__( 'About Us', 'pleased' ),
            'gallery'   => esc_html__( 'Gallery', 'pleased' ),
            'package'   => esc_html__( 'Package', 'pleased' ),
            'offer'     => esc_html__( 'Offer', 'pleased' ),
            'service'   => esc_html__( 'Services', 'pleased' ),
            'testimonial' => esc_html__( 'Testimonial', 'pleased' ),
            'blog'      => esc_html__( 'Blog', 'pleased' ),
        );
        return apply_filters( 'pleased_sortable_sections', $sections );
    }
endif;

if ( ! function_exists( 'pleased_gallery_content_type' ) ) :
    /**
     * Gallery Options
     * @return array site gallery options
     */
    function pleased_gallery_content_type() {
        $pleased_gallery_content_type = array(
            'category'   => esc_html__( 'Category', 'pleased' ),
        );

        if ( class_exists( 'WP_Travel' ) ) {
            $pleased_gallery_content_type = array_merge( $pleased_gallery_content_type, array(
                'trip-types'    => esc_html__( 'Trip Types', 'pleased' ),
                'destination'   => esc_html__( 'Destination', 'pleased' ),
                'activity'      => esc_html__( 'Activity', 'pleased' ),
                ) );
        }

        $output = apply_filters( 'pleased_gallery_content_type', $pleased_gallery_content_type );


        return $output;
    }
endif;

if ( ! function_exists( 'pleased_package_content_type' ) ) :
    /**
     * Gallery Options
     * @return array site gallery options
     */
    function pleased_package_content_type() {
        $pleased_package_content_type = array(
            'category'  => esc_html__( 'Category', 'pleased' ),
        );

        if ( class_exists( 'WP_Travel' ) ) {
            $pleased_package_content_type = array_merge( $pleased_package_content_type, array(
                'trip-types'    => esc_html__( 'Trip Types', 'pleased' ),
                'destination'   => esc_html__( 'Destination', 'pleased' ),
                'activity'      => esc_html__( 'Activity', 'pleased' ),
                ) );
        }

        $output = apply_filters( 'pleased_package_content_type', $pleased_package_content_type );


        return $output;
    }
endif;

if ( ! function_exists( 'pleased_offer_content_type' ) ) :
    /**
     * Gallery Options
     * @return array site gallery options
     */
    function pleased_offer_content_type() {
        $pleased_offer_content_type = array(
            'page'      => esc_html__( 'Page', 'pleased' ),
        );

        if ( class_exists( 'WP_Travel' ) ) {
            $pleased_offer_content_type = array_merge( $pleased_offer_content_type, array(
                'trip'          => esc_html__( 'Trip', 'pleased' ),
                ) );
        }

        $output = apply_filters( 'pleased_offer_content_type', $pleased_offer_content_type );


        return $output;
    }
endif;