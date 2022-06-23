<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use DynamicContentForElementor\DCE_Helper;
use DynamicContentForElementor\DCE_Tokens;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Parent Child Menu
 *
 * Elementor widget for Linkness Elements
 *
 * @since 1.0.0
 */
class DCE_Widget_Views extends DCE_Widget_Prototype {

    public $cpts;
    public $taxonomies;
    public $taxonomies_terms;
    public $wp_obj_type = ['post', 'user', 'term'];
    public $obj__in = [];
    public $the_query;

    public function get_name() {
        return 'dce-views';
    }

    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('Views', 'dynamic-content-for-elementor');
    }

    public function get_keywords() {
        return ['list', 'archive', 'search'];
    }

    public function get_description() {
        return __('Create a custom list from query results', 'dynamic-content-for-elementor');
    }

    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/views/';
    }

    public function get_icon() {
        return 'icon-dyn-views';
    }

    public function get_style_depends() {
        return ['datatables'];
    }

    public function get_script_depends() {
        return ['infinitescroll', 'datatables'];
    }

    protected function _register_controls() {

        $cpts = $templates = $taxonomies = $taxonomies_terms = $post_fields = $post_status = array();

        $cpts = DCE_Helper::get_post_types(false);
        $taxonomies = DCE_Helper::get_taxonomies();
        //$templates = DCE_Helper::get_all_template();
        $post_status = get_post_stati(); //DCE_Helper::get_post_statuses_all(); // get_post_statuses();
        $roles = DCE_Helper::get_roles(false);
        $this->taxonomies = $taxonomies;
        $sql_operators = DCE_Helper::get_sql_operators();

        //* OBJECT *//
        $this->start_controls_section(
                'section_object', [
            'label' => __('Object', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'dce_views_object', [
            'label' => __('Type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'post' => [
                    'title' => __('Posts', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-files-o',
                ],
                'user' => [
                    'title' => __('Users', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-users',
                ],
                'term' => [
                    'title' => __('Terms', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-tags',
                ]
            ],
            'default' => 'post',
            'toggle' => false,
                ]
        );
        $this->end_controls_section();

        //* SELECT *//
        $this->start_controls_section(
                'section_select', [
            'label' => __('Select', 'dynamic-content-for-elementor'),
                ]
        );

        /*
          $this->add_control(
          'dce_views_select_object', [
          'label' => __('Content object', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::CHOOSE,
          'options' => [
          'fields' => [
          'title' => __('Post', 'dynamic-content-for-elementor'),
          'icon' => 'fa fa-list',
          ],
          'text' => [
          'title' => __('User', 'dynamic-content-for-elementor'),
          'icon' => 'fa fa-align-left',
          ],
          'template' => [
          'title' => __('Term', 'dynamic-content-for-elementor'),
          'icon' => 'fa fa-th-large',
          ]
          ],
          'toggle' => false,
          'default' => 'post',
          ]
          );
         */

        $this->add_control(
                'dce_views_select_type', [
            'label' => __('Content type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'fields' => [
                    'title' => __('Fields', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-list',
                ],
                'text' => [
                    'title' => __('Text', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-left',
                ],
                'template' => [
                    'title' => __('Template', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-th-large',
                ]
            ],
            'toggle' => false,
            'default' => 'text',
                ]
        );
        /* $this->add_control(
          'dce_views_select_template', [
          'label' => __('Render Template', 'dynamic-content-for-elementor'),
          'label_block' => true,
          'type' => Controls_Manager::SELECT2,
          'options' => $templates,
          'condition' => [
          'dce_views_select_type' => 'template',
          ],
          ]
          ); */
        $this->add_control(
                'dce_views_select_template',
                [
                    'label' => __('Render Template', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Template Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'object_type' => 'elementor_library',
                    'condition' => [
                        'dce_views_select_type' => 'template',
                    ],
                ]
        );

        $this->add_control(
                'dce_views_select_text', [
            'label' => __('Post preview html', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CODE,
            'default' => '[post:thumb]<h4>[post:title]</h4><p>[post:excerpt]</p><a class="btn btn-primary" href="[post:permalink]">READ MORE</a>',
            'description' => __("Insert here some content showed if the widget is not visible", 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_select_type' => 'text',
            ],
                ]
        );

        $repeater_fields = new \Elementor\Repeater();
        /* $repeater_fields->add_control(
          'dce_views_select_field', [
          'label' => __('Field', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SELECT,
          //'options' => $post_fields,
          'groups' => $post_fields,
          'label_block' => true,
          ]
          ); */
        $repeater_fields->add_control(
                'dce_views_select_field',
                [
                    'label' => __('Field', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Meta key or Field Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'fields',
                    'object_type' => 'any',
                ]
        );
        /* $repeater_fields->add_control(
          'dce_views_select_field_is_sub', [
          'label' => __('Has Sub Fields', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SWITCHER,
          'description' => __('For data stored Serialized or in Json format', 'dynamic-content-for-elementor'),
          ]
          );
          $repeater_fields->add_control(
          'dce_views_select_field_sub', [
          'label' => __('Sub Field', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::TEXT,
          'default' => '[field]',
          'description' => __('Use Token notation to access to sub field value. Example: [field:sub_field], [field:sub_array:sub_sub_field], [field:0:sub_field]', 'dynamic-content-for-elementor'),
          'condition' => [
          'dce_views_where_field_is_sub!' => '',
          ],
          ]
          ); */

        $repeater_fields->add_control(
                'dce_views_select_label', [
            'label' => __('Label', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
                ]
        );
        $repeater_fields->add_control(
                'dce_views_select_label_inline', [
            'label' => __('Inline label', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'dce_views_select_label!' => '',
            ]
                ]
        );
        $repeater_fields->add_control(
                'dce_views_select_render', [
            'label' => __('Render', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'auto' => [
                    'title' => __('Auto', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-rocket',
                ],
                'rewrite' => [
                    'title' => __('Text', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-edit',
                ],
            ],
            'toggle' => false,
            'default' => 'auto',
                ]
        );
        $repeater_fields->add_control(
                'dce_views_select_tag', [
            'label' => __('HTML Tag', 'dynamic-content-for-elementor'),
            'description' => __('Wrap the output of this field in this HTML Tag.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '' => __('None', 'dynamic-content-for-elementor'),
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
                'div' => 'div',
                'span' => 'span',
                'p' => 'p',
            ],
            'condition' => [
                'dce_views_select_render' => 'auto',
            ]
                ]
        );
        $repeater_fields->add_control(
                'dce_views_select_rewrite', [
            'label' => __('Rewrite field', 'dynamic-content-for-elementor'),
            'description' => __('Override the output of this field with custom text. You may include HTML and Tokens.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '[field]',
            'placeholder' => '[field]',
            'condition' => [
                'dce_views_select_render' => 'rewrite',
            ]
                ]
        );
        $repeater_fields->add_control(
                'dce_views_select_link', [
            'label' => __('Link to Object', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
                ]
        );
        $repeater_fields->add_control(
                'dce_views_select_no_results', [
            'label' => __('No results text', 'dynamic-content-for-elementor'),
            'description' => __('Provide text to display if this field contains an empty result. You may include HTML and Tokens.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
                ]
        );
        $repeater_fields->add_control(
                'custom_classes_heading', [
            'label' => __('Custom classes', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $repeater_fields->add_control(
                'dce_views_select_class_wrapper', [
            'label' => __('Wrapper', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
                ]
        );
        $repeater_fields->add_control(
                'dce_views_select_class_label', [
            'label' => __('Label', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
                ]
        );
        $repeater_fields->add_control(
                'dce_views_select_class_value', [
            'label' => __('Value', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
                ]
        );


        $this->add_control(
                'dce_views_select_fields', [
            'label' => __('Show this fields', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater_fields->get_controls(),
            'title_field' => '{{{ dce_views_select_field }}}',
            'default' => ['dce_views_select_field' => 'post_title'],
            'condition' => [
                'dce_views_select_type' => 'fields',
            ],
                ]
        );

        $this->add_control(
                'dce_views_h_format',
                [
                    'label' => __('Format', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'dce_views_style_format', [
            'label' => __('Render as ', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => array('grid' => 'Grid', 'table' => 'Table', 'list' => 'List'),
            'default' => 'grid',
                ]
        );
        $this->add_control(
                'dce_views_style_list', [
            'label' => __('List type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'ul' => [
                    'title' => __('Unordered list', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-list-ul',
                ],
                'ol' => [
                    'title' => __('Ordered list', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-list-ol',
                ]
            ],
            'toggle' => false,
            'default' => 'ul',
            'condition' => [
                'dce_views_style_format' => 'list',
            ],
                ]
        );
        /*$this->add_responsive_control(
                'dce_views_style_col', [
            'label' => __('Columns', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 3,
            'min' => 1,
            'description' => __("Set 1 to show one result per line", 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_style_format' => 'grid',
                //'dce_views_style_grid_class!' => '',
            ],
                ]
        );*/
        $this->add_responsive_control(
                'dce_views_style_col_width',
                [
                    'label' => __('Column Width', 'elementor-pro'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => __('Default', 'elementor-pro'),
                        '100' => '100%',
                        '80' => '80%',
                        '75' => '75%',
                        '66' => '66%',
                        '60' => '60%',
                        '50' => '50%',
                        '40' => '40%',
                        '33' => '33%',
                        '25' => '25%',
                        '20' => '20%',
                    ],
                    'default' => '100',
                    'condition' => [
                        'dce_views_style_format' => 'grid',
                    ],
                ]
        );
        
        
        $this->add_control(
                'dce_views_select_class_heading', [
            'label' => __('Custom classes', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        /*$this->add_control(
                'dce_views_style_grid_class', [
            'label' => __('Add default classes', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'description' => __("Add default classes to row and cols to create a flex grid.", 'dynamic-content-for-elementor'),
            'default' => 1,
            'condition' => [
                'dce_views_style_format' => 'grid',
            ],
                ]
        );*/        
        $this->add_control(
                'dce_views_style_wrapper_class', [
            'label' => __('Wrapper', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => 'row',
                ]
        );
        $this->add_control(
                'dce_views_style_element_class', [
            'label' => __('Single Element', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => 'col col-md-4 col-sm-2',
                ]
        );

        $this->end_controls_section();

//* FROM *//
        $this->start_controls_section(
                'section_from', [
            'label' => __('From', 'dynamic-content-for-elementor'),
                ]
        );

        $this->add_control(
                'dce_views_from_dynamic', [
            'label' => __('Dynamic', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __('Automatic fetch results from global WP_Query, change by current page', 'dynamic-content-for-elementor'),
            'separator' => 'after',
            'condition' => [
                'dce_views_object' => 'post',
            ],
                ]
        );

        unset($cpts['elementor_library']);
        $this->add_control(
                'dce_views_cpt', [
            'label' => __('Post Type', 'dynamic-content-for-elementor'),
            'label_block' => true,
            'type' => Controls_Manager::SELECT2,
            'options' => $cpts + array('nav_menu_item' => __('Navigation menu item', 'dynamic-content-for-elementor'), 'custom' => __('Custom', 'dynamic-content-for-elementor'), 'any' => __('Any', 'dynamic-content-for-elementor')),
            //'description' => __('Select if post is one of this Type.', 'dynamic-content-for-elementor'),
            'default' => ['post'],
            'multiple' => true,
            'condition' => [
                'dce_views_from_dynamic' => '',
                'dce_views_object' => 'post',
            ],
                ]
        );
        $this->add_control(
                'dce_views_attachment_mime_type', [
            'label' => __('Mime Type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => 'application/pdf,image/jpeg,image/png',
            'condition' => [
                'dce_views_cpt' => 'attachment',
                'dce_views_from_dynamic' => '',
                'dce_views_object' => 'post',
            ],
                ]
        );
        $this->add_control(
                'dce_views_cpt_custom', [
            'label' => __('CPT name', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => 'my_cpt_name',
            'condition' => [
                'dce_views_cpt' => 'custom',
                'dce_views_from_dynamic' => '',
                'dce_views_object' => 'post',
            ],
                ]
        );

        $this->add_control(
                'dce_views_status', [
            'label' => __('Status', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'options' => $post_status + array('any' => __('Any', 'dynamic-content-for-elementor')),
            'multiple' => true,
            'default' => ['publish'],
            'separator' => 'after',
            'condition' => [
                'dce_views_from_dynamic' => '',
                'dce_views_object' => 'post',
            ],
                ]
        );
        $this->add_control(
                'taxonomy_heading', [
            'label' => __('Taxonomy', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'condition' => [
                'dce_views_from_dynamic' => '',
                'dce_views_object!' => 'user'
            ],
                ]
        );
        $this->add_control(
                'dce_views_tax', [
            'label' => __('Taxonomy', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'options' => $taxonomies,
            'multiple' => true,
            'condition' => [
                'dce_views_object' => 'term',
            ],
                ]
        );
        $this->add_control(
                'dce_views_empty', [
            'label' => __('Hide Empty', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => [
                'dce_views_object' => 'term',
            ],
                ]
        );
        $this->add_control(
                'dce_views_count', [
            'label' => __('Count', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'dce_views_object' => 'term',
            ],
                ]
        );

        $this->add_control(
                'dce_views_role', [
            'label' => __('Roles', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'options' => $roles,
            //'default' => 'everyone',
            'multiple' => true,
            'condition' => [
                'dce_views_object' => 'user',
            ],
                ]
        );
        $this->add_control(
                'dce_views_role_exclude', [
            'label' => __('Exclude Roles', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'options' => $roles,
            //'default' => 'everyone',
            'multiple' => true,
            'condition' => [
                'dce_views_object' => 'user',
            ],
                ]
        );

        unset($taxonomies['elementor_library_type']);
        foreach ($taxonomies as $tkey => $atax) {
            if ($tkey) {// && !empty($taxonomies_terms[$tkey]) && count($taxonomies_terms[$tkey]) > 1) {
                $this->add_control(
                        'dce_views_term_' . $tkey,
                        [
                            'label' => $atax,
                            'type' => 'ooo_query',
                            'placeholder' => __('Meta key or Field Name', 'dynamic-content-for-elementor'),
                            'label_block' => true,
                            'query_type' => 'terms',
                            'object_type' => $tkey,
                            'label_block' => true,
                            'multiple' => true,
                            'condition' => [
                                'dce_views_from_dynamic' => '',
                                'dce_views_object' => 'post',
                            ],
                        ]
                );
            }
        }
        $this->add_control(
                'dce_views_tax_relation', [
            'label' => __('Tax Relation', 'dynamic-content-for-elementor'),
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
                'dce_views_from_dynamic' => '',
                'dce_views_object' => 'post',
            ],
                ]
        );
        $this->add_control(
                'dce_views_ignore_ids', [
            'label' => __('Ignore IDs', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT, //SELECT2,
            'separator' => 'before',
                ]
        );
        $this->end_controls_section();


//* WHERE - Conditions *//
        $this->start_controls_section(
                'section_where', [
            'label' => __('Where - Filter criteria', 'dynamic-content-for-elementor'),
                ]
        );
        $repeater_where = new \Elementor\Repeater();

        $repeater_where->add_control(
                'dce_views_where_field',
                [
                    'label' => __('Field', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Meta key or Field Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'fields',
                    'object_type' => 'any',
                ]
        );
        $repeater_where->add_control(
                'dce_views_where_field_is_sub', [
            'label' => __('Has Sub Fields', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __('For data stored Serialized or in Json format', 'dynamic-content-for-elementor'),
                ]
        );
        $repeater_where->add_control(
                'dce_views_where_field_sub', [
            'label' => __('Sub Field', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '[field]',
            'description' => __('Use Token notation to access to sub field value. Example: [field:sub_field], [field:sub_array:sub_sub_field], [field:0:sub_field]', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_where_field_is_sub!' => '',
            ],
                ]
        );
        $repeater_where->add_control(
                'dce_views_where_operator', [
            'label' => __('Operator', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => $sql_operators,
            'default' => '=',
                ]
        );
        $repeater_where->add_control(
                'dce_views_where_value', [
            'label' => __('Value', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
                ]
        );
        $repeater_where->add_control(
                'dce_views_where_rule', [
            'label' => __('Combination', 'dynamic-content-for-elementor'),
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
                ]
        );
        $this->add_control(
                'dce_views_where', [
            'label' => __('Filter by this conditions', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater_where->get_controls(),
            'prevent_empty' => false,
            'title_field' => '{{{ dce_views_where_field }}}',
                ]
        );
        $this->end_controls_section();


//* WHERE - Exposed form *//
        $this->start_controls_section(
                'section_form', [
            'label' => __('Where - Exposed form', 'dynamic-content-for-elementor'),
                ]
        );

        $repeater_form = new \Elementor\Repeater();

        $repeater_form->start_controls_tabs('dce_views_where_form_fields_tabs');
        $repeater_form->start_controls_tab('dce_views_where_form_fields_content_tab', [
            'label' => __('Content', 'elementor-pro'),
        ]);
        $repeater_form->add_control(
                'dce_views_where_form_field',
                [
                    'label' => __('Filter', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Meta key or Field Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'taxonomies_fields',
                    'object_type' => 'any',
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_field_is_sub', [
            'label' => __('Has Sub Fields', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __('For data stored Serialized or in Json format', 'dynamic-content-for-elementor'),
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_field_sub', [
            'label' => __('Sub Field', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '[field]',
            'description' => __('Use Token notation to access to sub field value. Example: [field:sub_field], [field:sub_array:sub_sub_field], [field:0:sub_field]', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_where_form_field_is_sub!' => '',
            ],
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_label', [
            'label' => __('Label', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_type', [
            'label' => __('Type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => array('text' => 'Text', /* 'textarea' => 'TextArea', */ 'select' => 'Select', 'radio' => 'Radio', 'checkbox' => 'Checkbox', 'auto' => 'AUTO'),
            'default' => 'auto',
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_operator', [
            'label' => __('Operator', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => $sql_operators,
            'default' => 'LIKE'
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_value', [
            'label' => __('Value', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'description' => __('If select/ceckbox/radio use one line for option, use | to separate value and name (ex: "my_value|Name of the option").', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_where_form_type!' => 'text',
                'dce_views_where_form_type!' => 'auto',
            ]
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_rule', [
            'label' => __('Combination', 'dynamic-content-for-elementor'),
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
            'default' => 'AND',
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_required', [
            'label' => __('Required', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
                ]
        );
        $repeater_form->end_controls_tab();

        $repeater_form->start_controls_tab(
                'dce_views_where_form_fields_advanced_tab',
                [
                    'label' => __('Advanced', 'elementor-pro'),
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_preselect', [
            'label' => __('Preselect', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'description' => __('Insert default value.', 'dynamic-content-for-elementor'),
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_required_empty_label', [
            'label' => __('Empty option label', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Select a value', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_where_form_required' => '',
            ]
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_hint', [
            'label' => __('Hint', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'description' => __('A short description of the field', 'dynamic-content-for-elementor'),
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_placeholder', [
            'label' => __('Placeholder', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
                ]
        );
        $repeater_form->end_controls_tab();
        $repeater_form->start_controls_tab(
                'dce_views_where_form_fields_style_tab',
                [
                    'label' => __('Style', 'elementor-pro'),
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_required_label', [
            'label' => __('Post Field Status', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'none' => [
                    'title' => __('None', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-close',
                ],
                'asterisk' => [
                    'title' => __('*', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-asterisk',
                ],
                'text' => [
                    'title' => __('Text', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-i-cursor',
                ]
            ],
            'default' => 'asterisk',
            'toggle' => false,
            'condition' => [
                'dce_views_where_form_required!' => '',
            ],
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_required_label_text', [
            'label' => __('Required marker Text', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => __('required', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_where_form_required!' => '',
                'dce_views_where_form_required_label' => 'text',
            ]
                ]
        );
        $repeater_form->add_control(
            'dce_views_where_form_field_inline', [
            'label' => __('Inline', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'dce_views_where_form_type' => ['radio', 'checkbox'],
            ],
                ]
        );     
                
        $repeater_form->add_responsive_control(
                'dce_views_where_form_width',
                [
                    'label' => __('Column Width', 'elementor-pro'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => __('Default', 'elementor-pro'),
                        '100' => '100%',
                        '80' => '80%',
                        '75' => '75%',
                        '66' => '66%',
                        '60' => '60%',
                        '50' => '50%',
                        '40' => '40%',
                        '33' => '33%',
                        '25' => '25%',
                        '20' => '20%',
                    ],
                    'default' => '100',
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_custom_classes_heading', [
            'label' => __('Custom classes', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_class_wrapper', [
            'label' => __('Wrapper', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_class_label', [
            'label' => __('Label', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
                ]
        );
        $repeater_form->add_control(
                'dce_views_where_form_class_input', [
            'label' => __('Input', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
                ]
        );
        $repeater_form->end_controls_tab();
        $repeater_form->end_controls_tabs();
        $this->add_control(
                'dce_views_where_form', [
            'label' => __('Exposed Fields', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater_form->get_controls(),
            'prevent_empty' => false,
            'title_field' => '{{{ dce_views_where_form_field }}}',
            'separator' => 'before',
                ]
        );
        
        $this->add_control(
                'dce_views_input_size',
                [
                        'label' => __( 'Input Size', 'elementor-pro' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => DCE_Helper::bootstrap_button_sizes(),
                        'default' => 'sm',
                        'separator' => 'before',
                ]
        );
        $this->add_control(
                'dca_views_style_form_show_labels', [
            'label' => __( 'Label', 'elementor-pro' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'inline' => [
                    'title' => __('Inline', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-arrows-h',
                ],
                'block' => [
                    'title' => __('Block', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-stop',
                ],
                'none' => [
                    'title' => __('None', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-eye-slash',
                ],
            ],
            'default' => 'inline',
            'selectors' => ['{{WRAPPER}} .dce-view-exposed-form label.dce-view-input-label' => 'display: {{VALUE}};'],
                ]
        );
        
        $this->add_control(
                'dce_views_style_form_text', [
            'label' => __('Form Title', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
            'condition' => [
                'dce_views_where_form!' => ['', []],
            ],
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'dce_views_style_form_text_size',
                [
                    'label' => __('Title HTML Tag', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'h1' => 'H1',
                        'h2' => 'H2',
                        'h3' => 'H3',
                        'h4' => 'H4',
                        'h5' => 'H5',
                        'h6' => 'H6',
                        'div' => 'div',
                        'span' => 'span',
                        'p' => 'p',
                    ],
                    'default' => 'h4',
                    'condition' => [
                        'dce_views_where_form!' => ['', []],
                        'dce_views_style_form_text!' => '',
                    ]
                ]
        );

        $this->add_control(
                'dce_views_where_form_result', [
            'label' => __('Show result', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'description' => __("Show results from first time, also before user interact with form, using preselected value.", 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_where_form!' => ['', []],
            ],
                ]
        );
        $this->add_control(
                'dce_views_where_form_ajax', [
            'label' => __('Use Ajax', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'dce_views_where_form!' => ['', []],
            ],
                ]
        );
        $this->add_control(
                'dce_views_where_form_ajax_transition', [
            'label' => __('Transition', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => array('' => 'Toggle', 'fade' => 'Fade', 'slide' => 'Slide'),
            'condition' => [
                'dce_views_where_form!' => ['', []],
                'dce_views_where_form_ajax!' => '',
            ],
                ]
        );
        $this->add_control(
                'dce_views_where_form_ajax_onchange', [
            'label' => __('Submit on change', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'dce_views_where_form!' => ['', []],
                'dce_views_where_form_ajax!' => '',
            ],
                ]
        );
        $this->add_control(
                'dce_views_where_form_ajax_nobutton', [
            'label' => __('Remove submit button', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'selectors' => [
                '{{WRAPPER}} .dce-view-exposed-form input.dce-button' => 'display: none;',
            ],
            'condition' => [
                'dce_views_where_form!' => ['', []],
                'dce_views_where_form_ajax!' => '',
                'dce_views_where_form_ajax_onchange!' => '',
            ],
                ]
        );

        $this->add_control(
                'dce_views_where_form_action_heading', [
            'label' => __('Form Action', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'dce_views_where_form!' => ['', []],
                'dce_views_where_form_ajax_nobutton' => '',
            ],
                ]
        );
        $this->add_control(
                'dce_views_where_form_reset', [
            'label' => __('Show Reset', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'dce_views_where_form!' => ['', []],
                'dce_views_where_form_ajax_nobutton' => '',
            ],
                ]
        );
        $this->add_control(
                'dce_views_style_form_submit_text', [
            'label' => __('Submit Text', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Search', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_where_form!' => ['', []],
                'dce_views_where_form_ajax_nobutton' => '',
            ],
                ]
        );
        $this->add_responsive_control(
                'dce_views_where_form_action_width',
                [
                    'label' => __('Column Width', 'elementor-pro'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => __('Default', 'elementor-pro'),
                        '100' => '100%',
                        '80' => '80%',
                        '75' => '75%',
                        '66' => '66%',
                        '60' => '60%',
                        '50' => '50%',
                        '40' => '40%',
                        '33' => '33%',
                        '25' => '25%',
                        '20' => '20%',
                    ],
                    'default' => '100',
                    'condition' => [
                        'dce_views_where_form!' => ['', []],
                        'dce_views_where_form_ajax_nobutton' => '',
                    ],
                ]
        );

        $this->add_control(
                'dce_views_where_form_class_heading', [
            'label' => __('Custom classes', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'dce_views_where_form!' => ['', []],
            ],
                ]
        );
        $this->add_control(
                'dce_views_where_form_class', [
            'label' => __('Form', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'condition' => [
                'dce_views_where_form!' => ['', []],
            ],
                ]
        );
        $this->add_control(
                'dce_views_where_form_class_wrapper', [
            'label' => __('Wrapper', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'dce-basic-form',
            'condition' => [
                'dce_views_where_form!' => ['', []],
            ],
                ]
        );
        $this->add_control(
                'dce_views_where_form_class_filter', [
            'label' => __('Filters', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => 'form-group',
            'condition' => [
                'dce_views_where_form!' => ['', []],
            ],
                ]
        );
        $this->add_control(
                'dce_views_where_form_class_buttons', [
            'label' => __('Buttons wrapper', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => 'form-action',
            'condition' => [
                'dce_views_where_form!' => ['', []],
                'dce_views_where_form_ajax_nobutton' => '',
            ],
                ]
        );
        $this->add_control(
                'dce_views_where_form_class_button', [
            'label' => __('Button', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => 'btn-primary',
            'condition' => [
                'dce_views_where_form!' => ['', []],
                'dce_views_where_form_ajax_nobutton' => '',
            ],
                ]
        );
        $this->end_controls_section();


        //* GROUP BY *//
        $this->start_controls_section(
                'section_group_by', [
            'label' => __('Group By', 'dynamic-content-for-elementor'),
                ]
        );
        /* $this->add_control(
          'dce_views_group_by_field', [
          'label' => __('Grouping field', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SELECT,
          //'options' => $post_fields,
          'groups' => $post_fields,
          'multiple' => false,
          'label_block' => true,
          'description' => __(' You may optionally specify a field by which to group the records. Leave blank to not group.', 'dynamic-content-for-elementor'),
          ]
          ); */
        $this->add_control(
                'dce_views_group_by_field',
                [
                    'label' => __('Grouping field', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Meta key or Field Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'fields',
                    'object_type' => 'any',
                /* 'condition' => [
                  'dce_views_object' => $type,
                  ] */
                ]
        );
        $this->add_control(
                'dce_views_group_by_field_heading_show', [
            'label' => __('Show Heading', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                ]
        );
        $this->add_control(
                'dce_views_group_by_field_heading', [
            'label' => __('Heading text', 'dynamic-content-for-elementor'),
            'default' => '[TITLE]',
            'placeholder' => '[TITLE]',
            'description' => __('Group heading text. You may include HTML and Tokens.', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'condition' => [
                'dce_views_group_by_field_heading_show!' => '',
            ]
                ]
        );
        $this->add_control(
                'dce_views_group_by_heading_size',
                [
                    'label' => __('Heading HTML Tag', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'h1' => 'H1',
                        'h2' => 'H2',
                        'h3' => 'H3',
                        'h4' => 'H4',
                        'h5' => 'H5',
                        'h6' => 'H6',
                        'div' => 'div',
                        'span' => 'span',
                        'p' => 'p',
                    ],
                    'default' => 'h4',
                    'condition' => [
                        'dce_views_group_by_field_heading_show!' => '',
                    ]
                ]
        );
        $this->add_control(
                'dce_views_group_by_classes_heading', [
            'label' => __('Custom classes', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_control(
                'dce_views_group_by_class_wrapper', [
            'label' => __('Wrapper', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
                ]
        );
        $this->add_control(
                'dce_views_group_by_class_heading', [
            'label' => __('Heading', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'condition' => [
                'dce_views_group_by_field_heading_show!' => '',
            ]
                ]
        );
        $this->end_controls_section();

//* ORDER BY *//
        $repeater_order = new \Elementor\Repeater();
        /* $repeater_order->add_control(
          'dce_views_order_field', [
          'label' => __('Field', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SELECT,
          //'options' => $post_fields
          'groups' => $post_fields,
          'label_block' => true,
          ]
          ); */
        $repeater_order->add_control(
                'dce_views_order_field',
                [
                    'label' => __('Filter', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Meta key or Field Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'fields',
                    'object_type' => 'any',
                /* 'condition' => [
                  'dce_views_object' => $type,
                  ] */
                ]
        );
        $repeater_order->add_control(
                'dce_views_order_field_sort', [
            'label' => __('Sorting', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'ASC' => [
                    'title' => __('ASC', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-sort-up',
                ],
                'DESC' => [
                    'title' => __('DESC', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-sort-down',
                ]
            ],
            'inline' => true,
            'toggle' => false,
            'default' => 'ASC',
                ]
        );
        $repeater_order->add_control(
                'dce_views_order_field_sort_exposed', [
            'label' => __('Exposed', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __('Expose this sort to visitors, to allow them to change it', 'dynamic-content-for-elementor'),
                ]
        );

        $this->start_controls_section(
                'section_order', [
            'label' => __('Order By - Sort criteria', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'dce_views_order_random', [
            'label' => __('Random', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __('Randomize result sort order.', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'dce_views_order_by', [
            'label' => __('Sorting by fields', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater_order->get_controls(),
            'prevent_empty' => false,
            'title_field' => '{{{ dce_views_order_field }}}',
            'condition' => [
                'dce_views_order_random' => '',
            ],
                ]
        );
        $this->add_control(
                'exposed_sort_heading', [
            'label' => __('Exposed Sort', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HEADING,
            'condition' => [
                'dce_views_order_by!' => [],
            ],
                ]
        );
        $this->add_control(
                'dce_views_order_class', [
            'label' => __('Custom class', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'dce-basic-sort',
            'condition' => [
                'dce_views_order_by!' => [],
            ],
                ]
        );
        $this->add_control(
                'dce_views_order_label', [
            'label' => __('Label', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Sort by', 'dynamic-content-for-elementor' . '_texts'),
            'condition' => [
                'dce_views_order_by!' => [],
            ],
                ]
        );
        $this->end_controls_section();


//* LIMIT *//
        $this->start_controls_section(
                'section_limit', [
            'label' => __('Limit - Pager', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_group_by_field' => '',
            ],
                ]
        );
        $this->add_control(
                'dce_views_limit_offset', [
            'label' => __('Start from - Offset', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 0,
            'min' => 0,
            'description' => __("Number of items to skip. For example, set this to 3 and the first 3 items will not be displayed. Set 0 to show from the first result.", 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'dce_views_limit_to', [
            'label' => __('Max allowed result displayed', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 0,
            'min' => 0,
            'description' => __("Set 0 if you do not want to limit results", 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'dce_views_pagination', [
            'label' => __('Pagination', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __('Enable results pagination', 'dynamic-content-for-elementor'),
                ]
        );



        $this->add_control(
                'dce_views_post_per_page', [
            'label' => __('Results per page', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 10,
            'min' => 0,
            'description' => __("Set 0 for default site global limit.", 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_pagination!' => '',
            ],
                ]
        );

        $this->add_control(
                'dce_views_pagination_type',
                [
                    'label' => __('Pagination', 'elementor-pro'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'numbers_and_prev_next',
                    'options' => [
                        'infinite_scroll' => __('Infinite Scroll', 'dynamic-content-for-elementor'),
                        'numbers' => __('Numbers', 'elementor-pro'),
                        'prev_next' => __('Previous/Next', 'elementor-pro'),
                        'numbers_and_prev_next' => __('Numbers', 'elementor-pro') . ' + ' . __('Previous/Next', 'elementor-pro'),
                    ],
                    'condition' => [
                        'dce_views_pagination!' => '',
                    ],
                ]
        );

        $this->add_control(
                'dce_views_pagination_page_limit',
                [
                    'label' => __('Page Limit', 'elementor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 0,
                    'min' => 0,
                    'condition' => [
                        'dce_views_pagination!' => '',
                        'dce_views_pagination_type' => [
                            'numbers',
                            'numbers_and_prev_next',
                        ],
                    ],
                ]
        );

        $this->add_control(
                'dce_views_pagination_numbers_shorten',
                [
                    'label' => __('Shorten', 'elementor-pro'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'condition' => [
                        'dce_views_pagination!' => '',
                        'dce_views_pagination_type' => [
                            'numbers',
                            'numbers_and_prev_next',
                        ],
                    ],
                ]
        );

        $this->add_control(
                'dce_views_pagination_prev_label',
                [
                    'label' => __('Previous Label', 'elementor-pro'),
                    'default' => __('&laquo; Previous', 'elementor-pro'),
                    'condition' => [
                        'dce_views_pagination!' => '',
                        'dce_views_pagination_type' => [
                            'prev_next',
                            'numbers_and_prev_next',
                        ],
                    ],
                ]
        );

        $this->add_control(
                'dce_views_pagination_next_label',
                [
                    'label' => __('Next Label', 'elementor-pro'),
                    'default' => __('Next &raquo;', 'elementor-pro'),
                    'condition' => [
                        'dce_views_pagination!' => '',
                        'dce_views_pagination_type' => [
                            'prev_next',
                            'numbers_and_prev_next',
                        ],
                    ],
                ]
        );


        $this->add_control(
                'dce_views_limit_scroll_last', [
            'label' => __('Label Last', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'condition' => [
                'dce_views_pagination!' => '',
                'dce_views_pagination_type' => [
                    'infinite_scroll',
                ],
            ],
                ]
        );
        $this->end_controls_section();


//* FALLBACK for NO RESULTS *//
        $this->start_controls_section(
                'section_fallback', [
            'label' => __('No results behavior', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'dce_views_fallback', [
            'label' => __('Enable a Fallback Content', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'description' => __("If you want to show something when no element were found.", 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'dce_views_fallback_type', [
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
            'toggle' => false,
            'default' => 'text',
            'condition' => [
                'dce_views_fallback!' => '',
            ],
                ]
        );
        /* $this->add_control(
          'dce_views_fallback_template', [
          'label' => __('Render Template', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SELECT2,
          'options' => $templates,
          'description' => 'Use a Elementor Template as content, useful for complex structure.',
          'condition' => [
          'dce_views_fallback!' => '',
          'dce_views_fallback_type' => 'template',
          ],
          ]
          ); */
        $this->add_control(
                'dce_views_fallback_template',
                [
                    'label' => __('Render Template', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Template Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'object_type' => 'elementor_library',
                    'condition' => [
                        'dce_views_fallback!' => '',
                        'dce_views_fallback_type' => 'template',
                    ],
                ]
        );
        $this->add_control(
                'dce_views_fallback_text', [
            'label' => __('Text Fallback', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::WYSIWYG,
            'default' => "This view has no results.",
            'description' => __("Write here some content, you can use HTML and TOKENS.", 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_fallback!' => '',
                'dce_views_fallback_type' => 'text',
            ],
                ]
        );
        $this->end_controls_section();


//* STYLE *//

        $this->start_controls_section(
                'section_style_table', [
            'label' => __('Table', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'dce_views_style_format' => 'table',
            ],
                ]
        );
        $this->add_control(
                'dce_views_style_table_data', [
            'label' => __('Use DataTables', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'description' => __("Add advanced interaction controls to your HTML tables.", 'dynamic-content-for-elementor')
            . '<br><small>' . __('Read more on ', 'dynamic-content-for-elementor') . ' <a href="https://datatables.net/" target="_blank">DataTables</a></small>',
                ]
        );
        $this->add_control(
                'heading_views_datatables_extensions',
                [
                    'label' => __('DataTables Extensions', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => '<br><small>' . __('Read more on ', 'dynamic-content-for-elementor') . ' <a href="https://datatables.net/extensions/index" target="_blank">DataTables Extensions</a></small>',
                    'separator' => 'before',
                    'condition' => [
                        'dce_views_style_table_data!' => '',
                    ]
                ]
        );
        $this->add_control(
                'dce_views_style_table_data_autofill', [
            'label' => __('Autofill', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'description' => __('Excel-like click and drag copying and filling of data.', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_style_table_data!' => '',
            ]
                ]
        );
        $this->add_control(
                'dce_views_style_table_data_buttons', [
            'label' => __('Buttons', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'description' => __('A common framework for user interaction buttons. Like Export and Print.', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_style_table_data!' => '',
            ]
                ]
        );
        $this->add_control(
                'dce_views_style_table_data_colreorder', [
            'label' => __('ColReorder', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'description' => __('Click-and-drag column reordering.', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_style_table_data!' => '',
            ]
                ]
        );
        $this->add_control(
                'dce_views_style_table_data_fixedcolumns', [
            'label' => __('FixedColumns', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'description' => __('Fix one or more columns to the left or right of a scrolling table.', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_style_table_data!' => '',
            ]
                ]
        );
        $this->add_control(
                'dce_views_style_table_data_fixedheader', [
            'label' => __('FixedHeader', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'description' => __('Sticky header and / or footer for the table.', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_style_table_data!' => '',
            ]
                ]
        );
        $this->add_control(
                'dce_views_style_table_data_keytable', [
            'label' => __('KeyTable', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'description' => __('Keyboard navigation of cells in a table, just like a spreadsheet.', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_style_table_data!' => '',
            ]
                ]
        );
        $this->add_control(
                'dce_views_style_table_data_responsive', [
            'label' => __('Responsive', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'description' => __('Dynamically show and hide columns based on the browser size.', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_style_table_data!' => '',
            ]
                ]
        );
        $this->add_control(
                'dce_views_style_table_data_rowgroup', [
            'label' => __('RowGroup', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'description' => __('Show similar data grouped together by a custom data point.', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_style_table_data!' => '',
            ]
                ]
        );
        $this->add_control(
                'dce_views_style_table_data_rowreorder', [
            'label' => __('RowReorder', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'description' => __('Click-and-drag reordering of rows.', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_style_table_data!' => '',
            ]
                ]
        );
        $this->add_control(
                'dce_views_style_table_data_scroller', [
            'label' => __('Scroller', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'description' => __('Virtual rendering of a scrolling table for large data sets.', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_style_table_data!' => '',
            ]
                ]
        );
        $this->add_control(
                'dce_views_style_table_data_select', [
            'label' => __('Select', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'description' => __('Adds row, column and cell selection abilities to a table.', 'dynamic-content-for-elementor'),
            'condition' => [
                'dce_views_style_table_data!' => '',
            ]
                ]
        );
        $this->end_controls_section();

        
        
        // RESULTS WRAPPER
        $this->start_controls_section(
                'section_style_results', [
            'label' => __('Results Container', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        $this->add_responsive_control(
                'dce_views_style_results_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-view-results' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'dce_views_style_results_margin', [
            'label' => __('Margin', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-view-results' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'dce_views_style_results_align',
                [
                    'label' => __('Alignment', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
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
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-results' => 'text-align: {{VALUE}};',
                    ],
                ]
        );
        // Border ----------------
        $this->add_control(
                'heading_views_results_border',
                [
                    'label' => __('Border', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'dce_views_style_results_border',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-view-results',
                ]
        );
        $this->add_control(
                'dce_views_style_results_border_radius', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-view-results' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        // Background ----------------
        $this->add_control(
                'heading_views_results_background',
                [
                    'label' => __('Background', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'dce_views_style_results_background',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .dce-view-results',
                ]
        );
        $this->end_controls_section();
        
        
        
        // SINGLE RESULT
        $this->start_controls_section(
                'section_style_result', [
            'label' => __('Single Result', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        $this->add_responsive_control(
                'dce_views_style_result_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-view-single' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'dce_views_style_result_margin', [
            'label' => __('Margin', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-view-single' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'dce_views_style_result_align',
                [
                    'label' => __('Alignment', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
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
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-single' => 'text-align: {{VALUE}};',
                    ],
                ]
        );
        // Border ----------------
        $this->add_control(
                'heading_views_result_border',
                [
                    'label' => __('Border', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'dce_views_style_result_border',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-view-single',
                ]
        );
        $this->add_control(
                'dce_views_style_result_border_radius', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-view-single' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        // Background ----------------
        $this->add_control(
                'heading_views_result_background',
                [
                    'label' => __('Background', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'dce_views_style_result_background',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .dce-view-single',
                ]
        );
        $this->end_controls_section();


        // EXPOSED FORM
        $this->start_controls_section(
                'section_style_form', [
            'label' => __('Exposed Form', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'dce_views_where_form!' => ['', []],
            ]
                ]
        );
        $this->add_responsive_control(
                'dce_views_style_form_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-view-form-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'dce_views_style_form_margin', [
            'label' => __('Margin', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-view-form-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'dce_views_style_form_align',
                [
                    'label' => __('Alignment', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
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
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form' => 'text-align: {{VALUE}};',
                    ],
                ]
        );
// Border ----------------
        $this->add_control(
                'heading_views_border',
                [
                    'label' => __('Border', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'dce_views_style_form_border',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-view-form-wrapper',
                ]
        );
        $this->add_control(
                'dce_views_style_form_border_radius', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-view-form-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
// Background ----------------
        $this->add_control(
                'heading_views_background',
                [
                    'label' => __('Background', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'background_search',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .dce-view-exposed-form',
                ]
        );

// Title ----------------
        $this->add_control(
                'heading_views_title',
                [
                    'label' => __('Title', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'dce_views_style_form_text!' => '',
                    ],
                ]
        );
        $this->add_responsive_control(
                'dce_views_style_form_title_align',
                [
                    'label' => __('Alignment', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
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
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form .dce-views-form-title' => 'text-align: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'dce_views_style_form_title_color',
                [
                    'label' => __('Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form .dce-views-form-title' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'dce_views_style_form_text!' => '',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'dce_views_style_form_title_typography',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-view-exposed-form .dce-views-form-title',
            'condition' => [
                'dce_views_style_form_text!' => '',
            ],
                ]
        );
        $this->add_control(
                'dce_views_style_form_title_space',
                [
                    'label' => __('Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => -50,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form .dce-views-form-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'dce_views_style_form_text!' => '',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'dce_views_style_form_title_text_shadow',
                    'selector' => '{{WRAPPER}} .dce-view-exposed-form .dce-views-form-title',
                    'condition' => [
                        'dce_views_style_form_text!' => '',
                    ],
                ]
        );

// Filters ----------------
        $this->add_control(
                'heading_views_filters',
                [
                    'label' => __('Filters', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_responsive_control(
                'dce_views_style_form_filters_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-view-exposed-form .dce-view-field-filter' => 'padding: {{TOP}}{{UNIT}} calc( {{RIGHT}}{{UNIT}}/2 ) {{BOTTOM}}{{UNIT}} calc( {{LEFT}}{{UNIT}}/2 );',
                '{{WRAPPER}} .dce-view-exposed-form .elementor-field-type-submit' => 'padding: {{TOP}}{{UNIT}} calc( {{RIGHT}}{{UNIT}}/2 ) {{BOTTOM}}{{UNIT}} calc( {{LEFT}}{{UNIT}}/2 );',
                '{{WRAPPER}} .dce-view-exposed-form .dce-view-fields-wrapper' => 'margin-left: calc( -{{LEFT}}{{UNIT}}/2 ); margin-right: calc( -{{RIGHT}}{{UNIT}}/2 );',
                //'{{WRAPPER}} .elementor-field-group' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
                //'{{WRAPPER}} .elementor-field-group' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
                //'{{WRAPPER}} .elementor-field-group.recaptcha_v3-bottomleft, {{WRAPPER}} .elementor-field-group.recaptcha_v3-bottomright' => 'margin-bottom: 0;',
                //'{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-bottom: -{{TOP}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'dce_views_style_form_filters_margin', [
            'label' => __('Margin', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-view-form-wrapper .dce-view-field-filter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'dce_views_style_form_filters_align',
                [
                    'label' => __('Alignment', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
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
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form .dce-view-field-filter' => 'text-align: {{VALUE}};',
                    ],
                ]
        );
// Field ----------------
        $this->add_control(
                'heading_views_field',
                [
                    'label' => __('Input Text & Select', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'dce_views_style_form_field_txcolor',
                [
                    'label' => __('Text Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form .dce-view-input > input[type=text]' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .dce-view-exposed-form .dce-view-input > select' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'dce_views_style_form_field_bgcolor',
                [
                    'label' => __('Background Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form .dce-view-input > input[type=text]' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .dce-view-exposed-form .dce-view-input > select' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'dce_views_style_form_field_typography',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-view-exposed-form .dce-view-input > input[type=text], {{WRAPPER}} .dce-view-exposed-form .dce-view-input > select',
                ]
        );
        $this->add_control(
                'dce_views_style_form_field_border_radius',
                [
                    'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 1,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form .dce-view-input > input[type=text]' => 'border-radius: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .dce-view-exposed-form .dce-view-input > select' => 'border-radius: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_responsive_control(
                'dce_views_style_form_field_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-view-exposed-form .dce-view-input > input[type=text]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .dce-view-exposed-form .dce-view-input > select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'dce_views_style_form_field_border',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-view-exposed-form .button',
                ]
        );
        $this->add_control(
                'dce_views_style_form_field_space',
                [
                    'label' => __('Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => -50,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form .dce-view-field-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'dce_views_style_form_field_box_shadow',
                    'exclude' => [
                        'box_shadow_position',
                    ],
                    'selector' => '{{WRAPPER}} .dce-view-exposed-form .dce-view-input',
                ]
        );
// Label ----------------
        $this->add_control(
                'heading_label_field',
                [
                    'label' => __('Label', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'dce_views_style_form_label_color',
                [
                    'label' => __('Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form label.dce-view-input-label' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'dce_views_style_form_label_typography',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-view-exposed-form label.dce-view-input-label',
                ]
        );
        
// Buttons ----------------
        $this->add_control(
                'heading_views_buttons',
                [
                    'label' => __('Buttons', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'buttons_align',
                [
                    'label' => __('Alignment', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        //'start' => [
                        'left' => [
                                'title' => __( 'Left', 'elementor-pro' ),
                                'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                                'title' => __( 'Center', 'elementor-pro' ),
                                'icon' => 'eicon-text-align-center',
                        ],
                        //'end' => [
                        'right' => [
                                'title' => __( 'Right', 'elementor-pro' ),
                                'icon' => 'eicon-text-align-right',
                        ],
                        //'stretch' => [
                        'justify' => [
                                'title' => __( 'Justified', 'elementor-pro' ),
                                'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form .dce-view-exposed-form-buttons' => 'text-align: {{VALUE}};',
                    ],
                    'render_type' => 'template',
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'buttons_typography',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-view-exposed-form .button',
                ]
        );
        $this->add_control(
                'buttons_border_radius',
                [
                    'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 1,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form .button' => 'border-radius: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_responsive_control(
                'buttons_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .dce-view-exposed-form .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'buttons_margin', [
            'label' => __('Margin', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .dce-view-exposed-form .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'buttons_border',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-view-exposed-form .button',
                ]
        );

        $this->add_control(
                'buttons_v_space',
                [
                    'label' => __('Verical Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form .button' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_control(
                'buttons_h_space',
                [
                    'label' => __('Horizontal Space', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form .button' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
// Button Reset ----------------
        $this->add_control(
                'heading_views_buttonReset',
                [
                    'label' => __('Button Reset', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'buttonreset_txcolor',
                [
                    'label' => __('Text Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form input.reset' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonreset_bgcolor',
                [
                    'label' => __('Background Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form input.reset' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonreset_border_color',
                [
                    'label' => __('Border color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form input.reset' => 'border-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonreset_txcolor_hover',
                [
                    'label' => __('Hover Text Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form input.reset:hover' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonreset_bgcolor_hover',
                [
                    'label' => __('Hover Background Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form input.reset:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonreset_border_color_hover',
                [
                    'label' => __('Hover Border color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form input.reset:hover' => 'border-color: {{VALUE}};',
                    ],
                ]
        );
// Button Find ----------------
        $this->add_control(
                'heading_views_buttonFind',
                [
                    'label' => __('Button Find', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'buttonfind_txcolor',
                [
                    'label' => __('Text Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form input.find' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonfind_bgcolor',
                [
                    'label' => __('Background Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form input.find' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonfind_border_color',
                [
                    'label' => __('Border color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form input.find' => 'border-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonfind_txcolor_hover',
                [
                    'label' => __('Hover Text Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form input.find:hover' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonfind_bgcolor_hover',
                [
                    'label' => __('Hover Background Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form input.find:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'buttonfind_border_color_hover',
                [
                    'label' => __('Hover Border color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form input.reset:hover' => 'border-color: {{VALUE}};',
                    ],
                ]
        );


        $this->add_control(
                'form_col_inner_width',
                [
                    'label' => __('Col inner width', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HIDDEN,
                    'default' => '100',
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-form .dce-view-form-col-inner' => 'width: {{VALUE}}%;',
                    ],
                ]
        );

        $this->end_controls_section();

        // GROUP BY
        $this->start_controls_section(
                'section_style_group_by', [
            'label' => __('Group By', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'dce_views_group_by_field!' => '',
            ],
                ]
        );
        $this->add_control(
                'heading_views_group_by_title',
                [
                    'label' => __('Heading', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                ]
        );
        $this->add_control(
                'dce_views_style_group_by_title_width',
                [
                    'label' => __('Width', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HIDDEN,
                    'default' => '100',
                    'selectors' => [
                        '{{WRAPPER}} .dce-views-group-title' => 'width: {{VALUE}}%;',
                    ],
                ]
        );
        $this->add_responsive_control(
                'dce_views_style_group_by_title_align',
                [
                    'label' => __('Alignment', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
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
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-views-group-title' => 'text-align: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'dce_views_style_group_by_title_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-views-group-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'dce_views_style_group_by_title_margin', [
            'label' => __('Margin', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .dce-views-group-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'dce_views_style_group_by_title_border',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-views-group-title',
                ]
        );
        $this->add_control(
                'dce_views_style_group_by_title_color',
                [
                    'label' => __('Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .dce-views-group-title *' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'dce_views_style_group_by_title_bgcolor',
                [
                    'label' => __('Background Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-views-group-title' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'dce_views_style_group_by_title_typography',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-views-group-title',
                ]
        );
        $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'dce_views_style_group_by_title_text_shadow',
                    'selector' => '{{WRAPPER}} .dce-views-group-title',
                ]
        );
        $this->end_controls_section();


// EXPOSED SORT
        $this->start_controls_section(
                'section_style_sort', [
            'label' => __('Exposed Sort', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        $this->add_control(
                'dce_views_style_sort_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .dce-view-exposed-sort' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'dce_views_style_sort_margin', [
            'label' => __('Margin', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .dce-view-exposed-sort' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'dce_views_style_sort_align',
                [
                    'label' => __('Alignment', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
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
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-sort' => 'text-align: {{VALUE}};',
                    ],
                ]
        );
// Border ----------------
        $this->add_control(
                'heading_views_sort_border',
                [
                    'label' => __('Border', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'dce_views_style_sort_border',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-view-exposed-sort',
                ]
        );

        $this->add_control(
                'dce_views_style_sort_border_radius', [
            'label' => __('Border Radius', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-view-exposed-sort' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
// Background ----------------
        $this->add_control(
                'dce_views_h_style_sort_bg',
                [
                    'label' => __('Background', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'dce_views_style_sort_bg',
                    'selector' => '{{WRAPPER}} .dce-view-exposed-sort',
                ]
        );
// Label ----------------
        $this->add_control(
                'heading_sort_label_field',
                [
                    'label' => __('Label', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'dce_views_style_sort_label_color',
                [
                    'label' => __('Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-sort label' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'dce_views_style_sort_label_typography',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-view-exposed-sort label',
                ]
        );
        $this->add_control(
                'dce_views_style_sort_label_display', [
            'label' => __('Display', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'inline' => [
                    'title' => __('Inline', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-arrows-h',
                ],
                'block' => [
                    'title' => __('Block', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-stop',
                ]
            ],
            'default' => 'inline',
            'selectors' => ['{{WRAPPER}} .dce-view-exposed-sort label' => 'display: {{VALUE}};'],
                ]
        );
        $this->add_control(
                'heading_views_sort_field',
                [
                    'label' => __('Select', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->add_responsive_control(
                'dce_views_style_sort_field_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .dce-view-exposed-sort .dce-input-sort' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'dce_views_style_sort_field_txcolor',
                [
                    'label' => __('Text Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-sort .dce-input-sort' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'dce_views_style_sort_field_bgcolor',
                [
                    'label' => __('Background Color', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-sort .dce-input-sort' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'dce_views_style_sort_field_typography',
            'label' => __('Typography', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-view-exposed-sort .dce-input-sort',
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'dce_views_style_sort_field_border',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-view-exposed-sort .dce-input-sort',
                ]
        );
        $this->add_control(
                'dce_views_style_sort_field_border_radius',
                [
                    'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 1,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .dce-view-exposed-sort .dce-input-sort' => 'border-radius: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->end_controls_section();


        // PAGINATION
        $this->start_controls_section(
                'section_style_pagination', [
            'label' => __('Pagination', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'dce_views_pagination!' => '',
            ]
                ]
        );
        $this->add_control(
                'pagination_align',
                [
                    'label' => __('Alignment', 'elementor-pro'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __('Left', 'elementor-pro'),
                            'icon' => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => __('Center', 'elementor-pro'),
                            'icon' => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => __('Right', 'elementor-pro'),
                            'icon' => 'fa fa-align-right',
                        ],
                    ],
                    'default' => 'center',
                    'selectors' => [
                        '{{WRAPPER}} .elementor-pagination' => 'text-align: {{VALUE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'pagination_typography',
                    'selector' => '{{WRAPPER}} .elementor-pagination',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_2,
                ]
        );
        $this->add_control(
                'pagination_color_heading',
                [
                    'label' => __('Colors', 'elementor-pro'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        $this->start_controls_tabs('pagination_colors');
        $this->start_controls_tab(
                'pagination_color_normal',
                [
                    'label' => __('Normal', 'elementor-pro'),
                ]
        );
        $this->add_control(
                'pagination_color',
                [
                    'label' => __('Color', 'elementor-pro'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-pagination .page-numbers:not(.dots)' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'pagination_bgcolor',
                [
                    'label' => __('Background Color', 'elementor-pro'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-pagination .page-numbers:not(.dots)' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
                'pagination_color_hover',
                [
                    'label' => __('Hover', 'elementor-pro'),
                ]
        );
        $this->add_control(
                'pagination_hover_color',
                [
                    'label' => __('Color', 'elementor-pro'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-pagination a.page-numbers:hover' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'pagination_hover_bgcolor',
                [
                    'label' => __('Background Color', 'elementor-pro'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-pagination a.page-numbers:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
                'pagination_color_active',
                [
                    'label' => __('Active', 'elementor-pro'),
                ]
        );
        $this->add_control(
                'pagination_active_color',
                [
                    'label' => __('Color', 'elementor-pro'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-pagination .page-numbers.current' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'pagination_active_bgcolor',
                [
                    'label' => __('Background Color', 'elementor-pro'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-pagination .page-numbers.current' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
                'pagination_spacing',
                [
                    'label' => __('Space Between', 'elementor-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'separator' => 'before',
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        'body:not(.rtl) {{WRAPPER}} .elementor-pagination .page-numbers:not(:first-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
                        'body:not(.rtl) {{WRAPPER}} .elementor-pagination .page-numbers:not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
                        'body.rtl {{WRAPPER}} .elementor-pagination .page-numbers:not(:first-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
                        'body.rtl {{WRAPPER}} .elementor-pagination .page-numbers:not(:last-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
                    ],
                ]
        );
        $this->end_controls_section();


        // ADVANCED
        $this->start_controls_section(
                'section_style_advanced', [
            'label' => __('Special effects', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        $this->add_control(
                'dce_views_style_entrance_animation', [
            'label' => __('Entrance Animation', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::ANIMATION,
            'prefix_class' => 'animated ',
                ]
        );
        $this->end_controls_section();
    }

    protected function render() {

        //return false;

        $settings = $this->get_settings_for_display(null, true);
        //remove_filter('the_content', 'wpautop');

        $this->_exposed_form();

        $this->_exposed_sort();

        if ($settings['dce_views_where_form_result'] // forzo la visualizzazione alla prima volta
                || empty($settings['dce_views_where_form']) // non ho un form, quindi lo vedo per forza alla prima volta
                || isset($_GET['eid']) // ho eseguito un submit del form
        ) {
            $this->_loop();
        } else {
            if ($settings['dce_views_where_form_ajax']) {
                echo '<div class="dce-view-results dce-view-results-ajax"></div>';
            }
        }
    }

    public function _loop($settings = null) {
        if (!$settings) {
            $settings = $this->get_settings_for_display(null, true); // not parsed because token need to be valued on loop
        }

        global $wpdb, $post, $user, $authordata, $current_user, $wp_query, $in_the_loop;
        global $dce_obj;
        $original_post = $post;
        $original_user = $user;
        $original_current_user = $current_user;
        $original_authordata = $authordata;
        // $original_query = $wp_query; // not working for objects
        $original_queried_object = $wp_query->queried_object;
        $original_queried_object_id = $wp_query->queried_object_id;
        $original_loop = $in_the_loop;              
        
        //var_dump($wp_query->queried_object_id);

        $wrapper_class = 'dce-view-' . $settings['dce_views_style_format'] . ' ' . $settings['dce_views_style_wrapper_class'];
        $element_class = 'dce-view-' . $settings['dce_views_style_format'] . '-element ' . $settings['dce_views_style_entrance_animation'] . ' ' . $settings['dce_views_style_element_class'];
        
        $args = $this->get_wp_query_args();
        //$args["suppress_filters"] = true;  // No posts_orderby filters will be run
        //echo '<pre>'; var_dump($args); echo '</pre>';
        // The Query
        //add_action('pre_get_posts', array($this, 'filter_query'));
        switch ($settings['dce_views_object']) {
            case 'post':
                // https://codex.wordpress.org/Class_Reference/WP_Query
                //$this->the_query = $the_query = new \DynamicContentForElementor\DCE_Query($args);
                $this->the_query = $the_query = new \WP_Query($args);

                // https://developer.wordpress.org/reference/classes/wp_query/get_posts/
                $objects = $the_query->get_posts();
                //unset($args['posts_per_page']);
                //$tmp_query = new \DynamicContentForElementor\DCE_Query($args);
                $total_objects = $the_query->found_posts;
                break;
            case 'user':
                // https://codex.wordpress.org/Class_Reference/WP_User_Query
                $this->the_query = $the_query = $user_query = new \WP_User_Query($args);
                $objects = $the_query->get_results();
                $total_objects = $the_query->get_total();
                //$wp_query->in_the_loop = false;
                break;
            case 'term':
                // https://developer.wordpress.org/reference/classes/wp_term_query/
                $this->the_query = $the_query = $term_query = new \WP_Term_Query($args);
                //$total_objects = (int) $wpdb->get_var( 'SELECT FOUND_ROWS()' );
                $objects = $the_query->get_terms();
                $total_objects = count($objects);
                if (isset($args['number'])) {
                    unset($args['number']);
                    $term_query_totals = new \WP_Term_Query($args);
                    $total_objects = count($term_query_totals->get_terms());
                }
                //$wp_query->in_the_loop = false;
                break;
        }

        // The Loop
        if (!empty($objects)) {
            
            //var_dump($objects); die();

            //$in_the_loop = true;
            // Now wipe it out completely
            //$wp_query = null;
            //$wp_query = $the_query;
            $in_the_loop = $settings['dce_views_object'] != 'term';
            //$wp_query->in_the_loop = $in_the_loop;

            echo '<div class="dce-view-results">';

            switch ($settings['dce_views_style_format']) {
                case 'table':
                    echo '<table class="' . $wrapper_class . (isset($settings['dce_views_style_table_data']) && $settings['dce_views_style_table_data'] ? ' datatable' : '') . '">';
                    if ($settings['dce_views_select_type'] == 'fields') {
                        echo '<thead><tr>';
                        foreach ($settings['dce_views_select_fields'] as $key => $afield) {
                            echo '<th class="dce-view-field-th ' . $afield['dce_views_select_class_label'] . '">' . ($afield['dce_views_select_label'] ? $afield['dce_views_select_label'] : $afield['dce_views_select_field']) . '</th>';
                        }
                        echo '</tr></thead>';
                    }
                    echo '<tbody>';
                    break;
                case 'list':
                    echo '<' . $settings['dce_views_style_list'] . ' class="' . $wrapper_class . '">';
                    break;
                case 'grid':
                default:
                    echo '<div class="dce-view-row ' . $wrapper_class . ' dce-flex">';
            }


            $dce_views_limit_to = ($settings['dce_views_limit_to']) ? $settings['dce_views_limit_to'] : 0;
            if ($settings['dce_views_limit_offset'] && $settings['dce_views_limit_offset'] > 0) {
                $dce_views_limit_to += $settings['dce_views_limit_offset'];
            }

            $k = 0;
            $group_value_prev = false;
            //while ($the_query->have_posts()) {                    

            foreach ($objects as $key => $dce_obj) {

                if ($settings['dce_views_limit_to'] && $k >= $dce_views_limit_to) {
                    break;
                }
                if ($settings['dce_views_limit_offset'] > $k) { // && !$settings['dce_views_pagination']) {
                    $k++;
                    continue;
                }

                switch ($settings['dce_views_object']) {
                    case 'post':
                        //$the_query->the_post();
                        $post = $wp_query->queried_object = $dce_obj; //get_post();                       
                        $dce_obj_id = $wp_query->queried_object_id = $post->ID;
                        break;
                    case 'user':
                        $current_user = $user = $authordata = $wp_query->queried_object = $dce_obj;
                        $dce_obj_id = $wp_query->queried_object_id = $authordata->ID;
                        break;
                    case 'term':
                        $wp_query->queried_object = $dce_obj;
                        $dce_obj_id = $wp_query->queried_object_id = $dce_obj->term_id;
                        break;
                }
                //var_dump($wp_query->queried_object);

                if (!empty($settings['dce_views_group_by_field'])) {
                    $get_meta = 'get_' . $settings['dce_views_object'] . '_meta';
                    $group_value = DCE_Helper::{$get_meta}($post->ID, $settings['dce_views_group_by_field'], true, true);
                    $group_value = DCE_Tokens::replace_var_tokens($settings['dce_views_group_by_field_heading'], 'TITLE', $group_value);
                    if (!empty($settings['dce_views_group_by_field_heading_show'])) {
                        if ($group_value != $group_value_prev) {
                            ?>
                            <<?php echo $settings['dce_views_group_by_heading_size']; ?> class="dce-views-group-title <?php echo $settings['dce_views_group_by_class_heading']; ?>"><?php echo $group_value; ?></<?php echo $settings['dce_views_group_by_heading_size']; ?>>
                            <?php
                            $group_value_prev = $group_value;
                        }
                    }
                }


                $element_class_obj = ' dce-view-element dce-view-element-' . $dce_obj_id;
                $responsive_cols = ' elementor-column elementor-col-' . $settings['dce_views_style_col_width'];
                if ( ! empty( $settings['dce_views_style_col_width_tablet'] ) ) {
                    $responsive_cols .= ' elementor-md-' . $settings['dce_views_style_col_width_tablet'];
                }
                if ( ! empty( $settings['dce_views_style_col_width_mobile'] ) ) {
                    $responsive_cols .= ' elementor-sm-' . $settings['dce_views_style_col_width_mobile'];
                }
                
                switch ($settings['dce_views_select_type']) {
                    case 'fields':
                        switch ($settings['dce_views_style_format']) {
                            case 'table':
                                echo '<tr class="' . $element_class . $element_class_obj . '">';
                                foreach ($settings['dce_views_select_fields'] as $key => $afield) {
                                    echo '<td class="dce-view-field-' . $afield['dce_views_select_field'] . ' ' . $afield['dce_views_select_class_wrapper'] . '"><div class="dce-view-field-value ' . $afield['dce_views_select_class_value'] . '">' . $this->get_field_value($dce_obj, $dce_obj_id, $afield, $settings) . '</div></td>';
                                }
                                echo '</tr>';
                                break;
                            case 'list':
                                echo '<li class="' . $element_class . $element_class_obj . '">';
                                foreach ($settings['dce_views_select_fields'] as $key => $afield) {
                                    echo '<div class="dce-view-field-' . $afield['dce_views_select_field'] . ' ' . $afield['dce_views_select_class_wrapper'] . '">';
                                    if ($afield['dce_views_select_label']) {
                                        if ($afield['dce_views_select_label_inline']) {
                                            echo '<label';
                                        } else {
                                            echo '<div';
                                        }
                                        echo ' class="dce-view-field-label">' . $afield['dce_views_select_label'];
                                        if ($afield['dce_views_select_label_inline']) {
                                            echo '</label>';
                                        } else {
                                            echo '</div>';
                                        }
                                    }
                                    if ($afield['dce_views_select_label'] && $afield['dce_views_select_label_inline']) {
                                        echo '<span';
                                    } else {
                                        echo '<div';
                                    }
                                    echo ' class="dce-view-field-value ' . $afield['dce_views_select_class_value'] . '">' . $this->get_field_value($dce_obj, $dce_obj_id, $afield, $settings) . '</div>';
                                    if ($afield['dce_views_select_label'] && $afield['dce_views_select_label_inline']) {
                                        echo '</span>';
                                    } else {
                                        echo '</div>';
                                    }
                                }
                                echo '</li>';
                                break;
                            case 'grid':
                            default:
                                echo '<div class="dce-view-col ' . $element_class . $element_class_obj . $responsive_cols . '"><div class="dce-block dce-view-single">';
                                foreach ($settings['dce_views_select_fields'] as $key => $afield) {
                                    echo '<div class="dce-view-field-' . $afield['dce_views_select_field'] . ' ' . $afield['dce_views_select_class_wrapper'] . '">';
                                    if ($afield['dce_views_select_label']) {
                                        if ($afield['dce_views_select_label_inline']) {
                                            echo '<label';
                                        } else {
                                            echo '<div';
                                        }
                                        echo ' class="dce-view-field-label ' . $afield['dce_views_select_class_label'] . '">' . $afield['dce_views_select_label'];
                                        if ($afield['dce_views_select_label_inline']) {
                                            echo '</label>';
                                        } else {
                                            echo '</div>';
                                        }
                                    }
                                    if ($afield['dce_views_select_label'] && $afield['dce_views_select_label_inline']) {
                                        echo '<span';
                                    } else {
                                        echo '<div';
                                    }
                                    echo ' class="dce-view-field-value ' . $afield['dce_views_select_class_value'] . '">' . $this->get_field_value($dce_obj, $dce_obj_id, $afield, $settings) . '</div>';
                                    if ($afield['dce_views_select_label'] && $afield['dce_views_select_label_inline']) {
                                        echo '</span>';
                                    } else {
                                        echo '</div>';
                                    }
                                }
                                echo '</div></div>';
                        }
                        break;
                    case 'template':
                        if ($settings['dce_views_style_format'] == 'grid') {
                            echo '<div class="item-page dce-view-col ' . $element_class . $element_class_obj . $responsive_cols . '"><div class="dce-block dce-view-single">';
                        }
                        $tmpl_opt = '';
                        switch ($settings['dce_views_object']) {
                            case 'post': $tmpl_opt = ' post_id="' . $dce_obj_id . '"';
                                break;
                            case 'user': $tmpl_opt = ' author_id="' . $dce_obj_id . '" user_id="' . $dce_obj_id . '"';
                                break;
                            case 'term': $tmpl_opt = ' term_id="' . $dce_obj_id . '"';
                                break;
                        }
                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            $inlinecss = ' inlinecss="true"';
                        } else {
                            $inlinecss = '';
                        }
                        echo do_shortcode('[dce-elementor-template id="' . $settings['dce_views_select_template'] . '"' . $tmpl_opt . $inlinecss . ']');
                        if ($settings['dce_views_style_format'] == 'grid') {
                            echo '</div></div>';
                        }
                        break;
                    case 'text':
                        if ($settings['dce_views_style_format'] == 'grid') {
                            echo '<div class="item-page dce-view-col ' . $element_class . $element_class_obj . $responsive_cols . '"><div class="dce-block dce-view-single">';
                        }
                        if ($settings['dce_views_style_format'] == 'list') {
                            echo '<li class="' . $element_class . $element_class_obj . '">';
                        }
                        $field_value = $settings['dce_views_select_text'];
                        if ($settings['dce_views_object'] == 'user') {
                            $field_value = DCE_Tokens::user_to_author($field_value);
                        }
                        //echo $field_value;
                        //var_dump(get_queried_object());
                        
                        //var_dump(get_class($dce_obj));
                        echo $field_value = DCE_Tokens::replace_var_tokens($field_value, $settings['dce_views_object'], $dce_obj);
                        //echo DCE_Helper::get_dynamic_value($field_value);

                        if ($settings['dce_views_style_format'] == 'grid') {
                            echo '</div></div>';
                        }
                        if ($settings['dce_views_style_format'] == 'list') {
                            echo '</li>';
                        }
                }
                $k++;
            }

            switch ($settings['dce_views_style_format']) {
                case 'table':
                    echo '</tbody></table>';
                    if (isset($settings['dce_views_style_table_data']) && $settings['dce_views_style_table_data']) {
                        ?>
                        <script type="text/javascript">
                            jQuery(document).ready(function () {
                            jQuery('.elementor-element-<?php echo $this->get_id(); ?> table.datatable').DataTable({
                        <?php if ($settings['dce_views_style_table_data_autofill']) { ?>autoFill: true,<?php } ?>
                        <?php if ($settings['dce_views_style_table_data_buttons']) { ?>dom: 'Bfrtip',
                                                    buttons: [
                                                            'copyHtml5',
                                                            'excelHtml5',
                                                            'csvHtml5',
                                                            'pdfHtml5'
                                                    ],<?php } ?>
                        <?php if ($settings['dce_views_style_table_data_colreorder']) { ?>colReorder: true,<?php } ?>
                        <?php if ($settings['dce_views_style_table_data_fixedcolumns']) { ?>fixedColumns: true,<?php } ?>
                        <?php if ($settings['dce_views_style_table_data_fixedheader']) { ?>fixedHeader: true,<?php } ?>
                        <?php if ($settings['dce_views_style_table_data_keytable']) { ?>keys: true,<?php } ?>
                        <?php if ($settings['dce_views_style_table_data_responsive']) { ?>responsive: true,<?php } ?>
                        <?php if ($settings['dce_views_style_table_data_rowgroup']) { ?>rowGroup: {
                                            dataSrc: 'group'
                                            },<?php } ?>
                        <?php if ($settings['dce_views_style_table_data_rowreorder']) { ?>rowReorder: true,<?php } ?>
                        <?php if ($settings['dce_views_style_table_data_scroller']) { ?>scroller: true,
                                                    scrollY: 200,
                                                    paging: true,
                                                    deferRender: true,<?php } else { ?>
                                            paging: false,
                        <?php } ?>
                        <?php if ($settings['dce_views_style_table_data_select']) { ?>select: true,<?php } ?>

                                        ordering: true,
                                        });
                                        });</script>
                        <?php
                    }
                    break;
                case 'list':
                    echo '</ul>';
                    break;
                case 'grid':
                default:
                    echo '</div>';
            }

            echo '</div>';

            if (!empty($settings['dce_views_pagination'])) {
                $this->_nav($the_query, $settings, $total_objects);
            }

            //$in_the_loop = false;
            //$wp_query->in_the_loop = $in_the_loop;
            $wp_query->queried_object = $original_queried_object;
            $wp_query->queried_object_id = $original_queried_object_id;
            $post = $original_post;
            $user = $original_user;
            $current_user = $original_current_user;
            $authordata = $original_authordata;
            //var_dump($wp_query->queried_object_id);

            if ($settings['dce_views_object'] == 'post') {
                /* Restore original Post Data */
                wp_reset_postdata();
            }
        } else {

            // no posts found
            if (isset($settings['dce_views_fallback']) && $settings['dce_views_fallback']) {
                echo '<div class="dce-view-results dce-view-results-fallback dce-views-no-results">';
                if (isset($settings['dce_views_fallback_type']) && $settings['dce_views_fallback_type'] == 'template') {
                    $fallback_content = '[dce-elementor-template id="' . $settings['dce_views_fallback_template'] . '"]';
                } else {
                    $fallback_content = __($settings['dce_views_fallback_text'], 'dynamic-content-for-elementor' . '_texts');
                }
                $fallback_content = DCE_Helper::get_dynamic_value($fallback_content);
                echo $fallback_content;
                echo '</div>';
            } else {
                if ($settings['dce_views_where_form_ajax']) {
                    echo '<div class="dce-view-results dce-view-results-ajax"></div>';
                }
            }
        }

        $post = $original_post;
        $authordata = $original_user;
        $in_the_loop = $original_loop;
        // Restore original query object
        /*
          $wp_query = null;
          $wp_query = $original_query;
          $wp_query->in_the_loop = $in_the_loop;
          $wp_query->queried_object = null;
          $wp_query->queried_object_id = null;
          $queried_object = get_queried_object();
          //var_dump($queried_object);
         */
        return true;
    }

    public function _exposed_sort($settings = null) {
        if (!$settings) {
            $settings = $this->get_settings_for_display();
        }

        if (!$settings['dce_views_order_random']) {
            if (isset($settings['dce_views_order_by']) && !empty($settings['dce_views_order_by'])) {
                $options = '';
                $i = 0;
                foreach ($settings['dce_views_order_by'] as $key => $asort) {
                    if (!$i) {
                        $order_field = $asort['dce_views_order_field'];
                        $order_sort = $asort['dce_views_order_field_sort'];
                        if (isset($_GET['orderby'])) {
                            list($order_sort, $order_field) = explode('__', $_GET['orderby'], 2);
                        }
                    }
                    if ($asort['dce_views_order_field_sort_exposed']) {
                        $options .= '<option value="ASC__' . $asort['dce_views_order_field'] . '"' . ($asort['dce_views_order_field'] == $order_field && $order_sort == 'ASC' ? ' selected' : '') . '>' . DCE_Helper::get_post_meta_name($asort['dce_views_order_field']) . ' ASC</option>';
                        $options .= '<option value="DESC__' . $asort['dce_views_order_field'] . '"' . ($asort['dce_views_order_field'] == $order_field && $order_sort == 'DESC' ? ' selected' : '') . '>' . DCE_Helper::get_post_meta_name($asort['dce_views_order_field']) . ' DESC</option>';
                    }
                }
                if ($options) {
                    $form_action = '';
                    if (isset($_GET['page_id'])) {
                        $form_action = '?page_id=' . $_GET['page_id'];
                    }
                    if (isset($_GET['p'])) {
                        $form_action = '?p=' . $_GET['p'];
                    }
                    ?>
                    <form action="<?php echo $form_action; ?>" method="get" class="dce-view-exposed-sort <?php echo $settings['dce_views_order_class']; ?>">
                        <?php if (isset($_GET['page_id'])) { ?>
                            <input type="hidden" name="page_id" value="<?php echo $_GET['page_id']; ?>">
                        <?php } ?>
                        <?php if (isset($_GET['p'])) { ?>
                            <input type="hidden" name="p" value="<?php echo $_GET['p']; ?>">
                        <?php } ?>
                        <?php if ($settings['dce_views_order_label']) { ?>
                            <label for="order_<?php echo $this->get_id(); ?>">
                                <?php echo $settings['dce_views_order_label']; ?>
                            </label>
                        <?php } ?>
                        <select class="dce-input-sort" id="order_<?php echo $this->get_id(); ?>" name="orderby" onchange="jQuery(this).closest('form').submit();">
                            <?php echo $options; ?>
                        </select>
                        <?php
                        //$params = explode('&', $_SERVER['']);
                        if (!empty($_GET) && isset($_GET['eid']) && $_GET['eid'] == $this->get_id()) {
                            foreach ($_GET as $gkey => $gval) {
                                //$val = explode('=', $aparam, 2);
                                if ($gkey != 'eid' && $gkey != 'orderby') {
                                    echo '<input type="hidden" name="' . $gkey . '" value="' . $gval . '">';
                                }
                            }
                        }
                        ?>
                        <input type="hidden" name="eid" value="<?php echo $this->get_id(); ?>">
                        <?php if (isset($_GET['page_id'])) { ?>
                            <input type="hidden" name="page_id" value="<?php echo $_GET['page_id']; ?>">
                        <?php } ?>
                        <?php if (isset($_GET['p'])) { ?>
                            <input type="hidden" name="p" value="<?php echo $_GET['p']; ?>">
                        <?php } ?>
                        ?>
                    </form>
                    <?php
                }
            }
        }
    }

    public function _exposed_form($settings = null) {
        if (!$settings) {
            $settings = $this->get_settings_for_display();
        }

        if ((isset($settings['dce_views_where_form']) && !empty($settings['dce_views_where_form']))) {
            $form_action = '';
            if (isset($_GET['page_id'])) {
                $form_action = '?page_id=' . $_GET['page_id'];
            }
            if (isset($_GET['p'])) {
                $form_action = '?p=' . $_GET['p'];
            }
            ?>
            <div class="dce-view-form-wrapper dce-view-exposed-form elementor-button-align-<?php echo ($settings['buttons_align'] == 'justify') ? 'stretch' : 'start'; ?> <?php echo $settings['dce_views_where_form_class_wrapper']; ?>">
                <?php if ($settings['dce_views_style_form_text']) { ?>
                    <<?php echo $settings['dce_views_style_form_text_size']; ?> class="dce-views-form-title"><?php echo $settings['dce_views_style_form_text']; ?></<?php echo $settings['dce_views_style_form_text_size']; ?>>
                <?php } ?>
                <form id="dce-view-form-<?php echo $this->get_id(); ?>" method="get" action="<?php echo $form_action; ?>" class="elementor-view-fields-wrapper dce-view-form <?php echo $settings['dce_views_where_form_class']; ?>">
                    <?php if (isset($_GET['page_id'])) { ?>
                        <input type="hidden" name="page_id" value="<?php echo $_GET['page_id']; ?>">
                    <?php } ?>
                    <?php if (isset($_GET['p'])) { ?>
                        <input type="hidden" name="p" value="<?php echo $_GET['p']; ?>">
                    <?php } ?>
                        
                    <div class="dce-view-fields-wrapper dce-flex"> 
                    <?php
                    foreach ($settings['dce_views_where_form'] as $key => $afield) {
                        if (!$afield['dce_views_where_form_field'])
                            continue;

                        $taxonomy = false;
                        if (substr($afield['dce_views_where_form_field'], 0, 9) == 'taxonomy_') {
                            $taxonomy = substr($afield['dce_views_where_form_field'], 9);
                        }
                        $auto_label = $taxonomy ? $this->taxonomies[$taxonomy] : DCE_Helper::get_post_meta_name($afield['dce_views_where_form_field']);

                        $filter_class = 'elementor-field-group elementor-column elementor-col-' . $afield['dce_views_where_form_width'];
                        if (!empty($afield['dce_views_where_form_width_tablet'])) {
                            $filter_class .= ' elementor-md-' . $afield['dce_views_where_form_width_tablet'];
                        }
                        if (!empty($afield['dce_views_where_form_width_mobile'])) {
                            $filter_class .= ' elementor-sm-' . $afield['dce_views_where_form_width_mobile'];
                        }

                        /* if ( $afield['dce_views_where_form_allow_multiple'] ) {
                          $filter_class .= ' elementor-field-type-' . $afield['dce_views_where_form_type'] . '-multiple';
                          } */
                        ?>
                        <div class="dce-view-field-wrapper <?php echo $filter_class; ?> <?php echo $settings['dce_views_where_form_class_filter']; ?>">
                            <div class="dce-view-field-filter dce-view-form-col-inner">
                                <label class="elementor-field-label dce-view-input-label <?php echo $afield['dce_views_where_form_class_label']; ?>" for="dce_view_<?php echo $afield['dce_views_where_form_field']; ?>">
                                    <?php echo (isset($afield['dce_views_where_form_label']) && $afield['dce_views_where_form_label']) ? $afield['dce_views_where_form_label'] : $auto_label; ?>
                                    <?php if ($afield['dce_views_where_form_required'] && $afield['dce_views_where_form_required_label'] != 'none') { ?>
                                        <span class="dce-form-required">
                                            <?php if ($afield['dce_views_where_form_required_label'] == 'asterisk') { ?>*<?php } ?>
                                            <?php
                                            if ($afield['dce_views_where_form_required_label'] == 'text') {
                                                echo $afield['dce_views_where_form_required_label_text'];
                                            }
                                            ?>
                                        </span>
                                    <?php } ?>
                                </label>
                                <?php
                                $input_values = array();
                                $presel = DCE_Helper::str_to_array(',', $afield['dce_views_where_form_preselect']);
                                $dce_views_where_form_type = $afield['dce_views_where_form_type'];


                                $options = explode(PHP_EOL, $afield['dce_views_where_form_value']);
                                $options = array_filter($options);
                                if (!empty($options)) {
                                    foreach ($options as $akey => $aopt) {
                                        $aopt = trim($aopt);
                                        $option = explode('|', $aopt, 2);
                                        $akey = trim(reset($option));
                                        $avalue = end($option);
                                        $asel = false;
                                        if (in_array($akey, $presel)) {
                                            $asel = true;
                                        }
                                        $input_values[] = array('key' => $akey, 'value' => $avalue, 'selected' => $asel);
                                    }
                                }

                                if (empty($input_values)) {

                                    if ($taxonomy) {
                                        // TAXONOMY
                                        if (empty(trim($afield['dce_views_where_form_value']))) {
                                            $taxonomies_terms = DCE_Helper::get_taxonomy_terms($taxonomy); //$this->taxonomies_terms[$afield['dce_views_where_form_field']];
                                            foreach ($taxonomies_terms as $akey => $avalue) {
                                                if ($akey) {
                                                    $asel = false;
                                                    $pezzi = explode(' (', $avalue);
                                                    $term_title = reset($pezzi);
                                                    $pezzi = explode(')', str_replace('(', ')', $avalue));
                                                    if (count($pezzi) > 2) {
                                                        $term_slug = $pezzi[count($pezzi) - 2];
                                                        //$akey = $term_slug;
                                                        if (in_array($term_slug, $presel)) {
                                                            $asel = true;
                                                        }
                                                    }
                                                    if (in_array($akey, $presel)) {
                                                        $asel = true;
                                                    }
                                                    $input_values[] = array('key' => $akey, 'value' => $term_title, 'selected' => $asel);
                                                }
                                            }
                                        }
                                        if ($afield['dce_views_where_form_type'] == 'auto') {
                                            $dce_views_where_form_type = 'select';
                                        }
                                    } else {
                                        // ACF
                                        if (DCE_Helper::is_plugin_active('acf')) {
                                            $field_conf = get_field_object($afield['dce_views_where_form_field']);
                                            if ($field_conf && isset($field_conf['choices']) && !empty($field_conf['choices'])) {
                                                foreach ($field_conf['choices'] as $akey => $avalue) {
                                                    $asel = (in_array($akey, $field_conf['default_value'])) ? true : false;
                                                    $input_values[] = array('key' => $akey, 'value' => $avalue, 'selected' => $asel);
                                                }
                                                if ($afield['dce_views_where_form_type'] == 'auto') {
                                                    $afield['dce_views_where_form_type'] = $field_conf['type'];
                                                    if ($field_conf['type'] == 'true_false') {
                                                        $afield['dce_views_where_form_type'] = 'checkbox';
                                                    }
                                                    if ($field_conf['type'] == 'button_group') {
                                                        $afield['dce_views_where_form_type'] = 'radio';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                if (!$afield['dce_views_where_form_required']) {
                                    if ($dce_views_where_form_type == 'select') {
                                        array_unshift($input_values, array('key' => '', 'value' => $afield['dce_views_where_form_required_empty_label'], 'selected' => false));
                                    }
                                }

                                if (empty($input_values)) {
                                    // TEXT FALLBACK
                                    if ($afield['dce_views_where_form_type'] == 'auto') {
                                        $afield['dce_views_where_form_type'] = 'text';
                                    }
                                }

                                switch ($dce_views_where_form_type) {
                                    case 'select':
                                        ?>
                                        <span class="dce-view-input dce-view-select <?php echo $afield['dce_views_where_form_class_input']; ?>">
                                            <select class="elementor-field elementor-field-textual elementor-size-<?php echo $settings['dce_views_input_size']; ?>" name="<?php echo $afield['dce_views_where_form_field']; ?>" id="dce_view_<?php echo $afield['dce_views_where_form_field']; ?>"<?php if ($afield['dce_views_where_form_required']) { ?> required<?php } ?>>
                                                <?php
                                                foreach ($input_values as $aopt) {
                                                    ?>
                                                    <option value="<?php echo $aopt['key']; ?>"<?php echo ((isset($_GET[$afield['dce_views_where_form_field']]) && $_GET[$afield['dce_views_where_form_field']] == $aopt['key']) || (!isset($_GET[$afield['dce_views_where_form_field']]) && $aopt['selected']) ? ' selected' : ''); ?>>
                                                        <?php echo $aopt['value']; ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </span>
                                        <?php
                                        break;
                                    case 'radio':
                                        $html_tag = $afield['dce_views_where_form_field_inline'] ? 'span' : 'div';
                                        foreach ($input_values as $okey => $aopt) {
                                            $checked = (isset($_GET[$afield['dce_views_where_form_field']]) && $_GET[$afield['dce_views_where_form_field']] == $aopt['key']) || (empty($_GET[$afield['dce_views_where_form_field']]) && $aopt['selected']) ? ' checked' : '';
                                            ?>
                                            <<?php echo $html_tag; ?> class="dce-view-input dce-view-radio <?php echo $afield['dce_views_where_form_class_input']; ?>">
                                                <input type="radio" value="<?php echo $aopt['key']; ?>" name="<?php echo $afield['dce_views_where_form_field']; ?>" id="dce_view_<?php echo $afield['dce_views_where_form_field'] . '_' . $okey; ?>"<?php echo $checked; ?><?php if ($afield['dce_views_where_form_required']) { ?> required<?php } ?>>
                                                <label for="dce_view_<?php echo $afield['dce_views_where_form_field'] . '_' . $okey; ?>"><?php echo $aopt['value']; ?></label>
                                            </<?php echo $html_tag; ?>>
                                            <?php
                                        }
                                        break;
                                    /* case 'textarea':
                                      break; */
                                    case 'checkbox':
                                        $html_tag = $afield['dce_views_where_form_field_inline'] ? 'span' : 'div';
                                        foreach ($input_values as $okey => $aopt) {
                                            $checked = (isset($_GET[$afield['dce_views_where_form_field']]) && $_GET[$afield['dce_views_where_form_field']] == $aopt['key']) || (empty($_GET[$afield['dce_views_where_form_field']]) && $aopt['selected']) ? ' checked' : '';
                                            ?>
                                            <<?php echo $html_tag; ?> class="dce-view-input dce-view-checkbox <?php echo $afield['dce_views_where_form_class_input']; ?>">
                                                <input type="checkbox" value="<?php echo $aopt['key']; ?>" name="<?php echo $afield['dce_views_where_form_field']; ?>[]" id="dce_view_<?php echo $afield['dce_views_where_form_field'] . '_' . $okey; ?>"<?php echo $checked; ?><?php if ($afield['dce_views_where_form_required']) { ?> required<?php } ?>>
                                                <label for="dce_view_<?php echo $afield['dce_views_where_form_field'] . '_' . $okey; ?>"><?php echo $aopt['value']; ?></label>
                                            </<?php echo $html_tag; ?>>
                                            <?php
                                        }
                                        break;
                                    case 'text':
                                    default:
                                        ?>
                                        <span class="dce-view-input dce-view-text <?php echo $afield['dce_views_where_form_class_input']; ?>">
                                            <input class="elementor-field elementor-field-textual elementor-size-<?php echo $settings['dce_views_input_size']; ?>" type="text" placeholder="<?php echo $afield['dce_views_where_form_placeholder']; ?>" value="<?php echo isset($_GET[$afield['dce_views_where_form_field']]) ? $_GET[$afield['dce_views_where_form_field']] : $afield['dce_views_where_form_preselect']; ?>" name="<?php echo $afield['dce_views_where_form_field']; ?>" id="dce_view_<?php echo $afield['dce_views_where_form_field']; ?>"<?php if ($afield['dce_views_where_form_required']) { ?> required<?php } ?>>
                                        </span>
                                    <?php
                                }
                                ?>
                                <?php if ($afield['dce_views_where_form_hint']) { ?>
                                    <small class="dce-view-input-hint"><i class="fa fa-info" aria-hidden="true"></i> <?php echo $afield['dce_views_where_form_hint']; ?></small>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                    }
                    $action_class = 'elementor-field-group elementor-column elementor-col-' . $settings['dce_views_where_form_action_width'];
                    if (!empty($settings['dce_views_where_form_action_width_tablet'])) {
                        $action_class .= ' elementor-md-' . $settings['dce_views_where_form_action_width_tablet'];
                    }
                    if (!empty($settings['dce_views_where_form_action_width_mobile'])) {
                        $action_class .= ' elementor-sm-' . $settings['dce_views_where_form_action_width_mobile'];
                    }
                    ?>
                    <input type="hidden" name="eid" value="<?php echo $this->get_id(); ?>">
                    </div>
                    <?php
                    if (!$settings['dce_views_where_form_ajax_nobutton']) {
                    ?>
                    <div class="dce-view-exposed-form-action <?php echo $action_class; ?> <?php echo $settings['dce_views_where_form_class_buttons']; ?>">
                        <div class="dce-view-exposed-form-buttons elementor-field-type-submit dce-view-form-col-inner">                            
                            <button class="button dce-button elementor-button elementor-size-<?php echo $settings['dce_views_input_size']; ?> find <?php echo ($settings['buttons_align'] == 'justify') ? 'dce-block' : ''; ?> <?php echo $settings['dce_views_where_form_class_button']; ?>" type="submit"><span class="elementor-button-text"><?php echo $settings['dce_views_style_form_submit_text']; ?></span></button>
                            <?php if ($settings['dce_views_where_form_reset']) { ?><input class="button dce-button elementor-button elementor-size-<?php echo $settings['dce_views_input_size']; ?> reset <?php echo $settings['dce_views_where_form_class_button']; ?>" type="reset" value="<?php _e('Reset'); ?>"><?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </form>
            </div>
            <?php
            $this->_ajax($settings);
        }
    }

    public function _ajax($settings = null) {
        if (!$settings['dce_views_where_form_ajax'])
            return false;
        ?>
        <script>
            function dce_views_update_result() {
            //var result_container = '.elementor-element-<?php echo $this->get_id(); ?> .elementor-widget-container';
            var result_container = '.elementor-element-<?php echo $this->get_id(); ?> .dce-view-results';
            var sort_container = '.elementor-element-<?php echo $this->get_id(); ?> .dce-view-exposed-sort';
            var pagination_container = '.elementor-element-<?php echo $this->get_id(); ?> .dce-posts-pagination';
            jQuery(result_container).html('<div class="dce-preloader" style="text-align: center;"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>');
            var results = jQuery.get('?' + jQuery('#dce-view-form-<?php echo $this->get_id(); ?>').serialize(), function (data) {
            //console.log(data);
            jQuery(result_container).html(jQuery(data).find(result_container).html());
            jQuery(sort_container).html(jQuery(data).find(sort_container).html());
            jQuery(pagination_container).html(jQuery(data).find(pagination_container).html());
            });
            }
            jQuery(document).ready(function () {
            jQuery('#dce-view-form-<?php echo $this->get_id(); ?>').on('submit', function () {
            dce_views_update_result();
            return false;
            });
        <?php if ($settings['dce_views_where_form_ajax_onchange']) { ?>
                jQuery('#dce-view-form-<?php echo $this->get_id(); ?> input, #dce-view-form-<?php echo $this->get_id(); ?> select').on('change', function () {
                dce_views_update_result();
                return false;
                });
                jQuery('#dce-view-form-<?php echo $this->get_id(); ?> input[type=text]').on('keyup', function () {
                if (jQuery(this).val().length > 3) {
                dce_views_update_result();
                }
                return false;
                });
        <?php } ?>
            });</script>
        <?php
        return true;
    }

    public function _nav($the_query = null, $settings = array(), $total_objects = 0) {

        if (empty($settings)) {
            $settings = $this->get_settings_for_display();
        }
        if (empty($settings)) {
            return false;
        }
        switch ($settings['dce_views_object']) {
            case 'post':
                $max = intval($the_query->max_num_pages);
                break;
            case 'user':
                $max = ($settings['dce_views_post_per_page']) ? ceil($total_objects / $settings['dce_views_post_per_page']) : 0;
                break;
            case 'term':
                $max = ($settings['dce_views_post_per_page']) ? ceil($total_objects / $settings['dce_views_post_per_page']) : 0;
                break;
        }

        $dce_views_pagination_page_limit = intval($settings['dce_views_pagination_page_limit']);
        if ($dce_views_pagination_page_limit && $dce_views_pagination_page_limit < $max) {
            $max = $dce_views_pagination_page_limit;
        }
        //var_dump($total_objects);
        if ($max <= 1)
            return;

        $paged = $this->get_current_page();


        /** Add current page to the array */
        if ($paged >= 1)
            $links[] = $paged;

        /** Add the pages around the current page to the array */
        if ($paged >= 3) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }

        if (( $paged + 2 ) <= $max) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }
        ?>
        <nav class="navigation posts-navigation dce-posts-navigation elementor-pagination" role="navigation" arial-label="<?php _e('Pagination'); ?>">
            <ul class="dce-page-numbers">
        <?php
        if (empty($settings['dce_views_pagination_type']) || $settings['dce_views_pagination_type'] == 'prev_next' || $settings['dce_views_pagination_type'] == 'numbers_and_prev_next') {
            /** Previous Post Link */
            if ($paged > 1) {
                echo '<li><a class="page-numbers pagination__prev" href="' . $this->get_posts_link('prev') . '">';
                if (empty($settings['dce_views_pagination_prev_label'])) {
                    echo '&lt;';
                } else {
                    echo $settings['dce_views_pagination_prev_label'];
                }
                echo '</a></li> ';
            }
        }

        if (empty($settings['dce_views_pagination_type']) || $settings['dce_views_pagination_type'] == 'numbers' || $settings['dce_views_pagination_type'] == 'numbers_and_prev_next') {

            if ($settings['dce_views_pagination_numbers_shorten']) {

                /** Link to first page, plus ellipses if necessary */
                if (!in_array(1, $links)) {
                    $class = 1 == $paged ? ' current' : '';

                    printf('<li><a class="page-numbers%s" href="%s">%s</a></li>' . "\n", $class, esc_url($this->get_posts_link('first')), '1');

                    if (!in_array(2, $links))
                        echo '<li class="dots">…</li>';
                }

                /** Link to current page, plus 2 pages in either direction if necessary */
                sort($links);
                foreach ((array) $links as $link) {
                    $class = $paged == $link ? ' current' : '';
                    printf('<li><a class="page-numbers%s" href="%s">%s</a></li>' . "\n", $class, esc_url($this->get_posts_link('current', $link)), $link);
                }

                /** Link to last page, plus ellipses if necessary */
                if (!in_array($max, $links)) {
                    if (!in_array($max - 1, $links))
                        echo '<li class="dots">…</li>' . "\n";

                    $class = $paged == $max ? ' current' : '';
                    printf('<li><a class="page-numbers%s" href="%s">%s</a></li>' . "\n", $class, esc_url($this->get_posts_link('last', $max)), $max);
                }
            } else {

                for ($p = 1; $p <= $max; $p++) {
                    $class = $paged == $p ? ' current' : '';
                    printf('<li><a class="page-numbers%s" href="%s">%s</a></li>' . "\n", $class, esc_url($this->get_posts_link('', $p)), $p);
                }
            }
        }

        if (empty($settings['dce_views_pagination_type']) || $settings['dce_views_pagination_type'] == 'prev_next' || $settings['dce_views_pagination_type'] == 'numbers_and_prev_next' || $settings['dce_views_pagination_type'] == 'infinite_scroll') {
            /** Next Post Link */
            if ($paged < $max) {
                echo '<li><a class="page-numbers pagination__next" href="' . $this->get_posts_link() . '">';
                if (empty($settings['dce_views_pagination_next_label'])) {
                    echo '&gt;';
                } else {
                    echo $settings['dce_views_pagination_next_label'];
                }
                echo '</a></li>';
            }
        }
        ?>
            </ul>
        </nav>
                <?php
                $this->_infinite($settings);
            }

            public function _infinite($settings = array()) {
                if ($settings['dce_views_pagination']) {
                    if ($settings['dce_views_pagination_type'] == 'infinite_scroll') {
                        ?>
                <!-- status elements -->
                <div class="scroller-status">
                    <div class="infinite-scroll-request loader-ellips" style="text-align: center;">
                        <br>
                        <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="infinite-scroll-last"><?php echo $settings['dce_views_limit_scroll_last']; ?></p>
                    <p class="infinite-scroll-error" style="text-align: center;"><a class="infinite__next" href="<?php echo $this->get_posts_link(); ?>"><i class="fa fa-angle-double-down" aria-hidden="true"></i></a></p>
                </div>
                <script>
                    jQuery(window).load(function () {
                    jQuery('.elementor-element-<?php echo $this->get_id(); ?> .dce-view-row').infiniteScroll({
                    // options
                    path: '.elementor-element-<?php echo $this->get_id(); ?> .pagination__next',
                            //path: '?pag={{#}}&eid=<?php echo $this->get_id(); ?>',
                            append: '.elementor-element-<?php echo $this->get_id(); ?> .dce-view-row .item-page',
                            history: 'replace',
                            hideNav: '.elementor-element-<?php echo $this->get_id(); ?> .dce-posts-navigation',
                            status: '.elementor-element-<?php echo $this->get_id(); ?> .scroller-status',
                            debug: true,
                    });
                    });
                </script>
                <?php
                return true;
            }
        }
        return false;
    }

    public function get_current_page() {
        return isset($_GET['pag']) && isset($_GET['eid']) && $_GET['eid'] == $this->get_id() ? absint($_GET['pag']) : 1;
    }

    public function get_posts_link($verso = 'next', $page = 1) {
        global $wp_query;
        $current_url = home_url(add_query_arg(array(), $wp_query->request));
        $paged = $this->get_current_page();
        switch ($verso) {
            case 'next':
                $page = $paged + 1;
                break;
            case 'prev':
                $page = $paged - 1;
                break;
            case 'current':
            case 'first':
            case 'last':
        }
        $ret = $current_url . '/?';
        if (!empty($_GET) && isset($_GET['eid'])) {
            foreach ($_GET as $gkey => $gval) {
                if ($gkey != 'pag' && $gkey != 'eid') {
                    $ret .= '&' . $gkey . '=' . $gval;
                }
            }
        }
        if ($ret != $current_url . '/?') {
            $ret .= '&';
        }
        $ret .= 'eid=' . $this->get_id();
        $ret .= '&pag=' . $page;

        if (isset($_GET['page_id'])) {
            $ret .= '&page_id=' . $_GET['page_id'];
        }
        if (isset($_GET['p'])) {
            $ret .= '&p=' . $_GET['p'];
        }
        return $ret;
    }

    public function get_wp_query_args($settings = null) {
        if (!$settings) {
            $settings = $this->get_settings_for_display();
        }

        if (empty($this->taxonomies)) {
            $this->taxonomies = $taxonomies = DCE_Helper::get_taxonomies();
            /* $taxonomies_terms = array();
              foreach ($taxonomies as $tkey => $atax) {
              $taxonomies_terms[$tkey] = DCE_Helper::get_taxonomy_terms($tkey);
              }
              $this->taxonomies_terms = $taxonomies_terms; */
        }

        $args = array(
                //'ignore_sticky_posts' => 1,
                //'post__not_in' => array_merge($posts_excluded, $exclude_io),
        );

// FROM
        if ($settings['dce_views_object'] == 'post') {
            if (isset($settings['dce_views_from_dynamic']) && $settings['dce_views_from_dynamic']) {
                global $wp_query;
                if (is_archive()) {
                    $args = $wp_query->query_vars;
                } else {
                    $post_id = $wp_query->query_vars['p'];
                    $taxonomies = get_post_taxonomies($post_id);
                    $terms = array();
                    foreach ($taxonomies as $atax) {
                        $terms = $terms + wp_get_post_terms($post_id, $atax);
                    }


                    if (empty($terms)) {
// same type
                        $cpt = get_post_type();
                        $args['post_type'] = $cpt;
                    } else {
// same taxonomy terms associated
                        foreach ($terms as $akey => $aterm) {
                            $tkey = $aterm->taxonomy;
                            switch ($tkey) {
                                case 'category':
                                    if (isset($args['tag__in'])) {
                                        $args['category__in'] = array_merge($args['category__in'], array($aterm->term_id));
                                    } else {
                                        $args['category__in'] = array($aterm->term_id);
                                    }
                                    break;

                                case 'post_tag':
                                    if (isset($args['tag__in'])) {
                                        $args['tag__in'] = array_merge($args['tag__in'], array($aterm->term_id));
                                    } else {
                                        $args['tag__in'] = array($aterm->term_id);
                                    }
                                    break;

                                default:
                                    if (isset($args['tax_query'][$tkey])) {
                                        $args['tax_query'][$tkey] = array(
                                            'taxonomy' => $tkey,
                                            'field' => 'term_id',
                                            'terms' => array_merge($args['tax_query'][$tkey]['terms'], array($aterm->term_id)),
                                            'operator' => 'IN', // optional??
                                        );
                                    } else {
                                        $args['tax_query'][$tkey] = array(
                                            'taxonomy' => $tkey,
                                            'field' => 'term_id',
                                            'terms' => array($aterm->term_id),
                                            'operator' => 'IN', // optional??
                                        );
                                    }
                            }
                        }
                    }
// exclude himself
                    $args['post__not_in'] = array($post_id);
                }
            } else {

                if (!empty($settings['dce_views_cpt'])) {
                    if (count($settings['dce_views_cpt']) > 1) {
                        $args['post_type'] = $settings['dce_views_cpt'];
                        if (in_array('custom', $settings['dce_views_cpt'])) {
                            $args['post_type'] = array_merge($settings['dce_views_cpt'], DCE_Helper::str_to_array(',', $settings['dce_views_cpt_custom']));
                        }
                    } else {
                        if (is_array($settings['dce_views_cpt'])) {
                            $args['post_type'] = reset($settings['dce_views_cpt']);
                        } else {
                            $args['post_type'] = $settings['dce_views_cpt'];
                        }
                        if ($args['post_type'] == 'custom') {
                            $args['post_type'] = $settings['dce_views_cpt_custom'];
                        }
                    }
                }
                if (!empty($settings['dce_views_status'])) {
                    if (count($settings['dce_views_status']) > 1) {
                        $args['post_status'] = $settings['dce_views_status'];
                    } else {
                        $args['post_status'] = reset($settings['dce_views_status']);
                        if ($settings['dce_views_cpt'] == array('attachment')) {
                            $args['post_status'] = 'any';
                        }
                    }
                } elseif ($settings['dce_views_cpt'] == array('attachment')) {
                    $args['post_status'] = 'any';
                }

// FROM - filter by taxonomy term
                foreach ($this->taxonomies as $tkey => $atax) {
                    if (!empty($settings['dce_views_term_' . $tkey])) {
                        switch ($tkey) {
                            case 'category':
                                $args['category__in'] = array_map('intval', $settings['dce_views_term_' . $tkey]);
                                break;

                            case 'post_tag':
                                $args['tag__in'] = array_map('intval', $settings['dce_views_term_' . $tkey]);
//$args['category__not_in'] = $settings['dce_views_term_' . $tkey];
                                break;

                            default:
                                if ($tkey) {// && !empty($this->taxonomies_terms[$tkey])) {
                                    $args['tax_query'][] = array(
                                        'taxonomy' => $tkey,
                                        'field' => 'term_id',
                                        'terms' => array_map('intval', $settings['dce_views_term_' . $tkey]),
                                        'operator' => 'IN', // optional??
                                    );
                                }
                        }
                    }
                }
                if (isset($args['tax_query'])) {
                    $args['tax_query']['relation'] = $settings['dce_views_tax_relation'];
                }
            }
        }

        // PAGINATION
        if ($settings['dce_views_object'] == 'post') {
            if ($settings['dce_views_pagination']) {
                if ($settings['dce_views_post_per_page'] > 0) {
                    $args['posts_per_page'] = $settings['dce_views_post_per_page'];
                }
            } else {
                $args['nopaging'] = true;
                $args['posts_per_page'] = -1;
            }
        } else {
            if ($settings['dce_views_pagination']) {
                if ($settings['dce_views_post_per_page'] > 0) {
                    $args['number'] = $settings['dce_views_post_per_page'];
                }
                if ($settings['dce_views_object'] == 'term') {
                    if ($this->get_current_page()) {
                        $args['offset'] = $settings['dce_views_limit_offset'] + ($settings['dce_views_post_per_page'] * ($this->get_current_page() - 1));
                    }
                }
            } else {
                $args['number'] = 0;
            }
        }


        if ($settings['dce_views_object'] == 'user') {
            /* $defaults = array(
              92	                        'blog_id'             => get_current_blog_id(),
              93	                        'role'                => '',
              94	                        'role__in'            => array(),
              95	                        'role__not_in'        => array(),
              96	                        'meta_key'            => '',
              97	                        'meta_value'          => '',
              98	                        'meta_compare'        => '',
              99	                        'include'             => array(),
              100	                        'exclude'             => array(),
              101	                        'search'              => '',
              102	                        'search_columns'      => array(),
              103	                        'orderby'             => 'login',
              104	                        'order'               => 'ASC',
              105	                        'offset'              => '',
              106	                        'number'              => '',
              107	                        'paged'               => 1,
              108	                        'count_total'         => true,
              109	                        'fields'              => 'all',
              110	                        'who'                 => '',
              111	                        'has_published_posts' => null,
              112	                        'nicename'            => '',
              113	                        'nicename__in'        => array(),
              114	                        'nicename__not_in'    => array(),
              115	                        'login'               => '',
              116	                        'login__in'           => array(),
              117	                        'login__not_in'       => array(),
              118	                );
             */
            if (!empty($settings['dce_views_role'])) {
                $args['role__in'] = $settings['dce_views_role'];
            }
            if (!empty($settings['dce_views_role_exclude'])) {
                $args['role__not_in'] = $settings['dce_views_role_exclude'];
            }
        }

        if ($settings['dce_views_object'] == 'term') {
            /* $query_var_defaults = array(
              'taxonomy'               => null,
              'object_ids'             => null,
              'orderby'                => 'name',
              'order'                  => 'ASC',
              'hide_empty'             => true,
              'include'                => array(),
              'exclude'                => array(),
              'exclude_tree'           => array(),
              'number'                 => '',
              'offset'                 => '',
              'fields'                 => 'all',
              'count'                  => false,
              'name'                   => '',
              'slug'                   => '',
              'term_taxonomy_id'       => '',
              'hierarchical'           => true,
              'search'                 => '',
              'name__like'             => '',
              'description__like'      => '',
              'pad_counts'             => false,
              'get'                    => '',
              'child_of'               => 0,
              'parent'                 => '',
              'childless'              => false,
              'cache_domain'           => 'core',
              'update_term_meta_cache' => true,
              'meta_query'             => '',
              'meta_key'               => '',
              'meta_value'             => '',
              'meta_type'              => '',
              'meta_compare'           => '',
              ); */
            if (!empty($settings['dce_views_tax'])) {
                $args['taxonomy'] = $settings['dce_views_tax'];
            }
            //if (!empty($settings['dce_views_empty'])) {        
            $args['hide_empty'] = (bool) $settings['dce_views_empty'];
            //}
            $args['count'] = (bool) $settings['dce_views_count'];
        }

// COLLECT ALL WHERE CONDITIONS
        $where_fields = $settings['dce_views_where'];
        if (!empty($settings['dce_views_where_form'])) {
            foreach ($settings['dce_views_where_form'] as $afield) {
                if (isset($_GET[$afield['dce_views_where_form_field']]) || $settings['dce_views_where_form_result']) {
                    $default_value = $afield['dce_views_where_form_preselect'];
                    if (in_array($afield['dce_views_where_form_type'], array('select', 'radio', 'checkbox'))) {
                        $options = explode(PHP_EOL, $afield['dce_views_where_form_value']);
                        $i = 0;
                        foreach ($options as $okey => $aopt) {
                            $aopt = trim($aopt);
                            $option = explode('|', $aopt, 2);
                            if (!$i && !$afield['dce_views_where_form_value']) {
                                $default_value = reset($option);
                            }
                            $i++;
                        }
                    }
                    $afield_value = isset($_GET[$afield['dce_views_where_form_field']]) && $_GET['eid'] == $this->get_id() ? $_GET[$afield['dce_views_where_form_field']] : $default_value;
//if ($default_value) {
                    $taxonomy = false;
                    if (substr($afield['dce_views_where_form_field'], 0, 9) == 'taxonomy_') {
                        $taxonomy = substr($afield['dce_views_where_form_field'], 9);
                    }
                    if ($taxonomy) {
                        if (is_array($afield_value)) {
                            $tax_ids = array_map('intval', $afield_value);
                        } else {
                            $tax_ids = array(intval($afield_value));
                        }
                        if (!empty($tax_ids) && $tax_ids[0]) {
                            switch ($taxonomy) {
                                case 'category':
// if (isset($args['category__in'])) {
//    $tax_ids = array_intersect($tax_ids, $args['category__in']);
// }
                                    $args['category__in'] = $tax_ids;
                                    break;

                                case 'post_tag':
                                    $args['tag__in'] = $tax_ids;
                                    break;

                                default:
                                    if ($taxonomy) { // && !empty($this->taxonomies_terms[$afield['dce_views_where_form_field']])) {
                                        $args['tax_query'][] = array(
                                            'taxonomy' => $taxonomy,
                                            'field' => 'term_id',
                                            'terms' => $tax_ids,
                                            'operator' => 'IN', // optional??
                                        );
                                    }
                            }
                        }
                    } else {
                        $where_fields[] = array(
                            'dce_views_where_field' => $afield['dce_views_where_form_field'],
                            'dce_views_where_field_is_sub' => $afield['dce_views_where_form_field_is_sub'],
                            'dce_views_where_field_sub' => $afield['dce_views_where_form_field_sub'],
                            'dce_views_where_value' => $afield_value,
                            'dce_views_where_operator' => $afield['dce_views_where_form_operator'],
                            'dce_views_where_rule' => $afield['dce_views_where_form_rule'],
                            'dce_views_where_form_type' => $afield['dce_views_where_form_type'],
                        );
                    }
                }
            }
        }

        if ($settings['dce_views_pagination'] && $settings['dce_views_pagination_page_limit']) {
            $args['max_num_pages'] = intval($settings['dce_views_pagination_page_limit']);
        }

        $obj__in = array();
        $first = true;
        $is_meta_fnc = 'is_' . $settings['dce_views_object'] . '_meta';

// WHERE - NATIVE
        if (!empty($where_fields)) {
            foreach ($where_fields as $awhere) {
                if ($awhere['dce_views_where_field'] && $awhere['dce_views_where_operator'] && !DCE_Helper::{$is_meta_fnc}($awhere['dce_views_where_field'])) {
                    // need some raw query because wp_query has limitations        
                    $obj_ids = $this->get_obj_ids($awhere);
                    $this->obj__in[$awhere['dce_views_where_field']] = $obj_ids;
                    if ($awhere['dce_views_where_rule'] == 'AND') {
                        if (!$first) {
                            $obj__in = array_intersect($obj__in, $obj_ids);
                        } else {
                            $obj__in = $obj_ids;
                        }
                    } else {
                        $obj__in = array_merge($obj__in, $obj_ids);
                    }
                    $first = false;
                }
            }
        }



        if ($settings['dce_views_attachment_mime_type']) {
            $types = DCE_Helper::str_to_array(',', $settings['dce_views_attachment_mime_type']);
            if (count($types) > 1) {
                $args['post_mime_type'] = $types;
            } else {
                $args['post_mime_type'] = $settings['dce_views_attachment_mime_type'];
            }
        }

// WHERE - META
        if (!empty($where_fields)) {
            foreach ($where_fields as $awhere) {
                if ($awhere['dce_views_where_field'] && $awhere['dce_views_where_operator'] && DCE_Helper::{$is_meta_fnc}($awhere['dce_views_where_field'])) {
                    $mt = array(
                        'key' => $awhere['dce_views_where_field'],
                        'value' => $awhere['dce_views_where_value'],
                        'compare' => $awhere['dce_views_where_operator'],
                    );
                    if (in_array($awhere['dce_views_where_operator'], array('IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN'))) {
                        $values = DCE_Helper::str_to_array(',', $awhere['dce_views_where_value']);
                        if (is_numeric(reset($values))) {
                            $mt['type'] = 'numeric';
                        }
                        if (count($values) > 1) {
                            $mt['values'] = $values;
                        }
                    }

                    $obj_ids = $this->get_obj_ids($awhere);
                    $this->obj__in[$awhere['dce_views_where_field']] = $obj_ids;
                    if ($awhere['dce_views_where_rule'] == 'AND') {
                        if (!$first) {
                            $obj__in = array_intersect($obj__in, $obj_ids);
                        } else {
                            $obj__in = $obj_ids;
                        }
                    } else {
                        $obj__in = array_merge($obj__in, $obj_ids);
                    }
                    $first = false;
                }
            }
        }

        $obj_not_in = array();
        if (isset($settings['dce_views_ignore_ids']) && $settings['dce_views_ignore_ids']) {
            $obj_not_in = $settings['dce_views_ignore_ids'];
            $obj_not_in = DCE_Helper::str_to_array(',', $obj_not_in, 'intval');
        }

        switch ($settings['dce_views_object']) {
            case 'post':
                if (!empty($obj_not_in)) {
                    $args['post__not_in'] = $obj_not_in;
                }
                if (!$first) {
                    if (!empty($obj__in)) {
                        $args['post__in'] = $obj__in;
                    } else {
                        // NO RESULTS
                        $args['post__in'] = array(0);
                    }
                }
                if (isset($args['post__in']) && isset($args['post__not_in'])) {
                    $args['post__in'] = array_diff($args['post__in'], $args['post__not_in']);
                }
                break;
            case 'user':
                if (!empty($obj_not_in)) {
                    $args['exclude'] = $obj_not_in;
                }
                if (!$first) {
                    if (!empty($obj__in)) {
                        $args['include'] = $obj__in;
                    } else {
                        // NO RESULTS
                        $args['include'] = array(0);
                    }
                }
                if (isset($args['include']) && isset($args['exclude'])) {
                    $args['include'] = array_diff($args['include'], $args['exclude']);
                }
                break;
            case 'term':
                if (!empty($obj_not_in)) {
                    $args['exclude'] = $obj_not_in;
                    $args['exclude_tree'] = $obj_not_in;
                }
                if (!$first) {
                    if (!empty($obj__in)) {
                        $args['include'] = $obj__in;
                    } else {
                        // NO RESULTS
                        $args['include'] = array(0);
                    }
                }
                if (isset($args['include']) && isset($args['exclude'])) {
                    $args['include'] = array_diff($args['include'], $args['exclude']);
                }
                break;
        }



// ORDER BY
        if ($settings['dce_views_order_random']) {
            $args['orderby'] = 'rand';
        } else {

            if (!empty($settings['dce_views_group_by_field'])) {
                array_unshift($settings['dce_views_order_by'],
                        array(
                            'dce_views_order_field' => $settings['dce_views_group_by_field'],
                            'dce_views_order_field_sort' => 'ASC'
                        )
                );
            }

            if (isset($settings['dce_views_order_by']) && !empty($settings['dce_views_order_by'])) {
                if (isset($_GET['orderby']) && isset($_GET['eid']) && $_GET['eid'] == $this->get_id()) {
                    list($order_sort, $order_field) = explode('__', $_GET['orderby'], 2);
                    foreach ($settings['dce_views_order_by'] as $key => $asort) {
                        if ($asort['dce_views_order_field_sort_exposed']) {
                            if ($order_field == $asort['dce_views_order_field']) {
                                $args['orderby'][$order_field] = $order_sort;
                                break;
                            }
                        }
                    }
                } else {
                    foreach ($settings['dce_views_order_by'] as $key => $asort) {
                        if ($asort['dce_views_order_field']) {
                            if (!isset($args['orderby'][$asort['dce_views_order_field']])) {
                                $is_meta = 'is_' . $settings['dce_views_object'] . '_meta';
                                if (DCE_Helper::{$is_meta}($asort['dce_views_order_field'])) {
                                    $args['meta_key'][] = $asort['dce_views_order_field'];
//$args['meta_key'] = $asort['dce_views_order_field'];
                                    /* if (!isset($args['meta_query'])) {
                                      $args['meta_query'] = array(
                                      'relation' => 'OR',
                                      );
                                      }
                                      $args['meta_query'][$asort['dce_views_order_field'].'_clause'] = array(
                                      'key' => $asort['dce_views_order_field'],
                                      'compare' => 'EXISTS',
                                      ); */
//$args['orderby'][$asort['dce_views_order_field'].'_clause'] = $asort['dce_views_order_field_sort'];
                                    $args['orderby']['meta_value'] = $asort['dce_views_order_field_sort'];
                                } else {
                                    $dce_views_order_field = $asort['dce_views_order_field'];
//$dce_views_order_field = '.'.$dce_views_order_field;
//$dce_views_order_field = str_replace('.post_', '', $dce_views_order_field);
//$dce_views_order_field = str_replace('.', '', $dce_views_order_field);
                                    $args['orderby'][$dce_views_order_field] = $asort['dce_views_order_field_sort'];
                                }
                            }
                        }
                    }
                }
            }

            if (isset($args['orderby']) && count($args['orderby']) == 1) {
                $array_keys = array_keys($args['orderby']);
                $dce_views_order_field = reset($array_keys); //array_key_first($args['orderby']); // compatibility >7
                $args['order'] = reset($args['orderby']); //[$args['meta_key']];
                if (isset($args['meta_key'])) {
                    $args['meta_key'] = reset($args['meta_key']);
                    switch (DCE_Helper::get_meta_type($args['meta_key'])) {
                        case 'number':
                            $args['orderby'] = 'meta_value_num';
                            break;
                        case 'date':
                        default :
                            $args['orderby'] = $dce_views_order_field;
//$args['orderby'] = $args['orderby'][$args['meta_key']];
//$args['orderby'] = $args['meta_key'].'_clause';
                    }
                } else {
                    $args['orderby'] = $dce_views_order_field;
                }
            } else {
                if (!empty($args['meta_key'])) {
                    $args['meta_key'] = reset($args['meta_key']);
                }
            }
        }

// OFFSET
        if ($settings['dce_views_limit_offset']) {
            $args['offset'] = $settings['dce_views_limit_offset'];
        }

// PAGE
        if (isset($_GET['pag']) && $_GET['pag'] > 0 && isset($_GET['eid']) && $_GET['eid'] == $this->get_id()) {
            $args['paged'] = $this->get_current_page();
        }

        if ($settings['dce_views_object'] == 'post') {
            if (!isset($args['post_status'])) {
                $args['post_status'] = 'publish';
            }
        }

        if ($settings['dce_views_object'] == 'user') {
            if (empty($args)) {
                $args['exclude'] = array(0);
            }
        }

        return $args;
    }

    public function get_obj_ids($awhere, $retry = null) {
        global $wpdb;
        $settings = $this->get_active_settings();
        $obj_ids = array();

        if (!$retry) {
            if (isset($_GET[$awhere['dce_views_where_field']]) && $_GET['eid'] == $this->get_id()) {
                $search_value = $_GET[$awhere['dce_views_where_field']];
            } else {
                $search_value = $awhere['dce_views_where_value'];
            }
        } else {
            $search_value = $retry;
        }

        if ($awhere['dce_views_where_operator'] == 'IN' || $awhere['dce_views_where_operator'] == 'NOT IN') {
            $search_values = DCE_Helper::str_to_array(',', $search_value, 'esc_sql');
            if (!empty($search_values)) {
                $is_string = false;
                foreach ($search_values as $asrc) {
                    if (is_string($asrc)) {
                        $is_string = true;
                        break;
                    }
                }
                if ($is_string) {
                    $search_value = '("' . implode('","', $search_values) . '")';
                } else {
                    $search_value = '(' . implode(',', $search_values) . ')';
                }
            } else {
                $search_value = '(0)';
            }
        }
        if ($awhere['dce_views_where_operator'] == 'BETWEEN' || $awhere['dce_views_where_operator'] == 'NOT BETWEEN') {
            $search_value = implode('" AND "', DCE_Helper::str_to_array(',', $search_value));
        }

        $acf_repeater_block_fields = DCE_Helper::get_acf_fields(array('repeater', 'block'));
        $is_repeater = DCE_Helper::is_plugin_active('acf') && array_key_exists($awhere['dce_views_where_field'], $acf_repeater_block_fields);
        $is_meta_fnc = 'is_' . $settings['dce_views_object'] . '_meta';
        $is_meta = DCE_Helper::{$is_meta_fnc}($awhere['dce_views_where_field']);
        $obj_first = substr($settings['dce_views_object'], 0, 1);
        $field_id = $settings['dce_views_object'] == 'term' ? $settings['dce_views_object'] . '_id' : 'ID';
        $post_fields = $settings['dce_views_object'] == 'post' ? ', p.post_type, p.post_parent' : '';

        $table = $wpdb->prefix . $settings['dce_views_object'] . 's';
        $table_meta = $wpdb->prefix . $settings['dce_views_object'] . 'meta';
        if ($settings['dce_views_object'] == 'user') {
            if (defined('CUSTOM_USER_TABLE')) {
                $table = CUSTOM_USER_TABLE;
            }
            if (defined('CUSTOM_USER_META_TABLE')) {
                $table_meta = CUSTOM_USER_META_TABLE;
            }
        }

        if ($is_meta) {
            $search_query = 'SELECT ' . $obj_first . 'm.' . $settings['dce_views_object'] . '_id AS "ID"' . $post_fields . ' FROM ' . $table_meta . ' ' . $obj_first . 'm, ' . $table . ' ' . $obj_first;
            if ($is_repeater) {
                $search_query .= ' WHERE ' . $obj_first . '.' . $field_id . ' = ' . $obj_first . 'm.' . $settings['dce_views_object'] . '_id AND ' . $obj_first . 'm.meta_key LIKE "' . $awhere['dce_views_where_field'] . '_%" ';
            } else {
                $search_query .= ' WHERE ' . $obj_first . '.' . $field_id . ' = ' . $obj_first . 'm.' . $settings['dce_views_object'] . '_id AND ' . $obj_first . 'm.meta_key LIKE "' . $awhere['dce_views_where_field'] . '" ';
            }
            if (empty($awhere['dce_views_where_field_is_sub'])) {
                $search_query .= 'AND ( ' . $obj_first . 'm.meta_value ';
            }
        } else {
            $search_query = 'SELECT ' . $field_id . ' AS "ID" FROM ' . $table;
            $search_query .= ' WHERE ' . $awhere['dce_views_where_field'] . ' ';
        }

        if (!empty($awhere['dce_views_where_field_is_sub'])) {
            $results = $wpdb->get_results($search_query);
            foreach ($results as $key => $aobj) {
                if ($settings['dce_views_object'] == 'post' && $aobj->post_type == 'revision') {
                    continue;
                }
                $pid = intval($aobj->ID);
                if ($is_meta) {
                    $fnc = 'get_' . $settings['dce_views_object'] . '_meta';
                    $value = call_user_func($fnc, $pid, $awhere['dce_views_where_field'], true);
                } else {
                    $fnc = 'get_' . $settings['dce_views_object'];
                    if ($settings['dce_views_object'] == 'user') {
                        $aobj = get_user_by('ID', $pid);
                    } else {
                        $aobj = call_user_func($fnc, $pid);
                    }
                    $value = $aobj->{$awhere['dce_views_where_field']};
                }
                $sub_value = DCE_Tokens::replace_var_tokens($awhere['dce_views_where_field_sub'], 'field', $value);
                $satisfy = false;
                switch ($awhere['dce_views_where_operator']) {
                    case ">":
                        $satisfy = $sub_value > $search_value;
                        break;
                    case ">=":
                        $satisfy = $sub_value >= $search_value;
                        break;
                    case "<":
                        $satisfy = $sub_value < $search_value;
                        break;
                    case "<=":
                        $satisfy = $sub_value <= $search_value;
                        break;
                    case "LIKE":
                    case "RLIKE":
                    case "=":
                        $satisfy = $sub_value == $search_value;
                        break;
                    case "NOT LIKE":
                    case "!=":
                        $satisfy = $sub_value != $search_value;
                        break;
                    case "IN":
                        if (is_array($sub_value)) {
                            $satisfy = in_array($search_value, $sub_value);
                        }
                        break;
                    case "NOT IN":
                        if (is_array($sub_value)) {
                            $satisfy = !in_array($search_value, $sub_value);
                        }
                        break;
                    case "BETWEEN":
                        if (is_array($search_value)) {
                            $satisfy = ($sub_value > reset($search_value) && $sub_value < end($search_value));
                        }
                        break;
                    case "NOT BETWEEN":
                        if (is_array($search_value)) {
                            $satisfy = ($sub_value < reset($search_value) || $sub_value > end($search_value));
                        }
                        break;
                    case "NOT EXISTS":
                        $satisfy = $sub_value == '';
                        break;
                    //"REGEXP" => "REGEXP",
                    //"NOT REGEXP" => "NOT REGEXP",
                    default:
                        $satisfy = false;
                }

                if ($satisfy) {
                    if (!in_array($pid, $obj_ids)) {
                        $obj_ids[] = $pid;
                    }
                }
            }
        } else {
            $search_query .= $awhere['dce_views_where_operator'];
            if ($awhere['dce_views_where_operator'] != 'IS NULL' && $awhere['dce_views_where_operator'] != 'IS NOT NULL'
            ) {
                $search_query .= ' ';
                if ($awhere['dce_views_where_operator'] != 'IN' && $awhere['dce_views_where_operator'] != 'NOT IN' && $awhere['dce_views_where_operator'] != 'EXISTS' && $awhere['dce_views_where_operator'] != 'NOT EXISTS'
                ) {
                    $search_query .= '"';
                    /* if (empty(trim($search_value))) {
                      $search_value = '0';
                      } */
                }
                if ($awhere['dce_views_where_operator'] == 'LIKE' || $awhere['dce_views_where_operator'] == 'NOT LIKE') {
                    $search_query .= '%';
                    $search_value = $wpdb->esc_like($search_value);
                }
                if ($awhere['dce_views_where_operator'] == 'IN' || $awhere['dce_views_where_operator'] == 'NOT IN') {
                    $search_query .= $search_value;
                } else {
                    $search_query .= esc_sql($search_value);
                }
                if ($awhere['dce_views_where_operator'] == 'LIKE' || $awhere['dce_views_where_operator'] == 'NOT LIKE') {
                    $search_query .= '%';
                }
                if ($awhere['dce_views_where_operator'] != 'IN' && $awhere['dce_views_where_operator'] != 'NOT IN' && $awhere['dce_views_where_operator'] != 'EXISTS' && $awhere['dce_views_where_operator'] != 'NOT EXISTS'
                ) {
                    $search_query .= '"';
                }

                if ($is_meta) {
                    if ($awhere['dce_views_where_operator'] == 'IN' || $awhere['dce_views_where_operator'] == 'NOT IN') {
                        /* $search_value = str_replace('(', '', $search_value);
                          $search_value = str_replace(')', '', $search_value);
                          $search_values = DCE_Helper::str_to_array(',', $search_value); */
                        foreach ($search_values as $avalue) {
                            $avalue = str_replace('"', '', $avalue);
                            $search_query .= " OR " . $obj_first . "m.meta_value " . (trim(str_replace('IN', '', $awhere['dce_views_where_operator']))) . " LIKE '%s:" . strlen($avalue) . ":\"" . $avalue . "\"%'"; // serialized data s:1:"5";
                        }
                    }
                }
            }
            if ($is_meta) {
                $search_query .= ' )';
            }
            //var_dump($search_query);

            $results = $wpdb->get_results($search_query);
            //var_dump($results);
            if (!empty($results)) {
                foreach ($results as $key => $aobj) {
                    $pid = intval($aobj->ID);
                    if ($is_meta) {
                        if ($settings['dce_views_object'] == 'post' && $aobj->post_type == 'revision') {
                            if (!in_array(intval($aobj->post_parent), $obj_ids)) {
                                $obj_ids[] = intval($aobj->post_parent);
                            }
                        } else {
                            if (!in_array($pid, $obj_ids)) {
                                $obj_ids[] = $pid;
                            }
                        }
                    } else {
                        if (!in_array($pid, $obj_ids)) {
                            $obj_ids[] = $pid;
                        }
                    }
                }
            }
        }

        if (empty($obj_ids) && !$retry) {
            if (isset($awhere['dce_views_where_form_type']) && $awhere['dce_views_where_form_type'] == 'text') {
                $words = explode(' ', $search_value);
                if ($words > 2) {
                    foreach ($words as $key => $value) {
                        if (strlen($value) > 3) {
                            $obj_ids = array_merge($obj_ids, $this->get_obj_ids($awhere, $value));
                        }
                    }
                }
            }
        }
        //var_dump($obj_ids);
        return $obj_ids;
    }

    public function get_field_value($dce_obj, $dce_obj_id, $afield, $settings = null) {
        if (!$settings) {
            $settings = $this->get_settings_for_display();
        }
        $get_value = 'get_' . $settings['dce_views_object'] . '_value';
        $field_value = DCE_Helper::{$get_value}($dce_obj_id, $afield['dce_views_select_field']);
        
        if ($afield['dce_views_select_render'] == 'rewrite' && $afield['dce_views_select_rewrite']) {
            $field_value_rewrite = $afield['dce_views_select_rewrite'];
            $field_value_rewrite = DCE_Tokens::replace_var_tokens($field_value_rewrite, 'field', $field_value);
            $field_value_rewrite = DCE_Tokens::replace_var_tokens($field_value_rewrite, $settings['dce_views_object'], $dce_obj);
            $field_value_rewrite = DCE_Tokens::replace_var_tokens($field_value_rewrite, 'object', $dce_obj);
            /*if ($settings['dce_views_object'] == 'user') {
                $field_value_rewrite = DCE_Tokens::user_to_author($field_value_rewrite);
            }*/
            $field_value_rewrite = DCE_Helper::get_dynamic_value($field_value_rewrite);
            $field_value = $field_value_rewrite;
        }
            
        if ($field_value) {
            if (!empty($afield['dce_views_select_link'])) {
                $get_link = 'get_' . $settings['dce_views_object'] . '_link';
                $field_value = '<a href="' . DCE_Helper::{$get_link}($dce_obj_id) . '">' . $field_value . '</a>';
            }
            if ($afield['dce_views_select_render'] == 'auto' && $afield['dce_views_select_tag']) {
                $field_value = '<' . $afield['dce_views_select_tag'] . '>' . $field_value . '</' . $afield['dce_views_select_tag'] . '>';
            }
        } else {
            if ($afield['dce_views_select_no_results']) {
                $field_value = $afield['dce_views_select_no_results'];
                if ($settings['dce_views_object'] == 'user') {
                    $field_value = DCE_Tokens::user_to_author($field_value);
                }
                $field_value = DCE_Helper::get_dynamic_value($field_value);
            }
        }
        return $field_value;
    }

}

/*
  $options = array(
  'posts_per_page' => -1,
  'suppress_filters' => false, // important!
  'post_type' => 'post',
  'post_status' => 'publish',
  );
  $keyword = 'quote';

  add_filter( 'posts_where', 'my_filter_post_where' );
  $posts = get_posts( $options );
  remove_filter( 'posts_where', 'my_filter_post_where' );

  function my_filter_post_where( $where) {
  global $wpdb;
  global $keyword;

  $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( like_escape( $keyword ) ) . '%\'';

  return $where;
  }
 */


