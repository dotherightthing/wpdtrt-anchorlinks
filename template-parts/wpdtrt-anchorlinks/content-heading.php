<?php
/**
 * File: template-parts/wpdtrt-anchorlinks/content-heading.php
 *
 * Template to display plugin output in shortcodes and widgets.
 * This template is for auxiliary content outside the_content
 * which is not processed by filter_content_sections().
 *
 * Since:
 *   0.8.13 - DTRT WordPress Plugin Boilerplate Generator
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
$extra_header_class = null;

// access to plugin.
$plugin = null;

// Options: display $args + widget $instance settings + access to plugin.
$options = get_query_var( 'options', array() );

// Overwrite variables from array values
// @link http://kb.network.dan/php/wordpress/extract/.
extract( $options, EXTR_IF_EXISTS );

// content between shortcode tags.
if ( isset( $context ) ) {
	$content = $context->content;
	$id      = sanitize_title( $content );
} else {
	$content = '';
	$id      = '';
}

if ( null === $extra_header_class ) {
	$extra_header_class = '';
}

$content = $plugin->render_headings_as_anchors( $content, $extra_header_class );

// WordPress widget options (not output with shortcode).
echo $before_widget;
echo $before_title . $title . $after_title;

echo $content;

// output widget customisations (not output with shortcode).
echo $after_widget;
