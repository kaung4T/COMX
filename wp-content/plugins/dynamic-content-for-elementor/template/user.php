<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
get_header();
$dce_default_options = get_option( DCE_OPTIONS );
//
$global_is = 'user';

// BEFORE
$dce_before_template = null;
if( isset($dce_default_options['dyncontel_field_singleuser']) ) $dce_before_template = $dce_default_options['dyncontel_field_singleuser'];
if( isset($dce_default_options['dyncontel_before_field_archiveuser']) ) $dce_before_template = $dce_default_options['dyncontel_before_field_archiveuser'];
// AFTER
$dce_after_template = null;
if( isset($dce_default_options['dyncontel_field_singleuser']) ) $dce_after_template = $dce_default_options['dyncontel_field_singleuser'];
if( isset($dce_default_options['dyncontel_after_field_archiveuser']) ) $dce_after_template = $dce_default_options['dyncontel_after_field_archiveuser'];
//
$dce_block_template = 'dyncontel_field_archiveuser';
//
$dce_template_layout = $dce_default_options[$dce_block_template.'_template'];
//
$dce_default_template = $dce_default_options[$dce_block_template]; //$this->options[$dce_block_template];
//            
$dce_col_md = $dce_default_options[$dce_block_template.'_col_md'];
$dce_col_sm = $dce_default_options[$dce_block_template.'_col_sm'];
$dce_col_xs = $dce_default_options[$dce_block_template.'_col_xs'];
?>
<div id="content-wrap" class="clr">

    <div id="primary" class="clr">


        <div id="content" class="site-content clr">
        <?php


            // -------- quasta è la pagina del template che viene impostata nei settings di User -----------
            if ( isset($dce_before_template) && $dce_before_template > 1 ) {
                echo do_shortcode('[dce-elementor-template id="' . $dce_before_template . '"]');
            }
            else {
              /* ?>

                <!-- This sets the $curauth variable -->
                <div class="container">
                    <div id="data-user-page">
                        <?php
                        $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
                        
                        if($curauth->user_email) echo '<div class="user-avatar">'.get_avatar( $curauth->user_email, '300' ).'</div>';
                        
                        //if($curauth->user_nicename) echo '<div class="user-nicename">'.$curauth->user_nicename.'</div>';
                        //if($curauth->nickname) echo '<div class="user-nickname">'.$curauth->nickname.'</div>';
                        //if($curauth->user_login) echo '<div class="user-login">'.$curauth->user_login.'</div>';
                        if($curauth->display_name) echo '<div class="user-name">'.$curauth->display_name.'</div>';
                        
                        if($curauth->description) echo '<div class="user-description">'.$curauth->description.'</div>';
                        
                        if($curauth->first_name) echo '<div><span class="user-firstname">'.$curauth->first_name.'</span> ';
                        if($curauth->last_name) echo '<span class="user-lastname">'.$curauth->last_name.'</span></div>';

                        //
                        if($curauth->user_email) echo '<div class="user-email">'.$curauth->user_email.'</div>';

                        //if($curauth->user_registered) echo '<div class="user-registred">'.$curauth->user_registered.'</div>';
                        if($curauth->user_url) echo '<div class="user-url">URL: '.$curauth->user_url.'</div>';
                        //if($curauth->yim) echo '<div class="">'.$curauth->yim.'</div>';
                        //if($curauth->ID) echo '<div class="user-id">'.$curauth->ID.'</div>';
                        //if($curauth->jabber) echo '<div class="">'.$curauth->jabber.'</div>';
                        //if($curauth->aim) echo '<div class="">'.$curauth->aim.'</div>';

                        ?>  
                </div>
                <?php */
            }
            //
            
            
            
            ?>
            </div>
            <?php
            if( $dce_default_template > 1 ){
            ?>
            <div class="grid-user-page grid-page grid-col-md-<?php echo $dce_col_md; ?> grid-col-sm-<?php echo $dce_col_sm; ?> grid-col-xs-<?php echo $dce_col_xs; ?>">
            <?php
            // -------- quastO è il BLOCCO template che viene impostata nei settings di User -----------
            if ($dce_default_template) {
                if ($dce_template_layout == 'canvas') {
                    echo do_shortcode('[dce-elementor-template id="' . $dce_default_template . '"]');
                } else {
                    if ( have_posts() ) : while ( have_posts() ) : the_post(); 
                       echo '<div class="item-user-page item-page">';
                            the_content();
                       echo '</div>';
                     endwhile; else: ?>
                        <p><?php __('No posts by this author.','dynamic-content-for-elementor' ); ?></p>
                    <?php endif;
                }
            }
            ?>
            <!-- End Loop -->
            </div>
            <?php 
            }

            if ( isset($dce_after_template) && $dce_after_template > 1 ) {
                echo do_shortcode('[dce-elementor-template id="' . $dce_after_template . '"]');
            }
            ?>
            

        </div><!-- #content -->


    </div><!-- #primary -->



</div><!-- #content-wrap -->

<?php get_footer(); ?>