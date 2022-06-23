<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use DynamicContentForElementor\DCE_Helper;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Elementor Posts-Terms
 *
 * Elementor widget for Dynamic Content for Elementor
 *
 */
class DCE_Widget_Terms extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-terms';
    }

    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('Terms & Taxonomy', 'dynamic-content-for-elementor');
    }

    public function get_description() {
        return __('Write a taxonomy for your article', 'dynamic-content-for-elementor');
    }

    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/terms-and-taxonomy/';
    }

    public function get_icon() {
        return 'icon-dyn-terms';
    }

    static public function get_position() {
        return 3;
    }

    protected function _register_controls() {

        $this->start_controls_section(
                'section_content', [
            'label' => __('Terms', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'taxonomy', [
            'label' => __('Taxonomy', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            //'options' => get_post_taxonomies( $post->ID ),
            'options' => ['auto' => __('Dynamic', 'dynamic-content-for-elementor')] + get_taxonomies(array('public' => true)),
            'default' => 'category',
                ]
        );
        /*$this->add_control(
                'only_parent_terms', [
            'label' => __('Only parent terms', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __('Yes', 'dynamic-content-for-elementor'),
            'label_off' => __('No', 'dynamic-content-for-elementor'),
            'return_value' => 'yes',
        );*/

        $this->add_control(
                'only_parent_terms', [
            'label' => __('Show only', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'both' => [
                    'title' => __('Both', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-tags',
                ],
                'yes' => [
                    'title' => __('Parents', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-tag',
                ],
                'children' => [
                    'title' => __('Children', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-sticky-note-o',
                ]
            ],
            'toggle' => false,
            'default' => 'both',
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
            'default' => 'div',
                ]
        );
        $this->add_control(
                'separator', [
            'label' => __('Separator', 'dynamic-content-for-elementor'),
            //'description' => __('Separator caracters.','dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => ', ',
                ]
        );
        $this->add_responsive_control(
                'space', [
            'label' => __('Space', 'dynamic-content-for-elementor'),
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
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 100,
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-separator' => 'padding: 0 {{SIZE}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'text_before', [
            'label' => __('Text before', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
                ]
        );
        $this->add_control(
                'text_after', [
            'label' => __('Text after', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
                ]
        );
        $this->add_responsive_control(
                'align', [
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
                'justify' => [
                    'title' => __('Justified', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-justify',
                ],
            ],
            'default' => '',
            'selectors' => [
                '{{WRAPPER}}' => 'text-align: {{VALUE}};',
            ],
                ]
        );
        $this->add_control(
                'link_to', [
            'label' => __('Link to', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none' => __('None', 'dynamic-content-for-elementor'),
                'term' => __('Term', 'dynamic-content-for-elementor'),
            ],
                ]
        );

        $this->end_controls_section();

        if (DCE_Helper::is_plugin_active('acf')) {
            $this->start_controls_section(
                    'section_image', [
                'label' => __('Term Image', 'dynamic-content-for-elementor'),
                    ]
            );
            $this->add_control(
                    'heading_image_acf',
                    [
                        'label' => __('ACF Term image', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                    ]
            );
            $this->add_control(
                    'image_acf_enable',
                    [
                        'label' => __('Enable', 'dynamic-content-for-elementor'),
                        'type' => Controls_Manager::SWITCHER,
                        'default' => '',
                        'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                        'label_off' => __('No', 'dynamic-content-for-elementor'),
                        'return_value' => 'yes',
                    ]
            );
            $this->add_control(
                    'acf_field_image', [
                'label' => __('ACF Field', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SELECT,
                //'options' => $this->get_acf_field(),
                'groups' => $this->get_acf_field_image(true),
                'default' => 'Select the Field',
                'condition' => [
                    'image_acf_enable' => 'yes',
                ]
                    ]
            );
            $this->add_group_control(
                    Group_Control_Image_Size::get_type(), [
                'name' => 'imgsize',
                'label' => __('Image Size', 'dynamic-content-for-elementor'),
                'default' => 'large',
                'render_type' => 'template',
                'condition' => [
                    'image_acf_enable' => 'yes',
                ]
                    ]
            );
            $this->add_control(
                    'block_enable', [
                'label' => __('Block', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'dynamic-content-for-elementor'),
                'label_off' => __('No', 'dynamic-content-for-elementor'),
                'return_value' => 'block',
                'selectors' => [
                    '{{WRAPPER}} .dce-terms img' => 'display: {{VALUE}};',
                ],
                'condition' => [
                    'image_acf_enable' => 'yes',
                ],
                    ]
            );
            $this->add_control(
                    'image_acf_space',
                    [
                        'label' => __('Space', 'dynamic-content-for-elementor'),
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
                            '{{WRAPPER}} .dce-terms img' => 'margin-right: {{SIZE}}{{UNIT}};',
                        ],
                        'condition' => [
                            'image_acf_enable' => 'yes',
                        ],
                    ]
            );
            $this->add_responsive_control(
                    'image_acf_size', [
                'label' => __('Size (%)', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 800,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-terms img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'image_acf_enable' => 'yes',
                ],
                    ]
            );
            $this->add_responsive_control(
                    'image_acf_shift', [
                'label' => __('Shift', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dce-terms img' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'image_acf_enable' => 'yes',
                ],
                    ]
            );
            $this->end_controls_section();
        }

        // ----------------------------------------- [STYLE]
        $this->start_controls_section(
                'section_style', [
            'label' => __('Terms', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        $this->add_control(
                'color', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-terms' => 'color: {{VALUE}};',
                '{{WRAPPER}} .dce-terms a' => 'color: {{VALUE}};',
            ],
                ]
        );
        $this->add_control(
                'color_hover', [
            'label' => __('Text Color Hover', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-terms a:hover' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'link_to!' => 'none',
            ],
                ]
        );
        $this->add_control(
                'color_separator', [
            'label' => __('Separator color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-separator' => 'color: {{VALUE}};',
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'typography',
            'selector' => '{{WRAPPER}} .dce-terms',
                ]
        );
        $this->add_control(
                'hover_animation', [
            'label' => __('Hover Animation', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::HOVER_ANIMATION,
            'condition' => [
                'link_to!' => 'none',
            ],
                ]
        );

        /* ------------------ Text Before ------------ */
        $this->add_control(
                'txbefore_heading',
                [
                    'label' => __('Text before', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'text_before!' => '',
                    ]
                ]
        );
        $this->add_control(
                'tx_before_color', [
            'label' => __('Text Before Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-terms span.tx-before' => 'color: {{VALUE}};',
                '{{WRAPPER}} .dce-terms a span.tx-before' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'text_before!' => '',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'typography_tx_before',
            'label' => __('Font Before', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-terms span.tx-before',
            'condition' => [
                'text_before!' => '',
            ]
                ]
        );



        /* ------------------ Text After ------------ */
        $this->add_control(
                'txafter_heading',
                [
                    'label' => __('Text after', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'text_after!' => '',
                    ]
                ]
        );
        $this->add_control(
                'tx_after_color', [
            'label' => __('Text After Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-terms span.tx-after' => 'color: {{VALUE}};',
                '{{WRAPPER}} .dce-terms a span.tx-after' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'text_after!' => '',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'typography_tx_after',
            'label' => __('Font After', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-terms span.tx-after',
            'condition' => [
                'text_after!' => '',
            ]
                ]
        );
        $this->end_controls_section();
        // ------------------------------------------------ SETTINGS 
        $this->start_controls_section(
                'section_dce_settings', [
            'label' => __('Dynamic Content', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_SETTINGS,
                ]
        );
        $this->add_control(
                'data_source',
                [
                    'label' => __('Source', 'dynamic-content-for-elementor'),
                    'description' => __('Select the data source', 'dynamic-content-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'label_on' => __('Same', 'dynamic-content-for-elementor'),
                    'label_off' => __('other', 'dynamic-content-for-elementor'),
                    'return_value' => 'yes',
                ]
        );
        /* $this->add_control(
          'other_post_source', [
          'label' => __('Select from other source post', 'dynamic-content-for-elementor'),
          'type' => Controls_Manager::SELECT,

          'groups' => DCE_Helper::get_all_posts(get_the_ID(),true),
          'label_block' => true,
          'default' => '',
          'condition' => [
          'data_source' => '',
          ],
          ]
          ); */
        $this->add_control(
                'other_post_source',
                [
                    'label' => __('Select from other source post', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Post Title', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'condition' => [
                        'data_source' => '',
                    ],
                ]
        );
        /* $this->add_control(
          'go_to_page',
          [
          'type'    => Controls_Manager::RAW_HTML,
          'raw' => '<a target="_blank" class="dce-go-to-page-template dce-btn" href="#">
          <i class="fa fa-pencil"></i>'. __( 'Edit Page', 'dynamic-content-for-elementor' ).'</a>',
          'content_classes' => 'dce-btn-go-page',
          'separator' => 'after',
          //'render_type' => 'template',
          'condition' => [
          'other_post_source!' => '',
          ],
          ]
          ); */
        /* $this->add_control(
          'mod_page',
          [
          'type' => Controls_Manager::BUTTON,
          'label' => __( 'Modify', 'dynamic-content-for-elementor' ),
          'label_block' => true,
          'show_label' => false,
          'text' => __( 'View page', 'dynamic-content-for-elementor' ),
          'separator' => 'none',
          'event' => 'dceMain:previewPage',
          'condition' => [
          'other_post_source!' => 0,
          'data_source' => '',
          ],
          ]
          ); */
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
            return;
        //
        // ------------------------------------------
        $dce_data = DCE_Helper::dce_dynamic_data($settings['other_post_source']);
        $id_page = $dce_data['id'];
        $global_is = $dce_data['is'];
        // ------------------------------------------

        $taxonomy = $settings['taxonomy'];
        $taxonomyAuto = [];

        if (empty($taxonomy))
            return;

        if ($taxonomy == 'auto') {

            $taxonomyAuto = get_post_taxonomies($id_page);
        } else {

            $taxonomyAuto = $taxonomy;
        };
        $animation_class = !empty($settings['hover_animation']) ? 'elementor-animation-' . $settings['hover_animation'] : '';
        $html = '';
        if (is_array($taxonomyAuto)) {

            /* $term_list = array();
              foreach ( $taxonomyAuto as $taxo ) {
              echo $taxo;
              $autoTerms = get_the_terms( $id_page, $taxo );
              $tmpTerm = $term_list;
              foreach ( $autoTerms as $t ) {
              $term_list = array_push($term_list, $t);
              }
              //$term_list = array_merge($autoTerms, $tmpTerm);
              } */
            $term_list = \DynamicContentForElementor\DCE_Helper::get_the_terms_ordered($id_page/* $post->ID */, reset($taxonomyAuto));
        } else {
            $term_list = \DynamicContentForElementor\DCE_Helper::get_the_terms_ordered($id_page/* $post->ID */, $taxonomyAuto);
        }
        if (empty($term_list) || is_wp_error($term_list)) {
            if (is_admin()) {
                $html = sprintf('<%1$s class="dce-terms %2$s">', $settings['html_tag'], $animation_class);
                $html .= '<a href="#">Term</a><span class="dce-separator">' . $settings['separator'] . '</span>';
                $html .= '<a href="#">Term</a><span class="dce-separator">' . $settings['separator'] . '</span>';
                $html .= '<a href="#">Term</a>';
                $html .= sprintf('</%s>', $settings['html_tag']);
                echo $html;
            }

            return;
        } else {
            //$html = sprintf( '<%1$s class="dce-terms %2$s">', $settings['html_tag'], $animation_class );


            $separator = '';
            $conta = 0;


            $html = sprintf('<%1$s class="dce-terms %2$s">', $settings['html_tag'], $animation_class);


            if ($settings['text_before'] != "" || $settings['text_after'] != "")
                $html .= '<span class="tx-before">' . __($settings['text_before'], 'dynamic-content-for-elementor' . '_texts') . '</span>';
            //echo 'terms: ';
            // --------------------- Image ACF
            foreach ($term_list as $term) {
                
                if (!empty($settings["only_parent_terms"])) {
                    if ($settings["only_parent_terms"] == 'yes') {
                        if ($term->parent) continue;
                    }
                    if ($settings["only_parent_terms"] == 'children') {
                        if (!$term->parent) continue;
                    }
                }
                // se il termina non ha genitore è il padre..
                //if ($settings["only_parent_terms"] || !$term->parent) {
                    //echo '->only:'.$settings["only_parent_terms"]  .' ->p:'. $term->parent;

                    if ($conta > 0)
                        $html .= '<span class="dce-separator">' . $settings['separator'] . '</span>';

                    if (DCE_Helper::is_plugin_active('acf')) {
                        $image_acf = '';
                        if ($settings['image_acf_enable']) {

                            $idFields = $settings['acf_field_image'];
                            $imageField = get_field($idFields, 'term_' . $term->term_id);
                            $typeField = '';

                            //echo $typeField.': '.$imageField;
                            if (is_string($imageField)) {
                                //echo 'url: '.$imageField;
                                $typeField = 'image_url';
                                $imageSrc = $imageField;
                            } else if (is_numeric($imageField)) {
                                //echo 'id: '.$imageField;
                                $typeField = 'image';
                                $imageSrc = Group_Control_Image_Size::get_attachment_image_src($imageField, 'imgsize', $settings);
                            } else if (is_array($imageField)) {
                                //echo 'array: '.$imageField;
                                $typeField = 'image_array';
                                $imageSrc = Group_Control_Image_Size::get_attachment_image_src($imageField['ID'], 'imgsize', $settings);
                            }
                            if (isset($imageSrc)) {
                                $image_acf = '<img src="' . $imageSrc . '" />';

                                $html .= $image_acf;
                            }
                        }
                    }
                    switch ($settings['link_to']) {
                        case 'term' :



                            $html .= sprintf('<a href="%1$s" class="term%3$s">%2$s</a>', esc_url(get_term_link($term)), $term->name, $term->term_id);
                            $conta ++;

                            break;

                        case 'none' :
                        default:

                            $html .= sprintf('<span class="term%1$s">%2$s</span>', $term->term_id, $term->name);

                            $conta ++;

                            break;
                    }
                //}
            } // end onli_term_parent
            $html .= sprintf('</%s>', $settings['html_tag']);
        }
        //$html = substr( $html, 0, -2);


        echo $html;
        //}
    }

    protected function _content_template() {
        global $post;
        /*
          ?>
          <#
          var taxonomy = settings.taxonomy;

          var all_terms = [];
          <?php
          $taxonomies = get_taxonomies( array( 'public' => true ) );
          foreach ( $taxonomies as $taxonomy ) {
          printf( 'all_terms["%1$s"] = [];', $taxonomy );
          $terms = get_the_terms( $post->ID, $taxonomy );
          if ( $terms ) {
          $i = 0;
          foreach ( $terms as $term ) {
          printf( 'all_terms["%1$s"][%2$s] = [];', $taxonomy, $i );
          printf( 'all_terms["%1$s"][%2$s] = { slug: "%3$s", name: "%4$s", url: "%5$s" };', $taxonomy, $i, $term->slug, $term->name, esc_url( get_term_link( $term ) ) );
          $i++;
          }
          }
          }
          ?>
          var post_terms = all_terms[ settings.taxonomy ];

          var terms = '';
          var i = 0;

          switch( settings.link_to ) {
          case 'term':
          while ( all_terms[ settings.taxonomy ][i] ) {
          terms += "<a href='" + all_terms[ settings.taxonomy ][i].url + "'>" + all_terms[ settings.taxonomy ][i].name + "</a>, ";
          i++;
          }
          break;
          case 'none':
          default:
          while ( all_terms[ settings.taxonomy ][i] ) {
          terms += all_terms[ settings.taxonomy ][i].name + ", ";
          i++;
          }
          break;
          }
          terms = terms.slice(0, terms.length-2);

          var animation_class = '';
          if ( '' !== settings.hover_animation ) {
          animation_class = 'elementor-animation-' + settings.hover_animation;
          }

          var html = '<' + settings.html_tag + ' class="dce-terms ' + animation_class + '">';
          html += terms;
          html += '</' + settings.html_tag + '>';

          print( html );
          #>

          <?php
         */
    }

    protected function get_acf_field_image($group = false) {
        $acfList = [];
        $acfList[0] = 'Select the Field';
        $tipo = 'acf-field';
        $get_templates = get_posts(array('post_type' => $tipo, 'numberposts' => -1, 'post_status' => 'publish'));
        if (!empty($get_templates)) {

            foreach ($get_templates as $template) {
                $gruppoAppartenenza = get_the_title($template->post_parent);
                $arrayField = maybe_unserialize($template->post_content);

                if ($arrayField['type'] == 'image') {

                    if ($group) {
                        $acfList[$gruppoAppartenenza]['options'][$template->post_excerpt] = $template->post_title;
                        $acfList[$gruppoAppartenenza]['label'] = $gruppoAppartenenza;
                    } else {
                        $acfList[$template->post_excerpt] = $template->post_title . '(' . $arrayField['type'] . ')'; //.$template->post_content; //post_name,
                    }
                }
            }
        }
        return $acfList;
    }

}
