<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Newsberg
 */

if ( ! is_active_sidebar( 'front-left-page-sidebar' ) ) {
	return;
}
?>

<aside class="col-md-3">
	<div id="sidebar-left" class="mg-sidebar">
		<?php dynamic_sidebar( 'front-left-page-sidebar' );
		 ?>
	</div>
</aside><!-- #secondary -->
