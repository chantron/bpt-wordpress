<?php

use BrownPaperTickets\Modules\EventList;

class EventListTest extends WP_UnitTestCase {
	protected $event_list;

	public function setUp() {
		$this->event_list = new EventList;
		update_option( '_bpt_dev_id', 'abcde12345' );
		update_option( '_bpt_client_id', 'tester' );
	}

	public function test_module_name()
	{
		$this->assertEquals(EventList::$module_name, '_event');
	}

	public function test_load_shortcode()
	{
		$this->event_list->load_shortcode();
		global $shortcode_tags;
		$this->assertArrayHasKey( 'list-event', $shortcode_tags );
		$this->assertArrayHasKey( 'list_event', $shortcode_tags );
		$this->assertArrayHasKey( 'list-events', $shortcode_tags );
		$this->assertArrayHasKey( 'list_events', $shortcode_tags );

		$this->assertEquals( $shortcode_tags['list_event'][0], 'BrownPaperTickets\Modules\EventList\Shortcode' );
		$this->assertEquals( $shortcode_tags['list-event'][0], 'BrownPaperTickets\Modules\EventList\Shortcode' );
		$this->assertEquals( $shortcode_tags['list-events'][0], 'BrownPaperTickets\Modules\EventList\Shortcode' );
		$this->assertEquals( $shortcode_tags['list_events'][0], 'BrownPaperTickets\Modules\EventList\Shortcode' );


		$this->assertEquals( $shortcode_tags['list_event'][1], 'list_event_shortcode' );
		$this->assertEquals( $shortcode_tags['list-event'][1], 'list_event_shortcode' );
		$this->assertEquals( $shortcode_tags['list-events'][1], 'list_event_shortcode' );
		$this->assertEquals( $shortcode_tags['list_events'][1], 'list_event_shortcode' );
	}

	public function test_register_settings()
	{
		$this->event_list->register_settings();
		global $new_whitelist_options;

		$this->assertArrayHasKey( 'brown_paper_tickets_settings_event', $new_whitelist_options );
		$settings = $new_whitelist_options['brown_paper_tickets_settings_event'];

		$this->assertContains( '_bpt_show_location_after_description', $settings );
		$this->assertContains( '_bpt_show_full_description', $settings );
		$this->assertContains( '_bpt_sort_events', $settings );
		$this->assertContains( '_bpt_show_dates', $settings );
		$this->assertContains( '_bpt_date_format', $settings );
		$this->assertContains( '_bpt_time_format', $settings );
		$this->assertContains( '_bpt_custom_date_format', $settings );
		$this->assertContains( '_bpt_show_sold_out_dates', $settings );
		$this->assertContains( '_bpt_show_past_dates', $settings );
		$this->assertContains( '_bpt_show_end_time', $settings );
		$this->assertContains( '_bpt_show_prices', $settings );
		$this->assertContains( '_bpt_shipping_methods', $settings );
		$this->assertContains( '_bpt_shipping_countries', $settings );
		$this->assertContains( '_bpt_price_sort', $settings );
		$this->assertContains( '_bpt_show_sold_out_prices', $settings );
		$this->assertContains( '_bpt_include_service_fee', $settings );
		$this->assertContains( '_bpt_hidden_prices', $settings );
		$this->assertContains( '_bpt_price_intervals', $settings );
		$this->assertContains( '_bpt_price_include_fee', $settings );
	}

	public function test_resgister_sections()
	{
		global $wp_settings_sections;
		global $wp_settings_fields;

		$this->event_list->register_sections();
		$this->assertArrayHasKey( 'brown_paper_tickets_settings_event', $wp_settings_sections );

		$sections = $wp_settings_sections['brown_paper_tickets_settings_event'];
		$this->assertArrayHasKey( 'Event Display Settings', $sections );
		$this->assertArrayHasKey( 'Date Display Settings', $sections );
		$this->assertArrayHasKey( 'Price Display Settings', $sections );

		$settings = $wp_settings_fields['brown_paper_tickets_settings_event'];
		$this->assertArrayHasKey( 'Event Display Settings', $settings );
		$this->assertArrayHasKey( 'Date Display Settings', $settings );
		$this->assertArrayHasKey( 'Price Display Settings', $settings );
	}

