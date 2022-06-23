<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Tilt effect for Template
 *
 * Elementor widget for Elementor Dynamic COntent
 *
 */
class DCE_Widget_Tilt extends DCE_Widget_Prototype {
    public function get_name() {
        return 'dyncontel-tilt';
    }
    static public function is_enabled() {
        return true;
    }
    public function get_title() {
        return __('Tilt', 'dynamic-content-for-elementor');
    }
    public function get_description() {
        return __('Parallax hover tilt effect applicated to a template', 'dynamic-content-for-elementor');
    }
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/tilt/';
    }
    public function get_icon() {
        return 'icon-dyn-tilt';
    }
    public function get_script_depends() {
        return [ 'jquery', 'tilt-lib', 'dce-tilt'];
    }
    public function get_style_depends() {
        return [ 'dce-tilt' ];
    }
    protected function _register_controls() {
        $this->start_controls_section(
            'section_tilt', [
              'label' => __('Tilt', 'dynamic-content-for-elementor'),
            ]
        );
        /*$this->add_control(
            'template', [
              'label' => __('Select Template', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SELECT2,
              //'options' => get_post_taxonomies( $post->ID ),
              'options' => DCE_Helper::get_all_template(),
              'label_block' => true,
              'default' => '',
            ]
        );*/
        $this->add_control(
                'template',
                [
                    'label' => __('Select Template', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Template Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'object_type' => 'elementor_library',
                ]
        );
        /* $this->add_control(
          'translatez_template',
          [
          'label' => __( 'Translate Z', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::SLIDER,
          'default' => [
          'size' => 100,
          'unit' => 'px'
          ],
          'range' => [

          'min' => 0,
          'max' => 500,
          'step' => 10
          ],
          'frontend_available' => true,
          'selectors' => [
          '{{WRAPPER}} .template-inner' => 'transform: translateZ({{SIZE}}{{UNIT}});',//'height: {{SIZE}}{{UNIT}};',
          ],
          ]
          ); */
        $this->add_control(
            'translatez_template', [
              'label' => __('Translate Z', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::NUMBER,
              'default' => 0,
              'min' => 0,
              'max' => 200,
              'step' => 1,
              'frontend_available' => true,
              'selectors' => [
                  '{{WRAPPER}} .template-inner' => 'transform: translateZ({{VALUE}}px);', //'height: {{SIZE}}{{UNIT}};',
              ],
            ]
        );
        /* $this->add_responsive_control(
          'height',
          [
          'label' => __( 'Height', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SLIDER,
          'default' => [
          'size' => 400,
          'unit' => 'px',
          ],
          'tablet_default' => [
          'unit' => 'px',
          ],
          'mobile_default' => [
          'unit' => 'px',
          ],
          'size_units' => [ 'px','%','vw' ],
          'range' => [
          '%' => [
          'min' => 0,
          'max' => 100,
          ],
          'px' => [
          'min' => 0,
          'max' => 1000,
          ],
          'vw' => [
          'min' => 0,
          'max' => 100,
          ],

          ],
          'selectors' => [
          '{{WRAPPER}} .dce-threesixty' => 'padding-bottom: {{SIZE}}{{UNIT}};',//'height: {{SIZE}}{{UNIT}};',
          ],
          'frontend_available' => true,

          ]
          ); */
        /* $this->add_control(
          'tilt-maxTilt',
          [
          'label' => __( 'Max Tilt', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::NUMBER,
          'default' => 100,
          'min'     => 0,
          'max'     => 1000,
          'step'    => 10,
          ]
          ); */
        $this->add_control(
                'tilt_maxtilt', [
            'label' => __('Max Tilt', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 5,
            ],
            'range' => [

                'min' => 0,
                'max' => 10,
                'step' => 1
            ],
            'frontend_available' => true,
                ]
        );
        $this->add_control(
                'tilt_perspective', [
            'label' => __('Perspective', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 1000,
            'min' => 0,
            'max' => 2000,
            'step' => 10,
            'frontend_available' => true,
                ]
        );
        /* $this->add_control(
          'tilt_perspective',
          [
          'label' => __( 'Perspective', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::SLIDER,
          'default' => [
          'size' => 1000,
          ],
          'range' => [

          'min' => 100,
          'max' => 5000,
          'step' => 10
          ],
          'frontend_available' => true,
          ]
          ); */
        /* $this->add_control(
          'tilt_scale',
          [
          'label' => __( 'Scale', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::SLIDER,
          'default' => [
          'size' => 1,
          ],
          'range' => [

          'min' => 0,
          'max' => 1,
          'step' => 0.01
          ],
          'frontend_available' => true,
          ]
          ); */
        $this->add_control(
            'tilt_scale', ['label' => __('Scale', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::NUMBER,
              'default' => 1,
              'min' => 1,
              'max' => 2,
              'step' => 0.01
            ]
        );
        $this->add_control(
            'tilt_speed', [
              'label' => __('Speed', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::NUMBER,
              'default' => 300,
              'min' => 0,
              'max' => 1000,
              'step' => 10,
              'frontend_available' => true,
            ]
        );
        $this->add_control(
                'tilt_transition', [
            'label' => __('Transition', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
            'frontend_available' => true,
                ]
        );
        $this->add_control(
                'tilt_reset', [
            'label' => __('Reset', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
            'frontend_available' => true,
                ]
        );
        $this->add_control(
                'tilt_glare', [
            'label' => __('Glare', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
            'frontend_available' => true,
                ]
        );
        /* $this->add_control(
          'tilt_maxGlare',
          [
          'label' => __( 'Max Glare', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::SLIDER,
          'default' => [
          'size' => 1,
          ],
          'range' => [

          'min' => 0,
          'max' => 1,
          'step' => 0.1
          ],
          'frontend_available' => true,
          ]
          ); */
        $this->add_control(
                'tilt_maxGlare', [
            'label' => __('Max Glare', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'min' => 0,
            'max' => 1,
            'step' => 0.1
                ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        global $global_ID;
        $template = $settings['template'];
        echo '<div class="dce_tilt">';
        //echo '<div data-tilt class="js-tilt"></div>'; data-tilt-glare="true" data-tilt-perspective="1000" data-tilt-scale="1" data-tilt-speed="400" data-tilt-max="10"
        echo '	<div class="js-tilt">';
        //echo '		<div class="tilt-inner t1"></div>';
        if ($template != '') {

            echo '<div class="template-inner">' . do_shortcode('[dce-elementor-template id="' . $template . '"]') . '</div>';
        } else {
            echo '		<div class="tilt-inner"></div>';
        }

        //echo '		<div class="tilt-inner t3"></div>';
        echo '	</div>';
        echo '</div>';
    }

    protected function _content_template() {
        /*
          ?>
          <#
          var html = '';
          html += '<div class="dincontel_tilt">';
          html += '	<div class="js-tilt">';
          html += '		<div class="tilt-inner t1"></div>';
          html += '		<div class="tilt-inner t2"></div>';
          html += '		<div class="tilt-inner t3"></div>';
          html += '	</div>';
          html += '</div>';

          print( html );
          #>
          <?php
         */
    }

}
