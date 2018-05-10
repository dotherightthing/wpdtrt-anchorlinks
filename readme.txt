
=== DTRT Anchor Links ===
Contributors: dotherightthingnz
Donate link: http://dotherightthing.co.nz
Tags: anchor links, content links, menu, navigation, sticky, scrollto
Requires at least: 4.9.5
Tested up to: 4.9.5
Requires PHP: 5.6.30
Stable tag: 0.1.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Anchor links plugin.

== Description ==

Anchor links plugin.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wpdtrt-anchorlinks` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->DTRT Anchor Links screen to configure the plugin

== Frequently Asked Questions ==

= How do I use the widget? =

One or more widgets can be displayed within one or more sidebars:

1. Locate the widget: Appearance > Widgets > *DTRT Anchor Links Widget*
2. Drag and drop the widget into one of your sidebars
3. Add a *Title*
4. Specify options

= How do I use the shortcode? =

```
<!-- within the editor -->
[wpdtrt_anchorlinks option="value"]

// in a PHP template, as a template tag
<?php echo do_shortcode( '[wpdtrt_anchorlinks option="value"]' ); ?>
```

Please refer to the *Shortcode Options* on Settings->DTRT Anchor Links.

== Screenshots ==

1. The caption for ./images/screenshot-1.(png|jpg|jpeg|gif)
2. The caption for ./images/screenshot-2.(png|jpg|jpeg|gif)

== Changelog ==

= 0.1.7 =
* Clean Composer files

= 0.1.6 =
* Update wpdtrt-contentsections

= 0.1.5 =
* Update wpdtrt-contentsections

= 0.1.4 =
* Update wpdtrt-plugin to 1.4.14

= 0.1.3 =
* Update wpdtrt-contentsections dependency
* Add wpdtrt-contentsections as a test dependency
* Fix path to autoloader when loading test dependencies or when loaded as a test dependency

= 0.1.2 =
* Include release number in wpdtrt-plugin namespaces
* Update wpdtrt-plugin to 1.4.6

= 0.1.1 =
* Update wpdtrt-plugin to 1.3.6

= 0.1.0 =
* Migrate Bower & NPM to Yarn
* Update Node from 6.11.2 to 8.11.1
* Add messages required by shortcode demo
* Add SCSS partials for project-specific extends and variables
* Change tag badge to release badge
* Fix default .pot file
* Document dependencies
* Update wpdtrt-plugin to 1.3.1

= 0.0.2 =
* Migrate code from wpdtrt and wpdtrt-dbth themes
* Move content section splitting into wpdtrt-contentsections
* Document dependencies
* Remove development dependencies
* Update wpdtrt-plugin

= 0.0.1 =
* Initial version

== Upgrade Notice ==

= 0.0.1 =
* Initial release
