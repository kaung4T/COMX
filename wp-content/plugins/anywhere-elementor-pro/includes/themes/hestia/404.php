<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package Hestia
 * @since Hestia 1.0
 * @modified 1.1.30
 */

$default_blog_layout        = hestia_sidebar_on_single_post_get_default();
$hestia_blog_sidebar_layout = get_theme_mod( 'hestia_blog_sidebar_layout', $default_blog_layout );
$args                 = array(
    'sidebar-right' => 'col-md-12 archive-post-wrap',
    'sidebar-left'  => 'col-md-12 archive-post-wrap',
    'full-width'    => 'col-md-12 archive-post-wrap',
);
$class_to_add = hestia_get_content_classes( $hestia_blog_sidebar_layout, 'sidebar-1', $args );
get_header();
?>
</header>
<div class="<?php echo hestia_layout(); ?>">
    <div class="hestia-blogs">
        <div class="container">
            <div class="row">
                <div class="<?php echo esc_attr( $class_to_add ); ?>">
                    <?php echo do_action('aepro_404'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php get_footer(); ?>
