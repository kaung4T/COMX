<?php
/**
 * Getting Started Page. 
 *
 * @package Newsup
 */
require get_template_directory() . '/inc/ansar/admin/class-getting-start-plugin-helper.php';


// Adding Getting Started Page in admin menu

if( ! function_exists( 'newsup_getting_started_menu' ) ) :
function newsup_getting_started_menu() {	
		$plugin_count = null;
		if ( !is_plugin_active( 'shortbuild/shortbuild.php' ) ):	
			$plugin_count =	'<span class="awaiting-mod action-count">1</span>';
		endif;
	    /* translators: %1$s %2$s: about */		
		$title = sprintf(esc_html__('About %1$s %2$s', 'newsup'), esc_html( NEWSUP_THEME_NAME ), $plugin_count);
		/* translators: %1$s: welcome page */	
		add_theme_page(sprintf(esc_html__('Welcome to %1$s', 'newsup'), esc_html( NEWSUP_THEME_NAME ), esc_html(NEWSUP_THEME_VERSION)), $title, 'edit_theme_options', 'newsup-getting-started', 'newsup_getting_started_page');
}
endif;
add_action( 'admin_menu', 'newsup_getting_started_menu' );

// Load Getting Started styles in the admin
if( ! function_exists( 'newsup_getting_started_admin_scripts' ) ) :
function newsup_getting_started_admin_scripts( $hook ){
	// Load styles only on our page
	if( 'appearance_page_newsup-getting-started' != $hook ) return;

    wp_enqueue_style( 'newsup-getting-started', get_template_directory_uri() . '/inc/ansar/admin/css/getting-started.css', false, NEWSUP_THEME_VERSION );
    wp_enqueue_script( 'plugin-install' );
    wp_enqueue_script( 'updates' );
    wp_enqueue_script( 'newsup-getting-started', get_template_directory_uri() . '/inc/ansar/admin/js/getting-started.js', array( 'jquery' ), NEWSUP_THEME_VERSION, true );
    wp_enqueue_script( 'newsup-recommended-plugin-install', get_template_directory_uri() . '/inc/ansar/admin/js/recommended-plugin-install.js', array( 'jquery' ), NEWSUP_THEME_VERSION, true );    
    wp_localize_script( 'newsup-recommended-plugin-install', 'newsup_start_page', array( 'activating' => __( 'Activating ', 'newsup' ) ) );
}
endif;
add_action( 'admin_enqueue_scripts', 'newsup_getting_started_admin_scripts' );


// Plugin API
if( ! function_exists( 'newsup_call_plugin_api' ) ) :
function newsup_call_plugin_api( $slug ) {
	require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		$call_api = get_transient( 'newsup_about_plugin_info_' . $slug );

		if ( false === $call_api ) {
				$call_api = plugins_api(
					'plugin_information', array(
						'slug'   => $slug,
						'fields' => array(
							'downloaded'        => false,
							'rating'            => false,
							'description'       => false,
							'short_description' => true,
							'donate_link'       => false,
							'tags'              => false,
							'sections'          => true,
							'homepage'          => true,
							'added'             => false,
							'last_updated'      => false,
							'compatibility'     => false,
							'tested'            => false,
							'requires'          => false,
							'downloadlink'      => false,
							'icons'             => true,
						),
					)
				);
				set_transient( 'newsup_about_plugin_info_' . $slug, $call_api, 30 * MINUTE_IN_SECONDS );
			}

			return $call_api;
		}
endif;

