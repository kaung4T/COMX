<?php
namespace Aepro;

use Elementor\Core\Common\Modules\Finder\Base_Category;


class Aep_Finder extends Base_Category {

	public function get_title() {
		return __( 'Anywhere Elementor Pro', 'ae-pro' );
	}

	public function get_category_items( array $options = [] ) {

		$posts = get_posts([
			'post_type' => 'ae_global_templates',
			'post_status' => [ 'publish', 'draft' ],
			'numberposts' => -1
			// 'order'    => 'ASC'
		]);


		$items= [];
		if ( $posts ) {

			$helper = new Helper();
			$render_modes = $helper->get_ae_render_mode_hook();

			foreach ( $posts as $post ) :
				$mode = get_post_meta($post->ID, 'ae_render_mode', true);
				if($mode == '') {
					continue;
				}
				if(!class_exists('acf_pro') && $mode == 'acf_repeater_layout'){
					continue;
				}
				$draft_post = '';
				if($post->post_status == 'draft'){
					$draft_post = ' &#8210; Draft';
				}
				$items[] = [
					'title'       => __( $post->post_title . $draft_post, 'ae-pro' ),
					'description' => __( 'AE Template / ' . $render_modes[ $mode ], 'ae-pro' ),
					'url'         => admin_url( 'post.php?post=' . $post->ID . '&action=elementor' ),
					'icon'        => 'wordpress',
					'keywords'    => [ 'ae template', 'template' ],
					'actions'     => [
						[
							'name' => 'view',
							'url'  => get_permalink( $post->ID ),
							'icon' => 'eye',
						],
						[
							'name' => 'edit',
							'url'  => get_edit_post_link( $post->ID, 'context' ),
							'icon' => 'edit',
						]
					],
				];
			endforeach;
		}
		return $items;
	}
}