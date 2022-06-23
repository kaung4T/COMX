<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Utils;
use DynamicContentForElementor\DCE_Helper;
use DynamicContentForElementor\Controls\DCE_Group_Control_Transform_Element;
//use DynamicContentForElementor\Group_Control_AnimationElement;

if (!defined('ABSPATH')) {
    exit;
}
class DCE_Widget_Woo_Rating extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce-woocommerce-rating';
    }
    
    static public function is_enabled() {
        return false;
    }
    
    public function get_title() {
        return __('Rating', 'dynamic-content-for-elementor');
    }
    
    public function get_icon() {
        return 'icon-dyn-woo_ratings todo';
    }
    
    static public function get_position() {
        return 7;
    }
    public function get_plugin_depends() {
        return array('woocommerce' => 'woocommerce');
    }
    protected function _register_controls() {
        $this->start_controls_section(
            'section_content', [
                'label' => __('Settings', 'dynamic-content-for-elementor'),
            ]
        );
        
        $this->end_controls_section();

        
    }

    protected function render() {
        $settings = $this->get_active_settings();
        if ( empty( $settings ) )
           return;
        //
        // ------------------------------------------
        $demoPage = get_post_meta(get_the_ID(), 'demo_id', true);
        //
        $id_page = ''; //get_the_ID();
        $type_page = '';

        global $global_ID;
        global $global_TYPE;
        global $in_the_loop;
        global $global_is;
        //
        global $product;
        //
        if(!empty($demoPage)){
          //echo 'DEMO';
          $id_page = $demoPage;
          $type_page = get_post_type($demoPage);
          $product = wc_get_product( $demoPage );
          //echo 'DEMO ...';
        } 
        else if (!empty($global_ID)) {
          //echo 'GLOBAL';
          $id_page = $global_ID;
          $type_page = get_post_type($id_page);
          //echo 'global ...';
          if(!isset($product) || !$product){
            $product = wc_get_product( $global_ID );
          }
        }else {
          //echo 'Select DEMO product for show the value.';
          //echo 'NATURAL';
          $id_page = get_the_id();
          $type_page = get_post_type();
        }

        if ( empty( $product ) )
           return;
        // ------------------------------------------
        //$this->crea_woocrating($product);

        wc_get_template( 'single-product/rating.php' );

        
    }

    protected function _content_template() {
        
    }
    protected function crea_woocrating($product) {
        
        if ( ! comments_open() ) {
            return;
        }

        ?>
        <div id="reviews" class="woocommerce-Reviews">
            <div id="comments">
                <h2 class="woocommerce-Reviews-title"><?php
                    if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' && ( $count = $product->get_review_count() ) ) {
                        /* translators: 1: reviews count 2: product name */
                        printf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'woocommerce' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
                    } else {
                        _e( 'Reviews', 'woocommerce' );
                    }
                ?></h2>

                <?php if ( have_comments() ) : ?>

                    <ol class="commentlist">
                        <?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
                    </ol>

                    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
                        echo '<nav class="woocommerce-pagination">';
                        paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', array(
                            'prev_text' => '&larr;',
                            'next_text' => '&rarr;',
                            'type'      => 'list',
                        ) ) );
                        echo '</nav>';
                    endif; ?>

                <?php else : ?>

                    <p class="woocommerce-noreviews"><?php _e( 'There are no reviews yet.', 'woocommerce' ); ?></p>

                <?php endif; ?>
            </div>

            <?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>

                <div id="review_form_wrapper">
                    <div id="review_form">
                        <?php
                            $commenter = wp_get_current_commenter();

                            $comment_form = array(
                                'title_reply'          => have_comments() ? __( 'Add a review', 'woocommerce' ) : sprintf( __( 'Be the first to review &ldquo;%s&rdquo;', 'woocommerce' ), get_the_title() ),
                                'title_reply_to'       => __( 'Leave a Reply to %s', 'woocommerce' ),
                                'title_reply_before'   => '<span id="reply-title" class="comment-reply-title">',
                                'title_reply_after'    => '</span>',
                                'comment_notes_after'  => '',
                                'fields'               => array(
                                    'author' => '<p class="comment-form-author">' . '<label for="author">' . esc_html__( 'Name', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label> ' .
                                                '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" required /></p>',
                                    'email'  => '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label> ' .
                                                '<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" aria-required="true" required /></p>',
                                ),
                                'label_submit'  => __( 'Submit', 'woocommerce' ),
                                'logged_in_as'  => '',
                                'comment_field' => '',
                            );

                            if ( $account_page_url = wc_get_page_permalink( 'myaccount' ) ) {
                                $comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a review.', 'woocommerce' ), esc_url( $account_page_url ) ) . '</p>';
                            }

                            if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
                                $comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'woocommerce' ) . '</label><select name="rating" id="rating" aria-required="true" required>
                                    <option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
                                    <option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
                                    <option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
                                    <option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
                                    <option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
                                    <option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
                                </select></div>';
                            }

                            $comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required></textarea></p>';

                            comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
                        ?>
                    </div>
                </div>

            <?php else : ?>

                <p class="woocommerce-verification-required"><?php _e( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ); ?></p>

            <?php endif; ?>

            <div class="clear"></div>
        </div>
        <?php
    }
}