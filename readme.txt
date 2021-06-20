
=== DTRT Anchor Links ===
Contributors: dotherightthingnz
Donate link: http://dotherightthing.co.nz
Tags: anchor links, content links, menu, navigation, sticky, scrollto
Requires at least: 5.3.3
Tested up to: 5.3.3
Requires PHP: 7.2.15
Stable tag: 0.4.9
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

See [WordPress Usage](README.md#wordpress-usage).

== Screenshots ==

1. Sticky positioning even when JavaScript is disabled ./images/screenshots/noscript-sticky.png
1. Dynamic date context, highlighting, additional content injection ./images/screenshots/dynamic-date-highlighting-additional-content-injection.png

== Changelog ==

= 0.4.9 =
* [2815d17] Add shortcode option to allow widgets to be excluded from the generated anchor links list on the maintenance page, update documentation

= 0.4.8 =
* [e8b04af] Update wpdtrt-scss to 0.1.17
* [b9f6051] Refactor jump links/anchors to use the heading rather than the section, show the icon on the left and include the text in the link/anchor, shorten data attributes (#24, dotherightthing/wpdtrt-scss#2)
* [f39431b] Update wpdtrt-scss to 0.1.14
* [a817052] Refactor jump links/anchors to use the heading rather than the section, show the icon on the left and include the text in the link/anchor, shorten data attributes (#24, dotherightthing/wpdtrt-scss#2)
* [584b138] Prevent active fragment from headbutting the top of the browser window
* [5d0a6be] Update wpdtrt-scss to 0.1.13

= 0.4.7 =
* [d48abe7] Update wpdtrt-plugin-boilerplate from 1.7.16 to 1.7.17
* [f1b81be] Sync comment with plugin generator

= 0.4.6 =
* [0c09c46] Update wpdtrt-plugin-boilerplate from 1.7.15 to 1.7.16
* [91bdeaa] Remove redundant classes

= 0.4.5 =
* [921b64c] Docs
* [8010123] Update wpdtrt-npm-scripts to 0.3.30
* [59a36ff] Update dependencies
* [7f60057] Update wpdtrt-scss
* [2b49062] Update wpdtrt-plugin-boilerplate from 1.7.14 to 1.7.15
* [f3c0ea4] Remove redundant loading of project-specific JavaScript
* [e8dc35d] Update wpdtrt-plugin-boilerplate from 1.7.13 to 1.7.14
* [2624d18] Update wpdtrt-plugin-boilerplate from 1.7.12 to 1.7.13
* [b40cc23] Fix documented path to CSS variables
* [d81cb2b] Add placeholders for string replacements
* [bfbd404] Load boilerplate JS, as it is not compiled by the boilerplate

= 0.4.4 =
* [0c20dda] Update wpdtrt-plugin-boilerplate from 1.7.7 to 1.7.12
* [591d392] Move styles to wpdtrt-scss
* [b0395fd] Support multiple pinning controllers
* [ecc6849] Docs
* [108b040] Move styles to wpdtrt-scss
* [1884709] Ignore files sources from wpdtrt-npm-scripts

= 0.4.3 =
* [7fe633e] Fix Undefined index: sidebar-
* [624cc5c] Add ability to inject anchor into a heading which is not within the_content
* [27ba136] Add ability to inject additional items into anchor list, sourced from widgets in sidebars appearing before or after the_content
* [c37fc2d] Housekeeping

= 0.4.2 =
* [0315bec] Fix JQMIGRATE: jQuery.trim is deprecated; use String.prototype.trim
* [5a63a1c] Revert "Ensure that highlighted anchor link is always in view (fixes #23)"
* [5eaad3e] Ensure that highlighted anchor link is always in view (fixes #23)

= 0.4.1 =
* [90b8f03] Update dependencies, incl wpdtrt-plugin-boilerplate from 1.7.6 to 1.7.7 to use Composer v1
* [95422e9] Add polyfills for IE11
* [5acf5b2] Add icons and underlines to anchor list links
* [cc14509] Update wpdtrt-plugin-boilerplate from 1.7.5 to 1.7.6 to fix saving of admin field values
* [59e4206] Update section element in anchor link highlighting script (fixes #20)
* [35b4ef5] Pass wpdtrt-anchorlinks__additions in as a shortcode attribute
* [7a51600] Prevent additional <p></p> element from being injected after h2
* [058da3f] Prepend anchor IDs with a string to prevent them from starting with a number
* [110a172] Append date to nav header (fixes #19)

= 0.4.0 =
* Use ARIA rather than CSS to control un/sticky title visibility to prevent both strings being output (fixes #17)

= 0.3.9 =
* Apply separate margin variable when anchor links list is pinned
* Use CSS variables, compile CSS variables to separate file
* Update wpdtrt-npm-scripts to fix release
* Update wpdtrt-plugin-boilerplate to 1.7.5 to support CSS variables

= 0.3.8 =
* Update docs
* Update heading level in tests
* Fix heading level
* Fix gap between anchor link and heading text

= 0.3.7 =
* Update wpdtrt-npm-scripts to fix folder targeting for release job, bump version

= 0.3.6 =
* Update wpdtrt-npm-scripts to add debugging information to build

= 0.3.5 =
* Update wpdtrt-npm-scripts to fix expected path to CHANGELOG.md

= 0.3.4 =
* Replace Github generated changelog with locally generated one

= 0.3.3 =
* Switch to Github action for changelog generation, as the changelog generated by wpdtrt-npm-scripts is empty

= 0.3.2 =
* Fix missing release changelog

= 0.3.1 =
* Fix missing release changelog

= 0.3.0 =
* Remove better-anchor-links dependency
* Remove wpdtrt-contentsections dependency
* Replace http://imakewebthings.com/waypoints/ with position:sticky and Intersection Observers
* Replace gulp build scripts with wpdtrt-npm-scripts

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
