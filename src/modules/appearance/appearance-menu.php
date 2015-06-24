<div class="wrap">
	<form method="post" action="options.php">
		<h2>
			<img src="<?php echo esc_url( plugins_url( 'public/assets/img/bpt.png', self::$plugin_root ) )?>">
		</h2>
		<?php do_settings_sections( self::$menu_slug . '_appearance' ); ?>
		<?php settings_fields( self::$menu_slug ); ?>
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
	</form>
</div>
