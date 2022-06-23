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

$class = has_post_thumbnail() ? '' : 'no-post-thumbnail';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="featured-image" style="background-image: url('<?php the_post_thumbnail_url( 'post-thumbnail' ); ?>');">
            <a href="<?php the_permalink(); ?>" class="post-thumbnail-link"></a>
        </div><!-- .featured-image -->
    <?php endif; ?>

    <div class="entry-container">
        <div class="entry-meta">
            <?php pleased_posted_on(); ?>     
        </div><!-- .entry-meta -->

        <header class="entry-header">
            <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        </header>

    </div><!-- .entry-container -->
</article><!-- #post-## -->
