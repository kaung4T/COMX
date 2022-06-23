<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;

use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Panorama A-Frame
 *
 * Elementor widget for Elementor Dynamic Content
 *
 */

class DCE_Widget_Panorama extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-panorama';
    }
    static public function is_enabled() {
        return true;
    }
    public function get_title() {
        return __('Panorama', 'dynamic-content-for-elementor');
    }
    public function get_description() {
      return __('Display a spherical picture in 360 grades through VR mode', 'dynamic-content-for-elementor');
    }
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/panorama/';
    }
    public function get_icon() {
        return 'icon-dyn-panorama';
    }
    public function get_script_depends() {
        return [ 'dce-aframe'];
    }
    static public function get_position() {
        return 3;
    }
    /*public function get_style_depends() {
        return [ 'dce-panorama' ];
    }*/
    protected function _register_controls() {
        $this->start_controls_section(
            'section_panorama', [
              'label' => __('Panorama', 'dynamic-content-for-elementor'),
            ]
        );
         $this->add_control(
            'image_source', [
                  'label' => __('Source image', 'dynamic-content-for-elementor'),
                  'type' => Controls_Manager::SELECT,
                  'frontend_available' => true,
                  'options' => [
                      'from_media' => __('From media library', 'dynamic-content-for-elementor'),
                      'custom_url' => __('Custom URL', 'dynamic-content-for-elementor'),
                  ],
                  'default' => 'from_media',
                  
            ]
        );
        $this->add_control(
          'custom_url_panorama_image', [
            'label' => __('Custom URL', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => __('https://www...', 'dynamic-content-for-elementor'),
            'label_block' => true,
            'dynamic' => [
                'active' => true,
            ],
            'condition' => [
                
                'image_source' => 'custom_url'
            ]
          ]
        );
        $this->add_control(
          'panorama_image',
          [
             'label' => __( 'Panorama Image', 'dynamic-content-for-elementor' ),
             'type' => Controls_Manager::MEDIA,
             'dynamic' => [
                    'active' => true,
             ],
             'default' => [
                'url' => DCE_Helper::get_placeholder_image_src(),
             ],
             'condition' => [
                      'image_source' => 'from_media'
                    ],
          ]
        );
        $this->add_responsive_control(
            'height_scene',
            [
                'label' => __( 'Scene height', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'vh' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 550
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} a-scene' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'separator'   => 'after'
            ]
        );
         $this->add_control(
            'params_heading',
            [
                'label' => __( 'Parameters', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
         $this->add_control(
            'fullscreen_vr',
            [
                'label' => __( 'Fullscreen', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );
        $this->add_control(
          'vr_mode_ui',
          [
              'label' => __( 'VR mode UI', 'dynamic-content-for-elementor' ),
              'type' => Controls_Manager::SWITCHER,
          ]
        );
        $this->add_control(
          'keyboard_shortcuts',
          [
              'label' => __( 'Keyboard Shortcuts', 'dynamic-content-for-elementor' ),
              'type' => Controls_Manager::SWITCHER,
              'description' => __('Enables the shortcut to press "F" to enter VR.','dynamic-content-for-elementor'),
          ]
        );
        $this->add_control(
          'reversemousecontrol',
          [
              'label' => __( 'Reverse mouse control', 'dynamic-content-for-elementor' ),
              'type' => Controls_Manager::SWITCHER,
          ]
        );
        $this->end_controls_section();
    }

    protected function render() {
      $settings = $this->get_settings_for_display();
      if ( empty( $settings ) )
        return;
      $fullScreen = '';
      $keyboard = '';
      $vrmodeui = '';
      $reversemousecontrol = '';
      if(!$settings['fullscreen_vr']){
        $fullScreen = ' embedded';
      }
      if(!$settings['vr_mode_ui']){
        $vrmodeui = ' vr-mode-ui="enabled: false"';
      }
      if(!$settings['keyboard_shortcuts']){
        $keyboard = ' keyboard-shortcuts="enterVR: false"';
      }
      if($settings['reversemousecontrol']){
        $reversemousecontrol = '<a-camera mouse-cursor reverse-mouse-drag="true" id="cam" zoom="1.3"></a-camera>'; 
        //'<a-entity camera look-controls="reverseMouseDrag: true"></a-entity>';
      }
      // fog="type: exponential; color: #AAA"
      $url_image = $settings['panorama_image']['url'];
      if( $settings['image_source'] == 'custom_url' && $settings['custom_url_panorama_image'] != '' ){
        $url_image = $settings['custom_url_panorama_image'];
      }
      ?>
      <a-scene <?php echo $fullScreen.$keyboard.$vrmodeui; ?>>
        <?php echo $reversemousecontrol; ?>
        <a-sky src="<?php echo $url_image; ?>" rotation="0 -130 0"></a-sky>
      </a-scene>
       <?php
    }

    protected function _content_template_() {
      ?>
      <#
      var url_image = settings.panorama_image.url;
      if(settings.image_source == 'custom_url' && settings.custom_url_panorama_image != ''){
        url_image = settings.custom_url_panorama_image;
      }
      #>
      <a-scene embedded vr-mode-ui="enabled: false" keyboard-shortcuts="enterVR: false">
        <a-sky src="{{url_image}}" rotation="0 -130 0"></a-sky>
      </a-scene>
      <?php
        
    }

}
