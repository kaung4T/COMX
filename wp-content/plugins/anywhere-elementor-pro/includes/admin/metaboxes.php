<?php
namespace Aepro;

use Aepro\Helper;

//add_action( 'aep_butterbean_register', 'Aepro\wts_ae_butterbean', 10, 2 );

//add_action( 'butterbean_register', 'Aepro\wts_ae_product_template_list',10,2);

add_action( 'add_meta_boxes', 'Aepro\wts_ae_product_template_list_meta_box' );

function wts_ae_product_template_list_meta_box($post){
    $helper = new Helper();
    $post_types = $helper->get_rule_post_types('names');
    add_meta_box(
        'ae_post_template_meta_box',
        __( 'AE Post Template', 'ae-pro' ),
        'Aepro\wts_ae_post_template_list',
        $post_types,
        'side',
        'high'
    );
}

function wts_ae_post_template_list($post){
    $ae_post_template = get_post_meta($post->ID,'ae_post_template',true);
    $helper = new Helper();
    $post_templates = $helper->get_ae_post_templates();

    ?>
    <h4><?php echo __('Select Layout','ae-pro'); ?></h4>
    <select name="ae_post_template">
        <?php foreach($post_templates as $key => $post_template){
            ?>
            <option <?php echo ($key == $ae_post_template)?'selected':''; ?> value="<?php echo $key; ?>"><?php echo $post_template; ?></option>
            <?php
        }
        ?>
    </select>
    <?php
    wp_nonce_field( 'ae_post_template_metabox_nonce', 'ae_post_template_nonce' );
}

function save_ae_post_template($post_id){

    if( !isset( $_POST['ae_post_template_nonce'] ) || !wp_verify_nonce( $_POST['ae_post_template_nonce'],'ae_post_template_metabox_nonce') ) {
        return;
    }

    if ( isset($_POST['ae_post_template']) ) {
        update_post_meta($post_id, 'ae_post_template', sanitize_text_field($_POST['ae_post_template']));
    }
}
add_action('save_post','Aepro\save_ae_post_template');



