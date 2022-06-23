<?php
/**
 * Testimonial section
 *
 * This is the template for the content of testimonial section
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */
if ( ! function_exists( 'pleased_add_testimonial_section' ) ) :
    /**
    * Add testimonial section
    *
    *@since Pleased 1.0.0
    */
    function pleased_add_testimonial_section() {
    	$options = pleased_get_theme_options();
        // Check if testimonial is enabled on frontpage
        $testimonial_enable = apply_filters( 'pleased_section_status', true, 'testimonial_section_enable' );

        if ( true !== $testimonial_enable ) {
            return false;
        }
        // Get testimonial section details
        $section_details = array();
        $section_details = apply_filters( 'pleased_filter_testimonial_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render testimonial section now.
        pleased_render_testimonial_section( $section_details );
    }
endif;

if ( ! function_exists( 'pleased_get_testimonial_section_details' ) ) :
    /**
    * testimonial section details.
    *
    * @since Pleased 1.0.0
    * @param array $input testimonial section details.
    */
    function pleased_get_testimonial_section_details( $input ) {
        $options = pleased_get_theme_options();

        $content = array();
        $page_ids = array();

        for ( $i = 1; $i <= 2; $i++ ) {
            if ( ! empty( $options['testimonial_content_page_' . $i] ) ) :
                $page_ids[] = $options['testimonial_content_page_' . $i];
            endif;
        }
        
        $args = array(
            'post_type'         => 'page',
            'post__in'          => ( array ) $page_ids,
            'posts_per_page'    => 2,
            'orderby'           => 'post__in',
            );                    

        // Run The Loop.
        $query = new WP_Query( $args );
        $i = 0;
        if ( $query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
                $page_post['title']     = get_the_title();
                $page_post['url']       = get_the_permalink();
                $page_post['excerpt']   = pleased_trim_content( 30 );
                $page_post['image']  	= has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'thumbnail' ) : '';

                // Push to the main array.
                array_push( $content, $page_post );
                $i++;
            endwhile;
        endif;
        wp_reset_postdata();
            
        if ( ! empty( $content ) ) {
            $input = $content;
        }
        return $input;
    }
endif;
// testimonial section content details.
add_filter( 'pleased_filter_testimonial_section_details', 'pleased_get_testimonial_section_details' );


if ( ! function_exists( 'pleased_render_testimonial_section' ) ) :
  /**
   * Start testimonial section
   *
   * @return string testimonial content
   * @since Pleased 1.0.0
   *
   */
   function pleased_render_testimonial_section( $content_details = array() ) {
        $options = pleased_get_theme_options();
        $background = ! empty( $options['testimonial_background_image'] ) ? $options['testimonial_background_image'] : get_template_directory_uri() . '/assets/uploads/testimonial.jpg';
        $seperator = ! empty( $options['testimonial_seperator_image'] ) ? $options['testimonial_seperator_image'] : get_template_directory_uri() . '/assets/uploads/testimonial-01.jpg';

        if ( empty( $content_details ) ) {
            return;
        } ?>

        <div id="testimonial" class="page-section col-2" style="background-image: url('<?php echo esc_url( $background ); ?>');">
            <div class="wrapper">
                <div class="testimonial-wrapper" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "infinite": true, "speed": 1000, "dots": true, "arrows":false, "autoplay": true, "fade": true }'>
                    <?php foreach ( $content_details as $content ) : ?>
                        <article>
                            <div class="hentry">
                                <?php if ( ! empty( $content['image'] ) ) : ?>
                                    <img src="<?php echo esc_url( $content['image'] ); ?>" alt="<?php echo esc_attr( $content['title'] ); ?>">
                                <?php endif; ?>
                                <header class="entry-header">
                                    <?php if ( ! empty( $content['title'] ) ) : ?>
                                        <h2 class="entry-title"><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h2>
                                    <?php endif; ?>
                                </header><!-- .entry-header -->
                            </div>
                            <div class="hentry">
                                 <div class="featured-image" style="background-image: url('<?php echo esc_url( $seperator ); ?>');"></div>
                                 <div class="entry-container clear">
                                    <?php if ( ! empty( $content['excerpt'] ) ) : ?>
                                        <div class="entry-content">
                                            <p><?php echo wp_kses_post( $content['excerpt'] ); ?></p>
                                        </div><!-- .entry-content -->
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div><!-- #testimonial -->

    <?php }
endif;