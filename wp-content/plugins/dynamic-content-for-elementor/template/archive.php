<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();

global $global_TYPE;
global $global_is;
global $default_template;

//var_dump(is_404()); die();

$dce_default_options = get_option( DCE_OPTIONS );
$global_is = 'archive';
$cptype_archive = get_post_type();
//

// after before data id
if( isset( $cptype_archive ) && isset($dce_default_options['dyncontel_before_field_archive'. $cptype_archive])){
    $dce_before_archive = $dce_default_options['dyncontel_before_field_archive'. $cptype_archive];
}else{
    $dce_before_archive = '';
}
if( isset( $cptype_archive ) && isset($dce_default_options['dyncontel_after_field_archive'. $cptype_archive])){
    $dce_after_archive = $dce_default_options['dyncontel_after_field_archive'. $cptype_archive];
}else{
    $dce_after_archive = '';
}
// dyncontel_before_field_archive_taxonomy_locali
// relativo al template assegnato dai settings

$queried_object = get_queried_object();
if( 
    isset( $queried_object->taxonomy ) 
    && isset( $dce_default_options['dyncontel_field_archive_taxonomy_' . $queried_object->taxonomy ]) 
    && $dce_default_options['dyncontel_field_archive_taxonomy_' . $queried_object->taxonomy ]
    && isset( $dce_default_options['dyncontel_field_archive_taxonomy_' . $queried_object->taxonomy . '_template']) 
    && $dce_default_options['dyncontel_field_archive_taxonomy_' . $queried_object->taxonomy . '_template']
){
    $dce_elementor_templates = 'dyncontel_field_archive_taxonomy_'. $queried_object->taxonomy;
    //echo 'tax';
}else{
   $dce_elementor_templates = 'dyncontel_field_archive'. $cptype_archive; 
    //echo 'type';
}



//echo 'aaa';
//var_dump($dce_elementor_templates);

//$dce_elementor_templates = 'dyncontel_field_archive' . $cptype_archive;
//echo $dce_default_options['dyncontel_field_archive' . $cptype_archive.'_template'];
//echo $dce_default_options['dyncontel_field_archive_taxonomy_' . $queried_object->taxonomy . '_template'];
//echo $queried_object->taxonomy;

//
$dce_default_template = $dce_default_options[$dce_elementor_templates]; //                      ID
$dce_default_template_base = $dce_default_options[$dce_elementor_templates.'_template']; //     canvas | boxed | fullwidth
//$tenpdyn = $dce_default_template;

if (is_tax()) {
    // In caso di Termine Rileggo l'id del template (migliorabile)
    $termine_id = get_queried_object()->term_id;
    $dce_default_template_term = get_term_meta($termine_id, 'dynamic_content_block', true);

    if (!empty($dce_default_template_term) && $dce_default_template_term > 1) {

                $dce_default_template = $dce_default_template_term;
            }
}


//var_dump($dce_elementor_templates);
//var_dump($dce_default_template_base);
/*echo '-------- '.$dce_elementor_templates.'++++++++'.$dce_default_options['dyncontel_field_archive_taxonomy_' . $queried_object->taxonomy . '_template'].'--------';*/
//var_dump($dce_default_options);

$dce_col_md = $dce_default_options[$dce_elementor_templates.'_col_md'];
$dce_col_sm = $dce_default_options[$dce_elementor_templates.'_col_sm'];
$dce_col_xs = $dce_default_options[$dce_elementor_templates.'_col_xs'];

?>

