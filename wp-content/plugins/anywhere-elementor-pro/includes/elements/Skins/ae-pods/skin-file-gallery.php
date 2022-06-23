<?php

namespace Aepro\Ae_Pods\Skins;

use Aepro\Aepro;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use Aepro\Classes\PodsMaster;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Icons_Manager;
use function wp_get_attachment_caption;


class Skin_File_Gallery extends Skin_Base
{

    public function get_id()
    {
        return 'file_gallery';
    }

    public function get_title()
    {
        return __('File - Gallery', 'ae-pro');
    }

    protected function _register_controls_actions()
    {

        parent::_register_controls_actions();
        add_action('elementor/element/ae-pods/general/after_section_end', [$this, 'register_style_controls']);
    }

    public function register_controls(Widget_Base $widget)
    {

        $this->parent = $widget;

        parent::gallery_field_control();
        parent::register_gallery_type();


    }

    public function register_style_controls(){

        //Carousel Controls
        parent::gallery_image_carousel_control();
        parent::gallery_pagination_controls();
        parent::gallery_common_style_control();

        //Grid Controls
        parent::grid_view();
        parent::grid_style();
        parent::grid_overlay_controls();
        parent::grid_overlay_style_control();

    }

    public function render()
    {

        $settings = $this->parent->get_settings_for_display();
        $link_text = '';

        $field_args = [
            'field_name' => $settings['field_name'],
            'field_type' => $settings['field_type'],

        ];

        if ($settings['pods_option_name'] != ''){
            $field_args['pods_option_name'] = $settings['pods_option_name'];
        }

        $images_array = PodsMaster::instance()->get_field_object($field_args);

        if(isset($images_array) && !empty($images_array)) {
            $field_options = PodsMaster::instance()->get_field_options( $field_args );
            if ($field_options['file_format_type'] == 'single') {
                $images[0] = $images_array;
            } else {
                $images = $images_array;
            }
        }

        switch ($this->get_instance_value('gallery_type')) {

            case 'carousel':
                    $image_size = $this->get_instance_value('thumbnail_size');
                    // $images = $this->get_gallery_data();
                    $slide_per_view['desktop'] = $this->get_instance_value('slide_per_view');
                    $slide_per_view['tablet'] = $this->get_instance_value('slide_per_view_tablet');
                    $slide_per_view['mobile'] = $this->get_instance_value('slide_per_view_mobile');

                    $slides_per_group['desktop'] = $this->get_instance_value('slides_per_group');
                    $slides_per_group['tablet'] = $this->get_instance_value('slides_per_group_tablet');
                    $slides_per_group['mobile'] = $this->get_instance_value('slides_per_group_mobile');
                    //echo '<pre>';print_r($slide_per_view);'</pre>';

                    // $direction = $this->get_instance_value('orientation');
                    $speed = $this->get_instance_value('speed');
                    $autoplay = $this->get_instance_value('autoplay');
                    $duration = $this->get_instance_value('duration');
                    $effect = $this->get_instance_value('effect');
                    $space['desktop'] = $this->get_instance_value('space')['size'];
                    $space['tablet'] = $this->get_instance_value('space_tablet')['size'];
                    $space['mobile'] = $this->get_instance_value('space_mobile')['size'];
                    //print_r(json_encode($space));
                    $loop = $this->get_instance_value('loop');
                    $auto_height = $this->get_instance_value('auto_height');
                    $zoom = $this->get_instance_value('zoom');
                    $pagination_type = $this->get_instance_value('ptype');
                    $navigation_button = $this->get_instance_value('navigation_button');
                    $clickable = $this->get_instance_value('clickable');
                    $keyboard = $this->get_instance_value('keyboard');
                    $scrollbar = $this->get_instance_value('scrollbar');
                    $ptype = $this->get_instance_value('ptype');


                    if (!empty($images)) {

                        $this->parent->add_render_attribute('outer-wrapper', 'class', 'ae-swiper-outer-wrapper ae-acf-file-gallery');
                        // $this->parent->add_render_attribute('outer-wrapper', 'data-direction', $direction);
                        $this->parent->add_render_attribute('outer-wrapper', 'data-speed', $speed['size']);
                        if ($autoplay == 'yes') {
                            $this->parent->add_render_attribute('outer-wrapper', 'data-autoplay', $autoplay);
                        }
                        if ($autoplay == 'yes') {
                            $this->parent->add_render_attribute('outer-wrapper', 'data-duration', $duration['size']);
                        }
                        $this->parent->add_render_attribute('outer-wrapper', 'data-effect', $effect);
                        $this->parent->add_render_attribute('outer-wrapper', 'data-space', json_encode($space, JSON_NUMERIC_CHECK));
                        if ($loop == 'yes') {
                            $this->parent->add_render_attribute('outer-wrapper', 'data-loop', $loop);
                        } else {
                            autoplayStopOnLast:
                            true;
                        }

                        if ($auto_height == 'yes') {
                            $this->parent->add_render_attribute('outer-wrapper', 'data-auto-height', 'true');
                        } else {
                            $this->parent->add_render_attribute('outer-wrapper', 'data-auto-height', 'false');
                        }
                        if ($zoom == 'yes') {
                            $this->parent->add_render_attribute('outer-wrapper', 'data-zoom', $zoom);
                        }

                        if (!empty($slide_per_view)) {
                            $this->parent->add_render_attribute('outer-wrapper', 'data-slides-per-view', json_encode($slide_per_view, JSON_NUMERIC_CHECK));
                        }
                        if (!empty($slides_per_group)) {
                            $this->parent->add_render_attribute('outer-wrapper', 'data-slides-per-group', json_encode($slides_per_group, JSON_NUMERIC_CHECK));
                        }


                        if ($ptype != '') {
                            $this->parent->add_render_attribute('outer-wrapper', 'data-ptype', $ptype);
                        }
                        if ($pagination_type == 'bullets' && $clickable == 'yes') {
                            $this->parent->add_render_attribute('outer-wrapper', 'data-clickable', $clickable);
                        }
                        if ($navigation_button == 'yes') {
                            $this->parent->add_render_attribute('outer-wrapper', 'data-navigation', $navigation_button);
                        }
                        if ($keyboard == 'yes') {
                            $this->parent->add_render_attribute('outer-wrapper', 'data-keyboard', $keyboard);
                        }
                        if ($scrollbar == 'yes') {
                            $this->parent->add_render_attribute('outer-wrapper', 'data-scrollbar', $scrollbar);
                        }
                        ?>
                        <?php
                        if ($this->get_instance_value('open_lightbox') != 'no') {
                            $this->parent->add_render_attribute('link', [
                                'data-elementor-open-lightbox' => $this->get_instance_value('open_lightbox'),
                                'data-elementor-lightbox-slideshow' => 'ae-acf-gallery-' . rand(0, 99999),
                            ]);
                            if (Plugin::$instance->editor->is_edit_mode()) {
                                $this->parent->add_render_attribute('link', [
                                    'class' => 'elementor-clickable',
                                ]);
                            }
                        }
                        ?>
                        <div <?php echo $this->parent->get_render_attribute_string('outer-wrapper'); ?> >
                            <div class="ae-swiper-container swiper-container">
                                <div class="ae-swiper-wrapper swiper-wrapper">

                                    <?php
                                    foreach ($images as $image) {
                                        ?>
                                        <div class="ae-swiper-slide swiper-slide">
                                            <div class="ae-swiper-slide-wrapper swiper-slide-wrapper">
                                                <?php if ($this->get_instance_value('open_lightbox') != 'no') { ?>
                                                <a <?php echo $this->parent->get_render_attribute_string('link'); ?>
                                                    href="<?php echo wp_get_attachment_url($image['id'], 'full'); ?>">
                                                    <?php } ?>
                                                    <?php if ($this->get_instance_value('enable_image_ratio') == 'yes') { ?>
                                                        <div class="ae-acf-gallery-image"></div>
                                                    <?php } ?>

                                                    <?php echo wp_get_attachment_image($image['ID'], $image_size); ?>

                                                    <?php if ($this->get_instance_value('open_lightbox') != 'no') { ?>
                                                </a>
                                                    <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                                <?php if ($pagination_type != '') { ?>
                                    <div class="ae-swiper-pagination swiper-pagination"></div>
                                <?php } ?>

                                <?php if ($navigation_button == 'yes') { ?>
                                    <div class="ae-swiper-button-prev swiper-button-prev"></div>
                                    <div class="ae-swiper-button-next swiper-button-next"></div>
                                <?php } ?>

                                <?php if ($scrollbar == 'yes') { ?>
                                    <div class="ae-swiper-scrollbar swiper-scrollbar"></div>

                                <?php } ?>

                            </div>
                        </div>

                    <?php }
                    break;
            case 'grid' :
                $image_size = $this->get_instance_value('thumbnail_size');
                $masonry= $this->get_instance_value('masonry');
                $animation = $this->get_instance_value('animation');
                //$icon=$this->get_instance_value('icon');
                $icon = $this->get_instance_value('selected_icon');

                $caption=$this->get_instance_value('caption');

                $this->parent->add_render_attribute('grid-wrapper','class','ae-masonry-'.$masonry);
                $this->parent->add_render_attribute('grid-wrapper','class','ae-grid-wrapper');
                ?>
                <?php
                $this->parent->add_render_attribute('link', [
                    'data-elementor-open-lightbox' => $this->get_instance_value('open_lightbox'),
                    'data-elementor-lightbox-slideshow' => 'ae-acf-gallery-'.rand(0,99999), 'data-elementor-lightbox-title' =>
                    'testing'
                ]);
                if (Plugin::$instance->editor->is_edit_mode()) {
                    $this->parent->add_render_attribute('link', [
                        'class' => 'elementor-clickable',
                    ]);
                }
                $this->parent->add_render_attribute('grid_item_inner', 'class', 'ae-grid-item-inner');

                if($this->get_instance_value('enable_image_ratio') == 'yes') {
                    $this->parent->add_render_attribute('grid_item_inner', 'class', 'ae_image_ratio_yes');
                }
                ?>

                <div <?php echo $this->parent->get_render_attribute_string('grid-wrapper'); ?>>
                    <div class="ae-grid">
                        <?php
                        if(!empty($images)) {
                            foreach ($images as $image) {
                                $image_caption = wp_get_attachment_caption($image['ID']); ?>
                                <figure class="ae-grid-item">
                                    <div <?php echo $this->parent->get_render_attribute_string('grid_item_inner'); ?>>
                                        <a href="<?php echo wp_get_attachment_url($image['ID'], 'full'); ?>" <?php echo $this->parent->get_render_attribute_string('link'); ?>>
                                            <?php if ($this->get_instance_value('enable_image_ratio') == 'yes') { ?>
                                                <div class="ae-pods-gallery-image">
                                            <?php } ?>
                                            <?php echo wp_get_attachment_image($image['ID'], $image_size, ['alt' => '$image_caption']); ?>
                                            <?php if ($this->get_instance_value('enable_image_ratio') == 'yes') { ?>
                                                </div>
                                            <?php } ?>
                                            <div class="ae-grid-overlay <?php echo $animation ?>">
                                                <div class="ae-grid-overlay-inner">
                                                    <div class="ae-icon-wrapper">
                                                        <?php if (!empty($icon)) { ?>
                                                            <div class="ae-overlay-icon">
                                                                <?php Icons_Manager::render_icon( $this->get_instance_value('selected_icon'), [ 'aria-hidden' => 'true' ] ); ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <?php if ($image_caption != '' && $caption == 'yes') { ?>
                                                        <div class="ae-overlay-caption"><?php echo $image_caption; //$image['caption']; ?></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </a>

                                    </div>
                                </figure>
                            <?php }
                        }?>
                    </div>
                </div>
            <?php
            }
    }
}