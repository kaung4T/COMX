<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;

use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Utils;


use DynamicContentForElementor\DCE_Helper;
use DynamicContentForElementor\Group_Control_Outline;
use DynamicContentForElementor\Controls\DCE_Group_Control_Filters_CSS;
use DynamicContentForElementor\Controls\DCE_Group_Control_Transform_Element;
//use DynamicContentForElementor\Group_Control_AnimationElement;

if (!defined('ABSPATH')) {
    exit;
}
class DCE_Widget_Woo_ProductImage extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce-woocommerce-productImage';
    }
    
    static public function is_enabled() {
        return false;
    }
    
    public function get_title() {
        return __('Product Image', 'dynamic-content-for-elementor');
    }
    
    public function get_icon() {
        return 'icon-dyn-woo_image';
    }
    
    static public function get_position() {
        return 2;
    }
    public function get_script_depends() {
        return [ 'wc-single-product','woocommerce','zoom','photoswipe','photoswipe-ui-default' ];
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
        $dce_data = DCE_Helper::dce_dynamic_data();
        // ------------------------------------------     
        global $product;
        if ( empty( $product ) ) {
            return;
        }
        wc_get_template( 'single-product/product-image.php' );
        // On render widget from Editor - trigger the init manually.
        if ( Utils::is_ajax() ) {
            ?>
            <script>
                jQuery( '.woocommerce-product-gallery' ).each( function() {
                    jQuery( this ).wc_product_gallery();
                } );
            </script>
            <?php
        }
        
        
    }

    protected function _content_template() {
        
    }
    protected function crea_wooproductimage($product) {
       if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
            return;
        }


        $columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
        $post_thumbnail_id = $product->get_image_id();
        $wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
            'woocommerce-product-gallery',
            'woocommerce-product-gallery--' . ( has_post_thumbnail() ? 'with-images' : 'without-images' ),
            'woocommerce-product-gallery--columns-' . absint( $columns ),
            'images',
        ) );
        ?>
        <div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
            <figure class="woocommerce-product-gallery__wrapper">
                <?php
                if ( has_post_thumbnail() ) {
                    $html  = wc_get_gallery_image_html( $post_thumbnail_id, true );
                } else {
                    $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
                    $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
                    $html .= '</div>';
                }

                echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );
                // -----------------------------------
                /*$attachment_ids = $product->get_gallery_image_ids();

                if ( $attachment_ids && has_post_thumbnail() ) {
                    foreach ( $attachment_ids as $attachment_id ) {
                        echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $attachment_id  ), $attachment_id );
                    }
                }*/

                //echo 'TTTTTTTTT '.$html;
                /*$flexslider        = (bool) apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) );
                $gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
                $thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
                $image_size        = apply_filters( 'woocommerce_gallery_image_size', $flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size );
                $full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
                $thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
                $full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
                $image             = wp_get_attachment_image( $attachment_id, $image_size, false, array(
                    'title'                   => get_post_field( 'post_title', $attachment_id ),
                    'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
                    'data-src'                => $full_src[0],
                    'data-large_image'        => $full_src[0],
                    'data-large_image_width'  => $full_src[1],
                    'data-large_image_height' => $full_src[2],
                    'class'                   => $main_image ? 'wp-post-image' : '',
                ) );

                return '<div data-thumb="' . esc_url( $thumbnail_src[0] ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_src[0] ) . '">' . $image . '</a></div>';*/

                // -----------------------------------
                do_action( 'woocommerce_product_thumbnails' );
                ?>
            </figure>
        </div>
        <?php
    }
}
