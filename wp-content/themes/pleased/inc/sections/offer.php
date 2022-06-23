<?php
/**
 * Offer section
 *
 * This is the template for the content of offer section
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */
if ( ! function_exists( 'pleased_add_offer_section' ) ) :
    /**
    * Add offer section
    *
    *@since Pleased 1.0.0
    */
    function pleased_add_offer_section() {
    	$options = pleased_get_theme_options();
        // Check if offer is enabled on frontpage
        $offer_enable = apply_filters( 'pleased_section_status', true, 'offer_section_enable' );

        if ( true !== $offer_enable ) {
            return false;
        }
        // Get offer section details
        $section_details = array();
        $section_details = apply_filters( 'pleased_filter_offer_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render offer section now.
        pleased_render_offer_section( $section_details );
    }
endif;

if ( ! function_exists( 'pleased_get_offer_section_details' ) ) :
    /**
    * offer section details.
    *
    * @since Pleased 1.0.0
    * @param array $input offer section details.
    */
    function pleased_get_offer_section_details( $input ) {
        $options = pleased_get_theme_options();

        // Content type.
        $offer_content_type  = $options['offer_content_type'];
        
        $content = array();
        switch ( $offer_content_type ) {
        	
            case 'page':
                $page_id = ! empty( $options['offer_content_page'] ) ? $options['offer_content_page'] : '';

                $args = array(
                    'post_type'         => 'page',
                    'page_id'           => absint( $page_id ),
                    'posts_per_page'    => 1,
                    );                    
            break;

            case 'trip':

                if ( ! class_exists( 'WP_Travel' ) )
                    return;
                
                $page_id = ! empty( $options['offer_content_trip'] ) ? $options['offer_content_trip'] : '';
                
                $args = array(
                    'post_type'         => 'itineraries',
                    'p'                 => absint( $page_id ),
                    'posts_per_page'    => 1,
                    );                    
            break;

            default:
            break;
        }

            // Run The Loop.
            $query = new WP_Query( $args );
            if ( $query->have_posts() ) : 
                while ( $query->have_posts() ) : $query->the_post();
                    $page_post['id']        = get_the_id();
                    $page_post['title']     = get_the_title();
                    $page_post['url']       = get_the_permalink();
                    $page_post['excerpt']   = pleased_trim_content( 35 );
                    $page_post['image']     = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'post-thumbnail' ) : get_template_directory_uri() . '/assets/uploads/no-featured-image-590x650.jpg';

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
// offer section content details.
add_filter( 'pleased_filter_offer_section_details', 'pleased_get_offer_section_details' );


if ( ! function_exists( 'pleased_render_offer_section' ) ) :
  /**
   * Start offer section
   *
   * @return string offer content
   * @since Pleased 1.0.0
   *
   */
   function pleased_render_offer_section( $content_details = array() ) {
        $options = pleased_get_theme_options();
        $offer_content_type  = $options['offer_content_type'];
        $readmore = ! empty( $options['offer_btn_label'] ) ? $options['offer_btn_label'] : esc_html__( 'Book Now', 'pleased' ); 
        $background = ! empty( $options['offer_background_image'] ) ? $options['offer_background_image'] : get_template_directory_uri() . '/assets/uploads/special.jpg'; 

        if ( empty( $content_details ) ) {
            return;
        } ?>

        <div id="special-menu" class="col-2 page-section" style="background-image: url('<?php echo esc_url( $background ); ?>');">
            <div class="wrapper">
                <div class="regular" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "infinite": true, "speed": 1000, "dots": true, "arrows":false, "autoplay": true, "fade": true }'>
                    <?php foreach  ( $content_details as $content ) : ?>
                        <article class="has-post-thumbnail">
                            <div class="special-wrapper">
                                <div class="entry-container clear">
                                    <div class="content-wrapper">
                                    <div class="section-header">
                                        <h2 class="section-title"><?php echo esc_html( $content['title'] ); ?></h2>
                                    </div><!-- .section-header -->
                                    <div class="section-content">
                                        <p><?php echo esc_html( $content['excerpt'] ); ?></p>
                                        <?php if ( ! in_array( $offer_content_type, array( 'page', 'post', 'category' ) ) ) : 
                                            $enable_sale     = get_post_meta( $content['id'], 'wp_travel_enable_sale', true );
                                            $trip_price      = wp_travel_get_price( $content['id'] );
                                            $sale_price      = wp_travel_get_trip_sale_price( $content['id'] );
                                            $settings        = wp_travel_get_settings();
                                            $currency_code   = ( isset( $settings['currency'] ) ) ? $settings['currency'] : '';
                                            $currency_symbol = wp_travel_get_currency_symbol( $currency_code );
                                            ?>
                                            <span>
                                                <?php 
                                                if ( true == $enable_sale && $sale_price ) :
                                                    echo '<del>' . esc_html( $currency_symbol . $trip_price ) . '</del>';
                                                    echo esc_html( $currency_symbol . $sale_price );
                                                else :
                                                    echo esc_html( $currency_symbol . $trip_price );
                                                endif;
                                                ?>
                                            </span>
                                        <?php endif; ?>
                                    </div><!-- .section-content -->
                                    <a href="<?php echo esc_url( $content['url'] ); ?>" class="btn btn-default"><?php echo esc_html( $readmore ); ?></a>
                                    </div>
                                </div><!-- .entry-container -->

                                <div class="featured-image" style="background-image: url('<?php echo esc_url( $content['image'] ); ?>');">  
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
    <?php }
endif;