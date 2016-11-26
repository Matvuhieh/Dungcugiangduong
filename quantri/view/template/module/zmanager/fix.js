if (!$.expr.createPseudo) {
    var expando = 'adsada' + Date.now();
    var markFunction = function (fn, value) {
        fn[expando] = value == null || value;
        return fn;
    };

    $.expr.createPseudo = markFunction;

    if (!$.fn.addBack) {
        $.fn.addBack = function (a) {
            return this.add(null == a ? this.prevObject : this.prevObject.filter(a))
        };
    }

}

$(function() {
    // tooltips on hover
    //$('[data-toggle=\'tooltip\']').tooltip({container: 'body', html: true});

    $('[data-toggle=\'tooltip\']').tooltip({container: 'body', animation: false });

    // Makes tooltips work on ajax generated content
    $(document).ajaxStop(function() {
        $('[data-toggle=\'tooltip\']').tooltip({container: 'body', animation: false });
    });
});


