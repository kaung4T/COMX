<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Theme Palace
 * @subpackage Pleased
 * @since Pleased 1.0.0
 */
$options = pleased_get_theme_options();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-meta">
        <?php 
        if ( ! $options['single_post_hide_author'] ) :
        	echo pleased_author();
        endif;

        if ( ! $options['single_post_hide_date'] ) :
        	pleased_posted_on();
        endif;
        ?>
    </div><!-- .entry-meta -->

	<div class="post-wrapper">
		<div class="entry-container">
			<div class="entry-content">
				<?php
					the_content( sprintf(
						/* translators: %s: Name of current post. */
						wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'pleased' ), array( 'span' => array( 'class' => array() ) ) ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					) );

					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'pleased' ),
						'after'  => '</div>',
					) );
				?>
			</div><!-- .entry-content -->

			<div class="entry-meta">
				<?php 
					pleased_single_categories(); 

					pleased_entry_footer(); 
				?>
			</div>
		</div><!-- .entry-container -->
    </div><!-- .post-wrapper -->
</article><!-- #post-## -->
