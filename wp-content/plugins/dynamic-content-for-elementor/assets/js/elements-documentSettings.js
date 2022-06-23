( function( $ ) {
	var settings_page = {};

	
	// Change CallBack - - - - - - - - - - - - - - - - - - - - - - - - -

	function handlescroll_viewport ( newValue ) {
		if(newValue){
			// SI
			
		}else{
			// NO
			
		}
		
	}





	$( window ).on( 'elementor/frontend/init', function() {
		

	} );

	window.onload = function() {
		
	}
	
	$( document ).on( 'ready', function() {
		//alert($('.elementor[data-elementor-type=page]').length);
		if( typeof elementorFrontendConfig.settings.page !== 'undefined' ){
			if ( elementorFrontend.isEditMode() ){
				settings_page = elementorFrontendConfig.settings.page;
			}else{
				settings_page = JSON.parse( $('.elementor').attr('data-elementor-settings') ); //
			}
			
			//alert(settings_page.enable_scrollEffects);
			//console.log($('.elementor').attr('data-elementor-settings'));
			//alert(elementSettings.enable_scrollEffects);
			if( settings_page ){
				

				if ( elementorFrontend.isEditMode() ){
					/*elementor.once( 'preview:loaded', function() {
						// questo è il callBack di fine loading della preview

					} );*/
					elementor.settings.page.addChangeCallback( 'scroll_viewport', handlescroll_viewport );
					

					
					

				}
			}
		}
		
		

	} );
} )( jQuery );