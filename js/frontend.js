/**
 * @file DTRT Tour dates frontend.js
 * @summary
 *     Front-end scripting for public pages
 *     PHP variables are provided in `wpdtrtAnchorlinksConfig`.
 * @version 0.0.1
 * @since 0.7.0 DTRT WordPress Plugin Boilerplate Generator
 */

/* eslint-env browser */
/* global jQuery, wpdtrtAnchorlinksConfig, Waypoint */
/* eslint-disable no-unused-vars, max-len, require-jsdoc */

/**
 * @namespace wpdtrtAnchorlinksUi
 */
const wpdtrtAnchorlinksUi = {

    sticky_jump_menu: ($) => {
        const $jumpMenu = $('.mwm-aal-container');

        if (!$jumpMenu.length) {
            return;
        }

        // inject the summary section into the nav
        const $firstItem = $jumpMenu.find('ol > li').eq(0);
        const $lastItem = $jumpMenu.find('ol > li:last');
        const summaryItem = '<li><a href=\'#summary\'>Introduction</a</li>';

        $firstItem.before(summaryItem);

        // the wrapper is assigned critical dimensions as the page, and then fixed to the top
        // this allows the actual menu bar to be positioned within a page like construct
        let html = '';
        html += '<div class=\'mwm-aal-container--site\'>';
        html += '<div class=\'mwm-aal-container--site-inner\'>';
        html += '<div class=\'mwm-aal-container--site-content\'>';
        html += '<div class=\'mwm-aal-container--entry-content\'>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        $jumpMenu.wrap(html);

        const $jumpMenuLayout = $('.mwm-aal-container--site');

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

        // Get link by section or article id
        function getRelatedNavigation(el) {
            return $(`.mwm-aal-container a[href='#${$(el).attr('id')}']`);
        }

        function showScrollProgress() {
            const $mwmTitle = $('.mwm-aal-title');
            const $mwmLinks = $('.mwm-aal-container > ol a');
            const mwmLinksCount = $mwmLinks.length;
            const $mwmLinkActive = $('.mwm-aal-container > ol a.active');
            const mwmLinksActiveIndex = $mwmLinks.index($mwmLinkActive) + 1;
            let pctThru = (mwmLinksActiveIndex / mwmLinksCount) * 100;
            const $scrollProgress = $('.scroll-progress');

            if (!$scrollProgress.length) {
                $mwmTitle.append('<div class=\'scroll-progress\'></div>');
            }

            // if we're in a section, show how far through we are
            // else assume that we're all the way through
            if (mwmLinksActiveIndex < 1) {
                pctThru = 100;
            }

            $('.scroll-progress').css('width', `${pctThru}%`);
        }

        if ($('section.scrollto').length) {
            const sticky = new Waypoint.Sticky({
                element: $jumpMenuLayout[0],
                stuckClass: 'sticky'
            });

            $('section.scrollto').waypoint(
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

            $('section.scrollto').waypoint(
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
                        return $('.mwm-aal-container').height();
                    }
                }
            );
        }
    },

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
    const config = wpdtrtAnchorlinksConfig;
    wpdtrtAnchorlinksUi.init($);
});
