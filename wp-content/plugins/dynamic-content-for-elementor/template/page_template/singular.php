<?php
/**
 * The template for displaying all pages, single posts and attachments
 *
 * This is a new template file that WordPress introduced in
 * version 4.3.
 *
 * @package Dynamic Content Elements
 */
get_header();
?>
<div id="page-template-singular" class="wrap full-width">
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            DCE SINGULAR
            <?php
            /* Start the Loop */
            while (have_posts()) : the_post();
                ?>
                <div class="entry-content">
                    <?php
                    the_content();
                    ?>
                </div><!-- .entry-content -->	
                <?php
            endwhile; // End of the loop.
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->
</div><!-- .wrap -->
<?php get_footer(); ?>