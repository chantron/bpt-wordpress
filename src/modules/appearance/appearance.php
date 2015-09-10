<?php

namespace BrownPaperTickets\Modules;
require_once( plugin_dir_path( __FILE__ ).'../bpt-module-class.php' );
require_once( plugin_dir_path( __FILE__ ).'/appearance-inputs.php' );

class Appearance extends Module {

	public static $module_name = '_appearance';

	public function register_settings() {
		register_setting( self::$menu_slug . self::$module_name, self::$setting_prefix . 'event_list_style' );
		register_setting( self::$menu_slug . self::$module_name, self::$setting_prefix . 'calendar_style' );
	}

	public function register_sections() {

		$inputs = new Appearance\Inputs;

		add_settings_section(
			'Appearance',
			null,
			array( $inputs, 'section' ),
			self::$menu_slug . '_appearance'
		);

		add_settings_field(
			self::$setting_prefix . 'event_list_style' . self::$module_name, // The ID of the input.
			'Event List', // The title of the field.
			array( $inputs, 'event_list' ), // Event HTML callback
			self::$menu_slug . self::$module_name, // The settings page.
			'Appearance' // The section that the field will be rendered in.
		);

		add_settings_field(
			self::$setting_prefix . 'calendar_style' . self::$module_name,
			'Calendar',
			array( $inputs, 'calendar' ),
			self::$menu_slug . self::$module_name, // The settings page.
			'Appearance' // The section that the field will be rendered in.
		);
	}

	public function set_default_setting_values() {
		$event_list_options = get_option(
			self::$setting_prefix . 'event_list_style'
		);

		$calendar_options = self::$menu_slug . self::$setting_prefix . 'calendar_style';

		if ( ! $event_list_options ) {
			$event_list_options = array(
				'use_style' => false,
				'custom_css' => '',
			);

			update_option( self::$menu_slug . self::$setting_prefix . 'event_list_style', $event_list_options );
		}

		if ( ! $calendar_options ) {
			$calendar_options = array(
				'use_style' => false,
				'custom_css' => '',
			);

			update_option( self::$menu_slug . self::$setting_prefix . 'calendar_style', $calendar_options );
		}
	}

	public function remove_setting_values() {
		delete_option( self::$menu_slug . self::$setting_prefix . 'calendar_style' );
		delete_option( self::$menu_slug . self::$setting_prefix . 'event_list_style' );
	}

	public function load_menus() {
		$page = add_submenu_page(
			self::$menu_slug,  //or 'options.php'
			'Brown Paper Tickets Appearance',
			'Appearance',
			'manage_options',
			self::$menu_slug . '_appearance',
			array( $this, 'render_menu' )
		);

		add_action( 'load-' . $page, array( $this, 'add_help' ) );
	}

	public function add_help() {
		$screen = get_current_screen();

		$screen->add_help_tab( array(
			'id' => 'bpt-appearance-event-list-help',
			'title' => 'Event List Selectors',
			'callback' => array( 'BrownPaperTickets\Modules\Appearance\Inputs', 'event_list_help' ),
		) );

		$screen->add_help_tab( array(
			'id' => 'bpt-appearance-calendar-help',
			'title' => 'Calendar Selectors',
			'callback' => array( 'BrownPaperTickets\Modules\Appearance\Inputs', 'calendar_help' ),
		) );
	}

	public function render_menu() {
		require_once( __DIR__ . '/appearance-menu.php' );
	}
}
