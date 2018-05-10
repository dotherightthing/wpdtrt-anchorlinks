<?php
/**
 * Plugin Name:  DTRT Anchor Links
 * Plugin URI:   https://github.com/dotherightthing/wpdtrt-anchorlinks
 * Description:  Anchor links plugin.
 * Version:      0.1.5
 * Author:       Dan Smith
 * Author URI:   https://profiles.wordpress.org/dotherightthingnz
 * License:      GPLv2 or later
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  wpdtrt-anchorlinks
 * Domain Path:  /languages
 */

/**
 * Autoload namespaced package classes
 * @see https://github.com/dotherightthing/wpdtrt-plugin/wiki/Options:-Adding-WordPress-plugin-dependencies
 */
if ( defined( 'WPDTRT_ANCHORLINKS_TEST_DEPENDENCY' ) ) {
  $projectRootPath = realpath(__DIR__ . '/../../..') . '/';
}
else {
  $projectRootPath = '';
}

require_once $projectRootPath . "vendor/autoload.php";

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
 * @link https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Constants
 * @link https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Plugins
 */

/**
  * Determine the correct path to the autoloader
  * @see https://github.com/dotherightthing/wpdtrt-plugin/issues/51
  */
if( ! defined( 'WPDTRT_PLUGIN_CHILD' ) ) {
  define( 'WPDTRT_PLUGIN_CHILD', true );
}

if( ! defined( 'WPDTRT_ANCHORLINKS_VERSION' ) ) {
/**
 * Plugin version.
 *
 * WP provides get_plugin_data(), but it only works within WP Admin,
 * so we define a constant instead.
 *
 * @example $plugin_data = get_plugin_data( __FILE__ ); $plugin_version = $plugin_data['Version'];
 * @link https://wordpress.stackexchange.com/questions/18268/i-want-to-get-a-plugin-version-number-dynamically
 *
 * @version   0.0.1
 * @since     0.7.0
 */
  define( 'WPDTRT_ANCHORLINKS_VERSION', '0.1.5' );
}

if( ! defined( 'WPDTRT_ANCHORLINKS_PATH' ) ) {
/**
 * Plugin directory filesystem path.
 *
 * @param string $file
 * @return The filesystem directory path (with trailing slash)
 *
 * @link https://developer.wordpress.org/reference/functions/plugin_dir_path/
 * @link https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
 *
 * @version   0.0.1
 * @since     0.7.0
 */
  define( 'WPDTRT_ANCHORLINKS_PATH', plugin_dir_path( __FILE__ ) );
}

