<?php

namespace Aepro;

use Elementor;
use Elementor\Plugin;
use Elementor\Post_CSS_File;
use WP_Query;
use Elementor\Core\Settings\Manager as SettingsManager;
use Elementor\Core\Responsive\Responsive;

class Frontend{

    private static $_instance = null;

    private $_hook_templates = array();

    private $_page_type = null;

    private $_page_info = array();

    public static $_ae_post_block = 0;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {

        add_shortcode('INSERT_ELEMENTOR',[ $this, 'render_shortcode' ]);
        add_shortcode('AE_SEARCH_KEY', [ $this, 'ae_search_key' ]);
        add_shortcode('AE_SEARCH_COUNT', [ $this, 'ae_search_count' ]);
        add_action('init',[$this, 'init']);

        add_filter('aepro_single_data', [ $this, 'apply_ae_single_post_template'],10,1);
        add_action('aepro_archive_data', [ $this, 'apply_ae_archive_template'],10,1);
        add_action('aepro_404', [$this, 'apply_404_template']);
        add_action('ae_pro_search', [$this, 'apply_search_template']);

        // remove theme hooks and action for single page/post
        add_action('template_redirect', [ $this, 'ae_template_hook']);

        add_action('template_redirect', [ $this, 'set_page_type']);
        add_action('template_redirect', [ $this, 'bind_template_for_hooks']);

        add_filter('ae_template_filter', [ $this, 'term_based_post_template']);

        add_filter('acf/pre_load_post_id', [ $this, 'acf_post_block_loop']);
    }

    public function acf_post_block_loop($post_id){

        if(Frontend::$_ae_post_block != 0){
            $post_id = Frontend::$_ae_post_block;
        }

        return $post_id;
    }

    public function ae_search_key(){
        if(is_search()){
            return esc_html( get_search_query( false ) );
        }else{
            return 'Search Key';
        }
    }
    public function ae_search_count(){
        if(is_search()){
            global $wp_query;
            return $wp_query->found_posts;
        }else{
            return '0';
        }
    }
    public function set_page_type(){

        if ( is_front_page() && is_home() ) {
            // Default Home
            $this->_page_type = 'home';
        } elseif ( is_front_page() ) {
            // Static Home
            $this->_page_type = 'home';
        } elseif ( is_home() ) {
            //Blog Page
            $this->_page_type = 'blog';
        } else {

            if(is_singular()){
                $this->_page_type = 'single';
                return;
            }

            if(is_archive() || is_category() || is_tag()){
                $this->_page_type = 'archive';
                $this->_page_info = get_queried_object();
                return;
            }

            if(is_search()){
                $this->_page_type = 'search';
                return;
            }

            if(is_404()){
                $this->_page_type = '404';
                return;
            }

            if(is_author()){
                $this->_page_type = 'author';
            }
        }


    }
    public function init(){
        //$this->bind_template_for_hooks();
    }

    public function render_shortcode($atts){
        if(!isset($atts['id']) || empty($atts['id'])){
            return '';
        }

        $template_id = $atts['id'];

        return $this->render_insert_elementor($template_id);

    }

