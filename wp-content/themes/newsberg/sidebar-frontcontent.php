<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Newsberg
 */
if ( ! is_active_sidebar( 'front-page-content' ) ) {
	return;
}

$rtActive = is_active_sidebar('front-right-page-sidebar');
$ltActive = is_active_sidebar('front-left-page-sidebar');
?>
<div class="<?php if($ltActive && $rtActive){ echo 'col-md-6'; }

elseif(!$rtActive && !$ltActive) {
  
  echo 'col-md-12';

}

if(!$rtActive && $ltActive)
{
	echo 'col-md-8';
}

if($rtActive && !$ltActive)
{
	echo 'col-md-8';
}

?> col-sm-8">
		<?php dynamic_sidebar( 'front-page-content' ); ?>
</div>