function wts_ae_butterbean( $butterbean, $post_type ) {


    $hook_positions = Aepro::instance()->get_hook_positions();
    $helper = new Helper();


    // Bail if not our post type.
    if ( 'ae_global_templates' !== $post_type )
        return;


    $butterbean->register_manager(
        'ae_pro',
        array(
            'label'     => esc_html__( 'AnyWhere Elementor Settings', 'ae-pro' ),
            'post_type' => 'ae_global_templates',
            'context'   => 'normal',
            'priority'  => 'high'
        )
    );

    $manager = $butterbean->get_manager( 'ae_pro' );

    $manager->register_section(
        'general',
        array(
            'label' => esc_html__( 'General', 'ae-pro' ),
            'icon'  => 'dashicons-admin-generic'
        )
    );

    $manager->register_control(
        'ae_render_mode',
        array(
            'type'      => 'select',
            'section'   => 'general',
            'label'     => esc_html__('Render Mode','ae-pro'),
            'attr'    => array( 'class' => 'widefat' ),
            'choices'   => $helper->get_ae_render_mode_hook()
        )
    );

    $manager->register_setting(
        'ae_render_mode',
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_control(
      'ae_render_mode',
       array(
           'type'   => 'text'
       )
    );



    $manager->register_control(
         'ae_preview_woo_ID',
         array(
         'type'      => 'text',
         'section'   => 'general',
         'label'     => esc_html__('Preview Woocommerce Product ID','ae-pro'),
         )
     );

     $manager->register_setting(
         'ae_preview_woo_ID', // Same as control name.
         array(
         'sanitize_callback' => 'wp_filter_nohtml_kses'
         )
     );

    $manager->register_control(
        'ae_usage', // Same as setting name.
        array(
            'type'    => 'select-group',
            'section' => 'general',
            'label'   => esc_html__( 'Usage Area', 'ae-pro' ),
            'description' => esc_html__('Autodetected hooks from theme.','ae-pro'),
            'attr'    => array( 'class' => 'widefat' ),
            'choices' => apply_filters('ae_pro_filter_hook_positions',$hook_positions)
        )
    );
    $manager->register_setting(
        'ae_usage', // Same as control name.
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_control(
        'ae_custom_usage_area',
        array(
            'type'      => 'text',
            'section'   => 'general',
            'label'     => esc_html__('Custom Usage Area','ae-pro'),
            'description' => esc_html__('Add any hook position to apply this template at that location','ae-pro'),
            'attr'    => array( 'class' => 'widefat' ),
        )
    );
    $manager->register_setting(
        'ae_custom_usage_area', // Same as control name.
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_control(
        'ae_rule_post_type',
        array(
            'type'      => 'select-group',
            'section'   => 'general',
            'label'     => esc_html__('Post Type (applicable to single post layout)','ae-pro'),
            'choices'   => $helper->get_rule_post_types(),
            'attr'    => array( 'class' => 'ae_rules_control ae_post ae-post-type' ),
        )
    );

    $manager->register_setting(
        'ae_rule_post_type', // Same as control name.
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_control(
        'ae_preview_post_ID',
        array(
            'type'      => 'select-group',
            'section'   => 'general',
            'label'     => esc_html__('Preview Post','ae-pro'),
            'choices'   => $helper->get_saved_preview_post(),
            'attr'    => array( 'class' => 'ae_prev_post' ),
            'description' => esc_html('Type few starting letters of your post title below.', 'ae-pro')
        )
    );
    $manager->register_setting(
        'ae_preview_post_ID', // Same as control name.
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_control(
        'ae_rule_taxonomy',
        array(
            'type'      => 'select-group',
            'section'   => 'general',
            'label'     => esc_html__('Taxonomy (applicable to archive layouts)','ae-pro'),
            'choices'   => $helper->get_rules_taxonomies(),
            'attr'    => array( 'class' => 'ae_rules_control ae_archive ae-taxonomy' ),
        )
    );

    $manager->register_setting(
        'ae_rule_taxonomy', // Same as control name.
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_control(
        'ae_preview_term',
        array(
            'type'      => 'select-group',
            'section'   => 'general',
            'label'     => esc_html__('Preview Term','ae-pro'),
            'choices'   => $helper->get_saved_preview_term(),
            'attr'    => array( 'class' => 'ae_prev_term' ),
            'description' => esc_html('Type few starting letters of your category/taxonomy item below.', 'ae-pro')
        )
    );

    $manager->register_setting(
        'ae_preview_term', // Same as control name.
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_control(
        'ae_preview_author',
        array(
            'type'      => 'select-group',
            'section'   => 'general',
            'label'     => esc_html__('Preview Author','ae-pro'),
            'choices'   => $helper->get_author_list(),
            'attr'    => array( 'class' => 'ae_prev_author' ),
            'description' => esc_html('Type few starting letters of author.', 'ae-pro')
        )
    );

    $manager->register_setting(
        'ae_preview_author', // Same as control name.
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_control(
        'ae_rule_post_type_archive',
        array(
            'type'      => 'select-group',
            'section'   => 'general',
            'label'     => esc_html__('Post Types Archives (post type with has_archive true)','ae-pro'),
            'choices'   => $helper->get_post_types_with_archive(),
            'attr'    => array( 'class' => 'ae_rules_control ae_pt_archive' ),
        )
    );

    $manager->register_setting(
        'ae_rule_post_type_archive', // Same as control name.
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_control(
        'ae_apply_global',
        array(
            'type'      => 'checkbox',
            'section'   => 'general',
            'label'     => esc_html__('Auto Apply','ae-pro'),
            'choices'   => array(
                '1' => 'Yes'
            ),
            'attr'    => array( 'class' => 'ae_rules_control ae_post ae_archive' ),
        )
    );

    $manager->register_setting(
        'ae_apply_global', // Same as control name.
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_control(
        'ae_full_override',
        array(
            'type'      => 'checkbox',
            'section'   => 'general',
            'label'     => esc_html__('Override Theme Layout','ae-pro'),
            'choices'   => array(
                '1' => 'Yes'
            ),
            'attr'    => array( 'class' => 'ae_rules_control ae_post ae_archive' ),
        )
    );

    $manager->register_setting(
        'ae_full_override', // Same as control name.
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    /**
    $manager->register_control(
        'ae_enable_canvas',
        array(
            'type'      => 'checkbox',
            'section'   => 'general',
            'label'     => esc_html__('Enable Canvas','ae-pro'),
            'choices'   => array(
                '1' => 'Yes'
            ),
            'description' => esc_html__('Use Elementor Canvas Template','ae-pro'),
            'attr'    => array( 'class' => 'ae_rules_control ae_post ae_archive' ),
        )
    );

    $manager->register_setting(
        'ae_enable_canvas', // Same as control name.
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );
    **/

    $manager->register_control(
        'ae_elementor_template',
        [
            'type'  => 'select-group',
            'section'   => 'general',
            'label'     => esc_html__('Template','ae-pro'),
            'choices'   => [
                    ''      => 'Theme Default',
                    'ec'    => 'Elementor Canvas',
                    'ehf'   => 'Elementor Full Width'

            ],
            'attr'    => array( 'class' => 'ae_rules_control ae_post ae_archive' ),

        ]
    );

    $manager->register_setting(
        'ae_elementor_template',
        array(
	        'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    if(class_exists('acf')){
        $manager->register_control(
            'ae_acf_repeater_name',
            array(
                'type'      => 'select',
                'section'   => 'general',
                'choices'   => $helper->get_ae_acf_repeater_fields(),
                'label'     => esc_html__('Repeater Field (ACF)','ae-pro'),
                'attr'    => array( 'class' => 'ae_acf_repeater_name' ),
            )
        );

        $manager->register_setting(
            'ae_acf_repeater_name', // Same as control name.
            array(
                'sanitize_callback' => 'wp_filter_nohtml_kses'
            )
        );
    }


    $manager->register_section(
        'rules',
        array(
            'label' => esc_html__( 'Rules', 'ae-pro' ),
            'icon'  => 'dashicons-admin-generic'
        )
    );

    wts_ae_normal_mode_rules($manager);
}

function wts_ae_normal_mode_rules($manager){
    $helper = new Helper();
    $manager->register_control(
        'ae_hook_apply_on',
        array(
            'type'        => 'checkboxes',
            'section'     => 'rules',
            'label'       => 'Apply To',
            'description' => 'Select the pages on which you want your template to be displayed',
            'choices'     => array(
                'single' => 'Single Post',
                'archive' => 'Archive',
                'search'  => 'Search',
                '404'     => '404',
                'home'    => 'Home'
            )
        )
    );
    $manager->register_setting(
        'ae_hook_apply_on', // Same as control name.
        array( 'type' => 'array', 'sanitize_callback' => 'sanitize_key' )
    );

    $post_types = $helper->get_rule_post_types();
    $manager->register_control(
        'ae_hook_post_types',
        array(
            'type'      => 'checkboxes',
            'section'   => 'rules',
            'label'     => esc_html__('Post Type','ae-pro'),
            'description' => esc_html__('Select specific post type on which template has to be display','ae-pro'),
            'choices'   => $post_types,
            'attr'    => array( 'class' => 'ae_rules_control ae_hook' ),
        )
    );
    $manager->register_setting(
        'ae_hook_post_types', // Same as control name.
        array( 'type' => 'array', 'sanitize_callback' => 'sanitize_key' )
    );

    $manager->register_control(
        'ae_hook_posts_selected',
        array(
            'type'      => 'text',
            'section'   => 'rules',
            'label'     => esc_html__('Selected Posts','ae-pro'),
            'description' => esc_html__('Comma separated specific post ids on which this template has to be appeared','ae-pro'),
            'attr'    => array( 'class' => 'widefat ae_rules_control ae_hook' ),
        )
    );
    $manager->register_setting(
        'ae_hook_posts_selected', // Same as control name.
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_control(
        'ae_hook_posts_excluded',
        array(
            'type'      => 'text',
            'section'   => 'rules',
            'label'     => esc_html__('Excluding Posts','ae-pro'),
            'description' => esc_html__('Comma separated specific post ids on which this template should not to appeared','ae-pro'),
            'attr'    => array( 'class' => 'widefat ae_rules_control ae_hook' ),
        )
    );
    $manager->register_setting(
        'ae_hook_posts_excluded', // Same as control name.
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );


    $taxonomies = $helper->get_rules_taxonomies();
    $manager->register_control(
        'ae_hook_taxonomies',
        array(
            'type'      => 'checkboxes',
            'section'   => 'rules',
            'label'     => esc_html__('Taxonomies','ae-pro'),
            'description' => esc_html__('Select specific Taxonomies on which template will appear','ae-pro'),
            'choices'   => $taxonomies,
            'attr'    => array( 'class' => 'ae_rules_control ae_hook' ),
        )
    );
    $manager->register_setting(
        'ae_hook_taxonomies', // Same as control name.
        array( 'type' => 'array', 'sanitize_callback' => 'sanitize_key' )
    );

    $manager->register_control(
        'ae_hook_terms_selected',
        array(
            'type'      => 'text',
            'section'   => 'rules',
            'label'     => esc_html__('Selected Terms','ae-pro'),
            'description' => esc_html__('Comma separated specific term ids on which this template will appear','ae-pro'),
            'attr'    => array( 'class' => 'widefat ae_rules_control ae_hook' ),
        )
    );
    $manager->register_setting(
        'ae_hook_terms_selected', // Same as control name.
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

    $manager->register_control(
        'ae_hook_terms_excluded',
        array(
            'type'      => 'text',
            'section'   => 'rules',
            'label'     => esc_html__('Excluding Terms','ae-pro'),
            'description' => esc_html__('Comma separated specific term ids on which this template will not appeared','ae-pro'),
            'attr'    => array( 'class' => 'widefat ae_rules_control ae_hook' ),
        )
    );
    $manager->register_setting(
        'ae_hook_terms_excluded', // Same as control name.
        array(
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
    );

}






//  Meta Box for shortcode

function add_ae_meta_box(){
    add_meta_box('ae-shortcode-box','Anywhere Elementor Usage','Aepro\ae_pro_shortcode_box','ae_global_templates','side','high');
}
add_action("add_meta_boxes", "Aepro\add_ae_meta_box");


function ae_pro_shortcode_box($post){
    ?>
    <h4 style="margin-bottom:5px;">Shortcode</h4>
    <input type='text' class='widefat' value='[INSERT_ELEMENTOR id="<?php echo $post->ID; ?>"]' readonly="">

    <h4 style="margin-bottom:5px;">Php Code</h4>
    <input type='text' class='widefat' value="&lt;?php echo do_shortcode('[INSERT_ELEMENTOR id=&quot;<?php echo $post->ID; ?>&quot;]'); ?&gt;" readonly="">
    <?php
}



add_action('wp_loaded',function(){

    if(!is_admin()){
        return;
    }

    // Add Term Meta
    $args = array(
        'public'   => true
    );
    $taxonomies = get_taxonomies($args,'objects');

    global $ae_term_templates;
    global $ae_post_templates;
    $helper = new Helper();
    $ae_term_templates = $helper->get_taxonomy_templates();
    $ae_post_templates = $helper->get_ae_post_templates();

    foreach($taxonomies as $taxonomy){

        // Add Form Field
        add_action($taxonomy->name.'_add_form_fields', function ($taxonomy){
            global $ae_term_templates;
            global $ae_post_templates;

            ?>
            <div class="form-field term-group">
            <label for="ae_term_template"><?php _e('AE Pro Term Template', 'ae-pro'); ?></label>
            <select class="postform" id="equipment-group" name="ae_term_template">
                <option value="global"><?php _e('Global', 'ae-pro'); ?></option>
                <option value="none"><?php _e('None', 'ae-pro'); ?></option>
                <?php if(count($ae_term_templates)) {
                    foreach ($ae_term_templates[$taxonomy] as $template_id => $title) : ?>
                        <option value="<?php echo $template_id; ?>" class=""><?php echo $title; ?></option>
                <?php endforeach;
                }
                ?>
            </select>
            </div>
            <div class="form-field term-group">
            <label for="ae_term_post_template"><?php _e('AE Pro Singular Template', 'ae-pro'); ?></label>
            <select class="postform" id="equipment-group" name="ae_term_post_template">
                <?php if(count($ae_post_templates)){
                    foreach ($ae_post_templates as $key => $value) : ?>
                        <option value="<?php echo $key; ?>" class=""><?php echo $value; ?></option>
                <?php endforeach;
                }
                ?>
            </select>
            <br/><p><em><?php echo __('It will be applied on singular layout of all posts/cpt\'s of this term', 'ae-pro'); ?></em></p>
            </div><?php
        },10,2);

        // Edit Form Field
        add_action($taxonomy->name.'_edit_form_fields', function ($term, $taxonomy){
            global $ae_term_templates;
            global $ae_post_templates;

            $ae_term_templates_list['global'] = __('Global', 'ae-pro');
            $ae_term_templates_list['none'] =  __('None', 'ae-pro');

            if(isset($ae_term_templates[$taxonomy]) && is_array($ae_term_templates[$taxonomy]) && count($ae_term_templates[$taxonomy])){
                $ae_term_templates_list = array_replace($ae_term_templates_list,$ae_term_templates[$taxonomy]);
            }


            // get current template
            $ae_term_template = get_term_meta($term->term_id, 'ae_term_template', true);
            ?>
            <tr class="form-field term-group-wrap">
                <th scope="row"><label for="ae_term_template"><?php _e('AE Pro Term Template', 'ae-pro'); ?></label></th>
                <td><select class="postform" id="feature-group" name="ae_term_template">
                        <?php if(count($ae_term_templates_list)){
                            foreach( $ae_term_templates_list as $template_id => $title ) : ?>
                                <option value="<?php echo $template_id; ?>" <?php selected( $ae_term_template, $template_id ); ?>><?php echo $title; ?></option>
                            <?php endforeach;
                        } ?>
                    </select></td>
            </tr>
            <?php $ae_current_post_template = get_term_meta($term->term_id, 'ae_term_post_template', true); ?>
            <tr class="form-field term-group-wrap">
            <th scope="row"><label for="ae_term_post_template"><?php _e('AE Pro Singular Template', 'ae-pro'); ?></label></th>
            <td><select class="postform" id="feature-group1" name="ae_term_post_template">
                    <?php if(count($ae_post_templates)){
                        foreach ($ae_post_templates as $key => $value) : ?>
                            <option value="<?php echo $key; ?>" <?php selected( $ae_current_post_template, $key) ?>><?php echo $value; ?></option>
                        <?php endforeach;
                    } ?>
                </select>
                <br/><p><em><?php echo __('It will be applied on singular layout of all posts/cpt\'s of this term', 'ae-pro'); ?></em></p>
            </td>
            </tr>

            <?php
        },10,2);

        add_action('created_'.$taxonomy->name, function($term_id, $tt_id){
            if( isset( $_POST['ae_term_template'] ) && '' !== $_POST['ae_term_template'] ){
                $template = sanitize_title( $_POST['ae_term_template'] );
                add_term_meta( $term_id, 'ae_term_template', $template, true );
            }
            if( isset( $_POST['ae_term_post_template'] ) && '' !== $_POST['ae_term_post_template'] ) {
                $termposttemplate = sanitize_title( $_POST['ae_term_post_template'] );
                add_term_meta( $term_id, 'ae_term_post_template', $termposttemplate ,  true);
            }
        },10,2);

        add_action('edited_'.$taxonomy->name, function($term_id, $tt_id){
            if( isset( $_POST['ae_term_template'] ) && '' !== $_POST['ae_term_template'] ){
                $template = sanitize_title( $_POST['ae_term_template'] );
                update_term_meta( $term_id, 'ae_term_template', $template );
            }
            if( isset( $_POST['ae_term_post_template'] ) && '' !== $_POST['ae_term_post_template'] ) {
                $termposttemplate = sanitize_title( $_POST['ae_term_post_template'] );
                update_term_meta( $term_id, 'ae_term_post_template', $termposttemplate );
            }
        },10,2);
    }

});

