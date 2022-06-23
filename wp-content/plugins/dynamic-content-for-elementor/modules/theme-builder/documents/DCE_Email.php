<?php

namespace ElementorPro\Modules\ThemeBuilder\Documents;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class DCE_Email extends Theme_Document {

    public static function get_properties() {
        $properties = parent::get_properties();

        $properties['condition_type'] = 'email'; // 'general';
        $properties['location'] = 'single';

        return $properties;
    }

    public static function get_name_static() {
        return 'dce_email';
    }

    public function get_name() {
        return self::get_name_static();
    }

    public static function get_title() {
        return __('Email', 'ele-custom-skin');
    }

    public static function get_preview_as_default() {
        return '';
    }

    public static function get_preview_as_options() {
        return array_merge(
                [
                    '',
                ],
                Single::get_preview_as_options()
        );
    }

    public static function dce_add_more_types($settings) {
        $post_id = get_the_ID();
        $document = null;
        try {
            $document = \ElementorPro\Plugin::elementor()->documents->get($post_id);
        } catch (\Exception $e) {
            
        }
        if (!empty($document) && !$document instanceof Theme_Document) {
            $document = null;
        }
        if (!$document) {
            return $settings;
        }
        $new_types = [self::get_name_static() => self::get_properties()];
        $add_settings = ['theme_builder' => ['types' => $new_types]];
        if (!array_key_exists(self::get_name_static(), $settings['theme_builder']['types'])) {
            $settings = array_merge_recursive($settings, $add_settings);
        }
        return $settings;
    }

}
