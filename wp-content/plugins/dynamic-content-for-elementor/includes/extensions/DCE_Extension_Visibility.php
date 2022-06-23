<?php

namespace DynamicContentForElementor\Extensions;

use Elementor\Controls_Manager;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Visibility extenstion
 *
 * Conditional Visibility Widgets & Rows/Sections
 *
 * @since 1.0.1
 */
class DCE_Extension_Visibility extends DCE_Extension_Prototype {

    public $name = 'Visibility';
    public $has_controls = true;
    public $common_sections_actions = array(
        array(
            'element' => 'common',
            'action' => '_section_style',
        ),
        array(
            'element' => 'section',
            'action' => 'section_advanced',
        )
    );
    public static $tabs = [
        'user' => 'User & Role', 
        'device' => 'Device & Browser', 
        'datetime' => 'Date & Time', 
        'post' => 'Post', 
        //'tags' => 'Conditional Tags', 
        'archive' => 'Archive',
        'context' => 'Context', 
        'random' => 'Random', 
        'custom' => 'Custom condition', 
        'events' => 'Events',
        'v2' => 'V2 Backwards compatibility', 
        'repeater' => 'Advanced', 
        'fallback' => 'Fallback'
        ];
    public static $triggers = array(
        'user' => array(
            'label' => 'User & Role',
            'options' => array(
                'role',
                'users',
                'usermeta',
            ),
        ),
        'device' => array(
            'label' => 'Device & Browser',
            'options' => array(
                'browser',
                'responsive',
            ),
        ),
        'post' => array(
            'label' => 'Current Post',
            'options' => array(
                'leaf',
                'parent',
                'node',
                'root',
            ),
        ),
    );

