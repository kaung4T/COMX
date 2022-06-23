<?php
/**
 * Recommended Plugins Panel
 *
 * @package Newsup
 */
?>
<div id="recommended-plugins-panel" class="panel-left">
	<?php 
	$newsup_free_plugins = array(
		'one-click-demo-import' => array(
		    'name'     	=> 'One Click Demo Import',
			'slug'     	=> 'one-click-demo-import',
			'filename' 	=> 'one-click-demo-import.php',
		),
	);
	if( !empty( $newsup_free_plugins ) ) { ?>
		<div class="recomended-plugin-wrap">
		<?php
		foreach( $newsup_free_plugins as $newsup_plugin ) {
			$info 		= newsup_call_plugin_api( $newsup_plugin['slug'] ); ?>
			<div class="recom-plugin-wrap mb-0">
				<div class="plugin-title-install clearfix">
					<span class="title" title="<?php echo esc_attr( $plugin['name'] ); ?>">
					<?php echo esc_html( $newsup_plugin['name'] ); ?>
					</span>
					<?php if($newsup_plugin['slug'] == 'one-click-demo-import') : ?>
					<p><?php echo esc_html( 'First of all download demo files from', 'newsup' ); ?></h3>
					<a target="_blank" href="<?php echo esc_url( 'https://themeansar.com/free-themes/newsup/' ); ?>"><?php esc_html_e( 'Newsup Detail page', 'newsup' ); ?></a>
					<?php echo esc_html(', then install and activate','newsup'); ?>
					<a href="https://wordpress.org/plugins/one-click-demo-import/" target="_blank"><?php echo esc_html( 'One Click Demo Import', 'newsup' ); ?></a><?php echo esc_html(' plugin. After that, import sample demo content, visit Import Demo Data menu under Appearance.', 'newsup'); ?></p>
					<?php endif; ?>
					<?php echo '<div class="button-wrap">';
					echo Newsup_Getting_Started_Page_Plugin_Helper::instance()->get_button_html( $newsup_plugin['slug'] );
					echo '</div>';
					?>
				</div>
			</div>
			</br>
			<?php
		} ?>
		</div>
	<?php
	} ?>
</div>