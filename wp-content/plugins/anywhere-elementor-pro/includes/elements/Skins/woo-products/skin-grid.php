<?php
namespace Aepro\Woo_Products\Skins;

use Aepro\Helper;
use Elementor\Widget_Base;
use Elementor\Plugin;
use \WP_Query;

class Skin_Grid extends Skin_Base{

    public function get_id() {
        return 'grid';
    }

    public function get_title() {
        return __( 'Grid', 'ae-pro' );
    }

    protected function _register_controls_actions() {
        parent::_register_controls_actions();
    }

    public function register_controls( Widget_Base $widget ) {
        $this->parent = $widget;
        parent::product_query_controls();
        parent::grid_view_controls();

    }

    public function register_style_controls(){

        parent::grid_style_control();

    }

    public function render(){

        $args = parent::get_products_query_args();
        if(count($args) == 0){
            return;
        }

        if(count($args['post__in']) == 0){
            return;
        }

        $templates = $this->get_instance_value('template');
        $masonry= $this->get_instance_value('masonry');
        $animation = $this->get_instance_value('animation');

        $withcss =false;
        if(Plugin::instance()->editor->is_edit_mode()){
            $withcss = true;
        }
        ?>
        <div class="ae-grid-wrapper">
            <div class="ae-grid">
                <?php
                $loop = new WP_Query( $args );
                while ( $loop->have_posts() ) {
                    $loop->the_post();
                    global $product;
                    ?>
                    <div class="ae-grid-item">
                        <div class="ae-grid-item-inner">
                            <?php echo Plugin::instance()->frontend->get_builder_content( $templates,$withcss );?>
                            <div class = "ae-grid-overlay <?php echo $animation ?>"></div>
                        </div>
                    </div>
                    <?php
                }
                wp_reset_postdata();
                ?>
            </div>
        </div>
        <?php
    }
}