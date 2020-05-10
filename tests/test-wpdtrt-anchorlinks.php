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
	 * Method: test_content_filters
	 */
	public function test_content_filters() {
		$this->go_to(
			get_post_permalink( $this->post_id_1 )
		);

		// https://stackoverflow.com/a/22270259/6850747.
		$content = apply_filters( 'the_content', get_post_field( 'post_content', $this->post_id_1 ) );

		$dom = new DOMDocument();
		$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );

		$this->assertEquals(
			2,
			count(
				$dom
					->getElementsByTagName( 'h2' )
			),
			'Content contains unexpected number of headings'
		);

		$this->assertEquals(
			'wpdtrt-anchorlinks__section wpdtrt-anchorlinks__anchor',
			$dom
				->getElementsByTagName( 'div' )[0]
				->getAttribute( 'class' ),
			'Anchor has unexpected classname'
		);

		$this->assertEquals(
			'heading-1',
			$dom
				->getElementsByTagName( 'div' )[0]
				->getAttribute( 'id' ),
			'Anchor has unexpected id'
		);

		$this->assertEquals(
			'heading-2',
			$dom
				->getElementsByTagName( 'div' )[1]
				->getAttribute( 'id' ),
			'Anchor has unexpected id'
		);

		$this->assertEquals(
			1,
			count(
				$dom
					->getElementsByTagName( 'div' )[0]
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
				->getElementsByTagName( 'div' )[0]
				->getElementsByTagName( 'a' )[0]
				->getAttribute( 'href' ),
			'Anchor link has unexpected href'
		);
	}

	/**
	 * Method: test_content_filters_no_anchors
	 */
	public function test_content_filters_no_anchors() {
		$this->go_to(
			get_post_permalink( $this->post_id_2 )
		);

		// https://stackoverflow.com/a/22270259/6850747.
		$content = apply_filters( 'the_content', get_post_field( 'post_content', $this->post_id_2 ) );

		$dom = new DOMDocument();
		$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );

		$this->assertEquals(
			0,
			count(
				$dom
					->getElementsByTagName( 'h2' )
			),
			'Content contains unexpected number of headings'
		);

		$this->assertEquals(
			'wpdtrt-anchorlinks__section',
			$dom
				->getElementsByTagName( 'div' )[0]
				->getAttribute( 'class' ),
			'Section has unexpected classname'
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

		$dom = new DOMDocument();
		$dom->loadHTML( mb_convert_encoding( $shortcode_html, 'HTML-ENTITIES', 'UTF-8' ) );

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
			'h3',
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
