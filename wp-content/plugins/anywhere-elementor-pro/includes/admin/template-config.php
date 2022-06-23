<?php

namespace Aepro\Admin;

use Aepro\Aepro;
use Aepro\Helper;
use Aepro\Admin\AdminHelper;

class TemplateConfig{

	private $fields = [];

	public function __construct() {

		add_action( 'add_meta_boxes', [ $this, 'config_box' ], 10 );

		add_action( 'save_post', [ $this, 'save_config'] );

		$this->field_keys();
	}

	function config_box(){

		add_meta_box(
			'aep_config_box',
			__( 'AnyWhere Elementor Settings', 'ae-pro' ),
			[ $this, 'render_config_box' ],
			'ae_global_templates',
            'normal',
            'high'
		);
	}

	function field_keys(){

		$this->fields = [

			'ae_render_mode' => [
			        'multi' => false
            ],
			'ae_usage' => [
			        'multi' => false
            ],
			'ae_custom_usage_area' => [
			        'multi' => false
            ],
			'ae_rule_post_type' => [
			        'multi' => false
            ],
			'ae_preview_post_ID' => [
			        'multi' => false
            ],
			'ae_rule_taxonomy' => [
			        'multi' => false
            ],
			'ae_preview_term' => [
			        'multi' => false
            ],
			'ae_preview_author' => [
			        'multi' => false
            ],
			'ae_rule_post_type_archive'  => [
				'multi' => false
			],
			'ae_apply_global'  => [
				'multi' => false
			],
			'ae_full_override'  => [
				'multi' => false
			],
			'ae_elementor_template'  => [
				'multi' => false
			],
			'ae_acf_repeater_name'  => [
				'multi' => false
			],

            'ae_hook_apply_on'  => [
	            'multi' => true
            ],
            'ae_hook_post_types'  => [
	            'multi' => true
            ],
            'ae_hook_taxonomies'  => [
	            'multi' => true
            ],
            'ae_hook_posts_selected'  => [
	            'multi' => false
            ],
            'ae_hook_posts_excluded'  => [
	            'multi' => false
            ],
            'ae_hook_terms_selected'  => [
	            'multi' => false
            ],
            'ae_hook_terms_excluded'  => [
	            'multi' => false
            ],

		];

	}

