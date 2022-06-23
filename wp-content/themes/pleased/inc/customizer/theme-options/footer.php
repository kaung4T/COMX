<?php
/**
 * Footer options
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

// Footer Section
$wp_customize->add_section( 'pleased_section_footer',
	array(
		'title'      			=> esc_html__( 'Footer Options', 'pleased' ),
		'priority'   			=> 900,
		'panel'      			=> 'pleased_theme_options_panel',
	)
);

// footer text
$wp_customize->add_setting( 'pleased_theme_options[copyright_text]',
	array(
		'default'       		=> $options['copyright_text'],
		'sanitize_callback'		=> 'pleased_santize_allow_tag',
		'transport'				=> 'postMessage',
	)
);
$wp_customize->add_control( 'pleased_theme_options[copyright_text]',
    array(
		'label'      			=> esc_html__( 'Copyright Text', 'pleased' ),
		'section'    			=> 'pleased_section_footer',
		'type'		 			=> 'textarea',
    )
);

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'pleased_theme_options[copyright_text]', array(
		'selector'            => '.site-info .copyright p',
		'settings'            => 'pleased_theme_options[copyright_text]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'pleased_copyright_text_partial',
    ) );
}

// scroll top visible
$wp_customize->add_setting( 'pleased_theme_options[scroll_top_visible]',
	array(
		'default'       		=> $options['scroll_top_visible'],
		'sanitize_callback' => 'pleased_sanitize_switch_control',
	)
);
$wp_customize->add_control( new Pleased_Switch_Control( $wp_customize, 'pleased_theme_options[scroll_top_visible]',
    array(
		'label'      			=> esc_html__( 'Display Scroll Top Button', 'pleased' ),
		'section'    			=> 'pleased_section_footer',
		'on_off_label' 		=> pleased_switch_options(),
    )
) );