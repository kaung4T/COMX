<?php
/**
 * Testimonial Section options
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

// Add Testimonial section
$wp_customize->add_section( 'pleased_testimonial_section', array(
	'title'             => esc_html__( 'Testimonial','pleased' ),
	'description'       => esc_html__( 'Testimonial Section options.', 'pleased' ),
	'panel'             => 'pleased_front_page_panel',
) );

// Testimonial content enable control and setting
$wp_customize->add_setting( 'pleased_theme_options[testimonial_section_enable]', array(
	'default'			=> 	$options['testimonial_section_enable'],
	'sanitize_callback' => 'pleased_sanitize_switch_control',
) );

$wp_customize->add_control( new Pleased_Switch_Control( $wp_customize, 'pleased_theme_options[testimonial_section_enable]', array(
	'label'             => esc_html__( 'Testimonial Section Enable', 'pleased' ),
	'section'           => 'pleased_testimonial_section',
	'on_off_label' 		=> pleased_switch_options(),
) ) );

// Testimonial image setting and control.
$wp_customize->add_setting( 'pleased_theme_options[testimonial_background_image]', array(
	'sanitize_callback' => 'pleased_sanitize_image',
	'default'          	=> $options['testimonial_background_image'],
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'pleased_theme_options[testimonial_background_image]',
		array(
		'label'       		=> esc_html__( 'Background Image', 'pleased' ),
		'description' 		=> sprintf( esc_html__( 'Recommended size: %1$dpx x %2$dpx ', 'pleased' ), 1920, 1080 ),
		'section'     		=> 'pleased_testimonial_section',
		'active_callback'	=> 'pleased_is_testimonial_section_enable',
) ) );

// Testimonial image setting and control.
$wp_customize->add_setting( 'pleased_theme_options[testimonial_seperator_image]', array(
	'sanitize_callback' => 'pleased_sanitize_image',
	'default'          	=> $options['testimonial_seperator_image'],
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'pleased_theme_options[testimonial_seperator_image]',
		array(
		'label'       		=> esc_html__( 'Seperator Image', 'pleased' ),
		'description' 		=> sprintf( esc_html__( 'Recommended size: %1$dpx x %2$dpx ', 'pleased' ), 330, 550 ),
		'section'     		=> 'pleased_testimonial_section',
		'active_callback'	=> 'pleased_is_testimonial_section_enable',
) ) );

for ( $i = 1; $i <= 2; $i++ ) :
	// testimonial pages drop down chooser control and setting
	$wp_customize->add_setting( 'pleased_theme_options[testimonial_content_page_' . $i . ']', array(
		'sanitize_callback' => 'pleased_sanitize_page',
	) );

	$wp_customize->add_control( new Pleased_Dropdown_Chooser( $wp_customize, 'pleased_theme_options[testimonial_content_page_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Select Page %d', 'pleased' ), $i ),
		'section'           => 'pleased_testimonial_section',
		'choices'			=> pleased_page_choices(),
		'active_callback'	=> 'pleased_is_testimonial_section_enable',
	) ) );
endfor;
