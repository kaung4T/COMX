<?php
if (!class_exists('Newsup_Posts_Carousel')) :
    /**
     * Adds Newsup_Posts_Carousel widget.
     */
    class Newsup_Posts_Carousel extends Newsup_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 0.1
         */
        function __construct()
        {
            $this->text_fields = array('newsup-posts-slider-title', 'newsup-posts-slider-subtitle', 'newsup-posts-slider-number');
            $this->select_fields = array('newsup-select-category');

            $widget_ops = array(
                'classname' => 'mg-posts-sec mg-posts-modul-3',
                'description' => __('Displays posts carousel from selected category.', 'newsup'),
                'customize_selective_refresh' => true,
            );

            parent::__construct('newsup_posts_carousel', __('AR: Posts Carousel', 'newsup'), $widget_ops);
        }

        /**
         * Front-end display of widget.
         *
         * @see WP_Widget::widget()
         *
         * @param array $args Widget arguments.
         * @param array $instance Saved values from database.
         */

        public function widget($args, $instance)
        {
            $instance = parent::newsup_sanitize_data($instance, $instance);
            /** This filter is documented in wp-includes/default-widgets.php */

            $title = apply_filters('widget_title', $instance['newsup-posts-slider-title'], $instance, $this->id_base);

            $number_of_posts = 5;
            $category = isset($instance['newsup-select-category']) ? $instance['newsup-select-category'] : '0';

            // open the widget container
            echo $args['before_widget'];
            ?>
            <!-- mg-posts-sec mg-posts-modul-3 -->
            <div class="mg-posts-sec mg-posts-modul-3">
                <?php if (!empty($title)): ?>
                <!-- mg-sec-title -->
                <div class="mg-sec-title">
                    <?php if (!empty($title)): ?>
                        <h4><?php echo esc_html($title);  ?></h4>
                    <?php endif; ?>
                </div> <!-- // mg-sec-title -->
                <?php endif; ?>                    
                <?php
                $all_posts = newsup_get_posts($number_of_posts, $category);
                ?>
                <!-- mg-posts-sec-inner -->
                <div class="mg-posts-sec-inner">
                    <!-- featured_cat_slider -->
                    <div class="featured_cat_slider">
                        <div class="featuredcat">
                            <?php
                    if ($all_posts->have_posts()) :
                        while ($all_posts->have_posts()) : $all_posts->the_post();
                            global $post;
                            $url = newsup_get_freatured_image_url($post->ID, 'newsup-medium'); ?>
                            <!-- item -->
                            <div class="item">
                                <!-- blog -->
                                <div class="mg-blog-post-3">
                                    <div class="mg-blog-img">
                                        <a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url($url); ?>" alt="<?php the_title(); ?>"></a>
                                    </div>
                                    <div class="mg-blog-inner">
                                        <div class="mg-blog-category"> 
                                            <?php newsup_post_categories(); ?>
                                        </div>
                                        <h4 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                        <?php newsup_post_meta(); ?>
                                    </div>
                                </div>
                                <!-- blog -->
                            </div>
                            <!-- // item -->
                        <?php
                        endwhile;
                        endif;
                        wp_reset_postdata(); ?>   
                        </div>
                    </div> <!-- // featured_cat_slider -->
                </div> <!-- // mg-posts-sec-inner -->
            </div>
            <!-- // mg-posts-sec mg-posts-modul-3 --> 

                
                

            <?php
            //print_pre($all_posts);

            // close the widget container
            echo $args['after_widget'];
        }

        /**
         * Back-end widget form.
         *
         * @see WP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         */
        public function form($instance)
        {
            $this->form_instance = $instance;
            $categories = newsup_get_terms();
            if (isset($categories) && !empty($categories)) {
                // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
                echo parent::newsup_generate_text_input('newsup-posts-slider-title', 'Title', 'Posts Carousel');
                echo parent::newsup_generate_select_options('newsup-select-category', __('Select category', 'newsup'), $categories);



            }
        }
    }
endif;