<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use DynamicContentForElementor\DCE_Helper;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dynamic Content Title
 *
 * Widget ACF for Dynamic Content for Elementor
 *
 * @since 0.2.0
 */
class DCE_Widget_PodsRelationship extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-pods-relation';
    }

    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('PODS Relationship', 'dynamic-content-for-elementor');
    }

    public function get_description() {
        return __('Display related posts', 'dynamic-content-for-elementor');
    }

    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/pods-relationship/';
    }

    public function get_icon() {
        return 'icon-dyn-relation';
    }

    public function get_plugin_depends() {
        return array('pods' => 'pods');
    }

    protected function _register_controls() {
        $rels = DCE_Helper::get_pods_fields('pick');
        //var_dump($rels); die();
        //$templates = DCE_Helper::get_all_template();

        // ********************************************************************************* Section BASE
        $this->start_controls_section(
                'section_content', [
            'label' => __('Content', 'dynamic-content-for-elementor')
                ]
        );
        $this->add_control(
                'pods_relation_field', [
            'label' => __('PODS Relation Fields List', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'groups' => $rels,
            'default' => '0',
                ]
        );

        $this->add_control(
                'pods_relation_render', [
            'label' => __('Render mode', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'title' => [
                    'title' => __('Title', 'dynamic-content-for-elementor'),
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
            'default' => 'title',
            'separator' => 'before',
                ]
        );


        /*$this->add_control(
                'pods_relation_template', [
            'label' => __('Select Template', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'options' => $templates,
            'condition' => [
                'pods_relation_render' => 'template',
            ],
                ]
        );*/
        $this->add_control(
                'pods_relation_template',
                [
                    'label' => __('Select Template', 'dynamic-content-for-elementor'),
                    'type' => 'ooo_query',
                    'placeholder' => __('Template Name', 'dynamic-content-for-elementor'),
                    'label_block' => true,
                    'query_type' => 'posts',
                    'object_type' => 'elementor_library',
                    'condition' => [
                        'pods_relation_render' => 'template',
                    ],
                ]
        );
        $this->add_control(
                'pods_relation_text', [
            'label' => __('Post html', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::WYSIWYG,
            'default' => '<h4>[post:title]</h4>[post:thumb]<p>[post:excerpt]</p><a class="btn btn-primary" href="[post:permalink]">READ MORE</a>',
            'description' => __("Define related post structure.", 'dynamic-content-for-elementor'),
            'dynamic' => [
                'active' => true,
            ],
            'condition' => [
                'pods_relation_render' => 'text',
            ],
                ]
        );

        $this->add_control(
                'pods_relation_format', [
            'label' => __('Display mode', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '' => __('Natural', 'dynamic-content-for-elementor'),
                'ul' => __('Unordered list', 'dynamic-content-for-elementor'),
                'ol' => __('Ordered list', 'dynamic-content-for-elementor'),
                'grid' => __('Grid', 'dynamic-content-for-elementor'),
                'tab' => __('Tabs', 'dynamic-content-for-elementor'),
                'accordion' => __('Accordion', 'dynamic-content-for-elementor'),
                'select' => __('Select', 'dynamic-content-for-elementor'),
            ],
                ]
        );

        $this->add_control(
                'pods_relation_tag', [
            'label' => __('HTML Tag', 'elementor'),
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
            'default' => 'h2',
                /* 'condition' => [
                  'pods_relation_render' => 'title',
                  ], */
                ]
        );
        $this->add_control(
                'pods_relation_link', [
            'label' => __('Link', 'elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'pods_relation_render' => 'title',
                //'pods_relation_format!' => ['tab','accordion','select'],
            ],
                ]
        );

        $this->add_control(
                'pods_relation_label', [
            'label' => __('Label', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '[post:title]',
            'placeholder' => '[post:title]',
            'condition' => [
                'pods_relation_format' => ['tab', 'accordion', 'select'],
            ],
                ]
        );
        $this->add_control(
                'pods_relation_close', [
            'label' => __('Close by default', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'pods_relation_format' => ['accordion', 'select'],
            ],
                ]
        );
        $this->add_control(
                'pods_relation_close_label', [
            'label' => __('Empty value text', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Choose an option', 'dynamic-content-for-elementor'),
            'condition' => [
                'pods_relation_close!' => '',
                'pods_relation_format' => 'select',
            ],
                ]
        );
        $this->add_responsive_control(
                'pods_relation_col', [
            'label' => __('Columns', 'dynamic-content-for-elementor'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 3,
            'min' => 1,
            'description' => __("Set 1 to show one result per line", 'dynamic-content-for-elementor'),
            'condition' => [
                'pods_relation_format' => 'grid',
            ],
                ]
        );
        $this->add_control(
                'pods_relation_tab', [
            'label' => __('Tab orientation', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'horizontal' => [
                    'title' => __('Horizontal', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-chevron-up',
                ],
                'vertical' => [
                    'title' => __('Vertical', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-chevron-left',
                ],
            ],
            'toggle' => false,
            'default' => 'horizontal',
            'condition' => [
                'pods_relation_format' => 'tab',
            ],
                ]
        );
        $this->end_controls_section();


        /***************************** STYLE **********************************/

        $this->start_controls_section(
                'section_style_title', [
            'label' => __('Title', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        /* $this->add_control(
          'pods_relation_size', [
          'label' => __('Size', 'elementor'),
          'type' => Controls_Manager::SELECT,
          'default' => 'default',
          'options' => [
          'default' => __('Default', 'elementor'),
          'small' => __('Small', 'elementor'),
          'medium' => __('Medium', 'elementor'),
          'large' => __('Large', 'elementor'),
          'xl' => __('XL', 'elementor'),
          'xxl' => __('XXL', 'elementor'),
          ],
          ]
          ); */
        $this->add_control(
                'pods_relation_title_padding', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .elementor-heading-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'pods_relation_title_margin', [
            'label' => __('Margin', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .elementor-heading-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'pods_relation_title_align', [
            'label' => __('Alignment', 'elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'elementor'),
                    'icon' => 'fa fa-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'elementor'),
                    'icon' => 'fa fa-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'elementor'),
                    'icon' => 'fa fa-align-right',
                ],
                'justify' => [
                    'title' => __('Justified', 'elementor'),
                    'icon' => 'fa fa-align-justify',
                ],
            ],
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .elementor-heading-title' => 'text-align: {{VALUE}};',
            ],
                ]
        );
        $this->add_control(
                'pods_relation_title_color', [
            'label' => __('Text Color', 'elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                // Stronger selector to avoid section style from overwriting
                '{{WRAPPER}} .elementor-heading-title' => 'color: {{VALUE}};',
            ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'pods_relation_title_typography',
            'selector' => '{{WRAPPER}} .elementor-heading-title',
                ]
        );

        $this->add_group_control(
                Group_Control_Text_Shadow::get_type(), [
            'name' => 'pods_relation_title_text_shadow',
            'selector' => '{{WRAPPER}} .elementor-heading-title',
                ]
        );

        /* $this->add_control(
          'pods_relation_blend_mode', [
          'label' => __('Blend Mode', 'elementor'),
          'type' => Controls_Manager::SELECT,
          'options' => [
          '' => __('Normal', 'elementor'),
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
          '{{WRAPPER}} .elementor-heading-title' => 'mix-blend-mode: {{VALUE}}',
          ],
          'separator' => 'none',
          ]
          ); */
        $this->end_controls_section();



        $this->start_controls_section(
                'section_style_atitle', [
            'label' => __('Title Active', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
            'pods_relation_format' => 'tab'
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Background::get_type(), [
            'name' => 'pods_relation_bgcolor_aitem',
            'label' => __('Background', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-tab-item.dce-tab-item-active',
                ]
        );
        $this->add_control(
                'pods_relation_color_aitem', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-tab-item.dce-tab-item-active .elementor-heading-title' => 'color: {{VALUE}};',
            ],
                ]
        );
        $this->end_controls_section();



        $this->start_controls_section(
                'section_style_item', [
            'label' => __('Item', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
            'pods_relation_format' => ['accordion', 'tab']
            ],
                ]
        );
        $this->add_control(
                'pods_relation_padding_item', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .dce-view-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'pods_relation_border_item',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-view-item',
                ]
        );
        $this->add_control(
            'pods_relation_border_radius_item', [
                'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .dce-view-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
                Group_Control_Background::get_type(), [
            'name' => 'pods_relation_bgcolor_item',
            'label' => __('Background', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-view-item',
                ]
        );
        $this->end_controls_section();



        $this->start_controls_section(
                'section_style_pane', [
            'label' => __('Pane', 'dynamic-content-for-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'pods_relation_format' => ['accordion', 'tab', 'grid', 'select', 'ul', 'ol']
            ],
                ]
        );
        $this->add_control(
                'pods_relation_padding_pane', [
            'label' => __('Padding', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .dce-view-pane' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'pods_relation_margin_pane', [
            'label' => __('Margin', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .dce-view-pane' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'pods_relation_border_pane',
            'label' => __('Border', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-view-pane',
                ]
        );
        $this->add_control(
            'pods_relation_border_radius_pane', [
                'label' => __('Border Radius', 'dynamic-content-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .dce-view-pane' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
                'pods_relation_color_pane', [
            'label' => __('Text Color', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .dce-view-pane' => 'color: {{VALUE}};',
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Background::get_type(), [
            'name' => 'pods_relation_bgcolor_pane',
            'label' => __('Background', 'dynamic-content-for-elementor'),
            'selector' => '{{WRAPPER}} .dce-view-pane',
                ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display(null, true);
        if (empty($settings))
            return;

        global $post;
        global $global_ID;
        $old_post = $post; //get_post();
        // ------------------------------------------

        if ($settings['pods_relation_field']) {
            
            if (pods( get_post_type(), get_the_ID() )) {
              $rel_posts = pods_field_raw($settings['pods_relation_field']);
            } else {
              $rel_posts = array();
            }
            //var_dump($rel_posts);
            $rel_post = false;
            if (is_numeric($rel_posts)) {
                $rel_post = array($rel_posts);
            } else if (isset($rel_posts['ID'])) {
                $rel_post = array($rel_posts['ID']);
            } else if (is_array($rel_posts)) {
                $rel_post = wp_list_pluck($rel_posts, 'ID');
            }
            //var_dump($rel_post);
            if ($rel_post) {
                
                if (is_array($rel_post) && !empty($rel_post)) {
                    if (count($rel_post) > 1 && $settings['pods_relation_format']) {

                        $labels = array();
                        if (in_array($settings['pods_relation_format'], array('tab', 'accordion', 'select'))) {
                            foreach ($rel_post as $arel) {
                                $post = get_post($arel);
                                $tmp = $global_ID;
                                $global_ID = $arel;
                                //var_dump($post->ID);
                                //setup_postdata( $post );
                                $labels[$post->ID] = \DynamicContentForElementor\DCE_Tokens::do_tokens($settings['pods_relation_label']);
                                $global_ID = $tmp;
                            }
                        }

                        switch ($settings['pods_relation_format']) {
                            case 'ul':
                                echo '<ul class="dce-pods-relational-list">';
                                break;
                            case 'ol':
                                echo '<ol class="dce-pods-relational-list">';
                                break;
                            case 'grid':
                                echo '<div class="dce-view-row grid-page grid-col-md-' . $settings['pods_relation_col'] . ' grid-col-sm-' . $settings['pods_relation_col_tablet'] . ' grid-col-xs-' . $settings['pods_relation_col_mobile'] . '">';
                                break;
                            case 'tab':
                                echo '<div class="dce-view-tab dce-tab dce-tab-' . $settings['pods_relation_tab'] . '"><ul>';
                                $i = 0;
                                foreach ($labels as $pkey => $alabel) {
                                    ?>
                                    <li>
                                        <a class="dce-view-item dce-tab-item<?php echo (!$i) ? ' dce-tab-item-active' : ''; ?>" href="#dce-pods-relational-post-<?php echo $this->get_id() . '-' . $pkey; ?>" onclick="jQuery('.elementor-element-<?php echo $this->get_id(); ?> .dce-pods-relational-post').hide();jQuery('.elementor-element-<?php echo $this->get_id(); ?> .dce-tab-item-active').removeClass('dce-tab-item-active');jQuery(jQuery(this).attr('href')).show();jQuery(this).addClass('dce-tab-item-active'); return false;">
                                            <<?php echo $settings['pods_relation_tag']; ?> class="elementor-heading-title">
                                    <?php echo $alabel; ?>
                                            </<?php echo $settings['pods_relation_tag']; ?>>
                                        </a>
                                    </li>
                                    <?php
                                    $i++;
                                }
                                echo '</ul><div class="dce-tab-content">';
                                break;
                            case 'select':
                                ?>
                                <select class="elementor-heading-title dce-view-select" onchange="jQuery('.elementor-element-<?php echo $this->get_id(); ?> .dce-pods-relational-post').slideUp();jQuery(jQuery(this).val()).slideDown();">
                                    <?php
                                    if ($settings['pods_relation_close'] && $settings['pods_relation_close_label']) {
                                        echo '<option value="#dce-view-no-show">' . $settings['pods_relation_close_label'] . '</option>';
                                    }
                                    foreach ($labels as $pkey => $alabel) {
                                        echo '<option value="#dce-pods-relational-post-' . $this->get_id() . '-' . $pkey . '">' . $alabel . '</option>';
                                    }
                                    ?>
                                </select>
                                <div class="dce-select-content">
                                    <?php
                                    break;
                            }
                        }
                        foreach ($rel_post as $rkey => $arel) {
                            $post = get_post($arel);
                            //var_dump($post);
                            $tmp = $global_ID;
                            $global_ID = $arel;
                            if (count($rel_post) > 1) {
                                switch ($settings['pods_relation_format']) {
                                    case 'ul':
                                    case 'ol':
                                        echo '<li class="dce-view-pane dce-pods-relational-post dce-pods-relational-post-' . $post->ID . '">';
                                        break;
                                    default:
                                        if ($settings['pods_relation_format'] == 'accordion' && $settings['pods_relation_render'] != 'title') {
                                            ?>
                                            <div class="dce-accordion-item">
                                                <a class="dce-view-item" href="#dce-pods-relational-post-<?php echo $this->get_id() . '-' . $post->ID; ?>" onclick="if (!jQuery(jQuery(this).attr('href')).is(':visible')) {
                                                                                                jQuery('.elementor-element-<?php echo $this->get_id(); ?> .dce-pods-relational-post').slideUp();
                                                                                                jQuery(jQuery(this).attr('href')).slideDown();
                                                                                            } else {
                                                                                                jQuery(jQuery(this).attr('href')).slideUp();
                                                                                            } return false;">
                                                    <<?php echo $settings['pods_relation_tag']; ?> class="elementor-heading-title">
                                                    <?php echo $labels[$post->ID]; ?>
                                                    </<?php echo $settings['pods_relation_tag']; ?>>
                                                </a>
                                            </div>
                                        <?php
                                        }
                                        $is_hidden = false;
                                        if (in_array($settings['pods_relation_format'], array('accordion', 'select'))) { // && $settings['pods_relation_render'] != 'title') {
                                            if (($settings['pods_relation_close'] && !$rkey) || $rkey) {
                                                $is_hidden = true;
                                            }
                                        }
                                        if (in_array($settings['pods_relation_format'], array('tab')) && $rkey) {
                                            $is_hidden = true;
                                        }
                                        $pstyle = ($is_hidden) ? ' style="display: none;"' : '';
                                        echo '<div id="dce-pods-relational-post-' . $this->get_id() . '-' . $post->ID . '" class="dce-view-pane dce-' . $settings['pods_relation_format'] . '-pane dce-pods-relational-post dce-pods-relational-post-' . $post->ID . ($settings['pods_relation_format'] == 'grid' ? ' item-page' : '') . '"' . $pstyle . '>';
                                        break;
                                }
                            }
                            if ($settings['pods_relation_render'] == 'template' && $settings['pods_relation_template']) {
                                echo do_shortcode('[dce-elementor-template id="' . $settings['pods_relation_template'] . '"]');
                            } elseif ($settings['pods_relation_render'] == 'text') {
                                echo \DynamicContentForElementor\DCE_Tokens::do_tokens($settings['pods_relation_text']);
                            } else {
                                if ($settings['pods_relation_link']) {
                                    echo '<a class="dce-pods-relational-post-link" href="' . get_permalink($post->ID) . '">';
                                }
                                echo '<' . $settings['pods_relation_tag'] . ' class="elementor-heading-title">' . get_the_title($post->ID) . '</' . $settings['pods_relation_tag'] . '>';
                                if ($settings['pods_relation_link']) {
                                    echo '</a>';
                                }
                            }
                            if (count($rel_post) > 1) {
                                switch ($settings['pods_relation_format']) {
                                    case 'ul':
                                    case 'ol':
                                        echo '</li>';
                                        break;
                                    default:
                                        echo '</div>';
                                        break;
                                }
                            }
                            $global_ID = $tmp;
                        }
                        if (count($rel_post) > 1 && $settings['pods_relation_format']) {
                            switch ($settings['pods_relation_format']) {
                                case 'ul':
                                    echo '</ul>';
                                    break;
                                case 'ol':
                                    echo '</ol>';
                                    break;
                                case 'tab':
                                    echo '</div>';
                                case 'grid':
                                case 'select':
                                    echo '</div>';
                                    break;
                            }
                        }
                    }
                }
            }

            wp_reset_postdata();
            $post = $old_post;
            setup_postdata($old_post);
        }

    }
