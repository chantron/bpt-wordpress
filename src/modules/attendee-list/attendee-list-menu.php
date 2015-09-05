<?php

/**
 * Brown Paper Tickets Attendee List View
 *
 */
namespace BrownPaperTickets\Modules\AttendeeList;

use BrownPaperTickets\BPTPlugin;
use BrownPaperTickets\BptWordpress as Utils;

class Menu {

	public static function render()
	{
		require_once( __DIR__ . '/assets/attendee-list.php' );
	}
}
