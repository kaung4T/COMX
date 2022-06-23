<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @package Newsup
 */
get_header(); ?>
<!--==================== Newsup breadcrumb section ====================-->
<?php get_template_part('index','banner'); ?>
<div id="content">
    <!--container-->
    <div class="container-fluid">
    <!--row-->
        <div class="row">
            <!--col-md-8-->
                <?php 
                $newsup_content_layout = esc_attr(get_theme_mod('newsup_content_layout','align-content-right'));
                if($newsup_content_layout == "align-content-left")
                { ?>
                <aside class="col-md-4">
                    <?php get_sidebar();?>
                </aside>
                <?php } ?>
                <?php if($newsup_content_layout == "align-content-right"){
                ?>
                <div class="col-md-8">
                <?php } elseif($newsup_content_layout == "align-content-left") { ?>
                <div class="col-md-8">
                <?php } elseif($newsup_content_layout == "full-width-content") { ?>
                 <div class="col-md-12">
                 <?php } get_template_part('content',''); ?>   
                </div> <!--/col-md-8-->
                <?php if($newsup_content_layout == "align-content-right") { ?>
                <!--col-md-4-->
                <aside class="col-md-4">
                    <?php get_sidebar();?>
                </aside>
                <!--/col-md-4-->
                <?php } ?>
            </div><!--/row-->
    </div><!--/container-->
</div>
<?php
get_footer();
?>