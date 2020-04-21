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
     * @function sticky_jump_menu
     * @summary Inject wrappers required for fixed positioning, manage scroll link highlighting
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     *
     * @param {external:jQuery} $ - jQuery
     */
    sticky_jump_menu: ($) => {
        const $jumpMenu = $('.wpdtrt-anchorlinks');

        if (!$jumpMenu.length) {
            return;
        }

        // inject the summary section into the nav
        const $firstItem = $jumpMenu.find('.wpdtrt-anchorlinks__list-item').eq(0);
        let summaryItem = '';
        summaryItem += '<li class="wpdtrt-anchorlinks__list-item">';
        summaryItem += '<a class="wpdtrt-anchorlinks__list-link" href="#summary">'
        summaryItem += 'Introduction';
        summaryItem += '</a>';
        summaryItem += '</li>';

        $firstItem.before(summaryItem);

        // the wrapper is assigned critical dimensions as the page, and then fixed to the top
        // this allows the actual menu bar to be positioned within a page like construct
        let html = '';
        html += '<div class=\'wpdtrt_anchorlinks__site\'>';
        html += '<div class=\'wpdtrt_anchorlinks__site-inner\'>';
        html += '<div class=\'wpdtrt_anchorlinks__site-content\'>';
        html += '<div class=\'wpdtrt_anchorlinks__entry-content\'>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        $jumpMenu.wrap(html);

        const $jumpMenuLayout = $('.wpdtrt-anchorlinks__site');

        // removed as this makes for a terrible tab order
        // var $summary = $('.entry-summary-wrapper');
        // $summary.after($jumpMenuLayout);

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

        /**
         * @function getRelatedNavigation
         * @summary Get link by section or article id
         * @memberof wpdtrtAnchorlinksUi
         * @protected
         *
         * @param {external:jQuery} el - anchor element
         * @returns {external:jQuery} link element
         */
        function getRelatedNavigation(el) {
            return $(`.wpdtrt-anchorlinks__list-link[href='#${$(el).attr('id')}']`);
        }

        /**
         * @function showScrollProgress
         * @summary Resize the indicator according to the scroll progress
         * @memberof wpdtrtAnchorlinksUi
         * @protected
         */
        function showScrollProgress() {
            const $mwmTitle = $('.wpdtrt-anchorlinks__title');
            const $mwmLinks = $('.wpdtrt-anchorlinks__list-link');
            const mwmLinksCount = $mwmLinks.length;
            const $mwmLinkActive = $('.wpdtrt-anchorlinks__list-link.active');
            const mwmLinksActiveIndex = $mwmLinks.index($mwmLinkActive) + 1;
            let pctThru = (mwmLinksActiveIndex / mwmLinksCount) * 100;
            const $scrollProgress = $('.wpdtrt-anchorlinks__scroll-progress');

            if (!$scrollProgress.length) {
                $mwmTitle.append('<div class=\'wpdtrt_anchorlinks__scroll-progress\'></div>');
            }

            // if we're in a section, show how far through we are
            // else assume that we're all the way through
            if (mwmLinksActiveIndex < 1) {
                pctThru = 100;
            }

            $('.wpdtrt-anchorlinks__scroll-progress').css('width', `${pctThru}%`);
        }

        if ($('.wpdtrt-anchorlinks__anchor').length) {
            const sticky = new Waypoint.Sticky({
                element: $jumpMenuLayout[0],
                stuckClass: 'sticky'
            });

            $('.wpdtrt-anchorlinks__anchor').waypoint(
                function (direction) { // eslint-disable-line func-names
                    // Highlight element when related content
                    // is 10% percent from the bottom...
                    // remove if below
                    getRelatedNavigation(this.element).toggleClass('active', direction === 'down');
                    showScrollProgress();
                },
                {
                    offset: '90%'
                }
            );

            $('.wpdtrt-anchorlinks__anchor').waypoint(
                function (direction) { // eslint-disable-line func-names
                    // Highlight element when bottom of related content
                    // is 100px from the top - remove if less
                    getRelatedNavigation(this.element).toggleClass('active', direction === 'up');
                    showScrollProgress();
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
     * @function showScrollProgress
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

        wpdtrtAnchorlinksUi.sticky_jump_menu($);

        console.log('wpdtrtAnchorlinksUi.init'); // eslint-disable-line no-console
    }
};

jQuery(document).ready(($) => {
    const config = wpdtrt_anchorlinks_config; // eslint-disable-line camelcase
    wpdtrtAnchorlinksUi.init($);
});
