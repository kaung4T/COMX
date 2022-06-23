<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

/**
 * pleased_footer_primary_content hook
 *
 * @hooked pleased_add_contact_section -  10
 *
 */
do_action( 'pleased_footer_primary_content' );

/**
 * pleased_content_end_action hook
 *
 * @hooked pleased_content_end -  10
 *
 */
do_action( 'pleased_content_end_action' );

/**
 * pleased_content_end_action hook
 *
 * @hooked pleased_footer_start -  10
 * @hooked Pleased_Footer_Widgets->add_footer_widgets -  20
 * @hooked pleased_footer_site_info -  40
 * @hooked pleased_footer_end -  100
 *
 */
do_action( 'pleased_footer' );

/**
 * pleased_page_end_action hook
 *
 * @hooked pleased_page_end -  10
 *
 */
do_action( 'pleased_page_end_action' ); 

?>

<?php wp_footer(); ?>

</body>
</html>
