<?php
namespace Aepro;

class Ae_Theme extends Ae_Theme_Base{

    function manage_actions(){
        parent::manage_actions();

        do_action('aep_theme_manage_actions');

        return true;
    }

    function theme_hooks($hook_positions){

        return $hook_positions;
    }

    function set_fullwidth(){

        do_action('aep_theme_fullwidth');
    }
}