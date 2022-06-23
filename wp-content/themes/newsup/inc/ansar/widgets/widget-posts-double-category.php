<?php
if (!class_exists('Newsup_Dbl_Col_Cat_Posts')) :
    /**
     * Adds Newsup_Dbl_Col_Cat_Posts widget.
     */
    class Newsup_Dbl_Col_Cat_Posts extends Newsup_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array('newsup-categorised-posts-title-1', 'newsup-categorised-posts-title-2', 'newsup-posts-number-1', 'newsup-posts-number-2');
            $this->select_fields = array('newsup-select-category-1', 'newsup-select-category-2', 'newsup-select-layout-1', 'newsup-select-layout-2');

            $widget_ops = array(
                'classname' => 'newsup_dbl_col_cat_posts',
                'description' => __('Displays posts from 2 selected categories in double column.', 'newsup'),
                'customize_selective_refresh' => true,
            );

            parent::__construct('newsup_dbl_col_cat_posts', __('AR: Double Categories Posts', 'newsup'), $widget_ops);
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

            $title_1 = apply_filters('widget_title', $instance['newsup-categorised-posts-title-1'], $instance, $this->id_base);
            $title_2 = apply_filters('widget_title', $instance['newsup-categorised-posts-title-2'], $instance, $this->id_base);
            $category_1 = isset($instance['newsup-select-category-1']) ? $instance['newsup-select-category-1'] : '0';
            $category_2 = isset($instance['newsup-select-category-2']) ? $instance['newsup-select-category-2'] : '0';
            $layout_1 = isset($instance['newsup-select-layout-1']) ? $instance['newsup-select-layout-1'] : 'full-plus-list';
            $layout_2 = isset($instance['newsup-select-layout-2']) ? $instance['newsup-select-layout-2'] : 'list';
            $number_of_posts_1 =  4;
            $number_of_posts_2 =  4;


            // open the widget container
            echo $args['before_widget'];
            ?>


            <div class="mg-posts-sec mg-posts-modul-4">
                <div class="mg-posts-sec-inner row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mr-xs <?php echo esc_attr($layout_1); ?>">
                        <?php if (!empty($title_1)): ?>
                            <div class="mg-sec-title">
                            <h4><?php echo esc_html($title_1); ?> </h4>
                            </div>
                        <?php endif; ?>
                            <?php $all_posts = newsup_get_posts($number_of_posts_1, $category_1); ?>
                            <?php
                            $count_1 = 1;


                            if ($all_posts->have_posts()) :
                                while ($all_posts->have_posts()) : $all_posts->the_post();



                                        if ($count_1 == 1) {
                                            $thumbnail_size = 'newsup-medium';

                                        } else {
                                            $thumbnail_size = 'thumbnail';
                                        }


                                    global $post;
                                    $url = newsup_get_freatured_image_url($post->ID, $thumbnail_size);

                                    if ($url == '') {
                                        $img_class = 'no-image';
                                    }
                                    global $post;
                                    ?>
                                    
                                        <div class="small-list-post mg-post-<?php echo esc_attr($count_1); ?>">
                                                <ul>
                                                <li class="small-post clearfix mg-post-<?php echo esc_attr($count_1); ?>">
                                                    <!-- small_post -->
                                                    <div class="img-small-post">
                                                        <!-- img-small-post -->
                                                        <img src="<?php echo esc_url($url); ?>">
                                                    </div>
                                                    <!-- // img-small-post -->
                                                    <div class="small-post-content">
                                                        <div class="mg-blog-category">
                                                            <?php newsup_post_categories(); ?>
                                                        </div>
                                                        <!-- small-post-content -->
                                                        <h5 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                                        <?php if($count_1 == 1) { ?>
                                                        <?php newsup_post_meta(); ?>
                                                        <!-- // title_small_post -->
                                                        <p> <?php the_content();?></p><?php } ?>
                                                        <!-- // title_small_post -->
                                                    </div>
                                                    <!-- // small-post-content -->
                                                </li>
                                                <!-- // small_post -->
                                                </ul>
                                        </div>

                                            
                                    <?php
                                    $count_1++;
                                endwhile;
                                ?>
                                
                            <?php endif;
                            wp_reset_postdata(); ?>
                    </div>

                    <div class="col-lg-6 col-md-6 <?php echo esc_attr($layout_2); ?> col-sm-12 col-xs-12">
                        <?php if (!empty($title_2)): ?>
                        <!-- mg-sec-title -->
                        <div class="mg-sec-title">
                            <h4><?php echo esc_html($title_2); ?></h4>
                        </div>
                        <!-- // mg-sec-title -->
                        <?php endif; ?>
                            <?php $all_posts = newsup_get_posts($number_of_posts_2, $category_2); ?>
                            <?php
                            $count_2 = 1;


                            if ($all_posts->have_posts()) :
                                while ($all_posts->have_posts()) : $all_posts->the_post();



                                        if ($count_2 == 1) {
                                            $thumbnail_size = 'newsup-medium';

                                        } else {
                                            $thumbnail_size = 'thumbnail';
                                        }



                                    global $post;
                                    $url = newsup_get_freatured_image_url($post->ID, $thumbnail_size);

                                    if ($url == '') {
                                        $img_class = 'no-image';
                                    }

                                    global $post;

                                    ?>

                                    <div class="small-list-post mg-post-<?php echo esc_attr($count_2); ?>">
                                    <ul>
                                    <li class="small-post clearfix mg-post-<?php echo esc_attr($count_2); ?>">
                                                    <!-- small_post -->
                                                    <div class="img-small-post">
                                                        <!-- img-small-post -->
                                                        <img src="<?php echo esc_url($url); ?>">
                                                    </div>
                                                    <!-- // img-small-post -->
                                                    <div class="small-post-content">
                                                        <div class="mg-blog-category">
                                                            <?php newsup_post_categories(); ?> 
                                                        </div>
                                                        <!-- small-post-content -->
                                                        <h5 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                                        <?php if($count_2 == 1) { ?>
                                                      <?php newsup_post_meta(); ?>
                                                        <!-- // title_small_post -->
                                                        <p> <?php the_content();?></p><?php } ?> 
                                                    </div>
                                                    <!-- // small-post-content -->
                                                </li>
                                                <!-- // small_post -->

                                    </ul>
                        </div>                                    <?php
                                    $count_2++;
                                endwhile;
                                ?>
                            <?php endif;
                            wp_reset_postdata(); ?>
                        
                    </div>
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

            //print_pre($terms);
            $categories = newsup_get_terms();

            if (isset($categories) && !empty($categories)) {
                // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
                echo parent::newsup_generate_text_input('newsup-categorised-posts-title-1', __('Title 1', 'newsup'), 'Double Categories Posts 1');
                echo parent::newsup_generate_select_options('newsup-select-category-1', __('Select category 1', 'newsup'), $categories);
                echo parent::newsup_generate_text_input('newsup-categorised-posts-title-2', __('Title 2', 'newsup'), 'Double Categories Posts 2');
                echo parent::newsup_generate_select_options('newsup-select-category-2', __('Select category 2', 'newsup'), $categories);
            }

            //print_pre($terms);


        }

    }
endif;