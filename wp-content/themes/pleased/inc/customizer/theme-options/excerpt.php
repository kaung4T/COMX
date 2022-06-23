<?php
/**
 * Excerpt options
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

// Add excerpt section
$wp_customize->add_section( 'pleased_excerpt_section', array(
	'title'             => esc_html__( 'Excerpt','pleased' ),
	'description'       => esc_html__( 'Excerpt section options.', 'pleased' ),
	'panel'             => 'pleased_theme_options_panel',
) );


// long Excerpt length setting and control.
$wp_customize->add_setting( 'pleased_theme_options[long_excerpt_length]', array(
	'sanitize_callback' => 'pleased_sanitize_number_range',
	'validate_callback' => 'pleased_validate_long_excerpt',
	'default'			=> $options['long_excerpt_length'],
) );

$wp_customize->add_control( 'pleased_theme_options[long_excerpt_length]', array(
	'label'       		=> esc_html__( 'Blog Page Excerpt Length', 'pleased' ),
	'description' 		=> esc_html__( 'Total words if sticky post to be displayed in blog page.', 'pleased' ),
	'section'     		=> 'pleased_excerpt_section',
	'type'        		=> 'number',
	'input_attrs' 		=> array(
		'style'       => 'width: 80px;',
		'max'         => 100,
		'min'         => 5,
	),
) );

// read more text
$wp_customize->add_setting( 'pleased_theme_options[read_more_text]',
	array(
		'default'       		=> $options['read_more_text'],
		'sanitize_callback'		=> 'sanitize_text_field',
		'transport'				=> 'postMessage',
	)
);
$wp_customize->add_control( 'pleased_theme_options[read_more_text]',
    array(
		'label'      			=> esc_html__( 'Read More Text', 'pleased' ),
		'description'      		=> esc_html__( 'Button label for sticky posts in blog page.', 'pleased' ),
		'section'    			=> 'pleased_excerpt_section',
		'type'		 			=> 'text',
    )
);