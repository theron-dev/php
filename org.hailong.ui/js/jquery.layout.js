
/**
zhanghailong . 2009-06-09
hailongz.hit@gmail.com

**/

(function($) {

	if(!$.parseInt){
		$.parseInt = function(str){
			var v = parseInt(str);
			return v ? v : 0;
		}
	}
	
	var __auto_id = 0;
	
	var newId = function(){
		return "ui-screen-"+(++__auto_id);
	}
	
    var src = null;

    $(function($) {
        scr = $(".screen");
        scr = scr.size() == 0 ? $(document.body) : scr;
        screenWidth = 0;
        screenHeight = 0;

        var resize = function() {
            var w = scr.width(), h = scr.height();
            if (w != screenWidth || h != screenHeight) {
                screenWidth = w;
                screenHeight = h;
                scr.layout(true);

                $(document).css({ width: screenWidth, height: screenHeight }).trigger("resize");
            }
        }

        if (scr.size() > 0) {
            window.setInterval(resize, 600);
            resize();
        }
    });

    var outerLeft = function(el, left) {
        el.css({ left: left });
    };

    var outerTop = function(el, top) {
        el.css({ top: top });
    };

    var outerWidth = function(el, width) {
        var offsetLeft = $.parseInt(el.css("borderLeftWidth")) + $.parseInt(el.css("paddingLeft")) + $.parseInt(el.css("marginLeft"));
        var offsetRight = $.parseInt(el.css("borderRightWidth")) + $.parseInt(el.css("paddingRight")) + $.parseInt(el.css("marginRight"));
        var w = width - offsetLeft - offsetRight;
        el.css({ width: w > 0 ? w : 0 });
    };

    var outerHeight = function(el, height) {
        var offsetTop = $.parseInt(el.css("borderTopWidth")) + $.parseInt(el.css("paddingTop")) + $.parseInt(el.css("marginTop"));
        var offsetBottom = $.parseInt(el.css("borderBottomWidth")) + $.parseInt(el.css("paddingBottom")) + $.parseInt(el.css("marginBottom"));
        var h = height - offsetTop - offsetBottom;

        el.css({ height: h > 0 ? h : 0 });
    };

    var mousedown = function(e) {
        var el = $(this);
        el.data("moving", true);
        el.data("mouse_pos", { x: e.screenX, y: e.screenY });
        var offset = el.offset();
        var pOffset = el.parent().offset();
        offset.top -= pOffset.top;
        offset.left -= pOffset.left;
        el.data("split_offset", offset);
        e.stopPropagation();
    };

    var mouseup = function(e) {
        var el = this;
        var d = el.attr("split");
        if (d) {
            if (el.data("moved")) {
                var pos = el.data("mouse_pos");
                var x = e.screenX - pos.x;
                var y = e.screenY - pos.y;
                if (d == 'right') {
                    var parent = el.parent();
                    var right = $(">[right]", parent);

                    if (right.size() > 0) {
                        var w = right.outerWidth() - x;
                        w = w > 0 ? w : 0;
                        outerWidth(right, w);
                        parent.layout();
                    }

                }
                else if (d == 'left') {
                    var parent = el.parent();
                    var left = $(">[left]", parent);

                    if (left.size() > 0) {
                        var w = left.outerWidth() + x;
                        w = w > 0 ? w : 0;
                        outerWidth(left, w);
                        parent.layout();
                    }
                }
                else if (d == 'bottom') {
                    var parent = el.parent();
                    var bottom = $(">[bottom]", parent);

                    if (bottom.size() > 0) {
                        var h = bottom.outerHeight() - y;
                        h = h > 0 ? h : 0;
                        outerHeight(bottom, h);
                        parent.layout();
                    }

                }
                else if (d == 'top') {
                    var parent = el.parent();
                    var top = $(">[top]", parent);

                    if (top.size() > 0) {
                        var h = top.outerHeight() + y;
                        h = h > 0 ? h : 0;
                        outerHeight(top, h);
                        parent.layout();
                    }

                }
            }
            el.data("moved", false);
            el.data("moving", false);
            e.stopPropagation();
        }
    };

    var mousemove = function(e) {
        var el = this;
        var d = el.attr("split");
        if (d && el.data("moving")) {
            var pos = el.data("mouse_pos");
            var x = e.screenX - pos.x;
            var y = e.screenY - pos.y;
            var offset = el.data("split_offset");
            if (d == 'right') {
                el.css({ left: offset.left + x });
            }
            else if (d == 'left') {
                el.css({ left: offset.left + x });
            }
            else if (d == 'bottom') {
                el.css({ top: offset.top + y });
            }
            else if (d == 'top') {
                el.css({ top: offset.top + y });
            }
            el.data("moved", true);
            e.stopPropagation();
        }
    };

    var bindSplit = function(el) {

        var element = el;
        element
            .unbind("mousedown", mousedown).bind("mousedown", mousedown)
            .unbind("dblclick.layout").bind("dblclick.layout", function() {
                var e = $(this);
                $(">[" + e.attr("split") + "]", e.parent()).minimize();
            })
            .attr('unselectable', 'on');

        var id = element.attr("id");
        if (!id) {
            id = "split" + newId();
            element.attr("id", id);
        }

        $(document.body)
            .unbind("mouseup." + id)
            .unbind("mousemove." + id)
            .bind("mouseup." + id, function(e) {
                mouseup.call(element, e);
            })
            .bind("mousemove." + id, function(e) {
                mousemove.call(element, e);
            });

    };

    $.resize = function() {
        return (src || $(".screen")).resize();
    };

    $.fn.resize = function(psize) {
        var el = $(this);
        el.rewidth(psize && psize.width);
        el.reheight(psize && psize.height);
        return this;
    };

    $.rewidth = function() {
        return (src || $(".screen")).rewidth();
    };

    $.fn.rewidth = function(pwidth) {
        if (this.attr("wp")) {
            var p = pwidth || this.parent().innerWidth();
            var wp = parseFloat(this.attr("wp"));
            this.css({ width: p * wp / 100.0 + "px" });
        }
        else if (this.attr("w")) {
            var p = pwidth || this.parent().innerWidth();
            var w = parseFloat(this.attr("w"));
            this.css({ width: w + "px" });
        }
        return this;
    };

    $.reheight = function() {
        return (src || $(".screen")).reheight();
    };

    $.fn.reheight = function(pheight) {
        if (this.attr("hp")) {
            var p = pheight || this.parent().innerHeight();
            var hp = parseFloat(this.attr("hp"));
            this.css({ height: p * hp / 100.0 + "px" });
        }
        else if (this.attr("h")) {
            var p = pheight || this.parent().innerHeight();
            var h = parseFloat(this.attr("h"));
            this.css({ height: h + "px" });
        }
        return this;
    };

    $.layout = function() {
        (src || $(".screen")).layout();
    };

    $.fn.layout = function(resize) {
        this.each(function() {
            var el = $(this);
            var layout = el.attr("layout");
            if (layout) {
                var childs = el.children();
                var offsetLeft = $.parseInt(el.css("borderLeftWidth")) + $.parseInt(el.css("paddingLeft"));
                var offsetRight = $.parseInt(el.css("borderRightWidth")) + $.parseInt(el.css("paddingRight"));
                var offsetTop = $.parseInt(el.css("borderTopWidth")) + $.parseInt(el.css("paddingTop"));
                var offsetBottom = $.parseInt(el.css("borderBottomWidth")) + $.parseInt(el.css("paddingBottom"));
                if (layout == "xy") {
                    el.css({ position: 'relative' });
                    childs.each(function() {
                        var el = $(this);
                        if (el.attr("x") && el.attr("y")) {
                            var x = parseInt(el.attr("x"));
                            var y = parseInt(el.attr("y"));
                            el.css({ position: 'absolute' });
                            if (!$.isNaN(x)) {
                                outerLeft(el, x);
                            }
                            if (!$.isNaN(y)) {
                                outerTop(el, y);
                            }
                        }
                    });

                }
                else if (layout == "border") {
                    var obj = {
                        top: { el: [], width: 0, height: 0, size: 0, q: "[top]" }
                    , left: { el: [], width: 0, height: 0, size: 0, q: "[left]" }
                    , right: { el: [], width: 0, height: 0, size: 0, q: "[right]" }
                    , bottom: { el: [], width: 0, height: 0, size: 0, q: "[bottom]" }
                    , center: { el: [], width: 0, height: 0, size: 0, q: "[center]" }
                    , splitTop: { el: [], width: 0, height: 0, size: 0, q: "[split='top']" }
                    , splitLeft: { el: [], width: 0, height: 0, size: 0, q: "[split='left']" }
                    , splitBottom: { el: [], width: 0, height: 0, size: 0, q: "[split='bottom']" }
                    , splitRight: { el: [], width: 0, height: 0, size: 0, q: "[split='right']" }
                    , barTop: { el: [], width: 0, height: 0, size: 0, q: "[bar='top']" }
                    , barLeft: { el: [], width: 0, height: 0, size: 0, q: "[bar='left']" }
                    , barBottom: { el: [], width: 0, height: 0, size: 0, q: "[bar='bottom']" }
                    , barRight: { el: [], width: 0, height: 0, size: 0, q: "[bar='right']" }
                    };

                    childs.each(function() {
                        var ch = $(this);
                        for (var k in obj) {
                            var o = obj[k];
                            if (ch.is(o.q)) {
                                o.el.push(this);
                                o.size++;
                                break;
                            }
                        }
                    });


                    var width = el.innerWidth();
                    var height = el.innerHeight();

                    for (var k in obj) {
                        var o = obj[k];
                        o.el = $(o.el);
                        if (o.size > 0) {
                            resize && o.el.resize({ width: width, height: height });
                            o.width = o.el.outerWidth();
                            o.height = o.el.outerHeight();
                        }
                    }

                    if (obj.barTop.size > 0) {
                        obj.barTop.el.css({ position: 'absolute' });
                        outerTop(obj.barTop.el, offsetTop);
                        outerLeft(obj.barTop.el, offsetLeft);
                        outerWidth(obj.barTop.el, width);
                    }

                    if (obj.top.size > 0) {
                        obj.top.el.css({ position: 'absolute' });
                        outerTop(obj.top.el, offsetTop + obj.barTop.height);
                        outerLeft(obj.top.el, offsetLeft);
                        outerWidth(obj.top.el, width);
                    }


                    if (obj.splitTop.size > 0) {
                        obj.splitTop.el.css({ position: 'absolute', cursor: 'n-resize' });
                        outerTop(obj.splitTop.el, obj.top.height + offsetTop + obj.barTop.height);
                        outerLeft(obj.splitTop.el, offsetLeft);
                        outerWidth(obj.splitTop.el, width);
                        bindSplit(obj.splitTop.el);
                    }

                    if (obj.barLeft.size > 0) {
                        obj.barLeft.el.css({ position: 'absolute' });
                        outerTop(obj.barLeft.el, offsetTop + obj.top.height + obj.barTop.height);
                        outerLeft(obj.barLeft.el, offsetLeft);
                        outerHeight(obj.barLeft.el, height
                        - obj.top.height - obj.bottom.height
                        - obj.barTop.height - obj.barBottom.height
                        - obj.splitTop.height - obj.splitBottom.height);
                    }

                    if (obj.left.size > 0) {
                        obj.left.el.css({ position: 'absolute' });
                        outerTop(obj.left.el, offsetTop + obj.top.height + obj.barTop.height);
                        outerLeft(obj.left.el, offsetLeft + obj.barLeft.width);
                        outerHeight(obj.left.el, height - obj.top.height - obj.bottom.height
                        - obj.barTop.height - obj.barBottom.height
                        - obj.splitTop.height - obj.splitBottom.height);
                    }

                    if (obj.splitLeft.size > 0) {
                        obj.splitLeft.el.css({ position: 'absolute', cursor: 'e-resize' });
                        outerTop(obj.splitLeft.el, offsetTop + obj.top.height + obj.barTop.height);
                        outerLeft(obj.splitLeft.el, obj.left.width + offsetLeft + obj.barLeft.width);
                        outerHeight(obj.splitLeft.el, height - obj.top.height - obj.bottom.height
                        - obj.barTop.height - obj.barBottom.height);
                        bindSplit(obj.splitLeft.el);
                    }

                    if (obj.barRight.size > 0) {
                        obj.barRight.el.css({ position: 'absolute' });
                        outerTop(obj.barRight.el, offsetTop + obj.top.height + obj.barTop.height + obj.splitTop.height);
                        outerLeft(obj.barRight.el, offsetLeft + width - obj.barRight.width);
                        outerHeight(obj.barRight.el, height - obj.top.height - obj.bottom.height
                        - obj.barTop.height - obj.barBottom.height
                        - obj.splitTop.height - obj.splitBottom.height);
                    }

                    if (obj.right.size > 0) {
                        obj.right.el.css({ position: 'absolute' });
                        outerTop(obj.right.el, offsetTop + obj.top.height + obj.barTop.height + obj.splitTop.height);
                        outerLeft(obj.right.el, offsetLeft + width - obj.right.width - obj.barRight.width);
                        outerHeight(obj.right.el, height - obj.top.height - obj.bottom.height
                        - obj.barTop.height - obj.barBottom.height
                        - obj.splitTop.height - obj.splitBottom.height);
                    }

                    if (obj.splitRight.size > 0) {
                        obj.splitRight.el.css({ position: 'absolute', cursor: 'e-resize' });
                        outerTop(obj.splitRight.el, offsetTop + obj.top.height + obj.barTop.height + obj.splitTop.height);
                        outerLeft(obj.splitRight.el, offsetLeft + width - obj.right.width - obj.splitRight.width - obj.barRight.width);
                        outerHeight(obj.splitRight.el, height - obj.top.height - obj.bottom.height
                        - obj.barTop.height - obj.barBottom.height - obj.splitTop.height - obj.splitBottom.height);
                        bindSplit(obj.splitRight.el);
                    }

                    if (obj.barBottom.size > 0) {
                        obj.barBottom.el.css({ position: 'absolute' });
                        outerTop(obj.barBottom.el, offsetTop + height - obj.barBottom.height);
                        outerLeft(obj.barBottom.el, offsetLeft);
                        outerWidth(obj.barBottom.el, width);
                    }

                    if (obj.bottom.size > 0) {
                        obj.bottom.el.css({ position: 'absolute' });
                        outerTop(obj.bottom.el, offsetTop + height - obj.bottom.height - obj.barBottom.height);
                        outerLeft(obj.bottom.el, offsetLeft);
                        outerWidth(obj.bottom.el, width);
                    }

                    if (obj.splitBottom.size > 0) {
                        obj.splitBottom.el.css({ position: 'absolute', cursor: 'n-resize' });
                        outerTop(obj.splitBottom.el, offsetTop + height - obj.bottom.height - obj.splitBottom.height - obj.barBottom.height);
                        outerLeft(obj.splitBottom.el, offsetLeft);
                        outerWidth(obj.splitBottom.el, width);
                        bindSplit(obj.splitBottom.el);
                    }

                    if (obj.center.size > 0) {
                        obj.center.el.css({ position: 'absolute' });
                        outerTop(obj.center.el, offsetTop + obj.top.height + obj.splitTop.height + obj.barTop.height);
                        outerLeft(obj.center.el, offsetLeft + obj.left.width + obj.splitLeft.width + obj.barLeft.width);
                        outerWidth(obj.center.el, width - obj.left.width - obj.right.width
                        - obj.splitLeft.width - obj.splitRight.width
                        - obj.barLeft.width - obj.barRight.width);
                        outerHeight(obj.center.el, height - obj.top.height - obj.bottom.height
                        - obj.splitTop.height - obj.splitBottom.height
                        - obj.barTop.height - obj.barBottom.height);
                    }
                    for (var k in obj) {
                        delete obj[k];
                    }
                    delete obj;
                }
                childs.each(function() {
                    var ch = $(this);
                    if (ch.attr("layout")) {
                        ch.layout(resize);
                    }
                });
                el.trigger("layout");
            }
        });
        return this;
    };


})(jQuery);