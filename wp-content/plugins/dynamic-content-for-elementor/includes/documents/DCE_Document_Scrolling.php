<?php

namespace DynamicContentForElementor\Documents;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 *
 * Inertia Scroll Document
 *
 */
class DCE_Document_Scrolling extends DCE_Document_Prototype {

    public $name = "Scrolling";
    protected $is_common = true;

    public function get_script_depends() {
        return [
            'imagesLoaded', 'velocity', 'dce-tweenMax-lib', 'inertiaScroll', 'jquery-easing', 'scrollify', 'dce-lax-lib', 'dce-scrolling'
        ];
    }

    public static function get_description() {
        return __('Scrolling settings.');
    }

    protected function add_common_sections_actions() {


        // Activate sections for document
        add_action('elementor/documents/register_controls', function($element) {

            $this->add_common_sections($element);
        }, 10, 2);

        // Activate sections for widgets
        /* add_action( 'elementor/element/common/_section_style/after_section_end', function( $element, $args ) {

          $this->add_common_sections( $element, $args );

          }, 10, 2 ); */

        // Activate sections for columns
        /* add_action( 'elementor/element/column/section_advanced/after_section_end', function( $element, $args ) {

          $this->add_common_sections( $element, $args );

          }, 10, 2 ); */

        // Activate sections for sections
        /* add_action( 'elementor/element/section/section_advanced/after_section_end', function( $element, $args ) {

          $this->add_common_sections( $element, $args );

          }, 10, 2 ); */
    }

