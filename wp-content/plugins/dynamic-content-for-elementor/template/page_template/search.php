<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
get_header();
$dce_default_options = get_option( DCE_OPTIONS ); 
//
$global_is = 'search';
//
$dce_before_template = null;
if( isset($dce_default_options['dyncontel_field_singlesearch']) ) $dce_before_template = $dce_default_options['dyncontel_field_singlesearch'];
if( isset($dce_default_options['dyncontel_before_field_archivesearch']) ) $dce_before_template = $dce_default_options['dyncontel_before_field_archivesearch'];
//
$dce_block_template = 'dyncontel_field_archivesearch';
//
$dce_default_template = $dce_default_options[$dce_block_template]; //$this->options[$dce_block_template];
//
$dce_template_layout = $dce_default_options[$dce_block_template.'_template'];
//
$dce_col_md = $dce_default_options[$dce_block_template.'_col_md'];
$dce_col_sm = $dce_default_options[$dce_block_template.'_col_sm'];
$dce_col_xs = $dce_default_options[$dce_block_template.'_col_xs'];
?>
<div class="search-container">
    <main id="main" class="site-main" role="main">
        <div id="content-wrap" class="container clr">
        <?php
        // -------- quasta è la pagina del template che viene impostata nei settings di User -----------
        if (isset($dce_before_template) && $dce_before_template > 1 ) {
          echo do_shortcode('[dce-elementor-template id="' . $dce_before_template . '"]');
        }
        if ($dce_template_layout == 'canvas') {
            echo do_shortcode('[dce-elementor-template id="' . $dce_default_template . '"]');
        } else {
            ?>
            <h2><span class="icon-magnifier"></span> <?php echo __('Search') ?></h2>
            <?php
            if ( have_posts() ) : ?>

                <header class="page-header">
                    <span class="search-page-title"><?php printf( __( 'Search for: %s' ), get_search_query() ); ?></span>
                </header><!-- .page-header -->

                <div class="grid-search-page grid-page grid-col-md-<?php echo $dce_col_md; ?> grid-col-sm-<?php echo $dce_col_sm; ?> grid-col-xs-<?php echo $dce_col_xs; ?>">
               
                <?php while ( have_posts() ) : the_post();
                    
                    echo '<div class="item-search-page item-page">';
                            the_content();
                       echo '</div>';
                   
                endwhile; ?>
                </div>
                <?php //\Elementor\Plugin::$instance->modules_manager->get_modules( 'page-templates' )->print_content(); ?>
                <?php \DynamicContentForElementor\DCE_Helper::dce_numeric_posts_nav(); ?>

            <?php else : ?>
                 <header class="page-header">
                    <span class="search-page-title"><?php printf( __( 'Search for: %s', 'dynamic-content-for-elementor'.'_texts'), '<span>' . get_search_query() . '</span>' ); ?></span>
                </header><!-- .page-header -->
                 <h3 class="no-results"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <?php echo __('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'dynamic-content-for-elementor'.'_texts') ?></h3>
                 <div class="search-page-form" id="ss-search-page-form">
                    <?php get_search_form(); ?>
                      
                    </div>
                <?php //get_template_part( 'template-parts/content', 'none' ); ?>

            <?php endif; 
        }
        ?>
        </div>
    </main><!-- #main -->
</div>
<?php
/**
 * After Header-Footer page template content.
 *
 * Fires after the content of Elementor Header-Footer page template.
 *
 * @since 2.0.0
 */
do_action( 'elementor/page_templates/header-footer/after_content' );

get_footer();