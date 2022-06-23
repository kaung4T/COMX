<?php /**
 // Template Name: Frontpage
 */
get_header();
?>
<div id="content" class="container-fluid home">
     <!--row-->
      <div class="row">
<?php  
get_template_part('sidebar','frontpageleft');
get_template_part('sidebar','frontcontent');
get_template_part('sidebar','frontpageright');
?> 
	</div>
</div>
<?php get_footer(); ?>