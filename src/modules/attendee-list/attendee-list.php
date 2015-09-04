<?php
/**
 * This handles all the actions/hooks and other various WP related functions for
 * the AttendeeList.
 *
 * @package brown-paper-tickets
 */

namespace BrownPaperTickets\Modules;

require_once( plugin_dir_path( __FILE__ ) . '../bpt-module-class.php' );
require_once( plugin_dir_path( __FILE__ ) . 'attendee-list-view.php' );
require_once( plugin_dir_path( __FILE__ ) . 'attendee-list-ajax.php' );

/**
 * This class handles all of the Attendee List stuff.
 */
class AttendeeList extends \BrownPaperTickets\Modules\Module {

	/**
	 * Give the section a unique suffixe.
	 * @var string
	 */
	static $section_suffix = '_attendee_list';

	/**
	 * This module has just one section. Give it a name.
	 * @var string
	 */
	static $section_title = 'Attendees';

	/**
	 * Register the sections.
	 */
	public function register_sections() {
		add_settings_section(
			self::$section_title,
			self::$section_title,
			array( 'BrownPaperTickets\Modules\AttendeeList\View', 'render' ),
			self::$menu_slug . self::$section_suffix
		);
	}

	/**
	 * Add all the actions for the admin side.
	 */
	public function load_admin_ajax_actions() {
		add_action(
			'wp_ajax_bpt_attendee_list_get_events',
			array( 'BrownPaperTickets\Modules\AttendeeList\Ajax', 'get_events' )
		);

		add_action(
			'wp_ajax_bpt_attendee_list_get_attendees',
			array( 'BrownPaperTickets\Modules\AttendeeList\Ajax', 'get_attendees' )
		);
	}

	public function load_admin_js($hook) {
		$localized_variables = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'bpt-attendee-list-nonce' ),
			'dateFormat' => get_option( self::$setting_prefix . 'date_format' ),
			'timeFormat' => get_option( self::$setting_prefix . 'time_format' ),
		);

		wp_register_script(
			'attendee_list_js',
			plugins_url( '/assets/js/attendee-list.js', __FILE__ ),
			array( 'jquery', 'moment_with_langs_min' ),
			null,
			true
		);

		wp_localize_script(
			'attendee_list_js',
			'bptAttendeeList',
			$localized_variables
		);

		wp_enqueue_script( 'attendee_list_js' );
	}
}
