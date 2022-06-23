<?php
/**
 * Newsup Theme Customizer
 *
 * @package Newsup
 */

if (!function_exists('newsup_get_option')):
/**
 * Get theme option.
 *
 * @since 1.0.0
 *
 * @param string $key Option key.
 * @return mixed Option value.
 */
function newsup_get_option($key) {

	if (empty($key)) {
		return;
	}

	$value = '';

	$default       = newsup_get_default_theme_options();
	$default_value = null;

	if (is_array($default) && isset($default[$key])) {
		$default_value = $default[$key];
	}

	if (null !== $default_value) {
		$value = get_theme_mod($key, $default_value);
	} else {
		$value = get_theme_mod($key);
	}

	return $value;
}
endif;

// Load customize default values.
require get_template_directory().'/inc/ansar/customize/customizer-callback.php';

// Load customize default values.
require get_template_directory().'/inc/ansar/customize/customizer-default.php';

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function newsup_customize_register($wp_customize) {

	// Load customize controls.
	require get_template_directory().'/inc/ansar/customize/customizer-control.php';

    // Load customize sanitize.
	require get_template_directory().'/inc/ansar/customize/customizer-sanitize.php';

	$wp_customize->get_setting('blogname')->transport         = 'postMessage';
	$wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
	$wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

	if (isset($wp_customize->selective_refresh)) {
		$wp_customize->selective_refresh->add_partial('blogname', array(
				'selector'        => '.site-title a',
				'render_callback' => 'newsup_customize_partial_blogname',
			));
		
		$wp_customize->selective_refresh->add_partial('blogdescription', array(
				'selector'        => '.site-description',
				'render_callback' => 'newsup_customize_partial_blogdescription',
			));		

		$wp_customize->selective_refresh->add_partial('newsup_header_fb_link', array(
				'selector'        => '.mg-headwidget .mg-head-detail .info-right',
				'render_callback' => 'newsup_customize_partial_newsup_header_fb_link',
		));


		$wp_customize->selective_refresh->add_partial('banner_advertisement_section', array(
				'selector'        => '.header-ads',
				'render_callback' => 'newsup_customize_partial_banner_advertisement_section',
		));

		$wp_customize->selective_refresh->add_partial('select_slider_news_category', array(
				'selector'        => '.homemain',
				'render_callback' => '.newsup_customize_partial_select_slider_news_category',
		));

		$wp_customize->selective_refresh->add_partial('latest_tab_title', array(
				'selector'        => '.top-right-area .nav-tabs',
				'render_callback' => '.newsup_customize_partial_latest_tab_title',
		));


		$wp_customize->selective_refresh->add_partial('show_popular_tags_title', array(
				'selector'        => '.mg-tpt-txnlst strong',
				'render_callback' => 'newsup_customize_partial_show_popular_tags_title',
		));

		$wp_customize->selective_refresh->add_partial('newsup_date_time_show_type', array(
				'selector'        => '.mg-head-detail .info-left li',
				'render_callback' => 'newsup_customize_partial_newsup_date_time_show_type',
		));

		$wp_customize->selective_refresh->add_partial('flash_news_title', array(
				'selector'        => '.mg-latest-news .bn_title h2',
				'render_callback' => 'newsup_customize_partial_flash_news_title',
		));


		$wp_customize->selective_refresh->add_partial('you_missed_title', array(
				'selector'        => '.missed-inner .mg-sec-title h4',
				'render_callback' => 'newsup_customize_partial_you_missed_title',
		));

		$wp_customize->selective_refresh->add_partial('newsup_footer_fb_link', array(
				'selector'        => '.mg-social',
				'render_callback' => 'newsup_customize_partial_newsup_footer_fb_link',
		));





	}

    $default = newsup_get_default_theme_options();

    $selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	/*theme option panel info*/
	require get_template_directory().'/inc/ansar/customize/theme-options.php';

}
add_action('customize_register', 'newsup_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function newsup_customize_partial_blogname() {
	bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function newsup_customize_partial_blogdescription() {
	bloginfo('description');
}

function newsup_customize_partial_select_slider_news_category() {
	return get_theme_mod( 'select_slider_news_category' );
}


function newsup_customize_partial_latest_tab_title() {
	return get_theme_mod( 'latest_tab_title' );
}


function newsup_customize_partial_banner_advertisement_section() {
	return get_theme_mod( 'banner_advertisement_section' );
}

function newsup_customize_partial_newsup_header_fb_link() {
	return get_theme_mod( 'newsup_header_fb_link' );
}

function newsup_customize_partial_newsup_date_time_show_type() {
	return get_theme_mod( 'newsup_date_time_show_type' );
}

function newsup_customize_partial_show_popular_tags_title() {
	return get_theme_mod( 'show_popular_tags_title' );
}

function newsup_customize_partial_flash_news_title() {
	return get_theme_mod( 'flash_news_title' );
}


function newsup_customize_partial_you_missed_title() {
    return get_theme_mod( 'you_missed_title' );
}

function newsup_customize_partial_newsup_footer_fb_link() {
    return get_theme_mod( 'newsup_footer_fb_link' );
}




/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function newsup_customize_preview_js() {
	wp_enqueue_script('newsup-customizer', get_template_directory_uri().'/js/customizer.js', array('customize-preview'), '20151215', true);
}
add_action('customize_preview_init', 'newsup_customize_preview_js');

function newsup_customizer_css() {
    wp_enqueue_script( 'newsup-customize-controls', get_template_directory_uri() . '/assets/customizer-admin.js', array( 'customize-controls' ) );
}
add_action( 'customize_controls_enqueue_scripts', 'newsup_customizer_css',0 );


/************************* Related Post Callback function *********************************/

    function newsup_rt_post_callback ( $control ) 
    {
        if( true == $control->manager->get_setting ('newsup_enable_related_post')->value()){
            return true;
        }
        else {
            return false;
        }       
    }

/************************* Theme Customizer with Sanitize function *********************************/
function newsup_theme_option( $wp_customize )
{
    function newsup_sanitize_text( $input ) {
        return wp_kses_post( force_balance_tags( $input ) );
    }
}
add_action('customize_register','newsup_theme_option');