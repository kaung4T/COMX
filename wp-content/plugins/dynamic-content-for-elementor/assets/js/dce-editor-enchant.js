/*
 * DCE EDITOR
 * dynamic.ooo
 */

// SELECT2 everywhere
jQuery(window).on( 'load', function() {
//jQuery(window).on('elementor:init', function () {
//jQuery( window ).on( 'elementor/frontend/init', function() {
    if ( window.elementorFrontend ) {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $scope ) {
            jQuery('.elementor-control-type-select select').select2();
        } );
    }
    elementor.hooks.addAction( 'panel/open_editor/section', function( panel, model, view ) {
        jQuery('.elementor-control-type-select select').select2();
    } );
    elementor.hooks.addAction( 'panel/open_editor/column', function( panel, model, view ) {
        jQuery('.elementor-control-type-select select').select2();
    } );
    elementor.hooks.addAction( 'panel/open_editor/widget', function( panel, model, view ) {
        jQuery('.elementor-control-type-select select').select2();
    } );

    setInterval(function(){
        // add navigator element toggle
        jQuery('.elementor-control-type-select select').not('.select2-hidden-accessible').each(function(){
            jQuery(this).select2();
        });
    }, 1000);
});

// Hide Description
jQuery(window).on( 'load', function() {
    if ( window.elementorFrontend ) {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $scope ) {
            description_to_abbr();
        } );
    }
    elementor.hooks.addAction( 'panel/open_editor/section', function( panel, model, view ) {
        description_to_abbr();
    } );
    elementor.hooks.addAction( 'panel/open_editor/column', function( panel, model, view ) {
        description_to_abbr();
    } );
    elementor.hooks.addAction( 'panel/open_editor/widget', function( panel, model, view ) {
        description_to_abbr();
    } );

    setInterval(function(){
        // add navigator element toggle
        description_to_abbr();
    }, 1000);
});
function description_to_abbr() {
    jQuery('.elementor-control-field-description').each(function() {
        var title = jQuery(this).siblings('.elementor-control-field').children('.elementor-control-title');
        if (title.text().trim()) {
            var text = jQuery(this).text();
            text = text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            title.wrapInner('<abbr title="'+text+'"></abbr>');
            jQuery(this).remove();
        }
    });
}
