<?php
/**
 * File: template-parts/wpdtrt-anchorlinks/content-heading.php
 *
 * Template to display plugin output in shortcodes and widgets.
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

$header_class = 'wpdtrt-anchorlinks__header';

if ( null !== $extra_header_class ) {
	$header_class = $header_class . ' ' . $extra_header_class;
}

$heading_attrs   = "data-anchorlinks-id='section-{$id}' class='wpdtrt-anchorlinks__anchor' tabindex='-1'";
$heading_anchor  = "<a class='wpdtrt-anchorlinks__anchor-link' href='#section-{$id}'>";
$heading_anchor .= "<span aria-label='Anchor' class='wpdtrt-anchorlinks__anchor-icon'>#</span>";
$heading_anchor .= '</a>';

foreach ( [ 'h2', 'h3', 'h4' ] as $hx ) {
	$content = str_replace( "<{$hx}>", "<{$hx} {$heading_attrs}>", $content );
}

$content = str_replace( '</', "{$heading_anchor}</", $content );

// WordPress widget options (not output with shortcode).
echo $before_widget;
echo $before_title . $title . $after_title;

// This template is for auxiliary content outside the_content.
// It mirrors the output of render_headings_as_anchors()
// which is applied to headings within the_content.
?>

<div class="<?php echo $header_class; ?>">
	<?php echo $content; ?>
</div>

<?php
// output widget customisations (not output with shortcode).
echo $after_widget;
