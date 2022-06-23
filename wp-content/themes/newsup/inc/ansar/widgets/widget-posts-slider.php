<?php
if (!class_exists('Newsup_Posts_Slider')) :
    /**
     * Adds Newsup_Posts_Slider widget.
     */
    class Newsup_Posts_Slider extends Newsup_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array('newsup-posts-slider-title', 'newsup-excerpt-length', 'newsup-posts-slider-number');
            $this->select_fields = array('newsup-select-category', 'newsup-show-excerpt');

            $widget_ops = array(
                'classname' => 'newsup_posts_slider_widget',
                'description' => __('Displays posts slider from selected category.', 'newsup'),
                'customize_selective_refresh' => true,
            );

            parent::__construct('newsup_posts_slider', __('AR: Posts Slider', 'newsup'), $widget_ops);
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
            $category = isset($instance['newsup-select-category']) ? $instance['newsup-select-category'] : 0;
            $number_of_posts = 5;

            // open the widget container
            echo $args['before_widget'];
            ?>
            <?php if (!empty($title)): ?>
            <div class="mg-sec-title">
            <!-- mg-sec-title -->
                    <h4><?php echo esc_html($title); ?></h4>
            </div>
            <!-- // mg-sec-title -->
            <?php endif; ?>
            <?php

            $all_posts = newsup_get_posts($number_of_posts, $category);
            ?>

            <div class="postcrousel owl-carousel mr-bot60">
                <?php
                    if ($all_posts->have_posts()) :
                        while ($all_posts->have_posts()) : $all_posts->the_post();
                            global $post;
                            $url = newsup_get_freatured_image_url($post->ID, 'newsup-slider-full');
                            ?>
                <div class="item">
                    
                            <div class="mg-blog-post lg back-img" style="background-image: url('<?php echo esc_url($url); ?>');">
                                <a class="link-div" href="<?php the_permalink(); ?>"></a>
                                <article class="bottom">
                                <span class="post-form"><i class="fa fa-camera"></i></span>
                                    <div class="mg-blog-category">
                                        <?php newsup_post_categories(); ?>
                                    </div>
                                    
                                    <h4 class="title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h4>
                                        <?php newsup_post_meta(); ?>
                                </article>
                            </div>
                        </div>
                        <?php
                        endwhile;
                    endif;
                    wp_reset_postdata();
                    ?>
                
            </div>

            <?php
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
            $options = array(
                'true' => __('Yes', 'newsup'),
                'false' => __('No', 'newsup')

            );
            $categories = newsup_get_terms();
            if (isset($categories) && !empty($categories)) {
                // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
                echo parent::newsup_generate_text_input('newsup-posts-slider-title', __('Title', 'newsup'), 'Posts Slider');

                echo parent::newsup_generate_select_options('newsup-select-category', __('Select category', 'newsup'), $categories);


            }
        }
    }
endif;