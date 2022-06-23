<?php
/**
 * Latest Posts Widget
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

if ( ! class_exists( 'Pleased_Latest_Post' ) ) :

     
    class Pleased_Latest_Post extends WP_Widget {
        /**
         * Sets up the widgets name etc
         */
        public function __construct() {
            $tp_widget_popular_post = array(
                'classname'   => 'widget_latest_post',
                'description' => esc_html__( 'Retrive latest posts.', 'pleased' ),
            );
            parent::__construct( 'pleased_latest_post', esc_html__( 'TP : Latest Posts', 'pleased' ), $tp_widget_popular_post );
        }

        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */
        public function widget( $args, $instance ) {
            // outputs the content of the widget
            if ( ! isset( $args['widget_id'] ) ) {
                $args['widget_id'] = $this->id;
            }

            $tp_title  = ( ! empty( $instance['title'] ) ) ? ( $instance['title'] ) : '';
            $tp_title  = apply_filters( 'widget_title', $tp_title, $instance, $this->id_base );
            $tp_number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 3;

            echo $args['before_widget'];
                if ( ! empty( $tp_title ) ) {
                    echo $args['before_title'] . esc_html( $tp_title ) . $args['after_title'];
                }
            $popular_args = array(
                'post_type'         => 'post',
                'posts_per_page'    => $tp_number,
                'order'             => 'DESC'
                );

            echo '<ul>';
            $wp_query = get_posts( $popular_args );
            foreach ( $wp_query as $post ) :
            ?>

                <li class="has-post-thumbnail clear">
                    
                    <a href="<?php the_permalink( $post->ID ); ?>">
                        <?php 
                        if ( has_post_thumbnail( $post->ID ) ) :
                            $image = get_the_post_thumbnail( $post->ID, $size = 'thumbnail', array( 'alt' => esc_attr( get_the_title( $post->ID ) ) ) );
                            echo $image;
                        else :
                            echo '<img src="' . esc_url( get_template_directory_uri() .'/assets/uploads/no-featured-image-150x150.jpg' ) . '" alt="'. the_title_attribute( 'echo=0' ) .'">';
                        endif; 
                        ?>
                    </a>
                    <div class="entry-meta">
                        <span class="post-categories">
                            <span class="cat-links">
                                <?php the_category( ' ', '', $post->ID ); ?>   
                            </span>
                        </span>   
                    </div><!-- .entry-meta -->
                    <h3><a href="<?php the_permalink( $post->ID ); ?>"><?php echo esc_html( $post->post_title ); ?></a></h3>

                </li>

            <?php
            endforeach;
            echo '</ul>';
            echo $args['after_widget'];
        }

        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        public function form( $instance ) {
            $tp_title      = isset( $instance['title'] ) ? ( $instance['title'] ) : esc_html__( 'Latest Posts', 'pleased' );
            $tp_number     = isset( $instance['number'] ) ? absint( $instance['number'] ) : 3;
           ?>

           <p>
               <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'pleased' ); ?></label>
               <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $tp_title ); ?>" />
           </p>

           <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'pleased' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" max="7" value="<?php echo absint( $tp_number ); ?>" size="3" />
           </p>

           <?php
        }

        /**
        * Processing widget options on save
        *
        * @param array $new_instance The new options
        * @param array $old_instance The previous options
        */
        public function update( $new_instance, $old_instance ) {
            // processes widget options to be saved
            $instance           = $old_instance;
            $instance['title']  = sanitize_text_field( $new_instance['title'] );
            $instance['number'] = (int) $new_instance['number'];
           
            return $instance;
        }
    }
endif;
