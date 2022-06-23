<?php
namespace Aepro;

use WPML_Elementor_Module_With_Items;

class WPML_AE_Woo_Tabs extends WPML_Elementor_Module_With_Items{

    public function get_items_field() {
        return 'tabs';
    }

    public function get_fields() {
        return array( 'tab_title' );
    }

    protected function get_title( $field ) {

        switch( $field ) {

            case 'tab_title':
                return esc_html__( 'Tab: Title', 'ae-pro' );

            default:
                return '';
        }
    }

    protected function get_editor_type( $field ) {

        switch( $field ) {
            case 'tab_title':
                return 'LINE';

            default:
                return '';
        }
    }
}