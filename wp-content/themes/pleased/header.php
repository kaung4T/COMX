<?php
	/**
	 * The header for our theme.
	 *
	 * This is the template that displays all of the <head> section and everything up until <div id="content">
	 *
	 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
	 *
	 * @package Theme Palace
	 * @subpackage Pleased
	 * @since Pleased 1.0.0
	 */

	/**
	 * pleased_doctype hook
	 *
	 * @hooked pleased_doctype -  10
	 *
	 */
	do_action( 'pleased_doctype' );

?>
<head>
<?php
	/**
	 * pleased_before_wp_head hook
	 *
	 * @hooked pleased_head -  10
	 *
	 */
	do_action( 'pleased_before_wp_head' );

	wp_head(); 
?>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>
<?php
	/**
	 * pleased_page_start_action hook
	 *
	 * @hooked pleased_page_start -  10
	 *
	 */
	do_action( 'pleased_page_start_action' ); 

	/**
	 * pleased_loader_action hook
	 *
	 * @hooked pleased_loader -  10
	 *
	 */
	do_action( 'pleased_before_header' );

	/**
	 * pleased_header_action hook
	 *
	 * @hooked pleased_header_start -  10
	 * @hooked pleased_site_branding -  20
	 * @hooked pleased_site_navigation -  30
	 * @hooked pleased_header_end -  50
	 *
	 */
	do_action( 'pleased_header_action' );

	/**
	 * pleased_content_start_action hook
	 *
	 * @hooked pleased_content_start -  10
	 *
	 */
	do_action( 'pleased_content_start_action' );

	/**
	 * pleased_header_image_action hook
	 *
	 * @hooked pleased_header_image -  10
	 *
	 */
	do_action( 'pleased_header_image_action' );

    if ( pleased_is_frontpage() ) {

    	$sections = pleased_sortable_sections();
    	$i = 1;
		foreach ( $sections as $section => $value ) {
			add_action( 'pleased_primary_content', 'pleased_add_'. $section .'_section', $i . 0 );
			$i++;
		}
		do_action( 'pleased_primary_content' );
	}