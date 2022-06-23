<?php
/**
 * Gallery section
 *
 * This is the template for the content of gallery section
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */
if ( ! function_exists( 'pleased_add_gallery_section' ) ) :
    /**
    * Add gallery section
    *
    *@since Pleased 1.0.0
    */
    function pleased_add_gallery_section() {
    	$options = pleased_get_theme_options();
        // Check if gallery is enabled on frontpage
        $gallery_enable = apply_filters( 'pleased_section_status', true, 'gallery_section_enable' );

        if ( true !== $gallery_enable ) {
            return false;
        }
        // Get gallery section details
        $section_details = array();
        $section_details = apply_filters( 'pleased_filter_gallery_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render gallery section now.
        pleased_render_gallery_section( $section_details );
    }
endif;

if ( ! function_exists( 'pleased_get_gallery_section_details' ) ) :
    /**
    * gallery section details.
    *
    * @since Pleased 1.0.0
    * @param array $input gallery section details.
    */
    function pleased_get_gallery_section_details( $input ) {
        $options = pleased_get_theme_options();

        // Content type.
        $gallery_content_type  = $options['gallery_content_type'];
        
        $content = array();
        switch ( $gallery_content_type ) {
        	
            case 'category':
                $cat_ids = ! empty( $options['gallery_content_category'] ) ? ( array ) $options['gallery_content_category'] : array();
                $args = array(
                    'post_type'         => 'post',
                    'posts_per_page'    => -1,
                    'category__in'      => $cat_ids,
                    'ignore_sticky_posts'   => true,
                    );                    
            break;

            case 'trip-types':

                if ( ! class_exists( 'WP_Travel' ) )
                    return;
                
                $cat_ids = ! empty( $options['gallery_content_trip_types'] ) ? ( array ) $options['gallery_content_trip_types'] : array();
                $args = array(
                    'post_type'      => 'itineraries',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'itinerary_types',
                            'field'    => 'id',
                            'terms'    => $cat_ids,
                        ),
                    ),
                    'posts_per_page'  => -1,
                    );                    
            break;

            case 'destination':
                $cat_ids = ! empty( $options['gallery_content_destination'] ) ? ( array ) $options['gallery_content_destination'] : array();
                $args = array(
                    'post_type'      => 'itineraries',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'travel_locations',
                            'field'    => 'id',
                            'terms'    => $cat_ids,
                        ),
                    ),
                    'posts_per_page'  => -1,
                    );                    
            break;

            case 'activity':
                $cat_ids = ! empty( $options['gallery_content_activity'] ) ? ( array ) $options['gallery_content_activity'] : array();
                $args = array(
                    'post_type'      => 'itineraries',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'activity',
                            'field'    => 'id',
                            'terms'    => $cat_ids,
                        ),
                    ),
                    'posts_per_page'  => -1,
                    );                    
            break;

            default:
            break;
        }


            // Run The Loop.
            $query = new WP_Query( $args );
            if ( $query->have_posts() ) : 
                while ( $query->have_posts() ) : $query->the_post();
                    $page_post['id']     = get_the_id();
                    $page_post['title']     = get_the_title();
                    $page_post['url']       = get_the_permalink();
                    $page_post['image']     = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'large' ) : '';

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
// gallery section content details.
add_filter( 'pleased_filter_gallery_section_details', 'pleased_get_gallery_section_details' );


if ( ! function_exists( 'pleased_render_gallery_section' ) ) :
  /**
   * Start gallery section
   *
   * @return string gallery content
   * @since Pleased 1.0.0
   *
   */
   function pleased_render_gallery_section( $content_details = array() ) {
        $options = pleased_get_theme_options();
        $gallery_content_type  = $options['gallery_content_type'];
        $background = ! empty( $options['gallery_background_image'] ) ? $options['gallery_background_image'] : get_template_directory_uri() . '/assets/uploads/gallery.jpg';

        if ( empty( $content_details ) ) {
            return;
        } ?>

        <div id="gallery" class="relative">
            <div class="wrapper">
                <div class="gallery-filtering">
                    <ul>
                        <li class="active"><a href="#" data-slug="all"><?php esc_html_e('All', 'pleased'); ?></a></li>
                        <?php if ( 'category' == $gallery_content_type ) :

                            $cat_ids = ! empty( $options['gallery_content_category'] ) ? ( array ) $options['gallery_content_category'] : array();
                            foreach ( $cat_ids as $cat_id ) :
                                $cat = get_category( $cat_id ); ?>
                                <li><a href="#" data-slug="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></a></li>
                            <?php endforeach; 

                        elseif ( 'trip-types' == $gallery_content_type ) :

                            $cat_ids = ! empty( $options['gallery_content_trip_types'] ) ? ( array ) $options['gallery_content_trip_types'] : array();
                            foreach ( $cat_ids as $cat_id ) :
                                $cat = get_term_by( 'id', $cat_id, 'itinerary_types' ); ?>
                                <li><a href="#" data-slug="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></a></li>
                            <?php endforeach; 

                        elseif ( 'destination' == $gallery_content_type ) :

                            $cat_ids = ! empty( $options['gallery_content_destination'] ) ? ( array ) $options['gallery_content_destination'] : array();
                            foreach ( $cat_ids as $cat_id ) :
                                $cat = get_term_by( 'id', $cat_id, 'travel_locations' ); ?>
                                <li><a href="#" data-slug="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></a></li>
                            <?php endforeach; 

                        elseif ( 'activity' == $gallery_content_type ) :

                            $cat_ids = ! empty( $options['gallery_content_activity'] ) ? ( array ) $options['gallery_content_activity'] : array();
                            foreach ( $cat_ids as $cat_id ) :
                                $cat = get_term_by( 'id', $cat_id, 'activity' ); ?>
                                <li><a href="#" data-slug="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></a></li>
                            <?php endforeach; 

                        endif; ?>
                    </ul>
                </div><!-- .product-filtering -->
            </div><!-- .wrapper -->

            <div class="gallery-collection" style="background-image: url('<?php echo esc_url( $background ); ?>');">
                <div class="wrapper">
                        <ul class="products latest" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "infinite": true, "speed": 1000, "dots": true, "arrows":false, "autoplay": true, "fade": false }'>

                            <?php foreach ( $content_details as $content ) : 
                                if ( 'category' == $gallery_content_type ) :
                                    $categories = get_the_category( $content['id'] );
                                elseif ( 'trip-types' == $gallery_content_type ) :
                                    $categories = get_the_terms( $content['id'], 'itinerary_types' );
                                elseif ( 'destination' == $gallery_content_type ) :
                                    $categories = get_the_terms( $content['id'], 'travel_locations' );
                                elseif ( 'destination' == $gallery_content_type ) :
                                    $categories = get_the_terms( $content['id'], 'activity' );
                                endif;
                                if ( ! empty( $content['image'] ) ) : ?>
                                    <li class="product all<?php foreach ( $categories as $cat_slug ) {  echo ' ' . esc_attr( $cat_slug->slug ); } ?>">
                                        <div class="post-thumbnail">
                                            <a href="<?php echo esc_url( $content['url'] ); ?>"><img src="<?php echo esc_url( $content['image'] ); ?>" title="<?php echo esc_attr( $content['title'] ); ?>" alt="<?php echo esc_attr( $content['title'] ); ?>">
                                            </a>
                                        </div><!-- .post-thumbnail -->
                                    </li>
                                <?php endif; 
                            endforeach; ?>

                        </ul><!-- .products -->
                        
                    </div>
                </div><!-- .wrapper -->

            </div><!-- .wrapper -->
        </div><!-- #gallery -->
        
    <?php }
endif;