<?php

// Load widget base.
require_once get_template_directory() . '/inc/ansar/widgets/widgets-base.php';

/* Theme Widget sidebars. */
require get_template_directory() . '/inc/ansar/widgets/widgets-common-functions.php';

/* Theme Widgets*/
require get_template_directory() . '/inc/ansar/widgets/widget-posts-carousel.php';
require get_template_directory() . '/inc/ansar/widgets/widget-posts-double-category.php';
require get_template_directory() . '/inc/ansar/widgets/widget-latest-news.php';
require get_template_directory() . '/inc/ansar/widgets/widget-posts-list.php';
require get_template_directory() . '/inc/ansar/widgets/widget-posts-tabbed.php';
require get_template_directory() . '/inc/ansar/widgets/widget-posts-slider.php';
require get_template_directory() . '/inc/ansar/widgets/featured-post-widget.php';
require get_template_directory() . '/inc/ansar/widgets/widget-design-slider.php';



/* Register site widgets */
if ( ! function_exists( 'newsup_widgets' ) ) :
    /**
     * Load widgets.
     *
     * @since 1.0.0
     */
    function newsup_widgets() {
        register_widget( 'Newsup_Posts_Carousel' );
        register_widget( 'Newsup_Dbl_Col_Cat_Posts' );
        register_widget( 'Newsup_Latest_Post' );
        register_widget( 'Newsup_Posts_List' );
        register_widget( 'Newsup_Tab_Posts' );
        register_widget( 'Newsup_Posts_Slider' );
        register_widget( 'Newsup_horizontal_vertical_posts');
        register_widget( 'Newsup_Design_Slider');
    }
endif;
add_action( 'widgets_init', 'newsup_widgets' );
