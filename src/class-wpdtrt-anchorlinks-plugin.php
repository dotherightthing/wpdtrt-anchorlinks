<?php
/**
 * Plugin sub class.
 *
 * @package WPDTRT_Anchorlinks
 * @since   0.7.17 DTRT WordPress Plugin Boilerplate Generator
 */

/**
 * Extend the base class to inherit boilerplate functionality.
 * Adds application-specific methods.
 *
 * @since   1.0.0
 */
class WPDTRT_Anchorlinks_Plugin extends DoTheRightThing\WPDTRT_Plugin_Boilerplate\r_1_5_4\Plugin {

	/**
	 * Supplement plugin initialisation.
	 *
	 * @param     array $options Plugin options.
	 * @since     1.0.0
	 * @version   1.1.0
	 */
	public function __construct( $options ) { // phpcs:ignore

		// edit here.
		parent::__construct( $options );
	}

	/**
	 * ====== WordPress Integration ======
	 */

	/**
	 * Supplement plugin's WordPress setup.
	 * Note: Default priority is 10. A higher priority runs later.
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference Action order
	 */
	protected function wp_setup() {

		// edit here.
		parent::wp_setup();

		// add actions and filters here.
		add_filter( 'wpdtrt_anchorlinks_set_api_endpoint', array( $this, 'filter_set_api_endpoint' ) );
		add_filter( 'the_content', array( $this, 'filter_content_anchors' ), 10 );
	}

	/**
	 * ====== Getters and Setters ======
	 */

	/**
	 * ===== Renderers =====
	 */

	/**
	 * Add project-specific frontend scripts
	 *
	 * @version     0.0.1
	 * @since       0.7.1
	 *
	 * @see wpdtrt-plugin-boilerplate/src/Plugin.php
	 */
	public function render_js_frontend() {
		$attach_to_footer = true;

		wp_register_script( 'jquery_waypoints',
			$this->get_url() . 'node_modules/waypoints/lib/jquery.waypoints.min.js',
			array(
				// load these registered dependencies first:.
				'jquery',
			),
			'4.0.0',
			$attach_to_footer
		);

		/**
		 * Waypoints sticky nav highlighting
		 * Note: this plugin is also loaded via wpdtrt-gallery,
		 * using the same hook of 'jquery_waypoints',
		 * so that is is only loaded once (by wpdtrt-gallery);
		 * If it was loaded again after wpdtrt-gallery.js,
		 * then error = 'Uncaught TypeError: Waypoint.Inview is not a constructor'
		 */
		wp_register_script( 'waypoints_sticky',
			$this->get_url() . 'node_modules/waypoints/lib/shortcuts/sticky.min.js',
			array(
				// load these registered dependencies first:.
				'jquery_waypoints',
			),
			'4.0.0',
			$attach_to_footer
		);

		// init
		// from Plugin.php + extra dependencies.
		wp_enqueue_script( $this->get_prefix(),
			$this->get_url() . 'js/frontend-es5.js',
			array(
				// load these registered dependencies first:.
				'jquery',
				'waypoints_sticky',
			),
			$this->get_version(),
			$attach_to_footer
		);

		// from Plugin.php.
		wp_localize_script( $this->get_prefix(),
			$this->get_prefix() . '_config',
			array(
				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				// but we need to explicitly expose it to frontend pages.
				'ajaxurl' => admin_url( 'admin-ajax.php' ), // wpdtrt_foobar_config.ajaxurl.
				'options' => $this->get_options(), // wpdtrt_foobar_config.options.
			)
		);

		// Replace rather than extend, in order to specify dependencies:
		// parent::render_js_frontend();.
	}

	/**
	 * ===== Filters =====
	 */

	/**
	 * Method: filter_content_anchors
	 *
	 * Add an anchor to each heading.
	 * Replacement for Better Anchor Links.
	 *
	 * Parameters:
	 *   $content - Content
	 *
	 * Returns:
	 *   $content - Content
	 *
	 * See:
	 * <https://developer.wordpress.org/reference/functions/sanitize_title/>
	 */
	public function filter_content_anchors( string $content ) : string {
		$dom = new DOMDocument();
		$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );

		$headings     = $dom->getElementsByTagName( 'h2' );
		$replacements = array();

		foreach ( $headings as $key => $heading ) {
			$heading_span = $dom->createElement( 'span', '#' );
			$heading_span->setAttribute( 'aria-label', 'Anchor' );
			$heading_span->setAttribute( 'class', 'wpdtrt-anchorlinks__anchor-icon' );

			$heading_link = $dom->createElement( 'a' );
			$heading_link->setAttribute( 'class', 'wpdtrt-anchorlinks__anchor-link' );

			$heading->setAttribute( 'class', 'wpdtrt-anchorlinks__anchor' );
			$heading_id = sanitize_title( $heading->nodeValue ); // phpcs:ignore
			$heading->setAttribute( 'id', $heading_id );

			$heading_link->setAttribute( 'href', '#' . $heading_id );
			$heading_link->appendChild( $heading_span );
			$heading->appendChild( $heading_link );
		}

		$body = $dom->getElementsByTagName( 'body' )->item( 0 );

		return $dom->saveHTML( $body );
	}

	/**
	 * ===== Helpers =====
	 */
}