    /**
     * The description of the current extension
     *
     * @since 0.5.4
     * */
    public static function get_description() {
        return __('Visibility rules for Widgets and Rows', 'dynamic-content-for-elementor');
    }
    
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/dynamic-visibility-for-elementor/';
    }

    /**
     * Add Actions
     *
     * @since 0.5.5
     *
     * @access private
     */
    protected function add_actions() {
        
        // TODO/FIX: Error: Routes: `panel/editor/dce_visibility` not found.
        
        add_action('elementor/editor/after_enqueue_scripts', function() {
            wp_register_script(
                'dce-script-editor-visibility', plugins_url('/assets/js/dce-editor-visibility.js', DCE__FILE__), [], DCE_VERSION
            );
            wp_enqueue_script('dce-script-editor-visibility');
        });

        // Activate controls for widgets
        add_action('elementor/element/common/dce_section_visibility_advanced/before_section_end', function( $element, $args ) {
            $this->add_controls($element, $args);
        }, 10, 2);
        foreach (self::$tabs as $tkey => $tvalue) {
            // Activate controls for widgets
            add_action('elementor/element/common/dce_section_visibility_' . $tkey . '/before_section_end', function( $element, $args ) use ($tkey) {
                $args['section'] = $tkey;
                $this->add_controls($element, $args);
            }, 10, 2);
        }


        //add_filter('elementor/widget/print_template', array($this, 'visibility_print_widget'), 10, 2);
        add_filter('elementor/widget/render_content', array($this, 'visibility_render_widget'), 10, 2);
        add_action("elementor/frontend/widget/before_render", function( $element ) {
            $settings = $element->get_settings_for_display();
            if (isset($settings['enabled_visibility']) && $settings['enabled_visibility']) {
                if ($this->is_hidden($element)) {
                    echo '<!--DCE VISIBILITY HIDDEN WIDGET-->';
                    if (!isset($settings['dce_visibility_debug']) || !$settings['dce_visibility_debug']) {
                        if (!isset($settings['dce_visibility_fallback']) || !$settings['dce_visibility_fallback']) {
                            $element->add_render_attribute('_wrapper', 'class', 'dce-visibility-widget-hidden');
                        }
                    } else {
                        $element->add_render_attribute('_wrapper', 'class', 'dce-visibility-widget-hidden-debug');
                    }
                }
            }
        }, 10, 1);

        // Activate controls for sections
        add_action('elementor/element/section/dce_section_visibility_advanced/before_section_end', function( $element, $args ) {
            $this->add_controls($element, $args);
        }, 10, 2);
        foreach (self::$tabs as $tkey => $tvalue) {
            // Activate controls for widgets
            add_action('elementor/element/section/dce_section_visibility_' . $tkey . '/before_section_end', function( $element, $args ) use ($tkey) {
                $args['section'] = $tkey;
                $this->add_controls($element, $args);
            }, 10, 2);
        }
        add_action('elementor/frontend/section/before_render', function( $element ) {
            $element_type = $element->get_type();
            $element_name = $element->get_unique_name();
            $element_id = $element->get_id();
            $settings = $element->get_settings_for_display();
            if (isset($settings['enabled_visibility']) && $settings['enabled_visibility']) {
                //var_dump($this->is_hidden($settings));
                if ($this->is_hidden($element)) {
                    //$fallback = $this->get_fallback($settings, $element);
                    //if (!$fallback) {
                    //$element->add_render_attribute('_wrapper', 'class', 'dce-visibility-section-hidden');
                    //}
                }
            }
        }, 10, 1);


        // filter sections
        add_action("elementor/frontend/section/before_render", function( $element ) {
            $settings = $element->get_settings_for_display();
            if (isset($settings['enabled_visibility']) && $settings['enabled_visibility']) {
                $hidden = $this->is_hidden($element);
                if ($hidden) {
                    echo '<!--DCE VISIBILITY HIDDEN SECTION START-->';
                    if (!isset($settings['dce_visibility_dom']) || !$settings['dce_visibility_dom']) {
                        ob_start();
                    } else {
                        $element->add_render_attribute('_wrapper', 'class', 'dce-visibility-section-hidden');
                        $element->add_render_attribute('_wrapper', 'class', 'dce-visibility-original-content');
                    }
                } 
                $this->set_element_view_counters($element, $hidden);
            }
        }, 10, 1);
        add_action("elementor/frontend/section/after_render", function( $element ) {
            $settings = $element->get_settings_for_display();
            $content = '';
            if (isset($settings['enabled_visibility']) && $settings['enabled_visibility']) {
                if ($this->is_hidden($element)) {
                    if (!isset($settings['dce_visibility_dom']) || !$settings['dce_visibility_dom']) {
                        $content = ob_get_contents();
                        ob_end_clean();
                    }
                    $this->print_conditions($element);
                    $this->print_scripts($element);
                    $fallback = $this->get_fallback($settings, $element);
                    if ($fallback) {
                        $fallback = str_replace('dce-visibility-section-hidden', '', $fallback);
                        $fallback = str_replace('dce-visibility-original-content', 'dce-visibility-fallback-content', $fallback);
                        echo $fallback;
                    }
                    echo '<!--DCE VISIBILITY HIDDEN SECTION END-->';
                }
            }
        }, 10, 1);

        // filter columns
        //addAction( "elementor/frontend/column/before_render", 'filterSectionContentBefore', 10, 1 );
        //addAction( "elementor/frontend/column/after_render", 'filterSectionContentAfter', 10, 1 );
    }
    
    public function get_control_section($section_name, $element) {
            $low_name = $this->get_low_name();
            
            \Elementor\Controls_Manager::add_tab(
                    'dce_'.$low_name,
                    __( $this->name, 'dynamic-content-for-elementor' )
            );
            
            $element->start_controls_section(
                $section_name, [
                    'tab' => 'dce_'.$low_name,
                    'label' => '<span class="color-dce icon icon-dyn-logo-dce pull-right ml-1"></span> '.__($this->name, 'dynamic-content-for-elementor'),
                ]
            );
            $element->end_controls_section();
            
            foreach (DCE_Extension_Visibility::$tabs as $tkey => $tlabel) {
                $section_name = 'dce_section_'.$low_name.'_'.$tkey;
                
                $condition = [
                                'enabled_'.$low_name.'!' => '',
                                'dce_'.$low_name.'_hidden' => '',
                                'dce_'.$low_name.'_mode' => 'quick',
                            ];
                if ($tkey == 'fallback') {
                    $condition = ['enabled_'.$low_name.'!' => ''];
                }
                if ($tkey == 'repeater') {
                    $condition = [
                                'enabled_'.$low_name.'!' => '',
                                'dce_'.$low_name.'_hidden' => '',
                                'dce_'.$low_name.'_mode' => 'advanced',
                            ];
                }
                
                $icon = '';
                switch ($tkey) {
                    case 'user':
                        $icon = 'user-o';
                        break;
                    case 'datetime':
                        $icon = 'calendar';
                        break;
                    case 'device':
                        $icon = 'mobile';
                        break;
                    case 'post':
                        $icon = 'file-text-o';
                        break;
                    case 'context':
                        $icon = 'crosshairs';
                        break;
                    case 'tags':
                        $icon = 'question-circle-o';
                        break;
                    case 'archive':
                        $icon = 'puzzle-piece';
                        break;
                    case 'random':
                        $icon = 'random';
                        break;
                    case 'custom':
                        $icon = 'code';
                        break;
                    case 'events':
                        $icon = 'hand-pointer-o';
                        break;
                    case 'fallback':
                        $icon = 'life-ring';
                        break;
                    case 'advanced':
                        $icon = 'cogs';
                        break;
                    default:
                        $icon = 'cog';
                }
                if ($icon) {
                    $icon = '<i class="fa fa-'.$icon.' pull-right ml-1" aria-hidden="true"></i>';
                }
                
                $element->start_controls_section(
                    $section_name, [
                        'tab' => 'dce_'.$low_name,
                        'label' => $icon.__($tlabel, 'dynamic-content-for-elementor'),
                        'condition' => $condition,
                    ]
                );
                $element->end_controls_section();
            }
            
        }

    /**
     * Add Controls
     *
     * @since 0.5.5
     *
     * @access private
     */
    private function add_controls($element, $args) {

        $roles = DCE_Helper::get_roles(false);
        $post_types = DCE_Helper::get_post_types();        
        $taxonomies = DCE_Helper::get_taxonomies();
        //$templates = DCE_Helper::get_all_template();
        
        /* \Elementor\Controls_Manager::add_tab(
          'dce-visibility',
          __( 'Visibility', 'dynamic-content-for-elementor' )
          ); */
        //var_dump($args); die();

        if (isset($args['section'])) {
            $section = $args['section'];
        } else {
            $section = 'advanced';
        }

        $element_type = $element->get_type();

        /* $element->start_controls_section(
          'visibility_section',
          [
          'label' => __( 'Visibility', 'dynamic-content-for-elementor' ),
          'tab' => 'dce-visibility',
          ]
          ); */

        if ($section == 'advanced') {

            $element->add_control(
                    'enabled_visibility', [
                'label' => __('Enable Visibility', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'frontend_available' => true,
                    ]
            );

            $element->add_control(
                    'dce_visibility_hidden', [
                'label' => __('HIDE this element', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                //'label_on' => __('Hide', 'dynamic-content-for-elementor'),
                //'label_off' => __('Show', 'dynamic-content-for-elementor'),
                'description' => __('Hide the element on the frontend until it is enabled', 'dynamic-content-for-elementor'),
                'condition' => [
                    'enabled_visibility' => 'yes',
                ],
                'separator' => 'before',
                    ]
            );

            $element->add_control(
                    'dce_visibility_dom', [
                'label' => __('Keep HTML', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'description' => __('Keep the element HTML in the DOM.', 'dynamic-content-for-elementor') . '<br>' . __('Only hide this element via CSS.', 'dynamic-content-for-elementor'),
                'condition' => [
                    'enabled_visibility' => 'yes',
                ],
                'separator' => 'before',
                    ]
            );

            if (defined('DVE_PLUGIN_BASE') || true) {
                $element->add_control(
                        'dce_visibility_mode', [
                    'label' => __('Composition mode', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HIDDEN,
                    'default' => 'quick',
                        ]
                );
            } else {
                /*
                  $element->add_control(
                  'dce_visibility_mode', [
                  'label' => __('Composition mode', 'dynamic-content-for-elementor'),
                  'type' => Controls_Manager::CHOOSE,
                  'options' => [
                  'quick' => [
                  'title' => __('Quick', 'dynamic-content-for-elementor'),
                  'icon' => 'fa fa-bolt',
                  ],
                  'advanced' => [
                  'title' => __('Advanced', 'dynamic-content-for-elementor'),
                  'icon' => 'fa fa-list-ol',
                  ]
                  ],
                  'default' => 'quick',
                  'description' => __('Quickly set a trigger or create a complex expression in Advanced mode.', 'dynamic-content-for-elementor'),
                  'toggle' => false,
                  'condition' => [
                  'enabled_visibility' => 'yes',
                  'dce_visibility_hidden' => '',
                  ],
                  ]
                  );
                 *
                 */
            }

            $element->add_control(
                    'dce_visibility_selected', [
                'label' => __('Display mode', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'dynamic-content-for-elementor'),
                'label_off' => __('Hide', 'dynamic-content-for-elementor'),
                'description' => __('Hide or Show element when a condition is triggered.', 'dynamic-content-for-elementor'),
                'default' => 'yes',
                'condition' => [
                    'enabled_visibility' => 'yes',
                    'dce_visibility_hidden' => '',
                ],
                    ]
            );



            if (WP_DEBUG) {
                $element->add_control(
                        'dce_visibility_debug', [
                    'label' => __('DEBUG', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'description' => __('Enable to get a report of triggered rule which hide element in frontend.<br>WP_DEBUG must be active.', 'dynamic-content-for-elementor'),
                    'separator' => 'before',
                    'condition' => [
                        'enabled_visibility' => 'yes',
                        'dce_visibility_hidden' => '',
                    //'dce_visibility_selected' => '',
                    ],
                        ]
                );
            }

            if (defined('DVE_PLUGIN_BASE')) {
                $element->add_control(
                        'dce_visibility_review', [
                    'label' => '<b>' . __('Enjoyed Visibility extension?', 'dynamic-content-for-elementor') . '</b>',
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => __('Please leave us a', 'dynamic-content-for-elementor')
                    . ' <a target="_blank" href="https://wordpress.org/support/plugin/dynamic-visibility-for-elementor/reviews/?filter=5/#new-post">★★★★★</a> '
                    . __('rating.<br>We really appreciate your support!', 'dynamic-content-for-elementor'),
                    'separator' => 'before',
                        ]
                );
            }
            
            $element->add_control(
                    'dce_visibility_help', [
                //'label' => '<b>' . __('Enjoyed Visibility extension?', 'dynamic-content-for-elementor') . '</b>',
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div id="elementor-panel__editor__help" class="p-0"><a id="elementor-panel__editor__help__link" href="'.$this->get_docs().'" target="_blank">'.__( 'Need Help', 'elementor' ).' <i class="eicon-help-o"></i></a></div>',
                'separator' => 'before',
                    ]
            );
        }

        if ($section == 'v2') {
            if (false) {
                $ctype = Controls_Manager::HIDDEN;
            } else {
                $ctype = Controls_Manager::SWITCHER;
                $element->add_control(
                        'dce_visibility_v2_notice', [
                    'label' => __('<b>WARNING</b>', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => __('<b>If you updated from V2</b> set all to Yes and manage it from main "Display Mode", otherwise ignore this control section', 'dynamic-content-for-elementor'),
                        ]
                );
            }
            $element->add_control(
                    'dce_visibility_user_selected', [
                'label' => __('User Show/Hide', 'dynamic-content-for-elementor'),
                //'type' => Controls_Manager::HIDDEN,
                'type' => $ctype,
                'default' => 'yes',
                    ]
            );
            $element->add_control(
                    'dce_visibility_device_selected', [
                'label' => __('Device Show/Hide', 'dynamic-content-for-elementor'),
                //'type' => Controls_Manager::HIDDEN,
                'type' => $ctype,
                'default' => 'yes',
                    ]
            );
            $element->add_control(
                    'dce_visibility_datetime_selected', [
                'label' => __('DateTime Show/Hide', 'dynamic-content-for-elementor'),
                //'type' => Controls_Manager::HIDDEN,
                'type' => $ctype,
                'default' => 'yes',
                    ]
            );
            $element->add_control(
                    'dce_visibility_context_selected', [
                'label' => __('Context Show/Hide', 'dynamic-content-for-elementor'),
                //'type' => Controls_Manager::HIDDEN,
                'type' => $ctype,
                'default' => 'yes',
                    ]
            );
            $element->add_control(
                    'dce_visibility_tags_selected', [
                'label' => __('Tags Show/Hide', 'dynamic-content-for-elementor'),
                //'type' => Controls_Manager::HIDDEN,
                'type' => $ctype,
                'default' => 'yes',
                    ]
            );
            $element->add_control(
                    'dce_visibility_custom_condition_selected', [
                'label' => __('Custom Show/Hide', 'dynamic-content-for-elementor'),
                //'type' => Controls_Manager::HIDDEN,
                'type' => $ctype,
                'default' => 'yes',
                    ]
            );
        }


        if ($section == 'user') {
            /* $element->start_controls_section(
              'section_visibility_user', [
              'label' => __('User & Roles', 'dynamic-content-for-elementor'),
              'condition' => [
              'enabled_visibility' => 'yes',
              'dce_visibility_hidden' => '',
              ],
              ]
              ); */
            /* $element->add_control(
              'role_visibility_heading', [
              'label' => __('Users & Roles', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::HEADING,
              'condition' => [
              'enabled_visibility' => 'yes',
              'dce_visibility_hidden' => '',
              ],
              'separator' => 'before',
              ]
              ); */

            /* $element->add_control(
              'dce_visibility_everyone', [
              'label' => __('Visible by EveryONE', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SWITCHER,
              'default' => 'yes',
              'description' => __('If enabled every user, also visitors, can view the element', 'dynamic-content-for-elementor'),
              ]
              ); */


            $roles = array_reverse($roles, true);
            //$roles['users'] = 'Selected User';
            $roles['visitor'] = 'Visitor (non logged User)';
            $roles = array_reverse($roles, true);

            $element->add_control(
                    'dce_visibility_role', [
                'label' => __('Roles', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                'options' => $roles,
                //'default' => 'everyone',
                'description' => __('If you want limit visualization to specific user roles', 'dynamic-content-for-elementor'),
                'multiple' => true,
                    /* 'condition' => [
                      'dce_visibility_everyone' => '',
                      ], */
                    ]
            );
            $element->add_control(
                    'dce_visibility_users', [
                'label' => __('Selected Users', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'description' => __('Write here the list of user who will be able to view (or not) this element.<br>You can use their ID, email or username.<br>Simply separate them by a comma. (ex. "23, info@dynamic.ooo, dynamicooo")', 'dynamic-content-for-elementor'),
                'separator' => 'before',
                    ]
            );
            
            $element->add_control(
                    'dce_visibility_can', [
                'label' => __('User can', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'description' => __('Trigger by User capability, for example: "manage_options"', 'dynamic-content-for-elementor'),
                'separator' => 'before',
                    ]
            );

            /*$element->add_control(
                    'dce_visibility_usermeta', [
                'label' => __('User Meta', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                'options' => $user_metas,
                'description' => __('Triggered by a selected User Meta value', 'dynamic-content-for-elementor'),
                'separator' => 'before',
                    ]
            );*/
            $element->add_control(
                    'dce_visibility_usermeta',
                    [
                        'label' => __('User Field', 'dynamic-content-for-elementor'),
                        'type' 		=> 'ooo_query',
                        'placeholder'	=> __( 'Meta key or Name', 'dynamic-content-for-elementor' ),
                        'label_block' 	=> true,
                        'query_type'	=> 'fields',
                        'object_type'	=> 'user',
                        'description' => __('Triggered by a selected User Field value', 'dynamic-content-for-elementor'),
                        'separator' => 'before',
                    ]
            );
            
            
            $element->add_control(
                    'dce_visibility_usermeta_status', [
                'label' => __('User Field Status', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'not' => [
                        'title' => __('Not isset or empty', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-circle-o',
                    ],
                    'isset' => [
                        'title' => __('Valorized', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-dot-circle-o',
                    ],
                    'value' => [
                        'title' => __('Specific value', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-circle',
                    ]
                ],
                'default' => 'isset',
                'toggle' => false,
                'condition' => [
                    'dce_visibility_usermeta!' => '',
                ],
                    ]
            );
            $element->add_control(
                    'dce_visibility_usermeta_value', [
                'label' => __('User Field Value', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'description' => __('The specific value of the User Field', 'dynamic-content-for-elementor'),
                'condition' => [
                    //'dce_visibility_context' => '',
                    'dce_visibility_usermeta!' => '',
                    'dce_visibility_usermeta_status' => 'value',
                ],
                    ]
            );

            $element->add_control(
                    'dce_visibility_ip', [
                'label' => __('Remote IP', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'description' => __('Write here the list of IP who will be able to view this element.<br>Separate IPs by comma. (ex. "123.123.123.123, 8.8.8.8, 4.4.4.4")', 'dynamic-content-for-elementor')
                . '<br><b>' . __('Your current IP is: ', 'dynamic-content-for-elementor') . $_SERVER['REMOTE_ADDR'] . '</b>',
                'separator' => 'before',
                    ]
            );
            $element->add_control(
                    'dce_visibility_referrer', [
                'label' => __('Referrer', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'description' => __('Triggered when previous page is a specific page.', 'dynamic-content-for-elementor'),
                'separator' => 'before',
                    ]
            );
            $element->add_control(
                    'dce_visibility_referrer_list', [
                'label' => __('Specific referral site authorized:', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => 'facebook.com' . PHP_EOL . 'google.com',
                'description' => __('Only selected referral, once per line. If empty it is triggered for all external site.', 'dynamic-content-for-elementor'),
                'condition' => [
                    'dce_visibility_referrer' => 'yes',
                //'dce_visibility_everyone' => '',
                ],
                    ]
            );


            if (DCE_Helper::is_plugin_active('geoip-detect') && function_exists('geoip_detect2_get_info_from_current_ip')) {
                $geoinfo = geoip_detect2_get_info_from_current_ip();
                $countryInfo = new \YellowTree\GeoipDetect\Geonames\CountryInformation();
                $countries = $countryInfo->getAllCountries();
                $element->add_control(
                        'dce_visibility_country', [
                    'label' => __('Country', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT2,
                    'options' => $countries,
                    'description' => __('Trigger visibility for a specific country.', 'dynamic-content-for-elementor'),
                    'multiple' => true,
                    'separator' => 'before',
                        ]
                );
                $element->add_control(
                        'dce_visibility_city', [
                    'label' => __('City', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'description' => __('Write here the name of the city which trigger the condition. Insert the city name translated in one of the supported language (preferable in EN) and don\'t worry about case sensitive. You can insert multiple cities, separated by comma.', 'dynamic-content-for-elementor') . '<br>' . __('Actually you are in:', 'dynamic-content-for-elementor') . ' ' . implode(', ', $geoinfo->city->names),]
                );
            }

            //YellowTree\GeoipDetect\DataSources\City::
            //geoip_detect2_get_info_from_current_ip();
            /* $element->add_control(
              'dce_visibility_referrer_selected', [
              'label' => __('Show/Hide', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SWITCHER,
              'default' => 'yes',
              'label_on' => __('Show', 'dynamic-content-for-elementor'),
              'label_off' => __('Hide', 'dynamic-content-for-elementor'),
              'description' => __('Show or hide by selected referrers.', 'dynamic-content-for-elementor'),
              'condition' => [
              'dce_visibility_referrer' => 'yes',
              'dce_visibility_referrer_list!' => '',
              'dce_visibility_everyone' => '',
              ],
              ]
              ); */
            /* $element->add_control(
              'dce_visibility_user_selected', [
              'label' => __('Show/Hide', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SWITCHER,
              'default' => 'yes',
              'label_on' => __('Show', 'dynamic-content-for-elementor'),
              'label_off' => __('Hide', 'dynamic-content-for-elementor'),
              'return_value' => 'yes',
              'description' => __('Show or hide for selected users.', 'dynamic-content-for-elementor'),
              'condition' => [
              'dce_visibility_everyone' => '',
              ],
              ]
              ); */

            //$element->end_controls_section();
        }

        if ($section == 'device') {
            /* $element->add_control(
              'dce_visibility_device', [
              'label' => __('Visible on Every Device', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SWITCHER,
              'default' => 'yes',
              'description' => __('If enabled element will displayed on every device', 'dynamic-content-for-elementor'),
              ]
              ); */
            $element->add_control(
                    'dce_visibility_responsive', [
                'label' => __('Responsive', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    /* 'all' => [
                      'title' => __('All', 'dynamic-content-for-elementor'),
                      'icon' => 'fa fa-circle-o',
                      ], */
                    'desktop' => [
                        'title' => __('Desktop and Tv', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-desktop',
                    ],
                    'mobile' => [
                        'title' => __('Mobile and Tablet', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-mobile',
                    ]
                ],
                'description' => __('Not really responsive, remove the element from the code based on the user\'s device. This trigger use native WP device detenction.', 'dynamic-content-for-elementor') . ' <a href="https://codex.wordpress.org/Function_Reference/wp_is_mobile" target="_blank">' . __('Read more.', 'dynamic-content-for-elementor') . '</a>',
                    //'default' => 'all',
                    //'toggle' => false,
                    /* 'condition' => [
                      'dce_visibility_device' => '',
                      ], */
                    ]
            );
            $element->add_control(
                    'dce_visibility_browser', [
                'label' => __('Browser', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                'options' => array(
                    'is_chrome' => 'Google Chrome',
                    'is_gecko' => 'FireFox',
                    'is_safari' => 'Safari',
                    'is_IE' => 'Internet Explorer',
                    'is_edge' => 'Microsoft Edge',
                    'is_NS4' => 'Netscape',
                    'is_opera' => 'Opera',
                    'is_lynx' => 'Lynx',
                    'is_iphone' => 'iPhone Safari'
                ),
                'description' => __('Trigger visibility for a specific browser.', 'dynamic-content-for-elementor'),
                'multiple' => true,
                'separator' => 'before',
                    ]
            );
            /* $element->add_control(
              'dce_visibility_device_selected', [
              'label' => __('Show/Hide', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SWITCHER,
              'default' => 'yes',
              'label_on' => __('Show', 'dynamic-content-for-elementor'),
              'label_off' => __('Hide', 'dynamic-content-for-elementor'),
              'return_value' => 'yes',
              'description' => __('Show or hide for selected device.', 'dynamic-content-for-elementor'),
              'condition' => [
              'dce_visibility_device' => '',
              ],
              ]
              ); */
        }

        if ($section == 'datetime') {
            /* $element->add_control(
              'date_visibility_heading', [
              'label' => __('Date & Time', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::HEADING,
              'condition' => [
              'enabled_visibility' => 'yes',
              'dce_visibility_hidden' => '',
              ],
              'separator' => 'before',
              ]
              ); */

            /* $element->add_control(
              'dce_visibility_datetime', [
              'label' => __('Visible EveryTIME', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SWITCHER,
              'default' => 'yes',
              'description' => __('If enabled you can show the element for a specific period.', 'dynamic-content-for-elementor'),
              ]
              ); */

            if (time() != current_time('timestamp')) {
                $element->add_control(
                        'dce_visibility_datetime_important_note', [
                    'label' => '<strong><i class="elementor-dce-datetime-icon eicon-warning"></i> ' . __('ATTENTION', 'dynamic-content-for-elementor') . '</strong>',
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => '<small><br>' . __('Server time and Wordpress time are different.', 'dynamic-content-for-elementor') . '<br>'
                    . __('Will be used the Wordpress time you set in', 'dynamic-content-for-elementor')
                    . ' <a target="_blank" href="' . admin_url('options-general.php') . '">' . __('Wordpress General preferences', 'dynamic-content-for-elementor') . '</a>.<br>'
                    //.__( 'Here actual time on this page load:', 'dynamic-content-for-elementor' ).'<br>'
                    . '<br>'
                    . '<strong>SERVER time:</strong><br>' . date('r') . '<br><br>'
                    . '<strong>WORDPRESS time:</strong><br>' . current_time('r')
                    . '</small>'
                    ,
                    'content_classes' => 'dce-datetime-notice',
                        /* 'condition' => [
                          'dce_visibility_datetime' => ''
                          ], */
                        ]
                );
            }
            
            $element->add_control(
                'dce_visibility_date_dynamic', [
                    'label' => __('Use Dynamic Dates', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                ]
            );
            $element->add_control(
                    'dce_visibility_date_dynamic_from', [
                'label' => __('Date FROM', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'YYYY-mm-dd HH:ii:ss',
                'description' => __('If set the element will appear after this date', 'dynamic-content-for-elementor'),
                'condition' => [
                      'dce_visibility_date_dynamic!' => ''
                      ],
                    ]
            );
            $element->add_control(
                    'dce_visibility_date_dynamic_to', [
                'label' => __('Date TO', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'YYYY-mm-dd HH:ii:ss',
                'description' => __('If set the element will be visible until this date', 'dynamic-content-for-elementor'),
                    'condition' => [
                      'dce_visibility_date_dynamic!' => ''
                      ], 
                    ]
            );
            

            $element->add_control(
                    'dce_visibility_date_from', [
                'label' => __('Date FROM', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DATE_TIME,
                'description' => __('If set the element will appear after this date', 'dynamic-content-for-elementor'),
                'condition' => [
                      'dce_visibility_date_dynamic' => ''
                      ],
                    ]
            );
            $element->add_control(
                    'dce_visibility_date_to', [
                'label' => __('Date TO', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DATE_TIME,
                'description' => __('If set the element will be visible until this date', 'dynamic-content-for-elementor'),
                    'condition' => [
                      'dce_visibility_date_dynamic' => ''
                      ], 
                    ]
            );

            $element->add_control(
                    'dce_visibility_period_from', [
                'label' => __('Period FROM', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'description' => __('If set the element will appear after this period', 'dynamic-content-for-elementor'),
                'placeholder' => 'mm/dd',
                'separator' => 'before',
                    ]
            );
            $element->add_control(
                    'dce_visibility_period_to', [
                'label' => __('Period TO', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'mm/dd',
                'description' => __('If set the element will be visible until this period', 'dynamic-content-for-elementor'),
                    ]
            );

            global $wp_locale;
            $week = array();
            for ($day_index = 0; $day_index <= 6; $day_index++) {
                $week[esc_attr($day_index)] = $wp_locale->get_weekday($day_index);
            }
            $element->add_control(
                    'dce_visibility_time_week', [
                'label' => __('Days of the WEEK', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                'options' => $week,
                'description' => __('Select days in the week.', 'dynamic-content-for-elementor'),
                'multiple' => true,
                'separator' => 'before',
                    ]
            );


            $element->add_control(
                    'dce_visibility_time_from', [
                'label' => __('Time FROM', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'HH:mm',
                'description' => __('If setted (in H:m format) the element will appear after this time.', 'dynamic-content-for-elementor'),
                'separator' => 'before',
                    ]
            );
            $element->add_control(
                    'dce_visibility_time_to', [
                'label' => __('Time TO', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'HH:mm',
                'description' => __('If setted (in H:m format) the element will be visible until this time', 'dynamic-content-for-elementor'),
                    /* 'condition' => [
                      'dce_visibility_datetime' => ''
                      ], */
                    ]
            );
            /* $element->add_control(
              'dce_visibility_datetime_selected', [
              'label' => __('Show/Hide', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SWITCHER,
              'default' => 'yes',
              'label_on' => __('Show', 'dynamic-content-for-elementor'),
              'label_off' => __('Hide', 'dynamic-content-for-elementor'),
              'return_value' => 'yes',
              'description' => __('Show or hide for selected datetime.', 'dynamic-content-for-elementor'),
              'condition' => [
              'dce_visibility_datetime' => '',
              ],
              ]
              ); */
        }

        if ($section == 'context') {
            if (defined('DVE_PLUGIN_BASE')) { //  Feature not present in FREE version
                $element->add_control(
                        'dce_visibility_context_hide', [
                    'label' => __('Only in PRO', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => '<style>.elementor-control-dce_section_visibility_context { display: none !important; }</style>',
                        ]
                );
            } else {
                /* $element->add_control(
                  'post_visibility_heading', [
                  'label' => __('Context', 'dynamic-content-for-elementor'),
                  'type' => Controls_Manager::HEADING,
                  'condition' => [
                  'enabled_visibility' => 'yes',
                  'dce_visibility_hidden' => '',
                  ],
                  'separator' => 'before',
                  ]
                  ); */
                /* $element->add_control(
                  'dce_visibility_context', [
                  'label' => __('Visible EveryWHERE', 'dynamic-content-for-elementor'),
                  'type' => Controls_Manager::SWITCHER,
                  'default' => 'yes',
                  'description' => __("If you want show something only when it's in a specific page.", 'dynamic-content-for-elementor') . '<br><strong>' . __("Very useful if you are using a Template System.", 'dynamic-content-for-elementor') . '</strong>',
                  ]
                  ); */
                $element->add_control(
                        'dce_visibility_parameter', [
                    'label' => __('Parameter', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'description' => __('Write here the name of the parameter passed in GET or POST method', 'dynamic-content-for-elementor'),
                        /* 'condition' => [
                          'dce_visibility_context' => '',
                          ], */
                        ]
                );
                $element->add_control(
                        'dce_visibility_parameter_status', [
                    'label' => __('Parameter Status', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'not' => [
                            'title' => __('Not isset', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-circle-o',
                        ],
                        'isset' => [
                            'title' => __('Isset', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-dot-circle-o',
                        ],
                        'value' => [
                            'title' => __('Definited value', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-circle',
                        ]
                    ],
                    'default' => 'isset',
                    'toggle' => false,
                    'condition' => [
                        //'dce_visibility_context' => '',
                        'dce_visibility_parameter!' => '',
                    ],
                        ]
                );
                $element->add_control(
                        'dce_visibility_parameter_value', [
                    'label' => __('Parameter Value', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'description' => __('The specific value of the parameter', 'dynamic-content-for-elementor'),
                    'condition' => [
                        //'dce_visibility_context' => '',
                        'dce_visibility_parameter!' => '',
                        'dce_visibility_parameter_status' => 'value',
                    ],
                        ]
                );
                
                
                $element->add_control(
                    'dce_visibility_conditional_tags_site', [
                'label' => __('Site', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                'options' => [
                    'is_dynamic_sidebar' => __('Dynamic sidebar', 'dynamic-content-for-elementor'),
                    'is_active_sidebar' => __('Active sidebar', 'dynamic-content-for-elementor'),
                    'is_rtl' => __('RTL', 'dynamic-content-for-elementor'),
                    'is_multisite' => __('Multisite', 'dynamic-content-for-elementor'),
                    'is_main_site' => __('Main site', 'dynamic-content-for-elementor'),
                    'is_child_theme' => __('Child theme', 'dynamic-content-for-elementor'),
                    'is_customize_preview' => __('Customize preview', 'dynamic-content-for-elementor'),
                    'is_multi_author' => __('Multi author', 'dynamic-content-for-elementor'),
                    'is feed' => __('Feed', 'dynamic-content-for-elementor'),
                    'is_trackback' => __('Trackback', 'dynamic-content-for-elementor'),
                ],
                'multiple' => true,
                'separator' => 'before',
                    ]
            );

                /* $element->add_control(
                  'dce_visibility_max_user',
                  [
                  'label' => __('Max per User', 'dynamic-content-for-elementor'),
                  'type' => \Elementor\Controls_Manager::NUMBER,
                  'min' => 0,
                  ]
                  ); */
                $element->add_control(
                        'dce_visibility_max_day',
                        [
                            'label' => __('Max per Day', 'dynamic-content-for-elementor'),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'min' => 1,
                            'separator' => 'before',
                        ]
                );
                $element->add_control(
                        'dce_visibility_max_total',
                        [
                            'label' => __('Max Total', 'dynamic-content-for-elementor'),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'min' => 1,
                            'separator' => 'before',
                        ]
                );

                /* $element->add_control(
                  'dce_visibility_context_selected', [
                  'label' => __('Show/Hide', 'dynamic-content-for-elementor'),
                  'type' => Controls_Manager::SWITCHER,
                  'default' => 'yes',
                  'label_on' => __('Show', 'dynamic-content-for-elementor'),
                  'label_off' => __('Hide', 'dynamic-content-for-elementor'),
                  'description' => __('Hide or show in selected context.', 'dynamic-content-for-elementor'),
                  'condition' => [
                  'dce_visibility_context' => '',
                  ],
                  ]
                  ); */

                /* $element->add_control(
                  'dce_visibility_meta_selected', [
                  'label' => __('Show/Hide', 'dynamic-content-for-elementor'),
                  'type' => Controls_Manager::SWITCHER,
                  'default' => 'yes',
                  'label_on' => __('Hide', 'dynamic-content-for-elementor'),
                  'label_off' => __('Show', 'dynamic-content-for-elementor'),
                  'return_value' => 'yes',
                  'description' => __('Visible or hidden by selected meta.', 'dynamic-content-for-elementor'),
                  'condition' => [
                  'enabled_visibility' => 'yes',
                  'dce_visibility_hidden' => '',
                  'dce_visibility_meta!' => '',
                  ],
                  ]
                  ); */
                
                $select_lang = array();
                // WPML
                global $sitepress;
                if (!empty($sitepress)) {
                    //$current_language = $sitepress->get_current_language();
                    //$default_language = $sitepress->get_default_language();                    
                    $langs = $sitepress->get_ls_languages();
                    //var_dump($langs); die();
                    if (!empty($langs)) {
                        foreach ($langs as $lkey => $lvalue) {
                            $select_lang[$lkey] = $lvalue['native_name'];
                        }
                    }
                }
                // POLYLANG
                if (DCE_Helper::is_plugin_active('polylang') && function_exists('pll_languages_list')) {
                    $translations = pll_languages_list();
                    $translations_name = pll_languages_list(array('fields' => 'name'));
                    //var_dump($translations); die();
                    if (!empty($translations)) {
                        foreach ($translations as $tkey => $tvalue) {
                            $select_lang[$tvalue] = $translations_name[$tkey];
                        }
                    }
                }
                // TRANSLATEPRESS
                if (DCE_Helper::is_plugin_active('translatepress-multilingual')) {
                    $settings = get_option('trp_settings');
                    if ($settings && is_array($settings) && isset($settings['publish-languages'])) {
                        $languages = $settings['publish-languages'];
                        $trp = \TRP_Translate_Press::get_trp_instance(); 
                        $trp_languages = $trp->get_component( 'languages' );
                        $published_languages = $trp_languages->get_language_names( $languages, 'english_name' );
                        $select_lang = $published_languages;
                    }
                }
                
                if (!empty($select_lang)) {
                    $element->add_control(
                            'dce_visibility_lang', [
                        'label' => __('Language', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SELECT2,
                        'options' => $select_lang,
                        'multiple' => true,
                        'separator' => 'before',
                            ]
                    );
                }
                 
            }
        }

        if ($section == 'post') {
            if (defined('DVE_PLUGIN_BASE')) { //  Feature not present in FREE version
                $element->add_control(
                        'dce_visibility_curent_post_hide', [
                    'label' => __('Only in PRO', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => '<style>.elementor-control-dce_section_visibility_context { display: none !important; }</style>',
                        ]
                );
            } else {
                
                $element->add_control(
                        'dce_visibility_post_id', [
                    'label' => __('Post ID', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'current' => [
                            'title' => __('Current', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-list',
                        ],
                        'global' => [
                            'title' => __('Global', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-globe',
                        ],
                        'static' => [
                            'title' => __('Static', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-pencil',
                        ]
                    ],
                    'default' => 'current',
                    'toggle' => false,
                        ]
                );
                $element->add_control(
                        'dce_visibility_post_id_static',
                        [
                            'label' => __('Set Post ID', 'dynamic-content-for-elementor'),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'min' => 1,
                            'condition' => [
                                'dce_visibility_post_id' => 'static',
                            ],
                        ]
                );
                $element->add_control(
                        'dce_visibility_post_id_description', [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => '<small>'.__('NOTE: in some case Current and Global may be different. For example if you put a Widget with a Loop in a Page then Global ID will be Page ID and Current ID will be Post ID in preview inside the Loop.', 'dynamic-content-for-elementor').'</small>',
                        ]
                );
                
                $element->add_control(
                        'dce_visibility_cpt', [
                    'label' => __('Post Type', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT2,
                    'options' => $post_types,
                    'description' => __('Visible if current post is one of this Post Type.', 'dynamic-content-for-elementor'),
                    'multiple' => true,
                    'separator' => 'before',
                        ]
                );
                /*$element->add_control(
                        'dce_visibility_post', [
                    'label' => __('Page/Post', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT2,
                    'options' => $all_posts,
                    'description' => __('Visible if current post is one of this Page/Posts.', 'dynamic-content-for-elementor'),
                    'multiple' => true,
                    'separator' => 'before',
                        ]
                );*/
                $element->add_control(
                        'dce_visibility_post',
                        [
                            'label' => __('Page/Post', 'dynamic-content-for-elementor'),
                            'type' 		=> 'ooo_query',
                            'placeholder'	=> __( 'Post Title', 'dynamic-content-for-elementor' ),
                            'label_block' 	=> true,
                            'query_type'	=> 'posts',
                            'description' => __('Visible if current post is one of this Page/Posts.', 'dynamic-content-for-elementor'),
                            'multiple' => true,
                            'separator' => 'before',
                        ]
                );

                $element->add_control(
                        'dce_visibility_tax', [
                    'label' => __('Taxonomy', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT2,
                    'options' => $taxonomies,
                    'description' => __('Triggered if current post is related with this Taxonomy.', 'dynamic-content-for-elementor'),
                    'multiple' => false,
                    'separator' => 'before',
                        ]
                );

                foreach ($taxonomies as $tkey => $atax) {
                    if ($tkey) {
                        /*$element->add_control(
                                'dce_visibility_term_' . $tkey, [
                            'label' => __('Terms', 'dynamic-content-for-elementor'), //.' '.$atax,
                            'type' => Controls_Manager::SELECT2,
                            //'groups' => \DynamicContentForElementor\DCE_Helper::get_taxonomies_terms(),
                            'options' => $taxonomies_terms[$tkey],
                            'description' => __('Visible if current post is related with this Terms.', 'dynamic-content-for-elementor'),
                            'multiple' => true,
                            'condition' => [
                                //'dce_visibility_context' => '',
                                'dce_visibility_tax' => $tkey,
                            ],
                                ]
                        );*/
                        $element->add_control(
                                'dce_visibility_term_' . $tkey,
                                [
                                    'label' => __('Terms', 'dynamic-content-for-elementor'),
                                    'type' 		=> 'ooo_query',
                                    'placeholder'	=> __( 'Term Name', 'dynamic-content-for-elementor' ),
                                    'label_block' 	=> true,
                                    'query_type'	=> 'terms',
                                    'object_type'	=> $tkey,
                                    'description' => __('Visible if current post is related with this Terms.', 'dynamic-content-for-elementor'),
                                    'multiple' => true,
                                    'condition' => [
                                        'dce_visibility_tax' => $tkey,
                                    ],
                                ]
                        );
                    }
                }

                /*$element->add_control(
                        'dce_visibility_field', [
                    'label' => __('Meta Field', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT2,
                    'options' => $post_metas,
                    'description' => __('Triggered by a selected Post Meta value', 'dynamic-content-for-elementor'),
                    'separator' => 'before',
                        ]
                );*/
                $element->add_control(
                        'dce_visibility_field',
                        [
                            'label' => __('Meta Field', 'dynamic-content-for-elementor'),
                            'type' 		=> 'ooo_query',
                            'placeholder'	=> __( 'Meta key or Name', 'dynamic-content-for-elementor' ),
                            'label_block' 	=> true,
                            'query_type'	=> 'metas',
                            'object_type'	=> 'post',
                            'description' => __('Triggered by a selected Post Meta value', 'dynamic-content-for-elementor'),
                            'separator' => 'before',
                        ]
                );
                
                $element->add_control(
                        'dce_visibility_field_status', [
                    'label' => __('Post Field Status', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'not' => [
                            'title' => __('Not isset or empty', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-circle-o',
                        ],
                        'isset' => [
                            'title' => __('Valorized', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-dot-circle-o',
                        ],
                        'value' => [
                            'title' => __('Specific value', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-circle',
                        ]
                    ],
                    'default' => 'isset',
                    'toggle' => false,
                    'condition' => [
                        'dce_visibility_field!' => '',
                    ],
                        ]
                );
                $element->add_control(
                        'dce_visibility_field_value', [
                    'label' => __('Post Meta Value', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'description' => __('The specific value of the Post Meta', 'dynamic-content-for-elementor'),
                    'condition' => [
                        //'dce_visibility_context' => '',
                        'dce_visibility_field!' => '',
                        'dce_visibility_field_status' => 'value',
                    ],
                        ]
                );

                /*$element->add_control(
                        'dce_visibility_meta', [
                    'label' => __('Metas', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT2,
                    'options' => $post_metas,
                    'description' => __('Triggered by specifics metas fields if they are valorized.', 'dynamic-content-for-elementor'),
                    'multiple' => true,
                    'separator' => 'before',
                        ]
                );*/
                $element->add_control(
                        'dce_visibility_meta',
                        [
                            'label' => __('Metas', 'dynamic-content-for-elementor'),
                            'type' 		=> 'ooo_query',
                            'placeholder'	=> __( 'Meta key or Name', 'dynamic-content-for-elementor' ),
                            'label_block' 	=> true,
                            'query_type'	=> 'metas',
                            'object_type'	=> 'post',
                            'description' => __('Triggered by specifics metas fields if they are valorized.', 'dynamic-content-for-elementor'),
                            'multiple' => true,
                            'separator' => 'before',
                        ]
                );
                
                
                $element->add_control(
                        'dce_visibility_meta_operator', [
                    'label' => __('Meta conditions', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'label_on' => __('And', 'dynamic-content-for-elementor'),
                    'label_off' => __('Or', 'dynamic-content-for-elementor'),
                    'description' => __('How post meta have to satisfy this conditions.', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'dce_visibility_meta!' => '',
                    //'dce_visibility_context' => '',
                    ],
                        ]
                );

                $element->add_control(
                        'dce_visibility_format', [
                    'label' => __('Format', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT2,
                    'options' => DCE_Helper::get_post_formats(),
                    'description' => __('Triggered if current post is setted as one of this format.', 'dynamic-content-for-elementor') . '<br><a href="https://wordpress.org/support/article/post-formats/" target="_blank">' . __('Read more on Post Format.', 'dynamic-content-for-elementor') . '</a>',
                    'multiple' => true,
                    'separator' => 'before',
                        ]
                );

                $element->add_control(
                        'dce_visibility_parent', [
                    'label' => __('Is Parent', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'description' => __('Triggered for post with children.', 'dynamic-content-for-elementor'),
                    'separator' => 'before',
                        ]
                );
                $element->add_control(
                        'dce_visibility_root', [
                    'label' => __('Is Root', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'description' => __('Triggered for post of first level (without parent).', 'dynamic-content-for-elementor'),
                    'separator' => 'before',
                        ]
                );
                $element->add_control(
                        'dce_visibility_leaf', [
                    'label' => __('Is Leaf', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'description' => __('Triggered for post of last level (without children).', 'dynamic-content-for-elementor'),
                    'separator' => 'before',
                        ]
                );
                $element->add_control(
                        'dce_visibility_node', [
                    'label' => __('Is Node', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'description' => __('Triggered for post of intermedial level (with parent and child).', 'dynamic-content-for-elementor'),
                    'separator' => 'before',
                        ]
                );
                $element->add_control(
                        'dce_visibility_node_level',
                        [
                            'label' => __('Node level', 'dynamic-content-for-elementor'),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'min' => 1,
                            'condition' => [
                                'dce_visibility_node!' => '',
                            ],
                        ]
                );
                $element->add_control(
                    'dce_visibility_level',
                    [
                        'label' => __('Has Level', 'dynamic-content-for-elementor'),
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        'min' => 1,
                        'description' => __('Triggered for post has specific level.', 'dynamic-content-for-elementor'),
                        'separator' => 'before',
                    ]
                );
                $element->add_control(
                        'dce_visibility_child', [
                    'label' => __('Has Parent', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'description' => __('Triggered for posts which are children (with a parent).', 'dynamic-content-for-elementor'),
                    'separator' => 'before',
                        ]
                );
                /*$element->add_control(
                        'dce_visibility_child_parent',
                        [
                            'label' => __('Spercific Parent Post ID', 'dynamic-content-for-elementor'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            //'min' => 1,
                            'description' => __('Specify the ID (or multiple separated by comma) of a Post, all his children will be trigger. Otherwise leave blank for a generic parent.', 'dynamic-content-for-elementor'),
                            'condition' => [
                                'dce_visibility_child!' => '',
                            ],
                        ]
                );*/
                $element->add_control(
                        'dce_visibility_child_parent',
                        [
                            'label' => __('Spercific Parent Post ID', 'dynamic-content-for-elementor'),
                            'type' 		=> 'ooo_query',
                            'placeholder'	=> __( 'Post Title', 'dynamic-content-for-elementor' ),
                            'label_block' 	=> true,
                            'query_type'	=> 'posts',
                            'description' => __('Specify the ID (or multiple separated by comma) of a Post, all his children will be trigger. Otherwise leave blank for a generic parent.', 'dynamic-content-for-elementor'),
                            'condition' => [
                                'dce_visibility_child!' => '',
                            ],
                        ]
                );
                
                
                $element->add_control(
                        'dce_visibility_sibling', [
                    'label' => __('Has Siblings', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'description' => __('Triggered for post with siblings.', 'dynamic-content-for-elementor'),
                    'separator' => 'before',
                        ]
                );
                $element->add_control(
                        'dce_visibility_friend', [
                    'label' => __('Has Term Buddies', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'description' => __('Triggered for post grouped in taxonomies with other posts.', 'dynamic-content-for-elementor'),
                    'separator' => 'before',
                        ]
                );
                /*$element->add_control(
                        'dce_visibility_friend_term', [
                    'label' => __('Terms where find Buddies', 'dynamic-content-for-elementor'), //.' '.$atax,
                    'type' => Controls_Manager::SELECT2,
                    //'groups' => \DynamicContentForElementor\DCE_Helper::get_taxonomies_terms(),
                    'options' => $all_taxonomies_terms,
                    'description' => __('Specific a Term for current post has friends.', 'dynamic-content-for-elementor'),
                    'multiple' => true,
                    'label_block' => true,
                    'condition' => [
                        'dce_visibility_friend!' => '',
                    ],
                        ]
                );*/
                $element->add_control(
                        'dce_visibility_friend_term',
                        [
                            'label' => __('Terms where find Buddies', 'dynamic-content-for-elementor'), //.' '.$atax,
                            'type' 		=> 'ooo_query',
                            'placeholder'	=> __( 'Term Name', 'dynamic-content-for-elementor' ),
                            'label_block' 	=> true,
                            'query_type'	=> 'terms',
                            'description' => __('Specific a Term for current post has friends.', 'dynamic-content-for-elementor'),
                            'multiple' => true,
                            'label_block' => true,
                            'condition' => [
                                'dce_visibility_friend!' => '',
                            ],
                        ]
                );
                
                $element->add_control(
                    'dce_visibility_conditional_tags_post', [
                    'label' => __('Conditional Tags - Post', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT2,
                    'options' => [
                        'is_sticky' => __('Is Sticky', 'dynamic-content-for-elementor'),
                        'is_post_type_hierarchical' => __('Is Hierarchical Post Type', 'dynamic-content-for-elementor'),
                        'is_post_type_archive' => __('Is Post Type Archive', 'dynamic-content-for-elementor'),
                        'comments_open' => __('Comments open', 'dynamic-content-for-elementor'),
                        'pings_open' => __('Pings open', 'dynamic-content-for-elementor'),
                        'has_tag' => __('Has Tags', 'dynamic-content-for-elementor'),
                        'has_term' => __('Has Terms', 'dynamic-content-for-elementor'),
                        'has_excerpt' => __('Has Excerpt', 'dynamic-content-for-elementor'),
                        'has_post_thumbnail' => __('Has Post Thumbnail', 'dynamic-content-for-elementor'),
                        'has_nav_menu' => __('Has Nav menu', 'dynamic-content-for-elementor'),
                    ],
                    'multiple' => true,
                    'separator' => 'before',
                    'condition' => [
                        'dce_visibility_post_id' => 'current',
                    ],
                        ]
                );
                $element->add_control(
                    'dce_visibility_special', [
                    'label' => __('Conditonal Tags - Page', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT2,
                    'options' => [
                        'is_front_page' => __('Front Page', 'dynamic-content-for-elementor'),
                        'is_home' => __('Home', 'dynamic-content-for-elementor'),
                        'is_404' => __('404 Not Found', 'dynamic-content-for-elementor'),
                        'is_single' => __('Single', 'dynamic-content-for-elementor'),
                        'is_page' => __('Page', 'dynamic-content-for-elementor'),
                        'is_attachment' => __('Attachment', 'dynamic-content-for-elementor'),
                        'is_preview' => __('Preview', 'dynamic-content-for-elementor'),
                        'is_admin' => __('Admin', 'dynamic-content-for-elementor'),
                        'is_page_template' => __('Page Template', 'dynamic-content-for-elementor'),
                        'is_comments_popup' => __('Comments Popup', 'dynamic-content-for-elementor'),
                        /*
                          'static' => __('Static', 'dynamic-content-for-elementor'),
                          'login' => __('Login', 'dynamic-content-for-elementor'),
                          'registration' => __('Registration', 'dynamic-content-for-elementor'),
                          'profile' => __('Profile', 'dynamic-content-for-elementor'),
                         */
                        // woocommerce
                        'is_woocommerce' => __('A Woocommerce Page', 'dynamic-content-for-elementor'),
                        'is_shop' => __('Shop', 'dynamic-content-for-elementor'),
                        'is_product' => __('Product', 'dynamic-content-for-elementor'),
                        'is_product_taxonomy' => __('Product Taxonomy', 'dynamic-content-for-elementor'),
                        'is_product_category' => __('Product Category', 'dynamic-content-for-elementor'),
                        'is_product_tag' => __('Product Tag', 'dynamic-content-for-elementor'),
                        'is_cart' => __('Cart', 'dynamic-content-for-elementor'),
                        'is_checkout' => __('Checkout', 'dynamic-content-for-elementor'),
                        'is_add_payment_method_page' => __('Add Payment method', 'dynamic-content-for-elementor'),
                        'is_checkout_pay_page' => __('Checkout Pay', 'dynamic-content-for-elementor'),
                        'is_account_page' => __('Account page', 'dynamic-content-for-elementor'),
                        'is_edit_account_page' => __('Edit Account', 'dynamic-content-for-elementor'),
                        'is_lost_password_page' => __('Lost password', 'dynamic-content-for-elementor'),
                        'is_view_order_page' => __('Order summary', 'dynamic-content-for-elementor'),
                        'is_order_received_page' => __('Order complete', 'dynamic-content-for-elementor'),
                    ],
                    'multiple' => true,
                    'separator' => 'before',
                    'condition' => [
                        'dce_visibility_post_id' => 'current',
                    ],
                        ]
                );
            }
        }

        if ($section == 'events') {
            
            $element->add_control(
                        'dce_visibility_events_note', [
                    'label' => '<strong><i class="elementor-dce-datetime-icon eicon-warning"></i> ' . __('ATTENTION', 'dynamic-content-for-elementor') . '</strong>',
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => '<small><br>' . __('To use an Event trigger is necessary to activate ', 'dynamic-content-for-elementor'). '<strong>' . __('Keep HTML', 'dynamic-content-for-elementor') . '</strong>' . __(' from base settings', 'dynamic-content-for-elementor') . '</small>',
                    'content_classes' => 'dce-datetime-notice',
                        'condition' => [
                          'dce_visibility_dom' => '',
                          ],
                    ]
                );
            
            $element->add_control(
                'dce_visibility_click',
                [
                    'label' => __('On Click', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'description' => __('Write here the Selector in jQuery format of the Button which will toggle selected Element.', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'dce_visibility_dom!' => '',
                    ],
                ]
            );
            $element->add_control(
                'dce_visibility_click_show', [
                    'label' => __('Show Animation', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => DCE_Helper::get_jquery_display_mode(),
                    'condition' => [
                        'dce_visibility_dom!' => '',
                        'dce_visibility_click!' => '',
                    ],
                ]
            );
            $element->add_control(
                'dce_visibility_click_other',
                [
                    'label' => __('Hide other elements', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'description' => __('Write here the Selector in jQuery format.', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'dce_visibility_dom!' => '',
                        'dce_visibility_click!' => '',
                    ],
                ]
            );
            /*$element->add_control(
                'dce_visibility_click_hide', [
                    'label' => __('Hide Animation', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => DCE_Helper::get_jquery_display_mode(),
                    'condition' => [
                        'dce_visibility_dom!' => '',
                        'dce_visibility_click!' => '',
                    ],
                ]
            );*/
            $element->add_control(
                'dce_visibility_click_toggle', [
                    'label' => __('Toggle', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'dce_visibility_dom!' => '',
                        'dce_visibility_click!' => '',
                    ],
                ]
            );
            
            
            $element->add_control(
                'dce_visibility_load', [
                    'label' => __('On Page Load', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'dce_visibility_dom!' => '',
                    ],
                    'separator' => 'before'
                ]
            );
            $element->add_control(
                'dce_visibility_load_delay', [
                    'label' => __('Delay time', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'default' => 0,
                    'condition' => [
                        'dce_visibility_dom!' => '',
                        'dce_visibility_load!' => '',
                    ],
                ]
            );
            $element->add_control(
                'dce_visibility_load_show', [
                    'label' => __('Show Animation', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => DCE_Helper::get_jquery_display_mode(),
                    'condition' => [
                        'dce_visibility_dom!' => '',
                        'dce_visibility_load!' => '',
                    ],
                ]
            );
        }
        
        if ($section == 'archive') {
            /* $element->add_control(
              'tags_visibility_heading', [
              'label' => __('Conditional Tags', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::HEADING,
              'condition' => [
              'enabled_visibility' => 'yes',
              'dce_visibility_hidden' => '',
              ],
              'separator' => 'before',
              ]
              ); */
            /* $element->add_control(
              'dce_visibility_tags', [
              'label' => __('Visible UNconditionally', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SWITCHER,
              'default' => 'yes',
              'description' => __('You can use Conditional Tags rule to decide to show your element.', 'dynamic-content-for-elementor').'<a href="https://codex.wordpress.org/Conditional_Tags" target="_blank">' . '<br>'. __('Read more on WordPress related page.', 'dynamic-content-for-elementor').'</a>',
              ]
              ); */
            
            /*$element->add_control(
                    'dce_visibility_tags_intro', [
                'label' => '<b>' . __('What\'s Conditional Tags?', 'dynamic-content-for-elementor') . '</b>',
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('You can use native Wordpress Conditional Tags to decide when show your element.', 'dynamic-content-for-elementor')
                . '<br>' . __('Don\'t you know them?', 'dynamic-content-for-elementor') . ' <a href="https://codex.wordpress.org/Conditional_Tags" target="_blank">' . __('Read more on WordPress Codex related page.', 'dynamic-content-for-elementor') . '</a>',
                    ]
            );*/
            // https://codex.wordpress.org/Conditional_Tags
            
            
            // https://codex.wordpress.org/Special:SpecialPages
            
            $element->add_control(
                    'dce_visibility_archive', [
                'label' => __('Archive Type', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                'options' => [
                    'is_blog' => __('Home blog (latest posts)', 'dynamic-content-for-elementor'),
                    'posts_page' => __('Posts page', 'dynamic-content-for-elementor'),
                    'is_tax' => __('Taxonomy', 'dynamic-content-for-elementor'),
                    'is_category' => __('Category', 'dynamic-content-for-elementor'),
                    'is_tag' => __('Tag', 'dynamic-content-for-elementor'),
                    'is_author' => __('Author', 'dynamic-content-for-elementor'),
                    'is_date' => __('Date', 'dynamic-content-for-elementor'),
                    'is_year' => __('Year', 'dynamic-content-for-elementor'),
                    'is_month' => __('Month', 'dynamic-content-for-elementor'),
                    'is_day' => __('Day', 'dynamic-content-for-elementor'),
                    'is_time' => __('Time', 'dynamic-content-for-elementor'),
                    'is_new_day' => __('New Day', 'dynamic-content-for-elementor'),
                    'is_search' => __('Search', 'dynamic-content-for-elementor'),
                    'is_paged' => __('Paged', 'dynamic-content-for-elementor'),
                    'is_main_query' => __('Main Query', 'dynamic-content-for-elementor'),
                    'in_the_loop' => __('In the Loop', 'dynamic-content-for-elementor'),
                ],
                //'multiple' => true,
                'separator' => 'before',
                    ]
            );
            
            // TODO: specify what Category, Tag or CustomTax
            $element->add_control(
                        'dce_visibility_archive_tax', [
                    'label' => __('Taxonomy', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT2,
                    'options' => $taxonomies,
                    'description' => __('Triggered if current post is related with this Taxonomy.', 'dynamic-content-for-elementor'),
                    'multiple' => false,
                    'separator' => 'before',
                    'condition' => array(
                            'dce_visibility_archive' => 'is_tax',
                        ),
                    ]
                );

                foreach ($taxonomies as $tkey => $atax) {
                    if ($tkey) {
                        switch ($tkey) {
                            case 'post_tag':
                                $condition = array(
                                    'dce_visibility_archive' => 'is_tag',
                                );
                                break;
                            case 'category':
                                $condition = array(
                                    'dce_visibility_archive' => 'is_category',
                                );
                                break;
                            default:
                                $condition = array(
                                    'dce_visibility_archive' => 'is_tax',
                                    'dce_visibility_archive_tax' => $tkey,
                                );
                        }
                        $element->add_control(
                                'dce_visibility_archive_term_' . $tkey,
                                [
                                    'label' => $atax.' '.__('Terms', 'dynamic-content-for-elementor'),
                                    'type' 		=> 'ooo_query',
                                    'placeholder'	=> __( 'Term Name', 'dynamic-content-for-elementor' ),
                                    'label_block' 	=> true,
                                    'query_type'	=> 'terms',
                                    'object_type'	=> $tkey,
                                    'description' => __('Visible if current post is related with this Terms.', 'dynamic-content-for-elementor'),
                                    'multiple' => true,
                                    'condition' => $condition,
                                ]
                        );
                    }
                }

            /* $element->add_control(
              'dce_visibility_tags_selected', [
              'label' => __('Show/Hide', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SWITCHER,
              'default' => 'yes',
              'label_on' => __('Show', 'dynamic-content-for-elementor'),
              'label_off' => __('Hide', 'dynamic-content-for-elementor'),
              'description' => __('Hide or show in selected tags.', 'dynamic-content-for-elementor'),
              'condition' => [
              'dce_visibility_tags' => '',
              ],
              ]
              ); */
        }

        if ($section == 'random') {
            $element->add_control(
                    'dce_visibility_random',
                    [
                        'label' => __('Random', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => ['%'],
                        'range' => [
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                    ]
            );
        }

        if ($section == 'custom') {
            if (defined('DVE_PLUGIN_BASE')) { //  Feature not present in FREE version
                $element->add_control(
                        'dce_visibility_custom_hide', [
                    'label' => __('Only in PRO', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => '<style>.elementor-control-dce_section_visibility_custom { display: none !important; }</style>',
                        ]
                );
            } else {
                /* $element->add_control(
                  'php_visibility_heading', [
                  'label' => __('Custom Condition', 'dynamic-content-for-elementor'),
                  'type' => Controls_Manager::HEADING,
                  'condition' => [
                  'enabled_visibility' => 'yes',
                  'dce_visibility_hidden' => '',
                  ],
                  'separator' => 'before',
                  ]
                  ); */
                /* $element->add_control(
                  'dce_visibility_custom_condition', [
                  'label' => __('Visible Always', 'dynamic-content-for-elementor'),
                  'type' => Controls_Manager::SWITCHER,
                  'default' => 'yes',
                  'description' => __("By a your handwritten advanced custom condition.", 'dynamic-content-for-elementor'),
                  ]
                  ); */
                $element->add_control(
                        'dce_visibility_custom_condition_php', [
                    'label' => __('Custom PHP condition', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CODE,
                    //'placeholder' => 'return true;',
                    'default' => 'return true;',
                    'description' => __('Write here a function that return a boolean value. You can use all WP variabile and functions.', 'dynamic-content-for-elementor'),
                        /* 'condition' => [
                          'dce_visibility_custom_condition' => '',
                          ], */
                        ]
                );
                $element->add_control(
                        'dce_visibility_custom_condition_secure', [
                    'label' => __('Prevent errors', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'description' => __('Execute code externally in secure mode without throw possible FATAL error.', 'dynamic-content-for-elementor')
                    . '<br><strong>' . __("NOTE", 'dynamic-content-for-elementor') . '</strong>: ' . __("if you want access to current page data and context you need to disable it.", 'dynamic-content-for-elementor')
                    . '<br><strong>' . __("WARNING: if it's disabled a wrong code can broke this page, check if code is correct before saving.", 'dynamic-content-for-elementor') . '</strong>',
                        /* 'condition' => [
                          'dce_visibility_custom_condition' => '',
                          ], */
                        ]
                );
                /* $element->add_control(
                  'dce_visibility_custom_condition_selected', [
                  'label' => __('Show/Hide', 'dynamic-content-for-elementor'),
                  'type' => Controls_Manager::SWITCHER,
                  'default' => 'yes',
                  'label_on' => __('Show', 'dynamic-content-for-elementor'),
                  'label_off' => __('Hide', 'dynamic-content-for-elementor'),
                  'description' => __('Hide or show by custom condition.', 'dynamic-content-for-elementor'),
                  'condition' => [
                  'dce_visibility_custom_condition' => '',
                  ],
                  ]
                  ); */
            }
        }

        /*
          if ($section == 'repeater') {

          $repeater_fields = new \Elementor\Repeater();
          $repeater_fields->add_control(
          'dce_visibility_repeater_trigger', [
          'label' => __('Trigger', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SELECT,
          'options' => self::$triggers,
          ]
          );

          $element->add_control(
          'dce_visibility_repeater', [
          'label' => __('Add trigger', 'dynamic-content-for-elementor'),
          'type' => \Elementor\Controls_Manager::REPEATER,
          'fields' => $repeater_fields->get_controls(),
          ]
          );

          $element->add_control(
          'dce_visibility_repeater_expression', [
          'label' => __('Parameter Value', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::TEXT,
          'placeholder' => '((1) AND (2)) OR (3)',
          'description' => __('The combination of selected trigger', 'dynamic-content-for-elementor'),
          ]
          );
          }
         */

        if ($section == 'fallback') {
            /* $element->add_control(
              'fallback_visibility_heading', [
              'label' => __('Fallback', 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::HEADING,
              'separator' => 'before',
              'condition' => [
              'enabled_visibility' => 'yes',
              ],
              ]
              ); */
            $element->add_control(
                    'dce_visibility_fallback', [
                'label' => __('Enable a Fallback Content', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'description' => __("If you want to show something when the element is hidden", 'dynamic-content-for-elementor'),
                    ]
            );
            if (defined('DVE_PLUGIN_BASE')) { // free version not support template shortcode
                $element->add_control(
                        'dce_visibility_fallback_type', [
                    'label' => __('Content type', 'dynamic-content-for-elementor'),
                    'type' => \Elementor\Controls_Manager::HIDDEN,
                    'default' => 'text',
                        ]
                );
            } else {
                $element->add_control(
                        'dce_visibility_fallback_type', [
                    'label' => __('Content type', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'text' => [
                            'title' => __('Text', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-align-left',
                        ],
                        'template' => [
                            'title' => __('Template', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-th-large',
                        ]
                    ],
                    'default' => 'text',
                    'condition' => [
                        'dce_visibility_fallback!' => '',
                    ],
                        ]
                );
            }
            /*$element->add_control(
                    'dce_visibility_fallback_template', [
                'label' => __('Render Template', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                //'options' => get_post_taxonomies( $post->ID ),
                'options' => $templates,
                'description' => 'Use a Elementor Template as content of popup, useful for complex structure.',
                'condition' => [
                    'dce_visibility_fallback!' => '',
                    'dce_visibility_fallback_type' => 'template',
                ],
                    ]
            );*/
            $element->add_control(
                'dce_visibility_fallback_template',
                [
                    'label' => __('Render Template', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Template Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'object_type' => 'elementor_library',
                    'description' => 'Use a Elementor Template as content of popup, useful for complex structure.',
                    'condition' => [
                        'dce_visibility_fallback!' => '',
                        'dce_visibility_fallback_type' => 'template',
                    ],
                ]
            );
            $element->add_control(
                    'dce_visibility_fallback_text', [
                'label' => __('Text Fallback', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => "This element is currently hidden.",
                'description' => __("Insert here some content showed if the element is not visible", 'dynamic-content-for-elementor'),
                'condition' => [
                    'dce_visibility_fallback!' => '',
                    'dce_visibility_fallback_type' => 'text',
                ],
                    ]
            );
            if ($element_type == 'section') {
                $element->add_control(
                        'dce_visibility_fallback_section', [
                    'label' => __('Use section wrapper', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'description' => __('Mantain original section wrapper.', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'dce_visibility_fallback!' => '',
                    //'dce_visibility_fallback_type' => 'text',
                    //'dce_visibility_fallback_text!' => '',
                    ],
                        ]
                );
            }
        }

        //$this->end_controls_section();
    }

    public function visibility_print_widget($content, $widget) {
        if (!$content)
            return '';

        $notice = '<div class="dce-visibility-warning"><i class="fa fa-eye-slash"></i> Hidden</div>'; // nascondo il widget
        $content = "<# if ( '' !== settings.enabled_visibility ) { if ( '' !== settings.dce_visibility_hidden ) { #>" . $notice . "<# } #><div class=\"dce-visibility-hidden-outline\">" . $content . "</div><# } else { #>" . $content . "<# } #>";
        return $content;
    }

    public function set_element_view_counters($element, $hidden = false) {
        if (!\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $settings = $element->get_settings_for_display();
            if ((!$hidden && $settings['dce_visibility_selected']) || ($hidden && !$settings['dce_visibility_selected'])) {
                //var_dump($settings);
                if (!empty($settings['dce_visibility_max_user']) || !empty($settings['dce_visibility_max_day']) || !empty($settings['dce_visibility_max_total'])) {
                    $dce_visibility_max = get_option('dce_visibility_max', array());

                    // remove elements with no limits
                    foreach ($dce_visibility_max as $ekey => $value) {
                        if ($ekey != $element->get_id()) {
                            $esettings = DCE_Helper::get_settings_by_id($ekey);
                            //var_dump($esettings);
                            if (empty($esettings['dce_visibility_max_day']) && empty($esettings['dce_visibility_max_total'])) {
                                unset($dce_visibility_max[$ekey]);
                            } else {
                                if (empty($esettings['dce_visibility_max_day'])) {
                                    unset($dce_visibility_max[$ekey]['day']);
                                }
                                if (empty($esettings['dce_visibility_max_total'])) {
                                    unset($dce_visibility_max[$ekey]['total']);
                                }
                            }
                        }
                    }

                    //var_dump($dce_visibility_max);
                    if (isset($dce_visibility_max[$element->get_id()])) {
                        $today = date('Ymd');
                        /*
                          // save in cookie/usermeta
                          if (!empty($settings['dce_visibility_max_user'])) {
                          $current_user_unique = get_current_user_id();
                          if (!$current_user_unique) {
                          $current_user_unique = wp_get_session_token();
                          }
                          $dce_visibility_max_user = intval($dce_visibility_max['user'][]) + 1;
                          } else {
                          $dce_visibility_max_user = array();
                          }
                         */

                        if (!empty($settings['dce_visibility_max_day'])) {
                            if (!empty($dce_visibility_max[$element->get_id()]['day'][$today])) {
                                $dce_visibility_max_day = $dce_visibility_max[$element->get_id()]['day'];
                                $dce_visibility_max_day[$today] = intval($dce_visibility_max_day[$today]) + 1;
                            } else {
                                $dce_visibility_max_day = array();
                                $dce_visibility_max_day[$today] = 1;
                            }
                        } else {
                            $dce_visibility_max_day = array();
                        }
                        if (!empty($settings['dce_visibility_max_total'])) {
                            if (isset($dce_visibility_max[$element->get_id()]['total'])) {
                                $dce_visibility_max_total = intval($dce_visibility_max[$element->get_id()]['total']) + 1;
                            } else {
                                $dce_visibility_max_total = 1;
                            }
                        } else {
                            $dce_visibility_max_total = 0;
                        }
                    } else {
                        $dce_visibility_max_day = array();
                        $dce_visibility_max_total = 1;
                    }
                    $dce_visibility_max[$element->get_id()] = array(
                        'day' => $dce_visibility_max_day,
                        'total' => $dce_visibility_max_total,
                    );
                    //var_dump($dce_visibility_max);
                    update_option('dce_visibility_max', $dce_visibility_max);
                }
            }
        }
    }

    public function visibility_render_widget($content, $widget) {
        $settings = $widget->get_settings_for_display();
        //delete_option('dce_visibility_max');
        if (isset($settings['enabled_visibility']) && $settings['enabled_visibility']) {
            $hidden = $this->is_hidden($widget);
            if ($hidden) {
                $this->print_conditions($widget);
            }
            $this->print_scripts($widget);
            $this->set_element_view_counters($widget, $hidden);

            // show element in backend
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                $notice = '';
                if ($hidden) {
                    $widget->add_render_attribute('_wrapper', 'class', 'dce-visibility-hidden');
                    $notice = '<div class="dce-visibility-warning"><i class="fa fa-eye-slash"></i> Hidden</div>'; // nascondo il widget
                    //return $notice . '<div class="dce-visibility-hidden dce-visibility-hidden-outline">' . $content . '</div>'; // mostro il widget
                    //return $notice .  $content ; // mostro il widget
                }

                //return '<div class="dce-visibility-hidden-outline">' . $content . '</div>';
                return $content;
            }

            if ($hidden) {
                if (!isset($settings['dce_visibility_dom']) || !$settings['dce_visibility_dom']) {
                    $content = '';
                }
                if (isset($settings['dce_visibility_debug']) && $settings['dce_visibility_debug']) {
                    $content = '<div class="dce-visibility-original-content dce-visibility-widget-hidden">' . $content . '</div>';
                }

                $fallback = $this->get_fallback($settings, $widget);
                if ($fallback) {
                    return $content . $fallback;
                }
                return $content; // . '<style>' . $widget->get_unique_selector() . '{display:none !important;}</style>'; // nascondo il widget
            }
        }
        return $content; // mostro il widget
    }

    public function get_fallback($settings, $element = null) {

        if (isset($settings['dce_visibility_fallback']) && $settings['dce_visibility_fallback']) {
            if (isset($settings['dce_visibility_fallback_type']) && $settings['dce_visibility_fallback_type'] == 'template') {
                $fallback_content = '[dce-elementor-template id="' . $settings['dce_visibility_fallback_template'] . '"]';
            } else { //if ($settings['dce_visibility_fallback_type'] == 'text') {
                $fallback_content = __($settings['dce_visibility_fallback_text'], 'dynamic-content-for-elementor' . '_texts');
            }
            $fallback_content = do_shortcode($fallback_content); // TODO FIX
            if (!defined('DVE_PLUGIN_BASE')) {
                $fallback_content = \DynamicContentForElementor\DCE_Tokens::do_tokens($fallback_content);
            }


            if ($fallback_content && (!isset($settings['dce_visibility_fallback_section']) || $settings['dce_visibility_fallback_section'] == 'yes')) { // BUG - Fix it
                $fallback_content = '
                                <div class="elementor-element elementor-column elementor-col-100 elementor-top-column" data-element_type="column">
                                    <div class="elementor-column-wrap elementor-element-populated">
                                        <div class="elementor-widget-wrap">
                                            <div class="elementor-element elementor-widget">
                                                <div class="elementor-widget-container dce-visibility-fallback">'
                        . $fallback_content .
                        '</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';

                ob_start();
                $element->before_render();
                echo $fallback_content;
                $element->after_render();
                $fallback_content = ob_get_contents();
                ob_end_clean();
            }

            return $fallback_content;
        }
        return '';
    }

    public function is_hidden($element = null, $why = false) {
        $settings = $element->get_settings_for_display();

        $hidden = FALSE;
        $conditions = array();

        $post_ID = get_the_ID(); // Current post
        if (!empty($settings['dce_visibility_post_id'])) {
            switch ($settings['dce_visibility_post_id']) {
                case 'global':
                    $queried_object = get_queried_object();
                    //if ( $queried_object instanceof WP_Post ) {
                    if ($queried_object && is_object($queried_object) && get_class($queried_object) == 'WP_Post') {
                        $post_ID = $queried_object->ID; // get_queried_object_id();
                    }
                    break;
                case 'static':
                    $post_tmp = get_post( $settings['dce_visibility_post_id_static'] );
                    if (is_object($post_tmp)) {
                        $post_ID = $post_tmp->ID;
                    }
                    break;
            }
        }

        if (isset($settings['enabled_visibility']) && $settings['enabled_visibility']) {

            // FORCED HIDDEN
            if (isset($settings['dce_visibility_hidden']) && $settings['dce_visibility_hidden']) {
                $conditions['dce_visibility_hidden'] = __('Always Hidden', 'dynamic-content-for-elementor');
                $hidden = TRUE;
            } else {

                // DATETIME
                //if (isset($settings['dce_visibility_datetime']) && !$settings['dce_visibility_datetime']) {
                $everytimehidden = false;

                if ($settings['dce_visibility_date_dynamic']) {
                    if ($settings['dce_visibility_date_dynamic_from'] && $settings['dce_visibility_date_dynamic_to']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['date'] = __('Date Dynamic', 'dynamic-content-for-elementor');
                        }
                        // between
                        $dateTo = strtotime($settings['dce_visibility_date_dynamic_to']);
                        $dateFrom = strtotime($settings['dce_visibility_date_dynamic_from']);
                        if (current_time('timestamp') >= $dateFrom && current_time('timestamp') <= $dateTo) {
                            $conditions['date'] = __('Date Dynamic', 'dynamic-content-for-elementor');
                            $everytimehidden = TRUE;
                        }
                    } else {
                        if ($settings['dce_visibility_date_dynamic_from']) {
                            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                                $conditions['dce_visibility_date_dynamic_from'] = __('Date Dynamic From', 'dynamic-content-for-elementor');
                            }
                            $dateFrom = strtotime($settings['dce_visibility_date_dynamic_from']);
                            if (current_time('timestamp') >= $dateFrom) {
                                $conditions['dce_visibility_date_dynamic_from'] = __('Date Dynamic From', 'dynamic-content-for-elementor');
                                $everytimehidden = TRUE;
                            }
                        }
                        if ($settings['dce_visibility_date_dynamic_to']) {
                            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                                $conditions['dce_visibility_date_dynamic_to'] = __('Date Dynamic To', 'dynamic-content-for-elementor');
                            }
                            $dateTo = strtotime($settings['dce_visibility_date_dynamic_to']);
                            if (current_time('timestamp') <= $dateTo) {
                                $conditions['dce_visibility_date_dynamic_to'] = __('Date Dynamic To', 'dynamic-content-for-elementor');
                                $everytimehidden = TRUE;
                            }
                        }
                    }
                } else {
                    if ($settings['dce_visibility_date_from'] && $settings['dce_visibility_date_to']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['date'] = __('Date', 'dynamic-content-for-elementor');
                        }
                        // between
                        $dateTo = strtotime($settings['dce_visibility_date_to']);
                        $dateFrom = strtotime($settings['dce_visibility_date_from']);
                        if (current_time('timestamp') >= $dateFrom && current_time('timestamp') <= $dateTo) {
                            $conditions['date'] = __('Date', 'dynamic-content-for-elementor');
                            $everytimehidden = TRUE;
                        }
                    } else {
                        if ($settings['dce_visibility_date_from']) {
                            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                                $conditions['dce_visibility_date_from'] = __('Date From', 'dynamic-content-for-elementor');
                            }
                            $dateFrom = strtotime($settings['dce_visibility_date_from']);
                            if (current_time('timestamp') >= $dateFrom) {
                                $conditions['dce_visibility_date_from'] = __('Date From', 'dynamic-content-for-elementor');
                                $everytimehidden = TRUE;
                            }
                        }
                        if ($settings['dce_visibility_date_to']) {
                            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                                $conditions['dce_visibility_date_to'] = __('Date To', 'dynamic-content-for-elementor');
                            }
                            $dateTo = strtotime($settings['dce_visibility_date_to']);
                            if (current_time('timestamp') <= $dateTo) {
                                $conditions['dce_visibility_date_to'] = __('Date To', 'dynamic-content-for-elementor');
                                $everytimehidden = TRUE;
                            }
                        }
                    }
                }

                if ($settings['dce_visibility_period_from'] && $settings['dce_visibility_period_to']) {
                    if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                        $conditions['period'] = __('Period', 'dynamic-content-for-elementor');
                    }
                    // between
                    if (date_i18n('m/d') >= $settings['dce_visibility_period_from'] && date_i18n('m/d') <= $settings['dce_visibility_period_to']) {
                        $conditions['period'] = __('Period', 'dynamic-content-for-elementor');
                        $everytimehidden = TRUE;
                    }
                } else {
                    if ($settings['dce_visibility_period_from']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_period_from'] = __('Period From', 'dynamic-content-for-elementor');
                        }
                        if (date_i18n('m/d') >= $settings['dce_visibility_period_from']) {
                            $conditions['dce_visibility_period_from'] = __('Period From', 'dynamic-content-for-elementor');
                            $everytimehidden = TRUE;
                        }
                    }
                    if ($settings['dce_visibility_period_to']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_period_to'] = __('Period To', 'dynamic-content-for-elementor');
                        }
                        if (date_i18n('m/d') <= $settings['dce_visibility_period_to']) {
                            $conditions['dce_visibility_period_to'] = __('Period To', 'dynamic-content-for-elementor');
                            $everytimehidden = TRUE;
                        }
                    }
                }

                if ($settings['dce_visibility_time_week'] && !empty($settings['dce_visibility_time_week'])) {
                    if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                        $conditions['dce_visibility_time_week'] = __('Day of Week', 'dynamic-content-for-elementor');
                    }
                    if (in_array(current_time('w'), $settings['dce_visibility_time_week'])) {
                        $conditions['dce_visibility_time_week'] = __('Day of Week', 'dynamic-content-for-elementor');
                        $everytimehidden = TRUE;
                    }
                }

                
                if ($settings['dce_visibility_time_from'] && $settings['dce_visibility_time_to']) {
                    if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                        $conditions['time'] = __('Time', 'dynamic-content-for-elementor');
                    }
                    $timeFrom = $settings['dce_visibility_time_from'];
                    $timeTo = ($settings['dce_visibility_time_to'] == '00:00') ? '24:00' : $settings['dce_visibility_time_to'];                    
                    if (current_time('H:i') >= $timeFrom && current_time('H:i') <= $timeTo) {
                        $conditions['time'] = __('Time', 'dynamic-content-for-elementor');
                        $everytimehidden = TRUE;
                    }
                } else {
                    if ($settings['dce_visibility_time_from']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_time_from'] = __('Time From', 'dynamic-content-for-elementor');
                        }
                        $timeFrom = $settings['dce_visibility_time_from'];
                        if (current_time('H:i') >= $timeFrom) {
                            $conditions['dce_visibility_time_from'] = __('Time From', 'dynamic-content-for-elementor');
                            $everytimehidden = TRUE;
                        }
                    }
                    if ($settings['dce_visibility_time_to']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_time_to'] = __('Time To', 'dynamic-content-for-elementor');
                        }
                        $timeTo = ($settings['dce_visibility_time_to'] == '00:00') ? '24:00' : $settings['dce_visibility_time_to'];
                        if (current_time('H:i') <= $timeTo) {
                            $conditions['dce_visibility_time_to'] = __('Time To', 'dynamic-content-for-elementor');
                            $everytimehidden = TRUE;
                        }
                    }
                }
                //}
                // USER & ROLES
                if (!isset($settings['dce_visibility_everyone']) || !$settings['dce_visibility_everyone']) {
                    $everyonehidden = FALSE;

                    //roles
                    if (isset($settings['dce_visibility_role']) && !empty($settings['dce_visibility_role'])) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_role'] = __('User Role', 'dynamic-content-for-elementor');
                        }
                        $current_user = wp_get_current_user();
                        if ($current_user && $current_user->ID) {
                            $user_roles = $current_user->roles; // possibile avere più ruoli
                            if (!is_array($user_roles)) {
                                $user_roles = array($user_roles);
                            }
                            if (is_array($settings['dce_visibility_role'])) {
                                $tmp_role = array_intersect($user_roles, $settings['dce_visibility_role']);
                                if (!empty($tmp_role)) {
                                    $conditions['dce_visibility_role'] = __('User Role', 'dynamic-content-for-elementor');
                                    $everyonehidden = TRUE;
                                }
                            }
                        } else {
                            if (in_array('visitor', $settings['dce_visibility_role'])) {
                                $conditions['dce_visibility_role'] = __('User not logged', 'dynamic-content-for-elementor');
                                $everyonehidden = TRUE;
                            }
                        }
                    }

                    // user                    
                    if (isset($settings['dce_visibility_users']) && $settings['dce_visibility_users']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_users'] = __('Specific User', 'dynamic-content-for-elementor');
                        }
                        $users = DCE_Helper::str_to_array(',', $settings['dce_visibility_users']);
                        $is_user = false;
                        if (!empty($users)) {
                            $current_user = wp_get_current_user();
                            foreach ($users as $key => $value) {
                                if (is_numeric($value)) {
                                    if ($value == $current_user->ID) {
                                        $is_user = true;
                                    }
                                }
                                if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                    if ($value == $current_user->user_email) {
                                        $is_user = true;
                                    }
                                }
                                if ($value == $current_user->user_login) {
                                    $is_user = true;
                                }
                            }
                        }
                        //var_dump($is_user);
                        if ($is_user) {
                            $conditions['dce_visibility_users'] = __('Specific User', 'dynamic-content-for-elementor');
                            $everyonehidden = TRUE;
                        }
                    }
                    
                    if (isset($settings['dce_visibility_can']) && $settings['dce_visibility_can']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_can'] = __('User can', 'dynamic-content-for-elementor');
                        }
                        $user_can = false;
                        $user_id = get_current_user_id();
                        if (user_can($user_id, $settings['dce_visibility_can'])) {
                            $user_can = true;
                        }
                        if ($user_can) {
                            $conditions['dce_visibility_can'] = __('User can', 'dynamic-content-for-elementor');
                            $everyonehidden = TRUE;
                        }
                    }

                    if (isset($settings['dce_visibility_usermeta']) && !empty($settings['dce_visibility_usermeta'])) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_usermeta'] = __('User Field', 'dynamic-content-for-elementor');
                        }
                        $current_user = wp_get_current_user();
                        if (DCE_Helper::is_user_meta($settings['dce_visibility_usermeta'])) {
                            $usermeta = get_user_meta($current_user->ID, $settings['dce_visibility_usermeta'], true); // false for visitor
                        } else {
                            $usermeta = $current_user->{$settings['dce_visibility_usermeta']};
                        }
                        switch ($settings['dce_visibility_usermeta_status']) {
                            case 'isset':
                                if (!empty($usermeta)) {
                                    $conditions['dce_visibility_usermeta'] = __('User Field', 'dynamic-content-for-elementor');
                                }
                                break;
                            case 'not':
                                if (empty($usermeta)) {
                                    $conditions['dce_visibility_usermeta'] = __('User Field', 'dynamic-content-for-elementor');
                                }
                                break;
                            case 'value':
                                if ($usermeta == $settings['dce_visibility_usermeta_value']) {
                                    $conditions['dce_visibility_usermeta'] = __('User Field', 'dynamic-content-for-elementor');
                                }
                        }
                    }



                    // GEOIP
                    if (DCE_Helper::is_plugin_active('geoip-detect') && function_exists('geoip_detect2_get_info_from_current_ip')) {
                        if (!empty($settings['dce_visibility_country'])) {
                            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                                $conditions['dce_visibility_country'] = __('Country', 'dynamic-content-for-elementor');
                            } else {
                                $geoinfo = geoip_detect2_get_info_from_current_ip();
                                if (in_array($geoinfo->country->isoCode, $settings['dce_visibility_country'])) {
                                    $conditions['dce_visibility_country'] = __('Country', 'dynamic-content-for-elementor');
                                }
                            }
                        }

                        if (!empty($settings['dce_visibility_city'])) {
                            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                                $conditions['dce_visibility_country'] = __('City', 'dynamic-content-for-elementor');
                            } else {
                                $geoinfo = geoip_detect2_get_info_from_current_ip();
                                $ucity = array_map('strtolower', $geoinfo->city->names);
                                $scity = DCE_Helper::str_to_array(',', $settings['dce_visibility_city'], 'strtolower');
                                $icity = array_intersect($ucity, $scity);
                                if (!empty($icity)) {
                                    $conditions['dce_visibility_country'] = __('City', 'dynamic-content-for-elementor');
                                }
                            }
                        }
                    }


                    // referrer
                    if (isset($settings['dce_visibility_referrer']) && $settings['dce_visibility_referrer'] && $settings['dce_visibility_referrer_list']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_referrer_list'] = __('Referer', 'dynamic-content-for-elementor');
                        }
                        if ($_SERVER['HTTP_REFERER']) {
                            $pieces = explode('/', $_SERVER['HTTP_REFERER']);
                            $referrer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST); //$pieces[2];
                            $referrers = explode(PHP_EOL, $settings['dce_visibility_referrer_list']);
                            $referrers = array_map('trim', $referrers);
                            $ref_found = false;
                            foreach ($referrers as $aref) {
                                if ($aref == $referrer || $aref == str_replace('www.', '', $referrer)) {
                                    $ref_found = true;
                                }
                            }
                            if ($ref_found) {
                                $conditions['dce_visibility_referrer_list'] = __('Referer', 'dynamic-content-for-elementor');
                                $everyonehidden = TRUE;
                            }
                        }/* else {
                          $everyonehidden = TRUE;
                          } */
                    }

                    if (isset($settings['dce_visibility_ip']) && $settings['dce_visibility_ip']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_ip'] = __('Remote IP', 'dynamic-content-for-elementor');
                        }
                        $ips = explode(',', $settings['dce_visibility_ip']);
                        $ips = array_map('trim', $ips);
                        if (in_array($_SERVER['REMOTE_ADDR'], $ips)) {
                            $conditions['dce_visibility_ip'] = __('Remote IP', 'dynamic-content-for-elementor');
                            $everyonehidden = TRUE;
                        }
                    }
                }

                // DEVICE
                if (!isset($settings['dce_visibility_device']) || !$settings['dce_visibility_device']) {
                    $ahidden = FALSE;

                    // responsive
                    if (isset($settings['dce_visibility_responsive']) && $settings['dce_visibility_responsive']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_responsive'] = __('Responsive', 'dynamic-content-for-elementor');
                        }
                        if (wp_is_mobile()) {
                            if ($settings['dce_visibility_responsive'] == 'mobile') {
                                $conditions['dce_visibility_responsive'] = __('Responsive: is Mobile', 'dynamic-content-for-elementor');
                                $ahidden = TRUE;
                            }
                        } else {
                            if ($settings['dce_visibility_responsive'] == 'desktop') {
                                $conditions['dce_visibility_responsive'] = __('Responsive: is Desktop', 'dynamic-content-for-elementor');
                                $ahidden = TRUE;
                            }
                        }
                    }

                    // browser
                    if (isset($settings['dce_visibility_browser']) && is_array($settings['dce_visibility_browser']) && !empty($settings['dce_visibility_browser'])) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_browser'] = __('Browser', 'dynamic-content-for-elementor');
                        }
                        $is_browser = false;
                        foreach ($settings['dce_visibility_browser'] as $browser) {
                            global $$browser;
                            //var_dump($$browser);
                            if (isset($$browser) && $$browser) {
                                $is_browser = true;
                            }
                        }
                        //$hidden_browser = false;
                        if ($is_browser) {
                            $conditions['dce_visibility_browser'] = __('Browser', 'dynamic-content-for-elementor');
                            $ahidden = TRUE;
                        }
                    }
                }

                // CONTEXT
                if (!isset($settings['dce_visibility_context']) || !$settings['dce_visibility_context']) {
                    $contexthidden = false;

                    // cpt
                    if (isset($settings['dce_visibility_cpt']) && !empty($settings['dce_visibility_cpt']) && is_array($settings['dce_visibility_cpt'])) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_cpt'] = __('Post Type', 'dynamic-content-for-elementor');
                        }
                        $cpt = get_post_type();
                        //var_dump($cpt);
                        if (in_array($cpt, $settings['dce_visibility_cpt'])) {
                            $conditions['dce_visibility_cpt'] = __('Post Type', 'dynamic-content-for-elementor');
                            $contexthidden = true;
                        }
                    }

                    // post
                    //var_dump($settings['dce_visibility_post']);
                    if (isset($settings['dce_visibility_post']) && !empty($settings['dce_visibility_post']) && is_array($settings['dce_visibility_post'])) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_post'] = __('Post', 'dynamic-content-for-elementor');
                        }
                        if (in_array($post_ID, $settings['dce_visibility_post'])) {
                            $conditions['dce_visibility_post'] = __('Post', 'dynamic-content-for-elementor');
                            $contexthidden = true;
                        }
                    }

                    // taxonomy
                    /* if (!empty($settings['dce_visibility_tax']) && is_array($settings['dce_visibility_tax'])) {
                      $tax = get_post_taxonomies();
                      //return $tax;
                      if (!array_intersect($tax, $settings['dce_visibility_tax'])) {
                      $conditions[] = __('Taxonomy', 'dynamic-content-for-elementor');
                      $contexthidden = true;
                      }
                      } */
                    if (isset($settings['dce_visibility_tax']) && $settings['dce_visibility_tax']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_tax'] = __('Taxonomy', 'dynamic-content-for-elementor');
                        }
                        //return $settings['dce_visibility_tax'];
                        $tax = get_post_taxonomies();
                        //return $tax;
                        if (!in_array($settings['dce_visibility_tax'], $tax)) {
                            $conditions['dce_visibility_tax'] = __('Taxonomy', 'dynamic-content-for-elementor');
                            $contexthidden = true;
                        } else {
                            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                                $conditions['terms'] = __('Terms', 'dynamic-content-for-elementor');
                            }
                            // term
                            $terms = get_the_terms($post_ID, $settings['dce_visibility_tax']);
                            if (!empty($terms)) {
                                $terms = wp_list_pluck($terms, 'term_id');
                            }
                            //return $terms;
                            $tkey = 'dce_visibility_term_' . $settings['dce_visibility_tax'];
                            //return $settings[$tkey];
                            if (!empty($settings[$tkey]) && is_array($settings[$tkey])) {
                                if (!empty($terms)) {
                                    if (array_intersect($terms, $settings[$tkey])) {
                                        $conditions[$tkey] = __('Terms', 'dynamic-content-for-elementor');
                                        $contexthidden = true;
                                        //return $tax;
                                    }
                                }
                            } else {
                                if (!empty($terms)) {
                                    $conditions['terms'] = __('Terms', 'dynamic-content-for-elementor');
                                    $contexthidden = true;
                                }
                            }
                        }
                    }

                    // meta
                    if (isset($settings['dce_visibility_meta']) && is_array($settings['dce_visibility_meta']) && !empty($settings['dce_visibility_meta'])) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_meta'] = __('Post Metas', 'dynamic-content-for-elementor');
                        }
                        $post_metas = $settings['dce_visibility_meta'];
                        $metafirst = true;
                        $metavalued = false;
                        foreach ($post_metas as $mkey => $ameta) {
                            //var_dump($ameta);
                            if (is_author()) {
                                $author_id = get_the_author_meta('ID');
                                //var_dump($author_id);
                                $mvalue = get_user_meta($author_id, $ameta, true);
                            } else {
                                $mvalue = get_post_meta($post_ID, $ameta, true);
                                if (is_array($mvalue) && empty($mvalue)) {
                                    $mvalue = false;
                                }
                            }
                            if ($settings['dce_visibility_meta_operator']) { // AND
                                if ($metafirst && $mvalue) {
                                    $metavalued = true;
                                }
                                if (!$metavalued || !$mvalue) {
                                    $metavalued = FALSE;
                                }
                            } else { // OR
                                if ($metavalued || $mvalue) {
                                    $metavalued = TRUE;
                                }
                            }
                            $metafirst = false;
                        }

                        if ($metavalued) {
                            $conditions['dce_visibility_meta'] = __('Post Metas', 'dynamic-content-for-elementor');
                            $contexthidden = TRUE;
                        }
                    }

                    if (isset($settings['dce_visibility_field']) && !empty($settings['dce_visibility_field'])) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_field'] = __('Post Meta', 'dynamic-content-for-elementor');
                        }
                        $postmeta = get_post_meta($post_ID, $settings['dce_visibility_field'], true);
                        switch ($settings['dce_visibility_field_status']) {
                            case 'isset':
                                if (!empty($postmeta)) {
                                    $conditions['dce_visibility_field'] = __('Post Meta', 'dynamic-content-for-elementor');
                                }
                                break;
                            case 'not':
                                if (empty($postmeta)) {
                                    $conditions['dce_visibility_field'] = __('Post Meta', 'dynamic-content-for-elementor');
                                }
                                break;
                            case 'value':
                                if ($postmeta == $settings['dce_visibility_field_value']) {
                                    $conditions['dce_visibility_field'] = __('Post Meta', 'dynamic-content-for-elementor');
                                }
                        }
                    }

                    if (isset($settings['dce_visibility_parameter']) && $settings['dce_visibility_parameter']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_parameter'] = __('Parameter', 'dynamic-content-for-elementor');
                        }
                        switch ($settings['dce_visibility_parameter_status']) {
                            case 'isset':
                                if (isset($_REQUEST[$settings['dce_visibility_parameter']])) {
                                    $conditions['dce_visibility_parameter'] = __('Parameter', 'dynamic-content-for-elementor');
                                    $contexthidden = true;
                                }
                                break;
                            case 'not':
                                if (!isset($_REQUEST[$settings['dce_visibility_parameter']])) {
                                    $conditions['dce_visibility_parameter'] = __('Parameter', 'dynamic-content-for-elementor');
                                    $contexthidden = true;
                                }
                                break;
                            case 'value':
                                if (isset($_REQUEST[$settings['dce_visibility_parameter']]) && $_REQUEST[$settings['dce_visibility_parameter']] == $settings['dce_visibility_parameter_value']) {
                                    $conditions['dce_visibility_parameter'] = __('Parameter', 'dynamic-content-for-elementor');
                                    $contexthidden = true;
                                }
                        }
                    }

                    /* if (!empty($settings['dce_visibility_max_user']) && $settings['dce_visibility_max_user']) {
                      $dce_visibility_max = get_option('dce_visibility_max', array());
                      if (isset($dce_visibility_max[$element->get_id()]) && isset($dce_visibility_max[$element->get_id()]['user'])) {
                      if ($settings['dce_visibility_max_user'] < $dce_visibility_max[$element->get_id()]['user']) {
                      $conditions['dce_visibility_max_user'] = __('Max User', 'dynamic-content-for-elementor');
                      }
                      }
                      } */
                    
                    // LANGUAGES
                    if (!empty($settings['dce_visibility_lang']) && $settings['dce_visibility_lang']) {
                        $current_language = get_locale();
                        // WPML
                        global $sitepress;
                        if (!empty($sitepress)) {
                            $current_language = $sitepress->get_current_language(); // return lang code
                        }
                        // POLYLANG
                        if (DCE_Helper::is_plugin_active('polylang') && function_exists('pll_languages_list')) {
                            $current_language = pll_current_language();
                        }
                        // TRANSLATEPRESS
                        global $TRP_LANGUAGE;
                        if (!empty($TRP_LANGUAGE)) {
                            $current_language = $TRP_LANGUAGE; // return lang code
                        }
                        if (in_array($current_language, $settings['dce_visibility_lang'])) {
                            $conditions['dce_visibility_lang'] = __('Language', 'dynamic-content-for-elementor');
                        }
                    }
                    
                    if (!empty($settings['dce_visibility_max_day']) && $settings['dce_visibility_max_day']) {
                        $dce_visibility_max = get_option('dce_visibility_max', array());
                        //var_dump($dce_visibility_max);echo $element->get_id();
                        $today = date('Ymd');
                        if (isset($dce_visibility_max[$element->get_id()]) && isset($dce_visibility_max[$element->get_id()]['day']) && isset($dce_visibility_max[$element->get_id()]['day'][$today])) {
                            //var_dump($dce_visibility_max[$element->get_id()]['day'][$today]);
                            if ($settings['dce_visibility_max_day'] >= $dce_visibility_max[$element->get_id()]['day'][$today]) {
                                $conditions['dce_visibility_max_day'] = __('Max Day', 'dynamic-content-for-elementor');
                            }
                        } else {
                            $conditions['dce_visibility_max_day'] = __('Max Day', 'dynamic-content-for-elementor');
                        }
                    }
                    if (!empty($settings['dce_visibility_max_total']) && $settings['dce_visibility_max_total']) {
                        $dce_visibility_max = get_option('dce_visibility_max', array());
                        if (isset($dce_visibility_max[$element->get_id()]) && isset($dce_visibility_max[$element->get_id()]['total'])) {
                            //var_dump($dce_visibility_max[$element->get_id()]['total']);
                            if ($settings['dce_visibility_max_total'] >= $dce_visibility_max[$element->get_id()]['total']) {
                                $conditions['dce_visibility_max_total'] = __('Max Total', 'dynamic-content-for-elementor');
                            }
                        } else {
                            $conditions['dce_visibility_max_total'] = __('Max Total', 'dynamic-content-for-elementor');
                        }
                    }


                    if (isset($settings['dce_visibility_root']) && $settings['dce_visibility_root']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_root'] = __('Post is Root', 'dynamic-content-for-elementor');
                        }
                        if (!wp_get_post_parent_id($post_ID)) {
                            $conditions['dce_visibility_root'] = __('Post is Root', 'dynamic-content-for-elementor');
                        }
                    }

                    if (isset($settings['dce_visibility_format']) && !empty($settings['dce_visibility_format'])) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_format'] = __('Post Format', 'dynamic-content-for-elementor');
                        }
                        $format = get_post_format($post_ID) ?: 'standard';
                        if (in_array($format, $settings['dce_visibility_format'])) {
                            $conditions['dce_visibility_format'] = __('Post Format', 'dynamic-content-for-elementor');
                        }
                    }

                    if (isset($settings['dce_visibility_parent']) && $settings['dce_visibility_parent']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_parent'] = __('Post is Parent', 'dynamic-content-for-elementor');
                        }
                        $args = array(
                            'post_parent' => $post_ID,
                            'post_type' => get_post_type(),
                            'numberposts' => -1,
                            'post_status' => 'publish'
                        );
                        $children = get_children($args);
                        if (!empty($children) && count($children)) {
                            $conditions['dce_visibility_parent'] = __('Post is Parent', 'dynamic-content-for-elementor');
                        }
                    }

                    if (isset($settings['dce_visibility_leaf']) && $settings['dce_visibility_leaf']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_leaf'] = __('Post is Leaf', 'dynamic-content-for-elementor');
                        }
                        $args = array(
                            'post_parent' => $post_ID,
                            'post_type' => get_post_type(),
                            'numberposts' => -1,
                            'post_status' => 'publish'
                        );
                        $children = get_children($args);
                        if (empty($children)) {
                            $conditions['dce_visibility_leaf'] = __('Post is Leaf', 'dynamic-content-for-elementor');
                        }
                    }

                    if (isset($settings['dce_visibility_node']) && $settings['dce_visibility_node']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_node'] = __('Post is Node', 'dynamic-content-for-elementor');
                        }
                        if (wp_get_post_parent_id($post_ID)) {
                            $args = array(
                                'post_parent' => $post_ID,
                                'post_type' => get_post_type(),
                                'numberposts' => -1,
                                'post_status' => 'publish'
                            );
                            $children = get_children($args);
                            if (!empty($children)) {

                                $parents = get_post_ancestors($post_ID);
                                $node_level = count($parents) + 1;
                                if (empty($settings['dce_visibility_node_level']) || $node_level == $settings['dce_visibility_node_level']) {
                                    $conditions['dce_visibility_node'] = __('Post is Node', 'dynamic-content-for-elementor');
                                }
                            }
                        }
                    }

                    if (isset($settings['dce_visibility_level']) && $settings['dce_visibility_level']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_level'] = __('Post is Node', 'dynamic-content-for-elementor');
                        }
                        $parents = get_post_ancestors($post_ID);
                        $node_level = count($parents) + 1;
                        if ($node_level == $settings['dce_visibility_level']) {
                            $conditions['dce_visibility_level'] = __('Post has Level', 'dynamic-content-for-elementor');
                        }
                    }

                    if (isset($settings['dce_visibility_child']) && $settings['dce_visibility_child']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_child'] = __('Post has Parent', 'dynamic-content-for-elementor');
                        }
                        if ($post_parent_ID = wp_get_post_parent_id($post_ID)) {
                            $parent_ids = DCE_Helper::str_to_array(',', $settings['dce_visibility_child_parent']);
                            if (empty($settings['dce_visibility_child_parent']) || in_array($post_parent_ID, $parent_ids)) {
                                $conditions['dce_visibility_child'] = __('Post has Parent', 'dynamic-content-for-elementor');
                            }
                        }
                    }

                    if (isset($settings['dce_visibility_sibling']) && $settings['dce_visibility_sibling']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_sibling'] = __('Post has Siblings', 'dynamic-content-for-elementor');
                        }
                        if ($post_parent_ID = wp_get_post_parent_id($post_ID)) {
                            $args = array(
                                'post_parent' => $post_parent_ID,
                                'post_type' => get_post_type(),
                                'numberposts' => -1,
                                'post_status' => 'publish'
                            );
                            $children = get_children($args);
                            if (!empty($children) && count($children) > 1) {
                                $conditions['dce_visibility_sibling'] = __('Post has Siblings', 'dynamic-content-for-elementor');
                            }
                        }
                    }

                    if (isset($settings['dce_visibility_friend']) && $settings['dce_visibility_friend']) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_friend'] = __('Post has Friends', 'dynamic-content-for-elementor');
                        }
                        $posts_ids = array();
                        if ($settings['dce_visibility_friend_term']) {
                            $term = get_term($settings['dce_visibility_friend_term']);
                            $terms = array($term);
                        } else {
                            $terms = wp_get_post_terms();
                        }
                        if (!empty($terms)) {
                            foreach ($terms as $term) {
                                $post_args = array(
                                    'posts_per_page' => -1,
                                    'post_type' => get_post_type(),
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => $term->taxonomy,
                                            'field' => 'term_id', // this can be 'term_id', 'slug' & 'name'
                                            'terms' => $term->term_id,
                                        )
                                    )
                                );
                                $term_posts = get_posts($post_args);
                                if (!empty($term_posts) && count($term_posts) > 1) {
                                    $posts_ids = wp_list_pluck($term_posts, 'ID');
                                    if (in_array($post_ID, $posts_ids)) {
                                        $conditions['dce_visibility_friend'] = __('Post has Friends', 'dynamic-content-for-elementor');
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }

                // CONDITONAL TAGS
                if (!isset($settings['dce_visibility_tags']) || !$settings['dce_visibility_tags']) {
                    $contexttags = false;
                    // conditional tags
                    if (isset($settings['dce_visibility_conditional_tags_post']) && is_array($settings['dce_visibility_conditional_tags_post']) && !empty($settings['dce_visibility_conditional_tags_post'])) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_conditional_tags_post'] = __('Conditional tags Post', 'dynamic-content-for-elementor');
                        }
                        $context_conditional_tags = false;
                        $post_type = get_post_type();
                        foreach ($settings['dce_visibility_conditional_tags_post'] as $conditional_tags) {
                            if (!$context_conditional_tags) {
                                switch ($conditional_tags) {
                                    case 'is_post_type_hierarchical':
                                    case 'is_post_type_archive':
                                        if (is_callable($conditional_tags)) {
                                            $context_conditional_tags = call_user_func($conditional_tags, $post_type);
                                        }
                                        break;
                                    case 'has_post_thumbnail':
                                        if (is_callable($conditional_tags)) {
                                            $context_conditional_tags = call_user_func($conditional_tags, $post_ID);
                                        }
                                        break;
                                    default:
                                        if (is_callable($conditional_tags)) {
                                            $context_conditional_tags = call_user_func($conditional_tags);
                                        }
                                }
                            }
                        }
                        if ($context_conditional_tags) {
                            $conditions['dce_visibility_conditional_tags_post'] = __('Conditional tags Post', 'dynamic-content-for-elementor');
                            $contexttags = TRUE;
                        }
                    }
                    if (isset($settings['dce_visibility_conditional_tags_site']) && is_array($settings['dce_visibility_conditional_tags_site']) && !empty($settings['dce_visibility_conditional_tags_site'])) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_conditional_tags_site'] = __('Conditional tags Site', 'dynamic-content-for-elementor');
                        }
                        $context_conditional_tags = false;
                        foreach ($settings['dce_visibility_conditional_tags_site'] as $conditional_tags) {
                            if (!$context_conditional_tags) {
                                switch ($conditional_tags) {
                                    default:
                                        if (is_callable($conditional_tags)) {
                                            $context_conditional_tags = call_user_func($conditional_tags);
                                        }
                                }
                            }
                        }
                        if ($context_conditional_tags) {
                            $conditions['dce_visibility_conditional_tags_site'] = __('Conditional tags Site', 'dynamic-content-for-elementor');
                            $contexttags = TRUE;
                        }
                    }

                    // specials
                    if (isset($settings['dce_visibility_special']) && is_array($settings['dce_visibility_special']) && !empty($settings['dce_visibility_special'])) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_special'] = __('Conditional tags Special', 'dynamic-content-for-elementor');
                        }
                        $context_special = false;
                        foreach ($settings['dce_visibility_special'] as $special) {
                            if (!$context_special) {
                                switch ($special) {
                                    default:
                                        if (is_callable($special)) {
                                            $context_special = call_user_func($special);
                                        }
                                }
                            }
                        }
                        if ($context_special) {
                            $conditions['dce_visibility_special'] = __('Conditional tags Special', 'dynamic-content-for-elementor');
                            $contexttags = TRUE;
                        }
                    }

                    // archive
                    if (isset($settings['dce_visibility_archive'])) { // && is_array($settings['dce_visibility_archive']) && !empty($settings['dce_visibility_archive'])) {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['dce_visibility_archive'] = __('Conditional tags Archive', 'dynamic-content-for-elementor');
                        }
                        $context_archive = false;
                        $archive = $settings['dce_visibility_archive'];                        
                        if ($archive) {
                        //foreach ($settings['dce_visibility_archive'] as $archive) {
                            if (!$context_archive) {
                                switch ($archive) {
                                    case 'is_post_type_archive':
                                    case 'is_tax':
                                    //case 'is_taxonomy':
                                    case 'is_category':
                                    case 'is_tag':
                                    case 'is_author':
                                    case 'is_date':
                                    case 'is_year':
                                    case 'is_month':
                                    case 'is_day':
                                    case 'is_search':
                                        if (is_callable($archive)) {
                                            $context_archive = call_user_func($archive);
                                        }
                                        break;
                                    default:
                                        $context_archive = is_archive();
                                }
                            }
                        }
                        if ($context_archive) { // || ($context_archive && !$settings['dce_visibility_context_selected'])) {
                            
                            $context_archive_advanced = false;
                            $queried_object = get_queried_object();
                            switch ($archive) {
                                case 'is_tax':
                                    if (get_class($queried_object) == 'WP_Term' && $settings['dce_visibility_archive_tax'] && $queried_object->taxonomy == $settings['dce_visibility_archive_tax']) {
                                        if (empty($settings['dce_visibility_archive_term_'.$settings['dce_visibility_archive_tax']])) {
                                            $context_archive_advanced = true;
                                        } else {
                                            if (in_array($queried_object->term_id, $settings['dce_visibility_archive_term_'.$settings['dce_visibility_archive_tax']])) {
                                                $context_archive_advanced = true;
                                            }
                                        }
                                    } else {
                                        $context_archive_advanced = true;
                                    }
                                    break;
                                case 'is_category':
                                    is_category();
                                    if (get_class($queried_object) == 'WP_Term' && $queried_object->taxonomy == 'category') {
                                        if (empty($settings['dce_visibility_archive_term_category'])) {
                                            $context_archive_advanced = true;
                                        } else {
                                            if (in_array($queried_object->term_id, $settings['dce_visibility_archive_term_category'])) {
                                                $context_archive_advanced = true;
                                            }
                                        }
                                    }
                                    break;
                                case 'is_tag':
                                    if (get_class($queried_object) == 'WP_Term' && $queried_object->taxonomy == 'post_tag') {
                                        if (empty($settings['dce_visibility_archive_term_post_tag'])) {
                                            $context_archive_advanced = true;
                                        } else {
                                            if (in_array($queried_object->term_id, $settings['dce_visibility_archive_term_post_tag'])) {
                                                $context_archive_advanced = true;
                                            }
                                        }
                                    }
                                    break;
                                default :
                                    $context_archive_advanced = true;
                            }
                            if ($context_archive_advanced) {
                                $conditions['dce_visibility_archive'] = __('Archive', 'dynamic-content-for-elementor');
                                $contexttags = TRUE;
                            }
                        }
                        
                        
                    }
                    
                    
                }

                if (isset($settings['dce_visibility_random']) && $settings['dce_visibility_random']['size']) {
                    $rand = mt_rand(1, 100);
                    if ($rand <= $settings['dce_visibility_random']['size']) {
                        $conditions['dce_visibility_random'] = __('Random', 'dynamic-content-for-elementor');
                        $randomhidden = true;
                    }
                }
                
                /*if (!$settings['dce_visibility_selected']) {
                    if (isset($settings['dce_visibility_click']) && $settings['dce_visibility_click']) {
                        $conditions['dce_visibility_click'] = __('On Click', 'dynamic-content-for-elementor');
                    }
                    if (isset($settings['dce_visibility_load']) && $settings['dce_visibility_load']) {
                        $conditions['dce_visibility_load'] = __('On Page Load', 'dynamic-content-for-elementor');
                    }
                }*/

                // CUSTOM CONDITION
                if (!isset($settings['dce_visibility_custom_condition']) || !$settings['dce_visibility_custom_condition']) {
                    $customhidden = false;
                    if (isset($settings['dce_visibility_custom_condition_php']) && trim($settings['dce_visibility_custom_condition_php']) && trim($settings['dce_visibility_custom_condition_php']) != 'return true;') {
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $conditions['custom'] = __('Custom Condition', 'dynamic-content-for-elementor');
                        }
                        $customhidden = $this->check_custom_condition($settings, $element->get_id());
                        //var_dump($customhidden);
                        if ($customhidden) {
                            $conditions['custom'] = __('Custom Condition', 'dynamic-content-for-elementor');
                        }
                    }
                }
            }

            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                $hidden = true;
            }
        }

        //var_dump($hidden);

        $triggered = false;
        if (!empty($conditions)) {
            $triggered = true;
        }

        $shidden = $settings['dce_visibility_selected'];
        // retrocompatibility for 1.4
        if (isset($settings['dce_visibility_user_selected']) && !$settings['dce_visibility_user_selected']) {
            $shidden = FALSE;
        }
        if (isset($settings['dce_visibility_datetime_selected']) && !$settings['dce_visibility_datetime_selected']) {
            $shidden = FALSE;
        }
        if (isset($settings['dce_visibility_custom_condition_selected']) && !$settings['dce_visibility_custom_condition_selected']) {
            $shidden = FALSE;
        }
        if (isset($settings['dce_visibility_tags_selected']) && !$settings['dce_visibility_tags_selected']) {
            $shidden = FALSE;
        }
        if (isset($settings['dce_visibility_context_selected']) && !$settings['dce_visibility_context_selected']) {
            $shidden = FALSE;
        }
        if (isset($settings['dce_visibility_device_selected']) && !$settings['dce_visibility_device_selected']) {
            $shidden = FALSE;
        }

        if (self::check_visibility_condition($triggered, $shidden)) {
            $hidden = TRUE;
        }

        if ($why) {
            return $conditions;
        }

        return $hidden;
    }

    static public function check_visibility_condition($condition, $visibility) {
        $ret = $condition;
        if ($visibility) {
            if ($condition) {
                $ret = false; // mostro il widget
            } else {
                $ret = true; // nascondo il widget
            }
        } else {
            if ($condition) {
                $ret = true; // nascondo il widget
            } else {
                $ret = false; // mostro il widget
            }
        }
        return $ret;
    }

    public function check_custom_condition($settings, $eid = null) {
        $php_code = $settings['dce_visibility_custom_condition_php'];
        if ($php_code) {
            if (strpos($php_code, 'return ') !== false) {
                if ($settings['dce_visibility_custom_condition_secure']) {
                    $url = DCE_URL . 'assets/condition.php?pid=' . get_the_ID() . '&eid=' . $eid;
                    $custom_condition_result = wp_remote_get($url);
                    if ($custom_condition_result['body'] == '1') {
                        return true;
                    }
                } else {
                    // it may cause fatal error
                    $return = eval($php_code);
                    if ($return) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function print_conditions($element, $settings = null) {
        if (WP_DEBUG && !\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            //if ($this->is_hidden($element)) {
            if (empty($settings)) {
                $settings = $element->get_settings_for_display();
            }
            if ($settings['dce_visibility_debug']) {
                $conditions = $this->is_hidden($element, true);
                if (!empty($conditions)) {
                    //echo '<a href=".elementor-element-'.$element->get_ID().'" class="dce-btn-visibility"><i class="dce-icon-visibility fa fa-eye-slash" aria-hidden="true"></i></a>';
                    echo '<a onClick="jQuery(this).next().fadeToggle(); return false;" href="#box-visibility-debug-' . $element->get_ID() . '" class="dce-btn-visibility dce-btn-visibility-debug"><i class="dce-icon-visibility fa fa fa-eye exclamation-triangle" aria-hidden="true"></i></a>';
                    echo '<div id="#box-visibility-debug-' . $element->get_ID() . '" class="dce-box-visibility-debug"><ul>';
                    foreach ($conditions as $key => $value) {
                        echo '<li>';
                        echo $value;
                        if (isset($settings[$key])) {
                            echo ': ';
                            if (is_array($settings[$key])) {
                                if ($key == 'dce_visibility_random') {
                                    echo $settings[$key]['size'] . '%';
                                } else {
                                    echo implode(', ', $settings[$key]);
                                }
                            } else {
                                echo print_r($settings[$key], true);
                            }
                        }
                        echo '</li>';
                    }
                    echo '</ul></div>';
                }
            }
            //}
        }
    }
    
    public function print_scripts($element, $settings = null) {
        if (!\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            if (empty($settings)) {
                $settings = $element->get_settings_for_display();
            }
            if ($settings['dce_visibility_click']) {                
                switch ($settings['dce_visibility_click_show']) {
                    case 'slide':
                        $jFunction = 'slideDown';
                        $jFunctionHide = 'slideUp';
                        break;
                    case 'fade':
                        $jFunction = 'fadeIn';
                        $jFunctionHide = 'fadeOut';
                        break;
                    default:
                        $jFunction = 'show';
                        $jFunctionHide = 'hide';
                }
                //var_dump($settings['dce_visibility_selected']);
                $show = true;
                if (!$settings['dce_visibility_selected']) {                    
                    $show = false;
                    $jFunction = $jFunctionHide;
                }
                
                if ($settings['dce_visibility_click_toggle']) {
                    if ($settings['dce_visibility_click_show']) {
                        $jFunctionToggle = $settings['dce_visibility_click_show'].'Toggle';
                    } else {
                        $jFunctionToggle = 'toggle';
                    }
                    $jFunction = $jFunctionToggle;
                } else {
                    if ($show) {
                        $jFunctionToggle = $jFunctionHide;
                    } else {
                        $jFunctionToggle = $jFunction;
                    }
                }
                ?>
                <script>
                    jQuery(document).ready(function(){
                        //alert('<?php echo $jFunction; ?>');
                        jQuery('<?php echo $settings['dce_visibility_click']; ?>').on('click', function(){
                            <?php if ($settings['dce_visibility_click_other']) { ?>
                                jQuery('<?php echo $settings['dce_visibility_click_other']; ?>').<?php echo $jFunctionToggle; ?>();
                            <?php } ?>
                            jQuery('.elementor-element-<?php echo $element->get_id(); ?>').<?php echo $jFunction; ?>();
                            //console.log(jQuery(this).attr('href'));
                            if (jQuery(this).attr('href') == '#') {
                                return false;
                            }
                        });
                    });
                </script>
                <?php
            }
            if ($settings['dce_visibility_load']) {  
                if ($settings['dce_visibility_load_show']) {
                    $jFunctionToggle = $settings['dce_visibility_load_show'].'Toggle';
                } else {
                    $jFunctionToggle = 'toggle';
                }
                ?>
                <script>
                    jQuery(document).ready(function(){
                        //alert('<?php echo $jFunctionToggle; ?>');
                        jQuery(window).on('load', function(){
                            setTimeout(function(){ 
                                 jQuery('.elementor-element-<?php echo $element->get_id(); ?>').<?php echo $jFunctionToggle; ?>();
                            }, <?php echo $settings['dce_visibility_load_delay'] ? $settings['dce_visibility_load_delay'] : '0'; ?>);
                        });
                    });
                </script>
                <?php
            }
        }
    }

}
