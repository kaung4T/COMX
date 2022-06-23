<?php
/**
 * About section
 *
 * This is the template for the content of about section
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */
if ( ! function_exists( 'pleased_add_about_section' ) ) :
    /**
    * Add about section
    *
    *@since Pleased 1.0.0
    */
    function pleased_add_about_section() {
    	$options = pleased_get_theme_options();
        // Check if about is enabled on frontpage
        $about_enable = apply_filters( 'pleased_section_status', true, 'about_section_enable' );

        if ( true !== $about_enable ) {
            return false;
        }
        // Get about section details
        $section_details = array();
        $section_details = apply_filters( 'pleased_filter_about_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render about section now.
        pleased_render_about_section( $section_details );
    }
endif;

if ( ! function_exists( 'pleased_get_about_section_details' ) ) :
    /**
    * about section details.
    *
    * @since Pleased 1.0.0
    * @param array $input about section details.
    */
    function pleased_get_about_section_details( $input ) {
        $options = pleased_get_theme_options();

        // Content type.
        
        $content = array();
        $page_id = ! empty( $options['about_content_page'] ) ? $options['about_content_page'] : '';
        $args = array(
            'post_type'         => 'page',
            'page_id'           => $page_id,
            'posts_per_page'    => 1,
            );                    

        // Run The Loop.
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
                $page_post['title']     = get_the_title();
                $page_post['url']       = get_the_permalink();
                $page_post['excerpt']   = pleased_trim_content( 50 );

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
// about section content details.
add_filter( 'pleased_filter_about_section_details', 'pleased_get_about_section_details' );


if ( ! function_exists( 'pleased_render_about_section' ) ) :
  /**
   * Start about section
   *
   * @return string about content
   * @since Pleased 1.0.0
   *
   */
   function pleased_render_about_section( $content_details = array() ) {
        $options = pleased_get_theme_options();
        $readmore = ! empty( $options['about_btn_title'] ) ? $options['about_btn_title'] : '';

        if ( empty( $content_details ) ) {
            return;
        } 

        foreach ( $content_details as $content ) :
        ?>
            <div id="about-us" class="relative page-section align-center">
                <div class="wrapper">
                    <div class="entry-container">
                        <div class="section-header">
                            <?php if ( ! empty( $content['title'] ) ) : ?>
                                <h2 class="section-title"><?php echo esc_html( $content['title'] ); ?></h2>
                            <?php endif; ?>
                        </div><!-- .section-header -->

                        <div class="section-content">
                                <?php if ( ! empty( $content['excerpt'] ) ) : ?>
                                    <div class="entry-content">
                                        <p><?php echo wp_kses_post( $content['excerpt'] ); ?></p>
                                    </div><!-- .entry-content -->
                                <?php endif; 

                                if ( ! empty( $content['url'] ) && ! empty( $readmore ) ) : ?>
                                    <a href="<?php echo esc_url( $content['url'] ); ?>" class="btn btn-default"><?php echo esc_html( $readmore ); ?></a>
                                <?php endif; ?>
                        </div><!-- .section-content -->
                    </div>
                </div><!-- .wrapper -->
            </div><!-- #about-us -->

        <?php endforeach;
    }
endif;