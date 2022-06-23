<?php
/**
 * 404 Page
 *
 * Displayed if a page couldn't be found.
 *
 * @package Page Builder Framework
 */

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

get_header(); ?>

    <div id="content">

        <div id="inner-content" class="wpbf-container wpbf-container-center">

            <main id="main" class="wpbf-main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

                <article id="post-not-found" class="wpbf-post wpbf-404 wpbf-text-center">
                    <?php echo do_action('aepro_404'); ?>
                   <!-- <header class="article-header">
                        <h1 class="entry-title"><?php _e( "404 - This page couldn't be found.", 'page-builder-framework' ); ?></h1>
                    </header>

                    <section class="article-content">

                        <p><?php // _e( "Oops! We're sorry, this page couldn't be found!", 'page-builder-framework' ); ?></p>
                        <div class="wpbf-container-center wpbf-medium-1-2">
                            <?php // get_search_form(); ?>
                        </div>
                    </section> -->

                </article>

            </main>
        </div>

    </div>

<?php get_footer(); ?>