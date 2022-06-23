<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor PhpRaw
 *
 * Elementor widget for Dinamic Content Elements
 *
 */

class DCE_Widget_RawPhp extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce-rawphp';
    }
    
    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('PHP Raw', 'dynamic-content-for-elementor');
    }
    public function get_description() {
        return __('If you think about it, applying PHP code directly from a widget would have no limits and you could build anything as a template. This widget is dedicated to developers who want the utmost control directly from Elementor', 'dynamic-content-for-elementor');
    }
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/php-raw/';
    }
    public function get_icon() {
        return 'icon-dyn-phprow';
    }

    protected function _register_controls() {
        
        $this->start_controls_section(
            'section_rawphp', [
                'label' => __('PHP Raw', 'dynamic-content-for-elementor'),
            ]
        );
        
        if( current_user_can('administrator') || !\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            
            /*$js = $this->printJs();
            $this->add_control(
                'html_js',
                [
                'type'    => Controls_Manager::RAW_HTML,
                'raw' => $js,
                'content_classes' => 'phpraw_js',
                ]
            );*/
            
            $this->add_control(
              'custom_php',
              [
                 'label'   => __( 'Custom PHP', 'dynamic-content-for-elementor' ),
                 'type'    => Controls_Manager::CODE,
                 'language' => 'php',
                 'description' => '<div style="display: none;" class="alert notice warning dce-notice-phpraw dce-notice dce-error dce-notice-error"><strong>ALERT</strong>: php code seem to be in error, please check it before save, or your page will be corrupted by fatal error!</div>',
              ]
            );
            
        } else {
                $this->add_control(
                  'html_avviso',
                  [
                     'type'    => Controls_Manager::RAW_HTML,
                     'raw' => __( '<div class="dce-notice dce-error dce-notice-error">You must be admin to set this widget.</div>', 'dynamic-content-for-elementor' ),
                     'content_classes' => 'avviso',
                  ]
                );
        }
        
        $this->end_controls_section();
        
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if( isset($settings['custom_php']) && $settings['custom_php'] != '' ){
            $evalError = false;
            try {
                $this->execPhp($settings['custom_php']);
                /*if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                    echo '<div id="end-phpraw-'.$this->get_id().'" class="hidden end-phpraw">END-PHPRAW-'.$this->get_id().'</div>';
                }*/
            } catch (ParseError $e) {
                $evalError = true;
            } catch (Exception $e) {
                $evalError = true;
            } catch (Error $e) {
                $evalError = true;
            } catch (Error $e) {
                $evalError = true;
            }
            
            if ($evalError) {
                if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                    echo '<strong>';
                    _e( 'Please check your PHP code', 'dynamic-content-for-elementor' );
                    echo '</strong><br>';
                    echo 'ERROR: ',  $e->getMessage(), "\n";
                }
            }
        }else{
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                _e( 'Add Custom PHP Code', 'dynamic-content-for-elementor' );
            }
        }
    }
    
    protected function execPhp($code) {
        @eval($code);
    }
    
    /*public function printJs() {
        ob_start();
        ?>
        <script>
            document.addEventListener("DOMContentLoaded", function(){
                setInterval(function(){ 
                    var iFrameDOM = jQuery("iframe#elementor-preview-iframe").contents();
                    if (iFrameDOM.find("div.elementor-widget-dce-rawphp").length) { 
                        if (iFrameDOM.find("div.elementor-widget-dce-rawphp.elementor-loading").length) { 
                            //&& iFrameDOM.find("div[data-id=<?php echo $this->get_id(); ?>]").hasClass('elementor-loading')) {
                            jQuery('#elementor-panel-saver-button-publish').addClass('elementor-saver-disabled');
                            jQuery('.dce-notice-phpraw').slideDown();
                            //console.log('errore');
                        } else {
                            jQuery('#elementor-panel-saver-button-publish').removeClass('elementor-saver-disabled');
                            jQuery('.dce-notice-phpraw').slideUp();
                        }
                    }
                    console.log('controllato <?php echo $this->get_id(); ?>');
                }, 1000);
            });
        </script>
        <?php
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }*/

}
