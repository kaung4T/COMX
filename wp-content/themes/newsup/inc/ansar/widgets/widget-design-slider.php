<?php
if (!class_exists('Newsup_Design_Slider')) :
    /**
     * Adds Newsup_Design_Slider widget.
     */
    class Newsup_Design_Slider extends Newsup_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array('newsup-posts-design-slider-title', 'newsup-excerpt-length', 'newsup-posts-slider-number');
            $this->select_fields = array('newsup-select-category', 'newsup-show-excerpt');

            $widget_ops = array(
                'classname' => 'newsup_posts_design_slider_widget',
                'description' => __('Displays posts slider from selected category.', 'newsup'),
                'customize_selective_refresh' => true,
            );

            parent::__construct('newsup_design_slider', __('AR: 3 Column Posts Slider', 'newsup'), $widget_ops);
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
            $title = apply_filters('widget_title', $instance['newsup-posts-design-slider-title'], $instance, $this->id_base);
            $category = isset($instance['newsup-select-category']) ? $instance['newsup-select-category'] : 0;
            $number_of_posts = 5;

            // open the widget container
            echo $args['before_widget'];
            ?>
            <div class="mg-posts-sec mg-posts-modul-3">
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

            <div class="colmnthree owl-carousel">
                                            <!-- item -->
                <?php
                    if ($all_posts->have_posts()) :
                        while ($all_posts->have_posts()) : $all_posts->the_post();
                            global $post;
                            $url = newsup_get_freatured_image_url($post->ID, 'newsup-slider-full');
                            ?>
                        <div class="item">
                            <div class="mg-blog-post-3 back-img minhsec" style="background-image: url('<?php echo esc_url($url); ?>');">
                                <a class="link-div" href="<?php the_permalink(); ?>"></a>
                                <div class="mg-blog-inner">
                                    <div class="mg-blog-category">
                                        <?php newsup_post_categories(); ?>
                                    </div>
                                    
                                    <h4 class="title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h4>
                                    <?php newsup_post_meta(); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        endwhile;
                    endif;
                    wp_reset_postdata();
                    ?>
                     </div>
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
                echo parent::newsup_generate_text_input('newsup-posts-design-slider-title', __('Title', 'newsup'), 'Posts 3 Column Slider');

                echo parent::newsup_generate_select_options('newsup-select-category', __('Select category', 'newsup'), $categories);


            }
        }
    }
endif;