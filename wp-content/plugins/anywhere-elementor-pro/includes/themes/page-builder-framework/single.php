<?php
/**
 * Single
 *
 * @package Page Builder Framework
 */
use Elementor\Plugin;
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$grid_gap = get_theme_mod( 'sidebar_gap' );
$grid_gap ? true : $grid_gap = "divider";
$template_parts_header = get_theme_mod( 'single_sortable_header', array( 'title', 'categories', 'featured' ) );
$template_parts_footer = get_theme_mod( 'single_sortable_footer', array( 'categories', 'tags' ) );

get_header(); ?>

    <div id="content">

        <?php if( !is_singular( 'elementor_library' ) && !is_singular( 'et_pb_layout' ) ) : ?>

            <?php wpbf_inner_content(); ?>

            <div class="wpbf-grid wpbf-grid-<?php echo esc_attr( $grid_gap ); ?>">

                <?php do_action( 'wpbf_sidebar_left' ); ?>

                <main id="main" class="wpbf-main wpbf-single-content wpbf-medium-2-3" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemprop="blogPost" itemtype="http://schema.org/BlogPosting">

                            <div class="inside-article">
                                <?php if(Plugin::instance()->preview->is_preview_mode()){
                                    the_content();
                                }else{
                                    do_action('aepro_single_data');
                                }
                                ?>
                            </div><!-- .inside-article -->

                        </article>

                    <?php endwhile; else : ?>

                        <article id="post-not-found" class="wpbf-post">

                            <header class="article-header">
                                <h1 class="entry-title"><?php _e( "Oops, this article couldn't be found!", 'page-builder-framework' ); ?></h1>
                            </header>

                            <section class="article-content">
                                <p><?php _e( 'Something went wrong.', 'page-builder-framework' ); ?></p>
                            </section>

                        </article>

                    <?php endif; ?>

                </main>

                <?php do_action( 'wpbf_sidebar_right' ); ?>

            </div>

            <?php wpbf_inner_content_close(); ?>

        <?php else : ?>

            <?php the_content(); ?>

        <?php endif; ?>

    </div>

<?php get_footer(); ?>