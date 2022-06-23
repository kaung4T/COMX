<?php
$newsup_slider_category = newsup_get_option('select_slider_news_category');
$newsup_number_of_slides = newsup_get_option('number_of_slides');
$newsup_all_posts_main = newsup_get_posts($newsup_number_of_slides, $newsup_slider_category);
$newsup_count = 1;

if ($newsup_all_posts_main->have_posts()) :
    while ($newsup_all_posts_main->have_posts()) : $newsup_all_posts_main->the_post();

        global $post;
        $newsup_url = newsup_get_freatured_image_url($post->ID, 'newsup-slider-full');

        ?>
         <div class="item">
                <div class="mg-blog-post lg back-img" 
                <?php if (!empty($newsup_url)): ?>
                    style="background-image: url('<?php echo esc_url($newsup_url); ?>');">
                <?php endif; ?>

                <a class="link-div" href="<?php the_permalink(); ?>"> </a>

                <article class="bottom">
                        <span class="post-form"><i class="fa fa-camera"></i></span>
                        <div class="mg-blog-category"> <?php newsup_post_categories(); ?> </div>
                        <h4 class="title"> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <?php newsup_post_meta(); ?>
                </article>
            </div>
        </div>
    <?php
    endwhile;
endif;
wp_reset_postdata();
?>