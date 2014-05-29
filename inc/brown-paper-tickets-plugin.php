<?php
/**
 * Brown Paper Tickets
 */

namespace BrownPaperTickets;

const VERSION = '0.1';

const PLUGIN_SLUG = 'brown_paper_tickets';

require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-api.php');
use BrownPaperTickets\BPTFeed;


require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-settings-fields.php');
use BrownPaperTickets\BPTSettingsFields;

class BPTPlugin {

    protected $dev_id;
    protected $client_id;
    protected $settings_fields;
    protected static $menu_slug;

    protected static $instance = null;

    public function __construct() {

        $this->dev_id = get_option( '_bpt_dev_id' );
        $this->client_id = get_option( '_bpt_client_id' );
        $this->settings_fields = new BPTSettingsFields;
        self::$menu_slug = PLUGIN_SLUG.'_settings';


        if ( is_admin() ) {
            $this->load_admin();
        }

        $this->load_shared();
        $this->load_public();
    }

    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function get_plugin_slug() {
        return self::PLUGIN_SLUG;
    }

    public static function get_plugin_version() {
        return self::PLUGIN_VERSION;
    }

    public static function get_menu_slug() {
        return self::$menu_slug;
    }

    public static function activate() {
        /**
         * This will eventually call the setup wizard page.
         */
    }

    public static function deactivate() {

    }

    public function load_admin() {
        add_action( 'admin_menu', array( $this, 'create_bpt_settings' ) );

    }

    public function load_public() {
        add_shortcode( 'list-events', array( $this, 'event_list_shortcode' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'load_ajax_scripts' ) );
    }

    public function load_shared() {

        add_action( 'wp_ajax_bpt_feed_ajax', array( $this, 'bpt_feed_ajax' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'load_ajax_scripts' ) );
    }

    public function load_admin_scripts() {
        wp_enqueue_style( 'bpt_admin_css', plugins_url( '/assets/css/bpt-admin.css', dirname( __FILE__ ) ), false, VERSION );
        wp_enqueue_script( 'bpt_admin_js', plugins_url( '/assets/js/bpt-admin.js', dirname( __FILE__ ) ), array( 'jquery' ) ); 

    }

