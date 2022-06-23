<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use DynamicContentForElementor\DCE_Helper;
use DynamicContentForElementor\Controls\DCE_Group_Control_Transform_Element;
use DynamicContentForElementor\Controls\DCE_Group_Control_Filters_CSS;
use DynamicContentForElementor\Controls\DCE_Group_Control_Ajax_Page;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Dynamic Posts
 *
 * Elementor widget for Dynamic Content for Elementor
 *
 */
class DCE_Widget_DynamicPosts extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-acfposts';
    }

    static public function get_position() {
        return 1;
    }

    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('Dynamic Posts', 'dynamic-content-for-elementor');
    }

    public function get_description() {
        return __('Dynamic Posts allows to build archives from lists of articles with four different queries. You can display the list with various layouts and use them to shape blocks', 'dynamic-content-for-elementor');
    }

    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/dynamic-posts/';
    }

    public function get_icon() {
        return 'icon-dynamic_posts';
    }

    public function get_script_depends() {
        return ['imagesloaded', 'jquery-slick', 'swiper', 'isotope', 'infinitescroll', 'wow'];
    }
    
    public function get_dce_script_depends() {
        return ['dce-acf_posts'];
    }

    public function get_style_depends() {
      return [ 'animate' ];
      } 

    protected function _register_controls() {

        $taxonomies = DCE_Helper::get_taxonomies();
        $types = DCE_Helper::get_post_types();
        //$templates = DCE_Helper::get_all_template();
        // ------------------------------------------------------------------------------------ [SECTION]
        $this->start_controls_section(
                'section_cpt', [
            'label' => __('Post Type Query', 'dynamic-content-for-elementor'),
                ]
        );

        $this->add_control(
                'query_type', [
            'label' => __('Query Type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'options' => [
                'get_cpt' => [
                    'title' => 'Custom Post Type',
                    'icon' => 'fa fa-files-o',
                ],
                'dynamic_mode' => [
                    'title' => 'Dynamic',
                    'icon' => 'fa fa-cogs',
                ],
                'acf_relations' => [
                    'title' => 'ACF Relations',
                    'icon' => 'fa fa-american-sign-language-interpreting',
                ],
                'specific_posts' => [
                    'title' => 'From Specific Post',
                    'icon' => 'fa fa-list-ul',
                ]
            ],
            'default' => 'get_cpt',
                ]
        );
        // --------------------------------- [ Specific Pages ]        
        foreach ($types as $t => $tname) {
            //$data_page = DCE_Helper::get_posts_by_type($t, get_the_ID(), false);
            //if (count($data_page) > 0) {

            $object_t = get_post_type_object($t)->labels;
            $label_t = $object_t->name;

            /* $this->add_control(
              'specific_pages' . $t, [
              'label' => __($label_t, 'dynamic-content-for-elementor'),
              'type' => Controls_Manager::SELECT2,
              //'options' => DCE_Helper::get_pages(),
              'options' => $data_page,
              //'groups' => DCE_Helper::get_all_posts(get_the_ID(), true),
              'multiple' => true,
              'label_block' => true,
              'condition' => [
              'query_type' => 'specific_posts',
              ],
              ]
              ); */
            $this->add_control(
                    'specific_pages' . $t,
                    [
                        'label' => __($label_t, 'dynamic-content-for-elementor'),
                        'type' => 'ooo_query',
                        'placeholder' => __('Post Title', 'dynamic-content-for-elementor'),
                        'label_block' => true,
                        'query_type' => 'posts',
                        'object_type' => $t,
                        'multiple' => true,
                        'condition' => [
                            'query_type' => 'specific_posts',
                        ],
                    ]
            );
            //}
        }

        // --------------------------------- [ ACF relations ]
        $this->add_control(
                'acf_relationship', [
            'label' => __('Relations (ACF)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            //'options' => get_post_taxonomies( $post->ID ),
            'options' => DCE_Helper::get_acf_fields('relationship'),
            'default' => '0',
            'condition' => [
                'query_type' => 'acf_relations',
            ],
                ]
        );
        // --------------------------------- [ Custom Post Type ]

        $this->add_control(
                'post_type', [
            'label' => __('Post Type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'options' => $types,
            'multiple' => true,
            'label_block' => true,
            'default' => 'post',
            'condition' => [
                'query_type' => 'get_cpt',
            ],
                ]
        );
        $this->add_control(
                'exclude_io', [
            'label' => __('Exclude myself', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => [
                'query_type' => ['get_cpt', 'dynamic_mode']
            ]
                ]
        );
        $this->add_control(
                'exclude_page_parent', [
            'label' => __('Exclude page parent', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'query_type' => ['get_cpt', 'dynamic_mode']
            ]
                ]
        );



        /* $this->add_control(
          'exclude_posts', [
          'label' => __('Exclude posts', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SELECT2,
          'separator' => 'before',
          'multiple' => true,
          'options' => DCE_Helper::get_all_posts(),
          //'groups' => DCE_Helper::get_all_posts(get_the_ID(), true),
          'default' => '',
          'condition' => [
          'query_type' => ['get_cpt', 'dynamic_mode'],
          ],
          ]
          ); */
        $this->add_control(
                'exclude_posts',
                [
                    'label' => __('Exclude posts', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Post Title', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'separator' => 'before',
                    'multiple' => true,
                    'condition' => [
                        'query_type' => ['get_cpt', 'dynamic_mode'],
                    ],
                ]
        );
        $this->add_control(
                'num_posts', [
            'label' => __('Number of Posts', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => '-1',
            'separator' => 'before',
            'condition' => [
                'query_type' => ['get_cpt', 'dynamic_mode', 'acf_relations'],
            ],
                ]
        );
        $this->add_control(
                'post_offset', [
            'label' => __('Posts Offset', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => '0',
            'condition' => [
                'query_type' => ['get_cpt', 'dynamic_mode'],
                'num_posts!' => '-1'
            ],
                ]
        );
        $this->add_control(
            'orderby', [
                'label' => __('Order By', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => DCE_Helper::get_post_orderby_options(),
                'default' => 'date',
                /*'condition' => [
                    'query_type' => ['get_cpt', 'dynamic_mode', 'acf_relations'],
                ],*/
            ]
        );
        $this->add_control(
                'acf_metakey', [
            'label' => __('ACF meta', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'groups' => $this->get_acf_field(true, true),
            'default' => '0',
            'condition' => [
                'orderby' => ['meta_value_date', 'meta_value_num'],
            ]
                ]
        );
        $this->add_control(
                'order', [
            'label' => __('Order', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'ASC' => 'Ascending',
                'DESC' => 'Descending'
            ],
            'default' => 'desc',
            'condition' => [
                /*'query_type' => ['get_cpt', 'dynamic_mode', 'acf_relations'],*/
                'orderby!' => ['random']
            ],
                ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
                'section_querytax', [
            'label' => __('Taxonomy Query Filter', 'dynamic-content-for-elementor'),
            'condition' => [
                'query_type' => ['get_cpt', 'dynamic_mode'],
            ],
                ]
        );


        $this->add_control(
                'taxonomy', [
            'label' => __('Select Taxonomy', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            //'options' => get_post_taxonomies( $post->ID ),
            'options' => ['' => __('All', 'dynamic-content-for-elementor')] + $taxonomies, //get_taxonomies(array('public' => true)),
            'default' => '',
            'description' => __('Filter results by selected taxonomy', 'dynamic-content-for-elementor'),
            'label_block' => true,
            'condition' => [
                                /*'terms_from_acf' => ''*/
                            ],
                ]
        );
        /* $this->add_control(
          'taxonomy',
          [
          'label' => __( 'Taxonomy', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::SELECT2,
          'options' => [ '' => __('None','dynamic-content-for-elementor') ] + get_taxonomies( array( 'public' => true ) ),
          'multiple' => true,
          'label_block' => true,
          'default' => '',
          'condition' => [
          'query_type' => ['get_cpt','dynamic_mode'],
          ],
          ]
          ); */
        




        foreach ($taxonomies as $tkey => $atax) {
            if ($tkey) {
                /* $this->add_control(
                  'terms_' . $tkey, [
                  'label' => __('Terms', 'dynamic-content-for-elementor'), //.' '.$atax,
                  'type' => Controls_Manager::SELECT2,
                  //'groups' => \DynamicContentForElementor\DCE_Helper::get_taxonomies_terms(),
                  'options' => ['' => __('All', 'dynamic-content-for-elementor')] + \DynamicContentForElementor\DCE_Helper::get_taxonomy_terms($tkey), // + ['dce_current_post_terms' => __('Dynamic Current Post Terms', 'dynamic-content-for-elementor')],
                  'description' => __('Filter results by selected taxonomy term', 'dynamic-content-for-elementor'),
                  'multiple' => true,
                  'label_block' => true,
                  'condition' => [
                  //'taxonomy!' => '',
                  'taxonomy' => $tkey,
                  'terms_current_post' => '',
                  'terms_from_acf' => ''
                  ],
                  'render_type' => 'template',
                  'dynamic' => [
                  'active' => true,
                  ],
                  ]
                  ); */
                $this->add_control(
                        'terms_' . $tkey,
                        [
                            'label' => __('<b>Include</b> Terms of ' . $tkey, 'dynamic-content-for-elementor'),
                            'type' => 'ooo_query',
                            'placeholder' => __('All terms', 'dynamic-content-for-elementor'),
                            'label_block' => true,
                            'query_type' => 'terms',
                            'object_type' => $tkey,
                            //'description' => __('Filter results by selected taxonomy term', 'dynamic-content-for-elementor'),
                            'render_type' => 'template',
                            'multiple' => true,
                            'condition' => [
                                'taxonomy' => $tkey,
                                'terms_current_post' => '',
                                //'terms_from_acf' => ''
                            ],
                        ]
                );
            }
        }
        $this->add_control(
                'combination_taxonomy',
                [
                    'label' => __('<b>Include</b> Combination', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'AND' => [
                            'title' => __('AND', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-circle',
                        ],
                        'OR' => [
                            'title' => __('OR', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-circle-o',
                        ]
                    ],
                    'toggle' => false,
                    
                    'default' => 'OR',
                    'condition' => [
                        'taxonomy!' => '',
                        'terms_current_post' => '',
                        //'terms_from_acf' => ''
                    ]
                ]
        );
        //
        foreach ($taxonomies as $tkey => $atax) {
            if ($tkey) {
                $this->add_control(
                        'terms_' . $tkey . '_excluse',
                        [
                            'label' => __('<b>Exclude</b> Terms of ' . $tkey, 'dynamic-content-for-elementor'),
                            'type' => 'ooo_query',
                            'placeholder' => __('No terms', 'dynamic-content-for-elementor'),
                            'label_block' => true,
                            'query_type' => 'terms',
                            'object_type' => $tkey,
                            //'description' => __('Filter results by selected taxonomy term', 'dynamic-content-for-elementor'),
                            'render_type' => 'template',
                            'multiple' => true,
                            'condition' => [
                                'taxonomy' => $tkey,
                                'terms_current_post' => '',
                                //'terms_from_acf' => ''
                            ],
                        ]
                );
            }
        }
        $this->add_control(
                'combination_taxonomy_excluse',
                [
                    'label' => __('<b>Exclude</b> Combination', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'OR' => [
                            'title' => __('AND', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-circle',
                        ],
                        'AND' => [
                            'title' => __('OR', 'dynamic-content-for-elementor'),
                            'icon' => 'fa fa-circle-o',
                        ]
                    ],
                    'toggle' => false,
                    'default' => 'OR',
                    'condition' => [
                        'taxonomy!' => '',
                        'terms_current_post' => '',
                        //'terms_from_acf' => ''
                    ]
                ]
        );
        $this->add_control(
                'terms_current_post', [
            'label' => __('Dynamic Current Post Terms', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __('Filter results by taxonomy terms associated to current post', 'dynamic-content-for-elementor'),
            'separator' => 'before',

            'condition' => [
                'taxonomy!' => '',
                'terms_from_acf' => ''
            ],
                ]
        );
        $this->add_control(
                'terms_from_acf', [
            'label' => __('Use ACF Taxonomy', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __('The results of the filter for taxonomy terms indicated in the ACF Taxonomy type field. If the ACF Taxonomy value in the post is set, it will ignore the previously defined include / exclude filters.', 'dynamic-content-for-elementor'),
            'separator' => 'before',
            'condition' => [
                
                'terms_current_post' => ''
            ],
                ]
        );
        // ACF Taxomomy ---------------------
        $this->add_control(
                'acf_taxonomy', [
            'label' => __('Taxonomy (ACF)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            //'options' => get_post_taxonomies( $post->ID ),
            'options' => DCE_Helper::get_acf_fields('taxonomy'),
            'default' => '',
            'condition' => [
                'terms_from_acf' => 'yes',
            ],
                ]
        );

        $this->add_control(
                'category', [
            'label' => __('Terms ID', 'dynamic-content-for-elementor'),
            'description' => __('Comma separated list of category ids', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HIDDEN,
            'default' => '', //do_shortcode('[product_cat_id]'),
            'label_block' => true,
            'separator' => 'before',
            'condition' => [
                'taxonomy!' => '',
            ],
                ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
                'section_querydate', [
            'label' => __('Date Query Filter', 'dynamic-content-for-elementor'),
            'condition' => [
                'query_type' => ['get_cpt', 'dynamic_mode'],
            ],
                ]
        );
        $this->add_control(
                'querydate_mode', [
            'label' => __('Date Filter', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => '',
            'label_block' => true,
            'options' => [
                '' => __('No Filter', 'dynamic-content-for-elementor'),
                'past' => __('Past', 'dynamic-content-for-elementor'),
                'future' => __('Future', 'dynamic-content-for-elementor'),
                'today' => __('Today', 'dynamic-content-for-elementor'),
                'yesterday' => __('Yesterday', 'dynamic-content-for-elementor'),
                'days' => __('Past Days', 'dynamic-content-for-elementor'),
                'weeks' => __('Past Weeks', 'dynamic-content-for-elementor'),
                'months' => __('Past Months', 'dynamic-content-for-elementor'),
                'years' => __('Past Years', 'dynamic-content-for-elementor'),
                'period' => __('Period', 'dynamic-content-for-elementor'),
            ],
                ]
        );
        $this->add_control(
                'querydate_field', [
            'label' => __('Date Field', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => true,
            'options' => [
                'post_date' => [
                    'title' => __('Publish Date', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-calendar',
                ],
                /*'post_modified' => [
                    'title' => __('Modified Date', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-edit',
                ],*/
                'post_meta' => [
                    'title' => __('Post Meta', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-square',
                ],
            ],
            'default' => 'post_date',
            'toggle' => false,
            'condition' => [
                //'querydate_mode!' => '',
                'querydate_mode!' => ['', 'future'],
            ],
                ]
        );
        $this->add_control(
                'querydate_field_meta',
                [
                    'label' => __('Meta Field', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Meta key or Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'metas',
                    'object_type' => 'post',
                    'description' => __('Selected Post Meta value must be stored if format "Ymd", like ACF Date', 'dynamic-content-for-elementor'),
                    'separator' => 'before',
                    'condition' => [
                        'querydate_mode!' => '',
                        'querydate_mode!' => 'future',
                        'querydate_field' => 'post_meta'
                    ]
                ]
        );
        $this->add_control(
                'querydate_field_meta_future',
                [
                    'label' => __('Meta Field', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Meta key or Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'metas',
                    'object_type' => 'post',
                    'description' => __('Selected Post Meta value must be stored if format "Ymd", like ACF Date', 'dynamic-content-for-elementor'),
                    'separator' => 'before',
                    'condition' => [
                        'querydate_mode' => 'future',
                    ]
                ]
        );
        // number of days / months / years elapsed
        $this->add_control(
                'querydate_range', [
            'label' => __('Number of (days/months/years) elapsed', 'dynamic-content-for-elementor'),
            'label_block' => true,
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'condition' => [
                'querydate_mode' => ['days', 'weeks', 'months', 'years']
            ]
                ]
        );

        
        $this->add_control(
                'querydate_date_type', [
            'label' => __('Date Input Mode', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => true,
            'options' => [
                '' => [
                    'title' => __('Static', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-calendar-plus-o',
                ],
                '_dynamic' => [
                    'title' => __('Dynamic', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-cogs',
                ],
            ],
            'default' => '_dynamic',
            'toggle' => false,
            'separator' => 'before',
            'condition' => [
                'querydate_mode' => 'period'
            ],
                ]
        );
        $this->add_control(
                'querydate_date_from', [
            'label' => __('Date FROM', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DATE_TIME,
            'condition' => [
                'querydate_mode' => 'period',
                'querydate_date_type' => '',
            ],
                ]
        );
        $this->add_control(
                'querydate_date_to', [
            'label' => __('Date TO', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DATE_TIME,
            'condition' => [
                'querydate_mode' => 'period',
                'querydate_date_type' => '',
            ],
                ]
        );
        $this->add_control(
                'querydate_date_from_dynamic', [
            'label' => __('Date FROM', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'condition' => [
                'querydate_mode' => 'period',
                'querydate_date_type' => '_dynamic',
            ],
                ]
        );
        $this->add_control(
                'querydate_date_to_dynamic', [
            'label' => __('Date TO', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'condition' => [
                'querydate_mode' => 'period',
                'querydate_date_type' => '_dynamic',
            ],
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_queryuser', [
            'label' => __('Author Query Filter', 'dynamic-content-for-elementor'),
            'condition' => [
                'query_type' => ['get_cpt', 'dynamic_mode'],
            ],
                ]
        );
        $this->add_control(
                'by_users',
                [
                    'label' => __('<b>Include</b> Author', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Select author', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'multiple' => true,
                    'query_type' => 'users',
                    //'object_type'   => 'editor',
                    'description' => __('Filter posts by selected Authors', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'by_author' => ''
                    ]
                ]
        );
        $this->add_control(
                'exclude_users',
                [
                    'label' => __('<b>Exclude</b> Author', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('No', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'multiple' => true,
                    'query_type' => 'users',
                    //'object_type'   => 'editor',
                    'description' => __('Filter posts by selected Authors', 'dynamic-content-for-elementor'),
                    'separator' => 'after',
                    'condition' => [
                        'by_author' => ''
                    ]
                ]
        );
        $this->add_control(
                'byauthor_options', [
            'label' => __('Author (Archive)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            
                ]
        );
        $this->add_control(
                'by_author', [
            'label' => __('From current Author', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __('From current Post Author or Author in Archive Author page', 'dynamic-content-for-elementor'),
                ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
                'section_queryopt', [
            'label' => __('Parent Query Filter', 'dynamic-content-for-elementor'),
                ]
        );

        $this->add_control(
                'page_parent', [
            'label' => __('Enable ParentChild Options', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
                ]
        );
        $this->add_control(
                'parent_source', [
            'label' => __('Get from Parent', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __('Same', 'dynamic-content-for-elementor'),
            'label_off' => __('other', 'dynamic-content-for-elementor'),
            'condition' => [
                'page_parent' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'child_source', [
            'label' => __('Get from Children', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __('Same', 'dynamic-content-for-elementor'),
            'label_off' => __('other', 'dynamic-content-for-elementor'),
            'condition' => [
                'page_parent' => 'yes',
                'parent_source' => ''
            ],
                ]
        );
        /* $this->add_control(
          'specific_page_parent', [
          'label' => __('Get from custom', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SELECT,
          'options' => DCE_Helper::get_pages(),
          'default' => '0',
          'condition' => [
          'page_parent' => 'yes',
          'parent_source' => '',
          'child_source' => ''
          ],
          ]
          ); */
        $this->add_control(
                'specific_page_parent',
                [
                    'label' => __('Get from custom', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Page Title', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'object_type' => 'page',
                    'condition' => [
                        'page_parent' => 'yes',
                        'parent_source' => '',
                        'child_source' => ''
                    ],
                ]
        );
        $this->end_controls_section();
        // ----------------------------------------------------- [SECTION Layout]
        $this->start_controls_section(
                'section_layout', [
            'label' => __('Layout', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'templatemode_enable' => '',
                'native_templatemode_enable' => ''
            ],
                ]
        );
        $this->add_control(
                'order_image', [
            'label' => __('Order image', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'frontend_available' => true,
                ]
        );
        $this->add_responsive_control(
                'image_position', [
            'label' => __('Image Position', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'top',
            'tablet_default' => 'top',
            'mobile_default' => 'top',
            'options' => [
                'top' => __('Top', 'dynamic-content-for-elementor'),
                'left' => __('Left', 'dynamic-content-for-elementor'),
                'right' => __('Right', 'dynamic-content-for-elementor'),
                'alternate' => __('Alternate', 'dynamic-content-for-elementor'),
            ],
            'prefix_class' => 'image-acfposts%s-position-',
            'condition' => [
                'posts_style!' => 'timeline',
                'order_image' => ''
            ],
                ]
        );
        $this->add_responsive_control(
                'image_rate', [
            'label' => __('Distribution', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 50,
                'unit' => '%',
            ],
            'tablet_default' => [
                'unit' => '%',
            ],
            'mobile_default' => [
                'unit' => '%',
            ],
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 1,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-acfposts_image' => 'width: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .dce-acfposts_content' => 'width: calc( 100% - {{SIZE}}{{UNIT}} );',
            ],
            'condition' => [
                'image_position!' => 'top',
                'order_image' => '',
                'posts_style!' => 'timeline',
            ],
                ]
        );
        $this->add_control(
                'text_position', [
            'label' => __('Text Zone Position', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'outside',
            'options' => [
                'outside' => __('Natural', 'dynamic-content-for-elementor'),
                'inside' => __('Floats in front', 'dynamic-content-for-elementor'),
            ],
            'default' => 'outside',
            'prefix_class' => 'text-acfposts-position-',
            'condition' => [
                'posts_style!' => 'timeline',
                'image_position' => 'top',
                'order_image' => ''
            ],
                ]
        );

        $this->add_responsive_control(
                'text_space', [
            'label' => __('Vertical Text Movement', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'unit' => '%',
            ],
            'tablet_default' => [
                'unit' => '%',
            ],
            'mobile_default' => [
                'unit' => '%',
            ],
            'size_units' => ['%', 'px'],
            'range' => [
                '%' => [
                    'min' => -100,
                    'max' => 100,
                ],
                'px' => [
                    'min' => -200,
                    'max' => 200,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-acfposts_content' => 'top: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'image_position' => 'top',
                'text_position' => 'inside',
                'posts_style!' => 'timeline',
                'order_image' => ''
            ],
                ]
        );
        //
        $this->add_control(
                'dysplay_items', [
            'label' => __('Display', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        //
        $this->add_control(
                'show_title', [
            'label' => __('Show Title', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => false,
            'toggle' => false,
            'options' => [
                '1' => [
                    'title' => __('Yes', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-check',
                ],
                '0' => [
                    'title' => __('No', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-ban',
                ]
            ],
            'default' => '1',
                ]
        );
        $this->add_control(
                'show_image', [
            'label' => __('Show Image', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'label_block' => false,
            'options' => [
                '1' => [
                    'title' => __('Yes', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-check',
                ],
                '0' => [
                    'title' => __('No', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-ban',
                ]
            ],
            'default' => '1',
                ]
        );
        $this->add_control(
                'show_textcontent', [
            'label' => __('Show Content', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'label_block' => false,
            'options' => [
                '1' => [
                    'title' => __('Yes', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-check',
                ],
                '0' => [
                    'title' => __('No', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-ban',
                ],
                '2' => [
                    'title' => __('Excerpt', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-left',
                ]
            ],
            'default' => '0',
                ]
        );

        $this->add_control(
                'show_metadata', [
            'label' => __('Show Meta Data', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'label_block' => false,
            'options' => [
                '1' => [
                    'title' => __('Yes', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-check',
                ],
                '0' => [
                    'title' => __('No', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-ban',
                ]
            ],
            'default' => '0',
                ]
        );
        $this->add_control(
                'show_author', [
            'label' => __('Show Author', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'label_block' => false,
            'options' => [
                '1' => [
                    'title' => __('Yes', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-check',
                ],
                '0' => [
                    'title' => __('No', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-ban',
                ]
            ],
            'default' => '0',
                ]
        );
        $this->add_control(
                'show_date', [
            'label' => __('Show Date', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'label_block' => false,
            'options' => [
                '1' => [
                    'title' => __('Yes', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-check',
                ],
                '0' => [
                    'title' => __('No', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-ban',
                ]
            ],
            'default' => '0',
                ]
        );
        $this->add_control(
                'show_readmore', [
            'label' => __('Show Read More', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'label_block' => false,
            'options' => [
                '1' => [
                    'title' => __('Yes', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-check',
                ],
                '0' => [
                    'title' => __('No', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-ban',
                ]
            ],
            'default' => '0',
                ]
        );

        $this->add_control(
                'show_acfitems', [
            'label' => __('Show ACF Items', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'label_block' => false,
            'options' => [
                '1' => [
                    'title' => __('Yes', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-check',
                ],
                '0' => [
                    'title' => __('No', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-ban',
                ]
            ],
            'default' => '0'
                ]
        );

        // -------- ORDERING
        $repeater = new Repeater();
        $chid = $repeater->get_name();

        $repeater->add_control(
                'list_name', [
            'label' => __('Name', 'plugin-domain'),
            'type' => \Elementor\Controls_Manager::HIDDEN,
            'default' => __('List Name', 'plugin-domain'),
            'label_block' => true,
                ]
        );

        $this->add_control(
                'list_layout_posts',
                [
                    'label' => __('Ordering', 'plugin-domain'),
                    'separator' => 'before',
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'item_actions' => [
                        'add' => false,
                        'duplicate' => false,
                        'remove' => false,
                        'sort' => true,
                    ],
                    'default' => [
                        [
                            'list_name' => __('Image', 'plugin-domain'),
                            'list_html_content' => __('<p>Image</p>', 'dynamic-content-for-elementor'),
                            '_id' => 'sortimg',
                        ],
                        [
                            'list_name' => __('Date', 'plugin-domain'),
                            'list_html_content' => __('<p>Date</p>', 'dynamic-content-for-elementor'),
                            '_id' => 'sortdate',
                        ],
                        [
                            'list_name' => __('Title', 'plugin-domain'),
                            '_id' => 'sorttit',
                        ],
                        [
                            'list_name' => __('Meta Data', 'plugin-domain'),
                            'list_html_content' => __('<p>Meta data</p>', 'dynamic-content-for-elementor'),
                            '_id' => 'sortdata',
                        ],
                        [
                            'list_name' => __('Content', 'plugin-domain'),
                            'list_html_content' => __('<p>>Content</p>', 'dynamic-content-for-elementor'),
                            '_id' => 'sortcont',
                        ],
                        [
                            'list_name' => __('Author', 'plugin-domain'),
                            'list_html_content' => __('<p>Auhtor</p>', 'dynamic-content-for-elementor'),
                            '_id' => 'sortauth',
                        ],
                        [
                            'list_name' => __('ACF items', 'plugin-domain'),
                            'list_html_content' => __('<p>ACF Items</p>', 'dynamic-content-for-elementor'),
                            '_id' => 'sortacf',
                        ], [
                            'list_name' => __('Read More', 'plugin-domain'),
                            'list_html_content' => __('<p>Read More</p>', 'dynamic-content-for-elementor'),
                            '_id' => 'sortrem',
                        ],
                    ],
                    'title_field' => '{{{ list_name }}}',
                ]
        );
        $this->end_controls_section();



        // ------------------------------------------------------ [SECTION Style of List]

        $this->start_controls_section(
                'section_render', [
            'label' => __('Render', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );
        $this->add_control(
                'posts_style', [
            'label' => __('Render as ', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'flexgrid',
            'options' => [
                'simplegrid' => __('Row', 'dynamic-content-for-elementor'),
                'flexgrid' => __('Grid (Flex)', 'dynamic-content-for-elementor'),
                'grid' => __('Grid Masonry (filters)', 'dynamic-content-for-elementor'),
                'carousel' => __('Slick (carousel)', 'dynamic-content-for-elementor'),
                'swiper' => __('Swiper (carousel)', 'dynamic-content-for-elementor'),
                'timeline' => __('Timeline', 'dynamic-content-for-elementor'),
            //'dualslider' => __('Dual Slider (Carousel + Slider)', 'dynamic-content-for-elementor'),
            ]
                ]
        );
        $this->add_control(
                'filters_enable', [
            'label' => __('Show Filters', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'condition' => [
                'posts_style' => 'grid',
            ],
                ]
        );
        $this->add_control(
                'pagination_enable', [
            'label' => __('Show Pagination', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'posts_style' => ['simplegrid', 'flexgrid', 'grid'],
                'infiniteScroll_enable' => ''
            ],
                ]
        );
        /* $this->add_control(
          'pagination_noscript', [
          'label' => __('NoScript', 'dynamic-content-for-elementor'),
          'description' => __('Il paginatore si visualizzerà solo dal motore di ricerca.', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SWITCHER,
          'condition' => [
          'posts_style' => ['simplegrid', 'flexgrid', 'grid'],
          //'infiniteScroll_enable' => ''
          ],
          ]
          ); */
        $this->add_control(
                'infiniteScroll_enable', [
            'label' => __('Infinite Scroll', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'condition' => [
                'posts_style' => ['simplegrid', 'flexgrid', 'grid'],
                'pagination_enable' => ''
            ],
                ]
        );
        $this->add_control(
                'unic_date', [
            'label' => __('Use the years above the block', 'dynamic-content-for-elementor'),
            'description' => __('View demo: <a href="https://www.dynamic.ooo/demo/history-posts/" target="_blank" >History</a>', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'separator' => 'before',
            'condition' => [
                'posts_style' => ['flexgrid', 'grid'],
            ]
                ]
        );
        $this->add_control(
                'masking_enable', [
            'label' => __('Remove Masking', 'dynamic-content-for-elementor'),
            'description' => 'Remove the mask on the carousel to allow the display of the elements outside.',
            'type' => Controls_Manager::SWITCHER,
            'separator' => 'before',
            'prefix_class' => 'no-masking-',
            'default' => '',
            'condition' => [
                'posts_style' => ['swiper', 'carousel'],
            ]
                ]
        );
        /* $this->add_control(
          'masking_style', [
          'label' => __('Mask option', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SELECT,
          'default' => 'booth',
          'options' => [
          'y' => __('Y', 'dynamic-content-for-elementor'),
          'x' => __('X', 'dynamic-content-for-elementor'),
          'booth' => __('Booth', 'dynamic-content-for-elementor'),


          ],
          'prefix_class' => 'no-masking-',
          'condition' => [
          'masking_enable' => 'yes',
          ]
          ]
          ); */
        $this->end_controls_section();

        /////////////////////////////////////////////////////////////////// [ SECTION InfiniteScroll ]
        $this->start_controls_section(
                'section_infinitescroll', [
            'label' => __('Infinite Scroll', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'infiniteScroll_enable' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'infiniteScroll_trigger', [
            'label' => __('Trigger', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'scroll',
            'frontend_available' => true,
            'options' => [
                'scroll' => __('On Scroll Page', 'dynamic-content-for-elementor'),
                'button' => __('On Click Button', 'dynamic-content-for-elementor'),
            ],
                ]
        );
        $this->add_control(
                'infiniteScroll_label_button', [
            'label' => __('Label Button', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => __('View more', 'dynamic-content-for-elementor'),
            'condition' => [
                'infiniteScroll_trigger' => 'button',
            ],
                ]
        );
        $this->add_control(
                'infiniteScroll_enable_status', [
            'label' => __('Enable Status', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'separator' => 'before',
                ]
        );

        $this->add_control(
                'infiniteScroll_show_preview', [
            'label' => __('Show Status PREVIEW in Editor Mode', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'separator' => 'before',
            'condition' => [
                'infiniteScroll_enable_status' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'infiniteScroll_loading_type', [
            'label' => __('Loading Type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'options' => [
                'ellips' => [
                    'title' => __('Ellips', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-ellipsis-h',
                ],
                'text' => [
                    'title' => __('Label Text', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-font',
                ]
            ],
            'default' => 'ellips',
            'separator' => 'before',
            'condition' => [
                'infiniteScroll_enable_status' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'infiniteScroll_label_loading', [
            'label' => __('Label Loading', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Loading...', 'dynamic-content-for-elementor'),
            'condition' => [
                'infiniteScroll_enable_status' => 'yes',
                'infiniteScroll_loading_type' => 'text',
            ],
                ]
        );
        $this->add_control(
                'infiniteScroll_label_last', [
            'label' => __('Label Last', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => __('End of content', 'dynamic-content-for-elementor'),
            'condition' => [
                'infiniteScroll_enable_status' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'infiniteScroll_label_error', [
            'label' => __('Label Error', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => __('No more articles to load', 'dynamic-content-for-elementor'),
            'condition' => [
                'infiniteScroll_enable_status' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'infiniteScroll_enable_history', [
            'label' => __('Enable History', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'separator' => 'before',
            'frontend_available' => true,
                ]
        );

        $this->end_controls_section();


        /////////////////////////////////////////////////////////////////// [ SECTION Pagination ]
        $this->start_controls_section(
                'section_pagination', [
            'label' => __('Pagination', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'pagination_enable' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'pagination_show_numbers', [
            'label' => __('Show Numbers', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                ]
        );
        $this->add_control(
                'pagination_range', [
            'label' => __('Range of numbers', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 4,
            'condition' => [
                'pagination_show_numbers' => 'yes',
            ]
                ]
        );



        $this->add_control(
                'pagination_show_prevnext', [
            'label' => __('Show Prev/Next', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'pagination_icon_prevnext', [
            'label' => __('Icon Prev/Next', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::ICON,
            'default' => 'fa fa-long-arrow-right',
            'include' => [
                'fa fa-arrow-right',
                'fa fa-angle-right',
                'fa fa-chevron-circle-right',
                'fa fa-caret-square-o-right',
                'fa fa-chevron-right',
                'fa fa-caret-right',
                'fa fa-angle-double-right',
                'fa fa-hand-o-right',
                'fa fa-arrow-circle-right',
                'fa fa-long-arrow-right',
                'fa fa-arrow-circle-o-right',
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'pagination_prev_label', [
            'label' => __('Previous Label', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Previous', 'dynamic-content-for-elementor'),
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ],
                ]
        );

        $this->add_control(
                'pagination_next_label', [
            'label' => __('Next Label', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Next', 'dynamic-content-for-elementor'),
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ],
                ]
        );



        $this->add_control(
                'pagination_show_firstlast', [
            'label' => __('Show First/Last', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'separator' => 'before'
                ]
        );
        $this->add_control(
                'pagination_icon_firstlast', [
            'label' => __('Icon First/Last', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::ICON,
            'default' => 'fa fa-long-arrow-right',
            'include' => [
                'fa fa-arrow-right',
                'fa fa-angle-right',
                'fa fa-chevron-circle-right',
                'fa fa-caret-square-o-right',
                'fa fa-chevron-right',
                'fa fa-caret-right',
                'fa fa-angle-double-right',
                'fa fa-hand-o-right',
                'fa fa-arrow-circle-right',
                'fa fa-long-arrow-right',
                'fa fa-arrow-circle-o-right',
            ],
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'pagination_first_label', [
            'label' => __('Previous Label', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => __('First', 'dynamic-content-for-elementor'),
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ],
                ]
        );

        $this->add_control(
                'pagination_last_label', [
            'label' => __('Next Label', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Last', 'dynamic-content-for-elementor'),
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ],
                ]
        );

        $this->add_control(
                'pagination_show_progression', [
            'label' => __('Show Progression', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'separator' => 'before'
                ]
        );
        $this->end_controls_section();


        /////////////////////////////////////////////////////////////////// [ SECTION Carosello ]

        $this->start_controls_section(
                'section_carousel', [
            'label' => __('Carousel', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'posts_style' => ['carousel', 'dualslider'],
            ],
                ]
        );
        $slides_to_show = range(1, 10);

        $slides_to_show = array_combine($slides_to_show, $slides_to_show);

        $this->add_responsive_control(
                'slides_to_show', [
            'label' => __('Slides to Show', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => $slides_to_show,
            'default' => '4',
            'tablet_default' => '2',
            'mobile_default' => '1',
            'frontend_available' => true,
                ]
        );

        $slides_to_scroll = range(1, 10);

        $slides_to_scroll = array_combine($slides_to_scroll, $slides_to_scroll);

        $this->add_responsive_control(
                'slides_to_scroll', [
            'label' => __('Slides to Scroll', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => '1',
            'tablet_default' => '1',
            'mobile_default' => '1',
            'options' => $slides_to_scroll,
            'frontend_available' => true,
                ]
        );
        $this->add_responsive_control(
                'carousel_arrow_enable', [
            'label' => __('Arrows', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'frontend_available' => true,
                ]
        );
        $this->add_control(
                'carousel_arrow_style', [
            'label' => __('Arrow Style', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'inside',
            'options' => [
                'inside' => __('Inside', 'dynamic-content-for-elementor'),
                'outside' => __('Outside', 'dynamic-content-for-elementor'),
            ],
            'condition' => [
                'carousel_arrow_enable' => 'yes',
            ],
            'prefix_class' => 'arrows-acfposts-position-',
                ]
        );
        $this->add_responsive_control(
                'carousel_dots_enable', [
            'label' => __('Dots', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
                ]
        );

        $this->add_control(
                'carousel_infinite_enable', [
            'label' => __('Loop', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
                ]
        );

        $this->add_control(
                'carousel_speed', [
            'label' => __('Animation Speed', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 500,
            'frontend_available' => true,
                ]
        );
        $this->add_control(
                'carousel_autoplay_enable', [
            'label' => __('Auto Play', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
                ]
        );
        $this->add_control(
                'carousel_autoplayspeed', [
            'label' => __('Autoplay Speed', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 5000,
            'frontend_available' => true,
            'condition' => [
                'carousel_autoplay_enable!' => ''
            ]
                ]
        );
        $this->add_control(
                'carousel_center_enable', [
            'label' => __('Center Mode', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
                ]
        );
        $this->add_control(
                'carousel_effect', [
            'label' => __('Effect', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'slide',
            'options' => [
                'slide' => __('Slide', 'dynamic-content-for-elementor'),
                'fade' => __('Fade', 'dynamic-content-for-elementor'),
            ],
            'condition' => [
                'slides_to_show' => '1',
            ],
            'frontend_available' => true,
                ]
        );
        $this->end_controls_section();


        ///////////////////////////////////////////////////////////// [ SECTION Swiper ]
        //
        // ------------------------------------------------------------------------------- Base Settings, Slides grid, Grab Cursor
        $this->start_controls_section(
                'section_swiper_settings', [
            'label' => __('Swiper Settings', 'dynamic-content-for-elementor'),
            'condition' => [
                'posts_style' => 'swiper',
            ]
                ]
        );
        $this->add_control(
                'direction_slider', [
            'label' => __('Direction', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HIDDEN,
            'options' => [
                'horizontal' => __('Horizontal', 'dynamic-content-for-elementor'),
                'vertical' => __('Vertical', 'dynamic-content-for-elementor'),
            ],
            'default' => 'horizontal',
            'frontend_available' => true
                ]
        );
        $this->add_control(
                'speed_slider', [
            'label' => __('Speed', 'dynamic-content-for-elementor'),
            'description' => __('Duration of transition between slides (in ms)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 300,
            'min' => 0,
            'max' => 3000,
            'step' => 10,
            'frontend_available' => true
                ]
        );
        $this->add_control(
                'effects', [
            'label' => __('Effect of transition', 'dynamic-content-for-elementor'),
            'description' => __('Tranisition effect from the slides.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'slide' => __('Slide', 'dynamic-content-for-elementor'),
                'fade' => __('Fade', 'dynamic-content-for-elementor'),
                'cube' => __('Cube', 'dynamic-content-for-elementor'),
                'coverflow' => __('Coverflow', 'dynamic-content-for-elementor'),
                'flip' => __('Flip', 'dynamic-content-for-elementor'),
            ],
            'default' => 'slide',
            'frontend_available' => true,
                /* 'prefix_class' => 'effect-' */
                ]
        );
        $this->add_control(
                'centeredSlides', [
            'label' => __('Centered Slides', 'dynamic-content-for-elementor'),
            'description' => __('If true, then active slide will be centered, not always on the left side.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true
                ]
        );
        // -------------------------------- Progressione ------
        $this->add_control(
                'slideperview_options', [
            'label' => __('Slide per wiew', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'spaceBetween', [
            'label' => __('Space Between', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'tablet_default' => '',
            'mobile_default' => '',
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'frontend_available' => true
                ]
        );
        $this->add_responsive_control(
                'slidesPerView', [
            'label' => __('Slides Per View', 'dynamic-content-for-elementor'),
            'description' => __('Number of slides per view (slides visible at the same time on sliders container). If you use it with "auto" value and along with loop: true then you need to specify loopedSlides parameter with amount of slides to loop (duplicate). SlidesPerView: "auto"\'" is currently not compatible with multirow mode, when slidesPerColumn greater than 1', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => '1',
            //'tablet_default' => '',
            //'mobile_default' => '',
            'min' => 1,
            'max' => 12,
            'step' => 1,
            'frontend_available' => true
                ]
        );
        $this->add_responsive_control(
                'slidesColumn', [
            'label' => __('Slides Column', 'dynamic-content-for-elementor'),
            'description' => __('Number of slides per column, for multirow layout.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => '1',
            //'tablet_default' => '',
            //'mobile_default' => '',
            'min' => 1,
            'max' => 4,
            'step' => 1,
            'frontend_available' => true
                ]
        );
        $this->add_responsive_control(
                'slidesPerGroup', [
            'label' => __('Slides Per Group', 'dynamic-content-for-elementor'),
            'description' => __('Set numbers of slides to define and enable group sliding. Useful to use with slidesPerView > 1', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'tablet_default' => '',
            'mobile_default' => '',
            'min' => 1,
            'max' => 12,
            'step' => 1,
            'frontend_available' => true
                ]
        );
        // -------------------------------- Free Mode ------
        $this->add_control(
                'freemode_options', [
            'label' => __('Free Mode', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'freeMode', [
            'label' => __('Free Mode', 'dynamic-content-for-elementor'),
            'description' => __('If true then slides will not have fixed positions', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true
                ]
        );
        $this->add_control(
                'freeModeMomentum', [
            'label' => __('Free Mode Momentum', 'dynamic-content-for-elementor'),
            'description' => __('If true, then slide will keep moving for a while after you release it', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'condition' => [
                'freeMode' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'freeModeMomentumRatio', [
            'label' => __('Free Mode Momentum Ratio', 'dynamic-content-for-elementor'),
            'description' => __('Higher value produces larger momentum distance after you release slider', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'min' => 0,
            'max' => 10,
            'step' => 0.1,
            'frontend_available' => true,
            'condition' => [
                'freeMode' => 'yes',
                'freeModeMomentum' => 'yes'
            ]
                ]
        );
        $this->add_control(
                'freeModeMomentumVelocityRatio', [
            'label' => __('Free Mode Momentum Velocity Ratio', 'dynamic-content-for-elementor'),
            'description' => __('Higher value produces larger momentum velocity after you release slider', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'min' => 0,
            'max' => 10,
            'step' => 0.1,
            'frontend_available' => true,
            'condition' => [
                'freeMode' => 'yes',
                'freeModeMomentum' => 'yes'
            ]
                ]
        );
        $this->add_control(
                'freeModeMomentumBounce', [
            'label' => __('Free Mode Momentum Bounce', 'dynamic-content-for-elementor'),
            'description' => __('Set to false if you want to disable momentum bounce in free mode', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'frontend_available' => true,
            'condition' => [
                'freeMode' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'freeModeMomentumBounceRatio', [
            'label' => __('Free Mode Momentum Bounce Ratio', 'dynamic-content-for-elementor'),
            'description' => __('Higher value produces larger momentum bounce effect', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'min' => 0,
            'max' => 10,
            'step' => 0.1,
            'frontend_available' => true,
            'condition' => [
                'freeMode' => 'yes',
                'freeModeMomentumBounce' => 'yes'
            ]
                ]
        );
        $this->add_control(
                'freeModeMinimumVelocity', [
            'label' => __('Free Mode Momentum Velocity Ratio', 'dynamic-content-for-elementor'),
            'description' => __('Minimum touchmove-velocity required to trigger free mode momentum', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0.02,
            'min' => 0,
            'max' => 1,
            'step' => 0.01,
            'frontend_available' => true,
            'condition' => [
                'freeMode' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'freeModeSticky', [
            'label' => __('Free Mode Sticky', 'dynamic-content-for-elementor'),
            'description' => __('Set \'yes\' to enable snap to slides positions in free mode', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'condition' => [
                'freeMode' => 'yes',
            ]
                ]
        );
        // -------------------------------- Navigation options ------
        $this->add_control(
                'navigation_options', [
            'label' => __('Navigation options', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'useNavigation', [
            'label' => __('Use Navigation', 'dynamic-content-for-elementor'),
            'description' => __('Set "yes", you will use the navigation arrows.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'frontend_available' => true,
                ]
        );

        // ------------------------------------------------- Navigations Arrow Options
        $this->add_control(
                'navigation_arrow_color', [
            'label' => __('Arrows color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-button-next path, {{WRAPPER}} .swiper-button-prev path, ' => 'fill: {{VALUE}};',
                '{{WRAPPER}} .swiper-button-next line, {{WRAPPER}} .swiper-button-prev line, {{WRAPPER}} .swiper-button-next polyline, {{WRAPPER}} .swiper-button-prev polyline' => 'stroke: {{VALUE}};',
            ],
            'condition' => [
                'useNavigation' => 'yes'
            ]
                ]
        );

        $this->add_control(
                'useNavigation_animationHover', [
            'label' => __('Use animation in rollover', 'dynamic-content-for-elementor'),
            'description' => __('If "yes", a short animation will take place at the rollover.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'prefix_class' => 'hoveranim-',
            'separator' => 'before',
            'condition' => [
                'useNavigation' => 'yes'
            ]
                ]
        );
        $this->add_control(
                'navigation_arrow_color_hover', [
            'label' => __('Hover color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-button-next:hover path, {{WRAPPER}} .swiper-button-prev:hover path, ' => 'fill: {{VALUE}};',
                '{{WRAPPER}} .swiper-button-next:hover line, {{WRAPPER}} .swiper-button-prev:hover line, {{WRAPPER}} .swiper-button-next:hover polyline, {{WRAPPER}} .swiper-button-prev:hover polyline' => 'stroke: {{VALUE}};',
            ],
            'condition' => [
                'useNavigation' => 'yes'
            ],
            
                ]
        );
        $this->add_responsive_control(
                'pagination_stroke_1', [
            'label' => __('Stroke Arrow', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'tablet_default' => [
                'size' => '',
            ],
            'mobile_default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 50,
                    'min' => 0,
                    'step' => 1.0000,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-button-prev polyline, {{WRAPPER}} .swiper-button-next polyline' => 'stroke-width: {{SIZE}};',
            ],
            'condition' => [
                'useNavigation' => 'yes'
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_stroke_2', [
            'label' => __('Stroke Line', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'tablet_default' => [
                'size' => '',
            ],
            'mobile_default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 50,
                    'min' => 0,
                    'step' => 1.0000,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-button-next line, {{WRAPPER}} .swiper-button-prev line' => 'stroke-width: {{SIZE}};',
            ],
            'condition' => [
                'useNavigation' => 'yes'
            ]
                ]
        );

        ////////
        $this->add_control(
                'pagination_tratteggio', [
            'label' => __('Dashed', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '0',
            ],
            'range' => [
                'px' => [
                    'max' => 50,
                    'min' => 0,
                    'step' => 1.0000,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-button-prev line, {{WRAPPER}} .swiper-button-next line, {{WRAPPER}} .swiper-button-prev polyline, {{WRAPPER}} .swiper-button-next polyline' => 'stroke-dasharray: {{SIZE}},{{SIZE}};',
            ],
            'condition' => [
                'useNavigation' => 'yes'
            ]
                ]
        );
        ///////////
        $this->add_responsive_control(
                'pagination_scale', [
            'label' => __('Scale', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'tablet_default' => [
                'size' => '',
            ],
            'mobile_default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 2,
                    'min' => 0.10,
                    'step' => 0.01,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => 'transform: scale({{SIZE}});',
            ],
            'condition' => [
                'useNavigation' => 'yes'
            ]
                ]
        );

        $this->add_responsive_control(
                'pagination_position', [
            'label' => __('Horozontal Position', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'tablet_default' => [
                'size' => '',
            ],
            'mobile_default' => [
                'size' => '',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => -100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                //'{{WRAPPER}} .swiper-container-horizontal .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
                //'{{WRAPPER}} .swiper-container-horizontal .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'useNavigation' => 'yes'
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_position_v', [
            'label' => __('Verical Position', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 50,
                'unit' => '%'
            ],
            'size_units' => ['%', 'px'],
            'range' => [
                '%' => [
                    'max' => 120,
                    'min' => -20,
                    'step' => 1,
                ],
                'px' => [
                    'max' => 200,
                    'min' => -200,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'top: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'useNavigation' => 'yes'
            ]
                ]
        );
        // --------------------------------------------------- Pagination options ------
        $this->add_control(
                'pagination_options', [
            'label' => __('Pagination options', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'usePagination', [
            'label' => __('Use Pagination', 'dynamic-content-for-elementor'),
            'description' => __('If "yes", use the slide progression display system ("bullets", "fraction", "progress").', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'frontend_available' => true,
                ]
        );
        $this->add_control(
                'pagination_type', [
            'label' => __('Pagination Type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'bullets' => __('Bullets', 'dynamic-content-for-elementor'),
                'fraction' => __('Fraction', 'dynamic-content-for-elementor'),
                'progress' => __('Progress', 'dynamic-content-for-elementor'),
            ],
            'default' => 'bullets',
            'frontend_available' => true,
            'condition' => [
                'usePagination' => 'yes',
            ]
                ]
        );

        // ------------------------------------------------- Pagination Fraction Options
        $this->add_control(
                'fraction_separator', [
            'label' => __('Fraction text separator', 'dynamic-content-for-elementor'),
            'description' => __('The text that separates the 2 numbers', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'frontend_available' => true,
            'default' => '/',
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'fraction',
            ]
                ]
        );
        $this->add_responsive_control(
                'fraction_space', [
            'label' => __('Spacing', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '4',
                'unit' => 'px',
            ],
            'tablet_default' => [
                'unit' => 'px',
            ],
            'mobile_default' => [
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -20,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-fraction .separator' => 'margin: 0 {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'fraction',
            ]
                ]
        );
        $this->add_control(
                'fraction_color', [
            'label' => __('Il colore dei numeri', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-fraction > *' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'fraction',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'fraction_typography',
            'label' => __('Typography numeri', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .swiper-pagination-fraction > *',
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'fraction',
            ]
                ]
        );
        $this->add_control(
                'fraction_current_color', [
            'label' => __('Il colore del numero corrente', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-fraction .swiper-pagination-current' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'fraction',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'fraction_typography_current',
            'label' => __('Current number typography', 'dynamic-content-for-elementor'),
            'default' => '',
            'selector' => '{{WRAPPER}} .swiper-pagination-fraction .swiper-pagination-current',
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'fraction',
            ]
                ]
        );
        $this->add_control(
                'fraction_separator_color', [
            'label' => __('The color of the separator', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-fraction .separator' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'fraction',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => __('fraction_typography_separator', 'dynamic-content-for-elementor'),
            'label' => 'Typography separator',
            'default' => '',
            'selector' => '{{WRAPPER}} .swiper-pagination-fraction .separator',
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'fraction',
            ]
                ]
        );

        // ------------------------------------------------- Pagination Bullets Options
        $this->add_responsive_control(
                'bullets_space', [
            'label' => __('Space', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '5',
                'unit' => 'px',
            ],
            'tablet_default' => [
                'unit' => 'px',
            ],
            'mobile_default' => [
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'bullets',
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_bullets', [
            'label' => __('Bullets dimension', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '8',
                'unit' => 'px',
            ],
            'tablet_default' => [
                'unit' => 'px',
            ],
            'mobile_default' => [
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'bullets',
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_bullets_posy', [
            'label' => __('Shift', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '10',
                'unit' => 'px',
            ],
            'tablet_default' => [
                'unit' => 'px',
            ],
            'mobile_default' => [
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -160,
                    'max' => 160,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullets' => ' bottom: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'bullets',
            ]
                ]
        );
        $this->add_control(
                'bullets_color', [
            'label' => __('Bullets Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'bullets',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'border_bullet',
            'label' => __('Dullets border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet',
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'bullets',
            ]
                ]
        );
        $this->add_responsive_control(
                'current_bullet', [
            'label' => __('Dimension of active bullet', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'tablet_default' => [
                'unit' => 'px',
            ],
            'mobile_default' => [
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .swiper-pagination.swiper-pagination-bullets' => 'height: {{SIZE}}{{UNIT}}'
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'bullets',
            ]
                ]
        );
        $this->add_control(
                'current_bullet_color', [
            'label' => __('Active bullet color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'bullets',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'border_current_bullet',
            'label' => __('Active bullet border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet-active',
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'bullets',
            ]
                ]
        );
        $this->add_control(
                'progress_color', [
            'label' => __('Progress color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-progress' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'progress',
            ]
                ]
        );
        $this->add_control(
                'progressbar_color', [
            'label' => __('Progressbar color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-progress .swiper-pagination-progressbar' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'usePagination' => 'yes',
                'pagination_type' => 'progress',
            ]
                ]
        );
        // -------------------------------- Scrollbar options ------
        $this->add_control(
                'scrollbar_options', [
            'label' => __('Scrollbar options', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'useScrollbar', [
            'label' => __('Use Scrollbar', 'dynamic-content-for-elementor'),
            'description' => __('If "yes", you will use a scrollbar that displays navigation', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
                ]
        );

        // -------------------------------- Autoplay ------
        $this->add_control(
                'autoplay_options', [
            'label' => __('Autoplay options', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'useAutoplay', [
            'label' => __('Use Autoplay', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
                ]
        );
        $this->add_control(
                'autoplay', [
            'label' => __('Auto Play', 'dynamic-content-for-elementor'),
            'description' => __('Delay between transitions (in ms). If this parameter is not specified (by default), autoplay will be disabled', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => '',
            'min' => 0,
            'max' => 3000,
            'step' => 100,
            'frontend_available' => true,
            'condition' => [
                'useAutoplay' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'autoplayStopOnLast', [
            'label' => __('Autoplay stop on last slide', 'dynamic-content-for-elementor'),
            'description' => __('Enable this parameter and autoplay will be stopped when it reaches last slide (has no effect in loop mode)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'condition' => [
                'autoplay!' => '',
            ]
                ]
        );
        $this->add_control(
                'autoplayDisableOnInteraction', [
            'label' => __('Autoplay Disable on interaction', 'dynamic-content-for-elementor'),
            'description' => __('Set to "false" and autoplay will not be disabled after user interactions (swipes), it will be restarted every time after interaction', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'frontend_available' => true,
            'condition' => [
                'autoplay!' => '',
            ]
                ]
        );
        // -------------------------------- Keyboard ------
        $this->add_control(
                'keyboard_options', [
            'label' => __('Keyboard / Mousewheel', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'keyboardControl', [
            'label' => __('Keyboard Control', 'dynamic-content-for-elementor'),
            'description' => __('Set to true to enable keyboard control', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
                ]
        );
        $this->add_control(
                'mousewheelControl', [
            'label' => __('Mousewheel Control', 'dynamic-content-for-elementor'),
            'description' => __('Enables navigation through slides using mouse wheel', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
                ]
        );



        // -------------------------------- Ciclo ------
        $this->add_control(
                'cicleloop_options', [
            'label' => __('Cicle / Loop', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'loop', [
            'label' => __('Loop', 'dynamic-content-for-elementor'),
            'description' => __('Set to true to enable continuous loop mode', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
                ]
        );







        $this->end_controls_section();

        // ------------------------------------------------------------------------------- OPTIONS
        $this->start_controls_section(
                'section_swiper_special', [
            'label' => __('Swiper Special Options', 'dynamic-content-for-elementor'),
            'condition' => [
                'posts_style' => 'swiper',
            ]
                ]
        );
        // -------------------------------- Special options ---------
        $this->add_control(
                'special_options', [
            'label' => __('Specials options', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'setWrapperSize', [
            'label' => __('Set Wrapper Size', 'dynamic-content-for-elementor'),
            'description' => __('Enabled this option and plugin will set width/height on swiper wrapper equal to total size of all slides. Mostly should be used as compatibility fallback option for browser that don\'t support flexbox layout well', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true
                ]
        );
        $this->add_control(
                'virtualTranslate', [
            'label' => __('Virtual Translate', 'dynamic-content-for-elementor'),
            'description' => __('Enabled this option and swiper will be operated as usual except it will not move, real translate values on wrapper will not be set. Useful when you may need to create custom slide transition', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true
                ]
        );
        $this->add_control(
                'autoHeight', [
            'label' => __('Auto Height', 'dynamic-content-for-elementor'),
            'description' => __('Set to true and slider wrapper will adopt its height to the height of the currently active slide', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true
                ]
        );
        $this->add_control(
                'roundLengths', [
            'label' => __('Round Lengths', 'dynamic-content-for-elementor'),
            'description' => __('Set to true to round values of slides width and height to prevent blurry texts on usual resolution screens (if you have such)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true
                ]
        );
        $this->add_control(
                'nested', [
            'label' => __('Nidificato', 'dynamic-content-for-elementor'),
            'description' => __('Set to true on nested Swiper for correct touch events interception. Use only on nested swipers that use same direction as the parent one', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true
                ]
        );
        $this->add_control(
                'grabCursor', [
            'label' => __('Grab Cursor', 'dynamic-content-for-elementor'),
            'description' => __('This option may a little improve desktop usability. If true, user will see the "grab" cursor when hover on Swiper', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true
                ]
        );
        // -------------------------------- Special options ---------
        $this->add_control(
                'progress_options', [
            'label' => __('Progress', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'watchSlidesProgress', [
            'label' => __('Watch Slides Progress', 'dynamic-content-for-elementor'),
            'description' => __('Enable this feature to calculate each slides progress', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true
                ]
        );
        $this->add_control(
                'watchSlidesVisibility', [
            'label' => __('Watch Slides Visibility', 'dynamic-content-for-elementor'),
            'description' => __('WatchSlidesProgress should be enabled. Enable this option and slides that are in viewport will have additional visible class', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'condition' => [
                'watchSlidesProgress' => 'yes',
            ]
                ]
        );

        $this->end_controls_section();




        // ------------------------------------------------------------------------------- Parallax
        /* $this->start_controls_section(
          'section_swiper_tarallax',
          [
          'label'         => __( 'Parallax', 'dynamic-content-for-elementor' ),
          'condition' => [
          'posts_style' => 'swiper',
          ]
          ]
          );
          $this->end_controls_section(); */
        // ------------------------------------------------------------------------------- Touches, Touch Resistance
        /* $this->start_controls_section(
          'section_swiper_touch',
          [
          'label'         => __( 'Touches & Touch Resistance', 'dynamic-content-for-elementor' ),
          'condition' => [
          'posts_style' => 'swiper',
          ]
          ]
          );
          $this->end_controls_section(); */
        // ------------------------------------------------------------------------------- Swiping / No swiping
        /* $this->start_controls_section(
          'section_swiper_swiping',
          [
          'label'         => __( 'Swiping / No swiping', 'dynamic-content-for-elementor' ),
          'condition' => [
          'posts_style' => 'swiper',
          ]
          ]
          );
          $this->end_controls_section(); */
        // ------------------------------------------------------------------------------- Navigation Controls, Pagination, Navigation Buttons, Scollbar, Accessibility
        /* $this->start_controls_section(
          'section_swiper_navigation',
          [
          'label'         => __( 'Navigation', 'dynamic-content-for-elementor' ),
          'condition' => [
          'posts_style' => 'swiper',
          ]
          ]
          );
          $this->end_controls_section(); */

        // ------------------------------------------------------------------------------- Hash/History Navigation
        /* $this->start_controls_section(
          'section_swiper_hashHistory',
          [
          'label'         => __( 'Hash/History Navigation', 'dynamic-content-for-elementor' ),
          'condition' => [
          'posts_style' => 'swiper',
          ]
          ]
          );
          $this->end_controls_section(); */
        // ------------------------------------------------------------------------------- Images
        /* $this->start_controls_section(
          'section_swiper_images',
          [
          'label'         => __( 'Images', 'dynamic-content-for-elementor' ),
          'condition' => [
          'posts_style' => 'swiper',
          ]
          ]
          );
          $this->end_controls_section(); */


        //////////////////////////////////////////////////////////////////////////// [ SECTION Grid ]
        $this->start_controls_section(
                'section_grid', [
            'label' => __('Grid', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'posts_style' => ['grid', 'flexgrid'],
            ],
                ]
        );
        /* $this->add_responsive_control(
          'num_col',
          [
          'label' => __( 'Zoom Level', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::SLIDER,
          'default' => [
          'size' => 4,
          ],
          'range' => [
          'px' => [
          'min' => 1,
          'max' => 8,
          ],
          ],
          ]
          ); */

        $this->add_responsive_control(
                'columns_grid', [
            'label' => __('Columns', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => '5',
            'tablet_default' => '3',
            'mobile_default' => '1',
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7'
            ],
            //'frontend_available' => true,
            //'prefix_class' => 'columns-',
            'render_type' => 'template',
            'selectors' => [
                '{{WRAPPER}} .dce-post-item' => 'width: calc( 100% / {{VALUE}} );',
                '{{WRAPPER}} .dce-post-item.equalHMR' => 'flex: 0 1 calc( 100% / {{VALUE}} );',
            ],
            'condition' => [
                'posts_style' => ['grid', 'flexgrid'],
            ],
                ]
        );
        /*
          flex-grow: 0;
          flex-shrink: 1;
          flex-basis: calc(33.3333%)
         */
        $this->add_control(
                'fitrow_enable', [
            'label' => __('Fit Row', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'posts_style' => 'grid',
            ],
                ]
        );


        $this->add_control(
                'sameheight_enable', [
            'label' => __('Flex', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            //'frontend_available' => true,
            'condition' => [
                'posts_style' => 'flexgrid',
            ],
                ]
        );
        $this->add_control(
                'flex_grow', [
            'label' => __('Flex grow', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'label_block' => false,
            'options' => [
                '1' => [
                    'title' => __('1', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-check',
                ],
                '0' => [
                    'title' => __('0', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-ban',
                ]
            ],
            'default' => 1,
            'selectors' => [
                '{{WRAPPER}} .dce-post-item.equalHMR' => 'flex-grow: {{VALUE}};',
            ],
            'condition' => [
                'posts_style' => 'flexgrid',
                'sameheight_enable' => 'yes'
            ],
                ]
        );
        $this->add_responsive_control(
                'flexgrid_mode', [
            'label' => __('Alignment grid', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'flex-start',
            'label_block' => true,
            'options' => [
                'flex-start' => 'Flex start',
                'flex-end' => 'Flex end',
                'center' => 'Center',
                'space-between' => 'Space Between',
                'space-around' => 'Space Around',
            ],
            //'frontend_available' => true,
            'selectors' => [
                '{{WRAPPER}} .equalHMRWrap' => 'justify-content: {{VALUE}};',
            ],
            'condition' => [
                'posts_style' => 'flexgrid',
                'sameheight_enable' => 'yes',
                'flex_grow' => '0'
            ],
                ]
        );
        $this->add_responsive_control(
                'v_align_items', [
            'label' => __('Vertical Alignment', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => __('Top', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => __('Middle', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-v-align-middle',
                ],
                'flex-end' => [
                    'title' => __('Down', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-v-align-bottom',
                ],
                'stretch' => [
                    'title' => __('Stretch', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-v-align-stretch',
                ],
            ],
            'default' => 'top',
            'selectors' => [
                '{{WRAPPER}} .equalHMR' => 'align-self: {{VALUE}};',
            ],
            'condition' => [
                'posts_style' => 'flexgrid',
                'sameheight_enable' => 'yes',
                'flex_grow' => '0'
            ],
                ]
        );

        $this->end_controls_section();


        // ------------------------------------------------------------------------------------ [ SECTION Title ]

        $this->start_controls_section(
                'section_title', [
            'label' => __('Title', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'show_title' => '1',
                'templatemode_enable' => '',
                'native_templatemode_enable' => ''
            ],
                ]
        );


        $this->add_control(
                'title_inout', [
            'label' => __('Title inside or outside of box', 'dynamic-content-for-elementor'),
            //'type' => Controls_Manager::SELECT,
            'type' => Controls_Manager::HIDDEN,
            'options' => [
                'in' => __('In', 'dynamic-content-for-elementor'),
                'out' => __('Out', 'dynamic-content-for-elementor'),
            ],
            'default' => 'out',
            //'prefix_class' => 'nuvoletta-',
            //'frontend_available' => true,
            'condition' => [
                'show_title' => '1',
            ]
                ]
        );

        $this->add_control(
                'html_tag', [
            'label' => __('HTML Tag', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'h1' => __('H1', 'dynamic-content-for-elementor'),
                'h2' => __('H2', 'dynamic-content-for-elementor'),
                'h3' => __('H3', 'dynamic-content-for-elementor'),
                'h4' => __('H4', 'dynamic-content-for-elementor'),
                'h5' => __('H5', 'dynamic-content-for-elementor'),
                'h6' => __('H6', 'dynamic-content-for-elementor'),
                'p' => __('p', 'dynamic-content-for-elementor'),
                'div' => __('div', 'dynamic-content-for-elementor'),
                'span' => __('span', 'dynamic-content-for-elementor'),
            ],
            'default' => 'h3',
            'condition' => [
                'show_title' => '1',
            ]
                ]
        );

        $this->add_control(
                'title_link', [
            'label' => __('Use link', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => [
                'show_title' => '1',
            ]
                ]
        );

        $this->end_controls_section();

        // --------------------------------------------------------- [ SECTION TextContent ]

        $this->start_controls_section(
                'section_textcontent', [
            'label' => __('Content', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'show_textcontent!' => '0',
                'templatemode_enable' => '',
                'native_templatemode_enable' => ''
            ],
                ]
        );

        $this->add_control(
                'textcontent_limit', [
            'label' => __('Number of characters', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => '',
            'condition' => [
                'show_textcontent' => '1',
            ]
                ]
        );
        $this->add_control(
                'textcontent_position', [
            'label' => __('Text Position', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HIDDEN,
            'options' => [
                'bottom_in' => __('Bottom (in)', 'dynamic-content-for-elementor'),
                'bottom_out' => __('Bottom (out)', 'dynamic-content-for-elementor'),
                'top_out' => __('Top (out)', 'dynamic-content-for-elementor'),
            ],
            'default' => 'bottom_in',
            'prefix_class' => 'textcontent-position-',
            'frontend_available' => true,
            'condition' => [
                'show_textcontent!' => '0',
            ]
                ]
        );
        $this->add_control(
                'nuvoletta_enable', [
            'label' => __('Enable Tooltip', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'no' => __('No', 'dynamic-content-for-elementor'),
                'yes' => __('Yes', 'dynamic-content-for-elementor'),
            ],
            'default' => '',
            'prefix_class' => 'nuvoletta-',
            'frontend_available' => true,
            'condition' => [
                'show_textcontent' => '1',
                'posts_style' => ['simplegrid', 'flexgrid', 'grid'],
            ]
                ]
        );
        /* $this->add_control(
          'nuvoletta_enable',
          [
          'label'         => __( 'Nuvoletta', 'dynamic-content-for-elementor' ),
          'type'          => Controls_Manager::SWITCHER,
          'default'       => '',
          'label_on'      => __( 'Yes', 'dynamic-content-for-elementor' ),
          'label_off'     => __( 'No', 'dynamic-content-for-elementor' ),
          'return_value'  => 'yes',
          'frontend_available' => true,
          'prefix_class' => 'nuvoletta-',
          ]
          ); */
        /* $this->add_control(
          'nuvoletta_position',
          [
          'label' => __( 'Arrow Position', 'dynamic-content-for-elementor' ),
          'type' => Controls_Manager::SELECT,
          'default' => 'inside',
          'options' => [
          '' => __( 'None', 'dynamic-content-for-elementor' ),
          'top' => __( 'Top', 'dynamic-content-for-elementor' ),
          'bottom' => __( 'Bottom', 'dynamic-content-for-elementor' ),
          ],
          'default' => '',

          'condition' => [
          'nuvoletta_enable' => 'yes',
          ]
          ]
          ); */
        $this->add_responsive_control(
                'nuvolatta_move', [
            'label' => __('Scostamento Orizzontale Arrow', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 50,
                'unit' => '%',
            ],
            'tablet_default' => [
                'unit' => '%',
            ],
            'mobile_default' => [
                'unit' => '%',
            ],
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce_textcontent:after' => 'left: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'nuvoletta_enable' => 'yes',
            ]
                ]
        );
        $this->add_responsive_control(
                'nuvolatta_size', [
            'label' => __('Dimensione Arrow', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 15,
                'unit' => 'px',
            ],
            'tablet_default' => [
                'unit' => 'px',
            ],
            'mobile_default' => [
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                '%' => [
                    'min' => 1,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce_textcontent:after' => 'border-width: {{SIZE}}{{UNIT}}; margin-left: -{{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'nuvoletta_enable' => 'yes',
            ]
                ]
        );
        $this->end_controls_section();
        // ------------------------------------------------------------------ [ SECTION Image ]

        $this->start_controls_section(
                'section_image', [
            'label' => __('Image', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'show_image' => '1',
                'templatemode_enable' => '',
                'native_templatemode_enable' => ''
            ],
                ]
        );

        $this->add_control(
                'use_bgimage', [
            'label' => __('Background Image', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'prefix_class' => 'bgimage-',
            'render_type' => 'template',
            'condition' => [
                'show_image' => '1',
            ]
                ]
        );
        $this->add_responsive_control(
                'height_bgimage', [
            'label' => __('Height', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 120,
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 10,
                    'max' => 1000,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .acfposts-image' => 'min-height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .dce-overlay_hover, {{WRAPPER}} .dce-overlay' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'use_bgimage' => 'yes',
            ]
                ]
        );


        $this->add_group_control(
                Group_Control_Image_Size::get_type(), [
            'name' => 'size',
            'label' => __('Image Size', 'dynamic-content-for-elementor'),
            'default' => 'large',
            'condition' => [
                'show_image' => '1',
            ]
                ]
        );
        $this->add_responsive_control(
                'size_image', [
            'label' => __('Size (%)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            /* 'default' => [
              'size' => 100,
              'unit' => '%',
              ],
              'tablet_default' => [
              'unit' => '%',
              ],
              'mobile_default' => [
              'unit' => '%',
              ], */
            'size_units' => ['%', 'px', 'vw'],
            'range' => [
                '%' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1
                ],
                'vw' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1
                ],
                'px' => [
                    'min' => 1,
                    'max' => 800,
                    'step' => 1
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-acfposts_image > *' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'show_image' => '1',
            ]
                ]
        );
        // Link Image
        $this->add_control(
                'image_link', [
            'label' => __('Use link', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => [
                'show_image' => '1',
            ]
                ]
        );
        $this->add_control(
                'use_overlay', [
            'label' => __('Overlay', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'label_block' => false,
            'separator' => 'before',
            'options' => [
                '1' => [
                    'title' => __('Yes', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-check',
                ],
                '0' => [
                    'title' => __('No', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-ban',
                ]
            ],
            'default' => '0',
            'condition' => [
                'show_image' => '1',
            ]
                ]
        );

        $this->add_group_control(
                Group_Control_Background::get_type(), [
            'name' => 'overlay_color',
            'label' => __('Background', 'dynamic-content-for-elementor'),
            'types' => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .dce-overlay',
            'condition' => [
                'show_image' => '1',
                'use_overlay' => '1'
            ]
                ]
        );
        $this->add_responsive_control(
                'overlay_opacity', [
            'label' => __('Opacity (%)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 0.7,
            ],
            'range' => [
                'px' => [
                    'max' => 1,
                    'min' => 0.10,
                    'step' => 0.01,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-overlay' => 'opacity: {{SIZE}};',
            ],
            'condition' => [
                'show_image' => '1',
                'use_overlay' => '1'
            ]
                ]
        );
        $this->end_controls_section();
        // --------------------------------------------------- [ SECTION Metadata (Terms) ]

        $this->start_controls_section(
                'section_metadata', [
            'label' => __('Meta Data', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'show_metadata' => '1',
                'templatemode_enable' => '',
                'native_templatemode_enable' => ''
            ],
                ]
        );

        $this->add_control(
                'taxonomy_metadata_filter', [
            'label' => __('Filter Taxonomy', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $taxonomies,
            'placeholder' => __('All', 'dynamic-content-for-elementor'),
            'description' => __('Use only terms in selected taxonomies. If empty all terms will be used.', 'dynamic-content-for-elementor'),
            'condition' => [
                'show_metadata' => '1',
            ]
                ]
        );
        $this->add_control(
                'metadata_inout', [
            'label' => __('Metadata inside or outside of box', 'dynamic-content-for-elementor'),
            //'type' => Controls_Manager::SELECT,
            'type' => Controls_Manager::HIDDEN,
            'options' => [
                'in' => __('In', 'dynamic-content-for-elementor'),
                'out' => __('Out', 'dynamic-content-for-elementor'),
            ],
            'default' => 'out',
            //'prefix_class' => 'nuvoletta-',
            //'frontend_available' => true,
            'condition' => [
                'show_metadata' => '1',
            ]
                ]
        );
        $this->add_control(
                'separator_metadata', [
            'label' => __('Separator', 'dynamic-content-for-elementor'),
            //'description' => __('Separator caracters.','dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => ', ',
            'condition' => [
                'show_metadata' => '1',
            ]
                ]
        );
        $this->add_control(
                'only_parent_metadata', [
            'label' => __('Only parent items', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'show_metadata' => '1',
            ]
                ]
        );
        $this->add_control(
                'metadata_link', [
            'label' => __('Use link', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => [
                'show_metadata' => '1',
            ]
                ]
        );
        $this->add_control(
                'metadata_block_enable', [
            'label' => __('Block', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'show_metadata' => '1',
            ]
                ]
        );
        $this->add_control(
                'metadata_icon_enable', [
            'label' => __('Icon', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'show_metadata' => '1',
            ]
                ]
        );
        $this->end_controls_section();
        // ------------------------------------------------------ [ SECTION Metadata (Terms) ]

        $this->start_controls_section(
                'section_author', [
            'label' => __('Author', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'show_author' => '1',
                'templatemode_enable' => '',
                'native_templatemode_enable' => ''
            ],
                ]
        );

        $this->add_control(
                'enable_author_image', [
            'label' => __('Show image', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => [
                'show_author' => '1',
            ]
                ]
        );
        $this->add_control(
                'enable_author_bio', [
            'label' => __('Show biography', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'condition' => [
                'show_author' => '1',
            ]
                ]
        );

        $this->add_control(
                'author_inout', [
            'label' => __('Author inside or outside of box', 'dynamic-content-for-elementor'),
            //'type' => Controls_Manager::SELECT,
            'type' => Controls_Manager::HIDDEN,
            'options' => [
                'in' => __('In', 'dynamic-content-for-elementor'),
                'out' => __('Out', 'dynamic-content-for-elementor'),
            ],
            'default' => 'out',
            //'prefix_class' => 'nuvoletta-',
            //'frontend_available' => true,
            'condition' => [
            //'show_metadata' => '1',
            ]
                ]
        );
        $this->end_controls_section();
        // --------------------------------------------------------- [ SECTION Date ]

        $this->start_controls_section(
                'section_date', [
            'label' => __('Date', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'show_date' => '1',
                'templatemode_enable' => '',
                'native_templatemode_enable' => ''
            ],
                ]
        );

        $this->add_control(
                'date_type', [
            'label' => __('Date Type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'publish' => __('Publish Date', 'dynamic-content-for-elementor'),
                'modified' => __('Last Modified Date', 'dynamic-content-for-elementor'),
            ],
            'default' => 'publish',
            'condition' => [
                'show_date' => '1',
            ]
                ]
        );
        $this->add_control(
                'date_format', [
            'label' => __('Format date', 'dynamic-content-for-elementor'),
            'description' => __('The format of date.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => 'j.F.Y',
            'condition' => [
                'show_date' => '1',
            ]
                ]
        );
        $this->add_control(
                'date_format_2', [
            'label' => __('Format date 2', 'dynamic-content-for-elementor'),
            'description' => __('The format of date 2.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'condition' => [
                'show_date' => '1',
            ]
                ]
        );
        $this->add_control(
                'date_format_3', [
            'label' => __('Format date 3', 'dynamic-content-for-elementor'),
            'description' => __('The format of date 3.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'condition' => [
                'show_date' => '1',
            ]
                ]
        );
        $this->end_controls_section();

        // ---------------------------------------------- [ SECTION Read More Button ]

        $this->start_controls_section(
                'section_readmore', [
            'label' => __('Read More Button', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'show_readmore' => '1',
                'templatemode_enable' => '',
                'native_templatemode_enable' => ''
            ],
                ]
        );

        $this->add_control(
                'readmore_text', [
            'label' => __('Text button', 'dynamic-content-for-elementor'),
            //'description' => __('Separator caracters.','dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Read More', 'dynamic-content-for-elementor'),
            'condition' => [
                'show_readmore' => '1',
            ],
                ]
        );
        $this->add_control(
                'readmore_inout', [
            'label' => __('Read More button inside or outside of box', 'dynamic-content-for-elementor'),
            //'type' => Controls_Manager::SELECT,
            'type' => Controls_Manager::HIDDEN,
            'options' => [
                'in' => __('In', 'dynamic-content-for-elementor'),
                'out' => __('Out', 'dynamic-content-for-elementor'),
            ],
            'default' => 'out',
            //'prefix_class' => 'nuvoletta-',
            //'frontend_available' => true,
            'condition' => [
                'show_readmore' => '1',
            ]
                ]
        );
        /* $this->add_control(
          'readmore_size', [
          'label' => __('Space', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SLIDER,
          'default' => [
          'size' => 20,
          ],
          'size_units' => [ 'px'],
          'range' => [
          '%' => [
          'min' => 0,
          'max' => 100,
          ],
          ],
          'selectors' => [
          '{{WRAPPER}} .dce_readmore_wrapper' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
          ],
          'condition' => [
          'show_readmore' => '1',
          ],
          ]
          ); */
        $this->end_controls_section();
        // ---------------------------------------------------- [ ACF repeater START ]

        $this->start_controls_section(
                'section_acfitems', [
            'label' => __('ACF', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'templatemode_enable' => '',
                'native_templatemode_enable' => '',
                'show_acfitems' => '1',
            ],
                ]
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs('acfitems_repeater');

        $repeater->start_controls_tab('tab_content', ['label' => __('Item', 'dynamic-content-for-elementor')]);

        $repeater->add_control(
                'acf_field_item', [
            'label' => __('ACF Fields', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            //'options' => [ '' => __('Title', 'dynamic-content-for-elementor') . '(CORE)'] + ['content' => __('Content', 'dynamic-content-for-elementor') . '(CORE)'] + ['taxonomy' => __('Taxonomy MetaData', 'dynamic-content-for-elementor') . '(CORE)'] + ['date' => __('Date', 'dynamic-content-for-elementor') . '(CORE)'] + $this->get_acf_field(),
            'groups' => $this->get_acf_field(true, true),
            'default' => '0',
                /* 'condition' => [
                  'acf_group_list' => '',
                  ] */
                ]
        );
        $repeater->add_control(
                'acf_field_type', [
            'label' => __('Field type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'text',
            'options' => [
                'text' => __('Text', 'dynamic-content-for-elementor'),
                'image' => __('Image', 'dynamic-content-for-elementor'),
                'date' => __('Date', 'dynamic-content-for-elementor'),
            ],
                ]
        );
        $repeater->add_control(
                'acf_date_format', [
            'label' => __('Format date', 'dynamic-content-for-elementor'),
            'description' => __('The format of date.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => 'F j, Y, g:i a',
            'condition' => [
                'acf_field_type' => 'date',
            ]
                ]
        );
        $repeater->add_control(
                'html_tag_item', [
            'label' => __('HTML Tag', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '' => __('None', 'dynamic-content-for-elementor'),
                'h1' => __('H1', 'dynamic-content-for-elementor'),
                'h2' => __('H2', 'dynamic-content-for-elementor'),
                'h3' => __('H3', 'dynamic-content-for-elementor'),
                'h4' => __('H4', 'dynamic-content-for-elementor'),
                'h5' => __('H5', 'dynamic-content-for-elementor'),
                'h6' => __('H6', 'dynamic-content-for-elementor'),
                'p' => __('p', 'dynamic-content-for-elementor'),
                'div' => __('div', 'dynamic-content-for-elementor'),
                'span' => __('span', 'dynamic-content-for-elementor'),
            ],
            'condition' => [
                'acf_field_type' => 'text',
            ],
            'default' => '',
                ]
        );
        $repeater->add_control(
                'link_to', [
            'label' => __('Link to', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none' => __('None', 'dynamic-content-for-elementor'),
                'home' => __('Home URL', 'dynamic-content-for-elementor'),
                'post' => 'Post URL',
                'custom' => __('Custom URL', 'dynamic-content-for-elementor'),
            ],
                ]
        );
        $repeater->add_control(
                'link', [
            'label' => __('Link', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::URL,
            'placeholder' => __('http://your-link.com', 'dynamic-content-for-elementor'),
            'condition' => [
                'link_to' => 'custom',
            ],
            'default' => [
                'url' => '',
            ],
            'show_label' => false,
                ]
        );
        $repeater->add_control(
                'taxonomy_metadata', [
            'label' => __('Taxonomy', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            //'options' => get_post_taxonomies( $post->ID ),
            'options' => $taxonomies,
            'default' => 'category',
            'condition' => [
                'acf_field_item' => 'taxonomy',
            ]
                ]
        );
        $repeater->end_controls_tab();

        /* $repeater->start_controls_tab( 'tab_media', [ 'label' => __( 'Media', 'dynamic-content-for-elementor' ) ] );



          $repeater->end_controls_tab(); */

        $repeater->start_controls_tab('tab_style', ['label' => __('Style', 'dynamic-content-for-elementor')]);
        $repeater->add_control(
                'block_enable', [
            'label' => __('Block', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
                ]
        );
        $repeater->add_control(
                'padding_item', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-acfposts_content {{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'block_enable' => 'yes',
            ],
                ]
        );
        $repeater->add_control(
                'color_item', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-acfposts_content {{CURRENT_ITEM}}, {{WRAPPER}} .dce-acfposts_content {{CURRENT_ITEM}} > *' => 'color: {{VALUE}};',
                '{{WRAPPER}} .dce-acfposts_content {{CURRENT_ITEM}} a' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'acf_field_type' => ['text', 'date'],
            ]
                ]
        );
        $repeater->add_control(
                'hover_color_item', [
            'label' => __('Hover Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-acfposts_content {{CURRENT_ITEM}} > * a:hover' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'acf_field_type' => ['text', 'date'],
            ]
                ]
        );

        $repeater->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'typography_item',
            'selector' => '{{WRAPPER}} .dce-acfposts_content {{CURRENT_ITEM}}, {{WRAPPER}} .dce-acfposts_content {{CURRENT_ITEM}} > *',
            'render_type' => 'ui',
            'condition' => [
                'acf_field_type' => ['text', 'date'],
            ]
                ]
        );

        $repeater->end_controls_tab();


        $repeater->end_controls_tabs();

        $this->add_control(
                'acf_items', [
            'label' => __('ACF Items', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::REPEATER,
            'fields' => array_values($repeater->get_controls()),
            'title_field' => '{{{ acf_field_item }}}',
            'prevent_empty' => false,
            // show_acfitems
            'condition' => [
                'show_acfitems' => '1',
            ],
                ]
        );


        $this->end_controls_section();

        // ----------------------------------------------- [ SECTION Filters ]

        $this->start_controls_section(
                'section_filters', [
            'label' => __('Filters', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'posts_style' => 'grid',
                'filters_enable' => 'yes',
            ],
                ]
        );

        $this->add_control(
                'filters_taxonomy', [
            'label' => __('Data Filters (Taxonomy)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            //'options' => get_post_taxonomies( $post->ID ),
            'options' => ['' => __('None', 'dynamic-content-for-elementor')] + $taxonomies,
            'default' => 'category',
            'label_block' => true,
            'condition' => [
                'filters_enable' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'filters_taxonomy_first_level_terms', [
            'label' => __('Use first level Terms', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'description' => __('Use all First level Terms of the selected taxonomy', 'dynamic-content-for-elementor'),
            'condition' => [
                'filters_enable' => 'yes',
                'filters_taxonomy!' => ''
            ],
                ]
        );
        foreach ($taxonomies as $tkey => $atax) {
            if ($tkey) {
                /* $this->add_control(
                  'filters_taxonomy_terms_' . $tkey, [

                  'label' => __('Data Filters (Selected Terms)', 'dynamic-content-for-elementor'), //.' '.$atax,
                  'type' => Controls_Manager::SELECT2,
                  //'groups' => \DynamicContentForElementor\DCE_Helper::get_taxonomies_terms(),
                  'options' => ['' => __('All', 'dynamic-content-for-elementor')] + \DynamicContentForElementor\DCE_Helper::get_taxonomy_terms($tkey), // + ['dce_current_post_terms' => __('Dynamic Current Post Terms', 'dynamic-content-for-elementor')],
                  'default' => '',
                  'description' => __('Use only Selected taxonomy terms or leave empty to use All terms of this taxonomy', 'dynamic-content-for-elementor'),
                  'multiple' => true,
                  'label_block' => true,
                  'condition' => [
                  'filters_enable' => 'yes',
                  'filters_taxonomy' => $tkey,
                  'filters_taxonomy_first_level_terms' => '',
                  ]
                  ]
                  ); */
                $this->add_control(
                        'filters_taxonomy_terms_' . $tkey,
                        [
                            'label' => __('Data Filters (Selected Terms)', 'dynamic-content-for-elementor'), //.' '.$atax,
                            'type' => 'ooo_query',
                            'placeholder' => __('Term Name', 'dynamic-content-for-elementor'),
                            'label_block' => true,
                            'query_type' => 'terms',
                            'object_type' => $tkey,
                            'description' => __('Use only Selected taxonomy terms or leave empty to use All terms of this taxonomy', 'dynamic-content-for-elementor'),
                            'multiple' => true,
                            'condition' => [
                                'filters_enable' => 'yes',
                                'filters_taxonomy' => $tkey,
                                'filters_taxonomy_first_level_terms' => '',
                            ]
                        ]
                );
            }
        }

        $this->add_control(
            'orderby_filters', [
                'label' => __('Order By', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => DCE_Helper::get_post_orderby_options(),
                'default' => 'date',
                /*'condition' => [
                    'query_type' => ['get_cpt', 'dynamic_mode', 'acf_relations'],
                ],*/
            ]
        );

        $this->add_control(
                'filters_acf', [
            'label' => __('Data Filters (ACF)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            //'options' => get_post_taxonomies( $post->ID ),
            'options' => $this->get_acf_field(),
            'default' => '0',
            'condition' => [
                'filters_enable' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'alltext_filter', [
            'label' => __('All text', 'dynamic-content-for-elementor'),
            //'description' => __('Separator caracters.','dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => 'All',
            'condition' => [
                'filters_enable' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'separator_filter', [
            'label' => __('Separator', 'dynamic-content-for-elementor'),
            //'description' => __('Separator caracters.','dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => ' / ',
            'condition' => [
                'filters_enable' => 'yes',
            ],
                ]
        );
        $this->add_responsive_control(
                'filters_align', [
            'label' => __('Filters Alignment', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'options' => [
                'left' => [
                    'title' => __('Left', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-right',
                ]
            ],
            'default' => 'left',
            'selectors' => [
                '{{WRAPPER}} .dce-filters' => 'text-align: {{VALUE}};',
            ],
            'condition' => [
                'filters_enable' => 'yes',
            ],
                //'prefix_class' => 'acfposts-align-'
                ]
        );
        $this->add_control(
                'filter_hide_empty', [
            'label' => __('Show/Hide empty terms', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'label_on' => __('Show', 'dynamic-content-for-elementor'),
            'label_off' => __('Hide', 'dynamic-content-for-elementor'),
            'condition' => [
                'filters_enable' => 'yes',
            ],
                ]
        );
        $this->end_controls_section();
        // ------------------------------------- [SECTION Hover Effects]
        $this->start_controls_section(
                'section_hover_effect', [
            'label' => __('Hover effect', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );
        $this->add_responsive_control(
                'hover_opacity', [
            'label' => __('Hover Opacity (%)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'max' => 1,
                    'min' => 0.10,
                    'step' => 0.01,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-post-item:hover' => 'opacity: {{SIZE}};',
            ],
                ]
        );
        $this->add_group_control(
                DCE_Group_Control_Filters_CSS::get_type(), [
            'name' => 'hover_filters_image',
            'label' => 'Filters image',
            'selector' => '{{WRAPPER}} .dce-acfposts_image a:hover .acfposts-image',
                ]
        );
        $this->add_control(
                'hover_animation', [
            'label' => __('Hover Animation', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HOVER_ANIMATION,
                ]
        );

        $this->add_control(
                'use_overlay_hover', [
            'label' => __('Overlay', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'label_block' => false,
            'options' => [
                '1' => [
                    'title' => __('Yes', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-check',
                ],
                '0' => [
                    'title' => __('No', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-ban',
                ]
            ],
            'default' => '0',
            'condition' => [
                'image_link!' => '',
            ]
                ]
        );

        $this->add_group_control(
                Group_Control_Background::get_type(), [
            'name' => 'overlay_color_hover',
            'label' => __('Background', 'dynamic-content-for-elementor'),
            'types' => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .dce-overlay_hover',
            'condition' => [
                'use_overlay_hover' => '1',
                'image_link!' => '',
            ]
                ]
        );

        $this->end_controls_section();
        // ------------------------------------------- [SECTION DYNAMIC CONTENT - DCE ]
        $this->start_controls_section(
                'section_dce', [
            'label' => __('Dynamic Content', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'templatemode_enable', [
            'label' => __('Enable Template', 'dynamic-content-for-elementor'),
            'description' => __('Enable a template to manage the appearance of individual grid elements ', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'render_type' => 'template',
            'prefix_class' => 'templatemode-',
            'condition' => [
                'native_templatemode_enable' => '',
            ],
                ]
        );
        /* $this->add_control(
          'templatemode_template', [
          'label' => __('Template', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SELECT,

          //'options' => get_post_taxonomies( $post->ID ),
          'options' => $templates,
          'default' => '0',
          'condition' => [
          'templatemode_enable!' => '',
          'native_templatemode_enable' => ''
          ],
          ]
          ); */
        $this->add_control(
                'templatemode_template',
                [
                    'label' => __('Template', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Template Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'object_type' => 'elementor_library',
                    'condition' => [
                        'templatemode_enable!' => '',
                        'native_templatemode_enable' => ''
                    ],
                ]
        );
        $this->add_control(
                'templatemode_enable_2', [
            'label' => __('Enable Template 2', 'dynamic-content-for-elementor'),
            'description' => __('Enable a template to manage the appearance of the odd elements of the grid ', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'render_type' => 'template',
            'prefix_class' => 'templatemode-',
            'condition' => [
                'native_templatemode_enable' => '',
            ],
                ]
        );
        /* $this->add_control(
          'templatemode_template_2', [
          'label' => __('Template odd', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SELECT,
          //'options' => get_post_taxonomies( $post->ID ),
          'options' => $templates,
          'default' => '0',
          'condition' => [
          'templatemode_enable' => 'yes',
          'templatemode_enable_2' => 'yes',
          'native_templatemode_enable' => ''
          ],
          ]
          ); */
        $this->add_control(
                'templatemode_template_2',
                [
                    'label' => __('Template odd', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Template Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'object_type' => 'elementor_library',
                    'condition' => [
                        'templatemode_enable!' => '',
                        'templatemode_enable_2!' => '',
                        'native_templatemode_enable' => ''
                    ],
                ]
        );

        $this->add_control(
                'native_templatemode_enable', [
            'label' => __('Enable Native Template', 'dynamic-content-for-elementor'),
            'description' => __('Use the template associated with the type (Menu: Elementor> Dynamic Content) to manage the appearance of the individual elements of the grid ', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'prefix_class' => 'templatemode-',
            'render_type' => 'template',
            'condition' => [
                'templatemode_enable' => '',
            ],
                ]
        );
        $this->add_control(
                'templatemode_linkable', [
            'label' => __('Linkable', 'dynamic-content-for-elementor'),
            'description' => __('Use the extended link on the template block.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'render_type' => 'template',
                ]
        );
        $this->end_controls_section();

        // --------------------------------------------- [SECTION WOW animations ]
        $this->start_controls_section(
                'section_wow', [
            'label' => __('Wow Animation', 'dynamic-content-for-elementor'),
            'condition' => [
            //'enabled_wow' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'enabled_wow', [
            'label' => __('Enable WOW Animation', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
                ]
        );

        // coefficiente per default
        $this->add_control(
                'wow_coef', [
            'label' => __('Delay coefficient', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'min' => 0.01,
            'max' => 1,
            'step' => 0.01,
            'condition' => [
                'enabled_wow' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'wow_animations', [
            'label' => __('Wow Animation Effect', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'fadeIn' => 'Fade In',
                'fadeInDown' => 'Fade In Down',
                'fadeInLeft' => 'Fade In Left',
                'fadeInRight' => 'Fade In Right',
                'fadeInUp' => 'Fade In Up',
                'zoomIn' => 'Zoom In',
                'zoomInDown' => 'Zoom In Down',
                'zoomInLeft' => 'Zoom In Left',
                'zoomInRight' => 'Zoom In Right',
                'zoomInUp' => 'Zoom In Up',
                'bounceIn' => 'Bounce In',
                'bounceInDown' => 'Bounce In Down',
                'bounceInLeft' => 'Bounce In Left',
                'bounceInRight' => 'Bounce In Right',
                'bounceInUp' => 'Bounce In Up',
                'slideInDown' => 'Slide In Down',
                'slideInLeft' => 'Slide In Left',
                'slideInRight' => 'Slide In Right',
                'slideInUp' => 'Slide In Up',
                'rotateIn' => 'Rotate In',
                'rotateInDownLeft' => 'Rotate In Down Left',
                'rotateInDownRight' => 'Rotate In Down Right',
                'rotateInUpLeft' => 'Rotate In Up Left',
                'rotateInUpRight' => 'Rotate In Up Right',
                'bounce' => 'Bounce',
                'flash' => 'Flash',
                'pulse' => 'Pulse',
                'rubberBand' => 'Rubber Band',
                'shake' => 'Shake',
                'headShake' => 'Head Shake',
                'swing' => 'Swing',
                'tada' => 'Tada',
                'wobble' => 'Wobble',
                'jello' => 'Jello',
                'lightSpeedIn' => 'Light Speed In',
                'rollIn' => 'Roll In',
            ],
            'default' => 'fadeInUp',
            //'frontend_available' => true,
            'condition' => [
                'enabled_wow' => 'yes',
            ]
                ]
        );
        $this->end_controls_section();
        // ------------------------------------------- [SECTION Ajax]

        $this->start_controls_section(
                'section_ajax', [
            'label' => __('Ajax', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );
        $this->add_group_control(
                DCE_Group_Control_Ajax_Page::get_type(), [
            'name' => 'ajax_page',
            'label' => 'Ajax PAGE',
            'selector' => $this->get_id(),
                ]
        );
        $this->end_controls_section();

        ////////////////////////////////////////////////////////////////////////////////////////// STYLE TAB
        // -------------------------------------------------------------------------- [ section Style - Timeline ]

        $this->start_controls_section(
                'section_style_timeline', [
            'label' => __('Timeline', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'posts_style' => 'timeline',
            ]
                ]
        );
        $this->add_control(
                'timeline_bg_color_content', [
            'label' => __('Content Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cd-timeline__content' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .cd-timeline__block:nth-child(odd) .cd-timeline__content::before' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
                '{{WRAPPER}} .cd-timeline__block:nth-child(even) .cd-timeline__content::before' => 'border-right-color: {{VALUE}}; border-right-color: {{VALUE}};'
            ],
                ]
        );
        $this->add_control(
                'timleline_line_color', [
            'label' => __('Line Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cd-timeline__container:before' => 'background-color: {{VALUE}};',
            ],
                ]
        );
        $this->add_control(
                'timeline_line_size', [
            'label' => __('Line size', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 4,
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cd-timeline__container:before' => 'width: {{SIZE}}{{UNIT}};',
            ]
                ]
        );
        $this->add_control(
                'timeline_arrow_size', [
            'label' => __('Row size', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 4,
                'unit' => 'px',
            ],
            'size_units' => ['px', 'em'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cd-timeline__container .cd-timeline__block' => 'margin: {{SIZE}}{{UNIT}} 0;',
            ]
                ]
        );
        $this->add_responsive_control(
                'content_width', [
            'label' => __('Content Width', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 45,
                'unit' => '%',
            ],
            'size_units' => ['px', '%'],
            'range' => [
                '%' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 0.1
                ],
                'px' => [
                    'min' => 1,
                    'max' => 600,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cd-timeline__content' => 'width: {{SIZE}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'date_width', [
            'label' => __('Date Width', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 130,
                'unit' => '%',
            ],
            'size_units' => ['px', '%'],
            'range' => [
                '%' => [
                    'min' => 1,
                    'max' => 200,
                    'step' => 1
                ],
                'px' => [
                    'min' => 1,
                    'max' => 600,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cd-timeline__date' => 'width: {{SIZE}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'date_pos_x', [
            'label' => __('Date Position X', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 0,
                'unit' => '%',
            ],
            'size_units' => ['px', '%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1
                ],
                'px' => [
                    'min' => 1,
                    'max' => 600,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cd-timeline__block:nth-child(odd) .cd-timeline__date' => 'left: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .cd-timeline__block:nth-child(even) .cd-timeline__date' => 'right: {{SIZE}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'timeline_radius_content', [
            'label' => __('Content Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 10,
                'unit' => 'px',
            ],
            'size_units' => ['px', '%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 50,
                ],
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cd-timeline__content' => 'border-radius: {{SIZE}}{{UNIT}};',
            ]
                ]
        );
        $this->add_control(
                'timeline_arrows_size', [
            'label' => __('Content arrows size', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 7,
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .cd-timeline__content::before, {{WRAPPER}} .cd-timeline__content::before' => 'border-width: {{SIZE}}{{UNIT}};',
            ]
                ]
        );
        $this->end_controls_section();

        // -------------------------------------------------------------------------- [ section Style - Block ]

        $this->start_controls_section(
                'section_style_template', [
            'label' => __('Style Info', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'templatemode_enable!' => '',
            ]
                ]
        );
        $this->add_control(
                'templatemode_info',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'show_label' => false,
                    'raw' => __('Styles are managed in the template.', 'dynamic-content-for-elementor'),
                    'content_classes' => 'dce-document-settings',
                    'separator' => 'after',
                ]
        );
        $this->end_controls_section();



        $this->start_controls_section(
                'section_style_textzone', [
            'label' => __('Blocks', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'templatemode_enable' => '',
            ]
                ]
        );
        $this->add_control(
                'textzone_bgcolor', [
            'label' => __('Blocks Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'separator' => 'after',
            'selectors' => [
                '{{WRAPPER}} .dce-post-item .dce-wrapper' => 'background-color: {{VALUE}};'
            ],
                ]
        );
        $this->add_responsive_control(
                'layout_align', [
            'label' => __('Text Alignment', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'options' => [
                'left' => [
                    'title' => __('Left', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-right',
                ]
            ],
            'default' => 'left',
            'selectors' => [
                '{{WRAPPER}} .dce-post-item' => 'text-align: {{VALUE}};',
            ],
            'prefix_class' => 'acfposts%s-align-',
            'condition' => [
                'posts_style!' => 'timeline',
            ],
                ]
        );
        $this->add_responsive_control(
                'h_align_blocks', [
            'label' => __('Horizontal Alignment (Flex)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => __('Left', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-h-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-h-align-center',
                ],
                'flex-end' => [
                    'title' => __('Right', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-h-align-right',
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-wrapper' => 'justify-content: {{VALUE}};',
            ],
            'condition' => [
                'posts_style' => 'flexgrid',
            ],
                ]
        );
        $this->add_responsive_control(
                'v_align_blocks', [
            'label' => __('Vertical Alignment (Flex)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => __('Top', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => __('Middle', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-v-align-middle',
                ],
                'flex-end' => [
                    'title' => __('Down', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-v-align-bottom',
                ],
            ],
            'separator' => 'after',
            'selectors' => [
                '{{WRAPPER}} .dce-wrapper' => 'display: flex; align-items: {{VALUE}};',
            ],
            'condition' => [
                'posts_style' => 'flexgrid',
            ],
                ]
        );
        $this->add_responsive_control(
                'blocks_v_alternate', [
            'label' => __('Vertical alternate', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'render_type' => 'template',
            'selectors' => [
                '{{WRAPPER}} .dce-post-item.column-3:nth-child(3n+2) .dce-wrapper, {{WRAPPER}} .dce-post-item:not(.column-3):nth-child(even) .dce-wrapper' => 'margin-top: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'posts_style!' => 'timeline',
            ],
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'border_textzone',
            'label' => __('Image Border', 'dynamic-content-for-elementor'),
            'separator' => 'before',
            'selector' => '{{WRAPPER}} .dce-post-item > .dce-wrapper',
                ]
        );

        $this->add_responsive_control(
                'text_padding_item', [
            'label' => __('Blocks Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'separator' => 'before',
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-post-item .dce-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                /* 'condition' => [
                  'gallery_display_caption' => '',
                  ], */
                ]
        );
        $this->add_responsive_control(
                'textzone_margin', [
            'label' => __('Blocks Margin', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-post-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                /* 'condition' => [
                  'gallery_display_caption' => '',
                  ], */
                ]
        );
        // ** questo elemento ha il nome textzone perche nato prima di essere perfezionato con l'are "text zone", in realtà è il border_radius del block
        $this->add_control(
                'border_radius_textzone', [
            'label' => __('Block Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-post-item .dce-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'radius_masking_enable', [
            'label' => __('BorderRadius add Masking', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'separator' => 'after',
            'prefix_class' => 'add-radius-masking-',
            'condition' => [
                'border_radius_textzone[left]!' => '',
            ]
                ]
        );



        $this->end_controls_section();
        // --------------------------------------------------------- [ section Content Text Style - TextContent ]
        $this->start_controls_section(
                'section_style_contenttextzone', [
            'label' => __('Content Zone', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'templatemode_enable' => '',
                'posts_style!' => 'timeline',
            ]
                ]
        );

        $this->add_control(
                'textzone_heading', [
            'label' => __('Text Zone', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'textarea_bgcolor', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-acfposts_content' => 'background-color: {{VALUE}};'
            ],
                ]
        );
        $this->add_responsive_control(
                'textzone_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-acfposts_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'textzone_movement', [
            'label' => __('Movement (%)', 'dynamic-content-for-elementor'),
            'type' => 'xy_movement',
            'separator' => 'before',
            'default' =>[
                'x' => '',
                'y' => '',
            ],
            'responsive' => true,
            'render_type' => 'ui',
            'selectors' => [
                '{{WRAPPER}} .dce-acfposts_content' => 'transform: translate({{X}}%, {{Y}}%); -webkit-transform: translate({{X}}%, {{Y}}%);',
            ],
                ]
        );
        $this->add_responsive_control(
                'textzone_width', [
            'label' => __('Width (%)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 0.1
                ],
            /* 'px' => [
              'min' => 1,
              'max' => 500,
              ] */
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-acfposts_content' => 'width: {{SIZE}}%;',
            ],
            'condition' => [
                'posts_style!' => 'timeline',
            ],
                ]
        );
        // ** vedi sopra
        $this->add_control(
                'border_radius_textzone_2', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'separator' => 'before',
            'size_units' => ['px', '%', 'em'],
            //'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-acfposts_content' => 'overflow: hidden; border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(), [
            'name' => 'box_shadow_textzone',
            'selector' => '{{WRAPPER}} .dce-acfposts_content',
                ]
        );


        $this->end_controls_section();


        // --------------------------------------------------------- [ section Style - Title ]

        $this->start_controls_section(
                'section_style_text', [
            'label' => __('Title', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'show_title' => '1',
                'templatemode_enable' => '',
                'native_templatemode_enable' => ''
            ]
                ]
        );

        $this->add_control(
                'color', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-post-title ' => 'color: {{VALUE}};',
                '{{WRAPPER}} .dce-post-title a' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'show_title' => '1',
            ]
                ]
        );
        $this->add_control(
                'hover_color', [
            'label' => __('Hover Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-post-title a:hover' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'show_title' => '1',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'typography',
            'selector' => '{{WRAPPER}} .dce-post-title',
            'condition' => [
                'show_title' => '1',
            ]
                ]
        );
        $this->add_responsive_control(
                'text_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-post-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            /* 'condition' => [
              'gallery_display_caption' => '',
              ], */
            'condition' => [
                'show_title' => '1',
            ]
                ]
        );
        $this->add_responsive_control(
                'title_space', [
            'label' => __('Space', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-post-title' => 'margin-bottom: {{SIZE}}{{UNIT}};'
            ],
            'condition' => [
                'show_title' => '1',
            ]
                ]
        );


        $this->end_controls_section();

        // ----------------------------------------------- [ section Style - AUTHOR ]

        $this->start_controls_section(
                'section_style_author', [
            'label' => __('Author', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'show_author' => '1',
                'templatemode_enable' => '',
                'native_templatemode_enable' => ''
            ]
                ]
        );
        $this->add_responsive_control(
                'authot_space', [
            'label' => __('Space', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce_author-wrap' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};'
            ],
                ]
        );
        $this->add_control(
                'author_avatar', [
            'label' => __('Avatar image', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'show_author' => '1',
                'enable_author_image' => 'yes'
            ]
                ]
        );
        $this->add_responsive_control(
                'author_avatar_size', [
            'label' => __('Size (px)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'max' => 150,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce_author-avatar' => 'width: {{SIZE}}{{UNIT}};'
            ],
            'condition' => [
                'show_author' => '1',
                'enable_author_image' => 'yes'
            ]
                ]
        );
        $this->add_control(
                'author_name', [
            'label' => __('Name', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'show_author' => '1',
            ]
                ]
        );
        $this->add_control(
                'color_author', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce_author-name' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'show_author' => '1',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'typography_author',
            'selector' => '{{WRAPPER}} .dce_author-name',
            'condition' => [
                'show_author' => '1',
            ]
                ]
        );
        $this->add_responsive_control(
                'authot_name_space', [
            'label' => __('Space', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce_author-name' => 'padding-bottom: {{SIZE}}{{UNIT}};'
            ],
                ]
        );
        $this->add_control(
                'author_bio', [
            'label' => __('Biography', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'show_author' => '1',
                'enable_author_bio' => 'yes'
            ]
                ]
        );
        $this->add_control(
                'color_author_bio', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce_author-bio' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'show_author' => '1',
                'enable_author_bio' => 'yes'
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'typography_author_bio',
            'selector' => '{{WRAPPER}} .dce_author-bio',
            'condition' => [
                'show_author' => '1',
                'enable_author_bio' => 'yes'
            ]
                ]
        );
        $this->add_responsive_control(
                'authot_bio_space', [
            'label' => __('Space', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce_author-bio' => 'padding-bottom: {{SIZE}}{{UNIT}};'
            ],
            'condition' => [
                'show_author' => '1',
                'enable_author_bio' => 'yes'
            ]
                ]
        );
        $this->end_controls_section();
        // -------------------------------------------------- [ section Style - UnicDate ]

        $this->start_controls_section(
                'section_style_unicdate', [
            'label' => __('Anno sopra al blocco', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'unic_date' => 'yes',
            ]
                ]
        );

        $this->add_control(
                'color_unicdate', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-acfposts_date-year' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'unic_date' => 'yes',
            ]
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'typography_unicdate',
            'selector' => '{{WRAPPER}} .dce-acfposts_date-year',
            'condition' => [
                'unic_date' => 'yes',
            ]
                ]
        );

        $this->end_controls_section();

        // ------------------------------------------------ [ section Style - Image ]

        $this->start_controls_section(
                'section_style_image', [
            'label' => __('Image', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'show_image' => '1',
                'templatemode_enable' => '',
                'native_templatemode_enable' => ''
            ]
                ]
        );
        $this->add_responsive_control(
                'img_space', [
            'label' => __('Space', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => -100,
                    'step' => 1,
                ],
                '%' => [
                    'max' => 100,
                    'min' => -100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-acfposts_image' => 'margin-bottom: {{SIZE}}{{UNIT}};'
            ],
            'condition' => [
                'show_image' => '1',
            ]
                ]
        );
        $this->add_control(
                'popover-toggle', [
            'label' => __('Transforms image', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'label_off' => __('Default', 'dynamic-content-for-elementor'),
            'label_on' => __('Custom', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
                ]
        );
        $this->start_popover();

        $this->add_group_control(
                DCE_Group_Control_Transform_Element::get_type(), [
            'name' => 'transform_image',
            'label' => 'Transform image',
            'selector' => '{{WRAPPER}} .dce-acfposts_image',
            'condition' => [
                'show_image' => '1',
            ],
                ]
        );
        $this->end_popover();
        $this->add_group_control(
                DCE_Group_Control_Filters_CSS::get_type(), [
            'name' => 'filters_image',
            'label' => 'Filters image',
            //'selector' => '{{WRAPPER}} img, {{WRAPPER}} .dynamic-content-featuredimage-bg',
            'selector' => '{{WRAPPER}} .dce-acfposts_image .acfposts-image',
                ]
        );
        $this->add_control(
            'blend_mode',
            [
                'label' => __( 'Blend Mode', 'dynamic-content-for-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Normal', 'elementor' ),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-acfposts_image' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );
        /* $this->add_responsive_control(
          'opacity_image', [
          'label' => __('Opacity (%)', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SLIDER,
          'default' => [
          'size' => 1,
          ],
          'range' => [
          'px' => [
          'max' => 1,
          'min' => 0.10,
          'step' => 0.01,
          ],
          ],
          'selectors' => [
          '{{WRAPPER}} .acfposts-image' => 'opacity: {{SIZE}};',
          ],
          'condition' => [
          'show_image' => '1',
          ]
          ]
          );

          $this->add_control(
          'angle_image', [
          'label' => __('Angle (deg)', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SLIDER,
          'size_units' => [ 'deg'],
          'default' => [
          'unit' => 'deg',
          'size' => 0,
          ],
          'range' => [
          'deg' => [
          'max' => 360,
          'min' => -360,
          'step' => 1,
          ],
          ],
          'selectors' => [
          '{{WRAPPER}} .acfposts-image' => '-webkit-transform: rotate({{SIZE}}deg); -moz-transform: rotate({{SIZE}}deg); -ms-transform: rotate({{SIZE}}deg); -o-transform: rotate({{SIZE}}deg); transform: rotate({{SIZE}}deg);',
          ],
          'condition' => [
          'show_image' => '1',
          ]
          ]
          ); */

        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'border_image',
            'label' => __('Image Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .acfposts-image',
            'condition' => [
                'show_image' => '1',
            ]
                ]
        );

        $this->add_control(
                'border_radius_image', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .acfposts-image, {{WRAPPER}} .dce-overlay_hover, {{WRAPPER}} .dce-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'show_image' => '1',
            ]
                ]
        );
        $this->add_control(
                'padding_image', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .acfposts-image, {{WRAPPER}} .dce-overlay_hover, {{WRAPPER}} .dce-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'show_image' => '1',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(), [
            'name' => 'box_shadow_image',
            'selector' => '{{WRAPPER}} .acfposts-image',
            'condition' => [
                'show_image' => '1',
            ]
                ]
        );

        $this->end_controls_section();

        // ---------------------------------------------- [ section Style - Content ]

        $this->start_controls_section(
                'section_style_content', [
            'label' => __('Content', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'show_textcontent!' => '0',
                'templatemode_enable' => '',
                'native_templatemode_enable' => ''
            ]
                ]
        );

        $this->add_control(
                'content_color', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce_textcontent' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'show_textcontent!' => '0',
            ]
                ]
        );

        $this->add_control(
                'content_bgcolor', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce_textcontent' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .dce_textcontent:after' => 'border-bottom-color: {{VALUE}}; border-top-color: {{VALUE}};',
            ],
            'condition' => [
                'show_textcontent!' => '0',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'typography_content',
            'selector' => '{{WRAPPER}} .dce_textcontent',
            'condition' => [
                'show_textcontent!' => '0',
            ]
                ]
        );
        $this->add_responsive_control(
                'content_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'rem'],
            'selectors' => [
                '{{WRAPPER}} .dce_textcontent' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'show_textcontent!' => '0',
            ]
                ]
        );
        $this->add_responsive_control(
                'content_margin', [
            'label' => __('Margin', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce_textcontent' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'show_textcontent!' => '0',
            ]
                ]
        );
        $this->add_control(
                'content_border_radius', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce_textcontent' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'show_textcontent!' => '0',
            ]
                ]
        );


        $this->end_controls_section();
        // ------------------------------------------- [ section Style - Read More Button ]

        $this->start_controls_section(
                'section_style_readmore_button', [
            'label' => __('Read More Button', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'show_readmore' => '1',
                'templatemode_enable' => '',
                'native_templatemode_enable' => ''
            ]
                ]
        );



        $this->start_controls_tabs('readmore_colors');

        $this->start_controls_tab(
                'readmore_colors_normal',
                [
                    'label' => __('Normal', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'show_readmore' => '1',
                    ]
                ]
        );
        $this->add_control(
                'readmore_color', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce_readmore_btn' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'show_readmore' => '1',
            ]
                ]
        );

        $this->add_control(
                'readmore_bgcolor', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce_readmore_btn' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'show_readmore' => '1',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'readmore_border',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}}  .dce_readmore_btn',
                ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
                'readmore_colors_hover',
                [
                    'label' => __('Hover', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'show_readmore' => '1',
                    ]
                ]
        );

        $this->add_control(
                'readmore_color_hover', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce_readmore_btn:hover' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'show_readmore' => '1',
            ]
                ]
        );

        $this->add_control(
                'readmore_bgcolor_hover', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce_readmore_btn:hover' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'show_readmore' => '1',
            ]
                ]
        );
        $this->add_control(
                'readmore_hover_border_color', [
            'label' => __('Border Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'condition' => [
                'show_readmore' => '1',
                'readmore_border_border!' => '',
            ],
            'selectors' => [
                '{{WRAPPER}} .dce_readmore_btn:hover' => 'border-color: {{VALUE}};',
            ],
                ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();



        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'typography_readmore',
            'selector' => '{{WRAPPER}} .dce_readmore_btn',
            'condition' => [
                'show_readmore' => '1',
            ]
                ]
        );
        $this->add_responsive_control(
                'readmore_align', [
            'label' => __('Alignment', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => true,
            'options' => [
                'left' => [
                    'title' => __('Left', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-right',
                ]
            ],
            'default' => 'left',
            'selectors' => [
                '{{WRAPPER}} .dce_readmore_wrapper' => 'text-align: {{VALUE}};',
            ],
                //'prefix_class' => 'acfposts-align-'
                ]
        );
        $this->add_responsive_control(
                'readmore_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce_readmore_btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'show_readmore' => '1',
            ]
                ]
        );
        $this->add_responsive_control(
                'readmore_margin', [
            'label' => __('Margin', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce_readmore_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'show_readmore' => '1',
            ]
                ]
        );

        $this->add_control(
                'readmore_border_radius', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce_readmore_btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'show_readmore' => '1',
            ]
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(), [
            'name' => 'box_shadow_readmore',
            'selector' => '{{WRAPPER}} .dce_readmore_btn',
            'condition' => [
                'show_readmore' => '1',
            ]
                ]
        );
        $this->end_controls_section();
        // ----------------------------------------------------- [ section Style - Metadata ]
        $this->start_controls_section(
                'section_style_metadata', [
            'label' => __('Meta Data', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'show_metadata' => '1',
                'templatemode_enable' => '',
                'native_templatemode_enable' => ''
            ]
                ]
        );

        $this->add_control(
                'metadata_color', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce_metadata-wrap, {{WRAPPER}} .dce_metadata-wrap a' => 'color: {{VALUE}};',
            ],
                ]
        );

        $this->add_control(
                'metadata_color_hover', [
            'label' => __('Text Color Hover', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce_metadata a:hover' => 'color: {{VALUE}};',
            ],
                ]
        );
        $this->add_control(
                'metadata_color_separator', [
            'label' => __('Separator Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce_metadata-wrap .dce_metadata-separator' => 'color: {{VALUE}};',
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'typography_metadata',
            'selector' => '{{WRAPPER}} .dce_metadata',
                ]
        );
        $this->add_responsive_control(
            'metadata_align', [
                'label' => __('Metadata Alignment', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'dynamic-content-for-elementor'),
                        'icon' => 'fa fa-align-right',
                    ]
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dce_metadata-wrap' => 'text-align: {{VALUE}};',
                ],
                
            ]
        );
        $this->add_control(
            'metadata_padding', [
                'label' => __('Items Padding', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dce_metadata' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
           
            ]
        );
        $this->add_responsive_control(
                'metadata_space', [
            'label' => __('Space', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => -100,
                    'step' => 1,
                ],
                '%' => [
                    'max' => 100,
                    'min' => -100,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce_metadata-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};'
            ]
                ]
        );
        

        $this->end_controls_section();
        // ---------------------------------------- [ section Style - Date ]

        $this->start_controls_section(
                'section_style_date', [
            'label' => __('Date', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'show_date' => '1',
                'templatemode_enable' => '',
                'native_templatemode_enable' => ''
            ]
                ]
        );

        $this->add_control(
                'date_color', [
            'label' => __('Date Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-post-date ' => 'color: {{VALUE}};',
                '{{WRAPPER}} .dce-post-date a' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'show_date' => '1',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'date_typography',
            'label' => 'Date Typography',
            'selector' => '{{WRAPPER}} .dce-post-date',
            'condition' => [
                'show_date' => '1',
            ]
                ]
        );
        $this->add_control(
                'date_padding', [
            'label' => __('Date Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-post-date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            /* 'condition' => [
              'gallery_display_caption' => '',
              ], */
            'condition' => [
                'show_date' => '1',
            ]
                ]
        );

        /* Date 2 */
        $this->add_control(
                'date2_color', [
            'label' => __('Date2 Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-post-date .d2 ' => 'color: {{VALUE}};',
                '{{WRAPPER}} .dce-post-date .d2 a' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'show_date' => '1',
                'date_format_2!' => ''
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'date2_typography',
            'label' => 'Date2 Typography',
            'selector' => '{{WRAPPER}} .dce-post-date .d2',
            'condition' => [
                'show_date' => '1',
                'date_format_2!' => ''
            ]
                ]
        );
        $this->add_control(
                'date2_padding', [
            'label' => __('Date2 Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-post-date .d2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            /* 'condition' => [
              'gallery_display_caption' => '',
              ], */
            'condition' => [
                'show_date' => '1',
                'date_format_2!' => ''
            ]
                ]
        );

        /* Date 3 */
        $this->add_control(
                'date3_color', [
            'label' => __('Date3 Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-post-date .d3 ' => 'color: {{VALUE}};',
                '{{WRAPPER}} .dce-post-date .d3 a' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'show_date' => '1',
                'date_format_3!' => ''
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'date3_typography',
            'label' => 'Date3 Typography',
            'selector' => '{{WRAPPER}} .dce-post-date .d3',
            'condition' => [
                'show_date' => '1',
                'date_format_3!' => ''
            ]
                ]
        );
        $this->add_control(
                'date3_padding', [
            'label' => __('Date3 Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-post-date .d3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            /* 'condition' => [
              'gallery_display_caption' => '',
              ], */
            'condition' => [
                'show_date' => '1',
                'date_format_3!' => ''
            ]
                ]
        );

        $this->end_controls_section();


        // -------------------------------------------------------------------------- [ section Style - InfiniteScroll ]
        $this->start_controls_section(
                'section_style_infiniteScroll', [
            'label' => __('Infinite Scroll', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'infiniteScroll_enable' => 'yes',
            ],
                ]
        );
        $this->add_responsive_control(
                'infiniteScroll_spacing', [
            'label' => __('Spacing status', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 1,
            ],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .infiniteScroll' => 'margin-top: {{SIZE}}{{UNIT}};'
            ],
                ]
        );
        $this->add_control(
                'infiniteScroll_heading_button_style', [
            'label' => __('Button', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'infiniteScroll_trigger' => 'button',
            ],
                ]
        );

        $this->add_responsive_control(
                'infiniteScroll_button_align', [
            'label' => __('Alignment', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'options' => [
                'flex-start' => [
                    'title' => __('Left', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-h-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-h-align-center',
                ],
                'flex-end' => [
                    'title' => __('Right', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-h-align-right',
                ],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} div.infiniteScroll' => 'justify-content: {{VALUE}};',
            ],
            'condition' => [
                'infiniteScroll_trigger' => 'button',
            ],
                ]
        );
        $this->start_controls_tabs('infiniteScroll_button_colors');

        $this->start_controls_tab(
                'infiniteScroll_button_text_colors', [
            'label' => __('Normal', 'dynamic-content-for-elementor'),
            'condition' => [
                'infiniteScroll_trigger' => 'button',
            ],
                ]
        );

        $this->add_control(
                'infiniteScroll_button_text_color', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .infiniteScroll button' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'infiniteScroll_trigger' => 'button',
            ],
                ]
        );

        $this->add_control(
                'infiniteScroll_button_background_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .infiniteScroll button' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'infiniteScroll_trigger' => 'button',
            ],
                ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
                'infiniteScroll_button_text_colors_hover', [
            'label' => __('Hover', 'dynamic-content-for-elementor'),
            'condition' => [
                'infiniteScroll_trigger' => 'button',
            ],
                ]
        );
        $this->add_control(
                'infiniteScroll_button_hover_color', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .infiniteScroll button:hover' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'infiniteScroll_trigger' => 'button',
            ],
                ]
        );
        $this->add_control(
                'infiniteScroll_button_background_hover_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .infiniteScroll button:hover' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'infiniteScroll_trigger' => 'button',
            ],
                ]
        );
        $this->add_control(
                'infiniteScroll_button_hover_border_color', [
            'label' => __('Border Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .infiniteScroll button:hover' => 'border-color: {{VALUE}};',
            ],
            'condition' => [
                'infiniteScroll_trigger' => 'button',
            ],
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_control(
                'infiniteScroll_button_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .infiniteScroll button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'infiniteScroll_trigger' => 'button',
            ],
            'separator' => 'before'
                ]
        );
        $this->add_control(
                'infiniteScroll_button_radius', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .infiniteScroll button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'infiniteScroll_trigger' => 'button',
            ],
                ]
        );

        $this->end_controls_section();
        // -------------------------------------------------------------------------- [ section Style - Pagination ]
        $this->start_controls_section(
                'section_style_pagination', [
            'label' => __('Pagination', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'pagination_enable' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'pagination_heading_style', [
            'label' => __('Pagination', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_responsive_control(
                'pagination_align', [
            'label' => __('Alignment', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'options' => [
                'flex-start' => [
                    'title' => __('Left', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-h-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-h-align-center',
                ],
                'flex-end' => [
                    'title' => __('Right', 'dynamic-content-for-elementor'),
                    'icon' => 'eicon-h-align-right',
                ],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .dce-pagination' => 'justify-content: {{VALUE}};',
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'pagination_typography',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-pagination',
                ]
        );
        $this->add_responsive_control(
                'pagination_space', [
            'label' => __('Space', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 10,
            ],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-pagination' => 'padding-top: {{SIZE}}{{UNIT}};'
            ],
                ]
        );
        $this->add_responsive_control(
                'pagination_spacing', [
            'label' => __('Horizontal Spacing', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 1,
            ],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-pagination span, {{WRAPPER}} .dce-pagination a' => 'margin-right: {{SIZE}}{{UNIT}};'
            ],
                ]
        );
        $this->add_control(
                'pagination_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-pagination span, {{WRAPPER}} .dce-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'pagination_radius', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-pagination span, {{WRAPPER}} .dce-pagination a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'pagination_heading_colors', [
            'label' => __('Colors', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );

        $this->start_controls_tabs('pagination_colors');

        $this->start_controls_tab(
                'pagination_text_colors', [
            'label' => __('Normal', 'dynamic-content-for-elementor'),
                ]
        );

        $this->add_control(
                'pagination_text_color', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-pagination span, {{WRAPPER}} .dce-pagination a' => 'color: {{VALUE}};',
            ],
                ]
        );

        $this->add_control(
                'pagination_background_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'scheme' => [
                'type' => Scheme_Color::get_type(),
                'value' => Scheme_Color::COLOR_4,
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-pagination span, {{WRAPPER}} .dce-pagination a' => 'background-color: {{VALUE}};',
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'pagination_border',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-pagination span, {{WRAPPER}} .dce-pagination a',
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'pagination_text_colors_hover', [
            'label' => __('Hover', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'pagination_hover_color', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-pagination a:hover' => 'color: {{VALUE}};',
            ],
                ]
        );
        $this->add_control(
                'pagination_background_hover_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-pagination a:hover' => 'background-color: {{VALUE}};',
            ]
                ]
        );
        $this->add_control(
                'pagination_hover_border_color', [
            'label' => __('Border Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'condition' => [
                'pagination_border_border!' => '',
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-pagination a:hover' => 'border-color: {{VALUE}};',
            ],
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'pagination_text_colors_current', [
            'label' => __('Current', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'pagination_current_color', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-pagination span.current' => 'color: {{VALUE}};',
            ],
                ]
        );
        $this->add_control(
                'pagination_background_current_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-pagination span.current' => 'background-color: {{VALUE}};',
            ]
                ]
        );
        $this->add_control(
                'pagination_current_border_color', [
            'label' => __('Border Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'condition' => [
                'pagination_border_border!' => '',
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-pagination span.current' => 'border-color: {{VALUE}};',
            ],
                ]
        );

        $this->end_controls_tab();


        $this->end_controls_tabs();

        // PrevNext

        $this->add_control(
                'pagination_heading_prevnext', [
            'label' => __('Prev/Next', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_spacing_prevnext', [
            'label' => __('Spacing', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .pageprev' => 'margin-right: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .dce-pagination .pagenext' => 'margin-left: {{SIZE}}{{UNIT}};'
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );

        $this->add_responsive_control(
                'pagination_icon_spacing_prevnext', [
            'label' => __('Icon Spacing', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 50,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .pageprev .fa' => 'margin-right: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .dce-pagination .pagenext .fa' => 'margin-left: {{SIZE}}{{UNIT}};'
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_icon_size_prevnext', [
            'label' => __('Icon Size', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .pageprev .fa' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .dce-pagination .pagenext .fa' => 'font-size: {{SIZE}}{{UNIT}};'
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );

        $this->start_controls_tabs('pagination_prevnext_colors');

        $this->start_controls_tab(
                'pagination_prevnext_text_colors', [
            'label' => __('Normal', 'dynamic-content-for-elementor'),
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );

        $this->add_control(
                'pagination_prevnext_text_color', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .pageprev, {{WRAPPER}} .dce-pagination .pagenext' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );

        $this->add_control(
                'pagination_prevnext_background_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .pageprev, {{WRAPPER}} .dce-pagination .pagenext' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'pagination_prevnext_border',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-pagination .pageprev, {{WRAPPER}} .dce-pagination .pagenext',
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_prevnext_radius', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .pageprev, {{WRAPPER}} .dce-pagination .pagenext' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
                'pagination_prevnext_text_colors_hover', [
            'label' => __('Hover', 'dynamic-content-for-elementor'),
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_prevnext_hover_color', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .pageprev:hover, {{WRAPPER}} .dce-pagination .pagenext:hover' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_prevnext_background_hover_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .pageprev:hover, {{WRAPPER}} .dce-pagination .pagenext:hover' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_prevnext_hover_border_color', [
            'label' => __('Border Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .pageprev:hover, {{WRAPPER}} .dce-pagination .pagenext:hover' => 'border-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
                'pagination_prevnext_border_border!' => '',
            ]
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // FirstLast

        $this->add_control(
                'pagination_heading_firstlast', [
            'label' => __('First/last', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_spacing_firstlast', [
            'label' => __('Spacing', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .pagefirst' => 'margin-right: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .dce-pagination .pagelast' => 'margin-left: {{SIZE}}{{UNIT}};'
            ],
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );
        $this->start_controls_tabs('pagination_firstlast_colors');

        $this->start_controls_tab(
                'pagination_firstlast_text_colors', [
            'label' => __('Normal', 'dynamic-content-for-elementor'),
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );

        $this->add_control(
                'pagination_firstlast_text_color', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .pagefirst, {{WRAPPER}} .dce-pagination .pagelast' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );

        $this->add_control(
                'pagination_firstlast_background_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .pagefirst, {{WRAPPER}} .dce-pagination .pagelast' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'pagination_firstlast_border',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-pagination .pagefirst, {{WRAPPER}} .dce-pagination .pagelast',
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_firstlast_radius', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .pagefirst, {{WRAPPER}} .dce-pagination .pagelast' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
                'pagination_firstlast_text_colors_hover', [
            'label' => __('Hover', 'dynamic-content-for-elementor'),
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_firstlast_hover_color', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .pagefirst:hover, {{WRAPPER}} .dce-pagination .pagelast:hover' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_firstlast_background_hover_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .pagefirst:hover, {{WRAPPER}} .dce-pagination .pagelast:hover' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_firstlast_hover_border_color', [
            'label' => __('Border Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .pagefirst:hover, {{WRAPPER}} .dce-pagination .pagelast:hover' => 'border-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_firstlast' => 'yes',
                'pagination_firstlast_border_border!' => '',
            ]
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Progression

        $this->add_control(
                'pagination_heading_progression', [
            'label' => __('Progression', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_spacing_progression', [
            'label' => __('Spacing', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .progression' => 'margin-right: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );
        $this->start_controls_tabs('pagination_progression_colors');

        $this->start_controls_tab(
                'pagination_progression_text_colors', [
            'label' => __('Normal', 'dynamic-content-for-elementor'),
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );

        $this->add_control(
                'pagination_progression_text_color', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .progression' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );

        $this->add_control(
                'pagination_progression_background_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .progression' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'pagination_progression_border',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-pagination .progression',
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_progression_radius', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .progression' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
                'pagination_progression_text_colors_hover', [
            'label' => __('Hover', 'dynamic-content-for-elementor'),
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_progression_hover_color', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .progression' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_progression_background_hover_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .progression' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_progression_hover_border_color', [
            'label' => __('Border Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-pagination .progression' => 'border-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_progression' => 'yes',
                'pagination_firstlast_border_border!' => '',
            ]
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();
        // -------------------------------------------------------------------------- [ section Style - Carosello/Slider ]

        $this->start_controls_section(
                'section_style_carousel', [
            'label' => __('Carousel', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'posts_style' => ['carousel', 'dualslider'],
            ]
                ]
        );
        $this->add_control(
                'carousel_arrows_options', [
            'label' => __('Arrows', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->start_controls_tabs('carousel_arrows_colors');
        $this->start_controls_tab(
                'carousel_arrows_colors_normal',
                [
                    'label' => __('Normal', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'arrows_color', [
            'label' => __('Arrows Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .slick-arrow .fa' => 'color: {{VALUE}};',
            ],
                ]
        );
        $this->add_control(
                'arrows_bgcolor', [
            'label' => __('Arrows Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .slick-arrow' => 'background-color: {{VALUE}};',
            ],
                ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
                'carousel_arrows_colors_hover',
                [
                    'label' => __('Hover', 'dynamic-content-for-elementor'),
                ]
        );

        $this->add_control(
                'arrows_color_hover', [
            'label' => __('Arrows Color Hover', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .slick-arrow:hover .fa' => 'color: {{VALUE}};',
            ],
                ]
        );

        $this->add_control(
                'arrows_bgcolor_hover', [
            'label' => __('Arrows Background Color Hover', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .slick-arrow:hover' => 'background-color: {{VALUE}};',
            ],
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
                'arrows_size', [
            'label' => __('Arrows size', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 30,
            ],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 10,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .slick-arrow .fa' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}}.arrows-acfposts-position-outside' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}}.arrows-acfposts-position-outside .slick-prev' => 'left: -{{SIZE}}{{UNIT}};',
                '{{WRAPPER}}.arrows-acfposts-position-outside .slick-next' => 'right: -{{SIZE}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'arrows_topspace', [
            'label' => __('Vertical Shift Arrows', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 50,
                'unit' => '%',
            ],
            'tablet_default' => [
                'unit' => '%',
            ],
            'mobile_default' => [
                'unit' => '%',
            ],
            'size_units' => ['px', '%'],
            'range' => [
                '%' => [
                    'min' => -120,
                    'max' => 120,
                ],
                'px' => [
                    'min' => -120,
                    'max' => 120,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .slick-arrow' => 'bottom: {{SIZE}}{{UNIT}};',
            ]
                ]
        );
        $this->add_control(
                'arrows_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'arrows_border_radius', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .slick-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'carousel_dots_options', [
            'label' => __('Dots', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'carousel_dots_enable' => 'yes'
            ]
                ]
        );
        $this->start_controls_tabs('carousel_dots_colors');
        $this->start_controls_tab(
                'carousel_dots_colors_normal',
                [
                    'label' => __('Normal', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'carousel_dots_enable' => 'yes'
                    ]
                ]
        );
        $this->add_control(
                'dots_color', [
            'label' => __('Dots Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .slick-dots li button:before' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'carousel_dots_enable' => 'yes'
            ]
                ]
        );



        $this->end_controls_tab();

        $this->start_controls_tab(
                'carousel_dots_colors_hover',
                [
                    'label' => __('Hover', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'carousel_dots_enable' => 'yes'
                    ]
                ]
        );

        $this->add_control(
                'dots_color_hover', [
            'label' => __('Dots Color Hover', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .slick-dots li button:hover:before' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'carousel_dots_enable' => 'yes'
            ]
                ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
                'carousel_dots_colors_sctive',
                [
                    'label' => __('Active', 'dynamic-content-for-elementor'),
                    'condition' => [
                        'carousel_dots_enable' => 'yes'
                    ]
                ]
        );

        $this->add_control(
                'dots_color_active', [
            'label' => __('Dots Color Active', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .slick-dots li.slick-active button:before' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'carousel_dots_enable' => 'yes'
            ]
                ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->add_responsive_control(
                'dots_size', [
            'label' => __('Dots size', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 10,
            ],
            'range' => [
                'px' => [
                    'max' => 50,
                    'min' => 2,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .slick-dots li button:before, {{WRAPPER}} .slick-dots li button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'carousel_dots_enable' => 'yes'
            ]
                ]
        );
        $this->add_responsive_control(
                'dots_space', [
            'label' => __('Dots space', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 10,
            ],
            'range' => [
                'px' => [
                    'max' => 50,
                    'min' => 2,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .slick-dots li' => 'margin: 0 {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'carousel_dots_enable' => 'yes'
            ]
                ]
        );

        $this->end_controls_section();

        // ------------------------------------------------- [ section Style - Filters ]
        $this->start_controls_section(
                'section_style_filters', [
            'label' => __('Filters', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'posts_style' => 'grid',
                'filters_enable' => 'yes',
            ],
                ]
        );
        $this->add_control(
                'filters_color', [
            'label' => __('Filters Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-filters .filters-item a' => 'color: {{VALUE}};',
            ],
                ]
        );
        $this->add_control(
                'filters_color_hover', [
            'label' => __('Filters Color Hover', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-filters .filters-item a:hover' => 'color: {{VALUE}};',
            ],
                ]
        );
        $this->add_control(
                'filters_color_active', [
            'label' => __('Filters Color Active', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#990000',
            'selectors' => [
                '{{WRAPPER}} .dce-filters .filters-item.filter-active a' => 'color: {{VALUE}};',
            ],
                ]
        );
        $this->add_control(
                'filters_color_divisore', [
            'label' => __('Divider Filters Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-filters .filters-divider' => 'color: {{VALUE}};',
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'typography_filters',
            'label' => __('Typography Filters', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-filters',
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'typography_filters_divider',
            'label' => __('Typography Divider', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-filters .filters-divider',
                ]
        );
        $this->add_responsive_control(
                'filters_padding_items', [
            'label' => __('Filters spacing', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 5,
                'unit' => 'px',
            ],
            'tablet_default' => [
                'unit' => 'px',
            ],
            'mobile_default' => [
                'unit' => 'px',
            ],
            'size_units' => ['px', '%'],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 100,
                ],
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-filters .filters-divider' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'filters_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-filters' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                /* 'condition' => [
                  'gallery_display_caption' => '',
                  ], */
                ]
        );
        $this->add_responsive_control(
                'filters_move_divider', [
            'label' => __('Vertical Shift Divider', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 0,
                'unit' => 'px',
            ],
            'tablet_default' => [
                'unit' => 'px',
            ],
            'mobile_default' => [
                'unit' => 'px',
            ],
            'size_units' => ['px', '%'],
            'range' => [
                '%' => [
                    'min' => -100,
                    'max' => 100,
                ],
                'px' => [
                    'min' => -100,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-filters .filters-divider' => 'top: {{SIZE}}{{UNIT}}; position: relative;',
            ],
                ]
        );
        $this->end_controls_section();

        // ------------------------------------ [Ajax Page Modal - STYLE]
        $this->start_controls_section(
                'section_style_modal', [
            'label' => 'Modal of Ajax Page',
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'ajax_page' => 'yes',
            ],
                ]
        );
        // ------------------------- Il Modale
        $this->add_control(
                'fmw_modal',
                [
                    'label' => __('Modal', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'background',
                    'types' => ['classic', 'gradient'],
                    /* 'fields_options' => [
                      'background' => [
                      'frontend_available' => true,
                      ],
                      'video_link' => [
                      'frontend_available' => true,
                      ],
                      ], */
                    'selector' => '.modals-p-{{ID}} .wrap-p .modal-p',
                ]
        );
        $this->add_responsive_control(
                'fmw_modal_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '.modals-p-{{ID}} .wrap-p .modal-p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        // ------------- Close Button
        $this->add_control(
                'fmw_closebutton',
                [
                    'label' => __('Close button', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $this->start_controls_tabs('fmw_button_colors');

        $this->start_controls_tab(
                'fmw_button_text_colors', [
            'label' => __('Normal', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'color_closemodal', [
            'label' => __('Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '.modals-p-{{ID}} .close .dce-quit-ics:after, .modals-p-{{ID}} .close .dce-quit-ics:before' => 'background-color: {{VALUE}};',
            ],
                ]
        );

        $this->add_control(
                'fmw_button_background_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '.modals-p-{{ID}} .close .dce-quit-ics' => 'background-color: {{VALUE}};',
            ],
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'fmw_button_text_colors_hover', [
            'label' => __('Hover', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'fmw_button_hover_color', [
            'label' => __('Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '.modals-p-{{ID}} .close:hover .dce-quit-ics:after, .modals-p-{{ID}} .close:hover .dce-quit-ics:before' => 'background-color: {{VALUE}};',
            ],
                ]
        );
        $this->add_control(
                'fmw_button_background_hover_color', [
            'label' => __('Background Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '.modals-p-{{ID}} .close .dce-quit-ics:hover' => 'background-color: {{VALUE}};',
            ],
                ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();
        //
        $this->add_responsive_control(
                'buttonsize_closemodal', [
            'label' => __('Button Size', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'separator' => 'before',
            'default' => [
                'size' => 50,
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 20,
                    'max' => 100,
                    'step' => 1
                ]
            ],
            'selectors' => [
                '.modals-p-{{ID}} .close .dce-quit-ics' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'weight_closemodal', [
            'label' => __('Close Width', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 1,
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 20,
                    'step' => 1
                ]
            ],
            'selectors' => [
                '.modals-p-{{ID}} .close .dce-quit-ics:after, .modals-p-{{ID}} .close .dce-quit-ics:before' => 'height: {{SIZE}}{{UNIT}}; top: calc(50% - ({{SIZE}}{{UNIT}}/2));',
            ],
                ]
        );
        $this->add_control(
                'size_closemodal', [
            'label' => __('Close Size (%)', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 60,
                'unit' => '%',
            ],
            'size_units' => ['%'],
            'range' => [
                '%' => [
                    'min' => 20,
                    'max' => 200,
                    'step' => 1
                ]
            ],
            'selectors' => [
                '.modals-p-{{ID}} .close .dce-quit-ics:after, .modals-p-{{ID}} .close .dce-quit-ics:before' => 'width: {{SIZE}}{{UNIT}}; left: calc(50% - ({{SIZE}}{{UNIT}}/2));',
            ],
                ]
        );

        $this->add_responsive_control(
                'vertical_close', [
            'label' => __('Y Position', 'dynamic-content-for-elementor'),
            'separator' => 'before',
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 20,
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1
                ]
            ],
            'selectors' => [
                '.modals-p-{{ID}} .close .dce-quit-ics' => 'top: {{SIZE}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'horizontal_close', [
            'label' => __('X Position', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 20,
                'unit' => 'px',
            ],
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1
                ]
            ],
            'selectors' => [
                '.modals-p-{{ID}} .close .dce-quit-ics' => 'right: {{SIZE}}{{UNIT}};',
            ],
                ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;
        //var_dump($settings['border_radius_textzone']);
        /* global $global_ID;
          global $global_TYPE;
          global $global_is;

          global $in_the_loop;
          $in_the_loop = true;

          $id_page = ''; //get_the_ID();
          $type_page = '';
          if( !empty($global_ID) ) {
          $id_page = $global_ID;
          $type_page = get_post_type($id_page);;
          }else{
          $id_page = get_the_id();
          $type_page = get_post_type();
          } */
        // ------------------------------------------
        $demoPage = get_post_meta(get_the_ID(), 'demo_id', true);
        //
        $id_page = ''; //get_the_ID();
        $type_page = '';

        global $global_ID;
        global $global_TYPE;
        global $in_the_loop;
        global $global_is;
        $in_the_loop = true;
        //
        if (!empty($demoPage)) {
            $id_page = $demoPage;
            $type_page = get_post_type($demoPage);
            //echo 'DEMO ...';
        } else if (!empty($global_ID)) {
            $id_page = $global_ID;
            $type_page = get_post_type($id_page);
            //echo 'global ...';
        } else {
            $id_page = get_the_id();
            $type_page = get_post_type();
            //echo 'native ...';
        }


        $default_posts_per_page = get_option('posts_per_page');
        if ($settings['num_posts'] == 0 || $settings['num_posts'] == '')
            $settings['num_posts'] = $default_posts_per_page;

        // NEW ARCHIVE (in caso ci trovassimo in un archivio)
        if (is_archive()) {
            global $wp_taxonomies;
            //var_dump($wp_taxonomies);
            $queried_object = get_queried_object();
            if (is_tax() || is_category() || is_tag()) {
                if ($queried_object->name == 'product') {
                    // WOOCOMMERCE
                    $taxonomy = reset($queried_object->taxonomies);
                    if (isset($wp_taxonomies[$taxonomy])) {
                        $type_page = $wp_taxonomies[$taxonomy]->object_type;
                    }
                } else {
                    //var_dump($queried_object);

                    $taxonomy = $queried_object->taxonomy;
                    if (isset($wp_taxonomies[$taxonomy])) {
                        $type_page = $wp_taxonomies[$taxonomy]->object_type;
                    }
                    //echo $taxonomy;   
                }
            }

            //echo 'ARCHIVE ';
            //var_dump($queried_object);
            //$type_page = array('post','work');
        }
        // ------------------------------------------
        // ------- QUERY -----------------------------------------
        $args = array();
        $taxquery = array();

        $exclude_io = array();
        $posts_excluded = array();
        $terms_query_exclued = array();
        if (is_singular()) {
            //echo 'ei: '.$settings['exclude_io'].' '.count($exclude_io);
            if ($settings['exclude_io'])
                $exclude_io = array($id_page);
        } else if (is_home() || is_archive()) {
            $exclude_io = array();
        }

        if ($settings['exclude_posts'])
            $posts_excluded = $settings['exclude_posts'];
        if ($settings['exclude_page_parent']) {
            $use_parent_page = array(0);
        } else {
            $use_parent_page = array();
        }

        $terms_query = 'all';
        if ($settings['taxonomy']) {
            

            /* INCLUDED */
            // Nuovo! 
            if(!empty($settings['terms_' . $settings['taxonomy']])){
                $terms_query = $settings['terms_' . $settings['taxonomy']];
            }
            

            if (is_array($terms_query) && !empty($terms_query)) {

                // metodo per recuperare i termini 
                $terms_query = $this->get_terms_query($settings, $id_page);

                //var_dump($terms_query);
                $taxquery = array();

                if (count($terms_query) > 1)
                    $taxquery['relation'] = $settings['combination_taxonomy'];
                //
                foreach ($terms_query as $term_query) {

                    $taxquery[] = array(
                        'taxonomy' => $settings['taxonomy'],
                        'field' => 'id',
                        'terms' => $term_query,
                            //'compare' => '' // AAAAAAAAAAAAAAAAA
                    );
                }
            } else {
                $taxquery = array(
                    array(
                        'taxonomy' => $settings['taxonomy'],
                        'field' => 'id',
                        'terms' => $terms_query,
                    )
                );
            }


            /* EXCLUDED */
            $terms_query_exclued = $settings['terms_' . $settings['taxonomy'] . '_excluse'];
            //$terms_query_exclued = $this->get_terms_query($settings, $id_page);
            if (!empty($terms_query_exclued)) {
                $taxquery_excluded = array();
                if (count($terms_query_exclued) > 1) {
                    $taxquery_excluded['relation'] = $settings['combination_taxonomy_excluse'];
                }
                foreach ($terms_query_exclued as $term_query) {
                    $taxquery_excluded[] = array(
                        'taxonomy' => $settings['taxonomy'],
                        'field' => 'term_id',
                        'terms' => $term_query,
                        'operator' => 'NOT IN',
                    );
                }

                if (empty($taxquery)) {
                    $taxquery = $taxquery_excluded;
                } else {
                    $taxquery = array(
                        'relation' => 'AND',
                        $taxquery,
                        $taxquery_excluded,
                    );
                    //var_dump($taxquery_excluded);
                }
            }
            
        }
        //echo $settings['taxonomy'];
        //
        // Qui voglio elaborare la cosa in base alla ACF taxonomy che usa la lista dei termini associati nel post specifico
        if($settings['terms_from_acf'] && $settings['acf_taxonomy']){

            $acfterm = get_field($settings['acf_taxonomy'], $id_page);

            $terms_query = array();
            if ($acfterm) {

                foreach ($acfterm as $term) {
                    //$term = get_term($term);
                    $terms_query[] = $term;
                }
            

                $taxquery = array(
                    array(
                        'taxonomy' => $settings['taxonomy'],
                        'field' => 'id',
                        'terms' => $terms_query,
                    )
                );


            }
        }


        if ($settings['query_type'] == 'specific_posts') {
            $types = DCE_Helper::get_post_types();
            $specific_posts = array();
            foreach ($types as $t => $tname) {
                if (isset($settings['specific_pages' . $t])) {
                    $t_array = $settings['specific_pages' . $t];
                    if (is_array($t_array) || is_object($t_array)) {
                        $specific_posts = array_merge($specific_posts, $t_array);
                    }
                }
            }
            //var_dump($specific_posts);
            $args = array(
                'post_type' => 'any',
                'post__in' => $specific_posts,
                'order' => $settings['order'],
                'orderby' => $settings['orderby'],
                'meta_key' => $settings['acf_metakey'],
                'post_status' => 'publish',
            );
            
        } else if ($settings['query_type'] == 'dynamic_mode') {

            /*
              echo "Questa impostazione legge il Type, la taxonomy, e i termini associati al post in cui risiede il widget.";
              echo "ID: ".$id_page."<br />";
              echo "Title: ".get_the_title()."<br />";
              echo "Type: ".get_post_type()."<br />";

              echo "taxonomy: <br />"; //.get_post_taxonomies()."";
              $taxonomy_list = get_post_taxonomies();
              foreach($taxonomy_list as $tax) {
              echo $tax."<br />";
              }
              echo "terms: "."<br />";
              $terms_list = wp_get_post_terms($id_page, get_post_taxonomies(), array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all', 'hide_empty' => true) );

              foreach($terms_list as $term) {
              echo $term->slug."<br />"; //do something here


              } */


            //var_dump(is_tax());
            $array_taxquery = [];
            $taxonomy_list = [];

            // DYNAMIC MODE:
            if (is_archive()) {
                // Considero se sono in un archivio (Term)
                $queried_object = get_queried_object();
                //var_dump($queried_object);
                if (is_tax() || is_category() || is_tag()) {
                    $taxonomy_list[0] = $queried_object->taxonomy;
                } else {
                    
                }
            } else if (is_home()) {
                // Considero se sono in home del type
            } else if (is_single()) {
                // Considero se sono in un single-post (Correlati)
                $taxonomy_list = get_post_taxonomies($id_page);
            }

            //$type_p = get_post_type($global_ID);
            //echo $settings['taxonomy'];
            //var_dump($taxonomy_list);


            if(!empty($taxonomy_list)){
            foreach ($taxonomy_list as $tax) {
                //echo $tax."<br />";
                //var_dump(get_post_taxonomies( $id_page ));

                $terms_list = [];
                $lista_dei_termini = [];

                if (is_single()) {
                    // Considero se sono in un single-post (Correlati)
                    if ($settings['taxonomy'] == $tax)
                        $terms_list = wp_get_post_terms($id_page, $tax, array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all', 'hide_empty' => true));

                    foreach ($terms_list as $term) {
                        $lista_dei_termini[] = $term->term_id;
                    }
                } else if (is_archive()) {
                    // Considero se sono in un archivio (Term)
                    $lista_dei_termini[0] = $queried_object->term_id;
                }
                //echo $id_page.' '.$type_page.' '.$settings['taxonomy'];
                //echo $tax.' - '.count($terms_list)."<br />";
                //echo $tax . ': ' . count($lista_dei_termini);
                if (count($lista_dei_termini) > 0) {

                    $array_taxquery = array();

                    if (count($lista_dei_termini) > 1)
                        $array_taxquery['relation'] = $settings['combination_taxonomy'];
                    //
                    foreach ($lista_dei_termini as $termine) {

                        $array_taxquery[] = array(
                            'taxonomy' => $tax,
                            'field' => 'id',
                            'terms' => $termine,
                        );
                    }
                }
                

                /* EXCLUDED */
                $terms_query_exclued = $settings['terms_' . $tax . '_excluse'];
                //$terms_query_exclued = $this->get_terms_query($settings, $id_page);
                if (!empty($terms_query_exclued)) {
                    $array_taxquery_excluded = array();
                    if (count($terms_query_exclued) > 1) {
                        $array_taxquery_excluded['relation'] = $settings['combination_taxonomy_excluse'];
                    }
                    foreach ($terms_query_exclued as $term_query) {
                        $array_taxquery_excluded[] = array(
                            'taxonomy' => $tax,
                            'field' => 'term_id',
                            'terms' => $term_query,
                            'operator' => 'NOT IN',
                        );
                    }

                    if (empty($array_taxquery)) {
                        $array_taxquery = $array_taxquery_excluded;
                    } else {
                        $array_taxquery = array(
                            'relation' => 'AND',
                            $array_taxquery,
                            $array_taxquery_excluded,
                        );
                    }
                }

                // or and....
                /* if (count($lista_dei_termini) > 0) {
                  $array_taxquery[] = array(
                  'taxonomy' => $tax,
                  'field' => 'id',
                  'terms' => $lista_dei_termini
                  );
                  } */
                }
            }






            // Se la taxQuery dynamica non da risultati uso quella statica.
            if(!$array_taxquery) $array_taxquery = $taxquery;
            //var_dump($array_taxquery);

            if (is_array($type_page)) {
                if ($cptkey = array_search('elementor_library', $type_page))
                    $type_page[$cptkey] = 'post';
            } else {
                if ('elementor_library' == $type_page)
                    $type_page = 'post';
            }

            $args = array(
                'post_type' => $type_page,
                'posts_per_page' => $settings['num_posts'],
                'order' => $settings['order'],
                'orderby' => $settings['orderby'],
                'meta_key' => $settings['acf_metakey'],
                'post__not_in' => array_merge($posts_excluded, $exclude_io),
                'post_parent__not_in' => $use_parent_page,
                'tax_query' => $array_taxquery,
                /* 'tax_query'         => array(
                  array(
                  'taxonomy' => $settings['taxonomy'],
                  'field' => 'id',
                  'terms' => $lista_dei_termini
                  )
                  ), */
                'post_status' => 'publish',
            );
            // ----------------------------------------------------------
            if ($settings['page_parent']) {
                //
                if ($settings['parent_source']) {
                    // rispetto a me-stesso prendo il post genitore
                    $args['post_parent'] = wp_get_post_parent_id($id_page);
                } else if ($settings['child_source']) {
                    $args['post_parent'] = $id_page;
                } else {
                    $args['post_parent'] =  DCE_Helper::get_rev_ID($settings['specific_page_parent'],$type_page);
                }
            }
            if ($settings['post_offset']) {
                $args['offset'] = $settings['post_offset'];
            }
            // ----------------------------------------------------------
        } else if ($settings['query_type'] == 'acf_relations') {

            $relations_ids = get_field($settings['acf_relationship'], $id_page, false);
            //$relations_ids = unserialize(get_post_meta( $id_page, $settings['acf_relationship'] ));
            if (!empty($relations_ids)) {

                $relations_type = get_post_type($relations_ids[0]);
                //
                $ordinamentoRelationship = 'post__in';
                if ($settings['orderby'] != 'menu_order') {
                    $ordinamentoRelationship = $settings['orderby'];
                }
                $args = array(
                    'post_type' => 'any', //$relations_type,
                    'posts_per_page' => $settings['num_posts'],
                    'post__in' => $relations_ids,
                    'post_status' => 'publish',
                    'orderby' => $ordinamentoRelationship,
                    'order' => $settings['order'],
                );
            }
        } else if ($settings['query_type'] == 'get_cpt') {

            //var_dump($settings['post_type']);

            $args = array(
                'post_type' => $settings['post_type'],
                'posts_per_page' => $settings['num_posts'],
                'order' => $settings['order'],
                'orderby' => $settings['orderby'],
                'meta_key' => $settings['acf_metakey'],
                'tax_query' => $taxquery,

                'post_parent__not_in' => $use_parent_page,
                'post__not_in' => array_merge($posts_excluded, $exclude_io),
                'post_status' => 'publish',
            );
            
            //
            if ($settings['page_parent']) {
                //
                if ($settings['parent_source']) {
                    // rispetto a me-stesso prendo il post genitore
                    $args['post_parent'] = wp_get_post_parent_id($id_page);
                } else if ($settings['child_source']) {
                    $args['post_parent'] = $id_page;
                } else {
                    $args['post_parent'] = DCE_Helper::get_rev_ID($settings['specific_page_parent'],$type_page);
                }
            }
            if ($settings['post_offset']) {
                $args['offset'] = $settings['post_offset'];
            }
        }




        global $paged; //aggiunto da poglie perchè la paginazione mi faceva dispetti
        //echo '------ '.$paged.' -----';
        $paged = $this->get_current_page(); //(get_query_var('paged')) ? get_query_var('paged') : 1;
        $args['paged'] = $paged;
        $per_page = $settings['num_posts'];
        $offset = ( $paged - 1 ) * $per_page;

        

        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - @p
        // da implementare in base all'autore della pagina utile nella pagina utente
        if ($settings['by_author']) {
            $args['author'] = get_the_author_meta('ID');
            $args['posts_per_archive_page'] = $settings['num_posts'];
        }
        if ($settings['by_users']) {
            $args['author__in'] = $settings['by_users'];
        }
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - @/p
        
        if ($settings['querydate_mode']) {
            
            // get the field to compare
            $date_field = $settings['querydate_field'];
            if ($settings['querydate_field'] == 'post_meta') {
                $date_field = $settings['querydate_field_meta'];
            }
            if ($settings['querydate_mode'] == 'future') {
                $date_field = $settings['querydate_field_meta_future'];
            }

            if ($date_field) {
                $date_after = $date_before = false;
                switch ($settings['querydate_mode']) {
                    case 'past':
                        $date_before = date('Y-m-d H:i:s');
                        break;
                    case 'future':
                        $date_after = date('Y-m-d H:i:s');
                        break;
                    case 'today':
                        $date_after = date('Y-m-d 00:00:00');
                        $date_before = date('Y-m-d 23:23:59');
                        break;
                    case 'yesterday':
                        $date_after = date('Y-m-d 00:00:00', strtotime('-1 day'));
                        $date_before = date('Y-m-d 23:23:59', strtotime('-1 day'));
                        break;
                    case 'days':
                    case 'weeks':
                    case 'months':
                    case 'years':
                        $date_after = '-'.$settings['querydate_range'].' '.$settings['querydate_mode'];
                        $date_before = 'now';
                        break;
                    case 'period':
                        $date_after = $settings['querydate_date_from'.$settings['querydate_date_type']];
                        $date_before = $settings['querydate_date_to'.$settings['querydate_date_type']];
                        break;
                }

                if ($date_field == 'post_date') { 
                    // compare by post publish date
                    $args['date_query'] = array(
                        array(
                            'after'     => $date_after,
                            'before'    => $date_before,
                        'inclusive' => true,
                        )
                    );
                } else {
                    // compare by post meta
                    if ($date_after) $date_after = date('Ymd', strtotime($date_after));
                    if ($date_before) $date_before = date('Ymd', strtotime($date_before));
                    if ($date_before && $date_after) {
                        $args['meta_query'] = array(
                            array(
                                'key'     => $date_field,
                                'value'   => array($date_after,$date_before),
                                'meta_type' => 'DATETIME',
                                'compare' => 'BETWEEN'
                            )
                        );
                    } else if ($date_after) {
                        $args['meta_query'] = array(
                            array(
                                'key'     => $date_field,
                                'value'   => $date_after,
                                'meta_type' => 'DATETIME',
                                'compare' => '>='
                            )
                        );
                    } else {
                        $args['meta_query'] = array(
                            array(
                                'key'     => $date_field,
                                'value'   => $date_before,
                                'meta_type' => 'DATETIME',
                                'compare' => '<='
                            )
                        );
                    }
                }
            }
        }
        
        //var_dump($args);
        //echo $settings['num_posts']; 
        //echo $settings['order']; 
        //echo $settings['orderby'];
        //echo $settings['image_size'];
        // Build the WordPress query

        $p_query = new \WP_Query($args);

        
        //$p_query = new \DynamicContentForElementor\DCE_Query($args);
        ///////////////////////////////////////////////////////////// Filters /////////////////////////////////////////
        if ($settings['filters_enable'] && $settings['posts_style'] == 'grid') {
            $include_terms = 'all';
            $tag_filter = 'span';
            $divisore_f = '';
            //

            if ($settings['filters_taxonomy'] || $settings['taxonomy']) {
                


                /* if ($settings['taxonomy'] != "") {
                  $include_terms = array(); //$settings['category'];
                  //echo  $settings['category'];
                  $term_id = $settings['category'];
                  $taxonomy_name = $settings['taxonomy'];
                  //echo $taxonomy_name;
                  //echo '$term_id: '.$term_id;
                  $term_children = get_term_children($term_id, $taxonomy_name);
                  foreach ($term_children as $child) {
                  $term = get_term_by('id', $child, $taxonomy_name);
                  array_push($include_terms, $child);
                  //echo $child.'-';
                  //echo '<li><a href="' . get_term_link( $child, $taxonomy_name ) . '">' . $term->name . '</a></li>';
                  }
                  array_push($include_terms, $settings['category']);
                  } */
                

                //adesso controllo se $settings['taxonomy'] oppure $settings['filters_taxonomy'] ... se la toxonomy dei filtri-isotope è vuota uso quella della taxonomy del filtri Query
                


                $term_filter = $settings['taxonomy'];
                if( $settings['filters_taxonomy'] ) $term_filter = $settings['filters_taxonomy'];
                


                //var_dump($settings['taxonomy']);


                /// da 1.8 uso ---------------------------------------------------------
                $args_filters = $args;
                $args_filters['taxonomy'] = $term_filter;
                $args_filters['hide_empty'] = $settings['filter_hide_empty'] ? false : true;

                //$args_filters['tax_query'] = $taxquery;

                //$args_filters['include'] = $terms_query;
                //$args_filters['exclude'] = $terms_query_exclued;
                //$args_filters['offset'] = $offset;

                // -------- Questa parte è stata aggiungere per gestire i filtri in base ai post restituito ed eviare filtri vuoti.



                //var_dump($taxquery);
                $args_posts = $args;
                $args_posts['fields'] = 'ids';



                // Questo serve per includere solo i posts presenti e calcolati per la vista 
                $someposts = get_posts($args_posts);
                $args_filters['object_ids'] = $someposts;
                // -------------------------------------------------------------------


                
                //var_dump($args_posts);
                // Considero solo gli elementi di primo Livello ....
                if ($settings['filters_taxonomy_first_level_terms']) {
                    $include_terms = array(); //'all';
                    
                    $terms = get_terms($args_filters); // ..Get all the terms

                    foreach ($terms as $term) { //Cycle through terms, one at a time
                        if ($term->parent == '0') {
                            //echo $term->parent;
                            $include_terms[] = $term->term_id;
                        }
                    }
                } else {
                    

                    if (isset($settings['filters_taxonomy_terms_' . $term_filter]) && !empty($settings['filters_taxonomy_terms_' . $term_filter])) {
                        $include_terms = $settings['filters_taxonomy_terms_' . $term_filter];
                    }
                }
                // ---------------------------------------------
                $args_filters['include'] = $include_terms;
                $args_filters['orderby'] = 'parent'; //$settings['orderby_filters'];
                //$args_filters['parent'] = 1;
                // ---------------------------------------------

                //echo $term_filter;
                //$term_list_filters = get_the_terms( get_the_ID(), $term_filter );
                // ****************************************** Masonry Filters RENDER ($term_list_filters)
                //$page = ( get_query_var('paged') ) ? get_query_var( 'paged' ) : 1;
                

                
                // 1.8
                $term_list_filters = get_terms($args_filters);
                
                // 1.6
                /*$term_list_filters = get_terms(array(
                    'taxonomy' => $settings['filters_taxonomy'],
                    'hide_empty' => $settings['filter_hide_empty'] ? false : true,
                    'hierarchical' => true,
                    'orderby' => 'parent',
                    //'number' => $per_page,
                    'offset' => $offset,
                    //'hierarchical' => false,
                    //'parent ' => 2,
                    //'child_of' => 0,
                    //'child_of' => true,
                    'include' => $include_terms
                ));*/
                

                /// DEBUG
                /*$term_query = new \WP_Term_Query($args_filters);
                var_dump($args_filters['tax_query']);
                foreach ( $term_query->terms as $term ) {
                    echo "Term ID: {$term->term_id}<br>";
                    echo "Term Name: {$term->name}<br><br>";
                    echo "Term COUNT: {$term->count}<br><br>";
                }*/

            }
            
            // ACF relation filter *************************************
            //var_dump($args_filters);
            if ($settings['filters_acf'] != '0') {

                //$term_list_filters = [];
                $counter = 0;

                if ($p_query->have_posts()) :
                    while ($p_query->have_posts()) : $p_query->the_post();
                        $id_page = get_the_ID();
                        $acffield = get_field($settings['filters_acf']);
                        //$acffield = get_post_meta( $id_page, $settings['filters_acf'] );
                        $acfslug = $this->createSlug($acffield);
                        $obj = (object) [
                                    'name' => $acffield,
                                    "slug" => $acfslug
                        ];
                        // 
                        $term_list_filters[$acfslug] = $obj;

                        $counter++;
                    endwhile;

                    // Reset the post data to prevent conflicts with WP globals
                    // Ripristina Query & Post Data originali
                    wp_reset_query();
                    wp_reset_postdata();
                    ?>

                    <?php
                // End post check
                endif;
                // ***************************************************
                //$mmm = array_unique( $term_list_filters );
            } // end acf filters

            echo '<div class="dce-filters">';
            //echo $settings['filters_acf'];
            //echo $term_filter.' '.get_the_ID(xf);
            //var_dump($term_list_filters);
            $cont_f = 1;
            if (!empty($term_list_filters)) {

                $alltext = $settings['alltext_filter'];

                echo '<' . $tag_filter . ' class="filters-item filter-active">' . $divisore_f . '<a href="#" data-filter="*">' . __($alltext, 'dynamic-content-for-elementor') . '</a></' . $tag_filter . '>';
                foreach ($term_list_filters as $filter) {
                    // L'etichetta del filtro
                    $filternome = $filter->name;

                    // Lo slug del filtro
                    $filterslug = $filter->slug;
                    
                    /* if( $cont_f > 1) */ $divisore_f = '<span class="filters-divider">' . $settings['separator_filter'] . '</span>';
                    if ($filternome != "")
                        echo '<' . $tag_filter . ' class="filters-item">' . $divisore_f . '<a href="#" data-filter=".' . $filterslug . '">' . $filternome . '</a></' . $tag_filter . '>';
                    $cont_f++;
                }
            }
            echo '</div>';
        }

        // **************************** Grid/Carousel ************************
        $dataStyle = '';
        $styleClass = '';
        $dataGrid = '';
        // ------- Simple data ----------------------------------------------
        if ($settings['posts_style'] == 'simplegrid') {
            $dataStyle = ' data-style="simple"';
            $styleClass = ' simple-style';
            /* $dataCarousel = ' data-numcol="'.$settings['slides_to_show'].'" data-numcol-tablet="'.$settings['slides_to_show_tablet'].'" data-numcol-mobile="'.$settings['slides_to_show_mobile'].'"';
              $dataCarousel .= ' data-numscroll="'.$settings['slides_to_scroll'].'" data-numscroll-tablet="'.$settings['slides_to_scroll_tablet'].'" data-numscroll-mobile="'.$settings['slides_to_scroll_mobile'].'"'; */
        } else
        if ($settings['posts_style'] == 'flexgrid') {
            $dataStyle = ' data-style="flexgrid"';
            $styleClass = ' flexgrid-style';
        } else
        // ------- Carousel data ----------------------------------------------
        if ($settings['posts_style'] == 'carousel' || $settings['posts_style'] == 'dualslider') {
            $dataStyle = ' data-style="carousel"';
            $styleClass = ' carowsel-style';
            /* $dataCarousel = ' data-numcol="'.$settings['slides_to_show'].'" data-numcol-tablet="'.$settings['slides_to_show_tablet'].'" data-numcol-mobile="'.$settings['slides_to_show_mobile'].'"';
              $dataCarousel .= ' data-numscroll="'.$settings['slides_to_scroll'].'" data-numscroll-tablet="'.$settings['slides_to_scroll_tablet'].'" data-numscroll-mobile="'.$settings['slides_to_scroll_mobile'].'"'; */
        } else
        // ------- Grid data --------------------------------------------------
        if ($settings['posts_style'] == 'grid') {
            $dataStyle = ' data-style="grid"';
            $dataGrid = ' data-fitrow="' . $settings['fitrow_enable'] . '"';
            $styleClass = ' grid-style';
        } else
        // ------- Swiper data --------------------------------------------------
        if ($settings['posts_style'] == 'swiper') {
            $dataStyle = ' data-style="swiper"';
            $dataGrid = ' data-fitrow="' . $settings['fitrow_enable'] . '"';
            $styleClass = ' swiper-style';
        } else
        if ($settings['posts_style'] == 'timeline') {
            $dataStyle = ' data-style="timeline"';
            $styleClass = ' timeline-style';
        }
        // ---------------------------------------------------------------------
        $stringInfiniteScroll = '';
        if ($settings['infiniteScroll_enable']) {
            $stringInfiniteScroll = ' is_infiniteScroll';
        }
        // dce-posts-wrap
        // dce-post-item
        $stringSameHeightWrap = '';
        $stringSameHeightItem = '';

        if ($settings['sameheight_enable'] && $settings['posts_style'] == 'flexgrid') {
            $stringSameHeightWrap . $stringInfiniteScroll = ' equalHMRWrap eqWrap';
            $stringSameHeightItem = ' equalHMR eq';
        }

        if ($settings['ajax_page_enabled'] == 'open') {
            ?>
            <script type='text/javascript'>
                /* <![CDATA[ */
                var dceAjaxPath = {"ajaxurl": "<?php echo admin_url('admin-ajax.php'); ?>"};
                /* ]]> */
            </script>

            <?php
        }
        $counter = 0;

        // qui definisco la variabili per la creazione della data unica sopra al blocco/post
        $data_unica_old = '';
        $data_unica_new = '';
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        $classContainer = '';
        $classWrap = '';
        $classItem = '';
        $classItemImage = '';
        $classItemContent = '';
        $classItemDate = '';
        $classItemReadMore = '';

        if ($settings['posts_style'] == 'swiper') {
            $classContainer = 'swiper-container swiper-container-' . $settings['direction_slider'];
            $classWrap = ' swiper-wrapper';
            $classItem = ' swiper-slide';
            //
            echo '<div class="' . $classContainer . '">';
        }
        if ($settings['posts_style'] == 'timeline') {
            $classContainer = 'cd-timeline js-cd-timeline';
            $classWrap = ' cd-timeline__container';
            $classItem = ' cd-timeline__block js-cd-block';
            $classItemImage = ' cd-timeline__img cd-timeline__img--picture js-cd-img';
            $classItemContent = ' cd-timeline__content js-cd-content';
            $classItemDate = ' cd-timeline__date';
            $classItemReadMore = ' cd-timeline__read-more';

            echo '<div class="' . $classContainer . '">';
        }
        $sonoPronto = false;




        // Output posts
        // ////////////////////////////////////////// Query POST ///////////////////////////////////////////
        if ($p_query->have_posts()) :
            $animation_class = !empty($settings['hover_animation']) ? 'elementor-animation-' . $settings['hover_animation'] : '';
            $sonoPronto = true;
            $postlength = $p_query->post_count;

            // DualSlider: Sopra al grid le immagini grandi
            ?>
            <div class="acfposts-grid dce-posts-wrap<?php echo $styleClass . $classWrap . $stringSameHeightWrap . $stringInfiniteScroll; ?>"<?php echo $dataStyle . $dataGrid; ?>>

            <?php
            //print_r($settings['slides_to_show']);
            $original_global_ID = $id_page;

            // Start loop
            while ($p_query->have_posts()) : $p_query->the_post();
                // nel caso in cui num_post è -1 considero ugualmente l'offset
                if ($settings['post_offset'] > $counter && $settings['num_posts'] == -1) {
                    $counter++;
                    continue;
                }
                $id_page = get_the_ID();
                $global_is = 'singular';
                // ------------------------------------------ wow
                $wow_enable = $settings['enabled_wow'];
                if ($wow_enable) {
                    $wow_coeff = (float) $settings['wow_coef'];
                    $wow_delay = ' data-wow-delay="' . ($counter * $wow_coeff) . 's"';
                    $wow_animations = $settings['wow_animations'];
                    $wow_string = ' wow ' . $wow_animations;
                } else {
                    $wow_string = '';
                    $wow_delay = '';
                }
                // -------------------------------------------- 
                // 0 - se i filtri sono abilitati
                $filters_string_class = "";
                //
                if ($settings['filters_enable']) {

                    if ($term_filter != '') {
                        // 
                        // 1 - devo saper quali termini sono associati al post
                        $terms = get_the_terms($id_page, $term_filter);

                        if ($terms && !is_wp_error($terms)) {

                            $draught_links = array();

                            foreach ($terms as $term) {
                                $draught_links[] = $term->slug;
                            }

                            $filters_string_class .= ' ' . implode(" ", $draught_links);
                        } // end if esistono terms
                    } // end se sono in filters_Taxonomy
                    if ($settings['filters_acf'] != '0') {
                        // se .. $settings['filters_acf'] .. 
                        $acf_field = $settings['filters_acf'];

                        $slug = get_field($acf_field);
                        //$slug = get_post_meta( $id_page, $acf_field );
                        $slug_acf_field = $this->createSlug($slug);

                        $filters_string_class .= ' ' . $slug_acf_field;
                    } // end se sono in filters_acf
                } // end if filters_enable
                // 2 - li scrivo su una stringa separati da spazio
                // 3 - metto la stringa nella classe dell'item
                ?>

                    <div data-dce-post-id="<?php echo $id_page; ?>" class="dce-post-item column-<?php echo $settings['columns_grid'] . $classItem; ?>  <?php echo $filters_string_class . $stringSameHeightItem . $wow_string; ?>"<?php echo $wow_delay; ?>>

                <?php
                if ($settings['unic_date']) {
                    // La data unica sopra al blocco -------------------------
                    if ($counter > 0) {
                        $data_unica = '';
                        $data_unica_old = $data_unica_new;
                        $data_unica_new = get_the_date('Y');
                        //
                        if ($data_unica_new != $data_unica_old) {
                            $data_unica = $data_unica_new;
                        }
                        ?>
                                <span class="dce-acfposts_date-year"><?php echo $data_unica; ?></span>
                            <?php
                        }
                    }
                    // -------------------------------------------------------- 
                    ?>

                        <div class="dce-wrapper <?php echo $animation_class; ?>">

                    <?php //echo $counter.' - '.$wow_coeff;  ?>

                    <?php
                    // XXXXXXXXXXXX
                    //
                    if ($settings['templatemode_enable'] == '' && $settings['native_templatemode_enable'] == '') {
                        ?>

                        <?php
                        // deprecated: last version
                        if ($settings['textcontent_position'] == 'top_out') {
                            $this->generate_content($settings, $id_page);
                        }


                        // IMAGE
                        if (has_post_thumbnail() && $settings['show_image'] != 0 && $settings['order_image'] == '') {
                            $this->generate_image($settings, $classItemImage);
                        }

                        /*

                          Image
                          Date
                          Title
                          Meta Data
                          Content
                          Author
                          ACF items
                          Read More
                         */
                        ?>
                                <div class="dce-acfposts_content<?php echo $classItemContent; ?>"> 
                        <?php
                        $counter_item = 1;

                        //
                        if (isset($settings['list_layout_posts']) && !empty($settings['list_layout_posts'])) {
                            $items_ordering = $settings['list_layout_posts'];
                            foreach ($items_ordering as $listitem) {

                                $item_name = $listitem['list_name'];
                                //var_dump($listitem['_id']);
                                if ($item_name == 'Image' && $settings['order_image'] == 'yes') {
                                    if (has_post_thumbnail() && $settings['show_image'] != 0) {

                                        $this->generate_image($settings, $classItemImage);
                                    }
                                }
                                if ($item_name == 'Date') {
                                    if ($settings['show_date'] != 0) {
                                        $this->generate_date($settings, $classItemDate, $id_page);
                                    }
                                }
                                if ($item_name == 'Title') {
                                    if ($settings['show_title'] == 1 && $settings['title_inout'] == 'out') {
                                        $this->generate_title($settings);
                                    }
                                }
                                if ($item_name == 'Meta Data') {
                                    if ($settings['show_metadata'] == 1 && $settings['metadata_inout'] == 'out') {
                                        $this->generate_meta($settings, $id_page);
                                    }
                                }
                                if ($item_name == 'Content') {
                                    if ($settings['textcontent_position'] == 'bottom_in') {
                                        $this->generate_content($settings, $id_page);
                                    }
                                }
                                if ($item_name == 'Author') {
                                    if ($settings['show_author'] == 1 && $settings['author_inout'] == 'out') {
                                        $this->generate_author($settings);
                                    }
                                }
                                if ($item_name == 'ACF items') {
                                    if ($settings['show_acfitems'] == '1') {
                                        $this->generate_acfitems($settings, $id_page);
                                    }
                                }
                                if ($item_name == 'Read More') {
                                    if ($settings['show_readmore'] == 1 && $settings['readmore_inout'] == 'out') {
                                        $this->generate_readmore($settings, $classItemReadMore);
                                    }
                                }

                                $counter_item ++;
                            }
                        } else {

                            // ************************************************
                            if ($settings['show_date'] != 0) {
                                $this->generate_date($settings, $classItemDate, $id_page);
                            }
                            // ************************************************

                            if ($settings['show_title'] == 1 && $settings['title_inout'] == 'out') {
                                $this->generate_title($settings);
                            }
                            // ***********************************************************************************
                            if ($settings['show_metadata'] == 1 && $settings['metadata_inout'] == 'out') {
                                $this->generate_meta($settings, $id_page);
                            }
                            // ***********************************************************************************
                            if ($settings['show_author'] == 1 && $settings['author_inout'] == 'out') {
                                $this->generate_author($settings);
                            }

                            // **************************************************
                            if ($settings['show_acfitems'] == '1') {
                                $this->generate_acfitems($settings, $id_page);
                            }

                            // ora di default il content è solo questo, gli altri sono deprecati
                            if ($settings['textcontent_position'] == 'bottom_in') {
                                $this->generate_content($settings, $id_page);
                            }
                            if ($settings['show_readmore'] == 1 && $settings['readmore_inout'] == 'out') {
                                $this->generate_readmore($settings, $classItemReadMore);
                            }
                        }
                        ?>

                                </div>

                                    <?php
                                    // deprecated: last version
                                    if ($settings['textcontent_position'] == 'bottom_out') {
                                        echo '<div style="clear: both"></div>';
                                        $this->generate_content($settings, $id_page);
                                    }


                                    // XXXXXXXXXXXX
                                } else { // end if templatemode_enable NO .. qui comincia l'utilizzo dei template   
                                    //
                                    // global $global_ID;
                                    // global $global_TYPE;
                                    // global $in_the_loop;
                                    // global $global_is;
                                    //global $global_is;
                                    //echo 'valutere il dato: '.$global_is;
                                    // da capire! ...questa situazione crea problemi in certi casi, come quando è in un'archivio oppure nell'ajax-open
                                    /* if( $global_is == 'singular' ) */

                                    //global $post;
                                    //echo $post->ID;
                                    //$dce_data = DCE_Helper::dce_dynamic_data(get_the_ID());
                                    $global_ID = get_the_ID(); //$dce_data['id'];
                                    //echo $global_ID;
                                    //
                                    if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                                        $inlinecss = 'inlinecss="true"';
                                    } else {
                                        $inlinecss = '';
                                    }
                                    //
                                    // template DCE
                                    if ($settings['templatemode_enable'] && $settings['templatemode_enable_2'] == '') {
                                        //
                                        // qui chiamo il template Elementor per generare il contenuti dei Blocks.
                                        echo do_shortcode('[dce-elementor-template id="' . $settings['templatemode_template'] . '" post_id="'.$id_page.'" ' . $inlinecss . ']');
                                        //
                                    } else if ($settings['native_templatemode_enable']) {

                                        $type_of_posts = get_post_type(get_the_ID());
                                        $cptaxonomy = get_post_taxonomies(get_the_ID());

                                        // var_dump($cptaxonomy);
                                        $taxonomy_objects = get_object_taxonomies('post', 'objects');
                                        //print_r( $taxonomy_objects);


                                        $options = get_option(DCE_OPTIONS);

                                        $dce_elementor_templates = 'dyncontel_field_archive' . $type_of_posts;
                                        // *********
                                        $dce_default_template = $options[$dce_elementor_templates];

                                        if (isset($cptaxonomy) && count($cptaxonomy) > 0) {
                                            //var_dump(count($cptaxonomy));
                                            //foreach ($cptaxonomy as $chiave) {
                                            $chiave = $cptaxonomy[0];
                                            // 3 - Taxonomy
                                            if (isset($options['dyncontel_field_archive_taxonomy_' . $chiave])) {
                                                //
                                                $dce_default_template_taxo = $options['dyncontel_field_archive_taxonomy_' . $chiave];

                                                if (!empty($dce_default_template_taxo) && $dce_default_template_taxo > 0) {
                                                    $dce_elementor_templates = 'dyncontel_field_archive_taxonomy_' . $chiave;
                                                }
                                                //}


                                                /* if (!empty($dce_default_template_term)) {
                                                  $dce_default_template = $dce_default_template_term;
                                                  } */
                                            }
                                            // *********
                                            $dce_default_template = $options[$dce_elementor_templates];

                                            //foreach ($cptaxonomy as $chiave) {
                                            // 4 - Termine
                                            $cptaxonomyterm = get_the_terms(get_the_ID(), $cptaxonomy[0]/* $chiave */);
                                            //var_dump($cptaxonomyterm);
                                            if (isset($cptaxonomyterm) && $cptaxonomyterm) {
                                                foreach ($cptaxonomyterm as $cpterm) {
                                                    //echo $chiave;
                                                    //echo $cpterm->term_id;
                                                    $termine_id = $cpterm->term_id;

                                                    $dce_default_template_term = get_term_meta($termine_id, 'dynamic_content_block', true);
                                                    if (!empty($dce_default_template_term)) {

                                                        // *********
                                                        $dce_default_template = $dce_default_template_term;
                                                    }
                                                }
                                            }
                                            //}
                                        }
                                        // echo $dce_elementor_templates;
                                        // echo $dce_default_template;
                                        //
                                        echo do_shortcode('[dce-elementor-template id="' . $dce_default_template . '" post_id="'.$id_page.'" ' . $inlinecss . ']');
                                    } else if ($settings['templatemode_enable'] && $settings['templatemode_enable_2']) {

                                        if ($counter % 2 == 0) {

                                            // se pari
                                            echo do_shortcode('[dce-elementor-template id="' . $settings['templatemode_template'] . '" post_id="'.$id_page.'" ' . $inlinecss . ']');
                                        } else {
                                            // se dispari

                                            echo do_shortcode('[dce-elementor-template id="' . $settings['templatemode_template_2'] . '" post_id="'.$id_page.'" ' . $inlinecss . ']');
                                        }
                                    }
                                } // end Template mode..
                                // questa opzione distende il link su tutto il blocco e ignora i singoli link
                                if ($settings['templatemode_linkable']) {
                                    echo '<a style="cursor: pointer; position: absolute; left: 0; top: 0; right: 0; bottom: 0; z-index: 10;" href="' . get_the_permalink() . '">';
                                    echo '</a>';
                                }
                                ?>
                        </div><!-- end wrapper -->

                    </div><!-- end item -->

                            <?php
                            $counter++;
                        endwhile;

                        $global_ID = $original_global_ID;
                        $in_the_loop = false;
                        ?>

            </div><!-- end grid -->
                        <?php
                        //end contenitore della griglia
                        // La paginazione numerica ........
                        if ($settings['pagination_enable']) {
                            //if ($settings['pagination_noscript']) echo '<noscript>';
                            \DynamicContentForElementor\DCE_Helper::numeric_query_pagination($p_query->max_num_pages, $settings);
                            //if ($settings['pagination_noscript']) echo '</noscript>';
                            // \DynamicContentForElementor\DCE_Helper::dce_numeric_posts_nav();     
                        }

                        // La paginazione infinitescroll ...
                        // Se infiniteScroll è abilitato e anche se i post generati sono maggiori dei post visualizzati
                        if (($settings['infiniteScroll_enable'] &&
                                $postlength >= $settings['num_posts'] &&
                                $settings['num_posts'] >= 0) ||
                                (\Elementor\Plugin::$instance->editor->is_edit_mode())
                        ) {
                            $preview_mode = '';

                            if (\Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['infiniteScroll_show_preview']) {
                                $preview_mode = ' visible';
                            }

                            if ($settings['infiniteScroll_enable_status']) {
                                ?>
                    <nav class="infiniteScroll">
                        <div class="page-load-status<?php echo $preview_mode; ?>">

                                <?php
                                if ($settings['infiniteScroll_loading_type'] == 'text') {
                                    ?>
                                <div class="infinite-scroll-request status-text"><?php echo __($settings['infiniteScroll_label_loading'], 'dynamic-content-for-elementor' . '_texts'); ?></div>
                                    <?php
                                } else if ($settings['infiniteScroll_loading_type'] == 'ellips') {
                                    ?>
                                <div class="loader-ellips infinite-scroll-request">
                                    <span class="loader-ellips__dot"></span>
                                    <span class="loader-ellips__dot"></span>
                                    <span class="loader-ellips__dot"></span>
                                    <span class="loader-ellips__dot"></span>
                                </div>
                                    <?php
                                }
                                ?>
                            <div class="infinite-scroll-last status-text"><?php echo __($settings['infiniteScroll_label_last'], 'dynamic-content-for-elementor' . '_texts'); ?></div>
                            <div class="infinite-scroll-error status-text"><?php echo __($settings['infiniteScroll_label_error'], 'dynamic-content-for-elementor' . '_texts'); ?></div>

                            <div class="pagination" role="navigation">
                                <a class="pagination__next" href="<?php echo \DynamicContentForElementor\DCE_Helper::get_next_pagination(); ?>"><?php //echo __('Next', 'dynamic-content-for-elementor');  ?></a>
                            </div>
                        </div>


                    </nav>
                                <?php
                            }

                            // Infinite scroll Button version ...
                            if ($settings['infiniteScroll_trigger'] == 'button') {
                                ?>
                    <div class="infiniteScroll">
                        <button class="view-more-button"><?php echo __($settings['infiniteScroll_label_button'], 'dynamic-content-for-elementor' . '_texts'); ?></button>
                    </div>
                                <?php
                            }
                        }// end infinitescroll eneble
                        // Reset the post data to prevent conflicts with WP globals
                        // Ripristina Query & Post Data originali
                        wp_reset_query();
                        wp_reset_postdata();
                        ?>

                <?php
            // End post check
            endif;
            // *********************************************************************** end Query POST
            // 
            if ($settings['posts_style'] == 'swiper' && $sonoPronto) {

                // in precedenza prima di aprire la wp_query ho creato il contenitore per lo swiper
                echo '</div> <!-- swiper-wrapper -->';


                // NOTA: la paginazione e la navigazione per lo swiper è fuori dal suo contenitore per poter spostare gli elementi a mio piacimento, visto che il contenitore è in overflow: hidden, e se fossero all'interno (come di default) si nasconderebbero fuori dall'area.

                if ($settings['usePagination'] && $sonoPronto) {
                    // Add Pagination
                    echo '<div class="swiper-container-' . $settings['direction_slider'] . '"><div class="swiper-pagination pagination-' . $this->get_id() . '"></div></div>';
                }
                if ($settings['useNavigation']) {
                    // Add Arrows

                    echo '<div class="swiper-button-prev prev-' . $this->get_id() . '"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                width="85.039px" height="85.039px" viewBox="378.426 255.12 85.039 85.039" enable-background="new 378.426 255.12 85.039 85.039"
                xml:space="preserve">
                <line fill="none" stroke="#000000" stroke-width="1.3845" stroke-dasharray="0,0" stroke-miterlimit="10" x1="382.456" y1="298.077" x2="458.375" y2="298.077"/>
                <polyline fill="none" stroke="#000000" stroke-width="1.3845" stroke-dasharray="0,0" stroke-miterlimit="10" points="416.287,331.909 382.456,298.077 
                416.287,264.245 "/>
                </svg></div>';
                    echo '<div class="swiper-button-next next-' . $this->get_id() . '"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                width="85.039px" height="85.039px" viewBox="378.426 255.12 85.039 85.039" enable-background="new 378.426 255.12 85.039 85.039"
                xml:space="preserve">
                <line fill="none" stroke="#000000" stroke-width="1.3845" stroke-miterlimit="10" x1="458.375" y1="298.077" x2="382.456" y2="298.077"/>
                <polyline fill="none" stroke="#000000" stroke-width="1.3845" stroke-miterlimit="10" points="424.543,264.245 458.375,298.077 
                424.543,331.909 "/>
                </svg></div>';
                }
            }
            // end Swiper chiusura
            // end Render .....
        }

        public function get_current_page() {
            if ('' === $this->get_settings('pagination_enable') && '' === $this->get_settings('infiniteScroll_enable')) {
                return 1;
            }

            return max(1, get_query_var('paged'), get_query_var('page'));
        }

        private function createSlug($str, $delimiter = '-') {

            $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
            return $slug;
        }

        private function generate_author($settings) {
            $author = [];

            $avatar_args['size'] = $settings['author_avatar_size'] || 150;

            $user_id = get_the_author_meta('ID');
            $author['avatar'] = get_avatar_url($user_id, $avatar_args);

            $author['display_name'] = get_the_author_meta('display_name');
            $author['website'] = get_the_author_meta('user_url');
            $author['bio'] = get_the_author_meta('description');
            $author['posts_url'] = get_author_posts_url($user_id);

            echo '<div class="dce_author-wrap">';
            if ($settings['enable_author_image'])
                echo '<div class="dce_author-avatar"><a href="' . $author['posts_url'] . '">' . '<img src="' . $author['avatar'] . '" alt="' . $author['display_name'] . '" />' . '</a></div>';

            echo '  <div class="dce_author-text">';
            echo '      <div class="dce_author-name">' . $author['display_name'] . '</div>';
            //echo $author['website'];
            if ($settings['enable_author_bio'])
                echo '      <div class="dce_author-bio">' . $author['bio'] . '</div>';
            echo '  </div>';

            echo '</div>';
            //echo $author['posts_url'];
        }

        private function generate_title($settings) {
            echo sprintf('<%1$s class="dce-post-title">', $settings['html_tag']);
            ?>
        <?php if ($settings['title_link']) { ?><a href="<?php the_permalink(); ?>"><?php } ?>
        <?php get_the_title() ? the_title() : the_ID(); ?>
        <?php if ($settings['title_link']) { ?></a><?php } ?>
        <?php
        echo sprintf('</%s>', $settings['html_tag']);
    }

    private function generate_readmore($settings, $clss) {
        echo '<div class="dce_readmore_wrapper' . $clss . '"><a href="' . get_the_permalink() . '" class="dce_readmore_btn">' . __($settings['readmore_text'], 'dynamic-content-for-elementor' . '_texts') . '</a></div>';
    }

    private function generate_meta($settings, $id_page = null) {
        if (!$id_page) {
            $id_page = get_the_ID();
        }
        $term_list = [];
        $divisore = '';

        $tag_metadata = 'span';
        if ($settings['metadata_block_enable']) {
            $tag_metadata = 'div';
        }

        //
        $taxonomyAuto = get_post_taxonomies($id_page);


        //var_dump($taxonomyAuto);
        //$term_list = get_the_terms( get_the_ID(), $settings['taxonomy_metadata'] );
        //var_dump($term_list);
        //echo get_the_ID().' '.$settings['taxonomy_metadata'].' - '.count($term_list) ;


        echo '<div class="dce_metadata-wrap">';
        foreach ($taxonomyAuto as $tax) {

            if (isset($settings['taxonomy_metadata_filter']) && !empty($settings['taxonomy_metadata_filter'])) {
                if (!in_array($tax, $settings['taxonomy_metadata_filter'])) {
                    continue;
                }
            }


            // 

            $term_list = DCE_Helper::get_the_terms_ordered($id_page, $tax);
            //var_dump($term_list);
            if ($term_list && is_array($term_list) && count($term_list) > 0) {



                // l'icona
                if ($settings['metadata_icon_enable']) {
                    $icon_metadata = '';
                    if (is_taxonomy_hierarchical($tax)) {
                        $icon_metadata = '<i class="fa fa-folder-open" aria-hidden="true"></i> ';
                    } else {
                        $icon_metadata = '<i class="fa fa-tags" aria-hidden="true"></i> ';
                    }
                    echo $icon_metadata;
                }

                // ------- Ciclo i termini
                $cont = 1;
                foreach ($term_list as $term) {
                    //var_dump($term);
                    $termparent = true;
                    if ($settings['only_parent_metadata']) {
                        $termparent = $term->parent;
                    }



                    // ----------------
                    if (!$termparent || !$settings['only_parent_metadata']) {
                        echo '<' . $tag_metadata . '>';
                        $term_url = trailingslashit(get_term_link($term));
                        $linkOpen = '';
                        $linkClose = '';
                        if ($settings['metadata_link']) {
                            $linkOpen = '<a href="' . $term_url . '">';
                            $linkClose = '</a>';
                        }
                        if ($cont > 1 && !$settings['metadata_block_enable']) {
                            $divisore = '<span class="dce_metadata-separator">' . $settings['separator_metadata'] . '</span>';
                        } else {
                            $divisore = '';
                        }
                        echo $divisore . '<span class="dce_metadata" data-dce-order="' . $term->term_order . '">' . $linkOpen . $term->name . $linkClose . '</span>';
                        $cont++;

                        echo '</' . $tag_metadata . '>';
                    }
                } // fine ciclo
            }
        }
        echo '</div>';
    }

    private function generate_content($settings, $id_page = null) {
        if (!$id_page) {
            $id_page = get_the_ID();
        }
        //if ($settings['show_textcontent'] != 0) {
        ?>
        <div class="dce_textcontent">
        <?php
        // deprecated: last version
        if ($settings['show_metadata'] == 1 && $settings['metadata_inout'] == 'in') {
            $this->generate_meta($settings, $id_page);
        }

        // deprecated: last version
        if ($settings['show_title'] == 1 && $settings['title_inout'] == 'in') {
            $this->generate_title($settings);
        }
        ?>
        <?php
        //
        if ($settings['show_textcontent'] == 1) {
            if ($settings['textcontent_limit'] == '') {
                echo wpautop(get_the_content());
            } else {
                echo $this->limit_content($settings['textcontent_limit']);
            }
            //addslashes(substr(get_the_content(), 0, $settings['textcontent_limit'])) . ' ...'; //get_the_content(); //$this->limit_content( $settings['textcontent_limit'] ); //
        }
        if ($settings['show_textcontent'] == 2) {
            $mypost = get_post($id_page);
            echo $mypost->post_excerpt; //$this->limit_excerpt( $settings['textcontent_limit'] ); //
        }

        // deprecated: last version
        if ($settings['show_author'] == 1 && $settings['author_inout'] == 'in') {
            $this->generate_author($settings);
        }
        // deprecated: last version
        if ($settings['show_readmore'] == 1 && $settings['readmore_inout'] == 'in') {
            $this->generate_readmore($settings, $classItemReadMore);
        }
        ?>
        </div>
        <?php
        //}
    }

    private function generate_date($settings, $clss = null, $id_page = null) {

        $date = '';
        $date2 = '';
        $date3 = '';

        if ($settings['date_type'])
            $date_type = $settings['date_type'];
        else
            $date_type = 'publish';

        switch ($date_type) {
            case 'modified' :
                $date = '<span class="d1">' . get_the_modified_date($settings['date_format'], $id_page) . '</span>';
                if ($settings['date_format_2'] != '')
                    $date2 = '<span> class="d2"' . get_the_modified_date($settings['date_format_2'], $id_page) . '</span>';
                if ($settings['date_format_3'] != '')
                    $date3 = '<span> class="d3"' . get_the_modified_date($settings['date_format_3'], $id_page) . '</span>';
                break;

            case 'publish' :
            default:
                $date = '<span class="d1">' . get_the_date($settings['date_format'], $id_page) . '</span>';
                if ($settings['date_format_2'] != '')
                    $date2 = '<span class="d2">' . get_the_date($settings['date_format_2'], $id_page) . '</span>';
                if ($settings['date_format_3'] != '')
                    $date3 = '<span class="d3">' . get_the_date($settings['date_format_3'], $id_page) . '</span>';
                break;
        }

        echo '<div class="dce-post-date' . $clss . '">' . $date . $date2 . $date3 . '</div>';
    }

    private function generate_acfitems($settings, $id_page = null) {
        $counter_item = 1;
        $ACFitems = $settings['acf_items'];
        //
        if (!empty($ACFitems)) {
            foreach ($ACFitems as $acfitem) :


                $spazio = "";
                $tag_item = 'span';
                $tag_subitem = $acfitem['html_tag_item'];

                $tag_subitem_start = '';
                $tag_subitem_end = '';

                $a_start = '';
                $a_end = '';
                

                $acf_i = $acfitem['acf_field_item'];
                $acf_type_i = $acfitem['acf_field_type'];
                /* if( $counter_item > 1 ) */ $spazio = " ";
                if ($acfitem['block_enable']) {
                    $tag_item = 'div';
                }

                switch ($acfitem['link_to']) {
                    case 'custom' :
                        if (!empty($acfitem['link']['url'])) {
                            $link = esc_url($acfitem['link']['url']);
                        } else {
                            $link = false;
                        }
                        break;

                    case 'post' :
                        $link = esc_url(get_the_permalink($id_page));
                        break;
                    case 'parent' :
                        $id_page_parent = wp_get_post_parent_id($id_page);
                        $link = esc_url(get_the_permalink($id_page_parent));
                        break;
                    case 'home' :
                        $link = esc_url(get_home_url());
                        break;

                    case 'none' :

                    default:
                        $link = false;
                        break;
                }
                $target = $acfitem['link']['is_external'] ? ' target="_blank"' : '';

                if ($link){
                    $a_start = '<a href="' . $link . '"' . $target . '>';
                    $a_end = '</a>';
                }

                if ($tag_subitem != '') {
                    $tag_subitem_start = '<' . $tag_subitem . '>'.$a_start;
                    $tag_subitem_end = $a_end.'</' . $tag_subitem . '>';
                }


                if ($acf_i == 'title') {
                    echo '<' . $tag_item . ' class="acf-acfpost-item elementor-repeater-item-' . $acfitem['_id'] . '">' . $tag_subitem_start . get_the_title() . $tag_subitem_end . '</' . $tag_item . '>' . $spazio;
                } else if ($acf_i == 'content') {
                    $contentacf = get_post($id_page);
                    echo '<' . $tag_item . ' class="acf-acfpost-item elementor-repeater-item-' . $acfitem['_id'] . '">' . $tag_subitem_start . wpautop(get_the_content()) . $tag_subitem_end . '</' . $tag_item . '>' . $spazio;
                } else if ($acf_i == 'taxonomy') {
                    //var_dump($acfitem);
                    $divisore_i = '';
                    $term_list_item = get_the_terms($id_page, $acfitem['taxonomy_metadata']);
                    echo '<' . $tag_item . ' class="acf-acfpost-item elementor-repeater-item-' . $acfitem['_id'] . '">' . $tag_subitem_start;
                    $cont_i = 1;
                    if (!empty($term_list_item)) {
                        foreach ($term_list_item as $term) {
                            if ($cont_i > 1)
                                $divisore_i = $settings['separator_metadata'];
                            echo '<a href="' . get_term_link($term->term_id, $acfitem['taxonomy_metadata']) . '">' . '<span>' . $divisore_i . $term->name . '</span></a>'; //.' '.print_r($term)
                            $cont_i++;
                        }
                    }
                    echo $tag_subitem_end . '</' . $tag_item . '>' . $spazio;
                } else if ($acf_i == 'date') {
                    echo '<' . $tag_item . ' class="acf-acfpost-item elementor-repeater-item-' . $acfitem['_id'] . '">' . $tag_subitem_start . get_the_date($acfitem['acf_date_format']) . $tag_subitem_end . '</' . $tag_item . '>' . $spazio;
                } else {
                    $acf_i_val = get_field($acf_i);
                    //$acf_i_val = get_post_meta( get_the_ID(), $acf_i );
                    //    
                    if ($acf_type_i == 'image') {
                        //echo $acf_i_val;
                        //echo $typeField.': '.$imageField;
                        if (is_string($acf_i_val)) {
                            //echo 'url: '.$acf_i_val;
                            //$typeField = 'image_url';
                            if (is_numeric($marker_img)) {
                                $imageSrc = wp_get_attachment_image_src($marker_img, 'full');
                                $imageSrcUrl = $imageSrc[0];
                                $immagine_acf = $imageSrcUrl;
                            } else {
                                $immagine_acf = $immagine_acf;
                            }
                        } else if (is_numeric($acf_i_val)) {
                            //echo 'id: '.$acf_i_val;
                            //$typeField = 'image';
                            $imageSrc = wp_get_attachment_image_src($immagine_acf, 'full');
                            $imageSrcUrl = $imageSrc[0];
                            $immagine_acf = $imageSrcUrl;
                        } else if (is_array($acf_i_val)) {
                            //echo 'array: '.$acf_i_val;
                            //$typeField = 'image_array';
                            //var_dump($acf_i_val['ID']);
                            $imageSrc = wp_get_attachment_image_src($acf_i_val['ID'], 'full');
                            $imageSrcUrl = $imageSrc[0];
                            $immagine_acf = $imageSrcUrl;
                        }
                        //echo $immagine_acf;
                        if (isset($immagine_acf))
                            echo '<img src="' . $immagine_acf . '" />';
                    } else if ($acf_type_i == 'date') {
                        //echo $acf_i_val.'aa';
                        //$settings['acf_date_format']
                        $dataDate = get_field_object($acf_i);
                        //echo $dataDate['return_format'];
                        $d = \DateTime::createFromFormat($dataDate['return_format'], $acf_i_val);
                        //                                                       
                        echo '<' . $tag_item . ' class="acf-acfpost-item elementor-repeater-item-' . $acfitem['_id'] . '">' . date_i18n($acfitem['acf_date_format'], $d->format('U')) . '</' . $tag_item . '>' . $spazio;
                    } else {
                        echo '<' . $tag_item . ' class="acf-acfpost-item elementor-repeater-item-' . $acfitem['_id'] . '">' . $tag_subitem_start . $acf_i_val . $tag_subitem_end . '</' . $tag_item . '>' . $spazio;
                    }
                }
                
                $counter_item++;
            endforeach;
        }
    }

    protected function limit_content($limit) {
        /* $content = explode(' ', get_the_content(), $limit);
          if (count($content) >= $limit) {
          array_pop($content);
          $content = implode(" ", $content) . '...';
          } else {
          $content = implode(" ", $content);
          }
          $content = preg_replace('/[.+]/', '', $content);
          $content = apply_filters('the_content', $content);
          $content = str_replace(']]>', ']]&gt;', $content); */

        //
        $post = get_post();
        $content = $post->post_content; //do_shortcode($post['post_content']); //$content_post->post_content; //
        //

        $content = substr(wp_strip_all_tags($content), 0, $limit) . ' ...'; //


        return $content;
    }

    protected function limit_excerpt($limit) {
        $excerpt = explode(' ', get_the_excerpt(), $limit);
        if (count($excerpt) >= $limit) {
            array_pop($excerpt);
            $excerpt = implode(" ", $excerpt) . '...';
        } else {
            $excerpt = implode(" ", $excerpt);
        }
        $excerpt = preg_replace('`[[^]]*]`', '', $excerpt);
        return $excerpt;
    }

    protected function generate_image($settings, $clss = null) {


        //echo $settings['image-size'];
        $image_url = Group_Control_Image_Size::get_attachment_image_src(get_post_thumbnail_id(), 'size', $settings);
        //echo $image_url;
        //the_post_thumbnail($settings['image-size']);
        ?>
        <div class="dce-acfposts_image<?php echo $clss; ?>">
        <?php if ($settings['image_link']) echo '<a href="' . get_the_permalink() . '">' ?>
        <?php
        // in caso di background image
        if ($settings['use_bgimage']) {
            ?>
                <figure class="acfposts-image" style="background: url(<?php echo $image_url; ?>) no-repeat center; background-size: cover; display: block;"></figure>
            <?php
        } else {
            // in caso di img ...
            ?>
                <img src="<?php echo $image_url; ?>" title="<?php echo get_the_title(); ?>" class="acfposts-image" />
        <?php } ?>

        <?php if ($settings['use_overlay']) { ?>
                <div class="dce-overlay"></div>
        <?php } ?>
        <?php if ($settings['use_overlay_hover']) { ?>
                <div class="dce-overlay_hover"></div>
        <?php } ?>
        <?php if ($settings['image_link']) echo '</a>' ?>
        </div>

        <?php
    }

    // ************************************** ADVANCED CUSTOM FIELDS

    /**
     * Get ACF - field e group
     */
    protected function get_acf_field($group = false, $core = false) {

        //$settings = $this->get_settings_for_display();
        //$gruppo = $settings['acf_group_list']; //$settings->acf_group_list;
        $acfList = [];

        //$acfContents = [];
        $acfList[0] = 'Select the Field';
        //
        $tipo = 'acf-field';

        if ($group) {
            $acfList['core']['options']['title'] = __('Title', 'dynamic-content-for-elementor');
            $acfList['core']['label'] = 'Core';

            $acfList['core']['options']['content'] = __('Content', 'dynamic-content-for-elementor');
            $acfList['core']['label'] = 'Core';

            $acfList['core']['options']['taxonomy'] = __('Taxonomy MetaData', 'dynamic-content-for-elementor');
            $acfList['core']['label'] = 'Core';

            $acfList['core']['options']['date'] = __('Date', 'dynamic-content-for-elementor');
            $acfList['core']['label'] = 'Core';
        } else {
            $acfList['title'] = __('Title', 'dynamic-content-for-elementor');
            $acfList['content'] = __('Content', 'dynamic-content-for-elementor');
            $acfList['taxonomy'] = __('Taxonomy MetaData', 'dynamic-content-for-elementor');
            $acfList['date'] = __('Date', 'dynamic-content-for-elementor');
        }

        $get_templates = get_posts(array('post_type' => 'acf-field', 'numberposts' => -1, 'post_status' => 'publish'));
        if (!empty($get_templates)) {

            foreach ($get_templates as $template) {

                $gruppoAppartenenza = get_the_title($template->post_parent);
                $arrayField = maybe_unserialize($template->post_content);

                //$acfList = $arrayField['type'];
                //$acfList[ $template->ID ] =  $arrayField.' - '.$template->post_parent .' - '. $template->ID .' - '. $template->post_title .' - '.$template->post_name.' - '.$template->post_excerpt; //post_name, post_title 
                //print_r($template);
                //sort($template);
                //print_r($template);
                //var_dump($arrayField['type']);
                if ($arrayField['type'] == 'text' ||
                        $arrayField['type'] == 'textarea' ||
                        $arrayField['type'] == 'select' ||
                        $arrayField['type'] == 'number' ||
                        $arrayField['type'] == 'date_time_picker' ||
                        $arrayField['type'] == 'date_picker' ||
                        $arrayField['type'] == 'oembed' ||
                        $arrayField['type'] == 'file' ||
                        $arrayField['type'] == 'url' ||
                        $arrayField['type'] == 'image' ||
                        $arrayField['type'] == 'wysiwyg') {
                    if ($group) {
                        $acfList[$gruppoAppartenenza]['options'][$template->post_excerpt] = $template->post_title . '[' . $template->post_excerpt . '] (' . $arrayField['type'] . ')';
                        $acfList[$gruppoAppartenenza]['label'] = $gruppoAppartenenza;
                    } else {
                        $acfList[$template->post_excerpt] = $template->post_title . ' [' . $template->post_excerpt . '] (' . $arrayField['type'] . ')'; //.$template->post_content; //post_name,
                    }
                }
            }
            //$acfList = [ '' => __('Title', 'dynamic-content-for-elementor') . '(CORE)'] + ['content' => __('Content', 'dynamic-content-for-elementor') . '(CORE)'] + ['taxonomy' => __('Taxonomy MetaData', 'dynamic-content-for-elementor') . '(CORE)'] + ['date' => __('Date', 'dynamic-content-for-elementor') . '(CORE)'];
        }
        return $acfList;
    }

    public function get_terms_query($settings = null, $id_page = null) {
        $terms_query = 'all';

        if (!$settings) {
            $settings = $this->get_settings_for_display();
        }

        if (!$id_page) {
            $id_page = get_the_ID();
        }

        if ($settings['taxonomy']) {

            // per la retrocompatibilità con il vecchio category
            if ($settings['category'] != '') {
                $terms_query = explode(',', $settings['category']);
            }

            if ($settings['terms_current_post']) {
                // Da implementare oR & AND tems ...
                if (is_single()) {
                    $terms_list = wp_get_post_terms($id_page, $settings['taxonomy'], array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all', 'hide_empty' => true));
                    //var_dump($terms_list);
                    if (!empty($terms_list)) {
                        $terms_query = array();
                        foreach ($terms_list as $akey => $aterm) {
                            if (!in_array($aterm->term_id, $terms_query)) {
                                $terms_query[] = $aterm->term_id;
                            }
                        }
                    }
                }

                if (is_archive()) {
                    if (is_tax()) {
                        $queried_object = get_queried_object();
                        $terms_query = array($queried_object->term_id);
                    }
                }
            }

            if (isset($settings['terms_' . $settings['taxonomy']]) && !empty($settings['terms_' . $settings['taxonomy']])) {
                $terms_query = $settings['terms_' . $settings['taxonomy']];
                // add current post terms id
                $dce_key = array_search('dce_current_post_terms', $terms_query);
                if ($dce_key !== false) {
                    //var_dump($dce_key);
                    unset($terms_query[$dce_key]);
                    $terms_list = wp_get_post_terms($id_page, $settings['taxonomy'], array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all', 'hide_empty' => true));
                    if (!empty($terms_list)) {
                        $terms_query = array();
                        foreach ($terms_list as $akey => $aterm) {
                            if (!in_array($aterm->term_id, $terms_query)) {
                                $terms_query[] = $aterm->term_id;
                            }
                        }
                    }
                }
            }

            
        }
        //var_dump($terms_query);
        return $terms_query;
    }

}
