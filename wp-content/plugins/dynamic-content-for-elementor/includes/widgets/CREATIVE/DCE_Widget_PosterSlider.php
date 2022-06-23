<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Poster Slider
 *
 * Elementor widget for Dynamic Content Elements
 *
 */

class DCE_Widget_PosterSlider extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-posterSlider';
    }
    static public function is_enabled() {
        return false;
    }
    public function get_title() {
        return __('Poster Slider (acf)', 'dynamic-content-for-elementor');
    }
    public function get_icon() {
        return 'icon-dyn-poster todo';
    }
    public function get_script_depends() {
        return [ 'jquery', 'dce-poster-slider'];
    }
    public function get_style_depends() {
        return [ 'dce-posterSlider' ];
    }
    static public function get_position() {
        return 5;
    }
    protected function _register_controls() {
        $this->start_controls_section(
                'section_cpt', [
            'label' => __('Post Type', 'dynamic-content-for-elementor'),
                ]
        );
        $this->add_control(
                'post_type', [
            'label' => __('Post Type', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => DCE_Helper::get_post_types(),
            'default' => 'post',
                ]
        );
        $this->add_control(
                'category', [
            'label' => __('Category ID', 'dynamic-content-for-elementor'),
            'description' => __('Comma separated list of category ids', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'condition' => [
                'post_type' => 'post'
            ]
                ]
        );

        $this->add_control(
                'num_post', [
            'label' => __('Number of SliderPoster', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => '5'
                ]
        );

        $this->add_control(
                'post_offset', [
            'label' => __('Post Offset', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::NUMBER,
            'default' => '0'
                ]
        );

        $this->add_control(
                'orderby', [
            'label' => __('Order By', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => DCE_Helper::get_post_orderby_options(),
            'default' => 'date',
                ]
        );

        $this->add_control(
                'order', [
            'label' => __('Order', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'asc' => 'Ascending',
                'desc' => 'Descending'
            ],
            'default' => 'desc',
                ]
        );

        // ------------------------

        $this->add_group_control(
                Group_Control_Image_Size::get_type(), [
            'name' => 'size',
            'label' => __('Image Size', 'dynamic-content-for-elementor'),
            'default' => 'large',
                ]
        );

        $this->add_control(
                'show_title', [
            'label' => __('Show Title', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
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
            'default' => '1'
                ]
        );


        $this->add_control(
                'show_image', [
            'label' => __('Show Image', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
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
            'default' => '1'
                ]
        );


        $this->add_responsive_control(
                'image_align', [
            'label' => __('Image Alignment', 'dynamic-content-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-left',
                ],
                'none' => [
                    'title' => __('Center', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'dynamic-content-for-elementor'),
                    'icon' => 'fa fa-align-right',
                ]
            ],
            'default' => 'left',
            'condition' => [
                'show_image' => '1',
            ],
            'selectors' => [
                '{{WRAPPER}} .dce-sliderposter-wrappper' => 'float: {{VALUE}};',
            ],
            'prefix_class' => 'dce-sliderposter-img-align-'
                ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        global $global_ID;
        $args = array(
            'post_type' => $settings['post_type'],
            'posts_per_page' => $settings['num_post'],
            'offset' => $settings['post_offset'],
            'order' => $settings['order'],
            'orderby' => $settings['orderby'],
            'post_status' => 'publish'
        );
        $settings['num_post'];
        //echo $settings['order'];
        //echo $settings['orderby'];
        //echo $settings['size'];
        // Build the WordPress query
        $p_query = new \WP_Query($args);

        $counter = 0;

        //Output sliderposter
        if ($p_query->have_posts()) :
            //echo $settings['post_type'];
            // $imageBg = get_field('gbimage_realiz',get_the_ID());
            ?>

            <div class="dce-sliderposter-wrap">
            <?php
            //print_r($settings['slides_to_show']);
            // Start loop
            while ($p_query->have_posts()) : $p_query->the_post();
                $id_page = get_the_ID();
                //$gbimage_realiz = get_field('gbimage_realiz');
                $gbimage_realiz = get_post_meta( $id_page, 'gbimage_realiz' );
                $image_url = Group_Control_Image_Size::get_attachment_image_src($gbimage_realiz, 'size', $settings);
                //$posterimage_realiz = get_field('posterimage_realiz');
                $posterimage_realiz = get_post_meta( $id_page, 'posterimage_realiz' );
                $image_poster = wp_get_attachment_url($posterimage_realiz, 'full');
                //$dataPoster1 = get_field('anno_realiz');
                $dataPoster1 = get_post_meta( $id_page, 'anno_realiz' );
                //$dataPoster2 = get_field('anno_realiz_2');
                $dataPoster2 = get_post_meta( $id_page, 'anno_realiz_2' );
                //$used_in_home = get_field('usehome_realiz');
                $used_in_home = get_post_meta( $id_page, 'usehome_realiz' );
                //
                if ($used_in_home == 1) {
                    //if ( has_post_thumbnail() && $settings['show_image'] != 0 ) {
                    if ($image_url && $image_poster && $settings['show_image'] != 0) {
                        //echo $settings['image-size'];
                        //$image_url = Group_Control_Image_Size::get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'size', $settings );
                        //echo $image_url;
                        //the_post_thumbnail($settings['image-size']);
                        ?>
                            <div class="dce-sliderposter">

                                <div class="disteso sliderposter">
                            <!-- <img src="<?php echo $image_url; ?>" /> -->
                                    <div class="disteso bg-image-posterslider" style="background-image: url(<?php echo $image_url; ?>);"></div>
                            <?php
                            /**/
                            //echo "aaaa ".get_the_ID().'  '.get_field('gbimage_realiz');
                            //echo wp_get_attachment_url( get_field('gbimage_realiz'), 'full' )
                            ?>
                                    <div class="poster">

                                        <img src="<?php echo plugins_url('images/asticella.png', dirname(__FILE__)); ?>" class="asta1" />
                                        <img src="<?php echo plugins_url('images/asticella.png', dirname(__FILE__)); ?>" class="asta2" />
                                        <div class="poster-wrapper">
                                            <a href="<?php the_permalink(); ?>">
                                                <img src="<?php echo $image_poster; ?>" title="<?php the_title(); ?>" class="cartello" />
                                                <div class="description-poster"><div class="tit-poster"><?php echo get_the_title(); ?></div> <div class="date-poster"><?php echo '<span>' . $dataPoster1 . '</span><span>' . $dataPoster2 . '</span>'; ?></div></div>
                                                <div class="overlay-poster"></div>
                                            </a>
                                        </div>

                                    </div>

                                </div>


                        <?php ?>
                                <div class="dce-sliderpostercontent">
                        <?php if ($settings['show_title'] != 0) { ?>
                                        <h3><a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a></h3>
                        <?php } ?>
                        <?php //the_excerpt() . ' &hellip;';  ?> </div>
                            </div>
                        <?php
                        $counter++;
                        //
                    }
                }
            endwhile;
            ?>

            </div>

        <?php
        // Reset the post data to prevent conflicts with WP globals
        wp_reset_postdata();
  
        // End post check
        endif;
    }


}
