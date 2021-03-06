<?php
/**
 * Unit tests, using PHPUnit, wp-cli, WP_UnitTestCase
 *
 * The plugin is 'active' within a WP test environment
 * so the plugin class has already been instantiated
 * with the options set in wpdtrt-gallery.php
 *
 * Only function names prepended with test_ are run.
 * $debug logs are output with the test output in Terminal
 * A failed assertion may obscure other failed assertions in the same test.
 *
 * @package     WPDTRT_Anchorlinks
 * @version     0.0.1
 * @since       0.7.0
 *
 * @see http://kb.dotherightthing.dan/php/wordpress/php-unit-testing-revisited/ - Links
 * @see http://richardsweeney.com/testing-integrations/
 * @see https://gist.github.com/benlk/d1ac0240ec7c44abd393 - Collection of notes on WP_UnitTestCase
 * @see https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/factory.php
 * @see https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes//factory/
 * @see https://stackoverflow.com/questions/35442512/how-to-use-wp-unittestcase-go-to-to-simulate-current-pageclass-wp-unittest-factory-for-term.php
 * @see https://codesymphony.co/writing-wordpress-plugin-unit-tests/#object-factories
 */

$post = new stdClass();

/**
 * WP_UnitTestCase unit tests for wpdtrt_anchorlinks
 */
class WPDTRT_AnchorlinksTest extends WP_UnitTestCase {

	/**
	 * Compare two HTML fragments.
	 *
	 * @param string $expected Expected value.
	 * @param string $actual Actual value.
	 * @param string $error_message Message to show when strings don't match.
	 * @uses https://stackoverflow.com/a/26727310/6850747
	 */
	protected function assertEqualHtml( $expected, $actual, $error_message ) {
		$from = [ '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/> </s' ];
		$to   = [ '>', '<', '\\1', '><' ];
		$this->assertEquals(
			preg_replace( $from, $to, $expected ),
			preg_replace( $from, $to, $actual ),
			$error_message
		);
	}

	/**
	 * SetUp
	 * Automatically called by PHPUnit before each test method is run
	 */
	public function setUp() {
		// Make the factory objects available.
		parent::setUp();

		global $wpdtrt_anchorlinks_plugin;

		$plugin_options                           = $wpdtrt_anchorlinks_plugin->get_plugin_options();
		$plugin_options['heading_level']['value'] = 'h2';
		$wpdtrt_anchorlinks_plugin->set_plugin_options( $plugin_options );

		$this->post_id_1 = $this->create_post( array(
			'post_title'   => 'DTRT Anchor Links test',
			'post_date'    => '2020-04-22 13:00:00',
			'post_content' => '<h2>Heading 1</h2><p>Text</p><h2>Heading 2</h2><p>More text</p>',
		));

		$this->post_id_2 = $this->create_post( array(
			'post_title'   => 'DTRT Anchor Links test',
			'post_date'    => '2020-05-10 12:13:00',
			'post_content' => '<p>Text</p><p>More text</p>',
		));
	}

	/**
	 * TearDown
	 * Automatically called by PHPUnit after each test method is run
	 *
	 * @see https://codesymphony.co/writing-wordpress-plugin-unit-tests/#object-factories
	 */
	public function tearDown() {

		parent::tearDown();

		wp_delete_post( $this->post_id_1, true );
		wp_delete_post( $this->post_id_2, true );
	}

	/**
	 * Create post
	 *
	 * @param array $options Post options (post_title, post_date, post_content).
	 * @return number $post_id
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_insert_post/
	 * @see https://wordpress.stackexchange.com/questions/37163/proper-formatting-of-post-date-for-wp-insert-post
	 * @see https://codex.wordpress.org/Function_Reference/wp_update_post
	 */
	public function create_post( $options ) {

		$post_title   = null;
		$post_date    = null;
		$post_content = null;

		extract( $options, EXTR_IF_EXISTS );

		$post_id = $this->factory->post->create([
			'post_title'   => $post_title,
			'post_date'    => $post_date,
			'post_content' => $post_content,
			'post_type'    => 'post',
			'post_status'  => 'publish',
		]);

		return $post_id;
	}

