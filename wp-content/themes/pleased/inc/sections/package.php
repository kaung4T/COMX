<?php
/**
 * Package section
 *
 * This is the template for the content of package section
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */
if ( ! function_exists( 'pleased_add_package_section' ) ) :
    /**
    * Add package section
    *
    *@since Pleased 1.0.0
    */
    function pleased_add_package_section() {
    	$options = pleased_get_theme_options();
        // Check if package is enabled on frontpage
        $package_enable = apply_filters( 'pleased_section_status', true, 'package_section_enable' );

        if ( true !== $package_enable ) {
            return false;
        }
        // Get package section details
        $section_details = array();
        $section_details = apply_filters( 'pleased_filter_package_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render package section now.
        pleased_render_package_section( $section_details );
    }
endif;

if ( ! function_exists( 'pleased_get_package_section_details' ) ) :
    /**
    * package section details.
    *
    * @since Pleased 1.0.0
    * @param array $input package section details.
    */
    function pleased_get_package_section_details( $input ) {
        $options = pleased_get_theme_options();

        // Content type.
        $package_content_type  = $options['package_content_type'];
        
        $content = array();
        switch ( $package_content_type ) {
        	
            case 'category':
                $cat_id = ! empty( $options['package_content_category'] ) ? $options['package_content_category'] : '';
                $args = array(
                    'post_type'         => 'post',
                    'posts_per_page'    => 4,
                    'cat'               => $cat_id,
                    'ignore_sticky_posts'   => true,
                    );    
            break;

            case 'trip-types':

                if ( ! class_exists( 'WP_Travel' ) )
                    return;
                
                $cat_id = ! empty( $options['package_content_trip_types'] ) ? $options['package_content_trip_types'] : '';
                $args = array(
                    'post_type'      => 'itineraries',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'itinerary_types',
                            'field'    => 'id',
                            'terms'    => $cat_id,
                        ),
                    ),
                    'posts_per_page'  => 4,
                    );                    
            break;

            case 'destination':

                if ( ! class_exists( 'WP_Travel' ) )
                    return;
                
                $cat_id = ! empty( $options['package_content_destination'] ) ? $options['package_content_destination'] : '';
                $args = array(
                    'post_type'      => 'itineraries',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'travel_locations',
                            'field'    => 'id',
                            'terms'    => $cat_id,
                        ),
                    ),
                    'posts_per_page'  => 4,
                    );                    
            break;

            case 'activity':

                if ( ! class_exists( 'WP_Travel' ) )
                    return;
                
                $cat_id = ! empty( $options['package_content_activity'] ) ? $options['package_content_activity'] : '';
                $args = array(
                    'post_type'      => 'itineraries',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'activity',
                            'field'    => 'id',
                            'terms'    => $cat_id,
                        ),
                    ),
                    'posts_per_page'  => 4,
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
                    $page_post['image']     = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'large' ) : get_template_directory_uri() . '/assets/uploads/no-featured-image-590x650.jpg';

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
// package section content details.
add_filter( 'pleased_filter_package_section_details', 'pleased_get_package_section_details' );


if ( ! function_exists( 'pleased_render_package_section' ) ) :
  /**
   * Start package section
   *
   * @return string package content
   * @since Pleased 1.0.0
   *
   */
   function pleased_render_package_section( $content_details = array() ) {
        $options = pleased_get_theme_options();
        $package_content_type  = $options['package_content_type'];

        if ( empty( $content_details ) ) {
            return;
        } ?>

        <div id="luxury-room" class="page-section">
                <div class="wrapper">
                    <div class="section-header align-center">
                        <?php if ( ! empty( $options['package_title'] ) ) : ?>
                            <h2 class="section-title"><?php echo esc_html( $options['package_title'] ) ?></h2>
                        <?php endif; ?>
                    </div><!-- .section-header -->

                    <div class="section-content">
                          <div class="grid">

                            <?php foreach ( $content_details as $content ) : ?>
                                <div class="grid-item">
                                    <article class="has-featured-image" style="background-image: url('<?php echo esc_url( $content['image'] ); ?>');">
                                        <div class="overlay"></div>
                                        <div class="entry-container">
                                            <header class="entry-header">
                                                <h2 class="entry-title"><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h2>
                                            </header>

                                            <?php if ( ! in_array( $package_content_type, array( 'page', 'post', 'category' ) ) ) : 
                                                $enable_sale     = get_post_meta( $content['id'], 'wp_travel_enable_sale', true );
                                                $trip_price      = wp_travel_get_price( $content['id'] );
                                                $sale_price      = wp_travel_get_trip_sale_price( $content['id'] );
                                                $trip_per        = wp_travel_get_price_per_text( $content['id'], wp_travel_get_min_price_key( $content['id'] ) );
                                                $settings        = wp_travel_get_settings();
                                                $currency_code   = ( isset( $settings['currency'] ) ) ? $settings['currency'] : '';
                                                $currency_symbol = wp_travel_get_currency_symbol( $currency_code );
                                            ?>
                                                <div class="trip-entry-meta">
                                                   <div class="price-meta">
                                                        <span class="trip-price">                       
                                                            <?php 
                                                            echo esc_html( $currency_symbol );
                                                            echo ( true == $enable_sale && $sale_price ) ? esc_html( $sale_price ) : esc_html( $trip_price );
                                                            ?>
                                                        </span><!-- .trip-price -->
                                                        <span><?php printf( esc_html__( '/ %s', 'pleased' ), $trip_per ); ?></span><!-- .trip-price -->
                                                    </div>
                                                </div><!-- .entry-meta -->
                                            <?php endif; ?>
                                        </div><!-- .entry-container -->  
                                    </article>   
                                </div><!-- .grid-item -->
                            <?php endforeach; ?>

                        </div><!-- .grid -->
                    </div><!-- .section-content -->
                </div><!-- .wrapper -->
            </div><!-- #luxury-room -->
        
    <?php }
endif;