    public function run_elementor_builder($template_id){
        if(!isset($template_id) || empty($template_id)){
            return '';
        }
        $post_id = $template_id;
        ob_start();
        if(Plugin::$instance->db->is_built_with_elementor( $post_id )) {
            ?>
            <div class="ae_data elementor elementor-<?php echo $post_id; ?>" data-aetid="<?php echo $post_id; ?>">
                <?php echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id ); ?>
            </div>
            <?php
        }else{
            echo __('Not a valid elementor page','ae-pro');
        }
        $response = ob_get_contents();
        ob_end_clean();
        return $response;
    }

    public function render_insert_elementor($template_id,$with_css = false){
        if(!isset($template_id) || empty($template_id)){
            return '';
        }

        $post_id = $template_id;

        // check if page is elementor page

        $edit_mode = get_post_meta($post_id,'_elementor_edit_mode','');

	    $post_id = apply_filters( 'wpml_object_id', $post_id, 'ae_global_templates' );

        ob_start();
        if(Plugin::$instance->db->is_built_with_elementor( $post_id )) {
            ?>
            <div class="ae_data elementor elementor-<?php echo $post_id; ?>" data-aetid="<?php echo $post_id; ?>">
                <?php

                    echo Elementor\Plugin::instance()->frontend->get_builder_content( $post_id,$with_css );

                    add_action('wp_footer', function(){

                        if(is_archive()){
                            $elementor_frontend_config['settings'] = $this->get_init_settings();
                            wp_localize_script( 'elementor-frontend', 'elementorFrontendConfig', $elementor_frontend_config['settings'] );

                            //echo '<pre>'; print_r($elementor_frontend_config); die();

                        }
                    });

                ?>
            </div>
            <?php
        }else{
            echo __('Not a valid elementor page','ae-pro');
        }
        $response = ob_get_contents();
        ob_end_clean();
        return do_shortcode($response);
    }

    private function print_ae_data($sections){
        foreach ( $sections as $section_data ) {
            $section = new Elementor\Element_Section( $section_data );

            $section->print_element();
        }
    }

    public function bind_template_for_hooks(){
        $curr_post = $GLOBALS['post'];
        if(is_admin()){ return; }
        $hook_positions = Aepro::instance()->get_hook_positions();
        $hook_positions = apply_filters('ae_pro_filter_hook_positions',$hook_positions);

        $meta_query = array('relation' => 'OR');
        foreach($hook_positions as $key => $hook_position){
            if(empty($hook_position)){
                continue;
            }
            $meta_query[] = array(
                'key' => 'ae_usage',
                'value'   => $key,
                'compare' => '='
            );
        }

        $args = array(
            'post_type'  =>  'ae_global_templates',
            'posts_per_page'  => -1,
            'meta_key'  => 'ae_render_mode',
            'meta_value' => 'normal'
            //'meta_query' => $meta_query
        );
        $templates = new WP_Query($args);

        if($templates->found_posts){
            while($templates->have_posts()){

                $templates->the_post();
                $tid = get_the_ID();

                if(!$this->validate_hook($tid,$curr_post)){
                    continue;
                }

                $hook_position = get_post_meta($tid,'ae_usage',true);
                if($hook_position == 'custom'){
                    $hook_position = get_post_meta($tid,'ae_custom_usage_area',true);
                }
                if(!empty($hook_position)){
                    $this->_hook_templates[$hook_position] = $tid;

                    add_action($hook_position,function(){
                        $current_filter = current_filter();
                        echo $this->render_insert_elementor($this->_hook_templates[$current_filter]);
                    },10,1);
                }
            }
            wp_reset_postdata();
        }

    }

    /**
     * Checks whether hook is valid for these page as per
     * rules settings in AE Template
     * @param $tid
     * @param null $curr_post
     * @return bool
     */
    protected function validate_hook($tid,$curr_post = null){
        $ae_apply_global = get_post_meta($tid,'ae_apply_global',true);
        if($ae_apply_global === 'true'){
            // applied globally without any restriction
            return true;
        }

        $ae_hook_apply_on = get_post_meta($tid,'ae_hook_apply_on');

        switch($this->_page_type){
            case 'single'   :   // check if AE Template is allowed on current page type,
                                if(!isset($ae_hook_apply_on[0]) || !in_array($this->_page_type,$ae_hook_apply_on[0])){
                                    return false;
                                }

                                // check if post type allowed
                                $ae_hook_post_types = get_post_meta($tid,'ae_hook_post_types');

                                if(!isset($ae_hook_post_types[0]) || !in_array($curr_post->post_type,$ae_hook_post_types[0])){
                                    return false;
                                }

                                $ae_hook_posts_selected = get_post_meta($tid,'ae_hook_posts_selected',true);
                                $ae_hook_posts_excluded = get_post_meta($tid,'ae_hook_posts_excluded',true);

                                if(!empty($ae_hook_posts_selected)){
                                    $ae_hps = explode(',',$ae_hook_posts_selected);
                                    if(!in_array($curr_post->ID,$ae_hps)){
                                        return false;
                                    }
                                }elseif(!empty($ae_hook_posts_excluded)){
                                    $ae_hpe = explode(',',$ae_hook_posts_excluded);
                                    if(in_array($curr_post->ID,$ae_hpe)){
                                        return false;
                                    }
                                }
                                break;

            case 'archive'  :   // check if AE Template is allowed on current page type,
                                if(!isset($ae_hook_apply_on[0]) || !in_array($this->_page_type,$ae_hook_apply_on[0])){
                                    return false;
                                }

                                // check if taxonomy is allowed
                                $ae_hook_taxonomies = get_post_meta($tid,'ae_hook_taxonomies');
                                if(!isset($ae_hook_taxonomies[0]) || !in_array($this->_page_info->taxonomy,$ae_hook_taxonomies[0])){
                                    return false;
                                }

                                $ae_hook_terms_selected = get_post_meta($tid,'ae_hook_terms_selected',true);
                                $ae_hook_terms_excluded = get_post_meta($tid,'ae_hook_terms_excluded',true);

                                if(!empty($ae_hook_terms_selected)){
                                    $ae_hts = explode(',',$ae_hook_terms_selected);
                                    if(!in_array($this->_page_info->term_id,$ae_hts)){
                                        return false;
                                    }
                                }
                                if(!empty($ae_hook_terms_excluded)){
                                    $ae_hte = explode(',',$ae_hook_terms_excluded);
                                    if(in_array($this->_page_info->term_id,$ae_hte)){
                                        return false;
                                    }
                                }
                                break;

            default       :     if(!isset($ae_hook_apply_on[0]) || !in_array($this->_page_type,$ae_hook_apply_on[0])){
                                    return false;
                                }
        }


        return true;
    }


    public function apply_ae_single_post_template($content){
        global $post;
        if ( post_password_required( $post->ID ) ) {
            echo get_the_password_form( $post->ID );
            return $content;
        }
        $helper = new Helper();
        if(!is_single() && !is_page()){
            return $content;
        }
        $post_id = $GLOBALS['post']->ID;

        if(class_exists('WooCommerce') && $GLOBALS['post']->post_type == 'product'){
            $content = '<div class="product">';
            $content .= $this->apply_ae_wc_single_template();
            $content .= '</div>';
            echo $content;
            return $content;
        }

        // check ae_post_template
        $ae_post_template = $helper->get_ae_active_post_template($post_id,$GLOBALS['post']->post_type);
        if(isset($ae_post_template) && is_numeric($ae_post_template)){
            $template_content = $this->render_insert_elementor($ae_post_template);
            echo $template_content;
        }

        return $content;
    }

    public function apply_ae_archive_template($content){
        $helper = new Helper();

        $ae_archive_template = $helper->get_ae_active_archive_template();

        if($ae_archive_template){
            $template_content = $this->render_insert_elementor($ae_archive_template);
            echo $template_content;
        }

        return $content;
    }

    /**
     * Remove hooks for post single page
     */
    public function ae_template_hook(){
        $helper = new Helper();
        $is_blog = $helper->is_blog();

        if(is_single() || is_page()){
            $post = get_post();
            $tid_post = $helper->get_ae_active_post_template($post->ID,$post->post_type);
            $post_is_canvas_enabled = $helper->is_canvas_enabled($tid_post);
            $is_hf_enabled = $helper->is_heder_footer_enabled($tid_post);

            if(!$tid_post){
                return false;
            }

            if(class_exists('WooCommerce') && $post->post_type == 'product'){
                remove_action( 'woocommerce_before_main_content','hestia_woocommerce_before_main_content');
                add_filter('body_class',function($classes){
                    $classes[] = 'aep-product';
                    return $classes;
                });
                //return false;
            }

            // Todo:: Move remove actions to separate file. Run only after theme detect.
            if(class_exists('Aepro\Ae_Theme')){
                $theme_obj = new Ae_Theme();

                if($post_is_canvas_enabled){
                    $theme_obj->setUseCanvas(true);
                }

                if($is_hf_enabled){
                    $theme_obj->setUseHeaderFooter(true);
                }

                $theme_obj->manage_actions();
            }

            // handle canvas template
            add_filter('template_include', [$this, 'handle_canvas_template']);



        }elseif(is_search()){
            $tid_search = $helper->has_search_template();
            if($tid_search){
                if(class_exists('Aepro\Ae_Theme')){
                    $theme_obj = new Ae_Theme();
                    if($helper->is_canvas_enabled($tid_search)){
                        $theme_obj->setUseCanvas(true);
                    }

                    if($helper->is_heder_footer_enabled($tid_search)){
                        $theme_obj->setUseHeaderFooter(true);
                    }

                    $theme_obj->manage_actions();
                }
            }
        }elseif(is_archive()){
            $tid = $helper->get_ae_active_archive_template();
            if(!$tid && !$is_blog){
                return false;
            }

            if(class_exists('Aepro\Ae_Theme')){
                $theme_obj = new Ae_Theme();
                // check if canvas enabled
                if($helper->is_full_override($tid)){
                    $theme_obj->setOverride('full');
                }
                if($helper->is_canvas_enabled($tid)){
                    $theme_obj->setUseCanvas(true);
                }
                if($helper->is_heder_footer_enabled($tid)){
                    $theme_obj->setUseHeaderFooter(true);
                }

                $theme_obj->manage_actions();
            }
        }elseif($is_blog){
            $template_id = $helper->get_ae_active_archive_template();
            if(!$template_id){
                return false;
            }
            // is blog - force load archive template
            if(class_exists('Aepro\Ae_Theme')){
                // get override mode

                $theme_obj = new Ae_Theme();
                $theme_obj->setPageType('blog');


                if($helper->is_full_override($template_id)){
                    $theme_obj->setOverride('full');

                    if($helper->is_canvas_enabled($template_id)){
                        $theme_obj->setUseCanvas(true);
                    }

                    if($helper->is_heder_footer_enabled($template_id)){
                        $theme_obj->setUseHeaderFooter(true);
                    }
                }
                $theme_obj->manage_actions();
            }
        }elseif(is_404()){
            $tid_404 = $helper->has_404_template();
            if($tid_404){
                if(class_exists('Aepro\Ae_Theme')){
                    $theme_obj = new Ae_Theme();
                    if($helper->is_canvas_enabled($tid_404)){
                        $theme_obj->setUseCanvas(true);
                    }

                    if($helper->is_heder_footer_enabled($tid_404)){
                        $theme_obj->setUseHeaderFooter(true);
                    }
                    $theme_obj->manage_actions();
                }
            }
        }

        // load_template i
        do_action('ae_remove_theme_single_page_actions');
        return true;
    }

    function handle_canvas_template($template_include){
        if(is_single() && strpos($template_include,'canvas.php')){
            $template_include = AE_PRO_PATH . 'includes/themes/canvas.php';
        }
        return $template_include;
    }

    public function apply_ae_wc_single_template(){
        global $product;
        $helper = new Helper();
        $ae_product_template = $helper->get_ae_active_post_template($product->get_id(),'product');

        if($ae_product_template != '' && is_numeric($ae_product_template)){
            $template_content = $this->render_insert_elementor($ae_product_template);
            $wc_sd = new \WC_Structured_Data();
            $wc_sd->generate_product_data();
            return $template_content;
        }
    }

    public function apply_ae_wc_archive_template(){
        $helper = new Helper();
        $ae_product_template = $helper->get_woo_archive_template();

        if($ae_product_template != '' && is_numeric($ae_product_template)){
            $template_content = $this->render_insert_elementor($ae_product_template);
            echo $template_content;
        }
    }

    function apply_404_template(){
        $helper = new Helper();
        $tid = $helper->has_404_template();
        if($tid){
            echo $this->render_insert_elementor($tid);
        }
    }

    function apply_search_template(){
        $helper = new Helper();
        $tid = $helper->has_search_template();
        if($tid){
            echo $this->render_insert_elementor($tid);
        }
    }

    function term_based_post_template($template_id){

        global $post;

        if(is_singular()) { // to make sure this code runs only for single posts

            //$post_template = get_metadata(get_post_type(), $post->ID,'ae_post_template', true);

            $post_template = get_post_meta($post->ID, 'ae_post_template', true);

            if($post_template == 'none'){
                return false;
            }

            if(is_numeric($post_template)){
                return $template_id;
            }

            $tax_ob = get_object_taxonomies($post);

            if(!count($tax_ob)){
                return $template_id;
            }

            $args = array(
                'order'                  => 'ASC',
                'hide_empty'             => false,
                'fields' => 'ids',
                'meta_query' => array(
                    array(
                        'key'       => 'ae_term_post_template',
                        'compare'   => '!=',
                        'value'     => ''
                    )
                )
            );

            $tax_list = array();
            foreach ($tax_ob as $t_ob) {
                $terms = wp_get_post_terms($post->ID, $t_ob, $args);
                if(count($terms)) {
                    foreach ($terms as $term) {
                        $term_post_template = get_term_meta( $term,'ae_term_post_template', true);
                        if(is_numeric($term_post_template)){
                            $tax_list[] = $term_post_template;
                        }
                        if($term_post_template == 'none') {
                            return false;
                        }
                    }
                }
            }
            if(!count($tax_list)){
                return $template_id;
            }
            rsort($tax_list);
            $template_id_arr = apply_filters('ae_term_post_template', $tax_list);
            $template_id = $template_id_arr[0];

        }
        return $template_id;
    }


	/**
	 * @return array
     *
     * Copied for Elementor Frontend as that was protected
	 */

	protected function get_init_settings() {
		$is_preview_mode = Plugin::$instance->preview->is_preview_mode( Plugin::$instance->preview->get_post_id() );

		$settings = [
			'environmentMode' => [
				'edit' => $is_preview_mode,
				'wpPreview' => is_preview(),
			],
			'is_rtl' => is_rtl(),
			'breakpoints' => Responsive::get_breakpoints(),
			'version' => ELEMENTOR_VERSION,
			'urls' => [
				'assets' => ELEMENTOR_ASSETS_URL,
			],
		];

		$settings['settings'] = SettingsManager::get_settings_frontend_config();

		if ( is_singular() ) {
			$post = get_post();
			$settings['post'] = [
				'id' => $post->ID,
				'title' => $post->post_title,
				'excerpt' => $post->post_excerpt,
			];
		} else {
			$settings['post'] = [
				'id' => 0,
				'title' => wp_get_document_title(),
				'excerpt' => '',
			];
		}

		if ( $is_preview_mode ) {
			$elements_manager = Plugin::$instance->elements_manager;

			$elements_frontend_keys = [
				'section' => $elements_manager->get_element_types( 'section' )->get_frontend_settings_keys(),
				'column' => $elements_manager->get_element_types( 'column' )->get_frontend_settings_keys(),
			];

			$elements_frontend_keys += Plugin::$instance->widgets_manager->get_widgets_frontend_settings_keys();

			$settings['elements'] = [
				'data' => (object) [],
				'editSettings' => (object) [],
				'keys' => $elements_frontend_keys,
			];
		}

		if ( is_user_logged_in() ) {
			$user = wp_get_current_user();

			if ( ! empty( $user->roles ) ) {
				$settings['user'] = [
					'roles' => $user->roles,
				];
			}
		}

		return $settings;
	}

}

Frontend::instance();