<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Newsup
 */

if ( ! is_active_sidebar( 'front-page-sidebar' ) ) {
	return;
}
?>

<aside class="col-md-4">
	<div id="sidebar-right" class="mg-sidebar">
		<?php dynamic_sidebar( 'front-page-sidebar' );
		 ?>
	</div>
</aside><!-- #secondary -->
