<?php
/**
 * DTRT Anchor Links
 *
 * @package     WPDTRT_Anchorlinks
 * @author      Dan Smith
 * @copyright   2018 Do The Right Thing
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:  DTRT Anchor Links
 * Plugin URI:   https://github.com/dotherightthing/wpdtrt-anchorlinks
 * Description:  Anchor links plugin.
 * Version:      0.4.10
 * Author:       Dan Smith
 * Author URI:   https://profiles.wordpress.org/dotherightthingnz
 * License:      GPLv2 or later
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  wpdtrt-anchorlinks
 * Domain Path:  /languages
 */

/**
 * Constants
 * WordPress makes use of the following constants when determining the path to the content and plugin directories.
 * These should not be used directly by plugins or themes, but are listed here for completeness.
 * WP_CONTENT_DIR  // no trailing slash, full paths only
 * WP_CONTENT_URL  // full url
 * WP_PLUGIN_DIR  // full path, no trailing slash
 * WP_PLUGIN_URL  // full url, no trailing slash
 *
 * WordPress provides several functions for easily determining where a given file or directory lives.
 * Always use these functions in your plugins instead of hard-coding references to the wp-content directory
 * or using the WordPress internal constants.
 * plugins_url()
 * plugin_dir_url()
 * plugin_dir_path()
 * plugin_basename()
 *
 * @see https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Constants
 * @see https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Plugins
 */

if ( ! defined( 'WPDTRT_ANCHORLINKS_VERSION' ) ) {
	/**
	 * Plugin version.
	 *
	 * WP provides get_plugin_data(), but it only works within WP Admin,
	 * so we define a constant instead.
	 *
	 * @see $plugin_data = get_plugin_data( __FILE__ ); $plugin_version = $plugin_data['Version'];
	 * @see https://wordpress.stackexchange.com/questions/18268/i-want-to-get-a-plugin-version-number-dynamically
	 */
	define( 'WPDTRT_ANCHORLINKS_VERSION', '0.4.10' );
}

