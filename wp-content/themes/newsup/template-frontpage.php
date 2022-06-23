<?php /**
 // Template Name: Frontpage
 */
get_header();
?>
<div id="content" class="container-fluid home">
     <!--row-->
      <div class="row">
<?php  get_template_part('sidebar','frontcontent'); ?>
<?php
get_template_part('sidebar','frontpage');
?> 
	</div>
</div>
<?php get_footer(); ?>