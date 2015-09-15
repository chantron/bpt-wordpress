<?php

use BrownPaperTickets\Modules\EventList;
use BrownPaperTickets\Modules\EventList\Ajax;

class EventListAjaxTest extends WP_Ajax_UnitTestCase {
	protected $event_list;
	protected $ajax;
	protected $json;

	public function setUp() {
		// The ajax functions use wp_send_json() which exits so we need to
		// buffer the response.


		$this->event_list = new EventList();
		$this->ajax = new Ajax();
		$this->json = file_get_contents( __DIR__ . '/data/events-1.json' );

		$this->event_list->load_admin_ajax_actions();
		$this->event_list->load_public_ajax_actions();
		$this->event_list->set_default_setting_values();

		update_option( '_bpt_dev_id', 'bnp74TYgNW' );
		update_option( '_bpt_client_id', 'chandler' );
		update_option( '_bpt_cache_time', '14' );
		update_option( '_bpt_cache_unit', 'days' );
		set_transient(
			'_bpt_event_list_events1',
			$this->json,
			1440
		);

	}

	public function test_get_cached_event() {
		$_GET = array(
			'postID' => 1,
			'nonce' => wp_create_nonce( 'bpt-event-list-nonce' ),
		);

		try {
			$this->_handleAjax( 'bpt_get_events' );
		} catch ( WPAjaxDieStopException $e ) {
		    // We expected this, do nothing.
		}

		$this->assertEquals( $this->json,  $this->_last_response );
	}
	//
	// public function test_get_client_events() {
	// 	update_option( '_bpt_cache_time', false );
	//
	// 	$get = array(
	// 		'postID' => 2,
	// 		'nonce' => wp_create_nonce( 'bpt-event-list-nonce' ),
	// 	);
	//
	// 	$this->ajax->get_events( $get );
	// 	$buffer = ob_get_clean();
	// 	$this->assertEquals( $this->json,  $buffer);
	// }
}