if ( ! defined( 'WPDTRT_ANCHORLINKS_PATH' ) ) {
	/**
	 * Plugin directory filesystem path.
	 *
	 * @param string $file
	 * @return The filesystem directory path (with trailing slash)
	 * @see https://developer.wordpress.org/reference/functions/plugin_dir_path/
	 * @see https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
	 */
	define( 'WPDTRT_ANCHORLINKS_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'WPDTRT_ANCHORLINKS_URL' ) ) {
	/**
	 * Plugin directory URL path.
	 *
	 * @param string $file
	 * @return The URL (with trailing slash)
	 * @see https://codex.wordpress.org/Function_Reference/plugin_dir_url
	 * @see https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
	 */
	define( 'WPDTRT_ANCHORLINKS_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * ===== Dependencies =====
 */

/**
 * Determine the correct path to the PSR-4 autoloader.
 *
 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/issues/51
 */
if ( ! defined( 'WPDTRT_PLUGIN_CHILD' ) ) {
	define( 'WPDTRT_PLUGIN_CHILD', true );
}

/**
 * Determine the correct path to the PSR-4 autoloader.
 *
 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/issues/104
 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Options:-Adding-WordPress-plugin-dependencies
 */
if ( defined( 'WPDTRT_ANCHORLINKS_TEST_DEPENDENCY' ) ) {
	$project_root_path = realpath( __DIR__ . '/../../..' ) . '/';
} else {
	$project_root_path = '';
}

require_once $project_root_path . 'vendor/autoload.php';

if ( is_admin() ) {
	// This replaces the TGMPA autoloader
	// @see dotherightthing/generator-wpdtrt-plugin-boilerplate#77
	// @see dotherightthing/wpdtrt-plugin-boilerplate#136.
	require_once $project_root_path . 'vendor/tgmpa/tgm-plugin-activation/class-tgm-plugin-activation.php';
}

// sub classes, not loaded via PSR-4.
// remove the includes you don't need, edit the files you do need.
require_once WPDTRT_ANCHORLINKS_PATH . 'src/class-wpdtrt-anchorlinks-plugin.php';
require_once WPDTRT_ANCHORLINKS_PATH . 'src/class-wpdtrt-anchorlinks-shortcode.php';

// log & trace helpers.
global $debug;
$debug = new DoTheRightThing\WPDebug\Debug();

/**
 * ===== WordPress Integration =====
 *
 * Comment out the actions you don't need.
 *
 * Notes:
 *  Default priority is 10. A higher priority runs later.
 *  register_activation_hook() is run before any of the provided hooks.
 *
 * @see https://developer.wordpress.org/plugins/hooks/actions/#priority
 * @see https://codex.wordpress.org/Function_Reference/register_activation_hook.
 */
register_activation_hook( dirname( __FILE__ ), 'wpdtrt_anchorlinks_helper_activate' );

add_action( 'init', 'wpdtrt_anchorlinks_plugin_init', 0 );
add_action( 'init', 'wpdtrt_anchorlinks_shortcode_init', 100 );
add_action( 'init', 'wpdtrt_anchorlinks_shortcode_heading_init', 100 );

register_deactivation_hook( dirname( __FILE__ ), 'wpdtrt_anchorlinks_helper_deactivate' );

/**
 * ===== Plugin config =====
 */

/**
 * Register functions to be run when the plugin is activated.
 *
 * @see https://codex.wordpress.org/Function_Reference/register_activation_hook
 * @todo https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/issues/128
 * @see See also Plugin::helper_flush_rewrite_rules()
 */
function wpdtrt_anchorlinks_helper_activate() {
	flush_rewrite_rules();
}

/**
 * Register functions to be run when the plugin is deactivated.
 * (WordPress 2.0+)
 *
 * @see https://codex.wordpress.org/Function_Reference/register_deactivation_hook
 * @todo https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/issues/128
 * @see See also Plugin::helper_flush_rewrite_rules()
 */
function wpdtrt_anchorlinks_helper_deactivate() {
	flush_rewrite_rules();
}

/**
 * Plugin initialisaton
 *
 * We call init before widget_init so that the plugin object properties are available to it.
 * If widget_init is not working when called via init with priority 1, try changing the priority of init to 0.
 * init: Typically used by plugins to initialize. The current user is already authenticated by this time.
 * widgets_init: Used to register sidebars. Fired at 'init' priority 1 (and so before 'init' actions with priority ≥ 1!)
 *
 * @see https://wp-mix.com/wordpress-widget_init-not-working/
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference
 * @todo Add a constructor function to WPDTRT_Anchorlinks_Plugin, to explain the options array
 */
function wpdtrt_anchorlinks_plugin_init() {
	// pass object reference between classes via global
	// because the object does not exist until the WordPress init action has fired.
	global $wpdtrt_anchorlinks_plugin;

	/**
	 * Global options
	 *
	 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Options:-Adding-global-options Options: Adding global options
	 */
	$plugin_options = array(
		'heading_level' => array(
			'type'    => 'select',
			'label'   => __( 'Heading level', 'wpdtrt-anchorlinks' ),
			'options' => array(
				'h1' => array(
					'text' => 'H1',
				),
				'h2' => array(
					'text' => 'H2',
				),
				'h3' => array(
					'text' => 'H3',
				),
				'h4' => array(
					'text' => 'H4',
				),
				'h5' => array(
					'text' => 'H5',
				),
				'h6' => array(
					'text' => 'H6',
				),
			),
			'tip'     => __( 'Heading level to convert into anchors', 'wpdtrt-anchorlinks' ),
			'default' => 'h2',
		),
	);

	/**
	 * Shortcode or Widget options
	 *
	 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Options:-Adding-shortcode-or-widget-options Options: Adding shortcode or widget options
	 */
	$instance_options = array(
		'post_id'                         => array(
			'type'    => 'number',
			'size'    => 3,
			'label'   => __( 'Post ID', 'wpdtrt-anchorlinks' ),
			'tip'     => __( 'Used in unit tests', 'wpdtrt-anchorlinks' ),
			'default' => 1,
		),
		'title_text'                      => array(
			'type'    => 'text',
			'size'    => 30,
			'label'   => __( 'Anchor list title text', 'wpdtrt-anchorlinks' ),
			'tip'     => __( 'e.g. Outline', 'wpdtrt-anchorlinks' ),
			'default' => __( 'Outline', 'wpdtrt-anchorlinks' ),
		),
		'exclude_widgets_on_pages'        => array(
			'type'  => 'text',
			'size'  => 30,
			'label' => __( 'Exclude widgets from anchor links list on these pages', 'wpdtrt-anchorlinks' ),
			'tip'   => __( 'Comma separated list of page IDs', 'wpdtrt-anchorlinks' ),
		),
		'additional_html'                 => array(
			'type'  => 'text',
			'size'  => 30,
			'label' => __( 'Additional HTML', 'wpdtrt-anchorlinks' ),
			'tip'   => __( 'Output an HTML string below the list', 'wpdtrt-anchorlinks' ),
		),
		'additional_from_sidebar_id_1'    => array(
			'type'  => 'text',
			'size'  => 100,
			'label' => __( 'Sidebar 1 ID', 'wpdtrt-anchorlinks' ),
			'tip'   => __( 'ID of a sidebar outside of the_content, which contains widgets with titles', 'wpdtrt-anchorlinks' ),
		),
		'additional_from_sidebar_order_1' => array(
			'type'    => 'number',
			'size'    => 3,
			'label'   => __( 'Sidebar 1 order', 'wpdtrt-anchorlinks' ),
			'tip'     => __( 'DOM order of sidebar relative to the_content (0)', 'wpdtrt-anchorlinks' ),
			'default' => -1,
		),
		'extra_header_class'              => array(
			'type'  => 'string',
			'size'  => 100,
			'label' => __( 'Extra header class', 'wpdtrt-anchorlinks' ),
		),
	);

	/**
	 *  UI Messages
	 */
	$ui_messages = array(
		'demo_data_description'       => __( 'This demo was generated from the following data', 'wpdtrt-anchorlinks' ),
		'demo_data_displayed_length'  => __( '# results displayed', 'wpdtrt-anchorlinks' ),
		'demo_data_length'            => __( '# results', 'wpdtrt-anchorlinks' ),
		'demo_data_title'             => __( 'Demo data', 'wpdtrt-anchorlinks' ),
		'demo_date_last_updated'      => __( 'Data last updated', 'wpdtrt-anchorlinks' ),
		'demo_sample_title'           => __( 'Demo sample', 'wpdtrt-anchorlinks' ),
		'demo_shortcode_title'        => __( 'Demo shortcode', 'wpdtrt-anchorlinks' ),
		'insufficient_permissions'    => __( 'Sorry, you do not have sufficient permissions to access this page.', 'wpdtrt-anchorlinks' ),
		'no_options_form_description' => __( 'There aren\'t currently any options.', 'wpdtrt-anchorlinks' ),
		'noscript_warning'            => __( 'Please enable JavaScript', 'wpdtrt-anchorlinks' ),
		'options_form_description'    => __( 'Please enter your preferences.', 'wpdtrt-anchorlinks' ),
		'options_form_submit'         => __( 'Save Changes', 'wpdtrt-anchorlinks' ),
		'options_form_title'          => __( 'General Settings', 'wpdtrt-anchorlinks' ),
		'loading'                     => __( 'Loading latest data...', 'wpdtrt-anchorlinks' ),
		'success'                     => __( 'settings successfully updated', 'wpdtrt-anchorlinks' ),
	);

	/**
	 * Array: demo_shortcode_params
	 *
	 * Demo shortcode.
	 *
	 * See:
	 * - <Settings page - Adding a demo shortcode: https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Settings-page:-Adding-a-demo-shortcode>
	 */
	$demo_shortcode_params = array();

	/**
	 * Plugin configuration
	 */
	$wpdtrt_anchorlinks_plugin = new WPDTRT_Anchorlinks_Plugin(
		array(
			'path'                  => WPDTRT_ANCHORLINKS_PATH,
			'url'                   => WPDTRT_ANCHORLINKS_URL,
			'version'               => WPDTRT_ANCHORLINKS_VERSION,
			'prefix'                => 'wpdtrt_anchorlinks',
			'slug'                  => 'wpdtrt-anchorlinks',
			'menu_title'            => __( 'Anchor Links', 'wpdtrt-anchorlinks' ),
			'settings_title'        => __( 'Settings', 'wpdtrt-anchorlinks' ),
			'developer_prefix'      => 'DTRT',
			'messages'              => $ui_messages,
			'plugin_options'        => $plugin_options,
			'instance_options'      => $instance_options,
			'demo_shortcode_params' => $demo_shortcode_params,
		)
	);
}

/**
 * ===== Shortcode config =====
 */

/**
 * Register Shortcode
 */
function wpdtrt_anchorlinks_shortcode_init() {

	global $wpdtrt_anchorlinks_plugin;

	$wpdtrt_anchorlinks_shortcode = new WPDTRT_Anchorlinks_Shortcode(
		array(
			'name'                      => 'wpdtrt_anchorlinks_shortcode',
			'plugin'                    => $wpdtrt_anchorlinks_plugin,
			'template'                  => 'anchorlinks',
			'selected_instance_options' => array(
				'post_id',
				'title_text',
				'exclude_widgets_on_pages',
				'additional_html',
				'additional_from_sidebar_id_1',
				'additional_from_sidebar_order_1',
			),
		)
	);
}

/**
 * Register Shortcode
 */
function wpdtrt_anchorlinks_shortcode_heading_init() {

	global $wpdtrt_anchorlinks_plugin;

	$wpdtrt_anchorlinks_shortcode_heading = new WPDTRT_Anchorlinks_Shortcode(
		array(
			'name'                      => 'wpdtrt_anchorlinks_shortcode_heading',
			'plugin'                    => $wpdtrt_anchorlinks_plugin,
			'template'                  => 'heading',
			'selected_instance_options' => array(
				'extra_header_class',
			),
		)
	);
}
