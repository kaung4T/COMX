<?php
/**
 * Theme Palace widgets inclusion
 *
 * This is the template that includes all custom widgets of Pleased
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

/*
 * Add social link widget
 */
require get_template_directory() . '/inc/widgets/social-link-widget.php';
/*
 * Add Latest Posts widget
 */
require get_template_directory() . '/inc/widgets/latest-posts-widget.php';

/**
 * Register widgets
 */
function pleased_register_widgets() {

	register_widget( 'Pleased_Latest_Post' );

	register_widget( 'Pleased_Social_Link' );

}
add_action( 'widgets_init', 'pleased_register_widgets' );