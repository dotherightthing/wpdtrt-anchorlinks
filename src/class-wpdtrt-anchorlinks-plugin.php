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
class WPDTRT_Anchorlinks_Plugin extends DoTheRightThing\WPDTRT_Plugin_Boilerplate\r_1_7_14\Plugin {

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
		/**
		 * Prevent additional <p></p> element from being injected after h2.
		 *
		 * @see wp-includes/default-filters.php
		 * @see https://github.com/ferocknew/homefile/blob/4fd3686b88e2a0b579da3978941983f8538223a1/blog/blog/wp-content/themes/suffusion/post-formats/content-chat.php#L24-L36
		 */
		if ( has_filter( 'the_content', 'wpautop' ) ) {
			remove_filter( 'the_content', 'wpautop' );
			add_filter( 'the_content', 'wpautop', 9 );
		}

		add_filter( 'the_content', array( $this, 'filter_content_sections' ), 9 );
	}

	/**
	 * ====== Getters and Setters ======
	 */

	/**
	 * Method: get_anchors
	 *
	 * Uses the data-anchorlinks-id attributes injected by $this->filter_content_sections().
	 *
	 * Parameters:
	 *   $post_id - Page ID
	 *
	 * Returns:
	 *   $anchors
	 */
	public function get_anchors( int $post_id ) {
		$post    = get_post( $post_id );
		$content = apply_filters( 'the_content', $post->post_content );
		$content = $this->helper_clean_html( $content );

		$dom = new DOMDocument();
		$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );

		$headings         = $dom->getElementsByTagName( 'h2' );
		$anchors          = array();
		$anchor_list_html = '';

		// phpcs:disable WordPress.NamingConventions
		foreach ( $headings as $heading ) {
			if ( null !== $heading->getAttribute( 'data-anchorlinks-id' ) ) {
				$anchors[] = array(
					str_replace( '&', '&amp;', $heading->nodeValue ), // phpcs:ignore
					$heading->getAttribute( 'data-anchorlinks-id' ),
				);
			}
		}
		// phpcs:enable WordPress.NamingConventions

		return $anchors;
	}

	/**
	 * ===== Renderers =====
	 */

	/**
	 * Method: render_anchor_list_html
	 *
	 * Parameters:
	 *   $anchors - Anchors
	 *
	 * Returns:
	 *   $anchors
	 */
	public function render_anchor_list_html( array $anchors ) {
		$dom = new DOMDocument();

		if ( count( $anchors ) > 0 ) {
			$anchor_list = $dom->createElement( 'ol' );
			$anchor_list->setAttribute( 'class', 'wpdtrt-anchorlinks__list' );

			foreach ( $anchors as $anchor ) {
				$anchor_list_item_text = str_replace( '#', '', $anchor[0] );

				$anchor_list_item_link_liner_icon = $dom->createElement( 'span' );
				$anchor_list_item_link_liner_icon->setAttribute( 'class', 'wpdtrt-anchorlinks__list-link-icon' );

				$anchor_list_item_link_liner = $dom->createElement( 'span', $anchor_list_item_text );
				$anchor_list_item_link_liner->setAttribute( 'class', 'wpdtrt-anchorlinks__list-link-liner' );

				$anchor_list_item_link = $dom->createElement( 'a' );
				$anchor_list_item_link->setAttribute( 'href', '#' . $anchor[1] );
				$anchor_list_item_link->setAttribute( 'class', 'wpdtrt-anchorlinks__list-link' );
				$anchor_list_item_link->appendChild( $anchor_list_item_link_liner );
				$anchor_list_item_link->appendChild( $anchor_list_item_link_liner_icon );

				$anchor_list_item = $dom->createElement( 'li' );
				$anchor_list_item->setAttribute( 'class', 'wpdtrt-anchorlinks__list-item' );
				$anchor_list_item->appendChild( $anchor_list_item_link );

				$anchor_list->appendChild( $anchor_list_item );
			}

			$anchor_list_html = $this->render_html( $anchor_list, true );
		}

		return $anchor_list_html;
	}

	/**
	 * Method: render_headings_as_anchors
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
	public function render_headings_as_anchors( string $content ) : string {
		$dom = new DOMDocument();
		$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );

		$headings = $dom->getElementsByTagName( 'h2' );

		foreach ( $headings as $heading ) {
			$heading_span = $dom->createElement( 'span', '#' );
			$heading_span->setAttribute( 'aria-label', 'Anchor' );
			$heading_span->setAttribute( 'class', 'wpdtrt-anchorlinks__anchor-icon' );

			$heading_link = $dom->createElement( 'a' );
			$heading_link->setAttribute( 'class', 'wpdtrt-anchorlinks__anchor-link' );

			// class is also used by $this->render_headings_in_sections().
			$heading->setAttribute( 'class', 'wpdtrt-anchorlinks__anchor' );
			$heading_id = 'section-' . sanitize_title( $heading->nodeValue ); // phpcs:ignore
			$heading->setAttribute( 'id', $heading_id );
			$heading->setAttribute( 'tabindex', '-1' );

			$heading_link->setAttribute( 'href', '#' . $heading_id );
			$heading_link->appendChild( $heading_span );
			$heading->appendChild( $heading_link );
		}

		$body = $dom->getElementsByTagName( 'body' )->item( 0 );

		return $dom->saveHTML( $body );
	}

	/**
	 * Method: render_headings_in_sections
	 *
	 * Wrap a section around each heading and its siblings.
	 * Replacement for wpdtrt-contentsections.
	 * Headings are identified by the class
	 * added by $this->render_headings_as_anchors()
	 *
	 * Parameters:
	 *   $content - Content
	 *
	 * Returns:
	 *   $content - Content
	 *
	 * See:
	 * <https://www.php.net/manual/en/dom.constants.php>
	 */
	public function render_headings_in_sections( string $content ) : string {
		$content = $this->helper_clean_html( $content );

		$heading_start = '<h2 class="wpdtrt-anchorlinks__anchor"';

		// DOMDocument doesn't support HTML5 tags like <section>.
		$section_start = '<div class="wpdtrt-anchorlinks__section">';
		$section_end   = '</div>';

		// wrap a section around each heading and its siblings.
		$content = $section_start . str_replace(
			$heading_start,
			$section_end . $section_start . $heading_start,
			$content
		) . $section_end;

		// Fix DOMDocument::loadHTML(): htmlParseStartTag: misplaced <body> tag in Entity, line: 1.
		$content = str_replace( array( '<body>', '</body>' ), '', $content );

		return $content;
	}

	/**
	 * Method: render_html
	 *
	 * This is better than getting child nodes because WP shortcodes aren't HTML elements.
	 *
	 * Parameters:
	 *   $n - DOM node
	 *   $include_target_tag - whether to include the element tag in the output
	 *
	 * Returns:
	 *   $html - HTML
	 *
	 * See:
	 * <https://stackoverflow.com/a/53740544/6850747>
	 */
	public function render_html( DOMNode $n, $include_target_tag = false ) : string {
		$dom = new DOMDocument();
		$dom->appendChild( $dom->importNode( $n, true ) ); // $deep.
		$html = trim( $dom->saveHTML() );

		if ( $include_target_tag ) {
			return $html;
		}

		$html = preg_replace( '@^<' . $n->nodeName . '[^>]*>|</'. $n->nodeName . '>$@', '', $html ); // phpcs:ignore

		return $html;
	}

	/**
	 * Add project-specific frontend scripts
	 *
	 * Use this function to:
	 * - load scripts in addition to js/frontend-es5.js (via wp_enqueue_script)
	 * - add keys to wpdtrt_anchorlinks_config (via wp_localize_script)
	 *
	 * Don't use function this to:
	 * - add ES6 scripts requiring transpiling (load them using frontend.txt instead)
	 *
	 * @see wpdtrt-plugin-boilerplate/src/Plugin.php
	 */
	public function render_js_frontend() { // phpcs:ignore
		// If editing this function, remove this line to replace the parent function.
		parent::render_js_frontend();
	}

	/**
	 * ===== Filters =====
	 */

	/**
	 * Method: filter_content_sections
	 *
	 * Wrap a section around each heading and its siblings.
	 * Replacement for wpdtrt-contentsections.
	 *
	 * Parameters:
	 *   $content - Content
	 *
	 * Returns:
	 *   $content - Content
	 *
	 * See:
	 * <https://www.php.net/manual/en/dom.constants.php>
	 */
	public function filter_content_sections( string $content ) : string {
		$content = $this->render_headings_as_anchors( $content );
		$content = $this->render_headings_in_sections( $content );
		$content = $this->helper_clean_html( $content );

		$dom = new DOMDocument();
		$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );

		$sections = $dom->getElementsByTagName( 'div' );

		// remove sections after the loop, else DOMDocument gets confused.
		$empty_sections = array();

		// phpcs:disable WordPress.NamingConventions
		foreach ( $sections as $section ) {
			$headings = $section->getElementsByTagName( 'h2' );

			if ( $headings->length > 0 ) {
				$attributes = array( 'class', 'id', 'tabindex' );
				$heading    = $headings[0];

				foreach ( $attributes as $attribute ) {
					$old_value = $section->getAttribute( $attribute );
					$new_value = $heading->getAttribute( $attribute );

					if ( $old_value ) {
						$new_value = ( $old_value . ' ' . $new_value );
					}

					// set attribute.
					$section->setAttribute( $attribute, $new_value );

					// remove attribute.
					$heading->removeAttribute( $attribute );

					// retain the ID via a data-anchorlinks-id attribute
					// as the structure changes when the gallery wrappers are injected,
					// breaking the section > heading relationship.
					if ( 'id' === $attribute ) {
						$heading->setAttribute( 'data-anchorlinks-id', $new_value );
					}
				}
			} else {
				$empty_sections[] = $section;
			}
		}

		foreach ( $empty_sections as $empty_section ) {
			// remove empty section resulting from string replacement.
			// this excludes sections which contain p but no h2.
			$empty_section->parentNode->removeChild( $empty_section );
		}

		// phpcs:enable WordPress.NamingConventions

		$body = $dom->getElementsByTagName( 'body' )->item( 0 );

		return $dom->saveHTML( $body );
	}

	/**
	 * ===== Helpers =====
	 */

	/**
	 * Method: helper_clean_html
	 *
	 * Clean HTML to avoid DOMDocument errors in testing.
	 *
	 * Parameters:
	 *   $content - Content
	 *
	 * Returns:
	 *   $content - Content
	 */
	public function helper_clean_html( string $content ) : string {
		$content = str_replace( array( "\r\n", "\n", "\r" ), '', $content );
		$content = trim( $content );

		return $content;
	}
}
