<?php
/**
 * Template to display plugin output in shortcodes and widgets
 *
 * @package   DTRT Anchor Links
 * @version   0.0.1
 * @since     0.7.0 DTRT WordPress Plugin Boilerplate Generator
 */

// Predeclare variables
//
// Internal WordPress arguments available to widgets
// This allows us to use the same template for shortcodes and front-end widgets.
$before_widget = null; // register_sidebar.
$before_title  = null; // register_sidebar.
$title         = null;
$after_title   = null; // register_sidebar.
$after_widget  = null; // register_sidebar.

// shortcode options.
$post_id    = null; // $post->ID stand-in for unit tests
$title_text = null;

// access to plugin.
$plugin = null;

// Options: display $args + widget $instance settings + access to plugin.
$options = get_query_var( 'options' );

// Overwrite variables from array values
// @link http://kb.network.dan/php/wordpress/extract/.
extract( $options, EXTR_IF_EXISTS );

global $post;

if ( isset( $post ) && is_object( $post ) ) {
	$post_id = $post->ID;
}

// Logic.
$anchor_list_html = $plugin->get_anchor_list_html( (int) $post_id );

// WordPress widget options (not output with shortcode).
echo $before_widget;
echo $before_title . $title . $after_title;
?>

<div class="wpdtrt-anchorlinks">
	<?php if ( null !== $title_text ) : ?>
	<h3 class="wpdtrt-anchorlinks__title">
		<span class="wpdtrt-anchorlinks__title-unsticky"><?php echo $title_text; ?></span>
	</h3>
	<div class="wpdtrt-anchorlinks__scroll-progress">
		<div class="wpdtrt-anchorlinks__scroll-progress-bar"></div>
	</div>
	<?php endif; ?>
	<?php
		echo $anchor_list_html;
	?>
</div>

<?php
// output widget customisations (not output with shortcode).
echo $after_widget;
?>
