# DTRT Anchor Links

[![GitHub release](https://img.shields.io/github/v/tag/dotherightthing/wpdtrt-anchorlinks)](https://github.com/dotherightthing/wpdtrt-anchorlinks/releases) [![Build Status](https://github.com/dotherightthing/wpdtrt-anchorlinks/workflows/Build%20and%20release%20if%20tagged/badge.svg)](https://github.com/dotherightthing/wpdtrt-anchorlinks/actions?query=workflow%3A%22Build+and+release+if+tagged%22) [![GitHub issues](https://img.shields.io/github/issues/dotherightthing/wpdtrt-anchorlinks.svg)](https://github.com/dotherightthing/wpdtrt-anchorlinks/issues)

Anchor links plugin.

## Setup and Maintenance

Please read [DTRT WordPress Plugin Boilerplate: Workflows](https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Workflows).

## WordPress Installation

Please read the [WordPress readme.txt](readme.txt).

## WordPress Usage

### Display the list of anchor links

Within the editor:

```txt
[wpdtrt_anchorlinks_shortcode title_text="Jump links"]
```

In a PHP template, as a template tag:

```php
<?php
    echo do_shortcode( '[wpdtrt_anchorlinks_shortcode title_text="Jump links"]' );
?>
```

### Inject an HTML string below the list

Add the following parameter to the shortcode:

```php
# HTML to appear in .wpdtrt-anchorlinks__additions
additional_html='<div>Some HTML</div>'
```

### Inject widget titles into the list, from a widget sidebar which resides outside of the_content

Add the following parameters to the shortcode:

```php
# Sidebar ID to source widgets from
additional_from_sidebar_id_1='content-top'

# DOM order of sidebar relative to the_content (0)
# A negative value means that the sidebar appears before the_content.
additional_from_sidebar_order_1='-1'

# Exclude widgets titles from e.g. the maintenance page
# Comma separated list of page IDs
exclude_widgets_on_pages='12345, 67890'
```

### Control the dynamic pinning of the anchor links

Pinning keeps the navigation in view, while the rest of the page content is scrolled.

The dynamic pinning is controlled by CSS `position:sticky` in the plugin stylesheet. The element will be pinned when it is scrolled out of the viewport (requires modern browser or MS Edge 15+).

### Control the dynamic content in the anchor links

Dynamic content includes:

* replacement of the anchor links list title with the summary heading
* highlighting of the anchor link corresponding to the content section currently in view

Dynamic content is implemented using Intersection Observers (requires JavaScript / modern browser or MS Edge 15+).

Pinning toggles a class of `.wpdtrt-anchorlinks--sticky` on `.wpdtrt-anchorlinks`.

To control dynamic content, add the following attribute to the shortcode element:

```html
data-anchorlinks-controls="pinning"
```

* When this element is scrolled out of the viewport, the 'pinned' layout state will be enhanced
* When this element is scrolled into the viewport, 'the 'pinned' layout state will be unenhanced

### Control the dynamic highlighting of the anchor links

Add the following data attribute to a the relevant child of each anchor:

```html
data-anchorlinks-controls="highlighting"
```

* When this element is scrolled into the viewport, the matching anchor link will be highlighted.
* When another element is scrolled into the viewport,
  * the previous matching anchor link will be unhighlighted
  * the new matching anchor link will be highlighted

### Styling

Core CSS properties may be overwritten by changing the variable values in your theme stylesheet.

See `scss/variables/_css.scss`.
