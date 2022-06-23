/*
 * DCE EDITOR
 * dynamic.ooo
 */

/******************************************************************************/

jQuery(window).on('elementor:init', function () {
    // Add Control Handlers
    elementor.hooks.addAction('panel/open_editor/widget/form', onPanelShowFormFields);
});
jQuery(window).on('load', function () {
    jQuery(document).on('change', '.elementor-control-custom_id input', function() {
        //alert('change');
       updateFormFieldsSelect(); 
    });
    jQuery(document).on('click', '.elementor-control-form_fields .elementor-repeater-add', function() {
        //alert('add');
       updateFormFieldsSelect(); 
    });
    jQuery(document).on('mouseup', '.elementor-control-form_fields .elementor-repeater-row-tool', function() {
        //alert('tool');
       updateFormFieldsSelect(); 
    });
    jQuery(document).on('mouseup', '.elementor-control-section_form_fields', function() {
        //alert('form-fields');
       updateFormFieldsSelect(); 
    });
    jQuery(document).on('mouseup', '.elementor-control-section_submit_button', function() {
        //alert('submit-btn');
       updateFormFieldsSelect(); 
    });
    jQuery(document).on('mouseup', '.elementor-control-section_dce_form_email', function() {
        //alert('dyn email');
        updateFormFieldsSelect('.elementor-control-dce_form_email_condition_field select', 'dce_form_email_repeater'); 
    });
    jQuery(document).on('mouseup', '.elementor-control-section_dce_form_save', function() {
        //alert('dyn email');
        updateFormFieldsSelect('.elementor-control-dce_form_save_metas select', 'dce_form_email_repeater'); 
    });
    jQuery(document).on('mouseup', '.elementor-control-section_dce_form_paypal', function() {
        //alert('dyn email');
        updateFormFieldsSelect('.elementor-control-dce_form_paypal_condition_field select'); 
    });
    
    
    jQuery(document).on('change', '.elementor-control-field_label input', function() {
        if (jQuery(this).val()) {
            var custom_id = jQuery(this).closest('.elementor-repeater-row-controls').find('.elementor-control-custom_id input').first();
            if (custom_id) {
                if (custom_id.val().substr(0,6) == 'field_') {
                    //custom_id.val(jQuery(this).val().toLowerCase().split(' ').join('_'));
                    /*custom_id.trigger( "keydown" );
                    custom_id.trigger( "keypress" );
                    custom_id.trigger( "keyup" );
                    custom_id.trigger( 'update' );
                    custom_id.trigger( "change" );*/
                    //alert(custom_id.val());
                    //custom_id.closest('.elementor-control').trigger( 'update' );
                    //jQuery(document).trigger( 'update', '#'+custom_id.attr('id') );
                    //custom_id.closest('.elementor-control').trigger( "change" );
                }
            }
        }
    });
});


function onPanelShowFormFields(panel, model) {
    updateFormFieldsSelect();
}
function updateFormFieldsSelect(section_input = '', section_name = '') {
    // wait until elementor do its things
    setTimeout(function(){
        var custom_ids = [];
        var settings = elementorFrontend.config.elements.data[dce_model_cid].attributes;
        var fields = settings['form_fields'];
        var options = '';
        if (section_input) {
            var options = '<option value="">No field</option>';
        }
        jQuery(fields.models).each(function(index,element){
            custom_ids.push(element.attributes.custom_id);
            options += '<option value="'+element.attributes.custom_id+'">'+element.attributes.custom_id+'</option>';
        });
        jQuery(fields.models).each(function(index,element){
            var custom_id_input = false;
            jQuery(".elementor-control-custom_id input").each(function(index,input){
                if (jQuery(this).val() == element.attributes.custom_id) {
                    custom_id_input = jQuery(this);
                }
            });
            if (section_input) {
                // custom field selector
                var select = jQuery(section_input).first();
                if (select) {
                    if (select.closest('.elementor-control-type-repeater').length) {
                        if (select.closest('.elementor-control-type-repeater').hasClass('elementor-control-'+section_name)) {
                            // in repeater
                            jQuery(section_input).each(function(index, select){
                                var data_setting = jQuery(this).attr('data-setting');
                                var ids = settings[section_name]['models'][index]['attributes'][data_setting];
                                updateFormSelect(jQuery(this), options, ids, custom_id_input);
                            });
                        }
                    } else {             
                        // single field
                        var data_setting = select.data('setting');
                        var ids = settings[data_setting];
                        updateFormSelect(select, options, ids, custom_id_input);
                    }
                }
            } else {
                var ids = element.attributes.dce_field_visibility_field;
                var ids_multiple = element.attributes.dce_field_visibility_field_multiple;
                if (custom_id_input) {                    
                    // each field
                    var select = custom_id_input.closest('.elementor-repeater-row-controls').find('.elementor-control-dce_field_visibility_field select').first();
                    var select_multiple = custom_id_input.closest('.elementor-repeater-row-controls').find('.elementor-control-dce_field_visibility_field_multiple select').first();
                } else {
                    // for submit btn
                    var ids = settings.dce_field_visibility_field;
                    var select = jQuery('.elementor-control-dce_field_visibility_field select').first();
                    var select_multiple = jQuery('.elementor-control-dce_field_visibility_field_multiple select').first();
                }
                updateFormSelect(select, options, ids, custom_id_input);
                updateFormSelect(select_multiple, options, ids, custom_id_input);
            }
        });
        //console.log('form fields refreshed');
    }, 1000);
}

function updateFormSelect(select, options, ids, custom_id_input) {
    if (select) {
        var is_select2 = false;
        if (select.hasClass("select2-hidden-accessible")) {
            // Select2 has been initialized
            select.select2('destroy');
            is_select2 = true;
        }
        select.html(options);
        if (custom_id_input) {
            // remove itself
            select.find("option[value='"+custom_id_input.val()+"']").remove();
        }
        select.val(ids);
        if (is_select2) {            
            // Select2 has been initialized
            select.select2();
        }
    }
}
