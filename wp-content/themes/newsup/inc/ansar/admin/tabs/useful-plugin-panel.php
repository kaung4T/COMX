<?php
/**
 * Useful Plugin Panel
 *
 * @package Newsup
 */
?>
<div id="useful-plugin-panel" class="panel-left">
	<?php 
	$newsup_free_plugins = array(
		'contact-form-7' => array(
		    'name'      => 'Contact form 7',
			'slug'     	=> 'contact-form-7',
			'filename' 	=> 'contact-form-7.php',
		),
		'woocommerce' => array(
		    'name'     	=> 'Woocommerce',
			'slug'     	=> 'woocommerce',
			'filename' 	=> 'woocommerce.php',
		),
		'elementor' => array(
		    'name'     	=> 'Elementor',
			'slug'     	=> 'elementor',
			'filename' 	=> 'elementor.php',
		),
	);
	if( !empty( $newsup_free_plugins ) ) { ?>
		<div class="recomended-plugin-wrap">
		<?php
		foreach( $newsup_free_plugins as $newsup_plugin ) {
			$info 		= newsup_call_plugin_api( $newsup_plugin['slug'] ); ?>
			<div class="recom-plugin-wrap w-3-col">
				<div class="plugin-title-install clearfix">
					<span class="title" title="<?php echo esc_attr( $plugin['name'] ); ?>">
						<?php echo esc_html( $newsup_plugin['name'] ); ?>	
					</span>
					<?php if($newsup_plugin['slug'] == 'contact-form-7') : ?>
					<p><?php esc_html_e('To display the contact form, please install the Contact Form 7 plugin.', 'newsup'); ?></p>
					<?php endif; ?>
					
					<?php if($newsup_plugin['slug'] == 'woocommerce') : ?>
					<p><?php esc_html_e('To display the Woocommerce layout, please install the Woocommerce plugin.', 'newsup'); ?></p>
					<?php endif; ?>
					
				    <?php if($newsup_plugin['slug'] == 'elementor') : ?>
					<p><?php esc_html_e('To use the Elementor layouts and pages, install the Elementor plugin.', 'newsup'); ?></p>
					<?php endif; ?>	
					<?php 
					echo '<div class="button-wrap">';
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