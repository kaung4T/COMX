<?php

namespace DynamicContentForElementor\Includes\Settings;

use Elementor\Controls_Manager;
use Elementor\Core\Settings\General\Model as GeneralModel;
use Elementor\Scheme_Color;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Model extends GeneralModel {

    public function get_name() {
        return 'dce-settings_dce';
    }

    public function get_css_wrapper_selector() {
        return '';
    }

    public function get_panel_page_settings() {
        return [
            'title' => __('Dynamic Content', 'dynamic-content-for-elementor'),
            'menu' => [
                'icon' => 'icon-dyn-logo-dce',
                'beforeItem' => 'elementor-settings',
            ],
        ];
    }

    public static function get_controls_list() {

        return [
            Manager::PANEL_TAB_SETTINGS => [
                /* 'settings_barbajs' => [
                  'label' => __( 'Barba', 'dynamic-content-for-elementor' ),
                  'controls' => [
                  'dce_barba_note' => [
                  'type' 				=> Controls_Manager::RAW_HTML,
                  'raw' 				=> __( 'Barba.js.', 'dynamic-content-for-elementor' ),
                  'content_classes' 	=> '',
                  ],
                  'enable_barbajs' => [
                  'label' => __('Enable Barbajs', 'dynamic-content-for-elementor'),
                  'type' => Controls_Manager::SWITCHER,
                  'label_off' => __('Yes', 'dynamic-content-for-elementor'),
                  'label_on' => __('No', 'dynamic-content-for-elementor'),
                  'return_value' => 'yes',
                  'default' => '',
                  'frontend_available' => true
                  ],
                  'barbajs_duration' => [
                  'label' 		=> __( 'Duration', 'dynamic-content-for-elementor' ),
                  'type' 			=> Controls_Manager::SLIDER,
                  'default' 	=> [
                  'size' 	=> 0.2,
                  ],
                  'range' 	=> [
                  'px' 	=> [
                  'min' 	=> 0,
                  'max' 	=> 2,
                  'step'	=> 0.1,
                  ],
                  ],
                  'condition' => [
                  'enable_barbajs' => 'yes'
                  ],
                  'frontend_available' => true
                  ],

                  ]
                  ], */
                // SWUP
                'settings_animsition' => [
                    'label' => __('Smooth navigation', 'dynamic-content-for-elementor'),
                    'controls' => [
                        'dce_swup_note' => [
                            'type' => Controls_Manager::RAW_HTML,
                            'raw' => __('<div><i class="icon-dyn-logo-dce" style="font-size: 8em;text-align: center;display: block;"></i></div>', 'dynamic-content-for-elementor'),
                            'content_classes' => '',
                        ],
                        'id_wrapper' => [
                            'label' => __('Wrapper ID', 'dynamic-content-for-elementor'),
                            'type' => Controls_Manager::TEXT,
                            'default' => '',
                            'placeholder' => 'Write ID...',
                            'frontend_available' => true,
                            'separator' => 'before'
                        ],
                        /* 'header_site' => [
                          'label' => __('Header ID', 'dynamic-content-for-elementor'),
                          'type' => Controls_Manager::TEXT,
                          'default' => '',
                          'placeholder' => 'Write ID of header...',
                          'frontend_available' => true
                          ],
                          'main_site' => [
                          'label' => __('Main ID', 'dynamic-content-for-elementor'),
                          'type' => Controls_Manager::TEXT,
                          'default' => '',
                          'placeholder' => 'Write ID of main...',
                          'frontend_available' => true
                          ],
                          'footer_site' => [
                          'label' => __('Footer ID', 'dynamic-content-for-elementor'),
                          'type' => Controls_Manager::TEXT,
                          'default' => '',
                          'placeholder' => 'Write ID of footer...',
                          'frontend_available' => true
                          ], */
                        'enable_swup' => [
                            'label' => __('Enable Swup', 'dynamic-content-for-elementor'),
                            'type' => Controls_Manager::SWITCHER,
                            'label_off' => __('No', 'dynamic-content-for-elementor'),
                            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                            'return_value' => 'yes',
                            'default' => '',
                            'frontend_available' => true
                        ],
                        'a_class' => [
                            'label' => __('A class CLASS', 'dynamic-content-for-elementor'),
                            'type' => Controls_Manager::TEXT,
                            'label_block' => true,
                            'row' => 3,
                            'default' => 'a:not([target="_blank"]):not([href^="#"]):not([href^="mailto"]):not([href^="tel"]):not(.gallery-lightbox):not(.elementor-clickable):not(.oceanwp-lightbox)',
                            'placeholder' => 'a:not([target="_blank"]):not([href^="#"]):not([href^="mailto"]):not([href^="tel"]):not(.gallery-lightbox):not(.elementor-clickable):not(.oceanwp-lightbox)',
                            'frontend_available' => true,
                            'separator' => 'after',
                            'condition' => [
                                'enable_swup' => 'yes'
                            ],
                        ],
                        'swup_duration' => [
                            'label' => __('Duration', 'dynamic-content-for-elementor'),
                            'type' => Controls_Manager::SLIDER,
                            'default' => [
                                'size' => 0.2,
                            ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 2,
                                    'step' => 0.1,
                                ],
                            ],
                            'condition' => [
                                'enable_swup' => 'yes'
                            ],
                            'frontend_available' => true
                        ],
                    ]
                ]
            // ANIMSITION
            /* 'settings_animsition' => [
              'label' => __( 'Animsition', 'dynamic-content-for-elementor' ),
              'controls' => [
              'dce_animsition_note' => [
              'type' 				=> Controls_Manager::RAW_HTML,
              'raw' 				=> __( 'Animsition.js.', 'dynamic-content-for-elementor' ),
              'content_classes' 	=> '',
              ],
              'enable_animsition' => [
              'label' => __('Enable Animsition', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SWITCHER,
              'label_off' => __('Yes', 'dynamic-content-for-elementor'),
              'label_on' => __('No', 'dynamic-content-for-elementor'),
              'return_value' => 'yes',
              'default' => '',
              'frontend_available' => true
              ],
              'animsition_duration' => [
              'label' 		=> __( 'Duration', 'dynamic-content-for-elementor' ),
              'type' 			=> Controls_Manager::SLIDER,
              'default' 	=> [
              'size' 	=> 0.2,
              ],
              'range' 	=> [
              'px' 	=> [
              'min' 	=> 0,
              'max' 	=> 2,
              'step'	=> 0.1,
              ],
              ],
              'condition' => [
              'enable_animsition' => 'yes'
              ],
              'frontend_available' => true
              ],
              ]
              ] */

            // TEMPLATE SYSTEM
            /* 'settings_templateSystem' => [
              'label' => __( 'Template System', 'dynamic-content-for-elementor' ),
              'controls' => [
              'dce_templateSystem_note' => [
              'type' 				=> Controls_Manager::RAW_HTML,
              'raw' 				=> __( '<div><i class="icon-dyn-logo-dce" style="font-size: 8em;text-align: center;display: block;"></i></div>', 'dynamic-content-for-elementor' ),
              'content_classes' 	=> '',
              ],





              'animsition_duration' => [
              'label' 		=> __( 'Duration', 'dynamic-content-for-elementor' ),
              'type' 			=> Controls_Manager::SLIDER,
              'default' 	=> [
              'size' 	=> 0.2,
              ],
              'range' 	=> [
              'px' 	=> [
              'min' 	=> 0,
              'max' 	=> 2,
              'step'	=> 0.1,
              ],
              ],
              'condition' => [
              'enable_animsition' => 'yes'
              ],
              'frontend_available' => true
              ],
              ]
              ],






              'settings_templateSystem_types' => [
              'label' => __( 'Types', 'dynamic-content-for-elementor' ),
              'controls' => [

              'dce_templateSystem_heading_types_posts' => [
              'label' => __('Posts', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::HEADING,
              ],
              'dce_templateSystem_posts_template' => [
              'label' => '',
              'type' => Controls_Manager::CHOOSE,
              'options' => [
              'singlepost' => [
              'title' => __('Single Post', 'dynamic-content-for-elementor'),
              'icon' => 'eicon-image-box',
              ],
              'archive' => [
              'title' => __('Archive', 'dynamic-content-for-elementor'),
              'icon' => 'eicon-post-list',
              ],
              'archive_beforeafter' => [
              'title' => __('Archive Before/After', 'dynamic-content-for-elementor'),
              'icon' => 'eicon-accordion',
              ],

              ],
              'default' => '',
              ],





              'dce_templateSystem_posts_archive_heading' => [
              'label' => __('Archive', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::HEADING,
              'condition' => [
              'dce_templateSystem_posts_template' => 'archive',
              ],
              ],
              'dce_templateSystem_posts_archive_template' => [
              'label' => __('Template', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SELECT2,
              'options' => DCE_Helper::get_all_template(),

              'default' => '0',
              'condition' => [
              'dce_templateSystem_posts_template' => 'archive',
              ],
              ],

              'dce_templateSystem_posts_archive_columns' => [
              'label' => __('Columns', 'dynamic-content-for-elementor'),
              'responsive' => true,
              'type' => Controls_Manager::SELECT,
              'default' => '3',
              'options' => [
              '12' => '1',
              '6' => '2',
              '4' => '3',
              '3' => '4',
              '2' => '6',
              ],
              'condition' => [
              'dce_templateSystem_posts_template' => 'archive',
              'dce_templateSystem_posts_archive_template!' => ['','0'],
              'dce_templateSystem_posts_archive_canvas' => ''
              ],

              ],
              'dce_templateSystem_posts_archive_layout' => [
              'label' => __('Layout Boxed/Full', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SWITCHER,
              'label_off' => __('Full', 'dynamic-content-for-elementor'),
              'label_on' => __('Boxed', 'dynamic-content-for-elementor'),
              'return_value' => 'full',
              'default' => '',
              'condition' => [
              'dce_templateSystem_posts_template' => 'archive',
              'dce_templateSystem_posts_archive_template!' => ['','0'],
              'dce_templateSystem_posts_archive_canvas' => ''
              ],
              ],
              'dce_templateSystem_posts_archive_canvas' => [
              'label' => __('Canvas Layout', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SWITCHER,
              'label_off' => __('No', 'dynamic-content-for-elementor'),
              'label_on' => __('Yes', 'dynamic-content-for-elementor'),
              'return_value' => 'yes',
              'default' => '',
              'condition' => [
              'dce_templateSystem_posts_template' => 'archive',
              'dce_templateSystem_posts_archive_template!' => ['','0'],
              ],
              ],





              'dce_templateSystem_posts_singepost_heading' => [
              'label' => __('Single Post', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::HEADING,
              'condition' => [
              'dce_templateSystem_posts_template' => 'singlepost',
              ],
              ],
              'dce_templateSystem_posts_singlepost' => [
              'label' => __('Template', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SELECT2,
              'options' => DCE_Helper::get_all_template(),

              'default' => '0',
              'condition' => [
              'dce_templateSystem_posts_template' => 'singlepost',
              ],
              ],
              'dce_templateSystem_posts_singlepost_layout' => [
              'label' => __('Blank template', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SWITCHER,
              'label_off' => __('Default', 'dynamic-content-for-elementor'),
              'label_on' => __('Yes', 'dynamic-content-for-elementor'),
              'return_value' => 'yes',
              'default' => '',
              'condition' => [
              'dce_templateSystem_posts_template' => 'singlepost',

              ],
              ],







              'dce_templateSystem_posts_beforeafter_heading' => [
              'label' => __('Archive Before/After', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::HEADING,
              'condition' => [
              'dce_templateSystem_posts_template' => 'archive_beforeafter',
              ],
              ],
              'dce_templateSystem_posts_beforeArchive' => [
              'label' => __('Before Template', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SELECT2,
              'options' => DCE_Helper::get_all_template(),

              'default' => '0',
              'condition' => [
              'dce_templateSystem_posts_template' => 'archive_beforeafter',
              ],
              ],
              'dce_templateSystem_posts_afterArchive' => [
              'label' => __('After Template', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SELECT2,
              'options' => DCE_Helper::get_all_template(),

              'default' => '0',
              'condition' => [
              'dce_templateSystem_posts_template' => 'archive_beforeafter',
              ],
              ],


              'dce_templateSystem_heading_types_archive_hr' => [
              'type' => Controls_Manager::DIVIDER,
              ],


              // --------------------------------------------------------------------------------------
              'dce_templateSystem_heading_types_pages' => [
              'label' => __('Pages', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::HEADING,
              ],

              'dce_templateSystem_heading_types_pages_hr' => [
              'type' => Controls_Manager::DIVIDER,
              ],


              // --------------------------------------------------------------------------------------
              'dce_templateSystem_heading_types_cpt' => [
              'label' => __('CPT..', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::HEADING,
              ],


              ],

              ], */







            /* 'settings_templateSystem_otherPages' => [
              'label' => __( 'Other Pages', 'dynamic-content-for-elementor' ),
              'controls' => [


              'dce_templateSystem_heading_users' => [
              'label' => __('Users', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::HEADING,
              ],
              'dce_templateSystem_heading_media' => [
              'label' => __('Media attachmets', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::HEADING,
              ],
              'dce_templateSystem_heading_search' => [
              'label' => __('Search', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::HEADING,
              ],
              'dce_templateSystem_heading_404' => [
              'label' => __('404', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::HEADING,
              ],
              ],
              ], */







            /* 'settings_templateSystem_taxonomy' => [
              'label' => __( 'Taxominies', 'dynamic-content-for-elementor' ),
              'controls' => [

              'dce_templateSystem_heading_categories' => [
              'label' => __('Categories', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::HEADING,
              ],
              'dce_templateSystem_heading_tags' => [
              'label' => __('Tags', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::HEADING,
              ],
              'dce_templateSystem_heading_taxonomy' => [
              'label' => __('Taxonomy ...', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::HEADING,
              ],
              ],
              ] */
            ],
                /* Controls_Manager::TAB_STYLE => [

                  ], */
        ];
    }

    protected function _register_controls() {
        $controls_list = self::get_controls_list();

        foreach ($controls_list as $tab_name => $sections) {

            foreach ($sections as $section_name => $section_data) {

                $this->start_controls_section(
                        $section_name, [
                    'label' => $section_data['label'],
                    'tab' => $tab_name,
                        ]
                );

                foreach ($section_data['controls'] as $control_name => $control_data) {
                    $this->add_control($control_name, $control_data);
                }

                $this->end_controls_section();
            }
        }
    }

}
