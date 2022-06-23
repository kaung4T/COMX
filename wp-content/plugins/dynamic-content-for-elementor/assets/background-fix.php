<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

// dynamic background fix
function dce_set_bg_element(\Elementor\Element_Base $element) {
    global $dce_render_loop;
    if (!$dce_render_loop)
        return; // only act inside loop
    list($post_id, $loop_id) = explode(",", $dce_render_loop);
    $element_id = $element->get_ID();
    $dynamic_settings = $element->get_settings('__dynamic__');
    $all_controls = $element->get_controls();
    $all_controls = isset($all_controls) ? $all_controls : [];
    $dynamic_settings = isset($dynamic_settings) ? $dynamic_settings : [];
    $controls = array_intersect_key($all_controls, $dynamic_settings);
    $settings = $element->parse_dynamic_settings($dynamic_settings, $controls);
    /* start custom css */
    $css_rules['section']['normal'] = "#post-{$post_id} .elementor-{$loop_id} .elementor-element.elementor-element-{$element_id}:not(.elementor-motion-effects-element-type-background), #post-{$post_id} .elementor-{$loop_id} .elementor-element.elementor-element-{$element_id} > .elementor-motion-effects-container > .elementor-motion-effects-layer";
    $css_rules['section']['hover'] = "#post-{$post_id} .elementor-{$loop_id} .elementor-element.elementor-element-{$element_id}:hover";
    $css_rules['section']['overlay'] = "#post-{$post_id} .elementor-{$loop_id} .elementor-element.elementor-element-{$element_id} > .elementor-background-overlay";
    $css_rules['section']['overlay_hover'] = "#post-{$post_id} .elementor-{$loop_id} .elementor-element.elementor-element-{$element_id}:hover > .elementor-background-overlay";
    $css_rules['column']['normal'] = "#post-{$post_id} .elementor-{$loop_id} .elementor-element.elementor-element-{$element_id}:not(.elementor-motion-effects-element-type-background) > .elementor-element-populated, #post-{$post_id} .elementor-{$loop_id} .elementor-element.elementor-element-{$element_id} > .elementor-column-wrap > .elementor-motion-effects-container > .elementor-motion-effects-layer";
    $css_rules['column']['hover'] = "#post-{$post_id} .elementor-{$loop_id} .elementor-element.elementor-element-{$element_id}:hover > .elementor-element-populated";
    $css_rules['column']['overlay'] = "#post-{$post_id} .elementor-{$loop_id} .elementor-element.elementor-element-{$element_id} > .elementor-element-populated > .elementor-background-overlay";
    $css_rules['column']['overlay_hover'] = "#post-{$post_id} .elementor-{$loop_id} .elementor-element.elementor-element-{$element_id}:hover > .elementor-element-populated > .elementor-background-overlay";
    $bg['normal'] = isset($settings["background_image"]["url"]) ? $settings["background_image"]["url"] : (isset($settings["_background_image"]["url"]) ? $settings["_background_image"]["url"] : "");
    $bg['hover'] = isset($settings["background_hover_image"]["url"]) ? $settings["background_hover_image"]["url"] : (isset($settings["_background_hover_image"]["url"]) ? $settings["_background_hover_image"]["url"] : "");
    $bg['overlay'] = isset($settings["background_overlay_image"]["url"]) ? $settings["background_overlay_image"]["url"] : (isset($settings["_background_overlay_image"]["url"]) ? $settings["_background_overlay_image"]["url"] : "");
    $bg['overlay_hover'] = isset($settings["background_overlay_hover_image"]["url"]) ? $settings["background_overlay_hover_image"]["url"] : (isset($settings["_background_overlay_hover_image"]["url"]) ? $settings["_background_overlay_hover_image"]["url"] : "");
    $key_element = $element->get_name() == 'section' ? "section" : "column";
    $dce_css = "";
    foreach ($css_rules[$key_element] as $key => $value) {
        $dce_css .= $bg[$key] ? $css_rules[$key_element][$key] . " {background-image:url(" . $bg[$key] . ");} " : "";
    }
    echo $dce_css ? "<style>" . $dce_css . "</style>" : "";
    /* end custom css */
}

add_action('elementor/frontend/section/before_render', 'dce_set_bg_element');
add_action('elementor/frontend/column/before_render', 'dce_set_bg_element');

//keep track of index

add_action('elementor/frontend/widget/before_render', function ( \Elementor\Element_Base $element ) {
    global $dce_index;
    if ('posts' === $element->get_name() || 'archive-posts' === $element->get_name()) {
        $dce_index = 0;
    }
});

/*
function dce_get_template() {
    global $dce_render_loop, $wp_query, $dce_index;
    $dce_index++;
    $old_query = $wp_query;
    $new_query = new \WP_Query(array('p' => get_the_ID()));
    $wp_query = $new_query;
    $settings = $this->parent->get_settings();
    $this->pid = get_the_ID(); //set the current id in private var usefull to passid
    $default_template = $this->get_instance_value('skin_template');
    $template = $default_template;
    // move to pro
    $template = apply_filters('ECS_action_template', $template, $this, $dce_index);
    $newid = apply_filters('wpml_object_id', $template, 'elementor_library', TRUE);
    $template =  $newid ? $newid : $template;
    $dce_render_loop = get_the_ID() . "," . $template;
    //echo $dce_render_loop;
    // end pro
    if (!$template)
        return;
    $return = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($template);
    $dce_render_loop = false;
    $wp_query = $old_query;
    return $return;
}
/*