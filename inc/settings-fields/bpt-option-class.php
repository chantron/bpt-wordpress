<?php

/**
 * Brown Paper Tickets Account Settings Fields HTML
 *
 * Here lies the callbacks for the add_settings_fields() function.
 */
namespace BrownPaperTickets;

require_once( plugin_dir_path( __FILE__ ).'../brown-paper-tickets-plugin.php');
require_once( plugin_dir_path( __FILE__ ).'../../lib/bptWordpress.php');
use BrownPaperTickets\BPTPlugin;
use BrownPaperTickets\BptWordpress;

class BptOption {

	protected static $menu_slug;
	protected static $setting_prefix;
	static $section_title;
	static $section_suffix;

	public function __construct() {

		self::$menu_slug = BPTPlugin::$menu_slug;
		self::$setting_prefix = '_bpt_';

		$this->register_settings();
		$this->register_sections();

		register_activation_hook(
			BptWordpress::plugin_root_dir() . 'brown-paper-tickets.php',
			array( $this, 'activate' )
		);

		register_deactivation_hook(
			BptWordpress::plugin_root_dir() . 'brown-paper-tickets.php',
			array( $this, 'deactivate' )
		);

		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_js' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_css' ) );
			$this->load_admin_ajax_actions();
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'load_public_js' ) );
		add_action( 'wp_enqueue_styles', array( $this, 'load_public_css' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'load_shared_js' ) );
		add_action( 'wp_enqueue_styles', array( $this, 'load_shared_css' ) );

		$this->load_public_ajax_actions();

		$this->custom_functions();

	}

	public function load_admin_js( $hook ) {

	}

	public function load_public_js( $hook ) {

	}

	public function load_admin_css( $hook ) {

	}

	public function load_public_css( $hook ) {

	}

	public function load_shared_js( $hook ) {

	}

	public function load_shared_css( $hook ) {

	}

	public function load_admin_ajax_actions() {

	}

	public function load_public_ajax_actions() {

	}

	public function register_sections() {

	}

	public function register_settings() {

	}

	public function display_settings_sections() {

	}

	public function set_default_setting_values() {

	}

	public function custom_functions() {

	}

	public function remove_setting_values() {

	}

	public function activate() {
		exit('activation!');
		$this->set_default_setting_values();
	}

	public function deactivate() {
		exit('deactivation!');
		$this->remove_setting_values();
	}

}