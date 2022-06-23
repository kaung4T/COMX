<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package newsup
 */

if (!function_exists('newsup_post_categories')) :
    function newsup_post_categories($separator = '&nbsp')
    {
        $global_show_categories = newsup_get_option('global_show_categories');
        if ($global_show_categories == 'no') {
            return;
        }

        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {

            global $post;

            $post_categories = get_the_category($post->ID);
            if ($post_categories) {
                $output = '';
                foreach ($post_categories as $post_category) {
                    $t_id = $post_category->term_id;
                    $color_id = "category_color_" . $t_id;

                    // retrieve the existing value(s) for this meta field. This returns an array
                    $term_meta = get_option($color_id);
                    $color_class = ($term_meta) ? $term_meta['color_class_term_meta'] : 'category-color-1';

                    $output .= '<a class="newsup-categories ' . esc_attr($color_class) . '" href="' . esc_url(get_category_link($post_category)) . '" alt="' . esc_attr(sprintf(__('View all posts in %s', 'newsup'), $post_category->name)) . '"> 
                                 ' . esc_html($post_category->name) . '
                             </a>';
                }
                $output .= '';
                echo $output;

            }
        }
    }
endif;



if (!function_exists('newsup_get_category_color_class')) :

    function newsup_get_category_color_class($term_id)
    {

        $color_id = "category_color_" . $term_id;
        // retrieve the existing value(s) for this meta field. This returns an array
        $term_meta = get_option($color_id);
        $color_class = ($term_meta) ? $term_meta['color_class_term_meta'] : '';
        return $color_class;


    }
endif;

if (!function_exists('newsup_post_meta')) :

    function newsup_post_meta()
    {
    $global_post_date = get_theme_mod('global_post_date_author_setting','show-date-author');
    if($global_post_date =='show-date-author') {
    ?>
    <div class="mg-blog-meta">
        <span class="mg-blog-date"><i class="fa fa-clock-o"></i>
         <a href="<?php echo esc_url(get_month_link(get_post_time('Y'),get_post_time('m'))); ?>">
         <?php echo esc_html(get_the_date('M j, Y')); ?></a></span>
         <a class="auth" href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>"><i class="fa fa-user-circle-o"></i> 
        <?php the_author(); ?></a>
        <?php edit_post_link( __( 'Edit', 'newsup' ), '<span class="post-edit-link"><i class="fa fa-edit"></i>', '</span>' ); ?> 
    </div>
    <?php } 
            elseif($global_post_date =='show-date-only') {
    ?>
    <div class="mg-blog-meta">
        <span class="mg-blog-date"><i class="fa fa-clock-o"></i>
         <a href="<?php echo esc_url(get_month_link(get_post_time('Y'),get_post_time('m'))); ?>">
         <?php echo esc_html(get_the_date('M j, Y')); ?></a></span>
         <?php edit_post_link( __( 'Edit', 'newsup' ), '<span class="post-edit-link"><i class="fa fa-edit"></i>', '</span>' ); ?>
    </div>
    <?php } 
            elseif($global_post_date =='show-author-only') {
    ?>
    <div class="mg-blog-meta">
        <a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>"><i class="fa fa-user-circle-o"></i> 
        <?php the_author(); ?></a>
        <?php edit_post_link( __( 'Edit', 'newsup' ), '<span class="post-edit-link"><i class="fa fa-edit"></i>', '</span>' ); ?>
    </div>
    <?php } elseif($global_post_date =='hide-date-author') { } ?>
<?php }
endif;

function newsup_read_more() {
    
    global $post;
    
    $readbtnurl = '<br><a class="btn btn-theme post-btn" href="' . get_permalink() . '">'.__('Read More','newsup').'</a>';
    
    return $readbtnurl;
}
add_filter( 'the_content_more_link', 'newsup_read_more' );