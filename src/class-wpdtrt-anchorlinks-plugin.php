<?php
/**
 * Plugin sub class.
 *
 * @package     wpdtrt_anchorlinks
 * @version 	0.0.1
 * @since       0.7.0
 */

/**
 * Plugin sub class.
 *
 * Extends the base class to inherit boilerplate functionality.
 * Adds application-specific methods.
 *
 * @version 	0.0.1
 * @since       0.7.0
 */
class WPDTRT_Anchorlinks_Plugin extends DoTheRightThing\WPPlugin\r_1_4_6\Plugin {

    /**
     * Hook the plugin in to WordPress
     * This constructor automatically initialises the object's properties
     * when it is instantiated,
     * using new WPDTRT_Weather_Plugin
     *
     * @param     array $settings Plugin options
     *
	 * @version 	0.0.1
     * @since       0.7.0
     */
    function __construct( $settings ) {

    	// add any initialisation specific to wpdtrt-anchorlinks here

		// Instantiate the parent object
		parent::__construct( $settings );
    }

    //// START WORDPRESS INTEGRATION \\\\

    /**
     * Initialise plugin options ONCE.
     *
     * @param array $default_options
     *
     * @version     0.0.1
     * @since       0.7.0
     */
    protected function wp_setup() {

    	parent::wp_setup();

		// add actions and filters here
    }

    //// END WORDPRESS INTEGRATION \\\\

    //// START SETTERS AND GETTERS \\\\
    //// END SETTERS AND GETTERS \\\\

    //// START RENDERERS \\\\

    /**
     * Add project-specific frontend scripts
     *
     * @version     0.0.1
     * @since       0.7.1
     *
     * @see wpdtrt-plugin/src/Plugin.php
     */
    public function render_js_frontend() {
        $attach_to_footer = true;

        wp_register_script( 'jquery_waypoints',
            $this->get_url() . 'node_modules/waypoints/lib/jquery.waypoints.min.js',
            array(
                // load these registered dependencies first:
                'jquery',
            ),
            '4.0.0',
            $attach_to_footer
        );

        /**
         * waypoints sticky nav highlighting
         * Note: this plugin is also loaded via wpdtrt-gallery,
         * using the same hook of 'jquery_waypoints',
         * so that is is only loaded once (by wpdtrt-gallery);
         * If it was loaded again after wpdtrt-gallery.js,
         * then error = 'Uncaught TypeError: Waypoint.Inview is not a constructor'
         */
        wp_register_script( 'waypoints_sticky',
            $this->get_url() . 'node_modules/waypoints/lib/shortcuts/sticky.min.js',
            array(
                // load these registered dependencies first:
                'jquery_waypoints',
            ),
            '4.0.0',
            $attach_to_footer
        );

        // init
        // from Plugin.php + extra dependencies
        wp_enqueue_script( $this->get_prefix(),
            $this->get_url() . 'js/frontend.js',
            array(
                // load these registered dependencies first:
                'jquery',
                'waypoints_sticky',
            ),
            $this->get_version(),
            $attach_to_footer
        );

        // from Plugin.php
        wp_localize_script( $this->get_prefix(),
            $this->get_prefix() . '_config',
            array(
                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                // but we need to explicitly expose it to frontend pages
                'ajaxurl' => admin_url( 'admin-ajax.php' ), // wpdtrt_foobar_config.ajaxurl
                'options' => $this->get_options() // wpdtrt_foobar_config.options
            )
        );

        // Replace rather than extend, in order to specify dependencies:
        // parent::render_js_frontend();
    }

    //// END RENDERERS \\\\

    //// START FILTERS \\\\
    //// END FILTERS \\\\

    //// START HELPERS \\\\
    //// END HELPERS \\\\
}

?>