<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Utils;
use DynamicContentForElementor\DCE_Helper;
use DynamicContentForElementor\Controls\DCE_Group_Control_Transform_Element;
//use DynamicContentForElementor\Group_Control_AnimationElement;

if (!defined('ABSPATH')) {
    exit;
}
class DCE_Widget_Woo_Search extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce-woocommerce-search';
    }
    
    static public function is_enabled() {
        return false;
    }
    
    public function get_title() {
        return __('Search Commerce', 'dynamic-content-for-elementor');
    }
    
    public function get_icon() {
        return 'icon-dyn-woo_searchshop todo';
    }
    
    static public function get_position() {
        return 8;
    }
    public function get_plugin_depends() {
        return array('woocommerce' => 'woocommerce');
    }
    protected function _register_controls() {
        $this->start_controls_section(
            'section_content', [
                'label' => __('Settings', 'dynamic-content-for-elementor'),
            ]
        );
        
        $this->end_controls_section();

        
    }

    protected function render() {
        $settings = $this->get_active_settings();
        if ( empty( $settings ) )
           return;
        //
        // ------------------------------------------
        $demoPage = get_post_meta(get_the_ID(), 'demo_id', true);
        //
        $id_page = ''; //get_the_ID();
        $type_page = '';

        global $global_ID;
        global $global_TYPE;
        global $in_the_loop;
        global $global_is;
        //
        if(!empty($demoPage)){
        //echo 'DEMO';
          $id_page = $demoPage;
          $type_page = get_post_type($demoPage);
          $product = wc_get_product( $demoPage );
          //echo 'DEMO ...';
        } 
        else if (!empty($global_ID)) {
          //echo 'GLOBAL';
          $id_page = $global_ID;
          $type_page = get_post_type($id_page);
          global $product;
          //echo 'global ...';
        }else {
          //echo 'Select DEMO product for show the value.';
          $id_page = get_the_id();
          $type_page = get_post_type();
          global $product;
        }

        if ( empty( $product ) )
           return;
        // ------------------------------------------
        if(!empty($demoPage)){
            $args = array(
                'post_type'         => 'product',
                'post_status'       => 'publish',
                'post__in'          =>  array($id_page)
            );
            $loop = new \WP_Query( $args );
            $stock_count = array();
            while ( $loop->have_posts() ) : $loop->the_post();

                global $product; 

                $this->crea_woocsearch($product);
             
            endwhile; 
        }else{
            $this->crea_woocsearch($product);
        }
        
    }

    protected function _content_template() {
        
    }
    protected function crea_woocsearch($product) {
        ?>
        <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <label class="screen-reader-text" for="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>"><?php esc_html_e( 'Search for:', 'woocommerce' ); ?></label>
        <input type="search" id="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>" class="search-field" placeholder="<?php echo esc_attr__( 'Search products&hellip;', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        <button type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'woocommerce' ); ?>"><?php echo esc_html_x( 'Search', 'submit button', 'woocommerce' ); ?></button>
        <input type="hidden" name="post_type" value="product" />
    </form>
    <?php
    }
}
