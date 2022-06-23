<?php
/**
 * The template for displaying all single posts and attachments.
 *
 * @package Hestia
 * @since Hestia 1.0
 */
use Elementor\Plugin;
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
</header>
<div class="<?php echo hestia_layout(); ?>">
    <div class="blog-post blog-post-wrapper">
        <div class="container">
            <?php
            if ( have_posts() ) :
                    if(Plugin::instance()->preview->is_preview_mode()){
                        the_content();
                    }else{
                        do_action('aepro_single_data');
                    }
            else :
                get_template_part( 'template-parts/content', 'none' );
            endif;
            ?>
        </div>
    </div>
</div>
<?php do_action( 'hestia_blog_related_posts' ); ?>
<div class="footer-wrapper">
    <?php get_footer(); ?>