	/**
	 * ===== Tests =====
	 */

	/**
	 * Method: test_get_anchors
	 */
	public function test_get_anchors() {
		$this->go_to(
			get_post_permalink( $this->post_id_1 )
		);

		global $wpdtrt_anchorlinks_plugin;

		$anchors = $wpdtrt_anchorlinks_plugin->get_anchors( $this->post_id_1 );

		$this->assertEquals(
			true,
			is_array( $anchors ),
			'Array expected'
		);

		$this->assertEquals(
			2,
			count( $anchors ),
			'Number of anchor arrays'
		);

		// trim() removes trailing \n on CI.
		$this->assertEquals(
			'Heading 1',
			trim( $anchors[0][0] ),
			'Unexpected string'
		);

		$this->assertEquals(
			'heading-1',
			trim( $anchors[0][1] ),
			'Unexpected string'
		);
	}

	/**
	 * Method: test_render_headings_as_anchors
	 */
	public function test_render_headings_as_anchors() {
		$this->go_to(
			get_post_permalink( $this->post_id_1 )
		);

		// https://stackoverflow.com/a/22270259/6850747.
		$content = get_post_field( 'post_content', $this->post_id_1 );

		global $wpdtrt_anchorlinks_plugin;

		$content = $wpdtrt_anchorlinks_plugin->render_headings_as_anchors( $content, '' );

		$content = str_replace( array( '<body>', '</body>' ), '', $content );

		$this->assertEqualHtml(
			'<h2 class="wpdtrt-anchorlinks__anchor" id="heading-1"><a class="wpdtrt-anchorlinks__anchor-link" href="#heading-1"><span class="wpdtrt-anchorlinks__anchor-link-icon" aria-hidden="true"></span><span class="wpdtrt-anchorlinks__anchor-link-liner">Heading 1</span></a></h2><p>Text</p><h2 class="wpdtrt-anchorlinks__anchor" id="heading-2"><a class="wpdtrt-anchorlinks__anchor-link" href="#heading-2"><span class="wpdtrt-anchorlinks__anchor-link-icon" aria-hidden="true"></span><span class="wpdtrt-anchorlinks__anchor-link-liner">Heading 2</span></a></h2><p>More text</p>',
			trim( $content ),
			'Content unexpected'
		);
	}

	/**
	 * Method: test_render_headings_in_sections
	 */
	public function test_render_headings_in_sections() {
		$this->go_to(
			get_post_permalink( $this->post_id_1 )
		);

		// https://stackoverflow.com/a/22270259/6850747.
		$content = get_post_field( 'post_content', $this->post_id_1 );

		global $wpdtrt_anchorlinks_plugin;

		$content = $wpdtrt_anchorlinks_plugin->render_headings_as_anchors( $content, '' );
		$content = $wpdtrt_anchorlinks_plugin->render_headings_in_sections( $content );

		$content = str_replace( array( '<body>', '</body>' ), '', $content );

		$this->assertEqualHtml(
			'<div class="wpdtrt-anchorlinks__section" data-anchorlinks-controls="highlighting" data-anchorlinks-id="heading-1"><h2 class="wpdtrt-anchorlinks__anchor" id="heading-1"><a class="wpdtrt-anchorlinks__anchor-link" href="#heading-1"><span class="wpdtrt-anchorlinks__anchor-link-icon" aria-hidden="true"></span><span class="wpdtrt-anchorlinks__anchor-link-liner">Heading 1</span></a></h2><p>Text</p></div><div class="wpdtrt-anchorlinks__section" data-anchorlinks-controls="highlighting" data-anchorlinks-id="heading-2"><h2 class="wpdtrt-anchorlinks__anchor" id="heading-2"><a class="wpdtrt-anchorlinks__anchor-link" href="#heading-2"><span class="wpdtrt-anchorlinks__anchor-link-icon" aria-hidden="true"></span><span class="wpdtrt-anchorlinks__anchor-link-liner">Heading 2</span></a></h2><p>More text</p></div>',
			trim( $content ),
			'Content unexpected'
		);
	}