// Callback function for admin page.
if( ! function_exists( 'newsup_getting_started_page' ) ) :
function newsup_getting_started_page() { ?>
	<div class="wrap getting-started">
		<h2 class="notices"></h2>
		<div class="intro-wrap">
			<div class="intro">
				<h3>
				<?php 
				/* translators: %1$s %2$s: welcome message */	
				printf( esc_html__( 'Welcome to %1$s - Version %2$s', 'newsup' ), esc_html( NEWSUP_THEME_NAME ), esc_html( NEWSUP_THEME_VERSION ) ); ?></h3>
				<p><?php esc_html_e( 'Newsup is a fast, clean, modern-looking Best Responsive News Magazine WordPress theme. The theme is fully widgetized, so users can manage the content by using easy to use widgets. Newsup is suitable for dynamic news, newspapers, magazine, publishers, blogs, editors, online and gaming magazines, newsportals,personal blogs, newspaper, publishing or review siteand any creative website. Newsup is SEO friendly, WPML,Gutenberg, translation and RTL ready. Live preview : https://demo.themeansar.com/newsup and documentation at https://docs.themeansar.com/docs/newsup/', 'newsup' ); ?></p>
			</div>
			<div class="intro right">
				<a target="_blank" href="https://themeansar.com/"><img src="<?php echo esc_url(get_template_directory_uri());  ?>/inc/ansar/admin/images/logo.png"></a>
			</div>
		</div>
		<div class="panels">
			<ul class="inline-list">
			    <li class="current">
					<a id="getting-started-panel" href="#">
						<?php esc_html_e( 'Getting Started', 'newsup' ); ?>
					</a>
				</li>
				<li class="recommended-plugins-active">
					<a id="plugins" href="#">
						<?php esc_html_e( 'Demo Content', 'newsup' ); 
						if ( !is_plugin_active( 'shortbuild/shortbuild.php' ) ):  ?>
							<span class="plugin-not-active">1</span>
						<?php endif; ?>
					</a>
				</li>
				<li>
                	<a id="useful-plugin-panel" href="#">
						<?php esc_html_e( 'Useful Plugins', 'newsup' ); ?>
					</a>
				</li>
				
			</ul>
			<div id="panel" class="panel">
				<?php require get_template_directory() . '/inc/ansar/admin/tabs/getting-started-panel.php'; ?>
				<?php require get_template_directory() . '/inc/ansar/admin/tabs/recommended-plugins-panel.php'; ?>
				<?php require get_template_directory() . '/inc/ansar/admin/tabs/useful-plugin-panel.php'; ?>
			</div>
			<div class="panel">
				<div class="panel-aside panel-column w-50">
					<h4><?php esc_html_e( 'Newsup Theme Support', 'newsup' ); ?></h4>
					<a class="button button-primary" target="_blank" href="//wordpress.org/support/theme/newsup/" title="<?php esc_attr_e( 'Get Support', 'newsup' ); ?>"><?php esc_html_e( 'Get Support', 'newsup' ); ?></a>
				</div>
			   <div class="panel-aside panel-column w-50">
					<h4><?php esc_html_e( 'Your feedback is valuable to us', 'newsup' ); ?></h4>
					<a class="button button-primary" target="_blank" href="//wordpress.org/support/theme/newsup/reviews/#new-post" title="<?php esc_attr_e( 'Submit a review', 'newsup' ); ?>"><?php esc_html_e( 'Submit a review', 'newsup' ); ?></a>
				</div>
			</div>
		</div>
	</div>
	<?php
}
endif;


/**
 * Admin notice 
 */
class newsup_screen {
 	public function __construct() {
		/* notice  Lines*/
		add_action( 'load-themes.php', array( $this, 'newsup_activation_admin_notice' ) );
	}
	public function newsup_activation_admin_notice() {
		global $pagenow;

		if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
			add_action( 'admin_notices', array( $this, 'newsup_admin_notice' ), 99 );
		}
	}
	/**
	 * Display an admin notice linking to the welcome screen
	 * @sfunctionse 1.8.2.4
	 */
	public function newsup_admin_notice() {
		?>			
		<div class="updated notice is-dismissible newsup-notice">
			<h1><?php
			$theme_info = wp_get_theme();
			printf( esc_html__('Congratulations, Welcome to %1$s Theme', 'newsup'), esc_html( $theme_info->Name ), esc_html( $theme_info->Version ) ); ?>
			</h1>
			<p><?php echo sprintf( esc_html__("Thank you for choosing Newsup theme. To take full advantage of the complete features of the theme, you have to go to our %1\$s welcome page %2\$s.", "newsup"), '<a href="' . esc_url( admin_url( 'themes.php?page=newsup-getting-started' ) ) . '">', '</a>' ); ?></p>
			
			<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=newsup-getting-started' ) ); ?>" class="button button-blue-secondary button_info" style="text-decoration: none;"><?php echo esc_html__('Get started with Newsup','newsup'); ?></a></p>
		</div>
		<?php
	}
	
}
$GLOBALS['newsup_screen'] = new newsup_screen();