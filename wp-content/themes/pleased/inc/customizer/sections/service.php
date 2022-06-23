<?php
/**
 * Service Section options
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

// Add Service section
$wp_customize->add_section( 'pleased_service_section', array(
	'title'             => esc_html__( 'Services','pleased' ),
	'description'       => esc_html__( 'Services Section options.', 'pleased' ),
	'panel'             => 'pleased_front_page_panel',
) );

// Service content enable control and setting
$wp_customize->add_setting( 'pleased_theme_options[service_section_enable]', array(
	'default'			=> 	$options['service_section_enable'],
	'sanitize_callback' => 'pleased_sanitize_switch_control',
) );

$wp_customize->add_control( new Pleased_Switch_Control( $wp_customize, 'pleased_theme_options[service_section_enable]', array(
	'label'             => esc_html__( 'Service Section Enable', 'pleased' ),
	'section'           => 'pleased_service_section',
	'on_off_label' 		=> pleased_switch_options(),
) ) );

// service title setting and control
$wp_customize->add_setting( 'pleased_theme_options[service_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['service_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'pleased_theme_options[service_title]', array(
	'label'           	=> esc_html__( 'Title', 'pleased' ),
	'section'        	=> 'pleased_service_section',
	'active_callback' 	=> 'pleased_is_service_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'pleased_theme_options[service_title]', array(
		'selector'            => '#services .section-header h2.section-title',
		'settings'            => 'pleased_theme_options[service_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'pleased_service_title_partial',
    ) );
}

for ( $i = 1; $i <= 3; $i++ ) :

	// service note control and setting
	$wp_customize->add_setting( 'pleased_theme_options[service_content_icon_' . $i . ']', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( new Pleased_Icon_Picker( $wp_customize, 'pleased_theme_options[service_content_icon_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Select Icon %d', 'pleased' ), $i ),
		'section'           => 'pleased_service_section',
		'active_callback'	=> 'pleased_is_service_section_enable',
	) ) );

	// service pages drop down chooser control and setting
	$wp_customize->add_setting( 'pleased_theme_options[service_content_page_' . $i . ']', array(
		'sanitize_callback' => 'pleased_sanitize_page',
	) );

	$wp_customize->add_control( new Pleased_Dropdown_Chooser( $wp_customize, 'pleased_theme_options[service_content_page_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Select Page %d', 'pleased' ), $i ),
		'section'           => 'pleased_service_section',
		'choices'			=> pleased_page_choices(),
		'active_callback'	=> 'pleased_is_service_section_enable',
	) ) );

	// service hr setting and control
	$wp_customize->add_setting( 'pleased_theme_options[service_hr_'. $i .']', array(
		'sanitize_callback' => 'sanitize_text_field'
	) );

	$wp_customize->add_control( new Pleased_Customize_Horizontal_Line( $wp_customize, 'pleased_theme_options[service_hr_'. $i .']',
		array(
			'section'         => 'pleased_service_section',
			'active_callback' => 'pleased_is_service_section_enable',
			'type'			  => 'hr'
	) ) );
endfor;