	/**
	 * Method: test_filter_content_sections_copy_attributes
	 */
	public function test_filter_content_sections_copy_attributes() {
		$this->go_to(
			get_post_permalink( $this->post_id_1 )
		);

		global $wpdtrt_anchorlinks_plugin;

		// https://stackoverflow.com/a/22270259/6850747.
		$content = get_post_field( 'post_content', $this->post_id_1 );
		$content = $wpdtrt_anchorlinks_plugin->render_headings_as_anchors( $content, '' );
		$content = $wpdtrt_anchorlinks_plugin->render_headings_in_sections( $content );

		// Processes \r\n's first so they aren't converted twice.
		// https://www.php.net/manual/en/function.str-replace.php.
		$content = str_replace( array( "\r\n", "\n", "\r" ), '', $content );

		$dom = new DOMDocument();
		$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );
		$dom->preserveWhiteSpace = false; // phpcs:disable

		$sections = $dom->getElementsByTagName( 'div' );

		$this->assertEquals(
			2,
			count( $sections ),
			'Expected 2 sections'
		);

		$this->assertEquals(
			true,
			is_object( $sections[0]->firstChild ),
			'Expected first section to contain child'
		);

		$this->assertEquals(
			1,
			$sections[0]->firstChild->nodeType,
			'Expected first section child to be DOM element'
		);

		$this->assertEquals(
			'h2',
			$sections[0]->firstChild->tagName,
			'Expected first section child to be an H2'
		);

		$this->assertEquals(
			true,
			is_object( $sections[1]->firstChild ),
			'Expected second section to contain child'
		);

		$this->assertEquals(
			1,
			$sections[1]->firstChild->nodeType,
			'Expected second section child to be DOM element'
		);

