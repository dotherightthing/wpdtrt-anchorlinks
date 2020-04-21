/**
 * @file DTRT Anchor Links frontend.js
 * @summary
 *     Front-end scripting for public pages
 *     PHP variables are provided in `wpdtrt_anchorlinks_config`.
 * @version 0.0.1
 * @see 0.7.0 DTRT WordPress Plugin Boilerplate Generator
 */

/* eslint-env browser */
/* global jQuery, wpdtrt_anchorlinks_config, Waypoint */
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
    getRelatedNavigation: ($, el) => $(`.wpdtrt-anchorlinks__list-link[href='#${$(el).attr('id')}']`),

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
        summaryItem += '<a href="#summary" class="wpdtrt-anchorlinks__list-link">'
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
     *
     * @param {external:jQuery} $ - jQuery
     */
    showScrollProgress: ($) => {
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
     * @function wrapMenu
     * @summary Add wrappers to allow the pinned menu bar to be positioned within a page-like construct.
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     *
     * @param {external:jQuery} $ - jQuery
     * @param {external:jQuery} $jumpMenu - .wpdtrt-anchorlinks
     * @returns {external:jQuery} .wpdtrt-anchorlinks__site
     */
    wrapMenu: ($, $jumpMenu) => {
        let html = '';
        html += '<div class="wpdtrt-anchorlinks__site">';
        html += '<div class="wpdtrt-anchorlinks__site-inner">';
        html += '<div class="wpdtrt-anchorlinks__site-content">';
        html += '<div class="wpdtrt-anchorlinks__entry-content">';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        $jumpMenu.wrap(html);

        return $('.wpdtrt-anchorlinks__site');
    },

    /**
     * @function sticky_jump_menu
     * @summary Inject wrappers required for fixed positioning, manage scroll link highlighting
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     *
     * @param {external:jQuery} $ - jQuery
     * @param {external:jQuery} $jumpMenu - Jump menu
     */
    sticky_jump_menu: ($, $jumpMenu) => {
        if (!$jumpMenu.length) {
            return;
        }

        wpdtrtAnchorlinksUi.injectSummaryLink($jumpMenu);

        const $wrapper = wpdtrtAnchorlinksUi.wrapMenu($, $jumpMenu);

        // removed as this makes for a terrible tab order
        // var $summary = $('.entry-summary-wrapper');
        // $summary.after($wrapper);

        /*
         * highlight active nav item on scroll
         * http://codepen.io/jakob-e/pen/mhCyx
         * this changed to this.element to resolve error
         * 'Uncaught TypeError: Cannot read property 'defaultView' of undefined'
         * refer: http://stackoverflow.com/a/3936230
         * refer: http://imakewebthings.com/waypoints/guides/jquery-zepto/#fn-extension
         * --
         * + DTRT make sticky
         */

        const $anchors = $('.wpdtrt-anchorlinks__anchor');

        if ($anchors.length) {
            const sticky = new Waypoint.Sticky({
                element: $wrapper[0],
                stuckClass: 'sticky'
            });

            $anchors.waypoint(
                function (direction) { // eslint-disable-line func-names
                    // Highlight element when related content
                    // is 10% percent from the bottom...
                    // remove if below
                    wpdtrtAnchorlinksUi.getRelatedNavigation($, this.element).toggleClass('active', direction === 'down');
                    wpdtrtAnchorlinksUi.showScrollProgress($);
                },
                {
                    offset: '90%'
                }
            );

            $anchors.waypoint(
                function (direction) { // eslint-disable-line func-names
                    // Highlight element when bottom of related content
                    // is 100px from the top - remove if less
                    wpdtrtAnchorlinksUi.getRelatedNavigation($, this.element).toggleClass('active', direction === 'up');
                    wpdtrtAnchorlinksUi.showScrollProgress($);
                },
                {
                    offset: function () {
                        return -$(this.element).height() + 100;
                    }
                }
            );

            $('.site-footer').waypoint(
                (direction) => {
                    if (direction === 'down') {
                        $('.sticky').fadeOut(500);
                    } else {
                        $('.sticky').fadeIn(500);
                    }
                },
                {
                    offset: function () {
                        return $('.wpdtrt-anchorlinks').height();
                    }
                }
            );
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
    init: ($) => { // called from footer config script block
        // https://web-design-weekly.com/snippets/scroll-to-position-with-jquery/
        $.fn.scrollView = function (offset, duration) { // eslint-disable-line func-names
            return this.each(function () { // eslint-disable-line func-names
                $('html, body').animate({
                    scrollTop: $(this).offset().top - offset
                }, duration);
            });
        };

        wpdtrtAnchorlinksUi.sticky_jump_menu($, $('.wpdtrt-anchorlinks')); 

        console.log('wpdtrtAnchorlinksUi.init'); // eslint-disable-line no-console
    }
};

jQuery(document).ready(($) => {
    const config = wpdtrt_anchorlinks_config; // eslint-disable-line camelcase
    wpdtrtAnchorlinksUi.init($);
});
