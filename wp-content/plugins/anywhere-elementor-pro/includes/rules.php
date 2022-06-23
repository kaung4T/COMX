<?php
namespace Aepro;

class Rules{

    function get_post_type_conditions(){

        $conditions = array();
        $post_types = get_post_types( array( 'public' => true ), 'objects' );

        foreach ( $post_types as $name => $post_type ) {
            $exluded_post_types = array('ae_global_templates');
            if (in_array($name,$exluded_post_types)) {
                continue;
            }

            $conditions[$name.'_index'] = array(

            );
        }
        return;
        echo '<pre>';
        print_r($conditions);
        die();
    }
}