



( function( $ ) {
	    
    // --------------------------
	/*function handleGoToPage ( newValue ) {
		alert(newValue);
		if(newValue){
			// SI
			alert('Gooo');
			//$('.dce-go-to-page-template').attr('href','Ciaooooooo');
		}else{
			// NO
		}
	}*/
	//alert($('.unfold-yes .dce-content').height());

	
	$( window ).on( 'elementor/frontend/init', function() {
		/*alert(elementorFrontend.isEditMode());
		if ( elementorFrontend.isEditMode() ){
			elementor.settings.page.addChangeCallback( 'other_post_source', handleGoToPage );
		}*/

		 //alert('main');
		if ( elementorFrontend.isEditMode() ){ 
		   		elementor.channels.editor.on( 'dceMain:previewPage', function(e,editor) {
		   			//alert(e);
		   			var model = e.getOption('editedElementView').getEditModel(),
					    currentElementType = model.get('elType');
		   			//var model = editor.getOption('other_post_source').getEditModel();
		   			//alert(model);
				//elementor.$preview[0].contentWindow.location = 'XXX';
				//console.log(modelE);
				//alert('ss');

			} );
   		}
	});

	// --------





} )( jQuery );