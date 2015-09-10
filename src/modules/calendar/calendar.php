<?php

/**
 * Brown Paper Tickets Account Settings Fields HTML
 *
 * Here lies the callbacks for the add_settings_fields() function.
 */
namespace BrownPaperTickets\Modules;

require_once( plugin_dir_path( __FILE__ ) . '../bpt-module-class.php' );
require_once( 'calendar-ajax.php' );
require_once( 'calendar-shortcode.php' );
require_once( 'calendar-widget.php' );
require_once( 'calendar-inputs.php' );

class Calendar extends Module {

	public static $module_name = '_calendar';

	public function init_actions() {
		add_action( 'widgets_init', array( $this, 'load_widgets' ) );
	}

	public function load_public_ajax_actions() {
		add_action( 'wp_ajax_nopriv_bpt_get_calendar_events', array( 'BrownPaperTickets\Modules\Calendar\Ajax', 'get_events' ) );
	}

	public function load_admin_ajax_actions() {
		add_action( 'wp_ajax_bpt_get_calendar_events', array( 'BrownPaperTickets\Modules\Calendar\Ajax', 'get_events' ) );
	}

	public function load_widgets() {
		register_widget( 'BrownPaperTickets\Modules\Calendar\Widget' );
	}

	public function load_shortcode() {
		add_shortcode( 'event-calendar', array( 'BrownPaperTickets\Modules\Calendar\Shortcode', 'calendar' ) );
		add_shortcode( 'event_calendar', array( 'BrownPaperTickets\Modules\Calendar\Shortcode', 'calendar' ) );
	}

	public function register_sections() {

		$section_title  = 'Calendar Settings';

		add_settings_section( $section_title, $section_title, null, self::$menu_slug . self::$module_name );

		self::$inputs = new Calendar\Inputs;

		add_settings_field(
			self::$setting_prefix . 'show_upcoming_events_calendar' . self::$module_name,
			'Display Upcoming Events in Calendar',
			array( self::$inputs, 'show_upcoming_events' ),
			self::$menu_slug . self::$module_name,
			$section_title
		);

		add_settings_field(
			self::$setting_prefix . 'date_text_calendar' . self::$module_name,
			'"Events on..." text for the calendar event view',
			array( self::$inputs, 'date_text' ),
			self::$menu_slug . self::$module_name,
			$section_title
		);

		add_settings_field(
			self::$setting_prefix . 'purchase_text_calendar' . self::$module_name,
			'Text to display for purchase links',
			array( self::$inputs, 'purchase_text' ),
			self::$menu_slug . self::$module_name,
			$section_title
		);
	}

	public function register_settings() {
		register_setting( self::$menu_slug . self::$module_name, self::$setting_prefix . 'show_upcoming_events_calendar' );
		register_setting( self::$menu_slug . self::$module_name, self::$setting_prefix . 'date_text_calendar' );
		register_setting( self::$menu_slug . self::$module_name, self::$setting_prefix . 'purchase_text_calendar' );
	}

	public function display_settings_sections() {

	}

	public function set_default_setting_values() {
		update_option( self::$setting_prefix . 'show_upcoming_events_calendar', 'false' );
		update_option( self::$setting_prefix . 'date_text_calendar', 'Events on ' );
		update_option( self::$setting_prefix . 'purchase_text_calendar', 'Buy Tickets' );
	}


	public function load_menus() {
		$page = add_submenu_page(
			self::$menu_slug,  //or 'options.php'
			'Brown Paper Tickets Calendar',
			'Calendar',
			'manage_options',
			self::$menu_slug . '_calendar',
			array( $this, 'render_menu' )
		);

		add_action( 'load-' . $page, array( $this, 'add_help' ) );
	}

	public function add_help() {
		$screen = get_current_screen();

		// $screen->add_help_tab( array(
		// 	'id' => 'bpt-apperance-event-list-help',
		// 	'title' => 'Event List Selectors',
		// 	'callback' => array( self::$inputs, 'event_list_help' ),
		// ) );
		//
		// $screen->add_help_tab( array(
		// 	'id' => 'bpt-apperance-calendar-help',
		// 	'title' => 'Calendar Selectors',
		// 	'callback' => array( self::$inputs, 'calendar_help' ),
		// ) );
	}

	public function load_admin_css($hook) {
		if ( 'bpt-settings_page_brown_paper_tickets_settings_calendar' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'bpt_admin_css' );
	}

	public function render_menu() {
		require_once( __DIR__ . '/calendar-menu.php' );
	}
}
