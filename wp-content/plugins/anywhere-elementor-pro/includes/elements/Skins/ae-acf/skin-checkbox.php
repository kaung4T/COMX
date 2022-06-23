<?php

namespace Aepro\Ae_ACF\Skins;

use Aepro\Aepro_ACF;
use Aepro\Classes\AcfMaster;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Color;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;


class Skin_Checkbox extends Skin_Select {

	public function get_id() {
		return 'checkbox';
	}

	public function get_title() {
		return __( 'Checkbox', 'ae-pro' );
	}


	public function register_controls( Widget_Base $widget){

		$this->parent = $widget;

		parent::register_select_controls();

	}

}
