<?php

namespace Aepro\Woo_Products\Skins;

use Aepro\Helper;
use Elementor\Controls_Manager;
use Elementor\Skin_Base as Ae_Skin_Base;
use Elementor\Widget_Base;
use \WP_Query;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Plugin;
use Elementor\Group_Control_Background;


abstract class Skin_Base extends Ae_Skin_Base {

    protected function _register_controls_actions()
    {
        add_action('elementor/element/ae-woo-products/section_layout/before_section_end', [$this, 'register_controls']);

        add_action('elementor/element/ae-woo-products/section_style/before_section_end', [$this, 'register_style_controls']);
    }

    public function register_controls(Widget_Base $widget){
        $this->parent = $widget;


    }

    public function render()
    {
        // TODO: Implement render() method.
    }

    protected function product_types(){
        return [
          'related' => __('Related Products', 'ae-pro'),
          'upsell'  => __('Upsell Products', 'ae-pro')
        ];
    }

    protected function product_query_controls(){
        $helper = new Helper();
        $this->add_control(
            'product_type',
            [
                'label' => __('Product Type','ae-pro'),
                'type'  => Controls_Manager::SELECT,
                'options' => $this->product_types(),
                'default' => 'related'
            ]
        );

        $this->add_control(
            'template',
            [
                'label'     =>  __('Template','ae-pro'),
                'type'      =>  Controls_Manager::SELECT,
                    'options'   =>  $helper->ae_block_layouts(),
                'description' => __('Know more about templates <a href="http://aedocs.webtechstreet.com/article/9-creating-block-layout-in-anywhere-elementor-pro" target="_blank">Click Here</a>','ae-pro')
            ]
        );

        $this->add_control(
            'count',
            [
                'label' => __('Count', 'ae-pro'),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
            ]
        );
    }