if( ! defined( 'WPDTRT_ANCHORLINKS_URL' ) ) {
/**
 * Plugin directory URL path.
 *
 * @param string $file
 * @return The URL (with trailing slash)
 *
 * @link https://codex.wordpress.org/Function_Reference/plugin_dir_url
 * @link https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
 *
 * @version   0.0.1
 * @since     0.7.0
 */
  define( 'WPDTRT_ANCHORLINKS_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Include plugin logic
 *
 * @version   0.0.1
 * @since     0.7.0
 */

  // base class
  // redundant, but includes the composer-generated autoload file if not already included
  require_once($projectRootPath . 'vendor/dotherightthing/wpdtrt-plugin/index.php');

  // classes without composer.json files are loaded via Bower
  //require_once(WPDTRT_ANCHORLINKS_PATH . 'vendor/name/file.php');

  // sub classes
  require_once(WPDTRT_ANCHORLINKS_PATH . 'src/class-wpdtrt-anchorlinks-plugin.php');
  //require_once(WPDTRT_ANCHORLINKS_PATH . 'src/class-wpdtrt-anchorlinks-widgets.php');

  // log & trace helpers
  $debug = new DoTheRightThing\WPDebug\Debug;

  /**
   * Plugin initialisaton
   *
   * We call init before widget_init so that the plugin object properties are available to it.
   * If widget_init is not working when called via init with priority 1, try changing the priority of init to 0.
   * init: Typically used by plugins to initialize. The current user is already authenticated by this time.
   * └─ widgets_init: Used to register sidebars. Fired at 'init' priority 1 (and so before 'init' actions with priority ≥ 1!)
   *
   * @see https://wp-mix.com/wordpress-widget_init-not-working/
   * @see https://codex.wordpress.org/Plugin_API/Action_Reference
   * @todo Add a constructor function to WPDTRT_Anchorlinks_Plugin, to explain the options array
   */
  function wpdtrt_anchorlinks_init() {
    // pass object reference between classes via global
    // because the object does not exist until the WordPress init action has fired
    global $wpdtrt_anchorlinks_plugin;

    /**
     * Admin settings
     * For array syntax, please view the field documentation:
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-checkbox.php
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-number.php
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-password.php
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-select.php
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-text.php
     */
    $plugin_options = array();

    /**
     * All options available to Widgets and Shortcodes
     * For array syntax, please view the field documentation:
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-checkbox.php
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-number.php
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-password.php
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-select.php
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-text.php
     */
    $instance_options = array();

    $wpdtrt_anchorlinks_plugin = new WPDTRT_Anchorlinks_Plugin(
      array(
        'url' => WPDTRT_ANCHORLINKS_URL,
        'prefix' => 'wpdtrt_anchorlinks',
        'slug' => 'wpdtrt-anchorlinks',
        'menu_title' => __('Anchor Links', 'wpdtrt-anchorlinks'),
        'settings_title' => __('Settings', 'wpdtrt-anchorlinks'),
        'developer_prefix' => '',
        'path' => WPDTRT_ANCHORLINKS_PATH,
        'messages' => array(
          'loading' => __('Loading latest data...', 'wpdtrt-anchorlinks'),
          'success' => __('settings successfully updated', 'wpdtrt-anchorlinks'),
          'insufficient_permissions' => __('Sorry, you do not have sufficient permissions to access this page.', 'wpdtrt-anchorlinks'),
          'options_form_title' => __('General Settings', 'wpdtrt-anchorlinks'),
          'options_form_description' => __('Please enter your preferences.', 'wpdtrt-anchorlinks'),
          'no_options_form_description' => __('There aren\'t currently any options.', 'wpdtrt-anchorlinks'),
          'options_form_submit' => __('Save Changes', 'wpdtrt-anchorlinks'),
          'noscript_warning' => __('Please enable JavaScript', 'wpdtrt-anchorlinks'),
          'demo_sample_title' => __('Demo sample', 'wpdtrt-anchorlinks'),
          'demo_data_title' => __('Demo data', 'wpdtrt-anchorlinks'),
          'demo_shortcode_title' => __('Demo shortcode', 'wpdtrt-anchorlinks'),
          'demo_data_description' => __('This demo was generated from the following data', 'wpdtrt-anchorlinks'),
          'demo_date_last_updated' => __('Data last updated', 'wpdtrt-anchorlinks'),
          'demo_data_length' => __('results', 'wpdtrt-anchorlinks'),
          'demo_data_displayed_length' => __('results displayed', 'wpdtrt-anchorlinks'),
        ),
        'plugin_options' => $plugin_options,
        'instance_options' => $instance_options,
        'version' => WPDTRT_ANCHORLINKS_VERSION,
        'plugin_dependencies' => array(
          // Dependency: Pinning & scrolling is in addition to stock BAL link generation
          array(
            'name'      => 'Better Anchor Links',
            'slug'      => 'better-anchor-links',
            'required'  => true,
          ),
          // Dependency: Sections are used as scroll targets
          array(
            'name'          => 'DTRT Content Sections',
            'slug'          => 'wpdtrt-contentsections',
            'source'        => 'https://github.com/dotherightthing/wpdtrt-contentsections/releases/download/0.0.1/release.zip',
            'version'       => '0.0.1',
            'external_url'  => 'https://github.com/dotherightthing/wpdtrt-contentsections',
            'required'      => true,
          ),
        ),
        'demo_shortcode_params' => null
      )
    );
  }

  add_action( 'init', 'wpdtrt_anchorlinks_init', 0 );

  /**
   * Register functions to be run when the plugin is activated.
   *
   * @see https://codex.wordpress.org/Function_Reference/register_activation_hook
   *
   * @version   0.0.1
   * @since     0.7.0
   */
  function wpdtrt_anchorlinks_activate() {
    //wpdtrt_anchorlinks_rewrite_rules();
    flush_rewrite_rules();
  }

  register_activation_hook(__FILE__, 'wpdtrt_anchorlinks_activate');

  /**
   * Register functions to be run when the plugin is deactivated.
   *
   * (WordPress 2.0+)
   *
   * @see https://codex.wordpress.org/Function_Reference/register_deactivation_hook
   *
   * @version   0.0.1
   * @since     0.7.0
   */
  function wpdtrt_anchorlinks_deactivate() {
    flush_rewrite_rules();
  }

  register_deactivation_hook(__FILE__, 'wpdtrt_anchorlinks_deactivate');

?>
