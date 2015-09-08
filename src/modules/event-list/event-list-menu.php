<div class="wrap">
	<form method="post" action="options.php">
		<?php settings_fields( self::$menu_slug . self::$module_name ); ?>
		<h2>
			<img src="<?php echo esc_url( plugins_url( 'public/assets/img/bpt.png', self::$plugin_root ) )?>">
		</h2>
		<h1>Event List Display Settings</h1>
		<p>Configure how the event list displays your event information.</p>
		<?php do_settings_sections( self::$menu_slug . self::$module_name ); ?>
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Event List Settings' ); ?>" />
	</form>
</div>
