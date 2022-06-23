<?php class Newsup_horizontal_vertical_posts extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname' => 'newsup-widget-horizontal-vertical-posts',
			'description' => __('Display Featured Posts', 'newsup')
		);
		parent::__construct(false, $name = __('AR: Featured Posts', 'newsup') , $widget_ops);
	}

	function form($instance) {

		$instance = wp_parse_args(
			(array) $instance,
			array(
				'widget_title' => '',
				'category' => '',
				'type' => 1,
			)
		);
		$title = isset( $instance['widget_title'] ) ? $instance['widget_title'] : '';
		$type = ( isset($instance['type']) && is_numeric($instance['type']) ) ? (int) $instance['type'] : 1; ?>
		<p>
			<label for="<?php echo $this->get_field_id('widget_title'); ?>">
				<?php esc_html_e('Title: ', 'newsup'); ?>
			</label>
			<input id="<?php echo $this->get_field_id('widget_title'); ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
		</p>
		<p>
			<input type="radio" id="<?php echo ($this->get_field_id('type') . '-1'); ?>" name="<?php echo ($this->get_field_name('type')); ?>" value="1" <?php checked($type == 1, true); ?>>
			<label for="<?php echo ($this->get_field_id('type') . '-1'); ?>" class="input-label"><?php esc_html_e('Latest Posts', 'newsup'); ?></label>
			<br>
			<input type="radio" id="<?php echo ($this->get_field_id( 'type') . '-2'); ?>" name="<?php echo ($this->get_field_name('type')); ?>" value="2" <?php checked($type == 2, true); ?>>
			<label for="<?php echo ($this->get_field_id('type') . '-2'); ?>" class="input-label"><?php esc_html_e('Show Posts from Category', 'newsup'); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>">
				<?php esc_html_e('Choose Category:', 'newsup'); ?>
			</label>
			<?php wp_dropdown_categories(
				array(
					'show_option_none' => ' ',
					'name' => $this->get_field_name('category') ,
					'selected' => $instance['category']
				)
			); ?>
		</p>
		<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['category'] = absint($new_instance['category']);
		$instance['style'] = absint($new_instance['style']);
		$instance['widget_title'] = sanitize_text_field($new_instance['widget_title']);
		$instance['type'] = ( isset($new_instance['type']) && $new_instance['type'] > 0 && $new_instance['type'] < 3 ) ? (int) $new_instance['type'] : 1;
		return $instance;
	}

	function widget($args, $instance) {

		$category = isset($instance['category']) ? $instance['category'] : '';
		$style = empty($instance['style']) ? '' : $instance['style'];
		$widget_title = apply_filters( 'widget_title', empty( $instance['widget_title'] ) ? '' : $instance['widget_title'], $instance, $this->id_base );
		$type = ( isset($instance['type']) && is_numeric($instance['type']) ) ? (int) $instance['type'] : 1;
		global $post;

		$post_type = array(
			'posts_per_page' => 4,
			'post_type' => array('post'),
			'post__not_in' => get_option('sticky_posts'),
		);
		if ( $type == 2 ) {
			$post_type['category__in'] = $category;
		}

		$get_featured_posts = new WP_Query($post_type);

		//echo $args['before_widget']; ?>
		<!-- mg-posts-sec mg-posts-modul-1 -->
		<div class="mg-posts-sec mg-posts-modul-1">
        	<!-- mg-sec-title -->
        	<div class="mg-sec-title">
        		<h4><?php echo $widget_title; ?></h4>
        	</div>
        	 <!-- // mg-sec-title -->

        	<div class="mg-posts-sec-inner row">
            	<!-- mg-posts-sec-inner -->
            	<div class="<?php echo ($style == 0) ? 'col-lg-6 col-md-6 col-sm-6 col-xs-12' : 'col-12 ' ;?>">
                    <!--  post lg -->   
					<?php
					$i=1;
					while ($get_featured_posts->have_posts()):$get_featured_posts->the_post(); ?>
					<?php if ( $i == 1 ) { ?>
							<?php 
							$url = newsup_get_freatured_image_url($post->ID, 'newsup-featured'); ?>
						<div class="mg-blog-post lg back-img" style="background-image: url('<?php echo esc_url($url); ?>');">
							<a class="link-div" href="<?php the_permalink(); ?>">
		                	</a>
						<article class="bottom">
                        	<span class="post-form"><i class="fa fa-camera"></i></span>
                        	<div class="mg-blog-category"> <?php newsup_post_categories(); ?> </div>
                        	<h4 class="title"> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        	 <?php newsup_post_meta(); ?>
                     	</article>
					</div> <!-- /post lg -->             
				</div> <!-- /col-md-6 -->

				<div class="small-list-post <?php echo ($style == 0) ? 'col-lg-6 col-md-6 col-sm-6 col-xs-12' : 'col-12' ;?> ">
					<!-- small-list-post -->
                	<ul>
					<?php } ?>
						<!-- small_post -->
						<li class="small-post featured-post-<?php echo esc_attr($i); ?> clearfix">
						<?php if ( has_post_thumbnail() ) { 
						$url = newsup_get_freatured_image_url($post->ID, 'newsup-featured'); ?>
							<!-- img-small-post -->
							<div class="img-small-post">
								<a href="<?php the_permalink(); ?>">
	                            	<?php if (!empty($url)): ?>
	                                	<img src="<?php echo esc_url($url); ?>">
	                            	<?php endif; ?>
	                        	</a>
							</div>
						<?php } ?>
						<!-- // img-small-post -->
						<!-- small-post-content -->
						<div class="small-post-content">
							<div class="mg-blog-category"> <?php newsup_post_categories(); ?> </div>
							<h5 class="title"> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
						</div>
						<!-- /small-post-content -->
						</li><!-- /small_post -->
					<?php
				$i++;
				endwhile;
				// Reset Post Data
				wp_reset_postdata(); ?>
				<?php if ( $style == 1 ) { ?>
					</ul><!-- .row -->
				<?php } ?>
				</div>
				<!-- // small-list-post -->
        	</div>
        	<!-- // mg-posts-sec-inner -->

		<?php echo $args['after_widget'] . '<!-- .widget_featured_post -->';
	}
}