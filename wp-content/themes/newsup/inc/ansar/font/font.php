<?php
/*--------------------------------------------------------------------*/
/*     Register Google Fonts
/*--------------------------------------------------------------------*/
function newsup_fonts_url() {
	
    $fonts_url = '';
		
    $font_families = array();
 
	$font_families = array('Montserrat:400,500,700,800|Work+Sans:300,400,500,600,700,800,900&display=swap');
 
        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );
 
        $fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );

    return $fonts_url;
}
function newsup_scripts_styles() {
    wp_enqueue_style( 'newsup-fonts', newsup_fonts_url(), array(), null );
}
add_action( 'wp_enqueue_scripts', 'newsup_scripts_styles' );