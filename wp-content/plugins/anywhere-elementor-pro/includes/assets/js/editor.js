jQuery(document).ready(function (){
    elementor.hooks.addAction( 'panel/open_editor/widget', function( panel, model, view ) {
        var widget_type = model.attributes.widgetType;

        if(widget_type == 'ae-acf-repeater') {
            jQuery(document).on('change', "select[data-setting='layout_mode']", function () {
                //jQuery('settings[data-setting="ae_post_type"]').select2().trigger('change');
                elementor.reloadPreview();
            });
        }

        if(widget_type == 'ae-acf'){
            $('.elementor-control-_skin label').html('Field Type');
        }

        if(widget_type == 'ae-pods'){
            $('.elementor-control-_skin label').html('Field Type');
        }

        if(widget_type == 'ae-post-blocks'){
            jQuery(document).on('change', "select[data-setting='layout_mode']",  function () {
                //jQuery('settings[data-setting="ae_post_type"]').select2().trigger('change');
                //elementor.reloadPreview();
            });

            selected_post_ids = model.attributes.settings.attributes.ae_post_ids;

            // get selected data
            jQuery.ajax({
                url: aepro.ajaxurl,
                dataType: 'json',
                method: 'post',
                data: {
                    selected_posts : selected_post_ids,
                    action: 'ae_post_data',
                    fetch_mode: 'selected_posts'
                },
                success: function(res){
                    options = '';
                    if(res.data.length) {
                        jQuery.each(res.data, function (key, value) {
                            options += '<option selected value="' + value['id'] + '">' + value['text'] + '</option>';
                        });
                    }

                    jQuery("select[data-setting='ae_post_ids']").html(options).select2({
                        ajax: {
                            url: aepro.ajaxurl,
                            dataType: 'json',
                            data: function (params) {
                                return {
                                    q: params.term,
                                    action: 'ae_post_data',
                                    fetch_mode: 'posts'
                                }
                            },
                            processResults: function (res) {
                                return {
                                    results: res.data
                                }
                            }
                        }    ,
                        minimumInputLength: 2
                    });
                }
            });
        }
    } );
});

