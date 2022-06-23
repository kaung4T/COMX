<?php
/**
 * Blog Section options
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

// Add Blog section
$wp_customize->add_section( 'pleased_blog_section', array(
	'title'             => esc_html__( 'Blog','pleased' ),
	'description'       => esc_html__( 'Blog Section options.', 'pleased' ),
	'panel'             => 'pleased_front_page_panel',
) );

// Blog content enable control and setting
$wp_customize->add_setting( 'pleased_theme_options[blog_section_enable]', array(
	'default'			=> 	$options['blog_section_enable'],
	'sanitize_callback' => 'pleased_sanitize_switch_control',
) );

$wp_customize->add_control( new Pleased_Switch_Control( $wp_customize, 'pleased_theme_options[blog_section_enable]', array(
	'label'             => esc_html__( 'Blog Section Enable', 'pleased' ),
	'section'           => 'pleased_blog_section',
	'on_off_label' 		=> pleased_switch_options(),
) ) );

// blog title setting and control
$wp_customize->add_setting( 'pleased_theme_options[blog_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['blog_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'pleased_theme_options[blog_title]', array(
	'label'           	=> esc_html__( 'Title', 'pleased' ),
	'section'        	=> 'pleased_blog_section',
	'active_callback' 	=> 'pleased_is_blog_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'pleased_theme_options[blog_title]', array(
		'selector'            => '#latest-blog .section-header h2.section-title',
		'settings'            => 'pleased_theme_options[blog_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'pleased_blog_title_partial',
    ) );
}


// Add dropdown categories setting and control.
$wp_customize->add_setting( 'pleased_theme_options[blog_category_exclude]', array(
	'sanitize_callback' => 'pleased_sanitize_category_list',
) ) ;

$wp_customize->add_control( new Pleased_Dropdown_Category_Control( $wp_customize,'pleased_theme_options[blog_category_exclude]', array(
	'label'             => esc_html__( 'Select Excluding Categories', 'pleased' ),
	'description'      	=> esc_html__( 'Note: Select categories to exclude. Press Shift key select multilple categories.', 'pleased' ),
	'section'           => 'pleased_blog_section',
	'type'              => 'dropdown-categories',
	'active_callback'	=> 'pleased_is_blog_section_enable'
) ) );

// blog btn title setting and control
$wp_customize->add_setting( 'pleased_theme_options[blog_btn_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['blog_btn_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'pleased_theme_options[blog_btn_title]', array(
	'label'           	=> esc_html__( 'Button Label', 'pleased' ),
	'section'        	=> 'pleased_blog_section',
	'active_callback' 	=> 'pleased_is_blog_section_enable',
	'type'				=> 'text',
) );