    protected function grid_view_controls(){
        $this->add_control(
            'gird_view_control',
            [
                'label' => __('Layout', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'ae-pro'),
                'type'  => Controls_Manager::NUMBER,
                'desktop_default' => '4',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'min' => 1,
                'max' => 6,
                'selectors' => [
                    '{{WRAPPER}} .ae-grid-item' => 'width: calc(100%/{{VALUE}})',
                ]
            ]
        );

        /*$this->add_responsive_control(
            'col_gap',
            [
                'label' => __('Columns Gap' , 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'range'=> [
                    'px'=>[
                        'min' => 0,
                        'max' => 20,
                        'step' => 2,
                    ]
                ],
                'condition' => [
                    $this->get_control_id('columns!') => 1,
                    $this->get_control_id('masonry!')=> 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-grid-item' => 'padding-left:calc({{SIZE}}{{UNIT}}/2); padding-right:calc({{SIZE}}{{UNIT}}/2);',

                ],
            ]

        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label' => __('Row Gap', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>[
                    'unit'=>'px',
                    'size'=>5,
                ],
                'range'=>[
                    'px'=>[
                        'min' =>0,
                        'max' =>20,
                        'step' => 2,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-grid-item' => 'margin-bottom:{{SIZE}}{{UNIT}};',
                ],
                'condition'=>[
                    $this->get_control_id('masonry!')=> 'yes'
                ]

            ]
        );*/

        $this->add_control(
            'masonry',
            [
                'label' =>__('Masonry Layout' , 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('On', 'ae-pro'),
                'label_off' => __('Off', 'ae-pro'),
                'return_value' => 'yes',
                'condition' => [
                    $this->get_control_id('columns!') => 1
                ],
                'prefix_class' => 'ae-masonry-'
            ]

        );

        $this->add_responsive_control(
            'gutter',
            [
                'label' => __('Gutter','ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'range'=>[
                    'px'=>[
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ]
                ],
                'default'=>[
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-grid-item' => 'padding:calc({{SIZE}}{{UNIT}}/2);'
                ]
            ]
        );





    }

    protected function grid_style_control(){

        $this->start_controls_tabs('style_tabs');

        $this->start_controls_tab(
            'normal',
            [
                'label' => __('Normal','ae-pro')
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'grid_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-grid-item .ae-grid-item-inner ',
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-grid-item .ae-grid-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'label' => __( 'Item Shadow', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-grid-item .ae-grid-item-inner',
            ]
        );

        $this->end_controls_tab();


        $this->start_controls_tab(
            'hover',
            [
                'label' => __('Hover','ae-pro')
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'grid_border_hover',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-grid-item .ae-grid-item-inner:hover ',
            ]
        );

        $this->add_control(
            'item_border_radius_hover',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-grid-item .ae-grid-item-inner:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow_hover',
                'label' => __( 'Item Shadow', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-grid-item .ae-grid-item-inner:hover ',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
    }

    protected function common_controls()
    {
        $this->add_control(
            'common_comtrols',
            [
                'label' => __('Setting', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'orientation',
            [
                'label' => __('Orientation', 'ae-pro'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'horizontal' => [
                        'title' => __('Horizontal', 'ae-pro'),
                        'icon' => 'fa fa-arrows-h',
                    ],
                    'vertical' => [
                        'title' => __('Vertical', 'ae-pro'),
                        'icon' => 'fa fa-arrows-v',
                    ]
                ],
                'default' => 'horizontal'
            ]
        );

        $this->add_control(
            'height',
            [
                'label' => __('Height ', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 300,
                ],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                        'step' => 50,
                    ]
                ],
                'condition' => [
                    $this->get_control_id('orientation') => 'vertical'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-swiper-outer-wrapper' => 'height: {{SIZE}}{{UNIT}};'
                ]
            ]
        );


        $this->add_control(
            'speed',
            [
                'label' => __('Speed', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 300,
                ],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 900,
                        'step' => 1
                    ]
                ]

            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('On', 'ae-pro'),
                'label_off' => __('Off', 'ae-pro'),
                'return_value' => 'yes',
            ]

        );

        $this->add_control(
            'duration',
            [
                'label' => __('Duration', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 3000,
                ],
                'range' => [
                    'px' => [
                        'min' => 1000,
                        'max' => 10000,
                        'step' => 1000,
                    ]
                ],
                'condition' => [
                    $this->get_control_id('autoplay') => 'yes'
                ],
            ]
        );

        // Todo:: different effects management
        $this->add_control(
            'effect',
            [
                'label' => __('Effects', 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'fade' => __('Fade', 'ae-pro'),
                    'slide' => __('Slide', 'ae-pro'),
                    'cube' => __('Cube', 'ae-pro'),
                    'coverflow' => __('Coverflow', 'ae-pro'),
                    'flip' => __('Flip', 'ae-pro'),
                ],
                'default' => 'slide',
            ]
        );

        $this->add_control(
            'space',
            [
                'label' => __('Space Between Slides', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 5,
                    ]
                ]
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __('Loop', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'ae-pro'),
                'label_off' => __('No', 'ae-pro'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'zoom',
            [
                'label' => __('Zoom', 'ae-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'ae-pro'),
                'label_off' => __('No', 'ae-pro'),
                'return_value' => 'yes',
            ]
        );
    }

    protected function product_carousel_control()
    {

        $this->add_control(
            'image_carousel',
            [
                'label' => __('Carousel', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'slide_per_view',
            [
                'label' => __( 'Products Per View', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'options'=> [
                    '1'=>__('1','ae-pro'),
                    '2'=>__('2','ae-pro'),
                    '3'=>__('3','ae-pro'),
                    '4'=>__('4','ae-pro'),
                ],
                'default' => 3
            ]
        );

    }

    protected function pagination_controls(){



        $this->add_control(
            'pagination_heading',
            [
                'label' => __('Pagination', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );



        $this -> add_control(
            'ptype',
            [
                'label' => __(' Pagination Type' , 'ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' =>
                    [
                        ''        => __('None', 'ae-pro'),
                        'bullets' => __( 'Bullets' , 'ae-pro'),
                        'fraction' =>__( 'Fraction' , 'ae-pro'),
                        'progress' =>__('Progress' , 'ae-pro'),
                    ],
                'default'=>'bullets'
            ]
        );

        $this->add_control(
            'clickable',
            [
                'label' =>__('Clickable' , 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on'=>__('Yes', 'ae-pro'),
                'label_off' =>__('No' , 'ae-pro'),
                'condition'=> [
                    $this->get_control_id('ptype') => 'bullets'
                ],
            ]
        );

        $this->add_control(
            'navigation_button',
            [
                'label' => __('Previous/Next Button' , 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes' , 'ae-pro'),
                'label_off' => __('No' , 'ae-pro'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'keyboard',
            [
                'label' => __('Keyboard Control' , 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default'=> 'yes',
                'label_on'=>__('Yes', 'ae-pro'),
                'label_off' =>__('No' , 'ae-pro'),
                'return_value'=>'yes',
            ]
        );

        $this->add_control(
            'scrollbar',
            [
                'label' =>__('Scroll bar', 'ae-pro'),
                'type' =>Controls_Manager::SWITCHER,
                'default'=>'yes',
                'label_on' =>__('Yes' , 'ae-pro'),
                'label_off'=>__('No' , 'ae-pro'),
                'return_value' => 'yes',
            ]
        );
    }

    public function common_style_control(){

        $this->add_control(
            'heading_style_arrow',
            [
                'label' => __('Arrow', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' =>
                    [
                        $this->get_control_id('navigation_button') => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'arrow_size',
            [
                'label' => __('Arrow Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 5
                    ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1
                    ],
                'condition' =>
                    [
                        $this->get_control_id('navigation_button') => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __('Arrow Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'condition' =>
                    [
                        $this->get_control_id('navigation_button') => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'heading_style_dots',
            [
                'label' => __('Dots', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' =>
                    [
                        $this->get_control_id('ptype') => 'bullets'
                    ]
            ]
        );

        $this->add_control(
            'dots_size',
            [
                'label' => __('Dots Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 5
                    ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1
                    ],
                'condition' =>
                    [
                        $this->get_control_id('ptype') => 'bullets'
                    ]
            ]
        );

        $this->add_control(
            'dots_color',
            [
                'label' => __('Dots Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'condition' =>
                    [
                        $this->get_control_id('ptype') => 'bullets'
                    ]
            ]
        );


        $this->add_control(
            'heading_style_scroll',
            [
                'label' => __('Scrollbar', 'ae-pro'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' =>
                    [
                        $this->get_control_id('scrollbar') => 'yes'
                    ]
            ]
        );
        $this->add_control(
            'scroll_size',
            [
                'label' => __('Scrollbar Size', 'ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'default' =>
                    [
                        'size' => 5
                    ],
                'range' =>
                    [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1
                    ],
                'condition' =>
                    [
                        $this->get_control_id('scrollbar') => 'yes'
                    ]
            ]
        );

        $this->add_control(
            'scrollbar_color',
            [
                'label' => __('Scrollbar Color', 'ae-pro'),
                'type' => Controls_Manager::COLOR,
                'condition' =>
                    [
                        $this->get_control_id('scrollbar') => 'yes'
                    ]
            ]
        );

    }

    protected function get_products_query_args(){
        $helper = new Helper();
        $data_type = $this->get_instance_value('product_type');
        $count = $this->get_instance_value('count');

        switch($data_type){
            case 'related' : $product = $helper->get_ae_woo_product_data();
                             if(is_null($product) || $product->post_type != 'product'){
                                 return [];
                             }
                             $related_products = wc_get_related_products($product->get_id(),$count, $product->get_upsell_ids());
                             $args = [
                                 'post_type' => 'product',
                                 'stock' => 1,
                                 'orderby' =>'date',
                                 'order' => 'DESC',
                                 'post__in' => $related_products
                             ];
                             break;

            case 'upsell'   : $product = $helper->get_ae_woo_product_data();
                              if(is_null($product) || $product->post_type != 'product'){
                                  return [];
                              }
                              $upsell_products = $product->get_upsell_ids();
                                $args = [
                                    'post_type' => 'product',
                                    'posts_per_page' => $count,
                                    'stock' => 1,
                                    'orderby' =>'date',
                                    'order' => 'DESC',
                                    'post__in' => $upsell_products
                                ];
                                break;
        }

        return $args;
    }

    protected function swiper_html(){
        $args = $this->get_products_query_args();
        $templates = $this->get_instance_value('template');
        $slide_per_view = $this->get_instance_value('slide_per_view');
        $direction = $this->get_instance_value('orientation');
        $speed = $this->get_instance_value('speed');
        $autoplay = $this->get_instance_value('autoplay');
        $duration = $this->get_instance_value('duration');
        $effect = $this->get_instance_value('effect');
        $space = $this->get_instance_value('space');
        $loop = $this->get_instance_value('loop');
        $zoom = $this->get_instance_value('zoom');
        $pagination_type = $this->get_instance_value('ptype');
        $navigation_button = $this->get_instance_value('navigation_button');
        $clickable = $this->get_instance_value('clickable');
        $keyboard = $this->get_instance_value('keyboard');
        $scrollbar = $this->get_instance_value('scrollbar');
        $ptype= $this->get_instance_value('ptype');





            $this->parent->add_render_attribute('outer-wrapper', 'class', 'ae-swiper-outer-wrapper');
            $this->parent->add_render_attribute('outer-wrapper', 'data-direction', $direction);
            $this->parent->add_render_attribute('outer-wrapper', 'data-speed', $speed['size']);
            if ($autoplay == 'yes') {
                $this->parent->add_render_attribute('outer-wrapper', 'data-autoplay', $autoplay);
            }
            if ($autoplay == 'yes') {
                $this->parent->add_render_attribute('outer-wrapper', 'data-duration', $duration['size']);
            }
            $this->parent->add_render_attribute('outer-wrapper', 'data-effect', $effect);
            $this->parent->add_render_attribute('outer-wrapper', 'data-space', $space['size']);
            if ($loop == 'yes') {
                $this->parent->add_render_attribute('outer-wrapper', 'data-loop', $loop);
            }
            else{
                autoplayStopOnLast:true;
            }
            if ($zoom == 'yes') {
                $this->parent->add_render_attribute('outer-wrapper', 'data-zoom', $zoom);
            }

            if(!empty($slide_per_view)){
                $this->parent->add_render_attribute('outer-wrapper', 'data-slides-per-view', $slide_per_view);
            }


            if ($ptype != '') {
                $this->parent->add_render_attribute('outer-wrapper', 'data-ptype', $ptype);
            }
            if ($pagination_type == 'bullets' && $clickable == 'yes') {
                $this->parent->add_render_attribute('outer-wrapper', 'data-clickable', $clickable);
            }
            if($navigation_button == 'yes'){
                $this-> parent->add_render_attribute('outer-wrapper', 'data-navigation', $navigation_button);
            }
            if($keyboard == 'yes') {
                $this->parent->add_render_attribute('outer-wrapper', 'data-keyboard', $keyboard);
            }
            if($scrollbar == 'yes') {
                $this->parent->add_render_attribute('outer-wrapper', 'data-scrollbar', $scrollbar);
            }
            ?>
            <div <?php echo $this->parent->get_render_attribute_string('outer-wrapper'); ?> >
                <div class="ae-swiper-container">
                    <div class="ae-swiper-wrapper">
                        <?php

                        $loop = new WP_Query( $args );
                        while ( $loop->have_posts() ) {
                            $loop->the_post();
                            global $product;
                            ?>
                            <div class="ae-swiper-slide">
                                <?php   echo Plugin::instance()->frontend->get_builder_content( $templates );?>
                            </div>
                            <?php

                        }
                        wp_reset_postdata();
                        ?>
                    </div>

                    <?php if($pagination_type != ''){ ?>
                        <div class = "ae-swiper-pagination"></div>
                    <?php } ?>

                    <?php if($navigation_button == 'yes'){ ?>
                        <div class = "ae-swiper-button-prev"></div>
                        <div class = "ae-swiper-button-next"></div>
                    <?php } ?>

                    <?php if($scrollbar == 'yes'){ ?>
                        <div class = "ae-swiper-scrollbar"></div>

                    <?php } ?>

                </div>
            </div>
        <?php
    }
}