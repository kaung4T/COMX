<?php
if (!class_exists('Newsup_Latest_Post')) :
    /**
     * Adds Newsup_Latest_Post widget.
     */
    class Newsup_Latest_Post extends Newsup_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array('newsup-categorised-posts-title', 'newsup-posts-number', 'newsup-excerpt-length');
            $this->select_fields = array('newsup-select-category', 'newsup-show-excerpt');

            $widget_ops = array(
                'classname' => 'mg-posts-sec mg-posts-modul-6',
                'description' => __('Displays posts from selected category in single column.', 'newsup'),
                'customize_selective_refresh' => true,
            );

            parent::__construct('newsup_latest_post', __('AR: Latest News Post', 'newsup'), $widget_ops);
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

            $title = apply_filters('widget_title', $instance['newsup-categorised-posts-title'], $instance, $this->id_base);
            $category = isset($instance['newsup-select-category']) ? $instance['newsup-select-category'] : '0';
            $show_excerpt = isset($instance['newsup-show-excerpt']) ? $instance['newsup-show-excerpt'] : 'true';
            $excerpt_length = 25;
            $number_of_posts = 5;

            // open the widget container
            echo $args['before_widget'];
            ?>
            <?php if (!empty($title) || !empty($subtitle)): ?>
             <!-- mg-posts-sec mg-posts-modul-6 -->
            <div class="mg-posts-sec mg-posts-modul-6">
                <!-- mg-sec-title -->
                <div class="mg-sec-title">
                <?php if (!empty($title)): ?>
                    <h4><?php echo esc_html($title); ?></h4>
                <?php endif; ?>
                </div>
                <!-- // mg-sec-title -->
                <?php endif; ?>
                <?php
                $all_posts = newsup_get_posts($number_of_posts, $category);
                ?>
                <!-- mg-posts-sec-inner -->
                <div class="mg-posts-sec-inner">
                    <?php
                    if ($all_posts->have_posts()) :
                        while ($all_posts->have_posts()) : $all_posts->the_post();
                            global $post; ?>
                        <article class="d-md-flex mg-posts-sec-post">
                            <?php newsup_post_image_display_type($post); ?>
                            <div class="mg-sec-top-post py-3 col">
                                    <div class="mg-blog-category"> <?php newsup_post_categories(); ?> </div>
                                    <h4 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    <?php newsup_post_meta(); ?>
                                <?php if ($show_excerpt != 'false'): ?>
                                    <div class="mg-content">
                                        <?php if (absint($excerpt_length) > 0) : ?>
                                            <?php
                                                $excerpt = newsup_get_excerpt($excerpt_length, get_the_content());
                                                echo wp_kses_post(wpautop($excerpt)); ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endwhile; ?>
                <?php endif;
                wp_reset_postdata(); ?>
                </div> <!-- // mg-posts-sec-inner -->
            </div> <!-- // mg-posts-sec block_6 -->
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
                echo parent::newsup_generate_text_input('newsup-categorised-posts-title', 'Title', 'Latest News');
                echo parent::newsup_generate_select_options('newsup-select-category', __('Select category', 'newsup'), $categories);

                echo parent::newsup_generate_select_options('newsup-show-excerpt', __('Show excerpt', 'newsup'), $options);



            }

            //print_pre($terms);


        }

    }
endif;