 /**
 * jQuery Pagesajax v1.0
 * Client side pagination with jQuery
 * http://szelecom.com/Pagesajax
 *
 * Licensed under the SZ.Elec license.
 * Copyright 2016 Elecom
 */


jQuery(document).ready(function() {
    var echo_list = function(jQuerychildren, n) {
        var jQueryhiddenChildren = jQuerychildren.filter(":hidden");
        var cnt = jQueryhiddenChildren.length;
        for (var i = 0; i < n && i < cnt; i++) {
            jQueryhiddenChildren.eq(i).fadeIn();

        }
        return cnt - n; 
    }

    jQuery(".echo_list").each(function() {
        var pagenum = jQuery(this).attr("pagenum") || 15;
        var jQuerychildren = jQuery(this).children();
        if (jQuerychildren.length > pagenum) {
            for (var i = pagenum; i < jQuerychildren.length; i++) {
                 jQuerychildren.eq(i).hide();
            }
            jQuery("<div class=\"more_box\"><a id=\"more\" class=\"addmore\">加载更多 ↓</a></div><style>.echo_list{ width: 100%; max-height: 100%; overflow: visible}</style>").insertAfter(jQuery(this)).click(function() {
                if (echo_list(jQuerychildren, pagenum) <= 0) {
				    jQuery(this).html("<div class=\"more_box\"><a id=\"more\" class=\"addmore\">没有更多了...</a></div>");
                };
            });
        }
    });

});
