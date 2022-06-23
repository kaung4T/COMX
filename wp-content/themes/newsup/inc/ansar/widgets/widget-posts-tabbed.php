<?php
if (!class_exists('Newsup_Tab_Posts')) :
    /**
     * Adds newsup_Tabbed_Posts widget.
     */
    class Newsup_Tab_Posts extends Newsup_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $this->text_fields = array('newsup-tabbed-popular-posts-title', 'newsup-tabbed-latest-posts-title', 'newsup-tabbed-categorised-posts-title', 'newsup-excerpt-length', 'newsup-posts-number');

            $this->select_fields = array('newsup-show-excerpt', 'newsup-enable-categorised-tab', 'newsup-select-category');

            $widget_ops = array(
                'classname' => 'newsup_tabbed_posts_widget',
                'description' => __('Displays tabbed posts lists from selected settings.', 'newsup'),
                'customize_selective_refresh' => true,
            );

            parent::__construct('newsup_tab_posts', __('AR: Tabbed Posts', 'newsup'), $widget_ops);
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
            $tab_id = 'tabbed-' . $this->number;


            /** This filter is documented in wp-includes/default-widgets.php */

            $show_excerpt = 'false';
            $excerpt_length = '20';
            $number_of_posts =  '5';


            $popular_title = isset($instance['newsup-tabbed-popular-posts-title']) ? $instance['newsup-tabbed-popular-posts-title'] : __('NEWSUP Popular', 'newsup');
            $latest_title = isset($instance['newsup-tabbed-latest-posts-title']) ? $instance['newsup-tabbed-latest-posts-title'] : __('NEWSUP Latest', 'newsup');


            $enable_categorised_tab = isset($instance['newsup-enable-categorised-tab']) ? $instance['newsup-enable-categorised-tab'] : 'true';
            $categorised_title = isset($instance['newsup-tabbed-categorised-posts-title']) ? $instance['newsup-tabbed-categorised-posts-title'] : __('Trending', 'newsup');
            $category = isset($instance['newsup-select-category']) ? $instance['newsup-select-category'] : '0';


            // open the widget container
            echo $args['before_widget'];
            ?>
            <div class="tabbed-container">
                <div class="tabbed-head">
                    <ul class="nav nav-tabs ta-tabs tab-warpper" role="tablist">
                        <li class="tab tab-recent active">
                            <a href="#<?php echo esc_attr($tab_id); ?>-recent"
                               aria-controls="<?php esc_attr_e('Recent', 'newsup'); ?>" role="tab"
                               data-toggle="tab" class="font-family-1">
                                <i class="fa fa-bolt" aria-hidden="true"></i>  <?php echo esc_html($latest_title); ?>
                            </a>
                        </li>
                        <li role="presentation" class="tab tab-popular">
                            <a href="#<?php echo esc_attr($tab_id); ?>-popular"
                               aria-controls="<?php esc_attr_e('Popular', 'newsup'); ?>" role="tab"
                               data-toggle="tab" class="font-family-1">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>  <?php echo esc_html($popular_title); ?>
                            </a>
                        </li>

                        <?php if ($enable_categorised_tab == 'true'): ?>
                            <li class="tab tab-categorised">
                                <a href="#<?php echo esc_attr($tab_id); ?>-categorised"
                                   aria-controls="<?php esc_attr_e('Categorised', 'newsup'); ?>" role="tab"
                                   data-toggle="tab" class="font-family-1">
                                   <i class="fa fa-fire" aria-hidden="true"></i>  <?php echo esc_html($categorised_title); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="tab-content">
                    <div id="<?php echo esc_attr($tab_id); ?>-recent" role="tabpanel" class="tab-pane active">
                        <?php
                        newsup_render_posts('recent', $show_excerpt, $excerpt_length, $number_of_posts);
                        ?>
                    </div>
                    <div id="<?php echo esc_attr($tab_id); ?>-popular" role="tabpanel" class="tab-pane">
                        <?php
                        newsup_render_posts('popular', $show_excerpt, $excerpt_length, $number_of_posts);
                        ?>
                    </div>
                    <?php if ($enable_categorised_tab == 'true'): ?>
                        <div id="<?php echo esc_attr($tab_id); ?>-categorised" role="tabpanel" class="tab-pane">
                            <?php
                            newsup_render_posts('categorised', $show_excerpt, $excerpt_length, $number_of_posts, $category);
                            ?>
                        </div>
                    <?php endif; ?>
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
            $enable_categorised_tab = array(
                'true' => __('Yes', 'newsup'),
                'false' => __('No', 'newsup')

            );



            // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
            ?><h4><?php _e('Latest Posts', 'newsup'); ?></h4><?php
            echo parent::newsup_generate_text_input('newsup-tabbed-latest-posts-title', __('Title', 'newsup'), __('Latest', 'newsup'));

            ?><h4><?php _e('Popular Posts', 'newsup'); ?></h4><?php
            echo parent::newsup_generate_text_input('newsup-tabbed-popular-posts-title', __('Title', 'newsup'), __('Popular', 'newsup'));

            $categories = newsup_get_terms();
            if (isset($categories) && !empty($categories)) {
                ?><h4><?php _e('Categorised Posts', 'newsup'); ?></h4>
                <?php
                echo parent::newsup_generate_select_options('newsup-enable-categorised-tab', __('Enable Categorised Tab', 'newsup'), $enable_categorised_tab);
                echo parent::newsup_generate_text_input('newsup-tabbed-categorised-posts-title', __('Title', 'newsup'), __('Trending', 'newsup'));
                echo parent::newsup_generate_select_options('newsup-select-category', __('Select category', 'newsup'), $categories);

            }

        }
    }
endif;