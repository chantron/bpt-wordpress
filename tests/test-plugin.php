<?php

use BrownPaperTickets\BPTPlugin;

class PluginTest extends WP_UnitTestCase {

	public function setUp() {
		BPTPlugin::activate();
		update_option( '_bpt_dev_id', 'abcde12345' );
		update_option( '_bpt_client_id', 'tester' );
	}

	function test_plugin_data()
	{
		$data = get_plugin_data( dirname( __DIR__ ) . '/brown-paper-tickets.php', true );
		$this->assertEquals(BPTPlugin::get_plugin_version(), $data['Version']);
	}

	function test_get_instance() {
		$this->assertInstanceOf( '\BrownPaperTickets\BPTPlugin', BPTPlugin::get_instance() );
	}

	function test_get_plugin_version() {
		$this->assertEquals( BPTPlugin::get_plugin_version(), '0.7.0' );
	}

	function test_register_scripts() {
		BPTPlugin::get_instance()->load_admin_scripts(null);
		$this->assertArrayHasKey( 'bpt_admin_css', wp_styles()->registered );
		$this->assertArrayHasKey( 'bpt_admin_js', wp_scripts()->registered );
	}

	function test_get_plugin_slug() {
		$this->assertEquals( BPTPlugin::get_plugin_slug(), 'brown_paper_tickets' );
	}

	function test_get_modules() {
		$modules = BPTPlugin::get_modules();
		$this->assertCount(10, $modules);
		$this->assertArrayHasKey('dashboard', $modules);
		$this->assertArrayHasKey('account', $modules);
		$this->assertArrayHasKey('appearance', $modules);
		$this->assertArrayHasKey('cache', $modules);
		$this->assertArrayHasKey('calendar', $modules);
		$this->assertArrayHasKey('event_list', $modules);
		$this->assertArrayHasKey('purchase', $modules);
		$this->assertArrayHasKey('attendee_list', $modules);
		$this->assertArrayHasKey('help', $modules);
		$this->assertArrayHasKey('setup_wizard', $modules);

		$this->assertInstanceOf('BrownPaperTickets\Modules\Dashboard', $modules['dashboard']);
		$this->assertInstanceOf('BrownPaperTickets\Modules\Account', $modules['account']);
		$this->assertInstanceOf('BrownPaperTickets\Modules\Appearance', $modules['appearance']);
		$this->assertInstanceOf('BrownPaperTickets\Modules\Cache', $modules['cache']);
		$this->assertInstanceOf('BrownPaperTickets\Modules\Calendar', $modules['calendar']);
		$this->assertInstanceOf('BrownPaperTickets\Modules\EventList', $modules['event_list']);
		$this->assertInstanceOf('BrownPaperTickets\Modules\Purchase', $modules['purchase']);
		$this->assertInstanceOf('BrownPaperTickets\Modules\AttendeeList', $modules['attendee_list']);
		$this->assertInstanceOf('BrownPaperTickets\Modules\Help', $modules['help']);
		$this->assertInstanceOf('BrownPaperTickets\Modules\SetupWizard', $modules['setup_wizard']);
	}
}