		$this->assertEquals(
			'h2',
			$sections[1]->firstChild->tagName,
			'Expected second section child to be an H2'
		);
	}

	/**
	 * Method: test_filter_content_sections
	 */
	public function test_filter_content_sections() {
		$this->go_to(
			get_post_permalink( $this->post_id_1 )
		);

		// https://stackoverflow.com/a/22270259/6850747.
		$content = apply_filters( 'the_content', get_post_field( 'post_content', $this->post_id_1 ) );

		// Processes \r\n's first so they aren't converted twice.
		// https://www.php.net/manual/en/function.str-replace.php.
		$content = str_replace( array( "\r\n", "\n", "\r" ), '', $content );

		$dom = new DOMDocument();
		$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );
		$dom->preserveWhiteSpace = false; // phpcs:disable

		$this->assertEquals(
			2,
			count(
				$dom
					->getElementsByTagName( 'h2' )
			),
			'Content contains unexpected number of headings'
		);

		/*
		// Test fails on CI:
		// due to new lines.
		$this->assertEquals(
			'<div class="wpdtrt-anchorlinks__section" id="heading-1"><h2 id="heading-1" class="wpdtrt-anchorlinks__anchor"><a class="wpdtrt-anchorlinks__anchor-link" href="#heading-1"><span class="wpdtrt-anchorlinks__anchor-link-icon" aria-hidden="true"></span><span class="wpdtrt-anchorlinks__anchor-link-liner">Heading 1</span></a></h2><p>Text</p></div>',
			trim( $dom->saveHTML( $dom->getElementsByTagName( 'div' )[0] ) ),
			'Expected first div to be a section containing a heading and text'
		);
		*/

		$this->assertEquals(
			'wpdtrt-anchorlinks__section',
			$dom
				->getElementsByTagName( 'div' )[0]
				->getAttribute( 'class' ),
			'Anchor has unexpected classname'
		);

		$this->assertEquals(
			'heading-1',
			$dom
				->getElementsByTagName( 'h2' )[0]
				->getAttribute( 'id' ),
			'Anchor has unexpected data-anchorlinks-id'
		);

		$this->assertEquals(
			'heading-1',
			$dom
				->getElementsByTagName( 'div' )[0]
				->getAttribute( 'data-anchorlinks-id' ),
			'Section has unexpected data-anchorlinks-id'
		);

		$this->assertEquals(
			'heading-2',
			$dom
				->getElementsByTagName( 'h2' )[1]
				->getAttribute( 'id' ),
			'Anchor has unexpected data-anchorlinks-id'
		);

		$this->assertEquals(
			'heading-2',
			$dom
				->getElementsByTagName( 'div' )[1]
				->getAttribute( 'data-anchorlinks-id' ),
			'Section has unexpected data-anchorlinks-id'
		);

		$this->assertEquals(
			1,
			count(
				$dom
					->getElementsByTagName( 'h2' )[0]
					->getElementsByTagName( 'a' )
			),
			'Anchor does not contain a link'
		);

		$this->assertEquals(
			'wpdtrt-anchorlinks__anchor-link',
			$dom
				->getElementsByTagName( 'div' )[0]
				->getElementsByTagName( 'a' )[0]
				->getAttribute( 'class' ),
			'Anchor link has unexpected classname'
		);

		$this->assertEquals(
			'#heading-1',
			$dom
				->getElementsByTagName( 'h2' )[0]
				->getElementsByTagName( 'a' )[0]
				->getAttribute( 'href' ),
			'Anchor link has unexpected href'
		);
	}

	/**
	 * Method: test_filter_content_sections_no_anchors
	 */
	public function test_filter_content_sections_no_anchors() {
		$this->go_to(
			get_post_permalink( $this->post_id_2 )
		);

		// https://stackoverflow.com/a/22270259/6850747.
		$content = apply_filters( 'the_content', get_post_field( 'post_content', $this->post_id_2 ) );

		// Processes \r\n's first so they aren't converted twice.
		// https://www.php.net/manual/en/function.str-replace.php.
		$content = str_replace( array( "\r\n", "\n", "\r" ), '', $content );

		$dom = new DOMDocument();
		$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );
		$dom->preserveWhiteSpace = false; // phpcs:disable

		$this->assertEquals(
			0,
			count(
				$dom
					->getElementsByTagName( 'div' )
			),
			'Content contains unexpected number of sections'
		);

		$this->assertEquals(
			0,
			count(
				$dom
					->getElementsByTagName( 'h2' )
			),
			'Content contains unexpected number of headings'
		);
	}

	/**
	 * Test shortcode output
	 * trim() removes line break added by WordPress
	 *
	 * @see https://stackoverflow.com/a/3760828/6850747
	 * @todo wpdtrt_tourdates_shortcode_navigation
	 * @todo wpdtrt_tourdates_shortcode_thumbnail
	 * @todo Refactor wpdtrt_tourdates_shortcode_summary so that it is easier to test
	 */
	public function test_shortcode_output() {
		$this->go_to(
			get_post_permalink( $this->post_id_1 )
		);

		// shortcodes are not used in the content area,
		// so we don't load a WordPress post here.
		$shortcode      = '[wpdtrt_anchorlinks_shortcode title_text="Jump menu" post_id="' . $this->post_id_1 . '"]';
		$shortcode_html = trim( do_shortcode( $shortcode ) );

		// Processes \r\n's first so they aren't converted twice.
		// https://www.php.net/manual/en/function.str-replace.php.
		$shortcode_html = str_replace( array( "\r\n", "\n", "\r" ), '', $shortcode_html );

		$dom = new DOMDocument();
		$dom->loadHTML( mb_convert_encoding( $shortcode_html, 'HTML-ENTITIES', 'UTF-8' ) );
		$dom->preserveWhiteSpace = false; // phpcs:disable

		// phpcs:disable WordPress.NamingConventions

		// Anchor list.
		$this->assertEquals(
			1,
			count(
				$dom
					->getElementsByTagName( 'ol' )
			),
			'Expected 1 list element'
		);

		$anchor_list = $dom->getElementsByTagName( 'ol' )[0];

		// Anchor list class.
		$this->assertEquals(
			'wpdtrt-anchorlinks__list',
			$anchor_list
				->getAttribute( 'class' ),
			'List has unexpected classname'
		);

		// Anchor list parent class.
		$this->assertEquals(
			'wpdtrt-anchorlinks',
			$anchor_list
				->parentNode
				->getAttribute( 'class' ),
			'List wrapper has unexpected classname'
		);

		// Anchor list title element.
		// firstChild / childNodes[0] is an empty string.
		$this->assertEquals(
			'h2',
			$anchor_list
				->parentNode
				->childNodes[1]
				->tagName,
			'List title uses unexpected element'
		);

		// Anchor list title class.
		// firstChild / childNodes[0] is an empty string.
		$this->assertEquals(
			'wpdtrt-anchorlinks__title',
			$anchor_list
				->parentNode
				->childNodes[1]
				->getAttribute( 'class' ),
			'List title has unexpected classname'
		);

		// Anchor list title text.
		// firstChild / childNodes[0] is an empty string.
		$this->assertEquals(
			'Jump menu',
			 trim(
				$anchor_list
					->parentNode
					->childNodes[1]
					->textContent
			),
			'List title has unexpected text'
		);

		// Anchor list item length.
		$this->assertEquals(
			2,
			count(
				$anchor_list
					->getElementsByTagName( 'li' )
			),
			'List contains unexpected number of items'
		);

		// Anchor list item class.
		$this->assertEquals(
			'wpdtrt-anchorlinks__list-item',
			$anchor_list
				->getElementsByTagName( 'li' )[0]
				->getAttribute( 'class' ),
			'List item has unexpected classname'
		);

		// Anchor list link length.
		$this->assertEquals(
			2,
			count(
				$anchor_list
					->getElementsByTagName( 'a' )
			),
			'List contains unexpected number of links'
		);

		// Anchor list link class.
		$this->assertEquals(
			'wpdtrt-anchorlinks__list-link',
			$anchor_list
				->getElementsByTagName( 'li' )[0]
				->getElementsByTagName( 'a' )[0]
				->getAttribute( 'class' ),
			'List link has unexpected classname'
		);

		// Anchor list link href.
		$this->assertEquals(
			'#heading-1',
			$anchor_list
				->getElementsByTagName( 'li' )[0]
				->getElementsByTagName( 'a' )[0]
				->getAttribute( 'href' ),
			'List link has unexpected href'
		);

		// Anchor list link text.
		$this->assertEquals(
			'Heading 1',
			trim(
				$anchor_list
					->getElementsByTagName( 'li' )[0]
					->getElementsByTagName( 'a' )[0]
					->nodeValue
			),
			'List link has unexpected text'
		);

		// phpcs:enable WordPress.NamingConventions
	}

	/**
	 * Test shortcode output when there are no anchors
	 * trim() removes line break added by WordPress
	 */
	public function test_shortcode_output_no_anchors() {
		$this->go_to(
			get_post_permalink( $this->post_id_2 )
		);

		// shortcodes are not used in the content area,
		// so we don't load a WordPress post here.
		$shortcode      = '[wpdtrt_anchorlinks_shortcode title_text="Jump menu" post_id="' . $this->post_id_2 . '"]';
		$shortcode_html = trim( do_shortcode( $shortcode ) );

		// Anchor list.
		$this->assertEquals(
			0,
			strlen( $shortcode_html ),
			'Expected no output'
		);
	}
}