	public function test_set_default_settings_values()
	{
		$this->event_list->set_default_setting_values();

		$this->assertEquals( 'false', get_option( '_bpt_show_full_description' ) );
		$this->assertEquals( 'false', get_option( '_bpt_show_location_after_description' ) );
		$this->assertEquals( 'true', get_option( '_bpt_show_dates' ) );
		$this->assertEquals( 'MMMM Do, YYYY', get_option( '_bpt_date_format' ) );
		$this->assertEquals( 'hh:mm A', get_option( '_bpt_time_format' ) );
		$this->assertEquals( 'false', get_option( '_bpt_show_sold_out_dates' ) );
		$this->assertEquals( 'false', get_option( '_bpt_show_past_dates' ) );
		$this->assertEquals( 'true', get_option( '_bpt_show_end_time' ) );
		$this->assertEquals( 'true', get_option( '_bpt_show_prices' ) );
		$this->assertEquals( array( 'print_at_home', 'will_call' ), get_option( '_bpt_shipping_methods' ) );
		$this->assertEquals( 'United States', get_option( '_bpt_shipping_countries' ) );
		$this->assertEquals( 'usd', get_option( '_bpt_currency' ) );
		$this->assertEquals( 'value_asc', get_option( '_bpt_price_sort' ) );
		$this->assertEquals( 'false', get_option( '_bpt_show_sold_out_prices' ) );
		$this->assertEquals( 'false', get_option( '_bpt_include_service_fee' ) );
		$this->assertEquals( array(), get_option( '_bpt_hidden_prices' ) );
		$this->assertEquals( array(), get_option( '_bpt_price_max_quantity' ) );
		$this->assertEquals( array(), get_option( '_bpt_price_intervals' ) );
		$this->assertEquals( array(), get_option( '_bpt_price_include_fee' ) );
	}

	public function test_load_admin_ajax_actions()
	{
		global $wp_filter;

		$this->event_list->load_admin_ajax_actions();

		$this->assertArrayHasKey('wp_ajax_bpt_set_price_max_quantity', $wp_filter);
		$this->assertArrayHasKey('wp_ajax_bpt_hide_prices', $wp_filter);
		$this->assertArrayHasKey('wp_ajax_bpt_unhide_prices', $wp_filter);
		$this->assertArrayHasKey('wp_ajax_bpt_get_events', $wp_filter);
		$this->assertArrayHasKey('wp_ajax_bpt_set_price_intervals', $wp_filter);
		$this->assertArrayHasKey('wp_ajax_bpt_set_price_include_fee', $wp_filter);

		$this->assertArrayHasKey(
			'BrownPaperTickets\Modules\EventList\Ajax::set_price_max_quantity',
			$wp_filter['wp_ajax_bpt_set_price_max_quantity'][10]
		);

		$this->assertArrayHasKey(
			'BrownPaperTickets\Modules\EventList\Ajax::hide_prices',
			$wp_filter['wp_ajax_bpt_hide_prices'][10]
		);

		$this->assertArrayHasKey(
			'BrownPaperTickets\Modules\EventList\Ajax::unhide_prices',
			$wp_filter['wp_ajax_bpt_unhide_prices'][10]
		);

		$this->assertArrayHasKey(
			'BrownPaperTickets\Modules\EventList\Ajax::get_events',
			$wp_filter['wp_ajax_bpt_get_events'][10]
		);

		$this->assertArrayHasKey(
			'BrownPaperTickets\Modules\EventList\Ajax::set_price_intervals',
			$wp_filter['wp_ajax_bpt_set_price_intervals'][10]
		);

		$this->assertArrayHasKey(
			'BrownPaperTickets\Modules\EventList\Ajax::set_price_include_fee',
			$wp_filter['wp_ajax_bpt_set_price_include_fee'][10]
		);
	}

	public function test_load_public_ajax_actions()
	{
		global $wp_filter;

		$this->event_list->load_public_ajax_actions();
		$this->assertArrayHasKey( 'wp_ajax_nopriv_bpt_get_events', $wp_filter );
		$this->assertArrayHasKey(
			'BrownPaperTickets\Modules\EventList\Ajax::get_events',
			$wp_filter['wp_ajax_nopriv_bpt_get_events'][10]
		);
	}

	public function test_load_menus()
	{

	}
}
