<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Newsup
 */
if ( ! is_active_sidebar( 'front-page-content' ) ) {
	return;
}
?>
<div class="<?php if( !is_active_sidebar('front-page-sidebar')) { echo "col-md-12"; } else { echo "col-md-8"; } ?> col-sm-8">
	<div class="">
		<?php dynamic_sidebar( 'front-page-content' );
		 ?>
	</div>
</div>