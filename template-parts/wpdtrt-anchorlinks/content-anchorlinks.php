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
$post_id                         = null; // $post->ID stand-in for unit tests
$title_text                      = null;
$additional_html                 = null;
$additional_from_sidebar_id_1    = null;
$additional_from_sidebar_order_1 = null;

// access to plugin.
$plugin = null;

// Options: display $args + widget $instance settings + access to plugin.
$options = get_query_var( 'options' );

// Overwrite variables from array values
// @link http://kb.network.dan/php/wordpress/extract/.
extract( $options, EXTR_IF_EXISTS );

if ( isset( $additional_from_sidebar_id_1 ) && ( '' === $additional_from_sidebar_id_1 ) ) {
	$additional_from_sidebar_id_1 = null;
}

global $post;

if ( isset( $post ) && is_object( $post ) ) {
	$post_id = $post->ID;
}

// Logic.
$anchors = $plugin->get_anchors( (int) $post_id );

if ( ( null !== $additional_from_sidebar_id_1 ) && ( null !== $additional_from_sidebar_order_1 ) ) {
	global $wp_registered_widgets;
	$new_anchors = array();

	$all_sidebars_widgets = get_option( 'sidebars_widgets' );
	$sidebars_widgets     = $all_sidebars_widgets[ 'sidebar-' . $additional_from_sidebar_id_1 ];

	foreach ( $sidebars_widgets as $sidebars_widget ) {
		if ( isset( $wp_registered_widgets[ $sidebars_widget ]['name'] ) ) {
			$name = $wp_registered_widgets[ $sidebars_widget ]['name'];

			array_push( $new_anchors, array(
				$name . '#',
				'section-' . sanitize_title( $name ),
			) );
		}
	}

	$new_anchors = array_reverse( $new_anchors );

	foreach ( $new_anchors as $new_anchor ) {
		if ( intval( $additional_from_sidebar_order_1 ) < 0 ) {
			array_unshift( $anchors, $new_anchor );
		} else {
			array_push( $anchors, $new_anchor );
		}
	}
}

// WordPress widget options (not output with shortcode).
echo $before_widget;
echo $before_title . $title . $after_title;

if ( count( $anchors ) > 0 ) :
	?>

<div class="wpdtrt-anchorlinks">
	<?php if ( null !== $title_text ) : ?>
	<h2 class="wpdtrt-anchorlinks__title">
		<span class="wpdtrt-anchorlinks__title-fixed"><?php echo $title_text; ?></span>
	</h2>
	<div class="wpdtrt-anchorlinks__scroll-progress">
		<div class="wpdtrt-anchorlinks__scroll-progress-bar"></div>
	</div>
	<?php endif; ?>
	<?php
		echo $plugin->render_anchor_list_html( (array) $anchors );

	if ( null !== $additional_html ) {
		echo "<div class='wpdtrt-anchorlinks__additions'>{$additional_html}</div>";
	}
	?>
</div>

	<?php
endif;

// output widget customisations (not output with shortcode).
echo $after_widget;
?>