    public function load_ajax_scripts() {
        global $post;

        if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'list-events') ) {
            // Include Ractive Templates
            wp_enqueue_script( 'ractive_js', plugins_url( '/assets/js/ractive.js', dirname(__FILE__) ), array() );
            wp_enqueue_script( 'ractive_transitions_fade_js', plugins_url( '/assets/js/ractive-transitions-slide.js', dirname(__FILE__) ), array() );

            // Include Moment for date parsing.
            wp_enqueue_script( 'moment_with_langs_min', plugins_url( '/assets/js/moment-with-langs.min.js', dirname(__FILE__) ), array() );

            // load the event feed javascript
            wp_enqueue_script( 'event_feed_js', plugins_url( '/assets/js/event-feed.js', dirname(__FILE__) ), array( 'jquery', 'underscore' ) ); 
            wp_localize_script( 'event_feed_js', 'bptEventFeed', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'bptFeedNonce' => wp_create_nonce( 'bpt-event-feed-nonce' ))
            );
            wp_enqueue_style( 'bpt_admin_css', plugins_url( '/assets/css/bpt-event-list-shortcode.css', dirname( __FILE__ ) ), false, VERSION );
        }

        // wp_enqueue_script( 'account_feed_js', plugins_url( '/assets/js/account-feed.js', dirname(__FILE__) ), array( 'jquery') );
        // wp_localize_script( 'event_feed_js', 'bptAccountFeed', array(
        //     'ajaxurl' => admin_url( 'admin-ajax.php' ),
        //     'bptFeedNonce' => wp_create_nonce( 'account-feed-nonce' ) )
        // );
    }
    public function create_bpt_settings() {

        add_menu_page(
            'Brown Paper Tickets',
            'BPT Settings',
            'administrator',
            self::$menu_slug,
            array( $this, 'render_bpt_options_page')
        );

        $this->register_bpt_api_settings();
        $this->register_bpt_event_list_settings();
    }

    public function register_bpt_event_list_settings() {
        $setting_prefix = '_bpt_';
        $section_suffix = '_event';
        $section_title = 'Event Display Settings';
        $date_section_title = 'Date Display Settings';
        $price_section_title = 'Price Display Settings';

        // Event Settings
        register_setting( self::$menu_slug, $setting_prefix . 'show_full_description' );

        // Date Settings
        register_setting( self::$menu_slug, $setting_prefix . 'show_dates' );
        register_setting( self::$menu_slug, $setting_prefix . 'date_format' );
        register_setting( self::$menu_slug, $setting_prefix . 'time_format' );
        // custom_date_field is registered but it doesn't have a settings filed added.
        // That is added manually in the settings-fields.
        register_setting( self::$menu_slug, $setting_prefix . 'custom_date_format' );
        register_setting( self::$menu_slug, $setting_prefix . 'custom_time_format' );
        register_setting( self::$menu_slug, $setting_prefix . 'show_sold_out_dates' );
        register_setting( self::$menu_slug, $setting_prefix . 'show_past_dates' );
        register_setting( self::$menu_slug, $setting_prefix . 'show_end_time' );

        // Price Settings
        register_setting( self::$menu_slug, $setting_prefix . 'show_prices' );
        register_setting( self::$menu_slug, $setting_prefix . 'shipping_methods' );
        register_setting( self::$menu_slug, $setting_prefix . 'shipping_countries' );
        register_setting( self::$menu_slug, $setting_prefix . 'currency' );
        register_setting( self::$menu_slug, $setting_prefix . 'price_sort' );
        register_setting( self::$menu_slug, $setting_prefix . 'show_sold_out_prices' );


        add_settings_section( $section_title, $section_title, array( $this, 'render_bpt_options_page' ), self::$menu_slug . $section_suffix );
        add_settings_section( $date_section_title, $date_section_title, array( $this, 'render_bpt_options_page' ), self::$menu_slug . $section_suffix );
        add_settings_section( $price_section_title, $price_section_title, array( $this, 'render_bpt_options_page' ), self::$menu_slug . $section_suffix );


        // Add the settings fields.
        // Event Fields
        add_settings_field( $setting_prefix . 'show_full_description', 'Display Full Description by Default', array( $this->settings_fields, 'get_show_full_description_input' ), self::$menu_slug . $section_suffix, $section_title );
        
        // Date Fields
        add_settings_field( $setting_prefix . 'show_dates', 'Display Dates', array ( $this->settings_fields, 'get_show_dates_input' ), self::$menu_slug . $section_suffix, $date_section_title );
        add_settings_field( $setting_prefix . 'show_past_dates', 'Display Past Dates', array ( $this->settings_fields, 'get_show_past_dates_input' ), self::$menu_slug . $section_suffix, $date_section_title );
        add_settings_field( $setting_prefix . 'show_sold_out_dates', 'Display Sold Out Dates', array ( $this->settings_fields, 'get_show_sold_out_dates_input' ), self::$menu_slug . $section_suffix, $date_section_title );
        add_settings_field( $setting_prefix . 'date_format', 'Date Format', array( $this->settings_fields, 'get_date_format_input' ), self::$menu_slug . $section_suffix, $date_section_title );
        add_settings_field( $setting_prefix . 'time_format', 'Time Format', array( $this->settings_fields, 'get_time_format_input' ), self::$menu_slug . $section_suffix, $date_section_title );

        // Price Fields
        add_settings_field( $setting_prefix . 'show_prices', 'Display Prices', array( $this->settings_fields, 'get_show_prices_input' ), self::$menu_slug . $section_suffix, $price_section_title );
        add_settings_field( $setting_prefix . 'show_sold_out_prices', 'Display Sold Out Prices', array ( $this->settings_fields, 'get_show_sold_out_prices_input' ), self::$menu_slug . $section_suffix, $price_section_title );
        add_settings_field( $setting_prefix . 'show_end_time', 'Display Event End Time', array( $this->settings_fields, 'get_show_end_time_input' ), self::$menu_slug . $section_suffix, $price_section_title );
        add_settings_field( $setting_prefix . 'shipping_methods', 'Shipping Methods', array( $this->settings_fields, 'get_shipping_methods_input' ), self::$menu_slug . $section_suffix, $price_section_title );
        add_settings_field( $setting_prefix . 'shipping_countries', 'Default Shipping Country', array( $this->settings_fields, 'get_shipping_countries_input' ), self::$menu_slug . $section_suffix, $price_section_title );
        add_settings_field( $setting_prefix . 'currency', 'Currency', array( $this->settings_fields, 'get_currency_input' ), self::$menu_slug . $section_suffix, $price_section_title );
        add_settings_field( $setting_prefix . 'price_sort', 'Price Sort', array( $this->settings_fields, 'get_price_sort_input' ), self::$menu_slug . $section_suffix, $price_section_title );
    }

    /**
     * Register the API Credential Settings Fields
     *
     * Set the $section title variable to what you want the 
     */
    public function register_bpt_api_settings() {
        $setting_prefix = '_bpt_';
        $section_suffix = '_api';
        $section_title = 'API Credentials';

        register_setting( self::$menu_slug, $setting_prefix . 'dev_id');
        register_setting( self::$menu_slug, $setting_prefix . 'client_id');

        add_settings_section( $section_title, $section_title, array( $this, 'render_bpt_options_page' ), self::$menu_slug . $section_suffix );

        add_settings_field( $setting_prefix . 'dev_id', 'Developer ID', array( $this->settings_fields, 'get_developer_id_input' ), self::$menu_slug . $section_suffix, $section_title );
        add_settings_field( $setting_prefix . 'client_id', 'Client ID', array( $this->settings_fields, 'get_client_id_input' ), self::$menu_slug . $section_suffix, $section_title );
    }

    public function event_list_shortcode( $atts ) {
       require_once( plugin_dir_path( __FILE__ ) . '../public/event-list-shortcode.php' );
    }

    public function render_bpt_options_page() {
        require_once( plugin_dir_path( __FILE__ ) . '../admin-views/bpt-settings.php' );
    }

    /**
     * AJAX Stuff
     */
    public function bpt_feed_ajax() {
        header('Content-type: application/json');
        $nonce = $_POST['bptFeedNonce'];

        if ( ! wp_verify_nonce( $nonce, 'bpt-event-feed-nonce' ) ) {
            exit(
                json_encode(
                    array(
                        'error' => 'Could not obtain events.'
                    )
                )
            );
        }

        if ( $_POST['bptData'] === 'events' ) {

            $events = new BPTFeed();

            $response = $events->get_json_events();

            exit($response);

        }

        if ( $_POST['bptData'] === 'account' ) {

            $account = new BPTFeed;

            $response = $account->get_json_account();

            exit($response);

        }


    }

}