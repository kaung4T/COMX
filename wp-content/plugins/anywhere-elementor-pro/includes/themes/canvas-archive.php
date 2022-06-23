<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
		<title><?php echo wp_get_document_title(); ?></title>
		<?php endif; ?>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
	<?php
	do_action( 'elementor/page_templates/canvas/before_content' );

	if ( have_posts() ) : ?>

		<?php  echo do_action('aepro_archive_data', get_the_content()); ?>

	<?php else : ?>

		<?php get_template_part( 'no-results', 'archive' ); ?>

	<?php endif;

	wp_footer();
	?>
	</body>
</html>