	function render_config_box( $post ){

		$helper = new Helper();

		$admin_helper = AdminHelper::instance();


		$hook_positions = Aepro::instance()->get_hook_positions();

		$render_modes = $helper->get_ae_render_mode_hook();
		$usage_areas  = apply_filters('ae_pro_filter_hook_positions',$hook_positions);

		$post_types = $helper->get_rule_post_types();
		$taxonomies = $helper->get_rules_taxonomies();

		$preview_post = $helper->get_saved_preview_post();
		$preview_term = $helper->get_saved_preview_term();

		$authors = $helper->get_author_list();

		$post_type_archives = $helper->get_post_types_with_archive();

		$saved_meta = $this->get_saved_meta( $post->ID );




		// Rules Section Data
        $apply_on = [
	        'single' => 'Single Post',
	        'archive' => 'Archive',
	        'search'  => 'Search',
	        '404'     => '404',
	        'home'    => 'Home'
        ];


		?>

        <div class="ae-config-wrapper">
            <ul class="ae-config-nav">
                <li aria-selected="true">
                    <a href="#ae-config-general">General</a>
                </li>
                <li class="ae-rules" aria-selected="false">
                    <a href="#ae-config-rules">Rules</a>
                </li>
            </ul>
            <div class="ae-config-content-wrapper">
                <div id="ae-config-general" class="ae-config-content" aria-hidden="true">

                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_render_mode">Render Mode</label>
                        </div>
                        <div class="ae-control">
                            <select name="ae_render_mode" id="ae_render_mode">
		                        <?php $admin_helper->render_dropdown($render_modes, $saved_meta['ae_render_mode']); ?>
                            </select>
                        </div>
                    </div>

                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_usage">Usage Area</label>
                        </div>
                        <div class="ae-control">
                            <select name="ae_usage" id="ae_usage">
		                        <?php $admin_helper->render_dropdown($usage_areas, $saved_meta['ae_usage']); ?>
                            </select>
                        </div>
                    </div>

                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_custom_usage_area"><?php echo esc_html__('Custom Usage Area','ae-pro'); ?></label>
                            <span class="aep-desc"></span>
                        </div>
                        <div class="ae-control">
                            <input type="text" name="ae_custom_usage_area" id="ae_custom_usage_area" value="<?php echo $saved_meta['ae_custom_usage_area']; ?>" />
                        </div>
                    </div>

                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_rule_post_type">Post Type (applicable to single post layout)</label>
                        </div>
                        <div class="ae-control">
                            <select name="ae_rule_post_type" id="ae_rule_post_type">
		                        <?php $admin_helper->render_dropdown($post_types, $saved_meta['ae_rule_post_type']); ?>
                            </select>
                        </div>
                    </div>

                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_preview_post_ID"><?php echo esc_html__('Preview Post','ae-pro'); ?></label>
                        </div>
                        <div class="ae-control">
                            <select name="ae_preview_post_ID" id="ae_preview_post_ID">
		                        <?php $admin_helper->render_dropdown($preview_post, $saved_meta['ae_preview_post_ID']); ?>
                            </select>
                        </div>
                    </div>

                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_rule_taxonomy"><?php echo esc_html__('Taxonomy (applicable to archive layouts)','ae-pro'); ?></label>
                        </div>
                        <div class="ae-control">
                            <select name="ae_rule_taxonomy" id="ae_rule_taxonomy">
		                        <?php $admin_helper->render_dropdown( $taxonomies, $saved_meta['ae_rule_taxonomy'] ); ?>
                            </select>
                        </div>
                    </div>

                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_preview_term"><?php echo esc_html__('Preview Term','ae-pro'); ?></label>
                        </div>
                        <div class="ae-control">
                            <select name="ae_preview_term" id="ae_preview_term">
		                        <?php $admin_helper->render_dropdown( $preview_term, $saved_meta['ae_preview_term'] ); ?>
                            </select>
                        </div>
                    </div>

                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_preview_author"><?php echo esc_html__('Preview Author','ae-pro'); ?></label>
                        </div>
                        <div class="ae-control">
                            <select name="ae_preview_author" id="ae_preview_author">
		                        <?php $admin_helper->render_dropdown( $authors, $saved_meta['ae_preview_author'] ); ?>
                            </select>
                        </div>
                    </div>

                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_rule_post_type_archive"><?php echo esc_html__('Post Types Archives (post type with has_archive true)','ae-pro'); ?></label>
                        </div>
                        <div class="ae-control">
                            <select name="ae_rule_post_type_archive" id="ae_rule_post_type_archive">
		                        <?php $admin_helper->render_dropdown( $post_type_archives, $saved_meta['ae_rule_post_type_archive'] ); ?>
                            </select>
                        </div>
                    </div>

                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_apply_global"><?php echo esc_html__('Auto Apply','ae-pro'); ?></label>
                        </div>
                        <div class="ae-control">
                            <input type="hidden" name="ae_apply_global" value="" />
                            <input type="checkbox" value="true" <?php echo ($saved_meta['ae_apply_global'] == 'true')?'checked':''; ?> id="ae_apply_global" name="ae_apply_global" />
                        </div>
                    </div>

                    <!-- Removed option.. only hidden field for backward compatibility. -->
                    <input type="hidden" value="<?php echo (!isset($saved_meta['ae_full_override']) || $saved_meta['ae_full_override'] != 'true')?'':'true'; ?>" id="ae_full_override" name="ae_full_override" />

                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_elementor_template"><?php echo esc_html__('Template','ae-pro'); ?></label>
                        </div>
                        <div class="ae-control">
                            <select name="ae_elementor_template" id="ae_elementor_template">
		                        <?php $admin_helper->render_dropdown( [
			                        ''      => 'Theme Default',
			                        'ec'    => 'Elementor Canvas',
			                        'ehf'   => 'Elementor Full Width'

		                        ], $saved_meta['ae_elementor_template'] );
		                        ?>
                            </select>
                        </div>
                    </div>

	                <?php
                    if(class_exists('acf')){

	                    if(isset($_GET['post'])){
		                    $preview_post_id = $prev_post_id = get_post_meta($_GET['post'],'ae_preview_post_ID',true);
		                    $repeater_fields = $admin_helper->get_ae_acf_repeater_fields($preview_post_id);
	                    }else{
	                        $repeater_fields =  [];
                        }


		                ?>

                        <div class="f-row">
                            <div class="ae-desc">
                                <label for="ae_acf_repeater_name"><?php echo esc_html__('Repeater Field (ACF)','ae-pro'); ?></label>
                            </div>

                            <div class="ae-control">
                                <select name="ae_acf_repeater_name" id="ae_acf_repeater_name">
                                    <?php $admin_helper->render_dropdown( $repeater_fields, $saved_meta['ae_acf_repeater_name'] ); ?>
                                </select>
                            </div>
                        </div>

		                <?php
	                }
	                ?>

                </div>

                <div id="ae-config-rules" class="ae-config-content" aria-hidden="true">

                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_hook_apply_on">Apply To</label>
                            <span class="aep-desc">Select the pages on which you want your template to be displayed</span>
                        </div>
                        <div class="ae-control">

                            <?php $admin_helper->render_checkbox( 'ae_hook_apply_on[]', $apply_on, $saved_meta['ae_hook_apply_on']); ?>

                        </div>
                    </div>

                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_hook_post_types">Post Type</label>
                            <span class="aep-desc">Select specific post type on which template has to be display</span>
                        </div>
                        <div class="ae-control">

                            <?php $admin_helper->render_checkbox( 'ae_hook_post_types[]', $post_types, $saved_meta['ae_hook_post_types']); ?>

                        </div>
                    </div>

                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_hook_posts_selected"><?php echo esc_html__('Selected Posts','ae-pro'); ?></label>
                            <span class="aep-desc"><?php echo esc_html__('Comma separated specific post ids on which this template has to be appeared','ae-pro'); ?></span>
                        </div>
                        <div class="ae-control">
                            <input type="text" name="ae_hook_posts_selected" id="ae_hook_posts_selected" value="<?php echo $saved_meta['ae_hook_posts_selected']; ?>" />
                        </div>
                    </div>

                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_hook_posts_excluded"><?php echo esc_html__('Excluding Posts','ae-pro'); ?></label>
                            <span class="aep-desc"><?php echo esc_html__('Comma separated specific post ids on which this template should not to appeared','ae-pro'); ?></span>
                        </div>
                        <div class="ae-control">
                            <input type="text" name="ae_hook_posts_excluded" id="ae_hook_posts_excluded" value="<?php echo $saved_meta['ae_hook_posts_excluded']; ?>" />
                        </div>
                    </div>


                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_hook_taxonomies"><?php echo esc_html__('Taxonomies','ae-pro'); ?></label>
                            <span class="aep-desc"><?php echo esc_html__('Select specific Taxonomies on which template will appear','ae-pro'); ?></span>
                        </div>
                        <div class="ae-control">

                            <?php $admin_helper->render_checkbox( 'ae_hook_taxonomies[]', $taxonomies, $saved_meta['ae_hook_taxonomies']); ?>

                        </div>
                    </div>

                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_hook_terms_selected"><?php echo esc_html__('Selected Terms','ae-pro'); ?></label>
                            <span class="aep-desc"><?php echo esc_html__('Comma separated specific term ids on which this template will appear','ae-pro'); ?></span>
                        </div>
                        <div class="ae-control">
                            <input type="text" name="ae_hook_terms_selected" id="ae_hook_terms_selected" value="<?php echo $saved_meta['ae_hook_terms_selected']; ?>" />
                        </div>
                    </div>

                    <div class="f-row">
                        <div class="ae-desc">
                            <label for="ae_hook_terms_excluded"><?php echo esc_html__('Excluding Terms','ae-pro'); ?></label>
                            <span class="aep-desc"><?php echo esc_html__('Comma separated specific term ids on which this template will not appeared','ae-pro'); ?></span>
                        </div>
                        <div class="ae-control">
                            <input type="text" name="ae_hook_terms_excluded" id="ae_hook_terms_excluded" value="<?php echo $saved_meta['ae_hook_terms_excluded']; ?>" />
                        </div>
                    </div>

                </div>
            </div>
        </div>



		<?php
	}

