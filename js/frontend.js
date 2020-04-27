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
     * @function getRelatedNavigation
     * @summary Get link by section or article id
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     *
     * @param {external:jQuery} el - anchor element
     * @returns {external:jQuery} link element
     */
    getNavigation: () => {
        let $ = wpdtrtAnchorlinksUi.jQuery;

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
        let $ = wpdtrtAnchorlinksUi.jQuery;

        return $(`.wpdtrt-anchorlinks__list-link[href='#${$(el).attr('id')}']`);
    },

    /**
     * @function injectSummaryLink
     * @summary Inject the summary section into the nav.
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     *
     * @param {external:jQuery} $jumpMenu - .wpdtrt-anchorlinks
     */
    injectSummaryLink: ($jumpMenu) => {
        const $firstItem = $jumpMenu.find('.wpdtrt-anchorlinks__list-item').eq(0);
        let summaryItem = '';

        summaryItem += '<li class="wpdtrt-anchorlinks__list-item">';
        summaryItem += '<a href="#summary" class="wpdtrt-anchorlinks__list-link">';
        summaryItem += 'Introduction';
        summaryItem += '</a>';
        summaryItem += '</li>';

        $firstItem.before(summaryItem);
    },

    /**
     * @function showScrollProgress
     * @summary Resize the indicator according to the scroll progress
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     */
    showScrollProgress: () => {
        let $ = wpdtrtAnchorlinksUi.jQuery;

        const $title = $('.wpdtrt-anchorlinks__title');
        const $links = $('.wpdtrt-anchorlinks__list-link');
        const linksCount = $links.length;
        const $linkActive = $('.wpdtrt-anchorlinks__list-link.active');
        const linksActiveIndex = $links.index($linkActive) + 1;
        let pctThru = (linksActiveIndex / linksCount) * 100;
        const $scrollProgress = $('.wpdtrt-anchorlinks__scroll-progress');

        if (!$scrollProgress.length) {
            $title.append('<div class="wpdtrt-anchorlinks__scroll-progress"></div>');
        }

        // if we're in a section, show how far through we are
        // else assume that we're all the way through
        if (linksActiveIndex < 1) {
            pctThru = 100;
        }

        $('.wpdtrt-anchorlinks__scroll-progress').css('width', `${pctThru}%`);
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
    highlightAnchorLink: (changes, observer) => {
        let $ = wpdtrtAnchorlinksUi.jQuery;
        let $anchorLinks = wpdtrtAnchorlinksUi.getNavigation();

        changes.forEach(change => {
            let intersectingElement = change.target;

            // ratio of the element which is visible in the viewport
            // (entering or leaving)
            if (change.intersectionRatio > 0.5) {
                let $anchorLinkActive = wpdtrtAnchorlinksUi.getRelatedNavigation(intersectingElement);

                $anchorLinks.removeClass('active');
                $anchorLinkActive.addClass('active');

                wpdtrtAnchorlinksUi.showScrollProgress();
            }
        });
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
    pinAnchorLinksList: (changes, observer) => {
        let $ = wpdtrtAnchorlinksUi.jQuery;
        let $stickyTarget = $('.wpdtrt-anchorlinks__site-sticky-target');
        let stickyClass = 'wpdtrt-anchorlinks__site-sticky';

        changes.forEach(change => {
            let intersectingElement = change.target;

            // ratio of the element which is visible in the viewport
            // (entering or leaving)
            if (change.intersectionRatio > 0.1) {
                $stickyTarget.removeClass(stickyClass);
            } else if (change.intersectionRatio <= 0.1) {
                $stickyTarget.addClass(stickyClass);
            }
        });
    },

    /**
     * @function toggleAnchorLinksList
     * @summary Hide the anchor links list when the footer is in view.
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     *
     * @param {object} changes - Observed changes
     * @param {object} observer - Intersection Observer
     */
    toggleAnchorLinksList: (changes, observer) => {
        let $ = wpdtrtAnchorlinksUi.jQuery;
        let $fadeTarget = $('.wpdtrt-anchorlinks__site');

        changes.forEach(change => {
            let intersectingElement = change.target;

            // ratio of the element which is visible in the viewport
            // (entering or leaving)
            if (change.intersectionRatio > 0.1) {
                $fadeTarget.fadeOut(500);
            } else if (change.intersectionRatio <= 0.1) {
                $fadeTarget.fadeIn(500);
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
        let $ = wpdtrtAnchorlinksUi.jQuery;

        if (!$jumpMenu.length) {
            return;
        }

        wpdtrtAnchorlinksUi.injectSummaryLink($jumpMenu);

        const $anchors = $('.wpdtrt-anchorlinks__anchor');

        if ($anchors.length) {
            if ('IntersectionObserver' in window) {
                const highlightAnchorLinkObserver = new IntersectionObserver(wpdtrtAnchorlinksUi.highlightAnchorLink, {
                    root: null, // relative to document viewport
                    rootMargin: '0px', // margin around root, unitless values not allowed
                    threshold: 0.25 // visible amount of item shown in relation to root
                });

                $anchors.each((i, item) => {
                    // add element to the set being watched by the IntersectionObserver
                    highlightAnchorLinkObserver.observe($(item).get(0));
                });

                const pinAnchorLinksListObserver = new IntersectionObserver(wpdtrtAnchorlinksUi.pinAnchorLinksList, {
                    root: null, // relative to document viewport
                    rootMargin: '0px', // margin around root, unitless values not allowed
                    threshold: 0.1 // visible amount of item shown in relation to root
                });

                pinAnchorLinksListObserver.observe($('.stack--banner').get(0));

                const toggleAnchorLinksListObserver = new IntersectionObserver(wpdtrtAnchorlinksUi.toggleAnchorLinksList, {
                    root: null, // relative to document viewport
                    rootMargin: '0px', // margin around root, unitless values not allowed
                    threshold: 0.1 // visible amount of item shown in relation to root
                });

                toggleAnchorLinksListObserver.observe($('.site-footer').get(0));
            }

            // $anchors.waypoint(
            //     function (direction) { // eslint-disable-line func-names
            //         // Highlight element when related content
            //         // is 10% percent from the bottom...
            //         // remove if below
            //         wpdtrtAnchorlinksUi.getRelatedNavigation(this.element).toggleClass('active', direction === 'down');
            //         wpdtrtAnchorlinksUi.showScrollProgress();
            //     },
            //     {
            //         offset: '90%'
            //     }
            // );

            // $anchors.waypoint(
            //     function (direction) { // eslint-disable-line func-names
            //         // Highlight element when bottom of related content
            //         // is 100px from the top - remove if less
            //         wpdtrtAnchorlinksUi.getRelatedNavigation(this.element).toggleClass('active', direction === 'up');
            //         wpdtrtAnchorlinksUi.showScrollProgress();
            //     },
            //     {
            //         offset: function () {
            //             return -$(this.element).height() + 100;
            //         }
            //     }
            // );
        }
    },

    /**
     * @function init
     * @summary Initialise the component
     * @memberof wpdtrtAnchorlinksUi
     * @public
     *
     * @param {external:jQuery} $ - jQuery
     */
    init: () => { // called from footer config script block
        let $ = wpdtrtAnchorlinksUi.jQuery;

        // https://web-design-weekly.com/snippets/scroll-to-position-with-jquery/
        $.fn.scrollView = function (offset, duration) { // eslint-disable-line func-names
            return this.each(function () { // eslint-disable-line func-names
                $('html, body').animate({
                    scrollTop: $(this).offset().top - offset
                }, duration);
            });
        };

        wpdtrtAnchorlinksUi.sticky_jump_menu($('.wpdtrt-anchorlinks'));

        console.log('wpdtrtAnchorlinksUi.init'); // eslint-disable-line no-console
    }
};

jQuery(document).ready(($) => {
    const config = wpdtrt_anchorlinks_config; // eslint-disable-line camelcase
    wpdtrtAnchorlinksUi.jQuery = $;
    wpdtrtAnchorlinksUi.init();
});