    private function add_controls($document, $args) {

        $element_type = $document->get_type();

        //
        // ------------------------------------------
        $dce_data = DCE_Helper::dce_dynamic_data();
        // ------------------------------------------
        $id_page = $dce_data['id'];

        $global_is = $dce_data['is'];
        $type_page = $dce_data['type'];


        // se volessi filtrare i campi in base al tipo
        /* if ( $document->get_name() === 'section' ) {

          } */

        /* ----------------------------------- */
        /* $document->start_controls_section(
          'my_custom_section',
          [
          'label' => __( 'My Custom Section', 'my-domain' ),
          'tab' => Controls_Manager::TAB_SETTINGS
          ]
          ); */
        $document->add_control(
                'enable_dceScrolling',
                [
                    'label' => __('Scrolling settings', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'render_type' => 'template',
                    'frontend_available' => true,
                ]
        );

        $document->add_control(
                'scroll_opt_heading',
                [
                    'label' => __('Settings Scroll', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::RAW_HTML,
                    'show_label' => false,
                    'default' => '',
                    'raw' => __('<b>Scrolling Options:</b>', 'dynamic-content-for-elementor'),
                    'content_classes' => 'dce-document-settings',
                    'condition' => [
                        'enable_dceScrolling!' => '',
                    ],
                ]
        );

        $document->add_control(
                'scroll_id_page',
                [
                    'label' => __('ID Page', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::HIDDEN,
                    'default' => $id_page,
                    'frontend_available' => true,
                    'condition' => [
                        'enable_dceScrolling!' => '',
                    ],
                ]
        );







        // ----------------------------------- EFFECTS --------------------------
        $document->add_control(
                'enable_scrollEffects',
                [
                    'label' => __('Effects', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'render_type' => 'template',
                    'frontend_available' => true,
                    'separator' => 'before',
                    'condition' => [
                        'enable_dceScrolling!' => '',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        /* $document->add_control(
          'reload_scrollEffects_btn',
          [
          'type'    => Controls_Manager::RAW_HTML,
          'raw' => '<div class="elementor-update-preview" style="background-color: transparent;margin: 0;">
          <div class="elementor-update-preview-title">'.__( 'Update changes to page', 'dynamic-content-for-elementor').'</div>
          <div class="elementor-update-preview-button-wrapper">
          <button class="elementor-update-preview-button elementor-button elementor-button-success">'. __( 'Apply', 'dynamic-content-for-elementor').'</button>
          </div>
          </div>',
          'content_classes' => 'dce-btn-reload',

          'condition' => [
          'enable_scrollEffects!' => '',
          ],
          ]
          ); */
        /* $document->add_control(
          'scrollEffects_id_page',
          [
          'label' => __( 'ID Page', 'dynamic-content-for-elementor' ),
          'type' => \Elementor\Controls_Manager::HIDDEN,
          'default' => $id_page,
          'frontend_available' => true,
          'condition' => [
          'enable_scrollEffects!' => '',
          ],
          ]
          ); */
        /*
          linger 	n/a
          lazy 	100
          eager 	100
          lazy 	100
          slalom 	50
          crazy 	n/a
          spin 	360
          spinRev 	360
          spinIn 	360
          spinOut 	360
          blurInOut 	40
          blurIn 	40
          blurOut 	40
          fadeInOut 	n/a
          fadeIn 	n/a
          fadeOut 	n/a
          driftLeft 	100
          driftRight 	100
          leftToRight 	1
          rightToLeft 	1
          zoomInOut 	0.2
          zoomIn 	0.2
          zoomOut 	0.2
          swing 	30
          speedy 	30
         */
        $document->add_control(
                'animation_effects', [
            'label' => __('Animation Effects', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'label_block' => true,
            'options' => [
                '' => __('None', 'dynamic-content-for-elementor'),
                'scaleDown' => __('Scale Down', 'dynamic-content-for-elementor'),
                // 'gallery' => __('Gallery', 'dynamic-content-for-elementor'),
                'opacity' => __('Opacity', 'dynamic-content-for-elementor'),
                'fixed' => __('Fixed', 'dynamic-content-for-elementor'),
                //'parallax' => __('Parallax', 'dynamic-content-for-elementor'),
                'rotation' => __('Rotation', 'dynamic-content-for-elementor'),
                //'linger' => __('Linger', 'dynamic-content-for-elementor'),
                'lazy' => __('Lazy', 'dynamic-content-for-elementor'),
                'eager' => __('Eger', 'dynamic-content-for-elementor'),
                'slalom' => __('Slalom', 'dynamic-content-for-elementor'),
                // 'crazy' => __('Crazy', 'dynamic-content-for-elementor'),
                'spin' => __('Spin', 'dynamic-content-for-elementor'),
                'spinRev' => __('SpinRev', 'dynamic-content-for-elementor'),
                // 'spinIn' => __('SpinIn', 'dynamic-content-for-elementor'),
                // 'spinOut' => __('SpinOut', 'dynamic-content-for-elementor'),
                // 'blurInOut' => __('BlurInOut', 'dynamic-content-for-elementor'),
                // 'blurIn' => __('BlurIn', 'dynamic-content-for-elementor'),
                // 'blurOut' => __('BlurOut', 'dynamic-content-for-elementor'),
                // 'fadeInOut' => __('FadeInOut', 'dynamic-content-for-elementor'),
                // 'fadeIn' => __('FadeIn', 'dynamic-content-for-elementor'),
                // 'fadeOut' => __('FadeOut', 'dynamic-content-for-elementor'),
                'driftLeft' => __('DriftLeft', 'dynamic-content-for-elementor'),
                'driftRight' => __('DriftRight', 'dynamic-content-for-elementor'),
                'leftToRight' => __('LeftToRight', 'dynamic-content-for-elementor'),
                'rightToLeft' => __('RightToLeft', 'dynamic-content-for-elementor'),
                'zoomInOut' => __('ZoomInOut', 'dynamic-content-for-elementor'),
                'zoomIn' => __('ZoomIn', 'dynamic-content-for-elementor'),
                'zoomOut' => __('ZoomOut', 'dynamic-content-for-elementor'),
                'swing' => __('Swing', 'dynamic-content-for-elementor'),
                'speedy' => __('Speedy', 'dynamic-content-for-elementor'),
            ],
            'default' => ['scaleDown'],
            'frontend_available' => true,
            'render_type' => 'template',
            'condition' => [
                'enable_scrollEffects!' => '',
                'enable_dceScrolling!' => '',
            //'directionScroll' => 'vertical'
            ],
                ]
        );
        $document->add_control(
                'remove_first_scrollEffects',
                [
                    'label' => __('Remove Effect on first row', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'render_type' => 'template',
                    'frontend_available' => true,
                    'condition' => [
                        'enable_scrollEffects!' => '',
                        'enable_dceScrolling!' => '',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_control(
                'custom_class_section', [
            'label' => __('Custom section CLASS', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'placeholder' => 'Write custom CLASS',
            'frontend_available' => true,
            'separator' => 'before',
            'dynamic' => [
                'active' => false,
            ],
            'condition' => [
                'enable_scrollEffects!' => '',
                'enable_dceScrolling!' => '',
            //'directionScroll' => 'vertical'
            ],
                ]
        );
        $document->add_control(
                'responsive_scrollEffects', [
            'label' => __('Apply ScrollEffects on:', 'dynamic-content-for-elementor'),
            'description' => __('Responsive mode will take effect only on preview or live page, and not while editing in Elementor.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'separator' => 'before',
            'label_block' => true,
            'options' => [
                'desktop' => __('Desktop', 'dynamic-content-for-elementor'),
                'tablet' => __('Tablet', 'dynamic-content-for-elementor'),
                'mobile' => __('Mobile', 'dynamic-content-for-elementor'),
            ],
            'default' => ['desktop', 'tablet', 'mobile'],
            'frontend_available' => true,
            'render_type' => 'template',
            'condition' => [
                'enable_dceScrolling!' => '',
                'enable_scrollEffects!' => '',
            //'directionScroll' => 'vertical'
            ],
                ]
        );






        // ----------------------------------- SNAP --------------------------
        $document->add_control(
                'enable_scrollify',
                [
                    'label' => __('Snap', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    //'render_type' => 'template',
                    'frontend_available' => true,
                    'separator' => 'before',
                    'condition' => [
                        'enable_dceScrolling!' => '',
                    ////'directionScroll' => 'vertical'
                    ],
                ]
        );
        /* $document->add_control(
          'reload_scrollify_btn',
          [
          'type'    => Controls_Manager::RAW_HTML,
          'raw' => '<div class="elementor-update-preview" style="background-color: transparent;margin: 0;">
          <div class="elementor-update-preview-title">'.__( 'Update changes to page', 'dynamic-content-for-elementor').'</div>
          <div class="elementor-update-preview-button-wrapper">
          <button class="elementor-update-preview-button elementor-button elementor-button-success">'. __( 'Apply', 'dynamic-content-for-elementor').'</button>
          </div>
          </div>',
          'content_classes' => 'dce-btn-reload',
          'separator' => 'after',
          'condition' => [
          'enable_scrollify!' => '',
          'enable_dceScrolling!' => '',
          ],
          ]
          ); */
        $document->add_control(
                'custom_class_section_sfy', [
            'label' => __('Custom section CLASS', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'placeholder' => 'Write custom CLASS',
            'frontend_available' => true,
            'label_block' => true,
            'dynamic' => [
                'active' => false,
            ],
            'condition' => [
                'enable_scrollify!' => '',
                'enable_dceScrolling!' => '',
            //'directionScroll' => 'vertical'
            ],
                ]
        );
        /* $document->add_control(
          'scrollify_id_page',
          [
          'label' => __( 'ID Page', 'dynamic-content-for-elementor' ),
          'type' => \Elementor\Controls_Manager::HIDDEN,
          'default' => $dce_data['id'],
          'frontend_available' => true,
          'condition' => [
          'enable_scrollify!' => '',
          'enable_dceScrolling!' => '',
          ],
          ]
          ); */

        $document->add_control(
                'interstitialSection',
                [
                    'label' => __('Interstitial Section', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => __('', 'dynamic-content-for-elementor'),
                    'placeholder' => __('header, footer', 'dynamic-content-for-elementor'),
                    'frontend_available' => true,
                    'label_block' => true,
                    'dynamic' => [
                        'active' => false,
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_control(
                'scrollSpeed',
                [
                    'label' => __('Scroll Speed', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 1000,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 500,
                            'max' => 2400,
                            'step' => 10,
                        ],
                    ],
                    'size_units' => ['ms',],
                    'frontend_available' => true,
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );


        $document->add_control(
                'offset',
                [
                    'label' => __('Offset', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => -500,
                            'max' => 500,
                            'step' => 1,
                        ],
                    ],
                    'size_units' => ['px'],
                    'frontend_available' => true,
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        /* $document->add_control(
          'ease_scrollify',
          [
          'label' => __( 'Ease', 'dynamic-content-for-elementor' ),
          'description' => __( 'Define the easing method.','dynamic-content-for-elementor'),
          'type' => Controls_Manager::SELECT,
          'default' => 'easeOutExpo',
          'options' => DCE_Helper::get_ease_timingFunctions(),
          'frontend_available' => true,
          'condition' => [
          'enable_scrollify!' => '',
          'enable_dceScrolling!' => '',
          ////'directionScroll' => 'vertical'
          ],
          ]
          ); */
        $document->add_control(
                'setHeights',
                [
                    'label' => __('Set Heights', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'frontend_available' => true,
                    'default' => 'yes',
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_control(
                'overflowScroll',
                [
                    'label' => __('Overflow Scroll', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'frontend_available' => true,
                    'default' => 'yes',
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_control(
                'updateHash',
                [
                    'label' => __('Update Hash', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'frontend_available' => true,
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_control(
                'scrollBars',
                [
                    'label' => __('Show scrollBars', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'frontend_available' => true,
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                    ////'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_control(
                'touchScroll',
                [
                    'label' => __('Touch Scroll', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'frontend_available' => true,
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        // -----------------------------------------
        $document->add_control(
                'enable_scrollify_nav',
                [
                    'label' => __('Enable navigation', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'frontend_available' => true,
                    'render_type' => 'template',
                    'separator' => 'before',
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_control(
                'snapscroll_nav_style',
                [
                    'label' => __('Navigation style', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'default' => __('Dynamic', 'dynamic-content-for-elementor'), // quello che c'è già, simile a Maxamed
                        'shamso' => __('1 Dotts', 'dynamic-content-for-elementor'), // Shamso
                        'xusni' => __('2 Bars', 'dynamic-content-for-elementor'), // Xusni oppure Beca
                        'etefu' => __('3 Vertical Bars', 'dynamic-content-for-elementor'), // Etefu
                        'magool' => __('4 Lines (No title)', 'dynamic-content-for-elementor'), // Magool
                        'ubax' => __('5 Squares', 'dynamic-content-for-elementor'), // Ubax
                        'timiro' => __('6 Circles', 'dynamic-content-for-elementor'), // Timiro
                        'ayana' => __('7 Circles line (svg)', 'dynamic-content-for-elementor'), // Ayana
                        'desta' => __('8 triangles', 'dynamic-content-for-elementor'), // Desta
                        'totit' => __('9 Icons', 'dynamic-content-for-elementor'), // Totit
                    ],
                    'render_type' => 'template',
                    'frontend_available' => true,
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                    //'directionScroll' => 'vertical'
                    ////'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_control(
                'snapscroll_nav_title_style',
                [
                    'label' => __('Show title of section', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'separator' => 'before',
                    'default' => 'none',
                    'options' => [
                        'none' => __('None', 'dynamic-content-for-elementor'),
                        'number' => __('Number', 'dynamic-content-for-elementor'),
                        'classid' => __('Section CSS ID', 'dynamic-content-for-elementor'),
                    ],
                    'frontend_available' => true,
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style!' => ['magool', 'timiro'],
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_control(
                'sectionid_info',
                [
                    'label' => __('Section class-id info', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::RAW_HTML,
                    'show_label' => false,
                    'default' => '',
                    'raw' => __('<div>You must first write the <b>class ID</b> on the sections and then apply this option to see the result. The name in the ID must not contain spaces or use (-) or (_) to separate the words, in the result they will be converted into spaces.', 'dynamic-content-for-elementor'),
                    'content_classes' => 'dce-document-settings',
                    'separator' => 'after',
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_title_style' => 'classid',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'nav_title_typography',
            'label' => __('Title Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .nav__item-title',
            'condition' => [
                'enable_scrollify!' => '',
                'enable_dceScrolling!' => '',
                'enable_scrollify_nav!' => '',
                'snapscroll_nav_title_style!' => 'none',
            //'directionScroll' => 'vertical'
            ],
                ]
        );
        $document->add_control(
                'nav_title_color',
                [
                    'label' => __('Title Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination .nav__item--current .nav__item-title' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_title_style!' => 'none',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        //
        $document->add_control(
                'scrollify_nav_icon',
                [
                    'label' => __('Icon', 'dynamic-content-for-elementor'),
                    'type' => 'icons', //Controls_Manager::ICONS, 
                    'default' => [
                        'value' => 'fas fa-star',
                        'library' => 'solid',
                    ],
                    'frontend_available' => true,
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style' => 'totit',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_responsive_control(
                'scrollify_nav_size',
                [
                    'label' => __('Size', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'separator' => 'before',
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 80,
                            'step' => 1,
                        ],
                    ],
                    'size_units' => ['px'],
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a:after, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--magool .nav__item, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--shamso .nav__item, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--xusni .nav__item, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--etefu .nav__item, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ayana .nav__item,
                      {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--totit .nav__item,
                      {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--totit .nav__item-title,.dce-scrollify-pagination.nav--ubax .nav__item,
                      {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--desta .nav__icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--totit .nav__item .fas' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--desta .nav__item-title' => 'padding-right: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--totit .nav__item-title,.dce-scrollify-pagination.nav--ubax .nav__item-title' => 'right: {{SIZE}}{{UNIT}};'
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_responsive_control(
                'scrollify_nav_iconsize',
                [
                    'label' => __('Icon size', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => '',
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 80,
                            'step' => 1,
                        ],
                    ],
                    'size_units' => ['px'],
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--totit .nav__item .fas' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style' => 'totit',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_responsive_control(
                'scrollify_nav_space',
                [
                    'label' => __('Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'size_units' => ['px'],
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination li:not(first-child)' => 'padding-top: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_responsive_control(
                'scrollify_nav_side',
                [
                    'label' => __('Side space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                        'unit' => 'px'
                    ],
                    'size_units' => ['px', '%', 'vw'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 80,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                        'vw' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination' => 'right: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                    //'directionScroll' => 'vertical',
                    ],
                ]
        );

        $document->add_control(
                'scrollify_nav_style_color',
                [
                    'label' => __('Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'separator' => 'before',
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--shamso .nav__item::before, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--xusni .nav__item::before, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--etefu .nav__item-inner, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--magool .nav__item::after, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ubax .nav__item::after, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--timiro .nav__item, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--totit .nav__item::before' => 'background: {{VALUE}};',
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--shamso .nav__item::after' => 'box-shadow: inset 0 0 0 3px {{VALUE}};',
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ayana .nav__icon' => 'stroke: {{VALUE}};',
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--desta .nav__icon' => 'fill: {{VALUE}}',
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--totit .nav__item--current .fas' => 'color: {{VALUE}}'
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style!' => 'default',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_control(
                'scrollify_nav_style_active_color',
                [
                    'label' => __('Active Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'separator' => 'before',
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--shamso .nav__item--current::after' => 'box-shadow: inset 0 0 0 3px {{VALUE}};',
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--xusni .nav__item--current::before' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination .nav__item--current' => 'color: {{VALUE}};',
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ubax .nav__item--current::after' => 'border-color: {{VALUE}}',
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--etefu .nav__item-inner::before, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--magool .nav__item--current::after,
                        {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ubax .nav__item--current::after,
                        {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ubax .nav__item:not(.nav__item--current):focus::after, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ubax .nav__item:not(.nav__item--current):hover::after, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--timiro .nav__item::before, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ayana .nav__item::before' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--desta .nav__item--current .nav__icon, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--desta .nav__item:not(.nav__item--current):focus .nav__icon, {{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--desta .nav__item:not(.nav__item--current):hover .nav__icon ' => 'fill: {{VALUE}};',
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--totit .nav__item--current .fas' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style!' => 'default',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_control(
                'scrollify_nav_style_active_bordercolor',
                [
                    'label' => __('Active Border Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'separator' => 'before',
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--ubax .nav__item--current::after' => 'border-color: {{VALUE}}',
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style' => 'ubax',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );

        $document->start_controls_tabs('nav_colors');

        $document->start_controls_tab(
                'nav_colors_normal',
                [
                    'label' => __('Normal', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style' => 'default',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );


        $document->add_control(
                'scrollify_nav_bgcolor',
                [
                    'label' => __('Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a:after' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style' => 'default',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_control(
                'scrollify_nav_color',
                [
                    'label' => __('Border color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#444444',
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a:after' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style' => 'default',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_control(
                'scrollify_nav_border_size',
                [
                    'label' => __('Border size', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => '',
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 20,
                            'step' => 1,
                        ],
                    ],
                    'size_units' => ['px'],
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a:after' => 'border-width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style' => 'default',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );

        $document->end_controls_tab();

        $document->start_controls_tab(
                'nav_colors_hover',
                [
                    'label' => __('Hover', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style' => 'default',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );

        $document->add_control(
                'scrollify_nav_bgcolor_hover',
                [
                    'label' => __('Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a:hover:after' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style' => 'default',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );

        $document->add_control(
                'scrollify_nav_color_hover',
                [
                    'label' => __('Border color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a:hover:after' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style' => 'default',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_responsive_control(
                'scrollify_nav_hover_size',
                [
                    'label' => __('Size (&)', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => '',
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 10,
                            'step' => 0.1,
                        ],
                    ],
                    'size_units' => ['px'],
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a:hover:after' => 'transform: scale({{SIZE}});',
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style' => 'default',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_control(
                'scrollify_nav_border_hover_size',
                [
                    'label' => __('Border size', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => '',
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 20,
                            'step' => 1,
                        ],
                    ],
                    'size_units' => ['px'],
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a:hover:after' => 'border-width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style' => 'default',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );

        $document->end_controls_tab();

        $document->start_controls_tab(
                'nav_colors_active',
                [
                    'label' => __('Active', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style' => 'default',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );

        $document->add_control(
                'scrollify_nav_bgcolor_active',
                [
                    'label' => __('Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a.nav__item--current:after' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style' => 'default',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );

        $document->add_control(
                'scrollify_nav_color_active',
                [
                    'label' => __('Border color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a.nav__item--current:after' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style' => 'default',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_responsive_control(
                'scrollify_nav_active_size',
                [
                    'label' => __('Size (%)', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => '',
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 10,
                            'step' => 0.1,
                        ],
                    ],
                    'size_units' => ['px'],
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a.nav__item--current:after' => 'transform: scale({{SIZE}});',
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style' => 'default',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );
        $document->add_control(
                'scrollify_nav_border_active_size',
                [
                    'label' => __('Border size', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => '',
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 20,
                            'step' => 1,
                        ],
                    ],
                    'size_units' => ['px'],
                    'selectors' => [
                        '{{WRAPPER}}.dce-scrollify .dce-scrollify-pagination.nav--default a.nav__item--current:after' => 'border-width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_scrollify!' => '',
                        'enable_dceScrolling!' => '',
                        'enable_scrollify_nav!' => '',
                        'snapscroll_nav_style' => 'default',
                    //'directionScroll' => 'vertical'
                    ],
                ]
        );

        $document->end_controls_tab();

        $document->end_controls_tabs();

        $document->add_control(
                'responsive_snapScroll', [
            'label' => __('Apply SnapScroll on:', 'dynamic-content-for-elementor'),
            'description' => __('Responsive mode will take effect only on preview or live page, and not while editing in Elementor.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'separator' => 'before',
            'label_block' => true,
            'options' => [
                'desktop' => __('Desktop', 'dynamic-content-for-elementor'),
                'tablet' => __('Tablet', 'dynamic-content-for-elementor'),
                'mobile' => __('Mobile', 'dynamic-content-for-elementor'),
            ],
            'default' => ['desktop', 'tablet', 'mobile'],
            'frontend_available' => true,
            'render_type' => 'template',
            'condition' => [
                'enable_dceScrolling!' => '',
                'enable_scrollify!' => ''
            ],
                ]
        );
        // ----------------------------------- INERTIA --------------------------
        $document->add_control(
                'enable_inertiaScroll',
                [
                    'label' => __('InertiaScroll', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'separator' => 'before',
                    'frontend_available' => true,
                    'condition' => [
                        'enable_dceScrolling!' => '',
                        'enable_scrollify' => '',
                    ],
                ]
        );
        /* $document->add_control(
          'scroll_direction_info',
          [
          'label' => __( 'Direction info', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::RAW_HTML,
          'show_label' => false,
          'default' => '',

          'raw' 				=> __( '<div>Definisce se lo scorrimento delle sezioni è Naturale (verticale) oppure Orizzontale (richiede trasformazioni)</div>', 'dynamic-content-for-elementor' ),
          'content_classes' 	=> 'dce-document-settings',

          'condition' => [
          'enable_dceScrolling!' => '',
          ],
          ]
          ); */
        $document->add_control(
                'scroll_info',
                [
                    'label' => __('Settings Scroll', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::RAW_HTML,
                    'show_label' => false,
                    'default' => '',
                    'raw' => __('<div>Scrolling management compromises various elements of the page (not just Elementor). In order to function correctly and obtain the transformations, it is necessary to indicate the css selectors of the theme used.<br><b><hr>By default we indicate the elements of the theme OceanWP.</b>', 'dynamic-content-for-elementor'),
                    'content_classes' => 'dce-document-settings',
                    'separator' => 'after',
                    'condition' => [
                        'enable_dceScrolling!' => '',
                        'enable_inertiaScroll!' => '',
                    ],
                ]
        );
        $document->add_control(
                'scroll_viewport',
                [
                    'label' => __('Viewport', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => '#outer-wrap',
                    'frontend_available' => true,
                    'dynamic' => [
                        'active' => false,
                    ],
                    'condition' => [
                        'enable_dceScrolling!' => '',
                        'enable_inertiaScroll!' => '',
                    ],
                ]
        );
        $document->add_control(
                'scroll_contentScroll',
                [
                    'label' => __('Content Scroll', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => '#wrap',
                    'frontend_available' => true,
                    'dynamic' => [
                        'active' => false,
                    ],
                    'condition' => [
                        'enable_dceScrolling!' => '',
                        'enable_inertiaScroll!' => '',
                    ],
                ]
        );
        // --------- COEF
        $document->add_control(
                'coefSpeed_inertiaScroll', [
            'label' => __('Coef. of Speed (0-1) Default: 0.05', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '0.05',
            ],
            'range' => [
                'px' => [
                    'max' => 0.5,
                    'min' => 0,
                    'step' => 0.01,
                ],
            ],
            'frontend_available' => true,
            'condition' => [
                'enable_dceScrolling!' => '',
                'enable_inertiaScroll!' => '',
            ],
                ]
        );
        // --------- BOUNCE
        $document->add_control(
                'bounce_inertiaScroll', [
            'label' => __('Bounce', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '0',
            ],
            'range' => [
                'px' => [
                    'max' => 0.8,
                    'min' => 0,
                    'step' => 0.01,
                ],
            ],
            'frontend_available' => true,
            'condition' => [
                'enable_dceScrolling!' => '',
                'enable_inertiaScroll!' => '',
            ],
                ]
        );
        // --------- COEF
        $document->add_control(
                'skew_inertiaScroll', [
            'label' => __('Skew', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '10',
            ],
            'range' => [
                'px' => [
                    'max' => 20,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'frontend_available' => true,
            'condition' => [
                'enable_dceScrolling!' => '',
                'enable_inertiaScroll!' => '',
            ],
                ]
        );

        /* $document->add_control(
          'directionScroll', [
          'label' => __('Direction', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SELECT,
          'options' => [
          'vertical' => __('Vertical', 'dynamic-content-for-elementor'),
          'horizontal' => __('Horizontal', 'dynamic-content-for-elementor'),
          ],
          'default' => 'vertical',
          //'prefix_class' => 'scroll-direction-',
          'frontend_available' => true,
          'condition' => [
          'enable_dceScrolling!' => '',
          'enable_inertiaScroll' => 'yes'
          ],
          ]
          ); */
        $document->add_control(
                'directionScroll',
                [
                    'label' => __('direction of Scroll', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'vertical',
                    'options' => [
                        'vertical' => __('Vertical', 'dynamic-content-for-elementor'),
                        'horizontal' => __('Horizontal', 'dynamic-content-for-elementor'),
                    ],
                    'frontend_available' => true,
                    'condition' => [
                        'enable_dceScrolling!' => '',
                        'enable_inertiaScroll!' => '',
                    ],
                ]
        );
        /* $document->add_control(
          'scroll_target',
          [
          'label' => __('Target (optional)', 'dynamic-content-for-elementor'),
          'description' => 'the ID tag of the main item to be scrolled',
          'type' => Controls_Manager::TEXT,
          'dynamic' => [
          'active' => false,
          ],
          'default' => '',
          'frontend_available' => true,
          'condition' => [
          'enable_dceScrolling!' => '',
          'enable_inertiaScroll!' => '',
          ],
          ]
          ); */
        /* $document->add_control(
          'inertiaScroll_id_page',
          [
          'label' => __( 'ID Page', 'dynamic-content-for-elementor' ),
          'type' => \Elementor\Controls_Manager::HIDDEN,
          'default' => $id_page,
          'frontend_available' => true,
          'condition' => [
          'enable_scrollEffects!' => '',
          ],
          ]
          ); */
        // --------------------
        $document->add_control(
                'responsive_inertiaScroll', [
            'label' => __('Apply InertiaScroll on:', 'dynamic-content-for-elementor'),
            'description' => __('Responsive mode will take effect only on preview or live page, and not while editing in Elementor.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'separator' => 'before',
            'label_block' => true,
            'options' => [
                'desktop' => __('Desktop', 'dynamic-content-for-elementor'),
                'tablet' => __('Tablet', 'dynamic-content-for-elementor'),
                'mobile' => __('Mobile', 'dynamic-content-for-elementor'),
            ],
            'default' => ['desktop'],
            'frontend_available' => true,
            'render_type' => 'template',
            'condition' => [
                'enable_dceScrolling!' => '',
                'enable_inertiaScroll!' => ''
            ],
                ]
        );
    }

    protected function add_actions() {
        //$settings = $this->get_settings_for_display();
        //page-settings
        //document
        //common (i widget)
        $element_data;

        if (version_compare(ELEMENTOR_VERSION, '2.7.0', '<')) {
            // Activate controls for Post
            add_action('elementor/element/post/section_dce_document_scroll/before_section_end', function($element, $args) {
                $this->add_controls($element, $args);
            }, 10, 2);
            add_action('elementor/element/product/section_dce_document_scroll/before_section_end', function($element, $args) {
                $this->add_controls($element, $args);
            }, 10, 2);
        } else {
            add_action('elementor/element/wp-post/section_dce_document_scroll/before_section_end', function($element, $args) {
                $this->add_controls($element, $args);
            }, 10, 2);
            add_action('elementor/element/wp-page/section_dce_document_scroll/before_section_end', function($element, $args) {
                $this->add_controls($element, $args);
            }, 10, 2);            
        }

        add_action('elementor/element/page/section_dce_document_scroll/before_section_end', function($element, $args) {
            $this->add_controls($element, $args);
        }, 10, 2);
        add_action('elementor/element/section/section_dce_document_scroll/before_section_end', function($element, $args) {
            $this->add_controls($element, $args);
        }, 10, 2);
        

        
        add_action( 'elementor/frontend/after_enqueue_scripts', function() {
            $post_id = (isset($_GET['post'])) ? $_GET['post'] : get_the_ID();
            
            $settings = get_post_meta( $post_id, '_elementor_page_settings', true );
            if ( empty( $settings ) || !is_array( $settings ) ) {
                return;
            }
            
            if (!empty($settings['enable_dceScrolling'])) {
                $this->_enqueue_alles();
            }
        });
        
    }

}
