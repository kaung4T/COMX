<?php
/**
 * Blog section
 *
 * This is the template for the content of blog section
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */
if ( ! function_exists( 'pleased_add_blog_section' ) ) :
    /**
    * Add blog section
    *
    *@since Pleased 1.0.0
    */
    function pleased_add_blog_section() {
        $options = pleased_get_theme_options();
        // Check if blog is enabled on frontpage
        $blog_enable = apply_filters( 'pleased_section_status', true, 'blog_section_enable' );

        if ( true !== $blog_enable ) {
            return false;
        }
        // Get blog section details
        $section_details = array();
        $section_details = apply_filters( 'pleased_filter_blog_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render blog section now.
        pleased_render_blog_section( $section_details );
    }
endif;

if ( ! function_exists( 'pleased_get_blog_section_details' ) ) :
    /**
    * blog section details.
    *
    * @since Pleased 1.0.0
    * @param array $input blog section details.
    */
    function pleased_get_blog_section_details( $input ) {
        $options = pleased_get_theme_options();

        $content = array();
        $cat_ids = ! empty( $options['blog_category_exclude'] ) ? $options['blog_category_exclude'] : array();
        $args = array(
            'post_type'         => 'post',
            'posts_per_page'    => 3,
            'category__not_in'  => ( array ) $cat_ids,
            'ignore_sticky_posts'   => true,
            );                    


        // Run The Loop.
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
                $page_post['id']        = get_the_id();
                $page_post['title']     = get_the_title();
                $page_post['url']       = get_the_permalink();
                $page_post['excerpt']   = pleased_trim_content( 20 );
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
// blog section content details.
add_filter( 'pleased_filter_blog_section_details', 'pleased_get_blog_section_details' );


if ( ! function_exists( 'pleased_render_blog_section' ) ) :
  /**
   * Start blog section
   *
   * @return string blog content
   * @since Pleased 1.0.0
   *
   */
   function pleased_render_blog_section( $content_details = array() ) {
        $options = pleased_get_theme_options();
        $readmore  = ! empty( $options['blog_btn_title'] ) ? $options['blog_btn_title'] : esc_html__( 'Discover More', 'pleased' );
        $count = count( $content_details );
        $i = 1;

        if ( empty( $content_details ) ) {
            return;
        } ?>

        <div id="latest-blog" class="page-section">
            <div class="wrapper">
                <?php if ( ! empty( $options['blog_title'] ) ) : ?>
                    <div class="section-header align-center">
                        <h2 class="section-title"><?php echo esc_html( $options['blog_title'] ); ?></h2>
                    </div><!-- .section-header -->
                <?php endif; ?>

                <div class="section-content">
                    <div class="archive blog-wrapper clear col-2">
                        <?php foreach ( $content_details as $content) : 
                            if ( $i == 1 ) : ?>
                                <div class="hentry">
                                    <article class="post <?php echo ! empty( $content['image'] ) ? 'has' : 'no'; ?>-post-thumbnail">
                                        <?php if ( ! empty( $content['image'] ) ) : ?>
                                            <div class="featured-image">
                                                <img src="<?php echo esc_url( $content['image'] ); ?>" alt="<?php echo esc_attr( $content['title'] ); ?>">
                                            </div><!-- .featured-image -->
                                        <?php endif; ?>

                                        <div class="blog-content">
                                            <div class="entry-meta">
                                                <?php pleased_posted_on( $content['id'] ); ?>
                                            </div><!-- .entry-meta -->

                                            <header class="entry-header">
                                                <h2 class="entry-title"><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h2>
                                            </header><!-- .entry-header -->

                                            <a href="<?php echo esc_url( $content['url'] ); ?>" class="btn btn-default"><?php echo esc_html( $readmore ); ?></a>
                                        </div><!-- .news-content -->
                                    </article><!-- #post-1 -->
                                </div><!-- .hentry-->
                            <?php endif;

                            if ( $count > 1 && $i > 1 ) : 
                                if ( $i === 2 ) : ?>
                                    <div class="hentry">
                                        <div class="events clear">
                                            <ul>
                                <?php endif; ?>

                                    <li>
                                        <?php pleased_posted_on( $content['id'] ); ?>
                                        <h5><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h5>
                                        <p><?php echo esc_html( $content['excerpt'] ); ?></p>
                                        <a href="<?php echo esc_url( $content['url'] ); ?>" class="btn btn-primary"><?php echo esc_html( $readmore ); ?></a>
                                    </li>

                                <?php if ( $count === $i ) : ?>
                                            </ul>
                                        </div><!-- .events -->
                                    </div><!-- .hentry -->  
                                <?php endif; 
                            endif; 
                            $i++; 
                        endforeach; ?>
                    </div><!-- .archive -->
                </div>
            </div><!-- .wrapper -->
        </div><!-- .latest-blog -->

    <?php }
endif;