<div id="content-wrap" class="clr">

    <div id="primary" class="clr">
        
        <div id="content" class="site-content clr">
            <?php do_action( 'dce_before_content_inner' ); ?>
            
            <div class="dce-wrapper-container">
                <div class="dce-container <?php if ($dce_default_template_base == 'boxed') { ?>container<?php } else { ?>container-fluid<?php } ?>">
                   
                    <?php
                    // echo $dce_before_archive.'<br>';
                    // echo $dce_default_options[$dce_elementor_templates].'<br>';
                    // echo 'dyncontel_before_field_archive'. $dce_elementor_templates.'<br>';
                    //echo 't: '.$default_template;
                    /*if( !$dce_before_archive && !$default_template ){
                        // ------------------------------------------
                        
                        if ($dce_default_template_base != 'canvas') {
                            //
                            $title = __('Title of post', 'dynamic-content-for-elementor');

                            //echo 'T: '.single_term_title().' - ';
                            //echo 'H: '.get_the_title().' - ';
                            //echo 'A: '.single_cat_title('', false).' - ';
                            
                            // Archives
                            //var_dump(is_tax()); die();
                            if (is_tax()) {
                                $title = single_term_title('', false);
                                //var_dump($title); die();
                                //echo ' T';
                            } else if( is_home() ){
                                $object_t = get_post_type_object( $cptype_archive )->labels;
                                $label_t = $object_t->name;
                                $title = $label_t;
                                //echo ' H';
                            } else if (is_archive()) {
                                //echo get_the_title();
                                $title = post_type_archive_title('',false);
                                if($title == '') $title = single_cat_title('', false); //get_the_archive_title($id_page);  //
                                // TODO
                                //echo ' A';
                            } else {
                                $title = get_the_title();
                                //echo ' ???';
                            }
                            
                            ?>
                            <h1 class="archive-title"><?php echo $title; ?></h1>
                            <?php if( term_description() != ''){ 
                            echo '<div class="archive-description">';
                            
                                echo term_description();
                            
                            echo '</div>';
                            }
                        }
                    }*/

                    if (!empty($dce_default_template)) {
                      //echo 'connnnn'.$dce_default_template;
                      if ($dce_default_template > 1) {
                          //include DCE_PATH . '/template/template.php';
                        //echo do_shortcode('[dce-elementor-template id="' . $dce_default_template . '"]');
                      }
                      //$tenpdyn = 'sono basato su default'.get_option( 'dyncontel_options' )['dyncontel_field_archive'.$cptype]; //dyncontel_field_single'.$cptype;
                      //
                      //echo $tenpdyn = $pagina_temlate;
                    }
                    //echo $dce_default_template_base;
                    if ($dce_default_template_base == 'canvas') {
                            //the_content();
                            $global_is = 'archive';
                            echo do_shortcode('[dce-elementor-template id="' . $dce_default_template . '"]');
                        }else{
                    ?>
               
                    <!-- The Loop -->
                    <div class="grid-archive-page grid-page grid-col-md-<?php echo $dce_col_md; ?> grid-col-sm-<?php echo $dce_col_sm; ?> grid-col-xs-<?php echo $dce_col_xs; ?>">
                    <?php 

                    //echo '-> '.$dce_default_template;
                    $data_columns = ' data-col-md="'.$dce_col_md.'" data-col-sm="'.$dce_col_sm.'" data-col-xs="'.$dce_col_xs.'"';    
                    //echo 'Archivio '.$dce_default_template;     
                    if ($dce_default_template) {
                        

                            if ( have_posts() ) : while ( have_posts() ) : the_post(); 
                               echo '<div class="item-archive-page item-page"'.$data_columns.'>';
                                    the_content();
                                    //echo do_shortcode('[dce-elementor-template id="' . $dce_default_template_base . '"]');
                               echo '</div>';
                             endwhile; 
                             
                             \DynamicContentForElementor\DCE_Helper::dce_numeric_posts_nav();
                             else: ?>
                                <p><?php __('No posts by this author.','dynamic-content-for-elementor' ); ?></p>
                            <?php endif;
                        
                    }   
                    ?>
                    <!-- End Loop -->
                    </div><!-- End Grid -->
                <?php } ?>
                </div><!-- End Container -->
            </div><!-- End Wrapper-container -->
        <?php do_action( 'dce_after_content_inner' ); ?>
        </div><!-- End #content -->

    </div><!-- End #primary -->

</div><!-- End #content-wrap -->

<?php get_footer(); ?>
