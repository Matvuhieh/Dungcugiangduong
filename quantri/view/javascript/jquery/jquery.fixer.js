;(function($, window) {

    var $win = $(window);
    var defaults = {
        gap: 0,
        gapRevers: 0,
        isFixed: $.noop
    };

    var supportSticky = function(elem) {
        var prefixes = ['', '-webkit-', '-moz-', '-ms-', '-o-'], prefix;
        while (prefix = prefixes.pop()) {
            elem.style.cssText = 'position:' + prefix + 'sticky';
            if (elem.style.position !== '') return true;
        }
        return false;
    };

    $.fn.fixer = function(options) {
        options = $.extend({}, defaults, options);
        var cssPos = 'bottom',
        	cssRev = 'right';
        return this.each(function() {
            var style = this.style,
                $this = $(this),
                $parent = $this.parent();

            if (supportSticky(this)) {
                style[cssPos] = options.gap + 'px';
                style[cssRev] = options.gapRevers + 'px';
                return;
            }

            $win.on('scroll', function() {
                var scrollPos = $win.scrollTop() +  $win.height(),
                    elemSize = $this.outerHeight(),
                    parentSize = $parent.outerHeight(),
                    parentPos = $parent.offset().top,
                    parentPosBottom = $parent.offset().top+parentSize;


                if (scrollPos <= parentPosBottom + elemSize - options.gap && (parentPos + options.gap + 60) <= (scrollPos)) {
                	console.log('1')
                    style.position = 'fixed';
                    style[cssPos] = options.gap + 'px';
                    style[cssRev] = options.gapRevers + 'px';
                    options.isFixed();
                } else if (scrollPos > parentPosBottom) {
                	console.log('2')
                    style.position = 'absolute';
                    style[cssPos] = 0;
                    style[cssRev] = 0;
                } else {
                	console.log('3')
                    style.position = 'absolute';
                    style[cssPos] = parentSize - options.gap - 10 + 'px';
                    style[cssRev] = 0;
                }
            }).resize();
        });
    };

}(jQuery, this));
