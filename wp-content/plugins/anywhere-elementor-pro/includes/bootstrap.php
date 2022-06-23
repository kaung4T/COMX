<?php

namespace Aepro;

use function class_exists;
use Elementor;
use Elementor\Controls_Manager;
use Elementor\Plugin;

class Aepro{

    private static $_instance = null;

    public $_hook_positions = array();

    public static $_helper = null;

    /** @var array Themes that are fully supported in core */
    protected $supported_themes = [ 'generatepress', 'oceanwp', 'astra', 'hestia', 'twentyseventeen', 'wpbf', 'page-builder-framework'];

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function init(){

        add_post_type_support( 'ae_global_templates', 'elementor' );
        add_filter( 'widget_text', 'do_shortcode' );
    }

    /**
     * Plugin constructor.
     */
    private function __construct() {

        $this->load_hook_positions();

        $this->_includes();

        self::$_helper = new Helper();

        add_action( 'init', [ $this, 'init' ] );
        add_action( 'plugins_loaded', [ $this, '_plugins_loaded' ] );
        add_action( 'elementor/init',[ $this, 'elementor_loaded']);

        // for frontend scripts & styles
        add_action( 'wp_enqueue_scripts', [ $this, '_enqueue_scripts' ]);

        // elementor editor scripts & styles
        add_action('elementor/editor/wp_head', [$this, '_editor_enqueue_scripts']);


        // for admin scripts & styles
        add_action( 'admin_enqueue_scripts', [ $this, '_admin_enqueue_scripts' ]);


        add_action( 'elementor/widgets/widgets_registered', [$this, 'elementor_widget_registered']);


        add_filter( 'manage_ae_global_templates_posts_columns', [$this,'set_custom_edit_ae_global_templates_posts_columns'] );
        add_action( 'manage_ae_global_templates_posts_custom_column' , [$this, 'add_ae_global_templates_columns'], 10, 2 );
        add_filter( 'ae_pro_filter_hook_positions', [$this, 'theme_hooks']);


        // woo template hook
        add_filter( 'wc_get_template_part', [$this, 'load_wc_layout'],10,3 );

        // woo scripts setup
        add_action( 'template_redirect', [$this, 'ae_woo_setup'] );

        add_action( 'after_setup_theme', [$this, 'editor_woo_scripts'] );

        // TODO:: Do this only if product page is using AE Template
        add_filter( 'woocommerce_enqueue_styles', [$this, 'load_wc_styles'], 99, 1 );


        add_action('woocommerce_init', [ $this, 'woo_init']);

        $map_key = get_option('ae_pro_gmap_api');
        if($map_key) {
            add_filter('acf/fields/google_map/api', [$this, 'register_acf_map_key']);

            add_action('acf/init', [$this, 'register_acf_pro_map_key']);
        }
        add_filter('template_redirect', [$this, 'block_template_frontend']);

	    add_filter( 'template_include', [$this, 'ae_template_canvas']);

	    add_action('admin_init', [$this, 'db_upgrade_script']);

	    add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );

    }

	public function ae_template_canvas($template){
		if ( is_singular('ae_global_templates') ) {
			$helper = new Helper();

			if ($helper->is_canvas_enabled(get_the_ID())) {
				$template = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';
				return $template;
			}

			if($helper->is_heder_footer_enabled(get_the_ID())){
				$template = ELEMENTOR_PATH . '/modules/page-templates/templates/header-footer.php';
				return $template;
			}
		}

		return $template;
	}

    public function register_acf_pro_map_key(){
        $map_key = get_option('ae_pro_gmap_api');
        acf_update_setting('google_api_key', $map_key);
    }

    public function register_acf_map_key($api){
        $map_key = get_option('ae_pro_gmap_api');

        $api['key'] = $map_key;

        return $api;
    }

    public  function get_script_suffix(){
        $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

        return $suffix;
    }

    public function plugin_activated(){
        //echo "Plugin Activated";
    }
    public function woo_init(){
        if(is_product() || isset($_REQUEST['ae_global_templates'])){
            \WC_Frontend_Scripts::load_scripts();
            wp_enqueue_script( 'wc-single-product' );
            wp_enqueue_script( 'wc-product-gallery-zoom' );
            wp_enqueue_script( 'flexslider' );
            wp_enqueue_script( 'photoswipe-ui-default' );
            wp_enqueue_style('photoswipe-default-skin');
            add_action( 'wp_footer', 'woocommerce_photoswipe' );
        }

        if(isset($_REQUEST['ae_global_templates'])){
            add_theme_support( 'wc-product-gallery-zoom' );
            add_theme_support( 'wc-product-gallery-lightbox' );
            add_theme_support( 'wc-product-gallery-slider' );
        }
    }

    public function load_wc_styles($styles){
        return $styles;
    }

    function _editor_enqueue_scripts(){

        wp_enqueue_script('aepro-editor-js',AE_PRO_URL.'includes/assets/js/editor'. AE_PRO_SCRIPT_SUFFIX .'.js',array('jquery'),AE_PRO_VERSION );
        wp_localize_script('aepro-editor-js','aepro',array(
            'ajaxurl' => admin_url('admin-ajax.php')
        ));

        wp_enqueue_style('vegas-css',AE_PRO_URL.'includes/assets/lib/vegas/vegas'. AE_PRO_SCRIPT_SUFFIX .'.css' );
        wp_enqueue_script('vegas',AE_PRO_URL.'includes/assets/lib/vegas/vegas'. AE_PRO_SCRIPT_SUFFIX .'.js',array('jquery'),'2.4.0', true);
        wp_enqueue_script('ae-elementor-editor-js',AE_PRO_URL.'includes/assets/js/common'. AE_PRO_SCRIPT_SUFFIX .'.js', array('jquery','ae-gmap'), AE_PRO_VERSION);

        $localize_data = array(
            'plugin_url' => plugins_url('anywhere-elementor-pro')
        );
        wp_localize_script( 'ae-elementor-editor-js', 'aepro_editor', $localize_data );


        //wp_enqueue_script('ae-swiper',AE_PRO_URL.'includes/assets/lib/swiper/js/swiper'. AE_PRO_SCRIPT_SUFFIX .'.js',array('jquery', 'imagesloaded'),'4.3.2',true);
        //wp_enqueue_style('ae-swiper',AE_PRO_URL.'includes/assets/lib/swiper/css/swiper'. AE_PRO_SCRIPT_SUFFIX .'.css');

	    wp_enqueue_script( 'swiper' );

        $map_key = get_option('ae_pro_gmap_api');
        if($map_key) {
            wp_enqueue_script('ae-gmap', 'https://maps.googleapis.com/maps/api/js?key=' . $map_key);
        }

        wp_enqueue_script('ae-masonry',AE_PRO_URL.'includes/assets/lib/masonry/js/masonry.pkgd'. AE_PRO_SCRIPT_SUFFIX .'.js',array('jquery', 'jquery-masonry'),'2.0.1', true);

        wp_enqueue_style('aep-editor', AE_PRO_URL.'includes/assets/css/aep-editor.css');

        wp_enqueue_style('aep-font', AE_PRO_URL.'includes/assets/lib/aep-icons/style.css');

    }


    public function theme_hooks($hook_positions){
        if(class_exists('Aepro\Ae_Theme')){
            $theme_obj = new Ae_Theme();
            $hook_positions = $theme_obj->theme_hooks($hook_positions);
        }
        return $hook_positions;
    }




   public function set_custom_edit_ae_global_templates_posts_columns($columns) {
        //unset( $columns['author'] );
        $columns['ae_shortcode_column'] = __( 'Shortcode', 'ae-pro' );
        $columns['ae_global_template_column'] = __( 'Is Global', 'ae-pro' );
        $columns['ae_render_mode_column'] = __( 'Render Mode', 'ae-pro' );
        return $columns;
    }
    public function add_ae_global_templates_columns( $column, $post_id ) {

        switch ( $column ) {

            case 'ae_shortcode_column' :
                echo '<input type=\'text\' class=\'widefat\' value=\'[INSERT_ELEMENTOR id="'.$post_id.'"]\' readonly="">';
                break;

            case 'ae_global_template_column' :
                $is_global = get_post_meta( $post_id , 'ae_apply_global' , true );
                if(!empty($is_global)){
                    echo '<span class="dashicons dashicons-star-filled" style="color:#ffd71c;"></span>';
                }
                break;

            case 'ae_render_mode_column' :
                $helper = new Helper();
                $render_mode = get_post_meta( $post_id , 'ae_render_mode' , true );
                if(!empty($render_mode)){
                    $render_modes = $helper->get_ae_render_mode_hook();

                    if(isset($render_modes[$render_mode])){
                        echo $render_modes[$render_mode];
                    }else{
                        echo '<span style="color:#ff6033">' .$render_mode.'</span>';
                    }

                }
                break;
        }
    }

    public function _plugins_loaded(){

	    load_plugin_textdomain( 'ae-pro',false, AE_PRO_FILE.'includes/languages/ae-pro');

	    if ( ! did_action( 'elementor/loaded' ) ) {
		    /* TO DO */
		    add_action( 'admin_notices', array( $this, 'ae_pro_fail_load' ) );
		    return;
	    }

        require_once AE_PRO_PATH.'includes/controls/control-manager.php';

        // WPML Compatibility
        if (is_plugin_active('sitepress-multilingual-cms/sitepress.php') && is_plugin_active('wpml-string-translation/plugin.php')) {
            require_once AE_PRO_PATH.'includes/wpml/class-wpml-ae-woo-tabs.php';
            require_once AE_PRO_PATH.'includes/wpml/wpml-compatibility.php';

        }

        /**
         * Define ACF Constants
         *
         */

	    if (class_exists('acf_pro')){

		    define('AE_ACF', true);
		    define('AE_ACF_PRO', true);

	    }elseif(class_exists('ACF')){

	        define('AE_ACF', true);

        }




    }

    private function _includes(){
        global $ae_template;


        require_once AE_PRO_PATH.'includes/themes/Ae_Theme_Base.php';


        $enable_generic = get_option('ae_pro_generic_theme');

        if(!in_array($ae_template, $this->supported_themes) && $enable_generic == 1){
            $ae_template = 'generic';
        }

        if(file_exists(AE_PRO_PATH.'includes/themes/'.$ae_template.'/Ae_Theme.php')){
            require_once AE_PRO_PATH.'includes/themes/'.$ae_template.'/Ae_Theme.php';
        }else{
            //echo 'test'; die();
            add_action( 'after_setup_theme', function() {
                do_action('ae_external_theme_support');
            });
        }




        // controls on existing elements

        require_once AE_PRO_PATH.'includes/controls/featured-bg.php';
        //require_once AE_PRO_PATH.'includes/controls/show-on-hover.php';

        // Todo :: load only one frontend
        require_once AE_PRO_PATH.'includes/frontend.php';
        require_once AE_PRO_PATH.'includes/template.php';

        require_once AE_PRO_PATH.'includes/post_helper.php';
        require_once AE_PRO_PATH.'includes/rules.php';

        require_once AE_PRO_PATH.'includes/helper.php';
        require_once AE_PRO_PATH.'includes/post-type.php';
        require_once AE_PRO_PATH.'includes/admin/metaboxes.php';
		require_once AE_PRO_PATH.'includes/elements/bg-slider.php';

        if(is_admin()){
            require_once AE_PRO_PATH.'includes/admin/admin.php';
            require_once AE_PRO_PATH.'includes/admin/admin-helper.php';
            require_once AE_PRO_PATH.'includes/admin/template-config.php';
            //require_once AE_PRO_PATH.'includes/license/admin.php';
        }

        require_once AE_PRO_PATH.'includes/license/EDD_SL_Plugin_Updater.php';
        require_once AE_PRO_PATH.'includes/license-manager.php';

        if($this->licence_activated()){
            //require_once AE_PRO_PATH.'includes/license/wp-updates-plugin.php';
            //new AE_Updater( 'http://wp-updates.com/api/2/plugin',plugin_basename(AE_PRO_PATH.'anywhere-elementor-pro.php') );
        }


    }

    public function licence_activated(){
        return true;
    }

    public function _enqueue_scripts(){

    	global $wp;

        wp_enqueue_style('ae-pro-css',AE_PRO_URL.'includes/assets/css/ae-pro' . AE_PRO_SCRIPT_SUFFIX . '.css', AE_PRO_VERSION);
        //wp_enqueue_style('ae-pro-facet-css',AE_PRO_URL.'includes/assets/css/ae-pro-facet' . AE_PRO_SCRIPT_SUFFIX . '.css', AE_PRO_VERSION);
        wp_enqueue_script('ae-pro-js',AE_PRO_URL.'includes/assets/js/ae-pro' . AE_PRO_SCRIPT_SUFFIX . '.js', array('jquery'),AE_PRO_VERSION, true);
        wp_enqueue_script('aepro-editor-js',AE_PRO_URL.'includes/assets/js/common'. AE_PRO_SCRIPT_SUFFIX .'.js', array('jquery'), AE_PRO_VERSION, true);
		wp_enqueue_style('vegas-css',AE_PRO_URL.'includes/assets/lib/vegas/vegas'. AE_PRO_SCRIPT_SUFFIX .'.css' );
        wp_enqueue_script('vegas',AE_PRO_URL.'includes/assets/lib/vegas/vegas'. AE_PRO_SCRIPT_SUFFIX.'.js',array('jquery'),'2.4.0', true);


        $helper = new Helper();


        if(Plugin::instance()->preview->is_preview_mode()) {

            $post_css = $helper->ae_get_post_css();
            wp_add_inline_style('ae-pro-css',$post_css);


            if(class_exists('ACF') || class_exists('acf')){

                $post_cf_css = $helper->ae_get_cf_image_css();
                wp_add_inline_style('ae-pro-css', $post_cf_css);

                $post_term_cf_css = $helper->ae_get_term_cf_image_css();
                wp_add_inline_style('ae-pro-css',$post_term_cf_css);

            }
        }


        wp_enqueue_script( 'wc-single-product' );
        wp_enqueue_style( 'woocommerce-general' );


        wp_localize_script('ae-pro-js','aepro',array(
            'ajaxurl' => admin_url('admin-ajax.php'),
	        'current_url' => base64_encode($helper->get_current_url_non_paged())
        ));
		$localize_data = array(
            'plugin_url' => plugins_url('anywhere-elementor-pro')
        );
        wp_localize_script( 'aepro-editor-js', 'aepro_editor', $localize_data );

        //wp_register_script('ae-swiper',AE_PRO_URL.'includes/assets/lib/swiper/js/swiper'. AE_PRO_SCRIPT_SUFFIX .'.js',array('jquery', 'imagesloaded'),'4.3.2',true);
        //wp_enqueue_style('ae-swiper',AE_PRO_URL.'includes/assets/lib/swiper/css/swiper'. AE_PRO_SCRIPT_SUFFIX .'.css');

        $map_key = get_option('ae_pro_gmap_api');
        if($map_key) {
            wp_register_script('ae-gmap', 'https://maps.googleapis.com/maps/api/js?key=' . $map_key);
        }

        wp_enqueue_script('ae-masonry',AE_PRO_URL.'includes/assets/lib/masonry/js/masonry.pkgd'. AE_PRO_SCRIPT_SUFFIX .'.js',array('jquery', 'jquery-masonry'),'2.0.1', true);
        wp_register_script('ae-infinite-scroll', AE_PRO_URL.'includes/assets/lib/infinite-scroll/infinite-scroll.pkgd'. AE_PRO_SCRIPT_SUFFIX.'.js', array('jquery'), '3.0.5', true);

    }

    public function _admin_enqueue_scripts(){
        $screen = get_current_screen();
        if($screen->post_type == 'ae_global_templates'){
            wp_enqueue_script('ae-admin-js',AE_PRO_URL.'includes/admin/admin-scripts' . AE_PRO_SCRIPT_SUFFIX . '.js', array(), AE_PRO_VERSION);

            wp_enqueue_style('aep-select2',AE_PRO_URL.'includes/assets/lib/select2/css/select2'. AE_PRO_SCRIPT_SUFFIX .'.css');
            wp_enqueue_script('aep-select2',AE_PRO_URL.'includes/assets/lib/select2/js/select2'. AE_PRO_SCRIPT_SUFFIX .'.js',['jquery']);
        }



    }

    public function load_hook_positions(){
        $hook_positions = array(
            '' => esc_html__('None','ae-pro'),
            'custom' => esc_html__('Custom','ae-pro'),
        );
        $this->_hook_positions = $hook_positions;

    }

    public function get_hook_positions(){
        return $this->_hook_positions;
    }

    public function elementor_loaded(){
        \Elementor\Plugin::instance()->elements_manager->add_category(
            'ae-template-elements',
            [
                'title'  => 'AE Template Elements',
                'icon' => 'fa fa-plug'
            ],
            1
        );

        require_once AE_PRO_PATH.'includes/aep-finder.php';

	    add_action( 'elementor/finder/categories/init', function( $categories_manager ) {
		    // Add the category
		    $categories_manager->add_category( 'aep-finder', new Aep_Finder() );
	    } );


    }


    public function load_wc_layout($template,$slug,$name){

        global $product, $ae_template;
        $helper = new Helper();
        $ae_wc_template = '';

        if($slug == 'content' && $name == 'single-product'){
            $ae_wc_template = $helper->get_ae_active_post_template($product->get_id(),'product');
                if($ae_wc_template != '' && is_numeric($ae_wc_template)){
                    $ae_wc_path =  AE_PRO_PATH.'includes/wc/ae-wc-single.php';
                    return $ae_wc_path;
                }
        }


        if($slug == 'content' && $name == 'product'){
            $ae_wc_template = $helper->get_woo_archive_template();

            if($ae_wc_template != '' && is_numeric($ae_wc_template)){
                if($helper->is_full_override($ae_wc_template)){
                    $ae_theme = new Ae_Theme();
                    $ae_theme->setOverride('full');

                    $ae_theme->setUseCanvas($helper->is_canvas_enabled($ae_wc_template));
                    $ae_wc_path = $ae_theme->load_archive_template($template);
                }else{
                    $ae_wc_path =  AE_PRO_PATH.'includes/wc/ae-wc-archive.php';
                }

                return $ae_wc_path;
            }
        }



        return $template;
    }

    public function elementor_widget_registered()
    {
        require_once AE_PRO_PATH . 'includes/elements/post-title.php';
        require_once AE_PRO_PATH . 'includes/elements/post-navigation.php';
        require_once AE_PRO_PATH . 'includes/elements/post-thumbnail.php';
        require_once AE_PRO_PATH . 'includes/elements/post-content.php';
        require_once AE_PRO_PATH . 'includes/elements/post-readmore.php';
        require_once AE_PRO_PATH . 'includes/elements/post-meta.php';
        if ( function_exists('yoast_breadcrumb') ) {
            require_once AE_PRO_PATH . 'includes/elements/post-breadcrumb.php';
        }
        require_once AE_PRO_PATH . 'includes/elements/searchform.php';

        require_once AE_PRO_PATH . 'includes/elements/post-custom-taxonomy.php';
        require_once AE_PRO_PATH . 'includes/elements/post-custom-field.php';
		require_once AE_PRO_PATH . 'includes/elements/post-blocks.php';
		require_once AE_PRO_PATH . 'includes/elements/author.php';
		require_once AE_PRO_PATH . 'includes/elements/post-comments.php';
		require_once AE_PRO_PATH . 'includes/elements/portfolio.php';
        require_once AE_PRO_PATH . 'includes/elements/Skins/taxonomy-blocks/skin-base.php';
        require_once AE_PRO_PATH . 'includes/elements/Skins/taxonomy-blocks/skin-card.php';
        require_once AE_PRO_PATH . 'includes/elements/Skins/taxonomy-blocks/skin-classic.php';
        require_once AE_PRO_PATH . 'includes/elements/Skins/taxonomy-blocks/skin-list.php';
        require_once AE_PRO_PATH . 'includes/elements/taxonomy-blocks.php';

        if(class_exists('acf')) {
            require_once AE_PRO_PATH . 'includes/elements/acf-repeater.php';
        }


        // Todo:: Load only when acfpro is active
        require_once AE_PRO_PATH . 'includes/elements/Skins/acf-gallery/skin-base.php';
        require_once AE_PRO_PATH . 'includes/elements/Skins/acf-gallery/skin-carousel.php';
        //require_once AE_PRO_PATH . 'includes/elements/Skins/acf-gallery/skin-slider.php';
        require_once AE_PRO_PATH . 'includes/elements/Skins/acf-gallery/grid-view.php';
        require_once AE_PRO_PATH . 'includes/elements/acf-gallery.php';
        require_once AE_PRO_PATH . 'includes/elements/cf-google-map.php';
        require_once AE_PRO_PATH . 'includes/elements/tax-custom-field.php';


        // ACF Widget
        if(class_exists('acf')) {
            require_once AE_PRO_PATH . 'includes/classes/acf-master.php';

            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-acf/skin-base.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-acf/skin-text.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-acf/skin-text-area.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-acf/skin-wysiwyg.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-acf/skin-number.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-acf/skin-url.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-acf/skin-select.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-acf/skin-checkbox.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-acf/skin-radio.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-acf/skin-button-group.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-acf/skin-true-false.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-acf/skin-file.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-acf/skin-email.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-acf/skin-image.php';

            require_once AE_PRO_PATH . 'includes/elements/ae-acf.php';
        }

        // Pods Widget
        if(is_plugin_active('pods/init.php')) {
            require_once AE_PRO_PATH . 'includes/classes/pods-master.php';

            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-pods/skin-base.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-pods/skin-text.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-pods/skin-text-area.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-pods/skin-wysiwyg.php';
            //require_once AE_PRO_PATH. 'includes/elements/Skins/ae-pods/skin-code.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-pods/skin-number.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-pods/skin-website.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-pods/skin-select.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-pods/skin-checkbox.php';
            //require_once AE_PRO_PATH. 'includes/elements/Skins/ae-pods/skin-radio.php';
            //require_once AE_PRO_PATH. 'includes/elements/Skins/ae-pods/skin-button-group.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-pods/skin-yes-no.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-pods/skin-file.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-pods/skin-file-gallery.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-pods/skin-file-image.php';
            require_once AE_PRO_PATH . 'includes/elements/Skins/ae-pods/skin-email.php';

            require_once AE_PRO_PATH . 'includes/elements/ae-pods.php';
        }

        //FacetWP Widgets
	    //if(class_exists('FacetWP')){
            //require_once AE_PRO_PATH. 'includes/elements/Skins/ae-facetwp/skin-base.php';
            //require_once AE_PRO_PATH. 'includes/elements/Skins/ae-facetwp/skin-checkbox.php';
            //require_once AE_PRO_PATH. 'includes/elements/Skins/ae-facetwp/skin-radio.php';
            //require_once AE_PRO_PATH. 'includes/elements/Skins/ae-facetwp/skin-dropdown.php';
            //require_once AE_PRO_PATH. 'includes/elements/Skins/ae-facetwp/skin-fSelect.php';
            //require_once AE_PRO_PATH. 'includes/elements/ae-facetwp.php';
        //}

        //WooCommerce Widgets
        if (class_exists('WooCommerce')) {
            require_once AE_PRO_PATH . 'includes/elements/woo-elements/woo-title.php';
            require_once AE_PRO_PATH . 'includes/elements/woo-elements/woo-price.php';
            require_once AE_PRO_PATH . 'includes/elements/woo-elements/woo-sku.php';
            require_once AE_PRO_PATH . 'includes/elements/woo-elements/woo-content.php';
            require_once AE_PRO_PATH . 'includes/elements/woo-elements/woo-readmore.php';
            require_once AE_PRO_PATH . 'includes/elements/woo-elements/woo-add-to-cart.php';
            require_once AE_PRO_PATH . 'includes/elements/woo-elements/woo-stock-status.php';
            require_once AE_PRO_PATH . 'includes/elements/woo-elements/woo-rating.php';
            require_once AE_PRO_PATH . 'includes/elements/woo-elements/woo-category.php';
            require_once AE_PRO_PATH . 'includes/elements/woo-elements/woo-tags.php';
            require_once AE_PRO_PATH . 'includes/elements/woo-elements/woo-product-image-gallery.php';
            require_once AE_PRO_PATH . 'includes/elements/woo-elements/woo-tabs.php';
            require_once AE_PRO_PATH . 'includes/elements/woo-elements/woo-notices.php';

            require_once AE_PRO_PATH . 'includes/elements/Skins/woo-products/skin-base.php';

            require_once AE_PRO_PATH . 'includes/elements/Skins/woo-products/skin-grid.php';

            require_once AE_PRO_PATH . 'includes/elements/woo-elements/woo-products.php';
        }


    }


    function ae_woo_setup() {
        global $post;
        global $product;

        if(!class_exists('woocommerce')){
            return false;
        }

        if(is_product()){
            $helper = new Helper();
            $ae_product_template = $helper->get_ae_active_post_template($post->ID,'product');

            if($ae_product_template){
                add_theme_support( 'wc-product-gallery-zoom' );
                add_theme_support( 'wc-product-gallery-lightbox' );
                add_theme_support( 'wc-product-gallery-slider' );
            }
        }
    }

    function editor_woo_scripts(){

        if(is_singular('ae_global_templates')){
            add_theme_support( 'wc-product-gallery-zoom' );
            add_theme_support( 'wc-product-gallery-lightbox' );
            add_theme_support( 'wc-product-gallery-slider' );
        }
    }

	public function block_template_frontend() {

		if ( is_singular( 'ae_global_templates' ) && ! current_user_can( 'edit_posts' ) ) {
			wp_redirect( site_url(), 301 );
			die;
		}
	}

	public function db_upgrade_script(){

    	//check if upgrade required
		$upgrade_required = get_option('aepro_27_upgrade_run');

		if($upgrade_required != 1){

			// check posts with meta key
			$args = array(
				'meta_key' => 'ae_enable_canvas',
				'meta_value' => 'true',
				'post_type' => 'ae_global_templates',
				'post_status' => 'any',
				'posts_per_page' => -1
			);
			$posts = get_posts($args);

			if(count($posts)){

				// set new meta key
				foreach($posts as $p){
					update_post_meta($p->ID, 'ae_elementor_template', 'ec');
				}
			}

			update_option('aepro_27_upgrade_run', '1');

		}
	}

	public function ae_pro_fail_load() {

		$plugin = 'elementor/elementor.php';

		if ( _is_elementor_installed() ) {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			$message = sprintf( __( '<b>AnyWhere Elementor Pro</b> is not working because you need to activate the <b>Elementor</b> plugin.', 'ae-pro' ), '<strong>', '</strong>' );
			$action_url   = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
			$button_label = __( 'Activate Elementor', 'ae-pro' );

		} else {
			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}
			$message = sprintf( __( '<b>AnyWhere Elementor Pro</b> is not working because you need to install the <b>Elementor</b> plugin.', 'ae-pro' ), '<strong>', '</strong>' );
			$action_url   = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
			$button_label = __( 'Install Elementor', 'ae-pro' );
		}

		$button = '<p><a href="' . $action_url . '" class="button-primary">' . $button_label . '</a></p><p></p>';

		printf( '<div class="%1$s"><p>%2$s</p>%3$s</div>', 'notice notice-error', $message, $button );
	}

	public function plugin_row_meta( $plugin_meta, $plugin_file){

		if ( AE_PRO_BASE === $plugin_file ) {
			$row_meta = [
				'docs' => '<a href="https://aedocs.webtechstreet.com/" aria-label="' . esc_attr( __( 'View Documentation', 'ae-pro' ) ) . '" target="_blank">' . __( 'Docs', 'ae-pro' ) . '</a>',
			];

			$plugin_meta = array_merge( $plugin_meta, $row_meta );
		}

		return $plugin_meta;

	}

}

Aepro::instance();
