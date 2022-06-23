<?php
/**
 * Customizer active callbacks
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

if ( ! function_exists( 'pleased_is_breadcrumb_enable' ) ) :
	/**
	 * Check if breadcrumb is enabled.
	 *
	 * @since Pleased 1.0.0
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 * @return bool Whether the control is active to the current preview.
	 */
	function pleased_is_breadcrumb_enable( $control ) {
		return $control->manager->get_setting( 'pleased_theme_options[breadcrumb_enable]' )->value();
	}
endif;

if ( ! function_exists( 'pleased_is_pagination_enable' ) ) :
	/**
	 * Check if pagination is enabled.
	 *
	 * @since Pleased 1.0.0
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 * @return bool Whether the control is active to the current preview.
	 */
	function pleased_is_pagination_enable( $control ) {
		return $control->manager->get_setting( 'pleased_theme_options[pagination_enable]' )->value();
	}
endif;

/**
 * Front Page Active Callbacks
 */

/**
 * Check if banner section is enabled.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_banner_section_enable( $control ) {
	return ( $control->manager->get_setting( 'pleased_theme_options[banner_section_enable]' )->value() );
}

/**
 * Check if about section is enabled.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_about_section_enable( $control ) {
	return ( $control->manager->get_setting( 'pleased_theme_options[about_section_enable]' )->value() );
}

/**
 * Check if gallery section is enabled.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_gallery_section_enable( $control ) {
	return ( $control->manager->get_setting( 'pleased_theme_options[gallery_section_enable]' )->value() );
}

/**
 * Check if gallery section content type is category.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_gallery_section_content_category_enable( $control ) {
	$content_type = $control->manager->get_setting( 'pleased_theme_options[gallery_content_type]' )->value();
	return pleased_is_gallery_section_enable( $control ) && ( 'category' == $content_type );
}

/**
 * Check if gallery section content type is activity.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_gallery_section_content_activity_enable( $control ) {
	$content_type = $control->manager->get_setting( 'pleased_theme_options[gallery_content_type]' )->value();
	return pleased_is_gallery_section_enable( $control ) && ( 'activity' == $content_type );
}

/**
 * Check if gallery section content type is destination.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_gallery_section_content_destination_enable( $control ) {
	$content_type = $control->manager->get_setting( 'pleased_theme_options[gallery_content_type]' )->value();
	return pleased_is_gallery_section_enable( $control ) && ( 'destination' == $content_type );
}

/**
 * Check if gallery section content type is trip types.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_gallery_section_content_trip_types_enable( $control ) {
	$content_type = $control->manager->get_setting( 'pleased_theme_options[gallery_content_type]' )->value();
	return pleased_is_gallery_section_enable( $control ) && ( 'trip-types' == $content_type );
}

/**
 * Check if package section is enabled.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_package_section_enable( $control ) {
	return ( $control->manager->get_setting( 'pleased_theme_options[package_section_enable]' )->value() );
}

/**
 * Check if package section content type is category.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_package_section_content_category_enable( $control ) {
	$content_type = $control->manager->get_setting( 'pleased_theme_options[package_content_type]' )->value();
	return pleased_is_package_section_enable( $control ) && ( 'category' == $content_type );
}

/**
 * Check if package section content type is activity.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_package_section_content_activity_enable( $control ) {
	$content_type = $control->manager->get_setting( 'pleased_theme_options[package_content_type]' )->value();
	return pleased_is_package_section_enable( $control ) && ( 'activity' == $content_type );
}

/**
 * Check if package section content type is destination.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_package_section_content_destination_enable( $control ) {
	$content_type = $control->manager->get_setting( 'pleased_theme_options[package_content_type]' )->value();
	return pleased_is_package_section_enable( $control ) && ( 'destination' == $content_type );
}

/**
 * Check if package section content type is trip types.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_package_section_content_trip_types_enable( $control ) {
	$content_type = $control->manager->get_setting( 'pleased_theme_options[package_content_type]' )->value();
	return pleased_is_package_section_enable( $control ) && ( 'trip-types' == $content_type );
}

/**
 * Check if offer section is enabled.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_offer_section_enable( $control ) {
	return ( $control->manager->get_setting( 'pleased_theme_options[offer_section_enable]' )->value() );
}

/**
 * Check if offer section content type is page.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_offer_section_content_page_enable( $control ) {
	$content_type = $control->manager->get_setting( 'pleased_theme_options[offer_content_type]' )->value();
	return pleased_is_offer_section_enable( $control ) && ( 'page' == $content_type );
}

/**
 * Check if offer section content type is trip.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_offer_section_content_trip_enable( $control ) {
	$content_type = $control->manager->get_setting( 'pleased_theme_options[offer_content_type]' )->value();
	return pleased_is_offer_section_enable( $control ) && ( 'trip' == $content_type );
}

/**
 * Check if service section is enabled.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_service_section_enable( $control ) {
	return ( $control->manager->get_setting( 'pleased_theme_options[service_section_enable]' )->value() );
}

/**
 * Check if testimonial section is enabled.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_testimonial_section_enable( $control ) {
	return ( $control->manager->get_setting( 'pleased_theme_options[testimonial_section_enable]' )->value() );
}

/**
 * Check if blog section is enabled.
 *
 * @since Pleased 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function pleased_is_blog_section_enable( $control ) {
	return ( $control->manager->get_setting( 'pleased_theme_options[blog_section_enable]' )->value() );
}
