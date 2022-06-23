<?php
/**
 * The template for displaying all pages, single posts and attachments
 *
 * This is a new template file that WordPress introduced in
 * version 4.3.
 *
 * @package OceanWP WordPress theme
 */

use Elementor\Plugin;
get_header(); ?>

<?php do_action( 'ocean_before_content_wrap' ); ?>

<div id="content-wrap" class="container clr">

    <?php do_action( 'ocean_before_primary' ); ?>

    <div id="primary" class="content-area clr">

        <?php do_action( 'ocean_before_content' ); ?>

        <div id="content" class="site-content clr">

            <?php do_action( 'ocean_before_content_inner' ); ?>

            <?php
            // Start loop
            while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" itemprop="blogPost" itemscope="itemscope" itemtype="http://schema.org/BlogPosting">
                    <?php if(Plugin::instance()->preview->is_preview_mode()){
                        the_content();
                    }else{
                        do_action('aepro_single_data');
                    }
                    ?>
                </article>
                <?php

            endwhile; ?>

            <?php do_action( 'ocean_after_content_inner' ); ?>

        </div><!-- #content -->

        <?php do_action( 'ocean_after_content' ); ?>

    </div><!-- #primary -->

    <?php do_action( 'ocean_after_primary' ); ?>

    <?php do_action( 'ocean_display_sidebar' ); ?>

</div><!-- #content-wrap -->

<?php do_action( 'ocean_after_content_wrap' ); ?>

<?php get_footer(); ?>
