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
        const $title = $jumpMenu.find('.wpdtrt-anchorlinks__title').eq(0);
        const highlightController = '[data-wpdtrt-anchorlinks-controls="highlighting"]';
        let anchorText;
        let stickyTitle = '';

        if ($anchor.length && $anchor.find(highlightController).length) {
            anchorText = $anchor.find('h2').text();

            stickyTitle += `<a href="${anchor}" class="wpdtrt-anchorlinks__title-sticky wpdtrt-anchorlinks__list-link">`;
            stickyTitle += $.trim(anchorText);
            stickyTitle += '</a>';
        } else {
            anchorText = $title.find('.wpdtrt-anchorlinks__title-unsticky').text();

            stickyTitle += '<span class="wpdtrt-anchorlinks__title-sticky">';
            stickyTitle += $.trim(anchorText);
            stickyTitle += '</span>';
        }

        $title.prepend(stickyTitle);
    },

    /**
     * @function injectListAdditions
     * @summary Append theme elements after the list, in the order specified.
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     *
     * @example
     * <div data-wpdtrt-anchorlinks-list-addition="1">Added first</div>
     * <div data-wpdtrt-anchorlinks-list-addition="2">Added second</div>
     */
    injectListAdditions: () => {
        const $ = wpdtrtAnchorlinksUi.jQuery;
        const $elements = $('[data-wpdtrt-anchorlinks-list-addition]');
        const $list = $('.wpdtrt-anchorlinks__list');

        $elements.each((i, item) => {
            let id = i + 1;
            $elements.filter(`[data-wpdtrt-anchorlinks-list-addition="${id}"]`).clone().insertAfter($list);
        });
    },

    /**
     * @function showScrollProgress
     * @summary Resize the indicator according to the scroll progress
     * @memberof wpdtrtAnchorlinksUi
     * @protected
     */
    showScrollProgress: () => {
        const $ = wpdtrtAnchorlinksUi.jQuery;

        const $title = $('.wpdtrt-anchorlinks__title');
        const $links = $('.wpdtrt-anchorlinks__list-link');
        const linksCount = $links.length;
        const $linkActive = $('.wpdtrt-anchorlinks__list-link-active');
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
        const $ = wpdtrtAnchorlinksUi.jQuery;

        changes.forEach(change => {
            let intersectingElement = change.target;

            // ratio of the element which is visible in the viewport
            // (entering or leaving)
            if (change.intersectionRatio > 0.5) {
                let $anchor = $(intersectingElement).parents('.wpdtrt-anchorlinks__anchor').eq(0);
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
    pinAnchorLinksList: (changes, observer) => {
        const $ = wpdtrtAnchorlinksUi.jQuery;
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
        const $ = wpdtrtAnchorlinksUi.jQuery;
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
        const $ = wpdtrtAnchorlinksUi.jQuery;

        if (!$jumpMenu.length) {
            return;
        }

        wpdtrtAnchorlinksUi.setTitleToSummary($jumpMenu);

        const $anchors = $('.wpdtrt-anchorlinks__anchor');
        const $anchorLinks = wpdtrtAnchorlinksUi.getAnchorLinks();

        // theme elements
        const $highlightController = $('[data-wpdtrt-anchorlinks-controls="highlighting"]');
        const $pinController = $('[data-wpdtrt-anchorlinks-controls="pinning"]');
        const $fadeController = $('[data-wpdtrt-anchorlinks-controls="hiding"]');

        if ($anchors.length) {
            if ('IntersectionObserver' in window) {
                const highlightAnchorLinkObserver = new IntersectionObserver(wpdtrtAnchorlinksUi.highlightAnchorLink, {
                    root: null, // relative to document viewport
                    rootMargin: '0px', // margin around root, unitless values not allowed
                    threshold: 0.5 // visible amount of item shown in relation to root
                });

                $highlightController.each((i, item) => {
                    // add element to the set being watched by the IntersectionObserver
                    highlightAnchorLinkObserver.observe($(item).get(0));
                });

                // reset highlighted item on click
                $anchorLinks.on('click', event => {
                    const $clickedElement = $(event.target);
                    const $targetElement = $clickedElement.closest('a');

                    wpdtrtAnchorlinksUi.clickedAnchorLink($targetElement);
                });

                if ($pinController.length) {
                    const pinAnchorLinksListObserver = new IntersectionObserver(wpdtrtAnchorlinksUi.pinAnchorLinksList, {
                        root: null, // relative to document viewport
                        rootMargin: '0px', // margin around root, unitless values not allowed
                        threshold: 0.1 // visible amount of item shown in relation to root
                    });

                    pinAnchorLinksListObserver.observe($pinController.get(0));
                }

                if ($fadeController.length) {
                    const toggleAnchorLinksListObserver = new IntersectionObserver(wpdtrtAnchorlinksUi.toggleAnchorLinksList, {
                        root: null, // relative to document viewport
                        rootMargin: '0px', // margin around root, unitless values not allowed
                        threshold: 0.1 // visible amount of item shown in relation to root
                    });

                    toggleAnchorLinksListObserver.observe($fadeController.get(0));
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

        // https://web-design-weekly.com/snippets/scroll-to-position-with-jquery/
        $.fn.scrollView = function (offset, duration) { // eslint-disable-line func-names
            return this.each(function () { // eslint-disable-line func-names
                $('html, body').animate({
                    scrollTop: $(this).offset().top - offset
                }, duration);
            });
        };

        wpdtrtAnchorlinksUi.injectListAdditions();

        wpdtrtAnchorlinksUi.sticky_jump_menu($('.wpdtrt-anchorlinks'));

        console.log('wpdtrtAnchorlinksUi.init'); // eslint-disable-line no-console
    }
};

jQuery(document).ready(($) => {
    const config = wpdtrt_anchorlinks_config; // eslint-disable-line camelcase
    wpdtrtAnchorlinksUi.jQuery = $;
    wpdtrtAnchorlinksUi.init();
});
