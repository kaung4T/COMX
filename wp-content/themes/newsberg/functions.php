<?php
/**
 * Theme functions and definitions
 *
 * @package Newsberg
 */
if ( ! function_exists( 'newsberg_enqueue_styles' ) ) :
	/**
	 * @since 0.1
	 */
	function newsberg_enqueue_styles() {
		wp_enqueue_style( 'newsup-style-parent', get_template_directory_uri() . '/style.css' );
		wp_enqueue_style( 'newsberg-style', get_stylesheet_directory_uri() . '/style.css', array( 'newsup-style-parent' ), '1.0' );
		wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css');
		wp_dequeue_style( 'newsup-default',get_template_directory_uri() .'/css/colors/default.css');
		wp_enqueue_style( 'newsberg-default-css', get_stylesheet_directory_uri()."/css/colors/default.css" );
		if(is_rtl()){
		wp_enqueue_style( 'newsup_style_rtl', trailingslashit( get_template_directory_uri() ) . 'style-rtl.css' );
	    }
		
	}

endif;
add_action( 'wp_enqueue_scripts', 'newsberg_enqueue_styles', 9999 );

if ( ! function_exists( 'newsberg_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function newsberg_setup() {
// custom header Support
			$args = array(
			'default-image'		=>  get_stylesheet_directory_uri() .'/images/head-back.jpg',
			'width'			=> '1600',
			'height'		=> '600',
			'flex-height'		=> false,
			'flex-width'		=> false,
			'header-text'		=> true,
			'default-text-color'	=> '#143745'
		);
		add_theme_support( 'custom-header', $args );
}
endif;
add_action( 'after_setup_theme', 'newsberg_setup' );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function newsberg_widgets_init() {
	
	register_sidebar( array(
		'name'          => esc_html__( 'Front-Page Right Sidebar Section', 'newsberg'),
		'id'            => 'front-right-page-sidebar',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="mg-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h6>',
		'after_title'   => '</h6>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Front-Page Left Sidebar Section', 'newsberg'),
		'id'            => 'front-left-page-sidebar',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="mg-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h6>',
		'after_title'   => '</h6>',
	) );

	unregister_sidebar( 'front-page-sidebar' );

}
add_action( 'widgets_init', 'newsberg_widgets_init', 99 );


/* Remove parent theme page templates */

function newsberg_remove_page_templates( $page_templates ) {
  unset( $page_templates['template-frontpage.php'] );
  return $page_templates;
}
add_filter( 'theme_page_templates', 'newsberg_remove_page_templates' );