	function save_config( $post_id ){

		$post_type = get_post_type($post_id);

		// If this isn't a 'book' post, don't update it.
		if ( "ae_global_templates" != $post_type ) return;

		if(!isset($_POST['ae_render_mode'])) return;

		foreach($this->fields as $key => $field){

			if(isset($_POST[ $key ])){
                if($field['multi']){
                    update_post_meta( $post_id, $key, array_map('sanitize_text_field', $_POST[$key] ) );
                }else{
	                update_post_meta( $post_id, $key, sanitize_text_field($_POST[$key]));
                }
			}else{
				if($field['multi']){
					update_post_meta( $post_id, $key, [] );
				}else{
					update_post_meta( $post_id, $key, '');
				}
            }
		}
	}

	function get_saved_meta( $post_id ){

		$saved_meta = [];

		$post_metas = get_post_meta( $post_id );

		foreach( $this->fields as $key => $field){

			if(in_array( $key, array_keys($post_metas))){

				if($field['multi']){
					$saved_meta[ $key ] = unserialize($post_metas[ $key ][0]);
				}else{
					$saved_meta[ $key ] = $post_metas[ $key ][0];
                }

			}else{
				if($field['multi']){
					$saved_meta[ $key ] = array();
				}else{
					$saved_meta[ $key ] = '';
				}
			}
		}

        return $saved_meta;
	}

}

new TemplateConfig();