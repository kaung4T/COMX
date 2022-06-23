jQuery(document).ready(function($){

    var wrapper = $('#aep_config_box');
    wrapper.find('#ae-config-general').attr('aria-hidden', false);
    wrapper.find(".f-row").attr('aria-hidden', true);

    initialLoad();

    activate_post_load();
    activate_term_load();
    activate_acf_repeater_fields_load();


    jQuery(document).on('change',
            '[name="ae_apply_global"], ' +
            '[name="ae_render_mode"], ' +
            '[name="ae_hook_apply_on[]"], ' +
            '[name="ae_usage"]',
        function(){
            wrapper.find(".f-row").attr('aria-hidden', true);
            initialLoad();

    });

    $(".ae-config-wrapper").on('click', '.ae-config-nav a', function(e){
        e.preventDefault();

        $(".ae-config-nav li").attr('aria-selected', false);
        $(this).closest('li').attr('aria-selected', true);

        href = $(this).attr('href');

        $('.ae-config-content').attr('aria-hidden', true);
        $(href).attr('aria-hidden', false);
    });



    function activate_post_load(){
        jQuery('#ae_preview_post_ID').aeselect2({
            ajax: {
                url: ajaxurl,
                dataType: 'json',
                data: function (params) {
                    render_mode = jQuery('[name="ae_render_mode"]').val();
                    if(render_mode != 'block_layout' && render_mode != 'acf_repeater_layout'){
                        post_type = jQuery('#ae_rule_post_type').val();
                    }else{
                        post_type = 'any';
                    }

                    return {
                        q: params.term,
                        action: 'ae_prev_post',
                        post_type: post_type
                    }
                },
                processResults: function (res) {
                    return {
                        results: res.data
                    }
                }
            },
            minimumInputLength: 2
        });
    }

    function activate_term_load(){
        jQuery('#ae_preview_term').aeselect2({
            ajax: {
                url: ajaxurl,
                dataType: 'json',
                data: function (params) {
                    taxonomy = jQuery('#ae_rule_taxonomy').val();
                    return {
                        q: params.term,
                        action: 'ae_prev_term',
                        taxonomy: taxonomy
                    }
                },
                processResults: function (res) {
                    return {
                        results: res.data
                    }
                }
            },
            minimumInputLength: 2
        });
    }

    function activate_acf_repeater_fields_load(){
        jQuery('#ae_preview_post_ID').on('change',function () {


            render_mode = $('[name="ae_render_mode"]').val();
            if(render_mode != 'acf_repeater_layout'){
                return;
            }

            id = jQuery(this).val();
            jQuery.ajax({
                url: ajaxurl,
                dataType: 'json',
                data: {
                    action: 'ae_acf_repeater_fields',
                    post_id: id,
                },
                success: function (res) {
                    jQuery("#ae_acf_repeater_name").find('option').remove().end();
                    if(res.data.length){
                        jQuery.each(res.data, function(i, d) {
                            jQuery("#ae_acf_repeater_name").append(jQuery("<option/>", {
                                value: d.id,
                                text: d.text
                            }));
                        });
                    }
                }
            });
        });

    }


    function initialLoad(){
        showfield('ae_render_mode');

        $('#sec-rules').hide();

        var render_mode = $('[name="ae_render_mode"]').val();

        switch(render_mode){
            case 'post_type_archive_template'   :    pt_archive();
                                                     break;

            case 'post_template'                :    post_template();
                                                     break;

            case 'archive_template'             :    archive_template();
                                                     break;

            case 'block_layout'                 :    block_layout();
                                                     break;

            case 'normal'                       :    normal();
                                                     break;

            case '404'                          :    _404();
                                                     break;

            case 'search'                       :    _search();
                                                     break;

            case 'author_template'              :   _author();
                                                     break;

            case 'date_template'                :   _date();
                                                    break;

            case 'acf_repeater_layout'          :   acf_repeater_layout();
                                                    break;
        }
    }

    function showfield(field){
        $('[name="' + field +'"]').closest('.f-row').attr('aria-hidden', false);
    }

    function _404(){
        showfield('ae_elementor_template');
    }

    function _search(){
        showfield('ae_elementor_template');
    }

    function archive_template(){
        //showfield('ae_preview_post_ID');
        showfield('ae_apply_global');
        showfield('ae_rule_taxonomy');
        showfield('ae_full_override');
        showfield('ae_elementor_template');
        showfield('ae_preview_term');

    }

    function block_layout(){
        showfield('ae_preview_post_ID');

    }

    function acf_repeater_layout(){
        showfield('ae_preview_post_ID');
        showfield('ae_acf_repeater_name');

    }

    function normal(){
        showfield('ae_usage');

        usage_area = $('[name="ae_usage"]').val();

        if(usage_area == 'custom'){
            showfield("ae_custom_usage_area");
        }

        if(usage_area != ''){

            showfield('ae_apply_global');
            auto_apply = $('[name="ae_apply_global"]').is(":checked");

            if(!auto_apply){
                $('li.ae-rules').show();
                // auto apply not set.. reveal advanced rules
                showfield('ae_hook_apply_on[]');

                page_types = $("input[name='ae_hook_apply_on[]']:checked").map(function () {return this.value;}).get();

                // show post options in case of single post

                if(page_types.indexOf('single') >= 0){
                    showfield('ae_hook_post_types[]');
                    showfield('ae_hook_posts_selected');
                    showfield('ae_hook_posts_excluded');
                }

                if(page_types.indexOf('archive') >= 0){
                    showfield('ae_hook_taxonomies[]');
                    showfield('ae_hook_terms_selected');
                    showfield('ae_hook_terms_excluded');
                }
            }



        }
    }

    function post_template(){
        showfield('ae_preview_post_ID');
        showfield('ae_apply_global');
        showfield('ae_rule_post_type');
        showfield('ae_elementor_template');
    }

    function pt_archive(){
        //showfield('ae_preview_post_ID');
        showfield('ae_rule_post_type_archive');
        showfield('ae_full_override');
        showfield('ae_elementor_template');
    }

    function _author() {
        showfield('ae_apply_global');
        showfield('ae_elementor_template');
        showfield('ae_preview_author');
    }

    function _date() {
        //showfield('ae_apply_global');
        showfield('ae_elementor_template');
    }

});

