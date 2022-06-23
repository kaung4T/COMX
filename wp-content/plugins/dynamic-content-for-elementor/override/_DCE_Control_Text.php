<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Elementor;

use \Elementor\Control_Text;
use \Elementor\Modules\DynamicTags\Module as TagsModule;

/**
 * Description of DCE_Control_Text
 *
 * @author fra
 */
class DCE_Control_Text extends Control_Text {
    
    /**
    * Base settings.
    *
    * Holds all the base settings of the control.
    *
    * @access private
    *
    * @var array
    */
    private $_base_settings = [
        'label' => '',
        'description' => '',
        'show_label' => true,
        'label_block' => false,
        'separator' => 'default',
        'dynamic' => [
            'ooo' => 'true',
            'active' => 'ooo'
        ],
    ];
    
    /**
    * Get init settings.
    *
    * Used to define the default/initial settings of the object. Inheriting classes may implement this method to define
    * their own default/initial settings.
    *
    * @since 2.3.0
    * @access protected
    *
    * @return array
    */
    protected function get_init_settings() {
        return [
            'dynamic' => [
                'ooo' => 'true',
                'active' => 'ooo'
            ],
        ];
    }
    
    /**
    * Get text control default settings.
    *
    * Retrieve the default settings of the text control. Used to return the
    * default settings while initializing the text control.
    *
    * @since 1.0.0
    * @access protected
    *
    * @return array Control default settings.
    */
    public function get_default_settings() {
           return [
                   'input_type' => 'text',
                   'placeholder' => '',
                   'title' => '',
                   'dynamic' => [
                           'categories' => [ TagsModule::TEXT_CATEGORY ],
                           'ooo' => 'true',
                           'active' => 'ooo'
                   ],
           ];
    }
}
