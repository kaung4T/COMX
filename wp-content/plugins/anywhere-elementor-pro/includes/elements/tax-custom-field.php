<?php

namespace Aepro;

use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Aepro_Tax_Custom_Field extends Aepro_Custom_Field{

    public function get_name() {

        return 'ae-tax-custom-field';
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_title() {

        return __('AE - Taxonomy Custom Field', 'ae-pro');
    }

    protected function _register_controls()
    {

        $helper = new Helper();
        parent::_register_controls();

	    $this->update_control(
		    'link_text_type',
		    [
			    'options' => [
				    'static' => __('Static','ae-pro'),
				    'custom_field' => __('Custom Field','ae-pro'),
			    ],
		    ]
	    );

	    $this->update_control(
		    'links_to',
		    [
			    'options' => [
				    ''  => __('None', 'ae-pro'),
				    'media' => __('Full Image', 'ae-pro'),
				    'static' => __( 'Static URL', 'ae-pro' ),
				    'custom_field' => __( 'Custom Field', 'ae-pro' ),
			    ],
		    ]
	    );
    }

    protected function render()
    {
        $settings = $this->get_settings();
        if(!isset($settings['cf_type']) || $settings['cf_type'] == ''){
            $settings['cf_type'] = 'text';
        }

        $helper = new Helper();

        if(!is_tax() && !is_category()){
            $preview_tax = $helper->get_preview_term_data();
            $tax_id = $preview_tax['prev_term_id'];
            $tax_data = get_term($tax_id);
            $tax_title = $tax_data->name;
        }else{
            $tax_data = get_queried_object();
            $tax_id = $tax_data->term_id;
            $tax_title = $tax_data->name;
        }


        $custom_field = $settings['custom-field'];

        if(class_exists('acf') && in_array($settings['cf_type'],['text','link','audio', 'date']) && $settings['acf_support'] == 'yes'){
            $custom_field_val = get_field($custom_field,$tax_data);
        }else{
            $custom_field_val = get_term_meta( $tax_id, $custom_field, true );
        }

                $this->add_render_attribute( 'cf-wrapper','class','cf-type-'.$settings['cf_type'] );
                $this->add_render_attribute( 'cf-wrapper','class','ae-cf-wrapper' );
                $this->add_render_attribute( 'custom-field-class', 'class', 'ae-element-custom-field' );
                $this->add_render_attribute( 'custom-field-label-class', 'class', 'ae-element-custom-field-label' );
                $this->add_render_attribute( 'post-cf-icon-class','class','icon-wrapper' );
                $this->add_render_attribute( 'post-cf-icon-class','class','ae-element-custom-field-icon' );
                $this->add_render_attribute( 'post-cf-icon','class',$settings['cf_icon'] );

                if(empty($custom_field_val)){
                    $this->add_render_attribute('cf-wrapper','class','hide');
                }

                if($settings['cf_link_target'] == 'yes'){
                    $this->add_render_attribute( 'custom-field-class', 'target', '_blank' );
                }

                $cf_type = $settings['cf_type'];
                $eid = $this->get_id();
                $custom_field_html = '';
              switch ($cf_type) {

                  case "html": 	if(!empty($custom_field_val)){
                      $custom_field_html = '<div '.$this->get_render_attribute_string( 'custom-field-class' ).'>'.wpautop(do_shortcode($custom_field_val)).'</div>';
                  }
                      break;

                  case "link":	if($settings['link_type'] == 'email'){
                      $custom_field_val = 'mailto:'.$custom_field_val;
                  }elseif($settings['link_type'] == 'tel'){
                      $custom_field_val = 'tel:'.$custom_field_val;
                  }

                      if(!empty($settings['cf_link_text']) && $settings['link_text_type'] == 'static'){
                          $custom_field_html = '<a '.$this->get_render_attribute_string( 'custom-field-class' ).'  href="'.$custom_field_val.'">'.$settings['cf_link_text'].'</a>';
                      }else if(!empty($settings['cf_link_dynamic_text']) && $settings['link_text_type'] == 'custom_field') {
                          $custom_field_html = '<a '.$this->get_render_attribute_string( 'custom-field-class' ).'  href="'.$custom_field_val.'">'.get_term_meta($tax_id,$settings['cf_link_dynamic_text'], true).'</a>';
                      }else{
                          if($settings['link_type'] != 'default'){
                              $custom_field_html = '<a '.$this->get_render_attribute_string( 'custom-field-class' ).' href="'.$custom_field_val.'">'. get_term_meta($tax_id,$custom_field,true) .'</a>';
                          }else{
                              $custom_field_html = '<a '.$this->get_render_attribute_string( 'custom-field-class' ).' href="'.$custom_field_val.'">'.$custom_field_val.'</a>';
                          }
                      }

                      if($settings['link_text_type'] == 'post'){
                          $custom_field_html = '<a '.$this->get_render_attribute_string( 'custom-field-class' ).' href="'. get_term_link(get_term($tax_id)) .'">'.$custom_field_val.'</a>';
                      }

                      break;

                  case "image":	$post_image_size = $settings['image_size'];

                      if($settings['links_to'] == 'post'){
                          $term_link = get_term_link(get_term($tax_id));
                      }elseif($settings['links_to'] == 'media'){
                          $media_link = wp_get_attachment_image_src($custom_field_val ,'full');
                          $term_link = $media_link[0];
                      }

                      if(is_numeric($custom_field_val)){
                          $custom_field_html =  '<div '.$this->get_render_attribute_string( 'custom-field-class' ).'>';
                          if($settings['links_to'] != '') {
                             $custom_field_html .= '<a href="' . $term_link . '" title="' . $tax_title . '">';
                          }
                          $custom_field_html .=  wp_get_attachment_image( $custom_field_val, $post_image_size );
                          if($settings['links_to'] != ''){
                              $custom_field_html .= '</a>';
                          }
                          $custom_field_html .= '</div>';
                      }else{
                          $custom_field_html =  '<div '.$this->get_render_attribute_string( 'custom-field-class' ).'>';
                          if($settings['links_to'] != '') {
                              $custom_field_html .= '<a href="' . $term_link . '" title="' . $tax_title . '">';
                          }
                          $custom_field_html .= '<img src="'.$custom_field_val.'" />';
                          if($settings['links_to'] != ''){
                              $custom_field_html .= '</a>';
                          }
                          $custom_field_html .= '</div>';
                      }

                      break;

                  case "video":  add_filter( 'oembed_result', [ $this, 'ae_filter_oembed_result' ], 50, 3 );
                      $custom_field_html = wp_oembed_get( $custom_field_val, wp_embed_defaults() );
                      $custom_field_html .= "<script type='text/javascript'>
                                                           jQuery(document).ready(function(){
                                                               jQuery(document).trigger('elementor/render/cf-video',['".$eid."','".$settings['aspect_ratio']."']);
                                                           });
                                                           jQuery(window).resize(function(){
                                                              jQuery(document).trigger('elementor/render/cf-video',['".$eid."','".$settings['aspect_ratio']."']);
                                                           });
                                                           jQuery(document).trigger('elementor/render/cf-video',['".$eid."','".$settings['aspect_ratio']."']);
                                                           </script>";
                      remove_filter( 'oembed_result', [ $this, 'ae_filter_oembed_result' ], 50 );
                      break;

                  case "audio":
                      $custom_field_html = wp_audio_shortcode([
                          'src' => $custom_field_val
                      ]);
                      break;

                  case "oembed": $custom_field_html = wp_oembed_get( $custom_field_val, wp_embed_defaults() );
                      break;

                  case "date"  : 	if($settings['acf_support'] == ''){
                      $format = "g:i A";
                      if($settings['date_format']=='custom') {
                          $format = $settings['date_custom_format'];
                      }elseif($settings['date_format'] == 'default'){
                          $format = get_option( 'date_format' );
                      }else{
                          $format = $settings['date_format'];
                      }
                      $custom_field_html = date( $format, strtotime( $custom_field_val ) );
                  }else{
                      $custom_field_html = $custom_field_val;
                  }
                      $custom_field_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $this->get_render_attribute_string( 'custom-field-class' ), do_shortcode($custom_field_html) );
                      break;

                  default:
                      $custom_field_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $this->get_render_attribute_string( 'custom-field-class' ), do_shortcode($custom_field_val) );
                      break;
              } ?>

              <div <?php echo $this->get_render_attribute_string('cf-wrapper');?>>
                  <?php if(($settings['cf_type']=='text') || ($settings['cf_type']=='link') || ($settings['cf_type']=='date')){ ?>

                      <?php if(!empty($settings['cf_icon']) && !empty($custom_field_val)){ ?>
                          <span <?php echo $this->get_render_attribute_string( 'post-cf-icon-class' ); ?>>
                          <i <?php echo $this->get_render_attribute_string( 'post-cf-icon' ); ?>></i>
                      </span>
                      <?php }

                      if(!empty($settings['cf_label']) && !empty($custom_field_val)){ ?>
                          <span <?php echo $this->get_render_attribute_string('custom-field-label-class');?>>
                          <?php echo $settings['cf_label'];?>
                      </span>
                      <?php }

                  }
                  echo $custom_field_html;?>
              </div>
              <?php
    }

    public function ae_filter_oembed_result($html){
        $settings = $this->get_settings();

        $params = [];

        if ( 'youtube' === $settings['cf_video_type'] ) {
            $youtube_options = [ 'autoplay', 'rel', 'controls', 'showinfo' ];

            foreach ( $youtube_options as $option ) {
                //if ( 'autoplay' === $option && $this->has_image_overlay() )
                //	continue;

                $value = ( 'yes' === $settings[ 'cf_yt_' . $option ] ) ? '1' : '0';
                $params[ $option ] = $value;
            }

            $params['wmode'] = 'opaque';
        }

        if ( 'vimeo' === $settings['cf_video_type'] ) {
            $vimeo_options = [ 'autoplay', 'loop', 'title', 'portrait', 'byline' ];

            foreach ( $vimeo_options as $option ) {
                //if ( 'autoplay' === $option && $this->has_image_overlay() )
                //	continue;

                $value = ( 'yes' === $settings[ 'vimeo_' . $option ] ) ? '1' : '0';
                $params[ $option ] = $value;
            }

            $params['color'] = str_replace( '#', '', $settings['vimeo_color'] );

        }

        if ( ! empty( $params ) ) {
            preg_match( '/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $html, $matches );
            $url = esc_url( add_query_arg( $params, $matches[1] ) );

            $html = str_replace( $matches[1], $url, $html );
        }

        return $html;
    }

}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Tax_Custom_Field() );