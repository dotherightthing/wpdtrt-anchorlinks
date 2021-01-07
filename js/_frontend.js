/**
 * @file DTRT Anchor Links frontend.js
 * @summary
 *     Front-end scripting for public pages
 *     PHP variables are provided in `wpdtrt_anchorlinks_config`.
 * @version 0.0.1
 * @see 0.7.0 DTRT WordPress Plugin Boilerplate Generator
 */

/* eslint-env browser */
/* global jQuery, wpdtrt_anchorlinks_config */
/* eslint-disable no-unused-vars, max-len, require-jsdoc */

/**
 * jQuery object
 *
 * @external jQuery
 * @see {@link http://api.jquery.com/jQuery/}
 */

/**
 * @namespace wpdtrtAnchorlinksUi
 */
const wpdtrtAnchorlinksUi = {

    /**
     * @function getAnchorLinks
     * @summary Get list of anchor links
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     *
     * @returns {external:jQuery} link list
     */
    getAnchorLinks: () => {
        const $ = wpdtrtAnchorlinksUi.jQuery;

        return $('.wpdtrt-anchorlinks__list-link');
    },

    /**
     * @function getRelatedNavigation
     * @summary Get link by section or article id
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     *
     * @param {external:jQuery} el - anchor element
     * @returns {external:jQuery} link element
     */
    getRelatedNavigation: (el) => {
        const $ = wpdtrtAnchorlinksUi.jQuery;

        return $(`.wpdtrt-anchorlinks__list-link[href='#${$(el).attr('id')}']`);
    },

    /**
     * @function initPolyfills
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     */
    initPolyfill: () => {
        /* eslint-disable */

        // Polyfill for forEach
        if (window.NodeList && !NodeList.prototype.forEach) {
            NodeList.prototype.forEach = Array.prototype.forEach;
        }

        // Polyfill for forEach
        if (window.HTMLCollection && !HTMLCollection.prototype.forEach) {
            HTMLCollection.prototype.forEach = Array.prototype.forEach;
        }

        /* eslint-enable */
    },

    /**
     * @function setTitleToSummary
     * @summary Inject the summary section (outside of the page content) into the nav.
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     *
     * @param {external:jQuery} $jumpMenu - .wpdtrt-anchorlinks
     */
    setTitleToSummary: ($jumpMenu) => {
        const $ = wpdtrtAnchorlinksUi.jQuery;
        const anchor = '#summary';
        const $anchor = $(anchor);
        const $title = $jumpMenu.find('.wpdtrt-anchorlinks__title-fixed').eq(0);
        const { highlightController } = wpdtrtAnchorlinksUi.domElements;
        let anchorTextAbbreviated;
        let stickyTitle = '';

        if ($anchor.length && $anchor.is(highlightController)) {
            anchorTextAbbreviated = $anchor.find('h2').attr('data-abbreviation');

            if (anchorTextAbbreviated) {
                const abbreviations = [
                    [ 'Mon', 'Monday' ],
                    [ 'Tue', 'Tuesday' ],
                    [ 'Wed', 'Wednesday' ],
                    [ 'Thu', 'Thursday' ],
                    [ 'Fri', 'Friday' ],
                    [ 'Sat', 'Saturday' ],
                    [ 'Sun', 'Sunday' ],
                    [ 'Jan', 'January' ],
                    [ 'Feb', 'February' ],
                    [ 'Mar', 'March' ],
                    [ 'Apr', 'April' ],
                    [ 'May', 'May' ],
                    [ 'Jun', 'June' ],
                    [ 'Jul', 'July' ],
                    [ 'Aug', 'August' ],
                    [ 'Sep', 'September' ],
                    [ 'Oct', 'October' ],
                    [ 'Nov', 'November' ],
                    [ 'Dec', 'December' ]
                ];

                abbreviations.forEach(abbreviation => {
                    anchorTextAbbreviated = anchorTextAbbreviated
                        .replace(abbreviation[0], `<abbr title="${abbreviation[1]}">${abbreviation[0]}</abbr>`);
                });

                stickyTitle += `<a href="${anchor}" class="wpdtrt-anchorlinks__title-sticky wpdtrt-anchorlinks__list-link" aria-hidden="true">`;
                stickyTitle += '<span class="wpdtrt-anchorlinks__list-link-liner">';
                stickyTitle += $.trim(anchorTextAbbreviated);
                stickyTitle += '</span>';
                stickyTitle += '</a>';
            }

            $title.append(stickyTitle);
        }
    },

    /**
     * @function setListMaxHeight
     * @summary Calculate the height available to the anchor list.
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     *
     * @param {number} maxHeight - max height
     */
    setListMaxHeight: (maxHeight) => {
        const $ = wpdtrtAnchorlinksUi.jQuery;
        const $list = $('.wpdtrt-anchorlinks__list');

        if (typeof maxHeight !== 'undefined') {
            $list.css({
                'max-height': '',
                'overflow-y': ''
            });
        } else {
            const $additions = $('.wpdtrt-anchorlinks__additions');
            const $container = $('.wpdtrt-anchorlinks');
            const additionsHeight = $additions.outerHeight(true);
            const containerBottom = parseInt($container.css('margin-bottom'), 10);
            const listTop = $list.get(0).getBoundingClientRect().top;
            const listBottom = parseInt($list.css('margin-bottom'), 10);
            let nonListHeight = listTop + additionsHeight + listBottom + containerBottom;

            $list.css({
                'max-height': `calc(100vh - ${nonListHeight}px)`,
                'overflow-y': 'auto'
            });
        }
    },

    /**
     * @function showScrollProgress
     * @summary Resize the indicator according to the scroll progress
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     */
    showScrollProgress: () => {
        const $ = wpdtrtAnchorlinksUi.jQuery;

        const $links = $('.wpdtrt-anchorlinks__list-link');
        const $linkActive = $('.wpdtrt-anchorlinks__list-link-active');
        const linksActiveIndex = $links.index($linkActive) + 1;
        const linksCount = $links.length;
        const $scrollProgressBar = $('.wpdtrt-anchorlinks__scroll-progress-bar');
        let pctThru = (linksActiveIndex / linksCount) * 100;

        // if we're in a section, show how far through we are
        // else assume that we're all the way through
        if (linksActiveIndex < 1) {
            pctThru = 100;
        }

        $scrollProgressBar.css('width', `${pctThru}%`);
    },

    /**
     * @function highlightAnchorLink
     * @summary Highlight an anchor link, when its target is in view.
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     *
     * @param {object} changes - Observed changes
     * @param {object} observer - Intersection Observer
     */
    highlightAnchorLink: (changes, observer) => { // eslint-disable-line no-unused-vars
        const $ = wpdtrtAnchorlinksUi.jQuery;

        changes.forEach(change => {
            let intersectingElement = change.target;

            // ratio of the element which is visible in the viewport
            // (entering or leaving)
            if (change.intersectionRatio > 0.5) {
                let $anchor = $(intersectingElement);
                let anchor = $anchor.get(0);
                let $anchorLinkActive = wpdtrtAnchorlinksUi.getRelatedNavigation(anchor);

                wpdtrtAnchorlinksUi.unhighlightAnchorLinks();

                $anchorLinkActive.addClass('wpdtrt-anchorlinks__list-link-active');

                wpdtrtAnchorlinksUi.showScrollProgress();
            }
        });
    },

    /**
     * @function clickedAnchorLink
     * @summary Highlight the clicked anchor links.
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     *
     * @param {external:jQuery} $clickedAnchorLink - Clicked link.
     */
    clickedAnchorLink: ($clickedAnchorLink) => {
        setTimeout(() => {
            wpdtrtAnchorlinksUi.unhighlightAnchorLinks();
            $clickedAnchorLink.addClass('wpdtrt-anchorlinks__list-link-active');
        }, 250);
    },

    /**
     * @function unhighlightAnchorLinks
     * @summary Unhighlight all anchor links.
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     */
    unhighlightAnchorLinks: () => {
        const $anchorLinks = wpdtrtAnchorlinksUi.getAnchorLinks();
        $anchorLinks.removeClass('wpdtrt-anchorlinks__list-link-active');
    },

    /**
     * @function pinAnchorLinksList
     * @summary Pin the anchor link list when it is scrolled out of view.
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     *
     * @param {object} changes - Observed changes
     * @param {object} observer - Intersection Observer
     */
    pinAnchorLinksList: (changes, observer) => { // eslint-disable-line no-unused-vars
        const $ = wpdtrtAnchorlinksUi.jQuery;
        const $stickyTarget = $('.wpdtrt-anchorlinks');
        const stickyClass = 'wpdtrt-anchorlinks--sticky';
        const $stickyTitle = $('.wpdtrt-anchorlinks__title-sticky');
        const $unstickyTitle = $('.wpdtrt-anchorlinks__title-unsticky');

        changes.forEach(change => {
            // ratio of the element which is visible in the viewport
            // (entering or leaving)
            if (change.intersectionRatio > 0.1) {
                $stickyTarget.removeClass(stickyClass);
                $stickyTitle.attr('aria-hidden', true);
                $unstickyTitle.removeAttr('aria-hidden');
                wpdtrtAnchorlinksUi.setListMaxHeight(0);
            } else if (change.intersectionRatio <= 0.1) {
                $stickyTarget.addClass(stickyClass);
                $stickyTitle.removeAttr('aria-hidden');
                $unstickyTitle.attr('aria-hidden', true);
                wpdtrtAnchorlinksUi.setListMaxHeight();
            }
        });
    },

    /**
     * @function sticky_jump_menu
     * @summary Inject wrappers required for fixed positioning, manage scroll link highlighting
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     *
     * @param {external:jQuery} $jumpMenu - Jump menu
     *
     * @see {@link http://codepen.io/jakob-e/pen/mhCyx}
     */
    sticky_jump_menu: ($jumpMenu) => {
        const $ = wpdtrtAnchorlinksUi.jQuery;

        wpdtrtAnchorlinksUi.initPolyfill();

        if (!$jumpMenu.length) {
            return;
        }

        wpdtrtAnchorlinksUi.setTitleToSummary($jumpMenu);

        const $anchors = $('.wpdtrt-anchorlinks__anchor');
        const $anchorLinks = wpdtrtAnchorlinksUi.getAnchorLinks();
        const { highlightController, pinController } = wpdtrtAnchorlinksUi.domElements;

        if ($anchors.length) {
            if ('IntersectionObserver' in window) {
                const highlightAnchorLinkObserver = new IntersectionObserver(wpdtrtAnchorlinksUi.highlightAnchorLink, {
                    root: null, // relative to document viewport
                    rootMargin: '0px', // margin around root, unitless values not allowed
                    threshold: 0.5 // visible amount of item shown in relation to root
                });

                highlightController.each((i, item) => {
                    // add element to the set being watched by the IntersectionObserver
                    highlightAnchorLinkObserver.observe($(item).get(0));
                });

                // reset highlighted item on click
                $anchorLinks.on('click', event => {
                    const $clickedElement = $(event.target);
                    const $targetElement = $clickedElement.closest('a');

                    wpdtrtAnchorlinksUi.clickedAnchorLink($targetElement);
                });

                // the actual pinning is done with position:sticky
                // but this gives us control over the max-height

                if (pinController.length) {
                    const pinAnchorLinksListObserver = new IntersectionObserver(wpdtrtAnchorlinksUi.pinAnchorLinksList,
                        {
                            root: null, // relative to document viewport
                            rootMargin: '0px', // margin around root, unitless values not allowed
                            threshold: 0.1 // visible amount of item shown in relation to root
                        });

                    pinAnchorLinksListObserver.observe(pinController.get(0));
                }
            }
        }
    },

    /**
     * @function init
     * @summary Initialise the component
     * @memberof wpdtrtAnchorlinksUi
     * @public
     */
    init: () => { // called from footer config script block
        const $ = wpdtrtAnchorlinksUi.jQuery;

        wpdtrtAnchorlinksUi.domElements = {
            highlightController: $('[data-wpdtrt-anchorlinks-controls="highlighting"]'),
            pinController: $('[data-wpdtrt-anchorlinks-controls="pinning"]')
        };

        wpdtrtAnchorlinksUi.sticky_jump_menu($('.wpdtrt-anchorlinks'));

        console.log('wpdtrtAnchorlinksUi.init'); // eslint-disable-line no-console
    }
};

jQuery(document).ready(($) => {
    const config = wpdtrt_anchorlinks_config; // eslint-disable-line camelcase, no-unused-vars
    wpdtrtAnchorlinksUi.jQuery = $;
    wpdtrtAnchorlinksUi.init();
});
