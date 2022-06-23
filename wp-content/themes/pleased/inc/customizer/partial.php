<?php
/**
* Partial functions
*
* @package Theme Palace
* @subpackage Pleased
* @since Pleased 1.0.0
*/

if ( ! function_exists( 'pleased_about_btn_title_partial' ) ) :
    // about btn title
    function pleased_about_btn_title_partial() {
        $options = pleased_get_theme_options();
        return esc_html( $options['about_btn_title'] );
    }
endif;

if ( ! function_exists( 'pleased_package_title_partial' ) ) :
    // package title
    function pleased_package_title_partial() {
        $options = pleased_get_theme_options();
        return esc_html( $options['package_title'] );
    }
endif;

if ( ! function_exists( 'pleased_service_title_partial' ) ) :
    // service title
    function pleased_service_title_partial() {
        $options = pleased_get_theme_options();
        return esc_html( $options['service_title'] );
    }
endif;

if ( ! function_exists( 'pleased_blog_title_partial' ) ) :
    // blog title
    function pleased_blog_title_partial() {
        $options = pleased_get_theme_options();
        return esc_html( $options['blog_title'] );
    }
endif;

if ( ! function_exists( 'pleased_copyright_text_partial' ) ) :
    // copyright text
    function pleased_copyright_text_partial() {
        $options = pleased_get_theme_options();
        return esc_html( $options['copyright_text'] );
    }
endif;
