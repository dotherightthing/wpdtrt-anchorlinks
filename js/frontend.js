/**
 * Scripts for the public front-end
 *
 * PHP variables are provided in wpdtrt_anchorlinks_config.
 *
 * @version 	0.0.1
 * @since       0.7.0
 */

/* globals jQuery, Waypoint */

var wpdtrt_anchorlinks_ui = {

    sticky_jump_menu: function($) {

        var $jump_menu = $('.mwm-aal-container');

        if ( ! $jump_menu.length ) {
            return;
        }

        // inject the summary section into the nav
        var $first_item = $jump_menu.find('ol > li').eq(0);
        var $last_item = $jump_menu.find('ol > li:last');
        var  summary_item = '<li><a href="#summary">Introduction</a</li>';

        $first_item.before( summary_item );

        // the wrapper is assigned critical dimensions as the page, and then fixed to the top
        // this allows the actual menu bar to be positioned within a page like construct
        var html = '';
            html += '<div class="mwm-aal-container--site">';
            html += '<div class="mwm-aal-container--site-inner">';
            html += '<div class="mwm-aal-container--site-content">';
            html += '<div class="mwm-aal-container--entry-content">';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

        $jump_menu.wrap(html);

        var $jump_menu_layout = $('.mwm-aal-container--site');

        // removed as this makes for a terrible tab order
        //var $summary = $('.entry-summary-wrapper');
        //$summary.after($jump_menu_layout);

        /*
         * highlight active nav item on scroll
         * http://codepen.io/jakob-e/pen/mhCyx
         * this changed to this.element to resolve error
         * "Uncaught TypeError: Cannot read property 'defaultView' of undefined"
         * refer: http://stackoverflow.com/a/3936230
         * refer: http://imakewebthings.com/waypoints/guides/jquery-zepto/#fn-extension
         * --
         * + DTRT make sticky
         */

        // Get link by section or article id
        function getRelatedNavigation(el){
            return $('.mwm-aal-container a[href="#'+$(el).attr('id')+'"]');
        }

        function showScrollProgress(){

            var $mwm_title = $('.mwm-aal-title');
            var $mwm_links = $('.mwm-aal-container > ol a');
            var  mwm_links_count = $mwm_links.length;
            var $mwm_link_active = $('.mwm-aal-container > ol a.active');
            var  mwm_links_active_index = $mwm_links.index( $mwm_link_active ) + 1;
            var  pct_thru = ( mwm_links_active_index/mwm_links_count ) * 100;
            var $scroll_progress = $('.scroll-progress');

            if ( ! $scroll_progress.length ) {
                $mwm_title.append('<div class="scroll-progress"></div>');
            }

            // if we're in a section, show how far through we are
            // else assume that we're all the way through
            if ( mwm_links_active_index < 1 ) {
                pct_thru = 100;
            }

            $('.scroll-progress').css('width', pct_thru + '%');
        }

        if ( $('section.scrollto').length ) {

            var sticky = new Waypoint.Sticky({
                element: $jump_menu_layout[0],
                stuckClass: 'sticky'
            });

            $('section.scrollto').waypoint(
                function(direction) {
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
                function(direction) {
                    // Highlight element when bottom of related content
                    // is 100px from the top - remove if less
                    getRelatedNavigation(this.element).toggleClass('active', direction === 'up');
                    showScrollProgress();
                },
                {
                    offset: function() {
                        return -$(this.element).height() + 100;
                    }
                }
            );

            $('#comments').waypoint(
                function(direction) {

                    if ( direction === 'down' ) {
                        $('.sticky').fadeOut(500);
                    }
                    else {
                        $('.sticky').fadeIn(500);
                    }
                },
                {
                    offset: function() {
                        return $('.mwm-aal-container').height();
                    }
                }
            );
        }
    },

    init: function($) { // called from footer config script block

        // https://web-design-weekly.com/snippets/scroll-to-position-with-jquery/
        $.fn.scrollView = function (offset, duration) {
            return this.each(function () {
                $('html, body').animate({
                    scrollTop: $(this).offset().top - offset
                }, duration);
            });
        };

        wpdtrt_anchorlinks_ui.sticky_jump_menu($);
    }
};

jQuery(document).ready( function($) {

    var config = wpdtrt_anchorlinks_config;

    wpdtrt_anchorlinks_ui.init($);
});
