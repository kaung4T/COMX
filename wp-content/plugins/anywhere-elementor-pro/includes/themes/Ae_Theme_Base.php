<?php
namespace Aepro;

abstract class Ae_Theme_Base{

    protected $_theme = '';

    protected $_use_canvas = false;

    protected $_use_hf = false;

    // override type full & partial
    protected $_override = '';

    /**
     * @param boolean $use_canvas
     */
    public function setUseCanvas($use_canvas)
    {
        $this->_use_canvas = $use_canvas;
    }

    public function setUseHeaderFooter($use_hf){
    	$this->_use_hf = $use_hf;
    }

    /**
     * @param string $override
     */
    public function setOverride($override)
    {
        $this->_override = $override;
    }

    /**
     * @param string $page_type
     */
    public function setPageType($page_type)
    {
        $this->_page_type = $page_type;
    }

    protected $_page_type = '';

    public function __construct(){

        global $ae_template;
        $this->_theme = $ae_template;
    }

    protected function get_single_template(){
        if($this->_use_canvas){
            return AE_PRO_PATH.'includes/themes/'.'canvas.php';
        }elseif($this->_use_hf){
        	return AE_PRO_PATH.'includes/themes/'.'header-footer.php';
	    }else {
            return AE_PRO_PATH . 'includes/themes/' . $this->_theme . '/single.php';
        }
    }

    protected function get_archive_template(){
        return AE_PRO_PATH.'includes/themes/'.$this->_theme.'/archive.php';
    }

    // only for backward compatibiity in GP and OWP
    protected function get_partial_archive_template(){
        return AE_PRO_PATH.'includes/themes/'.$this->_theme.'/archive-partial.php';
    }

    protected function get_404_template(){
        return AE_PRO_PATH.'includes/themes/'.$this->_theme.'/404.php';
    }

    protected function get_search_template(){
        return AE_PRO_PATH.'includes/themes/'.$this->_theme.'/search.php';
    }

    protected function manage_actions(){
        add_filter('template_include', [$this, 'load_archive_template'],99);
        //add_filter('single_template', [$this, 'load_single_template']);
    }

    public function load_archive_template($template){
        // Page Template
        if(is_page()){
            return $this->get_single_template();
        }

        if(is_singular('product')){
            return $this->get_single_template();
        }

        if(is_singular()){
            return $this->get_single_template();
        }

        // Search Template
        if(is_search()){
            if($this->_use_canvas){
                return AE_PRO_PATH.'includes/themes/'.'canvas-search.php';
            }elseif($this->_use_hf){
            	return AE_PRO_PATH.'includes/themes/'.'header-footer-search.php';
            }else{
                $this->set_fullwidth();
                return $this->get_search_template();
            }
        }

        // taxonomy, post type and blog archive
        if(is_archive() || $this->_page_type == 'blog'){
            if($this->_override == 'partial'){
                return $this->get_partial_archive_template();
            }else{
                if($this->_use_canvas){
                    return AE_PRO_PATH.'includes/themes/'.'canvas-archive.php';
                }elseif($this->_use_hf){
                	return AE_PRO_PATH.'includes/themes/'.'header-footer-archive.php';
                }else{
                    $this->set_fullwidth();
                    return $this->get_archive_template();
                }
            }
        }

        // 404 layout
        if(is_404()){
            if($this->_use_canvas){
                return AE_PRO_PATH.'includes/themes/'.'canvas-404.php';
            }elseif($this->_use_hf){
            	return AE_PRO_PATH.'includes/themes/'.'header-footer-404.php';
            }else{
                $this->set_fullwidth();
                return $this->get_404_template();
            }
        }
        return $template;
    }

    public function load_single_template($single_template){
       return $this->get_single_template();
    }

    protected function set_fullwidth(){
        // apply hooks and filter to remove sidebar and set fullwidth
    }

    protected function theme_hooks($hook_positions){
        return $hook_positions;
    }
}