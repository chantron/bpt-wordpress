<?php

/**
 * Brown Paper Tickets Attendee List View
 *
 */
namespace BrownPaperTickets\Modules\AttendeeList;

use BrownPaperTickets\BPTPlugin;
use BrownPaperTickets\BptWordpress as Utils;

class View {

	public static function render()
	{
		$data = array(
			'events' => get_transient( '_bpt_attendee_list_events' ),
		);

		require_once( __DIR__ . '/assets/attendee-list.php' );
	}
}
