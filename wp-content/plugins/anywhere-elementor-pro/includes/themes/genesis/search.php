<?php
use Elementor\Plugin;
get_header();

do_action( 'genesis_before_content_sidebar_wrap' );

genesis_markup( array(
    'open'   => '<div %s>',
    'context' => 'content-sidebar-wrap',
) );


do_action( 'genesis_before_content' );
genesis_markup( array(
    'open'   => '<main %s>',
    'context' => 'content',
) );
do_action( 'genesis_before_loop' );

                    if ( have_posts() ) :
                        echo do_action('ae_pro_search');
                    else :
                        get_template_part( 'template-parts/content', 'none' );
                    endif;

do_action( 'genesis_after_loop' );
genesis_markup( array(
    'close' => '</main>', // End .content.
    'context' => 'content',
) );
do_action( 'genesis_after_content' );

genesis_markup( array(
    'close'   => '</div>',
    'context' => 'content-sidebar-wrap',
) );

do_action( 'genesis_after_content_sidebar_wrap' );

get_footer();