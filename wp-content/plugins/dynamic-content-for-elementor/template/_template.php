<?php
/**
 * Template dynamic content
 */
//namespace Elementor;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
// Get template ID
//$datopagina = get_post_meta( $dce_default_template, 'oceanwp_pprno_elementor_templates', true );
//echo 'PPPPPPPP '.$dce_elementor_templates;
//echo $dce_default_template.'<br />';
//echo $dce_elementor_templates.'<br />';

//$get_id = $dce_default_template; //get_post_meta( $dce_default_template, $dce_elementor_templates, true );

$get_id = apply_filters( 'wpml_object_id', $dce_default_template,'elementor_library', true );
if(isset($inlinecss) && $inlinecss){
	$inlcss = $inlinecss;
}else{
	$inlcss = false;
}
//var_dump($inlcss);
//echo 'eee: '.$get_id .'<br />';
// Check if the template is created with Elementor
$elementor = get_post_meta($get_id, '_elementor_edit_mode', true);
$pagina_temlate = '';
// If Elementor
if (class_exists('Elementor\Plugin') && $elementor) {

	// Dalla versione 0.1.0 (consigliavano questo) .. ma ha dei limiti ..per tutti i siti fino ad oggi ho fatto così ... e funzione per i template, ma non per i contenuti diretti.
    //$pagina_temlate = Elementor\Plugin::instance()->frontend->get_builder_content_for_display($get_id);
    
	// Dalla versione 0.6.0 dopo ore di ragionamenti vado ad usare questo per generare il contenuto di Elementor. Questo mi permette di usare un contenuto Elementor dentro a un contenuto nel template ... vedi (elementor/includes/frontend.php)
    $pagina_temlate = Elementor\Plugin::instance()->frontend->get_builder_content($get_id, $inlcss);
}else{
	//echo '<div style="margin: 40px; font-size:50px;">contenuto nativo</div>';
	//var_dump($get_id);
	$post_n = get_post($get_id);
	$content_n = apply_filters( 'the_content', $post_n->post_content );
	echo $content_n;

}