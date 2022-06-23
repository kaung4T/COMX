<?php
namespace Aepro;

class Template{

	private static $_instance = null;

	private $_page_type = '';

	private $_template = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {

		add_action('template_redirect', [ $this, 'setup_data' ]);
	}

	public function setup_data(){

		// Set Page Type
		$this->setPageType();

		$this->setTemplate();

		if($this->hasTemplate()){
			add_action( 'wp_enqueue_scripts', function() {
				if ( ! class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
					return;
				}

				$template = Template::instance()->getTemplate();

				$css_file = new \Elementor\Core\Files\CSS\Post( $template['id']);
				$css_file->enqueue();
			}, 500 );
		}

	}

	private function setPageType(){

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

		if(is_home()){
			$this->_page_type = 'home';
		}
	}

	private function setTemplate(){

		$helper = new Helper();

		$template_id = null;

		switch($this->_page_type){

			case 'archive'  :   $template_id = $helper->get_ae_active_archive_template();
								break;

			case 'search'   :   $template_id = $helper->has_search_template();
								break;

			case 'single'   :   $template_id = $helper->get_ae_active_post_template( $GLOBALS['post']->ID, $GLOBALS['post']->post_type);
								break;

			case '404'      :   $template_id = $helper->has_404_template();
								break;
		}

		if(!is_null($template_id)){
			$this->initializeTemplate($template_id);
		}

	}

	/**
	 * @return string
	 */
	public function getPageType() {

		return $this->_page_type;
	}

	public function getTemplate(){
		if($this->hasTemplate()){
			return $this->_template;
		}else{
			return false;
		}

	}

	private function initializeTemplate($template_id){

		$this->_template['id'] = $template_id;

		// Set Canvas status
		$canvas = get_post_meta($template_id,'ae_enable_canvas');
		if($canvas){
			$this->_template['canvas'] = true;
		}else{
			$this->_template['canvas'] = false;
		}


		// set full override
		$full_override = get_post_meta($template_id,'ae_full_override',true);
		if($full_override){
			$this->_template['full_override'] = true;
		}else{
			$this->_template['full_override'] = false;
		}


	}

	/**
	 * Check if there is an activate layout for current page
	 * @return bool
	 */
	public function hasTemplate(){
		if(is_null($this->_template)){
			return false;
		}else{
			return true;
		}
	}



}

Template::instance();