<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */

get_header(); 
$options = pleased_get_theme_options();
$readmore = ! empty( $options['read_more_text'] ) ? $options['read_more_text'] : esc_html__( 'Read More', 'pleased' );
$sticky_posts = get_option( 'sticky_posts' );
$sticky_args = array( 
	'post_type'	=> 'post',
	'post__in' => ( array ) $sticky_posts,
);
?>

<div id="inner-content-wrapper" class="wrapper page-section">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php 
			if ( ! empty( $sticky_posts ) ) :
				$sticky_query = new WP_Query( $sticky_args );
				if ( $sticky_query -> have_posts() && get_query_var( 'paged' ) === 0 ) : ?>
					<div class="sticky-post-wrapper posts-wrapper">
						<?php while ( $sticky_query -> have_posts() ) : $sticky_query -> the_post(); ?>
			                <article class="sticky <?php echo has_post_thumbnail() ? 'has' : 'no'; ?>-post-thumbnail">
			                	<?php if ( has_post_thumbnail() ) : ?>
				                    <div class="featured-image">
				                        <a href="<?php the_permalink(); ?>">
				                        	<?php the_post_thumbnail( 'large', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
			                        	</a>
				                    </div><!-- .featured-image -->
				                <?php endif; ?>

			                    <div class="entry-container">
			                        <div class="entry-meta">
			                            <?php pleased_posted_on(); ?>
			                        </div>       

			                        <header class="entry-header">
			                            <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			                        </header>

			                        <div class="entry-content">
			                            <?php the_excerpt(); ?>
			                        </div><!-- .entry-content -->

			                        <a href="<?php the_permalink(); ?>" class="btn btn-default"><?php echo esc_html( $readmore ); ?></a>
			                    </div><!-- .entry-container -->
			                </article>
		            	<?php endwhile; ?>
		            </div><!-- .sticky-post-wrapper -->
		        <?php endif; 
		        wp_reset_postdata();
	        endif;
	        ?>

	        <div class="archive-blog-wrapper posts-wrapper clear col-2">
				<?php
				if ( have_posts() ) : ?>

					<?php
					/* Start the Loop */
					while ( have_posts() ) : the_post();

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_format() );

					endwhile;

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif; ?>
			</div>
			<?php  
			/**
			* Hook - pleased_action_pagination.
			*
			* @hooked pleased_pagination 
			*/
			do_action( 'pleased_action_pagination' ); 
			?>
		</main><!-- #main -->
	</div><!-- #primary -->

	<?php  
	if ( pleased_is_sidebar_enable() ) {
		get_sidebar();
	}
	?>
</div><!-- .wrapper -->

<?php
get_footer();
