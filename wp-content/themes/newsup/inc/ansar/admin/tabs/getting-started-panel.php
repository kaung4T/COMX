<?php
/**
 * Getting Started Panel.
 *
 * @package Newsup
 */
?>
<div id="getting-started-panel" class="panel-left visible">
    <div class="panel-aside panel-column">
        <h4><?php esc_html_e( 'Demo Content', 'newsup' ); ?></h4>
		<a class="recommended-actions hyperlink" href="#actions"><?php esc_html_e( 'Demo Content', 'newsup' ); ?></a>
    </div> 
    <div class="panel-aside panel-column">
        <h4><?php esc_html_e( 'Newsup Documentation', 'newsup' ); ?></h4>
        <a href="https://docs.themeansar.com/docs/newsup/" class="hyperlink" title="<?php esc_attr_e( 'Newsup Support', 'newsup' ); ?>" target="_blank"><?php esc_html_e( 'Documentation', 'newsup' ); ?></a>
    </div>
   <div class="panel-aside panel-column">
        <h4><?php esc_html_e( 'Go to the Customizer', 'newsup' ); ?></h4>
        <a class="button button-primary" target="_blank" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" title="<?php esc_attr_e( 'Visit the Support', 'newsup' ); ?>"><?php esc_html_e( 'Go to the Customizer', 'newsup' ); ?></a>
    </div>
</div>