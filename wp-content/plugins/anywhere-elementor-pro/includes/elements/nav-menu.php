<?php

namespace Aepro;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

class Aepro_Nav_Menu extends Widget_Base
{
    public function get_name()
    {
        return 'ae-nav-menu';
    }

    public function get_title()
    {
        return __('AE - Nav Menu', 'ae-pro');
    }

    public function get_icon()
    {
        return 'eicon-menu';
    }

    public function get_categories()
    {
        return ['ae-template-elements'];
    }

    protected function _register_controls()
    {
        $helper = new Helper();

        $this->start_controls_section(
            'nav_menu_section',
            [
                'label' => __( 'Settings', 'ae-pro' ),
            ]
        );

        $this->add_control(
            'nav_menu',
            [
                'label'   => __( 'Select Menu', 'ae-pro' ),
                'type'    => Controls_Manager::SELECT,
                'options' => $helper->ae_get_wp_nav_menu(),
                'default' => '',
                'frontend_available' => true,
            ]
        );

        /*$this->add_responsive_control(
            'menu_layout',
            [
                'label'   => __( 'Menu Layout', 'ae-pro' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'simple',
                'options' => [
                    'simple' => __( 'Simple', 'ae-pro' ),
                    'slideout'  => __( 'Slideout', 'ae-pro' )
                ],
                'prefix_class' => 'ae-nav-menu-'
            ]
        );*/

        $this->add_responsive_control(
            'nav_alignment',
            [
                'label' => __( 'Horizontal Position', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'ae-pro' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'ae-pro' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'ae-pro' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
                'prefix_class' => 'ae-nav-',
                /*'selectors' => [
                    '{{WRAPPER}} ' => '{{VALUE}}',
                ],*/
                'default' => 'right',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings();
        $this->add_render_attribute('nav-menu-wrapper','class',['ae-nav-menu-wrapper', 'ae-nav-menu-simple']);
        $this->add_render_attribute('nav-menu','class','ae-nav-menu');
        $this->add_render_attribute('nav-container', 'class', 'ae-nav-container');
        $nav_menu = $settings['nav_menu'];
        $slideout_togle = '';
        /*if($settings['menu_layout'] == 'slideout'){ */
            $slideout_toggle = '<span class="ae-nav-menu-toggle"><i class="fa fa-plus"></i></span>';
        /*}*/

        $nav_menu_args = array(
            'fallback_cb'    => false,
            'container'      => 'nav',
            'container_class'=> 'nav-container',
            'menu_id'        => 'ae-nav-menu',
            'menu_class'     => 'ae-nav-menu',
            'menu'           => $nav_menu,
            'depth'          => 0,
            'walker'         => '',
            'after'          => $slideout_toggle
        );
    ?>
        <div <?php echo $this->get_render_attribute_string ('nav-menu-wrapper'); ?>>
            <button id="ae-menu-toggle" class="ae-menu-toggle"><i class="fa fa-navicon"></i></button>
            <?php
            wp_nav_menu(
                apply_filters('ae_nav_menu_args',
                    $nav_menu_args,
                    $nav_menu
                ));
            ?>
        </div>
<?php

    }
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Nav_Menu() );