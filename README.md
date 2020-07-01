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

### Control the dynamic pinning of the anchor links

Pinning keeps the navigation in view, while the rest of the page content is scrolled.

The dynamic pinning is controlled by CSS `position:sticky` in the plugin stylesheet. The element will be pinned when it is scrolled out of the viewport (requires modern browser or MS Edge 15+).

### Control the dynamic content in the anchor links

Dynamic content includes:

* replacement of the anchor links list title with the summary heading
* highlighting of the anchor link corresponding to the content section currently in view
* injection of theme elements below the anchor links list

Dynamic content is implemented using Intersection Observers (requires JavaScript / modern browser or MS Edge 15+).

Pinning toggles a class of `.wpdtrt-anchorlinks--sticky` on `.wpdtrt-anchorlinks`.

To control dynamic content, add the following data attribute to an element:

```html
data-wpdtrt-anchorlinks-controls="pinning"
```

* When this element is scrolled out of the viewport, the 'pinned' layout state will be enhanced
* When this element is scrolled into the viewport, 'the 'pinned' layout state will be unenhanced

### Control the dynamic highlighting of the anchor links

Add the following data attribute to a the relevant child of each anchor:

```html
data-wpdtrt-anchorlinks-controls="highlighting"
```

* When this element is scrolled into the viewport, the matching anchor link will be highlighted.
* When another element is scrolled into the viewport,
  * the previous matching anchor link will be unhighlighted
  * the new matching anchor link will be highlighted

### Inject a theme element after the anchor list

Add the following data attribute to the element:

```html
data-wpdtrt-anchorlinks-list-addition-clone="false"
data-wpdtrt-anchorlinks-list-addition="1"
```

* This element will be removed from its current location and injected after the list
* If there are multiple elements to inject, this element will be injected first, as it has an id of `1`

```html
data-wpdtrt-anchorlinks-list-addition-clone="true"
data-wpdtrt-anchorlinks-list-addition="2"
```

* This element will stay at its current location and a copy (clone) will be injected after the list
* If there are multiple elements to inject, this element will be injected second, as it has an id of `2`

### Styling

Core CSS properties may be overwritten by changing the variable values in your local stylesheet.

See `scss/_variables.scss`.
