
=== DTRT Anchor Links ===
Contributors: dotherightthingnz
Donate link: http://dotherightthing.co.nz
Tags: anchor links, content links, menu, navigation, sticky, scrollto
Requires at least: 4.9.5
Tested up to: 4.9.5
Requires PHP: 5.6.30
Stable tag: 0.2.4
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

= How do I display the list of anchor links? =

Within the editor: 

`[wpdtrt_anchorlinks_shortcode title_text="Jump links"]`

In a PHP template, as a template tag:

`<?php echo do_shortcode( '[wpdtrt_anchorlinks_shortcode title_text="Jump links"]' ); ?>`

= How do I control the dynamic pinning of the anchor links? =

Pinning toggles a class of `.wpdtrt-anchorlinks__site-sticky` on the `.wpdtrt-anchorlinks__site-sticky-target`.

This attached styling keeps the navigation in view, while the rest of the page content is scrolled.

To control pinning, add the following data attribute to an element:

`data-wpdtrt-anchorlinks-controls="pinning"`

* When this element is scrolled out of the viewport, the anchor links list will be pinned
* When this element is scrolled into the viewport, the anchor links list will be unpinned

= How do I control the dynamic hiding and showing of the anchor links? =

Add the following data attribute to an element.

`data-wpdtrt-anchorlinks-controls="hiding"`

* When this element is scrolled into the viewport, the anchor links list will be hidden
* When this element is scrolled out of the viewport, the anchor links list will be shown

= How do I control the dynamic highlighting of the anchor links? =

Add the following data attribute to a the relevant child of each anchor:

`data-wpdtrt-anchorlinks-controls="highlighting"`

* When this element is scrolled into the viewport, the matching anchor link will be highlighted.
* When another element is scrolled into the viewport,
    * the previous matching anchor link will be unhighlighted
    * the new matching anchor link will be highlighted

= How to inject a theme element after the anchor list? =

Add the following data attribute to the element:

`data-wpdtrt-anchorlinks-list-addition="1"`

* This element will be injected after the list
* If there are multiple elements to inject, this element will be injected first, as it has an id of `1`

== Screenshots ==

1. The caption for ./images/screenshot-1.(png|jpg|jpeg|gif)

== Changelog ==

= 0.2.5 =
* Remove better-anchor-links dependency
* Remove wpdtrt-contentsections dependency
* Replace gulp with wpdtrt-npm-scripts

= 0.2.4 =
* Update wpdtrt-plugin-boilerplate to 1.5.4 to fix 'slug' error
* Add missing initialisation message

= 0.2.3 =
* Update wpdtrt-plugin-boilerplate to 1.5.3
* Sync with generator-wpdtrt-plugin-boilerplate 0.8.2

= 0.2.2 =
* Update wpdtrt-plugin-boilerplate to 1.5.1
* Update wpdtrt-contentsections to 0.2.2
* Sync with generator-wpdtrt-plugin-boilerplate 0.8.1

= 0.2.1 =
* Update wpdtrt-contentsections dependency
* Fix JavaScript issues caused by ES6 refactoring

= 0.2.0 =
* Update wpdtrt-plugin-boilerplate to 1.5.0
* Sync with generator-wpdtrt-plugin-boilerplate 0.8.0

= 0.1.17 =
* Update wpdtrt-plugin-boilerplate to 1.4.39
* Sync with generator-wpdtrt-plugin-boilerplate 0.7.27

= 0.1.16 =
* Update TGMPA dependency version for wpdtrt-contentsections

= 0.1.15 =
* Update wpdtrt-plugin-boilerplate to 1.4.38
* Sync with generator-wpdtrt-plugin-boilerplate 0.7.25

= 0.1.14 =
* Update TGMPA dependency version for wpdtrt-contentsections

= 0.1.13 =
* Update wpdtrt-plugin-boilerplate to 1.4.25
* Sync with generator-wpdtrt-plugin-boilerplate 0.7.20 

= 0.1.12 =
* Update wpdtrt-contentsections to 0.1.4 in TGMPA config

= 0.1.11 =
* Update wpdtrt-plugin-boilerplate to 1.4.24
* Update wpdtrt-contentsections to 0.1.4
* Prefer stable versions but allow dev versions
* Update Yarn dependencies

= 0.1.10 =
* Rename wpdtrt-plugin to wpdtrt-plugin-boilerplate
* Update wpdtrt-plugin-boilerplate to 1.4.22
* Fix package name

= 0.1.9 =
* Update wpdtrt-contentsections dependency

= 0.1.8 =
* Update wpdtrt-plugin to 1.4.15

= 0.1.7 =
* Clean Composer files

= 0.1.6 =
* Update wpdtrt-contentsections dependency

= 0.1.5 =
* Update wpdtrt-contentsections dependency

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

== Validation ==

This readme was validated at https://wpreadme.com/.
