<?php
/**
 * Service section
 *
 * This is the template for the content of service section
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */
if ( ! function_exists( 'pleased_add_service_section' ) ) :
    /**
    * Add service section
    *
    *@since Pleased 1.0.0
    */
    function pleased_add_service_section() {
    	$options = pleased_get_theme_options();
        // Check if service is enabled on frontpage
        $service_enable = apply_filters( 'pleased_section_status', true, 'service_section_enable' );

        if ( true !== $service_enable ) {
            return false;
        }
        // Get service section details
        $section_details = array();
        $section_details = apply_filters( 'pleased_filter_service_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render service section now.
        pleased_render_service_section( $section_details );
    }
endif;

if ( ! function_exists( 'pleased_get_service_section_details' ) ) :
    /**
    * service section details.
    *
    * @since Pleased 1.0.0
    * @param array $input service section details.
    */
    function pleased_get_service_section_details( $input ) {
        $options = pleased_get_theme_options();

        $content = array();
        $page_ids = array();

        for ( $i = 1; $i <= 3; $i++ ) {
            if ( ! empty( $options['service_content_page_' . $i] ) )
                $page_ids[] = $options['service_content_page_' . $i];
        }
        
        $args = array(
            'post_type'         => 'page',
            'post__in'          => ( array ) $page_ids,
            'posts_per_page'    => 3,
            'orderby'           => 'post__in',
            );                    


        // Run The Loop.
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
                $page_post['title']     = get_the_title();
                $page_post['url']       = get_the_permalink();
                $page_post['excerpt']   = pleased_trim_content( 15 );

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
// service section content details.
add_filter( 'pleased_filter_service_section_details', 'pleased_get_service_section_details' );


if ( ! function_exists( 'pleased_render_service_section' ) ) :
  /**
   * Start service section
   *
   * @return string service content
   * @since Pleased 1.0.0
   *
   */
   function pleased_render_service_section( $content_details = array() ) {
        $options = pleased_get_theme_options();
        $i = 1;        

        if ( empty( $content_details ) ) {
            return;
        } ?>

        <div id="services" class="page-section">
            <div class="wrapper">
                <?php if ( ! empty( $options['service_title'] ) ) : ?>
                    <div class="section-header align-center">
                        <h2 class="section-title"><?php echo esc_html( $options['service_title'] ); ?></h2> 
                    </div><!-- .section-header -->
                <?php endif; ?>

                <div class="section-content col-3">
                    <?php foreach ( $content_details as $content ) : ?>
                        <article class="hentry">
                            <?php if ( ! empty( $options['service_content_icon_' . $i] ) ) : ?>
                                <div class="icon-container">
                                    <a href="<?php echo esc_url( $content['url'] ); ?>">
                                        <i class="fa fa-2x <?php echo esc_attr( $options['service_content_icon_' . $i] ); ?>"></i>
                                    </a>
                                </div><!-- .icon-container -->
                            <?php endif; ?>

                            <header class="entry-header">
                                <h2 class="entry-title"><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h2>
                                <div class="seperator"></div>
                            </header>

                            <div class="entry-content">
                                <?php echo esc_html( $content['excerpt'] ); ?>
                            </div><!-- .entry-content -->
                        </article><!-- .hentry -->
                    <?php $i++; endforeach; ?>
                </div><!-- .services-content -->        
            </div>
        </div>
        
    <?php }
endif;