<?php
/**
 * Banner section
 *
 * This is the template for the content of banner section
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */
if ( ! function_exists( 'pleased_add_banner_section' ) ) :
    /**
    * Add banner section
    *
    *@since Pleased 1.0.0
    */
    function pleased_add_banner_section() {
    	$options = pleased_get_theme_options();
        // Check if banner is enabled on frontpage
        $banner_enable = apply_filters( 'pleased_section_status', true, 'banner_section_enable' );

        if ( true !== $banner_enable ) {
            return false;
        }
        // Get banner section details
        $section_details = array();
        $section_details = apply_filters( 'pleased_filter_banner_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render banner section now.
        pleased_render_banner_section( $section_details );
    }
endif;

if ( ! function_exists( 'pleased_get_banner_section_details' ) ) :
    /**
    * banner section details.
    *
    * @since Pleased 1.0.0
    * @param array $input banner section details.
    */
    function pleased_get_banner_section_details( $input ) {
        $options = pleased_get_theme_options();

        $content = array();
        $page_id = ! empty( $options['banner_content_page'] ) ? $options['banner_content_page'] : '';
        $args = array(
            'post_type'         => 'page',
            'page_id'           => $page_id,
            'posts_per_page'    => 1,
            );                    

        // Run The Loop.
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
                $page_post['id']        = get_the_id();
                $page_post['title']     = get_the_title();
                $page_post['url']       = get_the_permalink();
                $page_post['image']  	= has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'full' ) : get_template_directory_uri() . '/assets/uploads/header-image.jpg';

                // Push to the main array.
                array_push( $content, $page_post );
            endwhile;
        endif;
        wp_reset_postdata();
            
        if ( ! empty( $content ) ) {
            $input = $content;
        }
        return $input;
    }
endif;
// banner section content details.
add_filter( 'pleased_filter_banner_section_details', 'pleased_get_banner_section_details' );


if ( ! function_exists( 'pleased_render_banner_section' ) ) :
  /**
   * Start banner section
   *
   * @return string banner content
   * @since Pleased 1.0.0
   *
   */
   function pleased_render_banner_section( $content_details = array() ) {
        $options = pleased_get_theme_options();

        if ( empty( $content_details ) ) {
            return;
        } 

        foreach ( $content_details as $content ) : ?>

            <div id="header-featured-image">
                <div class="overlay"></div>

                <?php if ( ! empty( $content['image'] ) ) : ?>
                    <div class="wp-custom-header">
                        <img src="<?php echo esc_url( $content['image'] ); ?>" alt="<?php echo esc_attr( $content['title'] ); ?>">
                    </div><!--.wp-custom-header -->
                <?php endif; ?>

                <div class="wrapper">

                    <div class="wp-custom-content">

                        <section class="section-header">
                            <?php if ( ! empty( $content['title'] ) ) : ?>
                                <h2 class="section-title"><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h2>
                            <?php endif; ?>
                        </section><!-- .section-header -->

                    </div><!-- .wp-custom-content -->
                </div>
            </div><!-- #header-featured-image --> 

        <?php endforeach; 
    }
endif;