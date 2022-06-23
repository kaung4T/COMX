<?php
if( ! function_exists( 'newsup_register_custom_controls' ) ) :
/**
 * Register Custom Controls
*/
function newsup_register_custom_controls( $wp_customize ) {

    require_once get_template_directory() . '/inc/ansar/custom-control/toggle/class-toggle-control.php';
    require_once get_template_directory() . '/inc/ansar/custom-control/customizer-alpha-color-picker/class-newsup-customize-alpha-color-control.php';

    $wp_customize->register_control_type( 'Newsup_Toggle_Control' );

}
endif;
add_action( 'customize_register', 'newsup_register_custom_controls' );