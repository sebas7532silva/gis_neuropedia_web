/*jshint browser: true, strict: true, undef: true */
/*global define: false */

(function (window) {

    'use strict';

// class helper functions from bonzo https://github.com/ded/bonzo

    function classReg(className) {
        return new RegExp("(^|\\s+)" + className + "(\\s+|$)");
    }

// classList support for class management
// altho to be fair, the api sucks because it won't accept multiple classes at once
    var hasClass, addClass, removeClass;

    if ('classList' in document.documentElement) {
        hasClass = function (elem, c) {
            return elem.classList.contains(c);
        };
        addClass = function (elem, c) {
            elem.classList.add(c);
        };
        removeClass = function (elem, c) {
            elem.classList.remove(c);
        };
    }
    else {
        hasClass = function (elem, c) {
            return classReg(c).test(elem.className);
        };
        addClass = function (elem, c) {
            if (!hasClass(elem, c)) {
                elem.className = elem.className + ' ' + c;
            }
        };
        removeClass = function (elem, c) {
            elem.className = elem.className.replace(classReg(c), ' ');
        };
    }

    function toggleClass(elem, c) {
        var fn = hasClass(elem, c) ? removeClass : addClass;
        fn(elem, c);
    }

    var classie = {
        // full names
        hasClass: hasClass,
        addClass: addClass,
        removeClass: removeClass,
        toggleClass: toggleClass,
        // short names
        has: hasClass,
        add: addClass,
        remove: removeClass,
        toggle: toggleClass
    };

// transport
    if (typeof define === 'function' && define.amd) {
        // AMD
        define(classie);
    } else {
        // browser global
        window.classie = classie;
    }

})(window, jQuery);

(function ($, undefined) {
    "use strict";
    var uuid = 0,
        runiqueId = /^ui-id-\d+$/;

// $.ui might exist from components with no dependencies, e.g., $.ui.position
    $.ui = $.ui || {};

    $.extend($.ui, {
        version: "1.10.3",

        keyCode: {
            BACKSPACE: 8,
            COMMA: 188,
            DELETE: 46,
            DOWN: 40,
            END: 35,
            ENTER: 13,
            ESCAPE: 27,
            HOME: 36,
            LEFT: 37,
            NUMPAD_ADD: 107,
            NUMPAD_DECIMAL: 110,
            NUMPAD_DIVIDE: 111,
            NUMPAD_ENTER: 108,
            NUMPAD_MULTIPLY: 106,
            NUMPAD_SUBTRACT: 109,
            PAGE_DOWN: 34,
            PAGE_UP: 33,
            PERIOD: 190,
            RIGHT: 39,
            SPACE: 32,
            TAB: 9,
            UP: 38
        }
    });

// plugins
    $.fn.extend({
        focus: (function (orig) {
            return function (delay, fn) {
                return typeof delay === "number" ?
                    this.each(function () {
                        var elem = this;
                        setTimeout(function () {
                            $(elem).focus();
                            if (fn) {
                                fn.call(elem);
                            }
                        }, delay);
                    }) :
                    orig.apply(this, arguments);
            };
        })($.fn.focus),

        scrollParent: function () {
            var scrollParent;
            if (($.ui.ie && (/(static|relative)/).test(this.css("position"))) || (/absolute/).test(this.css("position"))) {
                scrollParent = this.parents().filter(function () {
                    return (/(relative|absolute|fixed)/).test($.css(this, "position")) && (/(auto|scroll)/).test($.css(this, "overflow") + $.css(this, "overflow-y") + $.css(this, "overflow-x"));
                }).eq(0);
            } else {
                scrollParent = this.parents().filter(function () {
                    return (/(auto|scroll)/).test($.css(this, "overflow") + $.css(this, "overflow-y") + $.css(this, "overflow-x"));
                }).eq(0);
            }

            return (/fixed/).test(this.css("position")) || !scrollParent.length ? $(document) : scrollParent;
        },

        zIndex: function (zIndex) {
            if (zIndex !== undefined) {
                return this.css("zIndex", zIndex);
            }

            if (this.length) {
                var elem = $(this[0]), position, value;
                while (elem.length && elem[0] !== document) {
                    // Ignore z-index if position is set to a value where z-index is ignored by the browser
                    // This makes behavior of this function consistent across browsers
                    // WebKit always returns auto if the element is positioned
                    position = elem.css("position");
                    if (position === "absolute" || position === "relative" || position === "fixed") {
                        // IE returns 0 when zIndex is not specified
                        // other browsers return a string
                        // we ignore the case of nested elements with an explicit value of 0
                        // <div style="z-index: -10;"><div style="z-index: 0;"></div></div>
                        value = parseInt(elem.css("zIndex"), 10);
                        if (!isNaN(value) && value !== 0) {
                            return value;
                        }
                    }
                    elem = elem.parent();
                }
            }

            return 0;
        },

        uniqueId: function () {
            return this.each(function () {
                if (!this.id) {
                    this.id = "ui-id-" + (++uuid);
                }
            });
        },

        removeUniqueId: function () {
            return this.each(function () {
                if (runiqueId.test(this.id)) {
                    $(this).removeAttr("id");
                }
            });
        }
    });

// selectors
    function focusable(element, isTabIndexNotNaN) {
        var map, mapName, img,
            nodeName = element.nodeName.toLowerCase();
        if ("area" === nodeName) {
            map = element.parentNode;
            mapName = map.name;
            if (!element.href || !mapName || map.nodeName.toLowerCase() !== "map") {
                return false;
            }
            img = $("img[usemap=#" + mapName + "]")[0];
            return !!img && visible(img);
        }
        return ( /input|select|textarea|button|object/.test(nodeName) ?
                !element.disabled :
                "a" === nodeName ?
                    element.href || isTabIndexNotNaN :
                    isTabIndexNotNaN) &&
            // the element and all of its ancestors must be visible
            visible(element);
    }

    function visible(element) {
        return $.expr.filters.visible(element) && !$(element).parents().addBack().filter(function () {
                return $.css(this, "visibility") === "hidden";
            }).length;
    }

    $.extend($.expr[":"], {
        data: $.expr.createPseudo ?
            $.expr.createPseudo(function (dataName) {
                return function (elem) {
                    return !!$.data(elem, dataName);
                };
            }) :
            // support: jQuery <1.8
            function (elem, i, match) {
                return !!$.data(elem, match[3]);
            },

        focusable: function (element) {
            return focusable(element, !isNaN($.attr(element, "tabindex")));
        },

        tabbable: function (element) {
            var tabIndex = $.attr(element, "tabindex"),
                isTabIndexNaN = isNaN(tabIndex);
            return ( isTabIndexNaN || tabIndex >= 0 ) && focusable(element, !isTabIndexNaN);
        }
    });

// support: jQuery <1.8
    if (!$("<a>").outerWidth(1).jquery) {
        $.each(["Width", "Height"], function (i, name) {
            var side = name === "Width" ? ["Left", "Right"] : ["Top", "Bottom"],
                type = name.toLowerCase(),
                orig = {
                    innerWidth: $.fn.innerWidth,
                    innerHeight: $.fn.innerHeight,
                    outerWidth: $.fn.outerWidth,
                    outerHeight: $.fn.outerHeight
                };

            function reduce(elem, size, border, margin) {
                $.each(side, function () {
                    size -= parseFloat($.css(elem, "padding" + this)) || 0;
                    if (border) {
                        size -= parseFloat($.css(elem, "border" + this + "Width")) || 0;
                    }
                    if (margin) {
                        size -= parseFloat($.css(elem, "margin" + this)) || 0;
                    }
                });
                return size;
            }

            $.fn["inner" + name] = function (size) {
                if (size === undefined) {
                    return orig["inner" + name].call(this);
                }

                return this.each(function () {
                    $(this).css(type, reduce(this, size) + "px");
                });
            };

            $.fn["outer" + name] = function (size, margin) {
                if (typeof size !== "number") {
                    return orig["outer" + name].call(this, size);
                }

                return this.each(function () {
                    $(this).css(type, reduce(this, size, true, margin) + "px");
                });
            };
        });
    }

// support: jQuery <1.8
    if (!$.fn.addBack) {
        $.fn.addBack = function (selector) {
            return this.add(selector == null ?
                this.prevObject : this.prevObject.filter(selector)
            );
        };
    }

// support: jQuery 1.6.1, 1.6.2 (http://bugs.jquery.com/ticket/9413)
    if ($("<a>").data("a-b", "a").removeData("a-b").data("a-b")) {
        $.fn.removeData = (function (removeData) {
            return function (key) {
                if (arguments.length) {
                    return removeData.call(this, $.camelCase(key));
                } else {
                    return removeData.call(this);
                }
            };
        })($.fn.removeData);
    }


// deprecated
    $.ui.ie = !!/msie [\w.]+/.exec(navigator.userAgent.toLowerCase());

    $.support.selectstart = "onselectstart" in document.createElement("div");
    $.fn.extend({
        disableSelection: function () {
            return this.bind(( $.support.selectstart ? "selectstart" : "mousedown" ) +
                ".ui-disableSelection", function (event) {
                event.preventDefault();
            });
        },

        enableSelection: function () {
            return this.unbind(".ui-disableSelection");
        }
    });

    $.extend($.ui, {
        // $.ui.plugin is deprecated. Use $.widget() extensions instead.
        plugin: {
            add: function (module, option, set) {
                var i,
                    proto = $.ui[module].prototype;
                for (i in set) {
                    proto.plugins[i] = proto.plugins[i] || [];
                    proto.plugins[i].push([option, set[i]]);
                }
            },
            call: function (instance, name, args) {
                var i,
                    set = instance.plugins[name];
                if (!set || !instance.element[0].parentNode || instance.element[0].parentNode.nodeType === 11) {
                    return;
                }

                for (i = 0; i < set.length; i++) {
                    if (instance.options[set[i][0]]) {
                        set[i][1].apply(instance.element, args);
                    }
                }
            }
        },

        // only used by resizable
        hasScroll: function (el, a) {

            //If overflow is hidden, the element might have extra content, but the user wants to hide it
            if ($(el).css("overflow") === "hidden") {
                return false;
            }

            var scroll = ( a && a === "left" ) ? "scrollLeft" : "scrollTop",
                has = false;

            if (el[scroll] > 0) {
                return true;
            }

            // TODO: determine which cases actually cause this to happen
            // if the element doesn't have the scroll set, see if it's possible to
            // set the scroll
            el[scroll] = 1;
            has = ( el[scroll] > 0 );
            el[scroll] = 0;
            return has;
        }
    });

})(jQuery);
(function ($, undefined) {

    var uuid = 0,
        slice = Array.prototype.slice,
        _cleanData = $.cleanData;
    $.cleanData = function (elems) {
        for (var i = 0, elem; (elem = elems[i]) != null; i++) {
            try {
                $(elem).triggerHandler("remove");
                // http://bugs.jquery.com/ticket/8235
            } catch (e) {
            }
        }
        _cleanData(elems);
    };

    $.widget = function (name, base, prototype) {
        var fullName, existingConstructor, constructor, basePrototype,
            // proxiedPrototype allows the provided prototype to remain unmodified
            // so that it can be used as a mixin for multiple widgets (#8876)
            proxiedPrototype = {},
            namespace = name.split(".")[0];

        name = name.split(".")[1];
        fullName = namespace + "-" + name;

        if (!prototype) {
            prototype = base;
            base = $.Widget;
        }

        // create selector for plugin
        $.expr[":"][fullName.toLowerCase()] = function (elem) {
            return !!$.data(elem, fullName);
        };

        $[namespace] = $[namespace] || {};
        existingConstructor = $[namespace][name];
        constructor = $[namespace][name] = function (options, element) {
            // allow instantiation without "new" keyword
            if (!this._createWidget) {
                return new constructor(options, element);
            }

            // allow instantiation without initializing for simple inheritance
            // must use "new" keyword (the code above always passes args)
            if (arguments.length) {
                this._createWidget(options, element);
            }
        };
        // extend with the existing constructor to carry over any static properties
        $.extend(constructor, existingConstructor, {
            version: prototype.version,
            // copy the object used to create the prototype in case we need to
            // redefine the widget later
            _proto: $.extend({}, prototype),
            // track widgets that inherit from this widget in case this widget is
            // redefined after a widget inherits from it
            _childConstructors: []
        });

        basePrototype = new base();
        // we need to make the options hash a property directly on the new instance
        // otherwise we'll modify the options hash on the prototype that we're
        // inheriting from
        basePrototype.options = $.widget.extend({}, basePrototype.options);
        $.each(prototype, function (prop, value) {
            if (!$.isFunction(value)) {
                proxiedPrototype[prop] = value;
                return;
            }
            proxiedPrototype[prop] = (function () {
                var _super = function () {
                        return base.prototype[prop].apply(this, arguments);
                    },
                    _superApply = function (args) {
                        return base.prototype[prop].apply(this, args);
                    };
                return function () {
                    var __super = this._super,
                        __superApply = this._superApply,
                        returnValue;

                    this._super = _super;
                    this._superApply = _superApply;

                    returnValue = value.apply(this, arguments);

                    this._super = __super;
                    this._superApply = __superApply;

                    return returnValue;
                };
            })();
        });
        constructor.prototype = $.widget.extend(basePrototype, {
            // TODO: remove support for widgetEventPrefix
            // always use the name + a colon as the prefix, e.g., draggable:start
            // don't prefix for widgets that aren't DOM-based
            widgetEventPrefix: existingConstructor ? basePrototype.widgetEventPrefix : name
        }, proxiedPrototype, {
            constructor: constructor,
            namespace: namespace,
            widgetName: name,
            widgetFullName: fullName
        });

        // If this widget is being redefined then we need to find all widgets that
        // are inheriting from it and redefine all of them so that they inherit from
        // the new version of this widget. We're essentially trying to replace one
        // level in the prototype chain.
        if (existingConstructor) {
            $.each(existingConstructor._childConstructors, function (i, child) {
                var childPrototype = child.prototype;

                // redefine the child widget using the same prototype that was
                // originally used, but inherit from the new version of the base
                $.widget(childPrototype.namespace + "." + childPrototype.widgetName, constructor, child._proto);
            });
            // remove the list of existing child constructors from the old constructor
            // so the old child constructors can be garbage collected
            delete existingConstructor._childConstructors;
        } else {
            base._childConstructors.push(constructor);
        }

        $.widget.bridge(name, constructor);
    };

    $.widget.extend = function (target) {
        var input = slice.call(arguments, 1),
            inputIndex = 0,
            inputLength = input.length,
            key,
            value;
        for (; inputIndex < inputLength; inputIndex++) {
            for (key in input[inputIndex]) {
                value = input[inputIndex][key];
                if (input[inputIndex].hasOwnProperty(key) && value !== undefined) {
                    // Clone objects
                    if ($.isPlainObject(value)) {
                        target[key] = $.isPlainObject(target[key]) ?
                            $.widget.extend({}, target[key], value) :
                            // Don't extend strings, arrays, etc. with objects
                            $.widget.extend({}, value);
                        // Copy everything else by reference
                    } else {
                        target[key] = value;
                    }
                }
            }
        }
        return target;
    };

    $.widget.bridge = function (name, object) {
        var fullName = object.prototype.widgetFullName || name;
        $.fn[name] = function (options) {
            var isMethodCall = typeof options === "string",
                args = slice.call(arguments, 1),
                returnValue = this;

            // allow multiple hashes to be passed on init
            options = !isMethodCall && args.length ?
                $.widget.extend.apply(null, [options].concat(args)) :
                options;

            if (isMethodCall) {
                this.each(function () {
                    var methodValue,
                        instance = $.data(this, fullName);
                    if (!instance) {
                        return $.error("cannot call methods on " + name + " prior to initialization; " +
                            "attempted to call method '" + options + "'");
                    }
                    if (!$.isFunction(instance[options]) || options.charAt(0) === "_") {
                        return $.error("no such method '" + options + "' for " + name + " widget instance");
                    }
                    methodValue = instance[options].apply(instance, args);
                    if (methodValue !== instance && methodValue !== undefined) {
                        returnValue = methodValue && methodValue.jquery ?
                            returnValue.pushStack(methodValue.get()) :
                            methodValue;
                        return false;
                    }
                });
            } else {
                this.each(function () {
                    var instance = $.data(this, fullName);
                    if (instance) {
                        instance.option(options || {})._init();
                    } else {
                        $.data(this, fullName, new object(options, this));
                    }
                });
            }

            return returnValue;
        };
    };

    $.Widget = function (/* options, element */) {
    };
    $.Widget._childConstructors = [];

    $.Widget.prototype = {
        widgetName: "widget",
        widgetEventPrefix: "",
        defaultElement: "<div>",
        options: {
            disabled: false,

            // callbacks
            create: null
        },
        _createWidget: function (options, element) {
            element = $(element || this.defaultElement || this)[0];
            this.element = $(element);
            this.uuid = uuid++;
            this.eventNamespace = "." + this.widgetName + this.uuid;
            this.options = $.widget.extend({},
                this.options,
                this._getCreateOptions(),
                options);

            this.bindings = $();
            this.hoverable = $();
            this.focusable = $();

            if (element !== this) {
                $.data(element, this.widgetFullName, this);
                this._on(true, this.element, {
                    remove: function (event) {
                        if (event.target === element) {
                            this.destroy();
                        }
                    }
                });
                this.document = $(element.style ?
                    // element within the document
                    element.ownerDocument :
                    // element is window or document
                    element.document || element);
                this.window = $(this.document[0].defaultView || this.document[0].parentWindow);
            }

            this._create();
            this._trigger("create", null, this._getCreateEventData());
            this._init();
        },
        _getCreateOptions: $.noop,
        _getCreateEventData: $.noop,
        _create: $.noop,
        _init: $.noop,

        destroy: function () {
            this._destroy();
            // we can probably remove the unbind calls in 2.0
            // all event bindings should go through this._on()
            this.element
                .unbind(this.eventNamespace)
                // 1.9 BC for #7810
                // TODO remove dual storage
                .removeData(this.widgetName)
                .removeData(this.widgetFullName)
                // support: jquery <1.6.3
                // http://bugs.jquery.com/ticket/9413
                .removeData($.camelCase(this.widgetFullName));
            this.widget()
                .unbind(this.eventNamespace)
                .removeAttr("aria-disabled")
                .removeClass(
                    this.widgetFullName + "-disabled " +
                    "ui-state-disabled");

            // clean up events and states
            this.bindings.unbind(this.eventNamespace);
            this.hoverable.removeClass("ui-state-hover");
            this.focusable.removeClass("ui-state-focus");
        },
        _destroy: $.noop,

        widget: function () {
            return this.element;
        },

        option: function (key, value) {
            var options = key,
                parts,
                curOption,
                i;

            if (arguments.length === 0) {
                // don't return a reference to the internal hash
                return $.widget.extend({}, this.options);
            }

            if (typeof key === "string") {
                // handle nested keys, e.g., "foo.bar" => { foo: { bar: ___ } }
                options = {};
                parts = key.split(".");
                key = parts.shift();
                if (parts.length) {
                    curOption = options[key] = $.widget.extend({}, this.options[key]);
                    for (i = 0; i < parts.length - 1; i++) {
                        curOption[parts[i]] = curOption[parts[i]] || {};
                        curOption = curOption[parts[i]];
                    }
                    key = parts.pop();
                    if (value === undefined) {
                        return curOption[key] === undefined ? null : curOption[key];
                    }
                    curOption[key] = value;
                } else {
                    if (value === undefined) {
                        return this.options[key] === undefined ? null : this.options[key];
                    }
                    options[key] = value;
                }
            }

            this._setOptions(options);

            return this;
        },
        _setOptions: function (options) {
            var key;

            for (key in options) {
                this._setOption(key, options[key]);
            }

            return this;
        },
        _setOption: function (key, value) {
            this.options[key] = value;

            if (key === "disabled") {
                this.widget()
                    .toggleClass(this.widgetFullName + "-disabled ui-state-disabled", !!value)
                    .attr("aria-disabled", value);
                this.hoverable.removeClass("ui-state-hover");
                this.focusable.removeClass("ui-state-focus");
            }

            return this;
        },

        enable: function () {
            return this._setOption("disabled", false);
        },
        disable: function () {
            return this._setOption("disabled", true);
        },

        _on: function (suppressDisabledCheck, element, handlers) {
            var delegateElement,
                instance = this;

            // no suppressDisabledCheck flag, shuffle arguments
            if (typeof suppressDisabledCheck !== "boolean") {
                handlers = element;
                element = suppressDisabledCheck;
                suppressDisabledCheck = false;
            }

            // no element argument, shuffle and use this.element
            if (!handlers) {
                handlers = element;
                element = this.element;
                delegateElement = this.widget();
            } else {
                // accept selectors, DOM elements
                element = delegateElement = $(element);
                this.bindings = this.bindings.add(element);
            }

            $.each(handlers, function (event, handler) {
                function handlerProxy() {
                    // allow widgets to customize the disabled handling
                    // - disabled as an array instead of boolean
                    // - disabled class as method for disabling individual parts
                    if (!suppressDisabledCheck &&
                        ( instance.options.disabled === true ||
                        $(this).hasClass("ui-state-disabled") )) {
                        return;
                    }
                    return ( typeof handler === "string" ? instance[handler] : handler )
                        .apply(instance, arguments);
                }

                // copy the guid so direct unbinding works
                if (typeof handler !== "string") {
                    handlerProxy.guid = handler.guid =
                        handler.guid || handlerProxy.guid || $.guid++;
                }

                var match = event.match(/^(\w+)\s*(.*)$/),
                    eventName = match[1] + instance.eventNamespace,
                    selector = match[2];
                if (selector) {
                    delegateElement.delegate(selector, eventName, handlerProxy);
                } else {
                    element.bind(eventName, handlerProxy);
                }
            });
        },

        _off: function (element, eventName) {
            eventName = (eventName || "").split(" ").join(this.eventNamespace + " ") + this.eventNamespace;
            element.unbind(eventName).undelegate(eventName);
        },

        _delay: function (handler, delay) {
            function handlerProxy() {
                return ( typeof handler === "string" ? instance[handler] : handler )
                    .apply(instance, arguments);
            }

            var instance = this;
            return setTimeout(handlerProxy, delay || 0);
        },

        _hoverable: function (element) {
            this.hoverable = this.hoverable.add(element);
            this._on(element, {
                mouseenter: function (event) {
                    $(event.currentTarget).addClass("ui-state-hover");
                },
                mouseleave: function (event) {
                    $(event.currentTarget).removeClass("ui-state-hover");
                }
            });
        },

        _focusable: function (element) {
            this.focusable = this.focusable.add(element);
            this._on(element, {
                focusin: function (event) {
                    $(event.currentTarget).addClass("ui-state-focus");
                },
                focusout: function (event) {
                    $(event.currentTarget).removeClass("ui-state-focus");
                }
            });
        },

        _trigger: function (type, event, data) {
            var prop, orig,
                callback = this.options[type];

            data = data || {};
            event = $.Event(event);
            event.type = ( type === this.widgetEventPrefix ?
                type :
                this.widgetEventPrefix + type ).toLowerCase();
            // the original event may come from any element
            // so we need to reset the target on the new event
            event.target = this.element[0];

            // copy original event properties over to the new event
            orig = event.originalEvent;
            if (orig) {
                for (prop in orig) {
                    if (!( prop in event )) {
                        event[prop] = orig[prop];
                    }
                }
            }

            this.element.trigger(event, data);
            return !( $.isFunction(callback) &&
            callback.apply(this.element[0], [event].concat(data)) === false ||
            event.isDefaultPrevented() );
        }
    };

    $.each({show: "fadeIn", hide: "fadeOut"}, function (method, defaultEffect) {
        $.Widget.prototype["_" + method] = function (element, options, callback) {
            if (typeof options === "string") {
                options = {effect: options};
            }
            var hasOptions,
                effectName = !options ?
                    method :
                    options === true || typeof options === "number" ?
                        defaultEffect :
                        options.effect || defaultEffect;
            options = options || {};
            if (typeof options === "number") {
                options = {duration: options};
            }
            hasOptions = !$.isEmptyObject(options);
            options.complete = callback;
            if (options.delay) {
                element.delay(options.delay);
            }
            if (hasOptions && $.effects && $.effects.effect[effectName]) {
                element[method](options);
            } else if (effectName !== method && element[effectName]) {
                element[effectName](options.duration, options.easing, callback);
            } else {
                element.queue(function (next) {
                    $(this)[method]();
                    if (callback) {
                        callback.call(element[0]);
                    }
                    next();
                });
            }
        };
    });

})(jQuery);
(function ($, undefined) {

    var mouseHandled = false;
    $(document).mouseup(function () {
        mouseHandled = false;
    });

    $.widget("ui.mouse", {
        version: "1.10.3",
        options: {
            cancel: "input,textarea,button,select,option",
            distance: 1,
            delay: 0
        },
        _mouseInit: function () {
            var that = this;

            this.element
                .bind("mousedown." + this.widgetName, function (event) {
                    return that._mouseDown(event);
                })
                .bind("click." + this.widgetName, function (event) {
                    if (true === $.data(event.target, that.widgetName + ".preventClickEvent")) {
                        $.removeData(event.target, that.widgetName + ".preventClickEvent");
                        event.stopImmediatePropagation();
                        return false;
                    }
                });

            this.started = false;
        },

        // TODO: make sure destroying one instance of mouse doesn't mess with
        // other instances of mouse
        _mouseDestroy: function () {
            this.element.unbind("." + this.widgetName);
            if (this._mouseMoveDelegate) {
                $(document)
                    .unbind("mousemove." + this.widgetName, this._mouseMoveDelegate)
                    .unbind("mouseup." + this.widgetName, this._mouseUpDelegate);
            }
        },

        _mouseDown: function (event) {
            // don't let more than one widget handle mouseStart
            if (mouseHandled) {
                return;
            }

            // we may have missed mouseup (out of window)
            (this._mouseStarted && this._mouseUp(event));

            this._mouseDownEvent = event;

            var that = this,
                btnIsLeft = (event.which === 1),
                // event.target.nodeName works around a bug in IE 8 with
                // disabled inputs (#7620)
                elIsCancel = (typeof this.options.cancel === "string" && event.target.nodeName ? $(event.target).closest(this.options.cancel).length : false);
            if (!btnIsLeft || elIsCancel || !this._mouseCapture(event)) {
                return true;
            }

            this.mouseDelayMet = !this.options.delay;
            if (!this.mouseDelayMet) {
                this._mouseDelayTimer = setTimeout(function () {
                    that.mouseDelayMet = true;
                }, this.options.delay);
            }

            if (this._mouseDistanceMet(event) && this._mouseDelayMet(event)) {
                this._mouseStarted = (this._mouseStart(event) !== false);
                if (!this._mouseStarted) {
                    event.preventDefault();
                    return true;
                }
            }

            // Click event may never have fired (Gecko & Opera)
            if (true === $.data(event.target, this.widgetName + ".preventClickEvent")) {
                $.removeData(event.target, this.widgetName + ".preventClickEvent");
            }

            // these delegates are required to keep context
            this._mouseMoveDelegate = function (event) {
                return that._mouseMove(event);
            };
            this._mouseUpDelegate = function (event) {
                return that._mouseUp(event);
            };
            $(document)
                .bind("mousemove." + this.widgetName, this._mouseMoveDelegate)
                .bind("mouseup." + this.widgetName, this._mouseUpDelegate);

            event.preventDefault();

            mouseHandled = true;
            return true;
        },

        _mouseMove: function (event) {
            // IE mouseup check - mouseup happened when mouse was out of window
            if ($.ui.ie && ( !document.documentMode || document.documentMode < 9 ) && !event.button) {
                return this._mouseUp(event);
            }

            if (this._mouseStarted) {
                this._mouseDrag(event);
                return event.preventDefault();
            }

            if (this._mouseDistanceMet(event) && this._mouseDelayMet(event)) {
                this._mouseStarted =
                    (this._mouseStart(this._mouseDownEvent, event) !== false);
                (this._mouseStarted ? this._mouseDrag(event) : this._mouseUp(event));
            }

            return !this._mouseStarted;
        },

        _mouseUp: function (event) {
            $(document)
                .unbind("mousemove." + this.widgetName, this._mouseMoveDelegate)
                .unbind("mouseup." + this.widgetName, this._mouseUpDelegate);

            if (this._mouseStarted) {
                this._mouseStarted = false;

                if (event.target === this._mouseDownEvent.target) {
                    $.data(event.target, this.widgetName + ".preventClickEvent", true);
                }

                this._mouseStop(event);
            }

            return false;
        },

        _mouseDistanceMet: function (event) {
            return (Math.max(
                    Math.abs(this._mouseDownEvent.pageX - event.pageX),
                    Math.abs(this._mouseDownEvent.pageY - event.pageY)
                ) >= this.options.distance
            );
        },

        _mouseDelayMet: function (/* event */) {
            return this.mouseDelayMet;
        },

        // These are placeholder methods, to be overriden by extending plugin
        _mouseStart: function (/* event */) {
        },
        _mouseDrag: function (/* event */) {
        },
        _mouseStop: function (/* event */) {
        },
        _mouseCapture: function (/* event */) {
            return true;
        }
    });

})(jQuery);
(function ($, undefined) {

    $.ui = $.ui || {};

    var cachedScrollbarWidth,
        max = Math.max,
        abs = Math.abs,
        round = Math.round,
        rhorizontal = /left|center|right/,
        rvertical = /top|center|bottom/,
        roffset = /[\+\-]\d+(\.[\d]+)?%?/,
        rposition = /^\w+/,
        rpercent = /%$/,
        _position = $.fn.position;

    function getOffsets(offsets, width, height) {
        return [
            parseFloat(offsets[0]) * ( rpercent.test(offsets[0]) ? width / 100 : 1 ),
            parseFloat(offsets[1]) * ( rpercent.test(offsets[1]) ? height / 100 : 1 )
        ];
    }

    function parseCss(element, property) {
        return parseInt($.css(element, property), 10) || 0;
    }

    function getDimensions(elem) {
        var raw = elem[0];
        if (raw.nodeType === 9) {
            return {
                width: elem.width(),
                height: elem.height(),
                offset: {top: 0, left: 0}
            };
        }
        if ($.isWindow(raw)) {
            return {
                width: elem.width(),
                height: elem.height(),
                offset: {top: elem.scrollTop(), left: elem.scrollLeft()}
            };
        }
        if (raw.preventDefault) {
            return {
                width: 0,
                height: 0,
                offset: {top: raw.pageY, left: raw.pageX}
            };
        }
        return {
            width: elem.outerWidth(),
            height: elem.outerHeight(),
            offset: elem.offset()
        };
    }

    $.position = {
        scrollbarWidth: function () {
            if (cachedScrollbarWidth !== undefined) {
                return cachedScrollbarWidth;
            }
            var w1, w2,
                div = $("<div style='display:block;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"),
                innerDiv = div.children()[0];

            $("body").append(div);
            w1 = innerDiv.offsetWidth;
            div.css("overflow", "scroll");

            w2 = innerDiv.offsetWidth;

            if (w1 === w2) {
                w2 = div[0].clientWidth;
            }

            div.remove();

            return (cachedScrollbarWidth = w1 - w2);
        },
        getScrollInfo: function (within) {
            var overflowX = within.isWindow ? "" : within.element.css("overflow-x"),
                overflowY = within.isWindow ? "" : within.element.css("overflow-y"),
                hasOverflowX = overflowX === "scroll" ||
                    ( overflowX === "auto" && within.width < within.element[0].scrollWidth ),
                hasOverflowY = overflowY === "scroll" ||
                    ( overflowY === "auto" && within.height < within.element[0].scrollHeight );
            return {
                width: hasOverflowY ? $.position.scrollbarWidth() : 0,
                height: hasOverflowX ? $.position.scrollbarWidth() : 0
            };
        },
        getWithinInfo: function (element) {
            var withinElement = $(element || window),
                isWindow = $.isWindow(withinElement[0]);
            return {
                element: withinElement,
                isWindow: isWindow,
                offset: withinElement.offset() || {left: 0, top: 0},
                scrollLeft: withinElement.scrollLeft(),
                scrollTop: withinElement.scrollTop(),
                width: isWindow ? withinElement.width() : withinElement.outerWidth(),
                height: isWindow ? withinElement.height() : withinElement.outerHeight()
            };
        }
    };

    $.fn.position = function (options) {
        if (!options || !options.of) {
            return _position.apply(this, arguments);
        }

        // make a copy, we don't want to modify arguments
        options = $.extend({}, options);

        var atOffset, targetWidth, targetHeight, targetOffset, basePosition, dimensions,
            target = $(options.of),
            within = $.position.getWithinInfo(options.within),
            scrollInfo = $.position.getScrollInfo(within),
            collision = ( options.collision || "flip" ).split(" "),
            offsets = {};

        dimensions = getDimensions(target);
        if (target[0].preventDefault) {
            // force left top to allow flipping
            options.at = "left top";
        }
        targetWidth = dimensions.width;
        targetHeight = dimensions.height;
        targetOffset = dimensions.offset;
        // clone to reuse original targetOffset later
        basePosition = $.extend({}, targetOffset);

        // force my and at to have valid horizontal and vertical positions
        // if a value is missing or invalid, it will be converted to center
        $.each(["my", "at"], function () {
            var pos = ( options[this] || "" ).split(" "),
                horizontalOffset,
                verticalOffset;

            if (pos.length === 1) {
                pos = rhorizontal.test(pos[0]) ?
                    pos.concat(["center"]) :
                    rvertical.test(pos[0]) ?
                        ["center"].concat(pos) :
                        ["center", "center"];
            }
            pos[0] = rhorizontal.test(pos[0]) ? pos[0] : "center";
            pos[1] = rvertical.test(pos[1]) ? pos[1] : "center";

            // calculate offsets
            horizontalOffset = roffset.exec(pos[0]);
            verticalOffset = roffset.exec(pos[1]);
            offsets[this] = [
                horizontalOffset ? horizontalOffset[0] : 0,
                verticalOffset ? verticalOffset[0] : 0
            ];

            // reduce to just the positions without the offsets
            options[this] = [
                rposition.exec(pos[0])[0],
                rposition.exec(pos[1])[0]
            ];
        });

        // normalize collision option
        if (collision.length === 1) {
            collision[1] = collision[0];
        }

        if (options.at[0] === "right") {
            basePosition.left += targetWidth;
        } else if (options.at[0] === "center") {
            basePosition.left += targetWidth / 2;
        }

        if (options.at[1] === "bottom") {
            basePosition.top += targetHeight;
        } else if (options.at[1] === "center") {
            basePosition.top += targetHeight / 2;
        }

        atOffset = getOffsets(offsets.at, targetWidth, targetHeight);
        basePosition.left += atOffset[0];
        basePosition.top += atOffset[1];

        return this.each(function () {
            var collisionPosition, using,
                elem = $(this),
                elemWidth = elem.outerWidth(),
                elemHeight = elem.outerHeight(),
                marginLeft = parseCss(this, "marginLeft"),
                marginTop = parseCss(this, "marginTop"),
                collisionWidth = elemWidth + marginLeft + parseCss(this, "marginRight") + scrollInfo.width,
                collisionHeight = elemHeight + marginTop + parseCss(this, "marginBottom") + scrollInfo.height,
                position = $.extend({}, basePosition),
                myOffset = getOffsets(offsets.my, elem.outerWidth(), elem.outerHeight());

            if (options.my[0] === "right") {
                position.left -= elemWidth;
            } else if (options.my[0] === "center") {
                position.left -= elemWidth / 2;
            }

            if (options.my[1] === "bottom") {
                position.top -= elemHeight;
            } else if (options.my[1] === "center") {
                position.top -= elemHeight / 2;
            }

            position.left += myOffset[0];
            position.top += myOffset[1];

            // if the browser doesn't support fractions, then round for consistent results
            if (!$.support.offsetFractions) {
                position.left = round(position.left);
                position.top = round(position.top);
            }

            collisionPosition = {
                marginLeft: marginLeft,
                marginTop: marginTop
            };

            $.each(["left", "top"], function (i, dir) {
                if ($.ui.position[collision[i]]) {
                    $.ui.position[collision[i]][dir](position, {
                        targetWidth: targetWidth,
                        targetHeight: targetHeight,
                        elemWidth: elemWidth,
                        elemHeight: elemHeight,
                        collisionPosition: collisionPosition,
                        collisionWidth: collisionWidth,
                        collisionHeight: collisionHeight,
                        offset: [atOffset[0] + myOffset[0], atOffset [1] + myOffset[1]],
                        my: options.my,
                        at: options.at,
                        within: within,
                        elem: elem
                    });
                }
            });

            if (options.using) {
                // adds feedback as second argument to using callback, if present
                using = function (props) {
                    var left = targetOffset.left - position.left,
                        right = left + targetWidth - elemWidth,
                        top = targetOffset.top - position.top,
                        bottom = top + targetHeight - elemHeight,
                        feedback = {
                            target: {
                                element: target,
                                left: targetOffset.left,
                                top: targetOffset.top,
                                width: targetWidth,
                                height: targetHeight
                            },
                            element: {
                                element: elem,
                                left: position.left,
                                top: position.top,
                                width: elemWidth,
                                height: elemHeight
                            },
                            horizontal: right < 0 ? "left" : left > 0 ? "right" : "center",
                            vertical: bottom < 0 ? "top" : top > 0 ? "bottom" : "middle"
                        };
                    if (targetWidth < elemWidth && abs(left + right) < targetWidth) {
                        feedback.horizontal = "center";
                    }
                    if (targetHeight < elemHeight && abs(top + bottom) < targetHeight) {
                        feedback.vertical = "middle";
                    }
                    if (max(abs(left), abs(right)) > max(abs(top), abs(bottom))) {
                        feedback.important = "horizontal";
                    } else {
                        feedback.important = "vertical";
                    }
                    options.using.call(this, props, feedback);
                };
            }

            elem.offset($.extend(position, {using: using}));
        });
    };

    $.ui.position = {
        fit: {
            left: function (position, data) {
                var within = data.within,
                    withinOffset = within.isWindow ? within.scrollLeft : within.offset.left,
                    outerWidth = within.width,
                    collisionPosLeft = position.left - data.collisionPosition.marginLeft,
                    overLeft = withinOffset - collisionPosLeft,
                    overRight = collisionPosLeft + data.collisionWidth - outerWidth - withinOffset,
                    newOverRight;

                // element is wider than within
                if (data.collisionWidth > outerWidth) {
                    // element is initially over the left side of within
                    if (overLeft > 0 && overRight <= 0) {
                        newOverRight = position.left + overLeft + data.collisionWidth - outerWidth - withinOffset;
                        position.left += overLeft - newOverRight;
                        // element is initially over right side of within
                    } else if (overRight > 0 && overLeft <= 0) {
                        position.left = withinOffset;
                        // element is initially over both left and right sides of within
                    } else {
                        if (overLeft > overRight) {
                            position.left = withinOffset + outerWidth - data.collisionWidth;
                        } else {
                            position.left = withinOffset;
                        }
                    }
                    // too far left -> align with left edge
                } else if (overLeft > 0) {
                    position.left += overLeft;
                    // too far right -> align with right edge
                } else if (overRight > 0) {
                    position.left -= overRight;
                    // adjust based on position and margin
                } else {
                    position.left = max(position.left - collisionPosLeft, position.left);
                }
            },
            top: function (position, data) {
                var within = data.within,
                    withinOffset = within.isWindow ? within.scrollTop : within.offset.top,
                    outerHeight = data.within.height,
                    collisionPosTop = position.top - data.collisionPosition.marginTop,
                    overTop = withinOffset - collisionPosTop,
                    overBottom = collisionPosTop + data.collisionHeight - outerHeight - withinOffset,
                    newOverBottom;

                // element is taller than within
                if (data.collisionHeight > outerHeight) {
                    // element is initially over the top of within
                    if (overTop > 0 && overBottom <= 0) {
                        newOverBottom = position.top + overTop + data.collisionHeight - outerHeight - withinOffset;
                        position.top += overTop - newOverBottom;
                        // element is initially over bottom of within
                    } else if (overBottom > 0 && overTop <= 0) {
                        position.top = withinOffset;
                        // element is initially over both top and bottom of within
                    } else {
                        if (overTop > overBottom) {
                            position.top = withinOffset + outerHeight - data.collisionHeight;
                        } else {
                            position.top = withinOffset;
                        }
                    }
                    // too far up -> align with top
                } else if (overTop > 0) {
                    position.top += overTop;
                    // too far down -> align with bottom edge
                } else if (overBottom > 0) {
                    position.top -= overBottom;
                    // adjust based on position and margin
                } else {
                    position.top = max(position.top - collisionPosTop, position.top);
                }
            }
        },
        flip: {
            left: function (position, data) {
                var within = data.within,
                    withinOffset = within.offset.left + within.scrollLeft,
                    outerWidth = within.width,
                    offsetLeft = within.isWindow ? within.scrollLeft : within.offset.left,
                    collisionPosLeft = position.left - data.collisionPosition.marginLeft,
                    overLeft = collisionPosLeft - offsetLeft,
                    overRight = collisionPosLeft + data.collisionWidth - outerWidth - offsetLeft,
                    myOffset = data.my[0] === "left" ?
                        -data.elemWidth :
                        data.my[0] === "right" ?
                            data.elemWidth :
                            0,
                    atOffset = data.at[0] === "left" ?
                        data.targetWidth :
                        data.at[0] === "right" ?
                            -data.targetWidth :
                            0,
                    offset = -2 * data.offset[0],
                    newOverRight,
                    newOverLeft;

                if (overLeft < 0) {
                    newOverRight = position.left + myOffset + atOffset + offset + data.collisionWidth - outerWidth - withinOffset;
                    if (newOverRight < 0 || newOverRight < abs(overLeft)) {
                        position.left += myOffset + atOffset + offset;
                    }
                }
                else if (overRight > 0) {
                    newOverLeft = position.left - data.collisionPosition.marginLeft + myOffset + atOffset + offset - offsetLeft;
                    if (newOverLeft > 0 || abs(newOverLeft) < overRight) {
                        position.left += myOffset + atOffset + offset;
                    }
                }
            },
            top: function (position, data) {
                var within = data.within,
                    withinOffset = within.offset.top + within.scrollTop,
                    outerHeight = within.height,
                    offsetTop = within.isWindow ? within.scrollTop : within.offset.top,
                    collisionPosTop = position.top - data.collisionPosition.marginTop,
                    overTop = collisionPosTop - offsetTop,
                    overBottom = collisionPosTop + data.collisionHeight - outerHeight - offsetTop,
                    top = data.my[1] === "top",
                    myOffset = top ?
                        -data.elemHeight :
                        data.my[1] === "bottom" ?
                            data.elemHeight :
                            0,
                    atOffset = data.at[1] === "top" ?
                        data.targetHeight :
                        data.at[1] === "bottom" ?
                            -data.targetHeight :
                            0,
                    offset = -2 * data.offset[1],
                    newOverTop,
                    newOverBottom;
                if (overTop < 0) {
                    newOverBottom = position.top + myOffset + atOffset + offset + data.collisionHeight - outerHeight - withinOffset;
                    if (( position.top + myOffset + atOffset + offset) > overTop && ( newOverBottom < 0 || newOverBottom < abs(overTop) )) {
                        position.top += myOffset + atOffset + offset;
                    }
                }
                else if (overBottom > 0) {
                    newOverTop = position.top - data.collisionPosition.marginTop + myOffset + atOffset + offset - offsetTop;
                    if (( position.top + myOffset + atOffset + offset) > overBottom && ( newOverTop > 0 || abs(newOverTop) < overBottom )) {
                        position.top += myOffset + atOffset + offset;
                    }
                }
            }
        },
        flipfit: {
            left: function () {
                $.ui.position.flip.left.apply(this, arguments);
                $.ui.position.fit.left.apply(this, arguments);
            },
            top: function () {
                $.ui.position.flip.top.apply(this, arguments);
                $.ui.position.fit.top.apply(this, arguments);
            }
        }
    };

// fraction support test
    (function () {
        var testElement, testElementParent, testElementStyle, offsetLeft, i,
            body = document.getElementsByTagName("body")[0],
            div = document.createElement("div");

        //Create a "fake body" for testing based on method used in jQuery.support
        testElement = document.createElement(body ? "div" : "body");
        testElementStyle = {
            visibility: "hidden",
            width: 0,
            height: 0,
            border: 0,
            margin: 0,
            background: "none"
        };
        if (body) {
            $.extend(testElementStyle, {
                position: "absolute",
                left: "-1000px",
                top: "-1000px"
            });
        }
        for (i in testElementStyle) {
            testElement.style[i] = testElementStyle[i];
        }
        testElement.appendChild(div);
        testElementParent = body || document.documentElement;
        testElementParent.insertBefore(testElement, testElementParent.firstChild);

        div.style.cssText = "position: absolute; left: 10.7432222px;";

        offsetLeft = $(div).offset().left;
        $.support.offsetFractions = offsetLeft > 10 && offsetLeft < 11;

        testElement.innerHTML = "";
        testElementParent.removeChild(testElement);
    })();

}(jQuery) );
(function ($, undefined) {

    $.widget("ui.draggable", $.ui.mouse, {
        version: "1.10.3",
        widgetEventPrefix: "drag",
        options: {
            addClasses: true,
            appendTo: "parent",
            axis: false,
            connectToSortable: false,
            containment: false,
            cursor: "auto",
            cursorAt: false,
            grid: false,
            handle: false,
            helper: "original",
            iframeFix: false,
            opacity: false,
            refreshPositions: false,
            revert: false,
            revertDuration: 500,
            scope: "default",
            scroll: true,
            scrollSensitivity: 20,
            scrollSpeed: 20,
            snap: false,
            snapMode: "both",
            snapTolerance: 20,
            stack: false,
            zIndex: false,

            // callbacks
            drag: null,
            start: null,
            stop: null
        },
        _create: function () {

            if (this.options.helper === "original" && !(/^(?:r|a|f)/).test(this.element.css("position"))) {
                this.element[0].style.position = "relative";
            }
            if (this.options.addClasses) {
                this.element.addClass("ui-draggable");
            }
            if (this.options.disabled) {
                this.element.addClass("ui-draggable-disabled");
            }

            this._mouseInit();

        },

        _destroy: function () {
            this.element.removeClass("ui-draggable ui-draggable-dragging ui-draggable-disabled");
            this._mouseDestroy();
        },

        _mouseCapture: function (event) {

            var o = this.options;

            // among others, prevent a drag on a resizable-handle
            if (this.helper || o.disabled || $(event.target).closest(".ui-resizable-handle").length > 0) {
                return false;
            }

            //Quit if we're not on a valid handle
            this.handle = this._getHandle(event);
            if (!this.handle) {
                return false;
            }

            $(o.iframeFix === true ? "iframe" : o.iframeFix).each(function () {
                $("<div class='ui-draggable-iframeFix' style='background: #fff;'></div>")
                    .css({
                        width: this.offsetWidth + "px", height: this.offsetHeight + "px",
                        position: "absolute", opacity: "0.001", zIndex: 1000
                    })
                    .css($(this).offset())
                    .appendTo("body");
            });

            return true;

        },

        _mouseStart: function (event) {

            var o = this.options;

            //Create and append the visible helper
            this.helper = this._createHelper(event);

            this.helper.addClass("ui-draggable-dragging");

            //Cache the helper size
            this._cacheHelperProportions();

            //If ddmanager is used for droppables, set the global draggable
            if ($.ui.ddmanager) {
                $.ui.ddmanager.current = this;
            }

            /*
             * - Position generation -
             * This block generates everything position related - it's the core of draggables.
             */

            //Cache the margins of the original element
            this._cacheMargins();

            //Store the helper's css position
            this.cssPosition = this.helper.css("position");
            this.scrollParent = this.helper.scrollParent();
            this.offsetParent = this.helper.offsetParent();
            this.offsetParentCssPosition = this.offsetParent.css("position");

            //The element's absolute position on the page minus margins
            this.offset = this.positionAbs = this.element.offset();
            this.offset = {
                top: this.offset.top - this.margins.top,
                left: this.offset.left - this.margins.left
            };

            //Reset scroll cache
            this.offset.scroll = false;

            $.extend(this.offset, {
                click: { //Where the click happened, relative to the element
                    left: event.pageX - this.offset.left,
                    top: event.pageY - this.offset.top
                },
                parent: this._getParentOffset(),
                relative: this._getRelativeOffset() //This is a relative to absolute position minus the actual position calculation - only used for relative positioned helper
            });

            //Generate the original position
            this.originalPosition = this.position = this._generatePosition(event);
            this.originalPageX = event.pageX;
            this.originalPageY = event.pageY;

            //Adjust the mouse offset relative to the helper if "cursorAt" is supplied
            (o.cursorAt && this._adjustOffsetFromHelper(o.cursorAt));

            //Set a containment if given in the options
            this._setContainment();

            //Trigger event + callbacks
            if (this._trigger("start", event) === false) {
                this._clear();
                return false;
            }

            //Recache the helper size
            this._cacheHelperProportions();

            //Prepare the droppable offsets
            if ($.ui.ddmanager && !o.dropBehaviour) {
                $.ui.ddmanager.prepareOffsets(this, event);
            }


            this._mouseDrag(event, true); //Execute the drag once - this causes the helper not to be visible before getting its correct position

            //If the ddmanager is used for droppables, inform the manager that dragging has started (see #5003)
            if ($.ui.ddmanager) {
                $.ui.ddmanager.dragStart(this, event);
            }

            return true;
        },

        _mouseDrag: function (event, noPropagation) {
            // reset any necessary cached properties (see #5009)
            if (this.offsetParentCssPosition === "fixed") {
                this.offset.parent = this._getParentOffset();
            }

            //Compute the helpers position
            this.position = this._generatePosition(event);
            this.positionAbs = this._convertPositionTo("absolute");

            //Call plugins and callbacks and use the resulting position if something is returned
            if (!noPropagation) {
                var ui = this._uiHash();
                if (this._trigger("drag", event, ui) === false) {
                    this._mouseUp({});
                    return false;
                }
                this.position = ui.position;
            }

            if (!this.options.axis || this.options.axis !== "y") {
                this.helper[0].style.left = this.position.left + "px";
            }
            if (!this.options.axis || this.options.axis !== "x") {
                this.helper[0].style.top = this.position.top + "px";
            }
            if ($.ui.ddmanager) {
                $.ui.ddmanager.drag(this, event);
            }

            return false;
        },

        _mouseStop: function (event) {

            //If we are using droppables, inform the manager about the drop
            var that = this,
                dropped = false;
            if ($.ui.ddmanager && !this.options.dropBehaviour) {
                dropped = $.ui.ddmanager.drop(this, event);
            }

            //if a drop comes from outside (a sortable)
            if (this.dropped) {
                dropped = this.dropped;
                this.dropped = false;
            }

            //if the original element is no longer in the DOM don't bother to continue (see #8269)
            if (this.options.helper === "original" && !$.contains(this.element[0].ownerDocument, this.element[0])) {
                return false;
            }

            if ((this.options.revert === "invalid" && !dropped) || (this.options.revert === "valid" && dropped) || this.options.revert === true || ($.isFunction(this.options.revert) && this.options.revert.call(this.element, dropped))) {
                $(this.helper).animate(this.originalPosition, parseInt(this.options.revertDuration, 10), function () {
                    if (that._trigger("stop", event) !== false) {
                        that._clear();
                    }
                });
            } else {
                if (this._trigger("stop", event) !== false) {
                    this._clear();
                }
            }

            return false;
        },

        _mouseUp: function (event) {
            //Remove frame helpers
            $("div.ui-draggable-iframeFix").each(function () {
                this.parentNode.removeChild(this);
            });

            //If the ddmanager is used for droppables, inform the manager that dragging has stopped (see #5003)
            if ($.ui.ddmanager) {
                $.ui.ddmanager.dragStop(this, event);
            }

            return $.ui.mouse.prototype._mouseUp.call(this, event);
        },

        cancel: function () {

            if (this.helper.is(".ui-draggable-dragging")) {
                this._mouseUp({});
            } else {
                this._clear();
            }

            return this;

        },

        _getHandle: function (event) {
            return this.options.handle ?
                !!$(event.target).closest(this.element.find(this.options.handle)).length :
                true;
        },

        _createHelper: function (event) {

            var o = this.options,
                helper = $.isFunction(o.helper) ? $(o.helper.apply(this.element[0], [event])) : (o.helper === "clone" ? this.element.clone().removeAttr("id") : this.element);

            if (!helper.parents("body").length) {
                helper.appendTo((o.appendTo === "parent" ? this.element[0].parentNode : o.appendTo));
            }

            if (helper[0] !== this.element[0] && !(/(fixed|absolute)/).test(helper.css("position"))) {
                helper.css("position", "absolute");
            }

            return helper;

        },

        _adjustOffsetFromHelper: function (obj) {
            if (typeof obj === "string") {
                obj = obj.split(" ");
            }
            if ($.isArray(obj)) {
                obj = {left: +obj[0], top: +obj[1] || 0};
            }
            if ("left" in obj) {
                this.offset.click.left = obj.left + this.margins.left;
            }
            if ("right" in obj) {
                this.offset.click.left = this.helperProportions.width - obj.right + this.margins.left;
            }
            if ("top" in obj) {
                this.offset.click.top = obj.top + this.margins.top;
            }
            if ("bottom" in obj) {
                this.offset.click.top = this.helperProportions.height - obj.bottom + this.margins.top;
            }
        },

        _getParentOffset: function () {

            //Get the offsetParent and cache its position
            var po = this.offsetParent.offset();

            // This is a special case where we need to modify a offset calculated on start, since the following happened:
            // 1. The position of the helper is absolute, so it's position is calculated based on the next positioned parent
            // 2. The actual offset parent is a child of the scroll parent, and the scroll parent isn't the document, which means that
            //    the scroll is included in the initial calculation of the offset of the parent, and never recalculated upon drag
            if (this.cssPosition === "absolute" && this.scrollParent[0] !== document && $.contains(this.scrollParent[0], this.offsetParent[0])) {
                po.left += this.scrollParent.scrollLeft();
                po.top += this.scrollParent.scrollTop();
            }

            //This needs to be actually done for all browsers, since pageX/pageY includes this information
            //Ugly IE fix
            if ((this.offsetParent[0] === document.body) ||
                (this.offsetParent[0].tagName && this.offsetParent[0].tagName.toLowerCase() === "html" && $.ui.ie)) {
                po = {top: 0, left: 0};
            }

            return {
                top: po.top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0),
                left: po.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0)
            };

        },

        _getRelativeOffset: function () {

            if (this.cssPosition === "relative") {
                var p = this.element.position();
                return {
                    top: p.top - (parseInt(this.helper.css("top"), 10) || 0) + this.scrollParent.scrollTop(),
                    left: p.left - (parseInt(this.helper.css("left"), 10) || 0) + this.scrollParent.scrollLeft()
                };
            } else {
                return {top: 0, left: 0};
            }

        },

        _cacheMargins: function () {
            this.margins = {
                left: (parseInt(this.element.css("marginLeft"), 10) || 0),
                top: (parseInt(this.element.css("marginTop"), 10) || 0),
                right: (parseInt(this.element.css("marginRight"), 10) || 0),
                bottom: (parseInt(this.element.css("marginBottom"), 10) || 0)
            };
        },

        _cacheHelperProportions: function () {
            this.helperProportions = {
                width: this.helper.outerWidth(),
                height: this.helper.outerHeight()
            };
        },

        _setContainment: function () {

            var over, c, ce,
                o = this.options;

            if (!o.containment) {
                this.containment = null;
                return;
            }

            if (o.containment === "window") {
                this.containment = [
                    $(window).scrollLeft() - this.offset.relative.left - this.offset.parent.left,
                    $(window).scrollTop() - this.offset.relative.top - this.offset.parent.top,
                    $(window).scrollLeft() + $(window).width() - this.helperProportions.width - this.margins.left,
                    $(window).scrollTop() + ( $(window).height() || document.body.parentNode.scrollHeight ) - this.helperProportions.height - this.margins.top
                ];
                return;
            }

            if (o.containment === "document") {
                this.containment = [
                    0,
                    0,
                    $(document).width() - this.helperProportions.width - this.margins.left,
                    ( $(document).height() || document.body.parentNode.scrollHeight ) - this.helperProportions.height - this.margins.top
                ];
                return;
            }

            if (o.containment.constructor === Array) {
                this.containment = o.containment;
                return;
            }

            if (o.containment === "parent") {
                o.containment = this.helper[0].parentNode;
            }

            c = $(o.containment);
            ce = c[0];

            if (!ce) {
                return;
            }

            over = c.css("overflow") !== "hidden";

            this.containment = [
                ( parseInt(c.css("borderLeftWidth"), 10) || 0 ) + ( parseInt(c.css("paddingLeft"), 10) || 0 ),
                ( parseInt(c.css("borderTopWidth"), 10) || 0 ) + ( parseInt(c.css("paddingTop"), 10) || 0 ),
                ( over ? Math.max(ce.scrollWidth, ce.offsetWidth) : ce.offsetWidth ) - ( parseInt(c.css("borderRightWidth"), 10) || 0 ) - ( parseInt(c.css("paddingRight"), 10) || 0 ) - this.helperProportions.width - this.margins.left - this.margins.right,
                ( over ? Math.max(ce.scrollHeight, ce.offsetHeight) : ce.offsetHeight ) - ( parseInt(c.css("borderBottomWidth"), 10) || 0 ) - ( parseInt(c.css("paddingBottom"), 10) || 0 ) - this.helperProportions.height - this.margins.top - this.margins.bottom
            ];
            this.relative_container = c;
        },

        _convertPositionTo: function (d, pos) {

            if (!pos) {
                pos = this.position;
            }

            var mod = d === "absolute" ? 1 : -1,
                scroll = this.cssPosition === "absolute" && !( this.scrollParent[0] !== document && $.contains(this.scrollParent[0], this.offsetParent[0]) ) ? this.offsetParent : this.scrollParent;

            //Cache the scroll
            if (!this.offset.scroll) {
                this.offset.scroll = {top: scroll.scrollTop(), left: scroll.scrollLeft()};
            }

            return {
                top: (
                    pos.top +																// The absolute mouse position
                    this.offset.relative.top * mod +										// Only for relative positioned nodes: Relative offset from element to offset parent
                    this.offset.parent.top * mod -										// The offsetParent's offset without borders (offset + border)
                    ( ( this.cssPosition === "fixed" ? -this.scrollParent.scrollTop() : this.offset.scroll.top ) * mod )
                ),
                left: (
                    pos.left +																// The absolute mouse position
                    this.offset.relative.left * mod +										// Only for relative positioned nodes: Relative offset from element to offset parent
                    this.offset.parent.left * mod -										// The offsetParent's offset without borders (offset + border)
                    ( ( this.cssPosition === "fixed" ? -this.scrollParent.scrollLeft() : this.offset.scroll.left ) * mod )
                )
            };

        },

        _generatePosition: function (event) {

            var containment, co, top, left,
                o = this.options,
                scroll = this.cssPosition === "absolute" && !( this.scrollParent[0] !== document && $.contains(this.scrollParent[0], this.offsetParent[0]) ) ? this.offsetParent : this.scrollParent,
                pageX = event.pageX,
                pageY = event.pageY;

            //Cache the scroll
            if (!this.offset.scroll) {
                this.offset.scroll = {top: scroll.scrollTop(), left: scroll.scrollLeft()};
            }

            /*
             * - Position constraining -
             * Constrain the position to a mix of grid, containment.
             */

            // If we are not dragging yet, we won't check for options
            if (this.originalPosition) {
                if (this.containment) {
                    if (this.relative_container) {
                        co = this.relative_container.offset();
                        containment = [
                            this.containment[0] + co.left,
                            this.containment[1] + co.top,
                            this.containment[2] + co.left,
                            this.containment[3] + co.top
                        ];
                    }
                    else {
                        containment = this.containment;
                    }

                    if (event.pageX - this.offset.click.left < containment[0]) {
                        pageX = containment[0] + this.offset.click.left;
                    }
                    if (event.pageY - this.offset.click.top < containment[1]) {
                        pageY = containment[1] + this.offset.click.top;
                    }
                    if (event.pageX - this.offset.click.left > containment[2]) {
                        pageX = containment[2] + this.offset.click.left;
                    }
                    if (event.pageY - this.offset.click.top > containment[3]) {
                        pageY = containment[3] + this.offset.click.top;
                    }
                }

                if (o.grid) {
                    //Check for grid elements set to 0 to prevent divide by 0 error causing invalid argument errors in IE (see ticket #6950)
                    top = o.grid[1] ? this.originalPageY + Math.round((pageY - this.originalPageY) / o.grid[1]) * o.grid[1] : this.originalPageY;
                    pageY = containment ? ((top - this.offset.click.top >= containment[1] || top - this.offset.click.top > containment[3]) ? top : ((top - this.offset.click.top >= containment[1]) ? top - o.grid[1] : top + o.grid[1])) : top;

                    left = o.grid[0] ? this.originalPageX + Math.round((pageX - this.originalPageX) / o.grid[0]) * o.grid[0] : this.originalPageX;
                    pageX = containment ? ((left - this.offset.click.left >= containment[0] || left - this.offset.click.left > containment[2]) ? left : ((left - this.offset.click.left >= containment[0]) ? left - o.grid[0] : left + o.grid[0])) : left;
                }

            }

            return {
                top: (
                    pageY -																	// The absolute mouse position
                    this.offset.click.top -												// Click offset (relative to the element)
                    this.offset.relative.top -												// Only for relative positioned nodes: Relative offset from element to offset parent
                    this.offset.parent.top +												// The offsetParent's offset without borders (offset + border)
                    ( this.cssPosition === "fixed" ? -this.scrollParent.scrollTop() : this.offset.scroll.top )
                ),
                left: (
                    pageX -																	// The absolute mouse position
                    this.offset.click.left -												// Click offset (relative to the element)
                    this.offset.relative.left -												// Only for relative positioned nodes: Relative offset from element to offset parent
                    this.offset.parent.left +												// The offsetParent's offset without borders (offset + border)
                    ( this.cssPosition === "fixed" ? -this.scrollParent.scrollLeft() : this.offset.scroll.left )
                )
            };

        },

        _clear: function () {
            this.helper.removeClass("ui-draggable-dragging");
            if (this.helper[0] !== this.element[0] && !this.cancelHelperRemoval) {
                this.helper.remove();
            }
            this.helper = null;
            this.cancelHelperRemoval = false;
        },

        // From now on bulk stuff - mainly helpers

        _trigger: function (type, event, ui) {
            ui = ui || this._uiHash();
            $.ui.plugin.call(this, type, [event, ui]);
            //The absolute position has to be recalculated after plugins
            if (type === "drag") {
                this.positionAbs = this._convertPositionTo("absolute");
            }
            return $.Widget.prototype._trigger.call(this, type, event, ui);
        },

        plugins: {},

        _uiHash: function () {
            return {
                helper: this.helper,
                position: this.position,
                originalPosition: this.originalPosition,
                offset: this.positionAbs
            };
        }

    });

    $.ui.plugin.add("draggable", "connectToSortable", {
        start: function (event, ui) {

            var inst = $(this).data("ui-draggable"), o = inst.options,
                uiSortable = $.extend({}, ui, {item: inst.element});
            inst.sortables = [];
            $(o.connectToSortable).each(function () {
                var sortable = $.data(this, "ui-sortable");
                if (sortable && !sortable.options.disabled) {
                    inst.sortables.push({
                        instance: sortable,
                        shouldRevert: sortable.options.revert
                    });
                    sortable.refreshPositions();	// Call the sortable's refreshPositions at drag start to refresh the containerCache since the sortable container cache is used in drag and needs to be up to date (this will ensure it's initialised as well as being kept in step with any changes that might have happened on the page).
                    sortable._trigger("activate", event, uiSortable);
                }
            });

        },
        stop: function (event, ui) {

            //If we are still over the sortable, we fake the stop event of the sortable, but also remove helper
            var inst = $(this).data("ui-draggable"),
                uiSortable = $.extend({}, ui, {item: inst.element});

            $.each(inst.sortables, function () {
                if (this.instance.isOver) {

                    this.instance.isOver = 0;

                    inst.cancelHelperRemoval = true; //Don't remove the helper in the draggable instance
                    this.instance.cancelHelperRemoval = false; //Remove it in the sortable instance (so sortable plugins like revert still work)

                    //The sortable revert is supported, and we have to set a temporary dropped variable on the draggable to support revert: "valid/invalid"
                    if (this.shouldRevert) {
                        this.instance.options.revert = this.shouldRevert;
                    }

                    //Trigger the stop of the sortable
                    this.instance._mouseStop(event);

                    this.instance.options.helper = this.instance.options._helper;

                    //If the helper has been the original item, restore properties in the sortable
                    if (inst.options.helper === "original") {
                        this.instance.currentItem.css({top: "auto", left: "auto"});
                    }

                } else {
                    this.instance.cancelHelperRemoval = false; //Remove the helper in the sortable instance
                    this.instance._trigger("deactivate", event, uiSortable);
                }

            });

        },
        drag: function (event, ui) {

            var inst = $(this).data("ui-draggable"), that = this;

            $.each(inst.sortables, function () {

                var innermostIntersecting = false,
                    thisSortable = this;

                //Copy over some variables to allow calling the sortable's native _intersectsWith
                this.instance.positionAbs = inst.positionAbs;
                this.instance.helperProportions = inst.helperProportions;
                this.instance.offset.click = inst.offset.click;

                if (this.instance._intersectsWith(this.instance.containerCache)) {
                    innermostIntersecting = true;
                    $.each(inst.sortables, function () {
                        this.instance.positionAbs = inst.positionAbs;
                        this.instance.helperProportions = inst.helperProportions;
                        this.instance.offset.click = inst.offset.click;
                        if (this !== thisSortable &&
                            this.instance._intersectsWith(this.instance.containerCache) &&
                            $.contains(thisSortable.instance.element[0], this.instance.element[0])
                        ) {
                            innermostIntersecting = false;
                        }
                        return innermostIntersecting;
                    });
                }


                if (innermostIntersecting) {
                    //If it intersects, we use a little isOver variable and set it once, so our move-in stuff gets fired only once
                    if (!this.instance.isOver) {

                        this.instance.isOver = 1;
                        //Now we fake the start of dragging for the sortable instance,
                        //by cloning the list group item, appending it to the sortable and using it as inst.currentItem
                        //We can then fire the start event of the sortable with our passed browser event, and our own helper (so it doesn't create a new one)
                        this.instance.currentItem = $(that).clone().removeAttr("id").appendTo(this.instance.element).data("ui-sortable-item", true);
                        this.instance.options._helper = this.instance.options.helper; //Store helper option to later restore it
                        this.instance.options.helper = function () {
                            return ui.helper[0];
                        };

                        event.target = this.instance.currentItem[0];
                        this.instance._mouseCapture(event, true);
                        this.instance._mouseStart(event, true, true);

                        //Because the browser event is way off the new appended portlet, we modify a couple of variables to reflect the changes
                        this.instance.offset.click.top = inst.offset.click.top;
                        this.instance.offset.click.left = inst.offset.click.left;
                        this.instance.offset.parent.left -= inst.offset.parent.left - this.instance.offset.parent.left;
                        this.instance.offset.parent.top -= inst.offset.parent.top - this.instance.offset.parent.top;

                        inst._trigger("toSortable", event);
                        inst.dropped = this.instance.element; //draggable revert needs that
                        //hack so receive/update callbacks work (mostly)
                        inst.currentItem = inst.element;
                        this.instance.fromOutside = inst;

                    }

                    //Provided we did all the previous steps, we can fire the drag event of the sortable on every draggable drag, when it intersects with the sortable
                    if (this.instance.currentItem) {
                        this.instance._mouseDrag(event);
                    }

                } else {

                    //If it doesn't intersect with the sortable, and it intersected before,
                    //we fake the drag stop of the sortable, but make sure it doesn't remove the helper by using cancelHelperRemoval
                    if (this.instance.isOver) {

                        this.instance.isOver = 0;
                        this.instance.cancelHelperRemoval = true;

                        //Prevent reverting on this forced stop
                        this.instance.options.revert = false;

                        // The out event needs to be triggered independently
                        this.instance._trigger("out", event, this.instance._uiHash(this.instance));

                        this.instance._mouseStop(event, true);
                        this.instance.options.helper = this.instance.options._helper;

                        //Now we remove our currentItem, the list group clone again, and the placeholder, and animate the helper back to it's original size
                        this.instance.currentItem.remove();
                        if (this.instance.placeholder) {
                            this.instance.placeholder.remove();
                        }

                        inst._trigger("fromSortable", event);
                        inst.dropped = false; //draggable revert needs that
                    }

                }

            });

        }
    });

    $.ui.plugin.add("draggable", "cursor", {
        start: function () {
            var t = $("body"), o = $(this).data("ui-draggable").options;
            if (t.css("cursor")) {
                o._cursor = t.css("cursor");
            }
            t.css("cursor", o.cursor);
        },
        stop: function () {
            var o = $(this).data("ui-draggable").options;
            if (o._cursor) {
                $("body").css("cursor", o._cursor);
            }
        }
    });

    $.ui.plugin.add("draggable", "opacity", {
        start: function (event, ui) {
            var t = $(ui.helper), o = $(this).data("ui-draggable").options;
            if (t.css("opacity")) {
                o._opacity = t.css("opacity");
            }
            t.css("opacity", o.opacity);
        },
        stop: function (event, ui) {
            var o = $(this).data("ui-draggable").options;
            if (o._opacity) {
                $(ui.helper).css("opacity", o._opacity);
            }
        }
    });

    $.ui.plugin.add("draggable", "scroll", {
        start: function () {
            var i = $(this).data("ui-draggable");
            if (i.scrollParent[0] !== document && i.scrollParent[0].tagName !== "HTML") {
                i.overflowOffset = i.scrollParent.offset();
            }
        },
        drag: function (event) {

            var i = $(this).data("ui-draggable"), o = i.options, scrolled = false;

            if (i.scrollParent[0] !== document && i.scrollParent[0].tagName !== "HTML") {

                if (!o.axis || o.axis !== "x") {
                    if ((i.overflowOffset.top + i.scrollParent[0].offsetHeight) - event.pageY < o.scrollSensitivity) {
                        i.scrollParent[0].scrollTop = scrolled = i.scrollParent[0].scrollTop + o.scrollSpeed;
                    } else if (event.pageY - i.overflowOffset.top < o.scrollSensitivity) {
                        i.scrollParent[0].scrollTop = scrolled = i.scrollParent[0].scrollTop - o.scrollSpeed;
                    }
                }

                if (!o.axis || o.axis !== "y") {
                    if ((i.overflowOffset.left + i.scrollParent[0].offsetWidth) - event.pageX < o.scrollSensitivity) {
                        i.scrollParent[0].scrollLeft = scrolled = i.scrollParent[0].scrollLeft + o.scrollSpeed;
                    } else if (event.pageX - i.overflowOffset.left < o.scrollSensitivity) {
                        i.scrollParent[0].scrollLeft = scrolled = i.scrollParent[0].scrollLeft - o.scrollSpeed;
                    }
                }

            } else {

                if (!o.axis || o.axis !== "x") {
                    if (event.pageY - $(document).scrollTop() < o.scrollSensitivity) {
                        scrolled = $(document).scrollTop($(document).scrollTop() - o.scrollSpeed);
                    } else if ($(window).height() - (event.pageY - $(document).scrollTop()) < o.scrollSensitivity) {
                        scrolled = $(document).scrollTop($(document).scrollTop() + o.scrollSpeed);
                    }
                }

                if (!o.axis || o.axis !== "y") {
                    if (event.pageX - $(document).scrollLeft() < o.scrollSensitivity) {
                        scrolled = $(document).scrollLeft($(document).scrollLeft() - o.scrollSpeed);
                    } else if ($(window).width() - (event.pageX - $(document).scrollLeft()) < o.scrollSensitivity) {
                        scrolled = $(document).scrollLeft($(document).scrollLeft() + o.scrollSpeed);
                    }
                }

            }

            if (scrolled !== false && $.ui.ddmanager && !o.dropBehaviour) {
                $.ui.ddmanager.prepareOffsets(i, event);
            }

        }
    });

    $.ui.plugin.add("draggable", "snap", {
        start: function () {

            var i = $(this).data("ui-draggable"),
                o = i.options;

            i.snapElements = [];

            $(o.snap.constructor !== String ? ( o.snap.items || ":data(ui-draggable)" ) : o.snap).each(function () {
                var $t = $(this),
                    $o = $t.offset();
                if (this !== i.element[0]) {
                    i.snapElements.push({
                        item: this,
                        width: $t.outerWidth(), height: $t.outerHeight(),
                        top: $o.top, left: $o.left
                    });
                }
            });

        },
        drag: function (event, ui) {

            var ts, bs, ls, rs, l, r, t, b, i, first,
                inst = $(this).data("ui-draggable"),
                o = inst.options,
                d = o.snapTolerance,
                x1 = ui.offset.left, x2 = x1 + inst.helperProportions.width,
                y1 = ui.offset.top, y2 = y1 + inst.helperProportions.height;

            for (i = inst.snapElements.length - 1; i >= 0; i--) {

                l = inst.snapElements[i].left;
                r = l + inst.snapElements[i].width;
                t = inst.snapElements[i].top;
                b = t + inst.snapElements[i].height;

                if (x2 < l - d || x1 > r + d || y2 < t - d || y1 > b + d || !$.contains(inst.snapElements[i].item.ownerDocument, inst.snapElements[i].item)) {
                    if (inst.snapElements[i].snapping) {
                        (inst.options.snap.release && inst.options.snap.release.call(inst.element, event, $.extend(inst._uiHash(), {snapItem: inst.snapElements[i].item})));
                    }
                    inst.snapElements[i].snapping = false;
                    continue;
                }

                if (o.snapMode !== "inner") {
                    ts = Math.abs(t - y2) <= d;
                    bs = Math.abs(b - y1) <= d;
                    ls = Math.abs(l - x2) <= d;
                    rs = Math.abs(r - x1) <= d;
                    if (ts) {
                        ui.position.top = inst._convertPositionTo("relative", {
                                top: t - inst.helperProportions.height,
                                left: 0
                            }).top - inst.margins.top;
                    }
                    if (bs) {
                        ui.position.top = inst._convertPositionTo("relative", {top: b, left: 0}).top - inst.margins.top;
                    }
                    if (ls) {
                        ui.position.left = inst._convertPositionTo("relative", {
                                top: 0,
                                left: l - inst.helperProportions.width
                            }).left - inst.margins.left;
                    }
                    if (rs) {
                        ui.position.left = inst._convertPositionTo("relative", {
                                top: 0,
                                left: r
                            }).left - inst.margins.left;
                    }
                }

                first = (ts || bs || ls || rs);

                if (o.snapMode !== "outer") {
                    ts = Math.abs(t - y1) <= d;
                    bs = Math.abs(b - y2) <= d;
                    ls = Math.abs(l - x1) <= d;
                    rs = Math.abs(r - x2) <= d;
                    if (ts) {
                        ui.position.top = inst._convertPositionTo("relative", {top: t, left: 0}).top - inst.margins.top;
                    }
                    if (bs) {
                        ui.position.top = inst._convertPositionTo("relative", {
                                top: b - inst.helperProportions.height,
                                left: 0
                            }).top - inst.margins.top;
                    }
                    if (ls) {
                        ui.position.left = inst._convertPositionTo("relative", {
                                top: 0,
                                left: l
                            }).left - inst.margins.left;
                    }
                    if (rs) {
                        ui.position.left = inst._convertPositionTo("relative", {
                                top: 0,
                                left: r - inst.helperProportions.width
                            }).left - inst.margins.left;
                    }
                }

                if (!inst.snapElements[i].snapping && (ts || bs || ls || rs || first)) {
                    (inst.options.snap.snap && inst.options.snap.snap.call(inst.element, event, $.extend(inst._uiHash(), {snapItem: inst.snapElements[i].item})));
                }
                inst.snapElements[i].snapping = (ts || bs || ls || rs || first);

            }

        }
    });

    $.ui.plugin.add("draggable", "stack", {
        start: function () {
            var min,
                o = this.data("ui-draggable").options,
                group = $.makeArray($(o.stack)).sort(function (a, b) {
                    return (parseInt($(a).css("zIndex"), 10) || 0) - (parseInt($(b).css("zIndex"), 10) || 0);
                });

            if (!group.length) {
                return;
            }

            min = parseInt($(group[0]).css("zIndex"), 10) || 0;
            $(group).each(function (i) {
                $(this).css("zIndex", min + i);
            });
            this.css("zIndex", (min + group.length));
        }
    });

    $.ui.plugin.add("draggable", "zIndex", {
        start: function (event, ui) {
            var t = $(ui.helper), o = $(this).data("ui-draggable").options;
            if (t.css("zIndex")) {
                o._zIndex = t.css("zIndex");
            }
            t.css("zIndex", o.zIndex);
        },
        stop: function (event, ui) {
            var o = $(this).data("ui-draggable").options;
            if (o._zIndex) {
                $(ui.helper).css("zIndex", o._zIndex);
            }
        }
    });

})(jQuery);
(function ($, undefined) {

    function num(v) {
        return parseInt(v, 10) || 0;
    }

    function isNumber(value) {
        return !isNaN(parseInt(value, 10));
    }

    $.widget("ui.resizable", $.ui.mouse, {
        version: "1.10.3",
        widgetEventPrefix: "resize",
        options: {
            alsoResize: false,
            animate: false,
            animateDuration: "slow",
            animateEasing: "swing",
            aspectRatio: false,
            autoHide: false,
            containment: false,
            ghost: false,
            grid: false,
            handles: "e,s,se",
            helper: false,
            maxHeight: null,
            maxWidth: null,
            minHeight: 10,
            minWidth: 10,
            // See #7960
            zIndex: 90,

            // callbacks
            resize: null,
            start: null,
            stop: null
        },
        _create: function () {

            var n, i, handle, axis, hname,
                that = this,
                o = this.options;
            this.element.addClass("ui-resizable");

            $.extend(this, {
                _aspectRatio: !!(o.aspectRatio),
                aspectRatio: o.aspectRatio,
                originalElement: this.element,
                _proportionallyResizeElements: [],
                _helper: o.helper || o.ghost || o.animate ? o.helper || "ui-resizable-helper" : null
            });

            //Wrap the element if it cannot hold child nodes
            if (this.element[0].nodeName.match(/canvas|textarea|input|select|button|img/i)) {

                //Create a wrapper element and set the wrapper to the new current internal element
                this.element.wrap(
                    $("<div class='ui-wrapper' style='overflow: hidden;'></div>").css({
                        position: this.element.css("position"),
                        width: this.element.outerWidth(),
                        height: this.element.outerHeight(),
                        top: this.element.css("top"),
                        left: this.element.css("left")
                    })
                );

                //Overwrite the original this.element
                this.element = this.element.parent().data(
                    "ui-resizable", this.element.data("ui-resizable")
                );

                this.elementIsWrapper = true;

                //Move margins to the wrapper
                this.element.css({
                    marginLeft: this.originalElement.css("marginLeft"),
                    marginTop: this.originalElement.css("marginTop"),
                    marginRight: this.originalElement.css("marginRight"),
                    marginBottom: this.originalElement.css("marginBottom")
                });
                this.originalElement.css({marginLeft: 0, marginTop: 0, marginRight: 0, marginBottom: 0});

                //Prevent Safari textarea resize
                this.originalResizeStyle = this.originalElement.css("resize");
                this.originalElement.css("resize", "none");

                //Push the actual element to our proportionallyResize internal array
                this._proportionallyResizeElements.push(this.originalElement.css({
                    position: "static",
                    zoom: 1,
                    display: "block"
                }));

                // avoid IE jump (hard set the margin)
                this.originalElement.css({margin: this.originalElement.css("margin")});

                // fix handlers offset
                this._proportionallyResize();

            }

            this.handles = o.handles || (!$(".ui-resizable-handle", this.element).length ? "e,s,se" : {
                        n: ".ui-resizable-n",
                        e: ".ui-resizable-e",
                        s: ".ui-resizable-s",
                        w: ".ui-resizable-w",
                        se: ".ui-resizable-se",
                        sw: ".ui-resizable-sw",
                        ne: ".ui-resizable-ne",
                        nw: ".ui-resizable-nw"
                    });
            if (this.handles.constructor === String) {

                if (this.handles === "all") {
                    this.handles = "n,e,s,w,se,sw,ne,nw";
                }

                n = this.handles.split(",");
                this.handles = {};

                for (i = 0; i < n.length; i++) {

                    handle = $.trim(n[i]);
                    hname = "ui-resizable-" + handle;
                    axis = $("<div class='ui-resizable-handle " + hname + "'></div>");

                    // Apply zIndex to all handles - see #7960
                    axis.css({zIndex: o.zIndex});

                    //TODO : What's going on here?
                    if ("se" === handle) {
                        axis.addClass("ui-icon ui-icon-gripsmall-diagonal-se");
                    }

                    //Insert into internal handles object and append to element
                    this.handles[handle] = ".ui-resizable-" + handle;
                    this.element.append(axis);
                }

            }

            this._renderAxis = function (target) {

                var i, axis, padPos, padWrapper;

                target = target || this.element;

                for (i in this.handles) {

                    if (this.handles[i].constructor === String) {
                        this.handles[i] = $(this.handles[i], this.element).show();
                    }

                    //Apply pad to wrapper element, needed to fix axis position (textarea, inputs, scrolls)
                    if (this.elementIsWrapper && this.originalElement[0].nodeName.match(/textarea|input|select|button/i)) {

                        axis = $(this.handles[i], this.element);

                        //Checking the correct pad and border
                        padWrapper = /sw|ne|nw|se|n|s/.test(i) ? axis.outerHeight() : axis.outerWidth();

                        //The padding type i have to apply...
                        padPos = ["padding",
                            /ne|nw|n/.test(i) ? "Top" :
                                /se|sw|s/.test(i) ? "Bottom" :
                                    /^e$/.test(i) ? "Right" : "Left"].join("");

                        target.css(padPos, padWrapper);

                        this._proportionallyResize();

                    }

                    //TODO: What's that good for? There's not anything to be executed left
                    if (!$(this.handles[i]).length) {
                        continue;
                    }
                }
            };

            //TODO: make renderAxis a prototype function
            this._renderAxis(this.element);

            this._handles = $(".ui-resizable-handle", this.element)
                .disableSelection();

            //Matching axis name
            this._handles.mouseover(function () {
                if (!that.resizing) {
                    if (this.className) {
                        axis = this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i);
                    }
                    //Axis, default = se
                    that.axis = axis && axis[1] ? axis[1] : "se";
                }
            });

            //If we want to auto hide the elements
            if (o.autoHide) {
                this._handles.hide();
                $(this.element)
                    .addClass("ui-resizable-autohide")
                    .mouseenter(function () {
                        if (o.disabled) {
                            return;
                        }
                        $(this).removeClass("ui-resizable-autohide");
                        that._handles.show();
                    })
                    .mouseleave(function () {
                        if (o.disabled) {
                            return;
                        }
                        if (!that.resizing) {
                            $(this).addClass("ui-resizable-autohide");
                            that._handles.hide();
                        }
                    });
            }

            //Initialize the mouse interaction
            this._mouseInit();

        },

        _destroy: function () {

            this._mouseDestroy();

            var wrapper,
                _destroy = function (exp) {
                    $(exp).removeClass("ui-resizable ui-resizable-disabled ui-resizable-resizing")
                        .removeData("resizable").removeData("ui-resizable").unbind(".resizable").find(".ui-resizable-handle").remove();
                };

            //TODO: Unwrap at same DOM position
            if (this.elementIsWrapper) {
                _destroy(this.element);
                wrapper = this.element;
                this.originalElement.css({
                    position: wrapper.css("position"),
                    width: wrapper.outerWidth(),
                    height: wrapper.outerHeight(),
                    top: wrapper.css("top"),
                    left: wrapper.css("left")
                }).insertAfter(wrapper);
                wrapper.remove();
            }

            this.originalElement.css("resize", this.originalResizeStyle);
            _destroy(this.originalElement);

            return this;
        },

        _mouseCapture: function (event) {
            var i, handle,
                capture = false;

            for (i in this.handles) {
                handle = $(this.handles[i])[0];
                if (handle === event.target || $.contains(handle, event.target)) {
                    capture = true;
                }
            }

            return !this.options.disabled && capture;
        },

        _mouseStart: function (event) {

            var curleft, curtop, cursor,
                o = this.options,
                iniPos = this.element.position(),
                el = this.element;

            this.resizing = true;

            // bugfix for http://dev.jquery.com/ticket/1749
            if ((/absolute/).test(el.css("position"))) {
                el.css({position: "absolute", top: el.css("top"), left: el.css("left")});
            } else if (el.is(".ui-draggable")) {
                el.css({position: "absolute", top: iniPos.top, left: iniPos.left});
            }

            this._renderProxy();

            curleft = num(this.helper.css("left"));
            curtop = num(this.helper.css("top"));

            if (o.containment) {
                curleft += $(o.containment).scrollLeft() || 0;
                curtop += $(o.containment).scrollTop() || 0;
            }

            //Store needed variables
            this.offset = this.helper.offset();
            this.position = {left: curleft, top: curtop};
            this.size = this._helper ? {width: el.outerWidth(), height: el.outerHeight()} : {
                    width: el.width(),
                    height: el.height()
                };
            this.originalSize = this._helper ? {width: el.outerWidth(), height: el.outerHeight()} : {
                    width: el.width(),
                    height: el.height()
                };
            this.originalPosition = {left: curleft, top: curtop};
            this.sizeDiff = {width: el.outerWidth() - el.width(), height: el.outerHeight() - el.height()};
            this.originalMousePosition = {left: event.pageX, top: event.pageY};

            //Aspect Ratio
            this.aspectRatio = (typeof o.aspectRatio === "number") ? o.aspectRatio : ((this.originalSize.width / this.originalSize.height) || 1);

            cursor = $(".ui-resizable-" + this.axis).css("cursor");
            $("body").css("cursor", cursor === "auto" ? this.axis + "-resize" : cursor);

            el.addClass("ui-resizable-resizing");
            this._propagate("start", event);
            return true;
        },

        _mouseDrag: function (event) {

            //Increase performance, avoid regex
            var data,
                el = this.helper, props = {},
                smp = this.originalMousePosition,
                a = this.axis,
                prevTop = this.position.top,
                prevLeft = this.position.left,
                prevWidth = this.size.width,
                prevHeight = this.size.height,
                dx = (event.pageX - smp.left) || 0,
                dy = (event.pageY - smp.top) || 0,
                trigger = this._change[a];

            if (!trigger) {
                return false;
            }

            // Calculate the attrs that will be change
            data = trigger.apply(this, [event, dx, dy]);

            // Put this in the mouseDrag handler since the user can start pressing shift while resizing
            this._updateVirtualBoundaries(event.shiftKey);
            if (this._aspectRatio || event.shiftKey) {
                data = this._updateRatio(data, event);
            }

            data = this._respectSize(data, event);

            this._updateCache(data);

            // plugins callbacks need to be called first
            this._propagate("resize", event);

            if (this.position.top !== prevTop) {
                props.top = this.position.top + "px";
            }
            if (this.position.left !== prevLeft) {
                props.left = this.position.left + "px";
            }
            if (this.size.width !== prevWidth) {
                props.width = this.size.width + "px";
            }
            if (this.size.height !== prevHeight) {
                props.height = this.size.height + "px";
            }
            el.css(props);

            if (!this._helper && this._proportionallyResizeElements.length) {
                this._proportionallyResize();
            }

            // Call the user callback if the element was resized
            if (!$.isEmptyObject(props)) {
                this._trigger("resize", event, this.ui());
            }

            return false;
        },

        _mouseStop: function (event) {

            this.resizing = false;
            var pr, ista, soffseth, soffsetw, s, left, top,
                o = this.options, that = this;

            if (this._helper) {

                pr = this._proportionallyResizeElements;
                ista = pr.length && (/textarea/i).test(pr[0].nodeName);
                soffseth = ista && $.ui.hasScroll(pr[0], "left") /* TODO - jump height */ ? 0 : that.sizeDiff.height;
                soffsetw = ista ? 0 : that.sizeDiff.width;

                s = {width: (that.helper.width() - soffsetw), height: (that.helper.height() - soffseth)};
                left = (parseInt(that.element.css("left"), 10) + (that.position.left - that.originalPosition.left)) || null;
                top = (parseInt(that.element.css("top"), 10) + (that.position.top - that.originalPosition.top)) || null;

                if (!o.animate) {
                    this.element.css($.extend(s, {top: top, left: left}));
                }

                that.helper.height(that.size.height);
                that.helper.width(that.size.width);

                if (this._helper && !o.animate) {
                    this._proportionallyResize();
                }
            }

            $("body").css("cursor", "auto");

            this.element.removeClass("ui-resizable-resizing");

            this._propagate("stop", event);

            if (this._helper) {
                this.helper.remove();
            }

            return false;

        },

        _updateVirtualBoundaries: function (forceAspectRatio) {
            var pMinWidth, pMaxWidth, pMinHeight, pMaxHeight, b,
                o = this.options;

            b = {
                minWidth: isNumber(o.minWidth) ? o.minWidth : 0,
                maxWidth: isNumber(o.maxWidth) ? o.maxWidth : Infinity,
                minHeight: isNumber(o.minHeight) ? o.minHeight : 0,
                maxHeight: isNumber(o.maxHeight) ? o.maxHeight : Infinity
            };

            if (this._aspectRatio || forceAspectRatio) {
                // We want to create an enclosing box whose aspect ration is the requested one
                // First, compute the "projected" size for each dimension based on the aspect ratio and other dimension
                pMinWidth = b.minHeight * this.aspectRatio;
                pMinHeight = b.minWidth / this.aspectRatio;
                pMaxWidth = b.maxHeight * this.aspectRatio;
                pMaxHeight = b.maxWidth / this.aspectRatio;

                if (pMinWidth > b.minWidth) {
                    b.minWidth = pMinWidth;
                }
                if (pMinHeight > b.minHeight) {
                    b.minHeight = pMinHeight;
                }
                if (pMaxWidth < b.maxWidth) {
                    b.maxWidth = pMaxWidth;
                }
                if (pMaxHeight < b.maxHeight) {
                    b.maxHeight = pMaxHeight;
                }
            }
            this._vBoundaries = b;
        },

        _updateCache: function (data) {
            this.offset = this.helper.offset();
            if (isNumber(data.left)) {
                this.position.left = data.left;
            }
            if (isNumber(data.top)) {
                this.position.top = data.top;
            }
            if (isNumber(data.height)) {
                this.size.height = data.height;
            }
            if (isNumber(data.width)) {
                this.size.width = data.width;
            }
        },

        _updateRatio: function (data) {

            var cpos = this.position,
                csize = this.size,
                a = this.axis;

            if (isNumber(data.height)) {
                data.width = (data.height * this.aspectRatio);
            } else if (isNumber(data.width)) {
                data.height = (data.width / this.aspectRatio);
            }

            if (a === "sw") {
                data.left = cpos.left + (csize.width - data.width);
                data.top = null;
            }
            if (a === "nw") {
                data.top = cpos.top + (csize.height - data.height);
                data.left = cpos.left + (csize.width - data.width);
            }

            return data;
        },

        _respectSize: function (data) {

            var o = this._vBoundaries,
                a = this.axis,
                ismaxw = isNumber(data.width) && o.maxWidth && (o.maxWidth < data.width), ismaxh = isNumber(data.height) && o.maxHeight && (o.maxHeight < data.height),
                isminw = isNumber(data.width) && o.minWidth && (o.minWidth > data.width), isminh = isNumber(data.height) && o.minHeight && (o.minHeight > data.height),
                dw = this.originalPosition.left + this.originalSize.width,
                dh = this.position.top + this.size.height,
                cw = /sw|nw|w/.test(a), ch = /nw|ne|n/.test(a);
            if (isminw) {
                data.width = o.minWidth;
            }
            if (isminh) {
                data.height = o.minHeight;
            }
            if (ismaxw) {
                data.width = o.maxWidth;
            }
            if (ismaxh) {
                data.height = o.maxHeight;
            }

            if (isminw && cw) {
                data.left = dw - o.minWidth;
            }
            if (ismaxw && cw) {
                data.left = dw - o.maxWidth;
            }
            if (isminh && ch) {
                data.top = dh - o.minHeight;
            }
            if (ismaxh && ch) {
                data.top = dh - o.maxHeight;
            }

            // fixing jump error on top/left - bug #2330
            if (!data.width && !data.height && !data.left && data.top) {
                data.top = null;
            } else if (!data.width && !data.height && !data.top && data.left) {
                data.left = null;
            }

            return data;
        },

        _proportionallyResize: function () {

            if (!this._proportionallyResizeElements.length) {
                return;
            }

            var i, j, borders, paddings, prel,
                element = this.helper || this.element;

            for (i = 0; i < this._proportionallyResizeElements.length; i++) {

                prel = this._proportionallyResizeElements[i];

                if (!this.borderDif) {
                    this.borderDif = [];
                    borders = [prel.css("borderTopWidth"), prel.css("borderRightWidth"), prel.css("borderBottomWidth"), prel.css("borderLeftWidth")];
                    paddings = [prel.css("paddingTop"), prel.css("paddingRight"), prel.css("paddingBottom"), prel.css("paddingLeft")];

                    for (j = 0; j < borders.length; j++) {
                        this.borderDif[j] = ( parseInt(borders[j], 10) || 0 ) + ( parseInt(paddings[j], 10) || 0 );
                    }
                }

                prel.css({
                    height: (element.height() - this.borderDif[0] - this.borderDif[2]) || 0,
                    width: (element.width() - this.borderDif[1] - this.borderDif[3]) || 0
                });

            }

        },

        _renderProxy: function () {

            var el = this.element, o = this.options;
            this.elementOffset = el.offset();

            if (this._helper) {

                this.helper = this.helper || $("<div style='overflow:hidden;'></div>");

                this.helper.addClass(this._helper).css({
                    width: this.element.outerWidth() - 1,
                    height: this.element.outerHeight() - 1,
                    position: "absolute",
                    left: this.elementOffset.left + "px",
                    top: this.elementOffset.top + "px",
                    zIndex: ++o.zIndex //TODO: Don't modify option
                });

                this.helper
                    .appendTo("body")
                    .disableSelection();

            } else {
                this.helper = this.element;
            }

        },

        _change: {
            e: function (event, dx) {
                return {width: this.originalSize.width + dx};
            },
            w: function (event, dx) {
                var cs = this.originalSize, sp = this.originalPosition;
                return {left: sp.left + dx, width: cs.width - dx};
            },
            n: function (event, dx, dy) {
                var cs = this.originalSize, sp = this.originalPosition;
                return {top: sp.top + dy, height: cs.height - dy};
            },
            s: function (event, dx, dy) {
                return {height: this.originalSize.height + dy};
            },
            se: function (event, dx, dy) {
                return $.extend(this._change.s.apply(this, arguments), this._change.e.apply(this, [event, dx, dy]));
            },
            sw: function (event, dx, dy) {
                return $.extend(this._change.s.apply(this, arguments), this._change.w.apply(this, [event, dx, dy]));
            },
            ne: function (event, dx, dy) {
                return $.extend(this._change.n.apply(this, arguments), this._change.e.apply(this, [event, dx, dy]));
            },
            nw: function (event, dx, dy) {
                return $.extend(this._change.n.apply(this, arguments), this._change.w.apply(this, [event, dx, dy]));
            }
        },

        _propagate: function (n, event) {
            $.ui.plugin.call(this, n, [event, this.ui()]);
            (n !== "resize" && this._trigger(n, event, this.ui()));
        },

        plugins: {},

        ui: function () {
            return {
                originalElement: this.originalElement,
                element: this.element,
                helper: this.helper,
                position: this.position,
                size: this.size,
                originalSize: this.originalSize,
                originalPosition: this.originalPosition
            };
        }

    });

    /*
     * Resizable Extensions
     */

    $.ui.plugin.add("resizable", "animate", {

        stop: function (event) {
            var that = $(this).data("ui-resizable"),
                o = that.options,
                pr = that._proportionallyResizeElements,
                ista = pr.length && (/textarea/i).test(pr[0].nodeName),
                soffseth = ista && $.ui.hasScroll(pr[0], "left") /* TODO - jump height */ ? 0 : that.sizeDiff.height,
                soffsetw = ista ? 0 : that.sizeDiff.width,
                style = {width: (that.size.width - soffsetw), height: (that.size.height - soffseth)},
                left = (parseInt(that.element.css("left"), 10) + (that.position.left - that.originalPosition.left)) || null,
                top = (parseInt(that.element.css("top"), 10) + (that.position.top - that.originalPosition.top)) || null;

            that.element.animate(
                $.extend(style, top && left ? {top: top, left: left} : {}), {
                    duration: o.animateDuration,
                    easing: o.animateEasing,
                    step: function () {

                        var data = {
                            width: parseInt(that.element.css("width"), 10),
                            height: parseInt(that.element.css("height"), 10),
                            top: parseInt(that.element.css("top"), 10),
                            left: parseInt(that.element.css("left"), 10)
                        };

                        if (pr && pr.length) {
                            $(pr[0]).css({width: data.width, height: data.height});
                        }

                        // propagating resize, and updating values for each animation step
                        that._updateCache(data);
                        that._propagate("resize", event);

                    }
                }
            );
        }

    });

    $.ui.plugin.add("resizable", "containment", {

        start: function () {
            var element, p, co, ch, cw, width, height,
                that = $(this).data("ui-resizable"),
                o = that.options,
                el = that.element,
                oc = o.containment,
                ce = (oc instanceof $) ? oc.get(0) : (/parent/.test(oc)) ? el.parent().get(0) : oc;

            if (!ce) {
                return;
            }

            that.containerElement = $(ce);

            if (/document/.test(oc) || oc === document) {
                that.containerOffset = {left: 0, top: 0};
                that.containerPosition = {left: 0, top: 0};

                that.parentData = {
                    element: $(document), left: 0, top: 0,
                    width: $(document).width(), height: $(document).height() || document.body.parentNode.scrollHeight
                };
            }

            // i'm a node, so compute top, left, right, bottom
            else {
                element = $(ce);
                p = [];
                $(["Top", "Right", "Left", "Bottom"]).each(function (i, name) {
                    p[i] = num(element.css("padding" + name));
                });

                that.containerOffset = element.offset();
                that.containerPosition = element.position();
                that.containerSize = {height: (element.innerHeight() - p[3]), width: (element.innerWidth() - p[1])};

                co = that.containerOffset;
                ch = that.containerSize.height;
                cw = that.containerSize.width;
                width = ($.ui.hasScroll(ce, "left") ? ce.scrollWidth : cw );
                height = ($.ui.hasScroll(ce) ? ce.scrollHeight : ch);

                that.parentData = {
                    element: ce, left: co.left, top: co.top, width: width, height: height
                };
            }
        },

        resize: function (event) {
            var woset, hoset, isParent, isOffsetRelative,
                that = $(this).data("ui-resizable"),
                o = that.options,
                co = that.containerOffset, cp = that.position,
                pRatio = that._aspectRatio || event.shiftKey,
                cop = {top: 0, left: 0}, ce = that.containerElement;

            if (ce[0] !== document && (/static/).test(ce.css("position"))) {
                cop = co;
            }

            if (cp.left < (that._helper ? co.left : 0)) {
                that.size.width = that.size.width + (that._helper ? (that.position.left - co.left) : (that.position.left - cop.left));
                if (pRatio) {
                    that.size.height = that.size.width / that.aspectRatio;
                }
                that.position.left = o.helper ? co.left : 0;
            }

            if (cp.top < (that._helper ? co.top : 0)) {
                that.size.height = that.size.height + (that._helper ? (that.position.top - co.top) : that.position.top);
                if (pRatio) {
                    that.size.width = that.size.height * that.aspectRatio;
                }
                that.position.top = that._helper ? co.top : 0;
            }

            that.offset.left = that.parentData.left + that.position.left;
            that.offset.top = that.parentData.top + that.position.top;

            woset = Math.abs((that._helper ? that.offset.left - cop.left : (that.offset.left - cop.left)) + that.sizeDiff.width);
            hoset = Math.abs((that._helper ? that.offset.top - cop.top : (that.offset.top - co.top)) + that.sizeDiff.height);

            isParent = that.containerElement.get(0) === that.element.parent().get(0);
            isOffsetRelative = /relative|absolute/.test(that.containerElement.css("position"));

            if (isParent && isOffsetRelative) {
                woset -= that.parentData.left;
            }

            if (woset + that.size.width >= that.parentData.width) {
                that.size.width = that.parentData.width - woset;
                if (pRatio) {
                    that.size.height = that.size.width / that.aspectRatio;
                }
            }

            if (hoset + that.size.height >= that.parentData.height) {
                that.size.height = that.parentData.height - hoset;
                if (pRatio) {
                    that.size.width = that.size.height * that.aspectRatio;
                }
            }
        },

        stop: function () {
            var that = $(this).data("ui-resizable"),
                o = that.options,
                co = that.containerOffset,
                cop = that.containerPosition,
                ce = that.containerElement,
                helper = $(that.helper),
                ho = helper.offset(),
                w = helper.outerWidth() - that.sizeDiff.width,
                h = helper.outerHeight() - that.sizeDiff.height;

            if (that._helper && !o.animate && (/relative/).test(ce.css("position"))) {
                $(this).css({left: ho.left - cop.left - co.left, width: w, height: h});
            }

            if (that._helper && !o.animate && (/static/).test(ce.css("position"))) {
                $(this).css({left: ho.left - cop.left - co.left, width: w, height: h});
            }

        }
    });

    $.ui.plugin.add("resizable", "alsoResize", {

        start: function () {
            var that = $(this).data("ui-resizable"),
                o = that.options,
                _store = function (exp) {
                    $(exp).each(function () {
                        var el = $(this);
                        el.data("ui-resizable-alsoresize", {
                            width: parseInt(el.width(), 10), height: parseInt(el.height(), 10),
                            left: parseInt(el.css("left"), 10), top: parseInt(el.css("top"), 10)
                        });
                    });
                };

            if (typeof(o.alsoResize) === "object" && !o.alsoResize.parentNode) {
                if (o.alsoResize.length) {
                    o.alsoResize = o.alsoResize[0];
                    _store(o.alsoResize);
                }
                else {
                    $.each(o.alsoResize, function (exp) {
                        _store(exp);
                    });
                }
            } else {
                _store(o.alsoResize);
            }
        },

        resize: function (event, ui) {
            var that = $(this).data("ui-resizable"),
                o = that.options,
                os = that.originalSize,
                op = that.originalPosition,
                delta = {
                    height: (that.size.height - os.height) || 0, width: (that.size.width - os.width) || 0,
                    top: (that.position.top - op.top) || 0, left: (that.position.left - op.left) || 0
                },

                _alsoResize = function (exp, c) {
                    $(exp).each(function () {
                        var el = $(this), start = $(this).data("ui-resizable-alsoresize"), style = {},
                            css = c && c.length ? c : el.parents(ui.originalElement[0]).length ? ["width", "height"] : ["width", "height", "top", "left"];

                        $.each(css, function (i, prop) {
                            var sum = (start[prop] || 0) + (delta[prop] || 0);
                            if (sum && sum >= 0) {
                                style[prop] = sum || null;
                            }
                        });

                        el.css(style);
                    });
                };

            if (typeof(o.alsoResize) === "object" && !o.alsoResize.nodeType) {
                $.each(o.alsoResize, function (exp, c) {
                    _alsoResize(exp, c);
                });
            } else {
                _alsoResize(o.alsoResize);
            }
        },

        stop: function () {
            $(this).removeData("resizable-alsoresize");
        }
    });

    $.ui.plugin.add("resizable", "ghost", {

        start: function () {

            var that = $(this).data("ui-resizable"), o = that.options, cs = that.size;

            that.ghost = that.originalElement.clone();
            that.ghost
                .css({
                    opacity: 0.25,
                    display: "block",
                    position: "relative",
                    height: cs.height,
                    width: cs.width,
                    margin: 0,
                    left: 0,
                    top: 0
                })
                .addClass("ui-resizable-ghost")
                .addClass(typeof o.ghost === "string" ? o.ghost : "");

            that.ghost.appendTo(that.helper);

        },

        resize: function () {
            var that = $(this).data("ui-resizable");
            if (that.ghost) {
                that.ghost.css({position: "relative", height: that.size.height, width: that.size.width});
            }
        },

        stop: function () {
            var that = $(this).data("ui-resizable");
            if (that.ghost && that.helper) {
                that.helper.get(0).removeChild(that.ghost.get(0));
            }
        }

    });

    $.ui.plugin.add("resizable", "grid", {

        resize: function () {
            var that = $(this).data("ui-resizable"),
                o = that.options,
                cs = that.size,
                os = that.originalSize,
                op = that.originalPosition,
                a = that.axis,
                grid = typeof o.grid === "number" ? [o.grid, o.grid] : o.grid,
                gridX = (grid[0] || 1),
                gridY = (grid[1] || 1),
                ox = Math.round((cs.width - os.width) / gridX) * gridX,
                oy = Math.round((cs.height - os.height) / gridY) * gridY,
                newWidth = os.width + ox,
                newHeight = os.height + oy,
                isMaxWidth = o.maxWidth && (o.maxWidth < newWidth),
                isMaxHeight = o.maxHeight && (o.maxHeight < newHeight),
                isMinWidth = o.minWidth && (o.minWidth > newWidth),
                isMinHeight = o.minHeight && (o.minHeight > newHeight);

            o.grid = grid;

            if (isMinWidth) {
                newWidth = newWidth + gridX;
            }
            if (isMinHeight) {
                newHeight = newHeight + gridY;
            }
            if (isMaxWidth) {
                newWidth = newWidth - gridX;
            }
            if (isMaxHeight) {
                newHeight = newHeight - gridY;
            }

            if (/^(se|s|e)$/.test(a)) {
                that.size.width = newWidth;
                that.size.height = newHeight;
            } else if (/^(ne)$/.test(a)) {
                that.size.width = newWidth;
                that.size.height = newHeight;
                that.position.top = op.top - oy;
            } else if (/^(sw)$/.test(a)) {
                that.size.width = newWidth;
                that.size.height = newHeight;
                that.position.left = op.left - ox;
            } else {
                that.size.width = newWidth;
                that.size.height = newHeight;
                that.position.top = op.top - oy;
                that.position.left = op.left - ox;
            }
        }

    });

})(jQuery);
(function ($, undefined) {

    var lastActive, startXPos, startYPos, clickDragged,
        baseClasses = "ui-button ui-widget ui-state-default ui-corner-all",
        stateClasses = "ui-state-hover ui-state-active ",
        typeClasses = "ui-button-icons-only ui-button-icon-only ui-button-text-icons ui-button-text-icon-primary ui-button-text-icon-secondary ui-button-text-only",
        formResetHandler = function () {
            var form = $(this);
            setTimeout(function () {
                form.find(":ui-button").button("refresh");
            }, 1);
        },
        radioGroup = function (radio) {
            var name = radio.name,
                form = radio.form,
                radios = $([]);
            if (name) {
                name = name.replace(/'/g, "\\'");
                if (form) {
                    radios = $(form).find("[name='" + name + "']");
                } else {
                    radios = $("[name='" + name + "']", radio.ownerDocument)
                        .filter(function () {
                            return !this.form;
                        });
                }
            }
            return radios;
        };

    $.widget("ui.button", {
        version: "1.10.3",
        defaultElement: "<button>",
        options: {
            disabled: null,
            text: true,
            label: null,
            icons: {
                primary: null,
                secondary: null
            }
        },
        _create: function () {
            this.element.closest("form")
                .unbind("reset" + this.eventNamespace)
                .bind("reset" + this.eventNamespace, formResetHandler);

            if (typeof this.options.disabled !== "boolean") {
                this.options.disabled = !!this.element.prop("disabled");
            } else {
                this.element.prop("disabled", this.options.disabled);
            }

            this._determineButtonType();
            this.hasTitle = !!this.buttonElement.attr("title");

            var that = this,
                options = this.options,
                toggleButton = this.type === "checkbox" || this.type === "radio",
                activeClass = !toggleButton ? "ui-state-active" : "",
                focusClass = "ui-state-focus";

            if (options.label === null) {
                options.label = (this.type === "input" ? this.buttonElement.val() : this.buttonElement.html());
            }

            this._hoverable(this.buttonElement);

            this.buttonElement
                .addClass(baseClasses)
                .attr("role", "button")
                .bind("mouseenter" + this.eventNamespace, function () {
                    if (options.disabled) {
                        return;
                    }
                    if (this === lastActive) {
                        $(this).addClass("ui-state-active");
                    }
                })
                .bind("mouseleave" + this.eventNamespace, function () {
                    if (options.disabled) {
                        return;
                    }
                    $(this).removeClass(activeClass);
                })
                .bind("click" + this.eventNamespace, function (event) {
                    if (options.disabled) {
                        event.preventDefault();
                        event.stopImmediatePropagation();
                    }
                });

            this.element
                .bind("focus" + this.eventNamespace, function () {
                    // no need to check disabled, focus won't be triggered anyway
                    that.buttonElement.addClass(focusClass);
                })
                .bind("blur" + this.eventNamespace, function () {
                    that.buttonElement.removeClass(focusClass);
                });

            if (toggleButton) {
                this.element.bind("change" + this.eventNamespace, function () {
                    if (clickDragged) {
                        return;
                    }
                    that.refresh();
                });
                // if mouse moves between mousedown and mouseup (drag) set clickDragged flag
                // prevents issue where button state changes but checkbox/radio checked state
                // does not in Firefox (see ticket #6970)
                this.buttonElement
                    .bind("mousedown" + this.eventNamespace, function (event) {
                        if (options.disabled) {
                            return;
                        }
                        clickDragged = false;
                        startXPos = event.pageX;
                        startYPos = event.pageY;
                    })
                    .bind("mouseup" + this.eventNamespace, function (event) {
                        if (options.disabled) {
                            return;
                        }
                        if (startXPos !== event.pageX || startYPos !== event.pageY) {
                            clickDragged = true;
                        }
                    });
            }

            if (this.type === "checkbox") {
                this.buttonElement.bind("click" + this.eventNamespace, function () {
                    if (options.disabled || clickDragged) {
                        return false;
                    }
                });
            } else if (this.type === "radio") {
                this.buttonElement.bind("click" + this.eventNamespace, function () {
                    if (options.disabled || clickDragged) {
                        return false;
                    }
                    $(this).addClass("ui-state-active");
                    that.buttonElement.attr("aria-pressed", "true");

                    var radio = that.element[0];
                    radioGroup(radio)
                        .not(radio)
                        .map(function () {
                            return $(this).button("widget")[0];
                        })
                        .removeClass("ui-state-active")
                        .attr("aria-pressed", "false");
                });
            } else {
                this.buttonElement
                    .bind("mousedown" + this.eventNamespace, function () {
                        if (options.disabled) {
                            return false;
                        }
                        $(this).addClass("ui-state-active");
                        lastActive = this;
                        that.document.one("mouseup", function () {
                            lastActive = null;
                        });
                    })
                    .bind("mouseup" + this.eventNamespace, function () {
                        if (options.disabled) {
                            return false;
                        }
                        $(this).removeClass("ui-state-active");
                    })
                    .bind("keydown" + this.eventNamespace, function (event) {
                        if (options.disabled) {
                            return false;
                        }
                        if (event.keyCode === $.ui.keyCode.SPACE || event.keyCode === $.ui.keyCode.ENTER) {
                            $(this).addClass("ui-state-active");
                        }
                    })
                    // see #8559, we bind to blur here in case the button element loses
                    // focus between keydown and keyup, it would be left in an "active" state
                    .bind("keyup" + this.eventNamespace + " blur" + this.eventNamespace, function () {
                        $(this).removeClass("ui-state-active");
                    });

                if (this.buttonElement.is("a")) {
                    this.buttonElement.keyup(function (event) {
                        if (event.keyCode === $.ui.keyCode.SPACE) {
                            // TODO pass through original event correctly (just as 2nd argument doesn't work)
                            $(this).click();
                        }
                    });
                }
            }

            // TODO: pull out $.Widget's handling for the disabled option into
            // $.Widget.prototype._setOptionDisabled so it's easy to proxy and can
            // be overridden by individual plugins
            this._setOption("disabled", options.disabled);
            this._resetButton();
        },

        _determineButtonType: function () {
            var ancestor, labelSelector, checked;

            if (this.element.is("[type=checkbox]")) {
                this.type = "checkbox";
            } else if (this.element.is("[type=radio]")) {
                this.type = "radio";
            } else if (this.element.is("input")) {
                this.type = "input";
            } else {
                this.type = "button";
            }

            if (this.type === "checkbox" || this.type === "radio") {
                // we don't search against the document in case the element
                // is disconnected from the DOM
                ancestor = this.element.parents().last();
                labelSelector = "label[for='" + this.element.attr("id") + "']";
                this.buttonElement = ancestor.find(labelSelector);
                if (!this.buttonElement.length) {
                    ancestor = ancestor.length ? ancestor.siblings() : this.element.siblings();
                    this.buttonElement = ancestor.filter(labelSelector);
                    if (!this.buttonElement.length) {
                        this.buttonElement = ancestor.find(labelSelector);
                    }
                }
                this.element.addClass("ui-helper-hidden-accessible");

                checked = this.element.is(":checked");
                if (checked) {
                    this.buttonElement.addClass("ui-state-active");
                }
                this.buttonElement.prop("aria-pressed", checked);
            } else {
                this.buttonElement = this.element;
            }
        },

        widget: function () {
            return this.buttonElement;
        },

        _destroy: function () {
            this.element
                .removeClass("ui-helper-hidden-accessible");
            this.buttonElement
                .removeClass(baseClasses + " " + stateClasses + " " + typeClasses)
                .removeAttr("role")
                .removeAttr("aria-pressed")
                .html(this.buttonElement.find(".ui-button-text").html());

            if (!this.hasTitle) {
                this.buttonElement.removeAttr("title");
            }
        },

        _setOption: function (key, value) {
            this._super(key, value);
            if (key === "disabled") {
                if (value) {
                    this.element.prop("disabled", true);
                } else {
                    this.element.prop("disabled", false);
                }
                return;
            }
            this._resetButton();
        },

        refresh: function () {
            //See #8237 & #8828
            var isDisabled = this.element.is("input, button") ? this.element.is(":disabled") : this.element.hasClass("ui-button-disabled");

            if (isDisabled !== this.options.disabled) {
                this._setOption("disabled", isDisabled);
            }
            if (this.type === "radio") {
                radioGroup(this.element[0]).each(function () {
                    if ($(this).is(":checked")) {
                        $(this).button("widget")
                            .addClass("ui-state-active")
                            .attr("aria-pressed", "true");
                    } else {
                        $(this).button("widget")
                            .removeClass("ui-state-active")
                            .attr("aria-pressed", "false");
                    }
                });
            } else if (this.type === "checkbox") {
                if (this.element.is(":checked")) {
                    this.buttonElement
                        .addClass("ui-state-active")
                        .attr("aria-pressed", "true");
                } else {
                    this.buttonElement
                        .removeClass("ui-state-active")
                        .attr("aria-pressed", "false");
                }
            }
        },

        _resetButton: function () {
            if (this.type === "input") {
                if (this.options.label) {
                    this.element.val(this.options.label);
                }
                return;
            }
            var buttonElement = this.buttonElement.removeClass(typeClasses),
                buttonText = $("<span></span>", this.document[0])
                    .addClass("ui-button-text")
                    .html(this.options.label)
                    .appendTo(buttonElement.empty())
                    .text(),
                icons = this.options.icons,
                multipleIcons = icons.primary && icons.secondary,
                buttonClasses = [];

            if (icons.primary || icons.secondary) {
                if (this.options.text) {
                    buttonClasses.push("ui-button-text-icon" + ( multipleIcons ? "s" : ( icons.primary ? "-primary" : "-secondary" ) ));
                }

                if (icons.primary) {
                    buttonElement.prepend("<span class='ui-button-icon-primary ui-icon " + icons.primary + "'></span>");
                }

                if (icons.secondary) {
                    buttonElement.append("<span class='ui-button-icon-secondary ui-icon " + icons.secondary + "'></span>");
                }

                if (!this.options.text) {
                    buttonClasses.push(multipleIcons ? "ui-button-icons-only" : "ui-button-icon-only");

                    if (!this.hasTitle) {
                        buttonElement.attr("title", $.trim(buttonText));
                    }
                }
            } else {
                buttonClasses.push("ui-button-text-only");
            }
            buttonElement.addClass(buttonClasses.join(" "));
        }
    });

    $.widget("ui.buttonset", {
        version: "1.10.3",
        options: {
            items: "button, input[type=button], input[type=submit], input[type=reset], input[type=checkbox], input[type=radio], a, :data(ui-button)"
        },

        _create: function () {
            this.element.addClass("ui-buttonset");
        },

        _init: function () {
            this.refresh();
        },

        _setOption: function (key, value) {
            if (key === "disabled") {
                this.buttons.button("option", key, value);
            }

            this._super(key, value);
        },

        refresh: function () {
            var rtl = this.element.css("direction") === "rtl";

            this.buttons = this.element.find(this.options.items)
                .filter(":ui-button")
                .button("refresh")
                .end()
                .not(":ui-button")
                .button()
                .end()
                .map(function () {
                    return $(this).button("widget")[0];
                })
                .removeClass("ui-corner-all ui-corner-left ui-corner-right")
                .filter(":first")
                .addClass(rtl ? "ui-corner-right" : "ui-corner-left")
                .end()
                .filter(":last")
                .addClass(rtl ? "ui-corner-left" : "ui-corner-right")
                .end()
                .end();
        },

        _destroy: function () {
            this.element.removeClass("ui-buttonset");
            this.buttons
                .map(function () {
                    return $(this).button("widget")[0];
                })
                .removeClass("ui-corner-left ui-corner-right")
                .end()
                .button("destroy");
        }
    });

}(jQuery) );
(function ($, undefined) {

    $.extend($.ui, {datepicker: {version: "1.10.3"}});

    var PROP_NAME = "datepicker",
        instActive;

    /* Date picker manager.
     Use the singleton instance of this class, $.datepicker, to interact with the date picker.
     Settings for (groups of) date pickers are maintained in an instance object,
     allowing multiple different settings on the same page. */

    function Datepicker() {
        this._curInst = null; // The current instance in use
        this._keyEvent = false; // If the last event was a key event
        this._disabledInputs = []; // List of date picker inputs that have been disabled
        this._datepickerShowing = false; // True if the popup picker is showing , false if not
        this._inDialog = false; // True if showing within a "dialog", false if not
        this._mainDivId = "ui-datepicker-div"; // The ID of the main datepicker division
        this._inlineClass = "ui-datepicker-inline"; // The name of the inline marker class
        this._appendClass = "ui-datepicker-append"; // The name of the append marker class
        this._triggerClass = "ui-datepicker-trigger"; // The name of the trigger marker class
        this._dialogClass = "ui-datepicker-dialog"; // The name of the dialog marker class
        this._disableClass = "ui-datepicker-disabled"; // The name of the disabled covering marker class
        this._unselectableClass = "ui-datepicker-unselectable"; // The name of the unselectable cell marker class
        this._currentClass = "ui-datepicker-current-day"; // The name of the current day marker class
        this._dayOverClass = "ui-datepicker-days-cell-over"; // The name of the day hover marker class
        this.regional = []; // Available regional settings, indexed by language code
        this.regional[""] = { // Default regional settings
            closeText: "Done", // Display text for close link
            prevText: "Prev", // Display text for previous month link
            nextText: "Next", // Display text for next month link
            currentText: "Today", // Display text for current month link
            monthNames: ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"], // Names of months for drop-down and formatting
            monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"], // For formatting
            dayNames: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"], // For formatting
            dayNamesShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"], // For formatting
            dayNamesMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"], // Column headings for days starting at Sunday
            weekHeader: "Wk", // Column header for week of the year
            dateFormat: "mm/dd/yy", // See format options on parseDate
            firstDay: 0, // The first day of the week, Sun = 0, Mon = 1, ...
            isRTL: false, // True if right-to-left language, false if left-to-right
            showMonthAfterYear: false, // True if the year select precedes month, false for month then year
            yearSuffix: "" // Additional text to append to the year in the month headers
        };
        this._defaults = { // Global defaults for all the date picker instances
            showOn: "focus", // "focus" for popup on focus,
            // "button" for trigger button, or "both" for either
            showAnim: "fadeIn", // Name of jQuery animation for popup
            showOptions: {}, // Options for enhanced animations
            defaultDate: null, // Used when field is blank: actual date,
            // +/-number for offset from today, null for today
            appendText: "", // Display text following the input box, e.g. showing the format
            buttonText: "...", // Text for trigger button
            buttonImage: "", // URL for trigger button image
            buttonImageOnly: false, // True if the image appears alone, false if it appears on a button
            hideIfNoPrevNext: false, // True to hide next/previous month links
            // if not applicable, false to just disable them
            navigationAsDateFormat: false, // True if date formatting applied to prev/today/next links
            gotoCurrent: false, // True if today link goes back to current selection instead
            changeMonth: false, // True if month can be selected directly, false if only prev/next
            changeYear: false, // True if year can be selected directly, false if only prev/next
            yearRange: "c-10:c+10", // Range of years to display in drop-down,
            // either relative to today's year (-nn:+nn), relative to currently displayed year
            // (c-nn:c+nn), absolute (nnnn:nnnn), or a combination of the above (nnnn:-n)
            showOtherMonths: false, // True to show dates in other months, false to leave blank
            selectOtherMonths: false, // True to allow selection of dates in other months, false for unselectable
            showWeek: false, // True to show week of the year, false to not show it
            calculateWeek: this.iso8601Week, // How to calculate the week of the year,
            // takes a Date and returns the number of the week for it
            shortYearCutoff: "+10", // Short year values < this are in the current century,
            // > this are in the previous century,
            // string value starting with "+" for current year + value
            minDate: null, // The earliest selectable date, or null for no limit
            maxDate: null, // The latest selectable date, or null for no limit
            duration: "fast", // Duration of display/closure
            beforeShowDay: null, // Function that takes a date and returns an array with
            // [0] = true if selectable, false if not, [1] = custom CSS class name(s) or "",
            // [2] = cell title (optional), e.g. $.datepicker.noWeekends
            beforeShow: null, // Function that takes an input field and
            // returns a set of custom settings for the date picker
            onSelect: null, // Define a callback function when a date is selected
            onChangeMonthYear: null, // Define a callback function when the month or year is changed
            onClose: null, // Define a callback function when the datepicker is closed
            numberOfMonths: 1, // Number of months to show at a time
            showCurrentAtPos: 0, // The position in multipe months at which to show the current month (starting at 0)
            stepMonths: 1, // Number of months to step back/forward
            stepBigMonths: 12, // Number of months to step back/forward for the big links
            altField: "", // Selector for an alternate field to store selected dates into
            altFormat: "", // The date format to use for the alternate field
            constrainInput: true, // The input is constrained by the current date format
            showButtonPanel: false, // True to show button panel, false to not show it
            autoSize: false, // True to size the input for the date format, false to leave as is
            disabled: false // The initial disabled state
        };
        $.extend(this._defaults, this.regional[""]);
        this.dpDiv = bindHover($("<div id='" + this._mainDivId + "' class='ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>"));
    }

    $.extend(Datepicker.prototype, {
        /* Class name added to elements to indicate already configured with a date picker. */
        markerClassName: "hasDatepicker",

        //Keep track of the maximum number of rows displayed (see #7043)
        maxRows: 4,

        // TODO rename to "widget" when switching to widget factory
        _widgetDatepicker: function () {
            return this.dpDiv;
        },

        /* Override the default settings for all instances of the date picker.
         * @param  settings  object - the new settings to use as defaults (anonymous object)
         * @return the manager object
         */
        setDefaults: function (settings) {
            extendRemove(this._defaults, settings || {});
            return this;
        },

        /* Attach the date picker to a jQuery selection.
         * @param  target	element - the target input field or division or span
         * @param  settings  object - the new settings to use for this date picker instance (anonymous)
         */
        _attachDatepicker: function (target, settings) {
            var nodeName, inline, inst;
            nodeName = target.nodeName.toLowerCase();
            inline = (nodeName === "div" || nodeName === "span");
            if (!target.id) {
                this.uuid += 1;
                target.id = "dp" + this.uuid;
            }
            inst = this._newInst($(target), inline);
            inst.settings = $.extend({}, settings || {});
            if (nodeName === "input") {
                this._connectDatepicker(target, inst);
            } else if (inline) {
                this._inlineDatepicker(target, inst);
            }
        },

        /* Create a new instance object. */
        _newInst: function (target, inline) {
            var id = target[0].id.replace(/([^A-Za-z0-9_\-])/g, "\\\\$1"); // escape jQuery meta chars
            return {
                id: id, input: target, // associated target
                selectedDay: 0, selectedMonth: 0, selectedYear: 0, // current selection
                drawMonth: 0, drawYear: 0, // month being drawn
                inline: inline, // is datepicker inline or not
                dpDiv: (!inline ? this.dpDiv : // presentation div
                    bindHover($("<div class='" + this._inlineClass + " ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>")))
            };
        },

        /* Attach the date picker to an input field. */
        _connectDatepicker: function (target, inst) {
            var input = $(target);
            inst.append = $([]);
            inst.trigger = $([]);
            if (input.hasClass(this.markerClassName)) {
                return;
            }
            this._attachments(input, inst);
            input.addClass(this.markerClassName).keydown(this._doKeyDown).keypress(this._doKeyPress).keyup(this._doKeyUp);
            this._autoSize(inst);
            $.data(target, PROP_NAME, inst);
            //If disabled option is true, disable the datepicker once it has been attached to the input (see ticket #5665)
            if (inst.settings.disabled) {
                this._disableDatepicker(target);
            }
        },

        /* Make attachments based on settings. */
        _attachments: function (input, inst) {
            var showOn, buttonText, buttonImage,
                appendText = this._get(inst, "appendText"),
                isRTL = this._get(inst, "isRTL");

            if (inst.append) {
                inst.append.remove();
            }
            if (appendText) {
                inst.append = $("<span class='" + this._appendClass + "'>" + appendText + "</span>");
                input[isRTL ? "before" : "after"](inst.append);
            }

            input.unbind("focus", this._showDatepicker);

            if (inst.trigger) {
                inst.trigger.remove();
            }

            showOn = this._get(inst, "showOn");
            if (showOn === "focus" || showOn === "both") { // pop-up date picker when in the marked field
                input.focus(this._showDatepicker);
            }
            if (showOn === "button" || showOn === "both") { // pop-up date picker when button clicked
                buttonText = this._get(inst, "buttonText");
                buttonImage = this._get(inst, "buttonImage");
                inst.trigger = $(this._get(inst, "buttonImageOnly") ?
                    $("<img/>").addClass(this._triggerClass).attr({
                        src: buttonImage,
                        alt: buttonText,
                        title: buttonText
                    }) :
                    $("<button type='button'></button>").addClass(this._triggerClass).html(!buttonImage ? buttonText : $("<img/>").attr(
                            {src: buttonImage, alt: buttonText, title: buttonText})));
                input[isRTL ? "before" : "after"](inst.trigger);
                inst.trigger.click(function () {
                    if ($.datepicker._datepickerShowing && $.datepicker._lastInput === input[0]) {
                        $.datepicker._hideDatepicker();
                    } else if ($.datepicker._datepickerShowing && $.datepicker._lastInput !== input[0]) {
                        $.datepicker._hideDatepicker();
                        $.datepicker._showDatepicker(input[0]);
                    } else {
                        $.datepicker._showDatepicker(input[0]);
                    }
                    return false;
                });
            }
        },

        /* Apply the maximum length for the date format. */
        _autoSize: function (inst) {
            if (this._get(inst, "autoSize") && !inst.inline) {
                var findMax, max, maxI, i,
                    date = new Date(2009, 12 - 1, 20), // Ensure double digits
                    dateFormat = this._get(inst, "dateFormat");

                if (dateFormat.match(/[DM]/)) {
                    findMax = function (names) {
                        max = 0;
                        maxI = 0;
                        for (i = 0; i < names.length; i++) {
                            if (names[i].length > max) {
                                max = names[i].length;
                                maxI = i;
                            }
                        }
                        return maxI;
                    };
                    date.setMonth(findMax(this._get(inst, (dateFormat.match(/MM/) ?
                        "monthNames" : "monthNamesShort"))));
                    date.setDate(findMax(this._get(inst, (dateFormat.match(/DD/) ?
                            "dayNames" : "dayNamesShort"))) + 20 - date.getDay());
                }
                inst.input.attr("size", this._formatDate(inst, date).length);
            }
        },

        /* Attach an inline date picker to a div. */
        _inlineDatepicker: function (target, inst) {
            var divSpan = $(target);
            if (divSpan.hasClass(this.markerClassName)) {
                return;
            }
            divSpan.addClass(this.markerClassName).append(inst.dpDiv);
            $.data(target, PROP_NAME, inst);
            this._setDate(inst, this._getDefaultDate(inst), true);
            this._updateDatepicker(inst);
            this._updateAlternate(inst);
            //If disabled option is true, disable the datepicker before showing it (see ticket #5665)
            if (inst.settings.disabled) {
                this._disableDatepicker(target);
            }
            // Set display:block in place of inst.dpDiv.show() which won't work on disconnected elements
            // http://bugs.jqueryui.com/ticket/7552 - A Datepicker created on a detached div has zero height
            inst.dpDiv.css("display", "block");
        },

        /* Pop-up the date picker in a "dialog" box.
         * @param  input element - ignored
         * @param  date	string or Date - the initial date to display
         * @param  onSelect  function - the function to call when a date is selected
         * @param  settings  object - update the dialog date picker instance's settings (anonymous object)
         * @param  pos int[2] - coordinates for the dialog's position within the screen or
         *					event - with x/y coordinates or
         *					leave empty for default (screen centre)
         * @return the manager object
         */
        _dialogDatepicker: function (input, date, onSelect, settings, pos) {
            var id, browserWidth, browserHeight, scrollX, scrollY,
                inst = this._dialogInst; // internal instance

            if (!inst) {
                this.uuid += 1;
                id = "dp" + this.uuid;
                this._dialogInput = $("<input type='text' id='" + id +
                    "' style='position: absolute; top: -100px; width: 0px;'/>");
                this._dialogInput.keydown(this._doKeyDown);
                $("body").append(this._dialogInput);
                inst = this._dialogInst = this._newInst(this._dialogInput, false);
                inst.settings = {};
                $.data(this._dialogInput[0], PROP_NAME, inst);
            }
            extendRemove(inst.settings, settings || {});
            date = (date && date.constructor === Date ? this._formatDate(inst, date) : date);
            this._dialogInput.val(date);

            this._pos = (pos ? (pos.length ? pos : [pos.pageX, pos.pageY]) : null);
            if (!this._pos) {
                browserWidth = document.documentElement.clientWidth;
                browserHeight = document.documentElement.clientHeight;
                scrollX = document.documentElement.scrollLeft || document.body.scrollLeft;
                scrollY = document.documentElement.scrollTop || document.body.scrollTop;
                this._pos = // should use actual width/height below
                    [(browserWidth / 2) - 100 + scrollX, (browserHeight / 2) - 150 + scrollY];
            }

            // move input on screen for focus, but hidden behind dialog
            this._dialogInput.css("left", (this._pos[0] + 20) + "px").css("top", this._pos[1] + "px");
            inst.settings.onSelect = onSelect;
            this._inDialog = true;
            this.dpDiv.addClass(this._dialogClass);
            this._showDatepicker(this._dialogInput[0]);
            if ($.blockUI) {
                $.blockUI(this.dpDiv);
            }
            $.data(this._dialogInput[0], PROP_NAME, inst);
            return this;
        },

        /* Detach a datepicker from its control.
         * @param  target	element - the target input field or division or span
         */
        _destroyDatepicker: function (target) {
            var nodeName,
                $target = $(target),
                inst = $.data(target, PROP_NAME);

            if (!$target.hasClass(this.markerClassName)) {
                return;
            }

            nodeName = target.nodeName.toLowerCase();
            $.removeData(target, PROP_NAME);
            if (nodeName === "input") {
                inst.append.remove();
                inst.trigger.remove();
                $target.removeClass(this.markerClassName).unbind("focus", this._showDatepicker).unbind("keydown", this._doKeyDown).unbind("keypress", this._doKeyPress).unbind("keyup", this._doKeyUp);
            } else if (nodeName === "div" || nodeName === "span") {
                $target.removeClass(this.markerClassName).empty();
            }
        },

        /* Enable the date picker to a jQuery selection.
         * @param  target	element - the target input field or division or span
         */
        _enableDatepicker: function (target) {
            var nodeName, inline,
                $target = $(target),
                inst = $.data(target, PROP_NAME);

            if (!$target.hasClass(this.markerClassName)) {
                return;
            }

            nodeName = target.nodeName.toLowerCase();
            if (nodeName === "input") {
                target.disabled = false;
                inst.trigger.filter("button").each(function () {
                    this.disabled = false;
                }).end().filter("img").css({opacity: "1.0", cursor: ""});
            } else if (nodeName === "div" || nodeName === "span") {
                inline = $target.children("." + this._inlineClass);
                inline.children().removeClass("ui-state-disabled");
                inline.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled", false);
            }
            this._disabledInputs = $.map(this._disabledInputs,
                function (value) {
                    return (value === target ? null : value);
                }); // delete entry
        },

        /* Disable the date picker to a jQuery selection.
         * @param  target	element - the target input field or division or span
         */
        _disableDatepicker: function (target) {
            var nodeName, inline,
                $target = $(target),
                inst = $.data(target, PROP_NAME);

            if (!$target.hasClass(this.markerClassName)) {
                return;
            }

            nodeName = target.nodeName.toLowerCase();
            if (nodeName === "input") {
                target.disabled = true;
                inst.trigger.filter("button").each(function () {
                    this.disabled = true;
                }).end().filter("img").css({opacity: "0.5", cursor: "default"});
            } else if (nodeName === "div" || nodeName === "span") {
                inline = $target.children("." + this._inlineClass);
                inline.children().addClass("ui-state-disabled");
                inline.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled", true);
            }
            this._disabledInputs = $.map(this._disabledInputs,
                function (value) {
                    return (value === target ? null : value);
                }); // delete entry
            this._disabledInputs[this._disabledInputs.length] = target;
        },

        /* Is the first field in a jQuery collection disabled as a datepicker?
         * @param  target	element - the target input field or division or span
         * @return boolean - true if disabled, false if enabled
         */
        _isDisabledDatepicker: function (target) {
            if (!target) {
                return false;
            }
            for (var i = 0; i < this._disabledInputs.length; i++) {
                if (this._disabledInputs[i] === target) {
                    return true;
                }
            }
            return false;
        },

        /* Retrieve the instance data for the target control.
         * @param  target  element - the target input field or division or span
         * @return  object - the associated instance data
         * @throws  error if a jQuery problem getting data
         */
        _getInst: function (target) {
            try {
                return $.data(target, PROP_NAME);
            }
            catch (err) {
                throw "Missing instance data for this datepicker";
            }
        },

        /* Update or retrieve the settings for a date picker attached to an input field or division.
         * @param  target  element - the target input field or division or span
         * @param  name	object - the new settings to update or
         *				string - the name of the setting to change or retrieve,
         *				when retrieving also "all" for all instance settings or
         *				"defaults" for all global defaults
         * @param  value   any - the new value for the setting
         *				(omit if above is an object or to retrieve a value)
         */
        _optionDatepicker: function (target, name, value) {
            var settings, date, minDate, maxDate,
                inst = this._getInst(target);

            if (arguments.length === 2 && typeof name === "string") {
                return (name === "defaults" ? $.extend({}, $.datepicker._defaults) :
                    (inst ? (name === "all" ? $.extend({}, inst.settings) :
                            this._get(inst, name)) : null));
            }

            settings = name || {};
            if (typeof name === "string") {
                settings = {};
                settings[name] = value;
            }

            if (inst) {
                if (this._curInst === inst) {
                    this._hideDatepicker();
                }

                date = this._getDateDatepicker(target, true);
                minDate = this._getMinMaxDate(inst, "min");
                maxDate = this._getMinMaxDate(inst, "max");
                extendRemove(inst.settings, settings);
                // reformat the old minDate/maxDate values if dateFormat changes and a new minDate/maxDate isn't provided
                if (minDate !== null && settings.dateFormat !== undefined && settings.minDate === undefined) {
                    inst.settings.minDate = this._formatDate(inst, minDate);
                }
                if (maxDate !== null && settings.dateFormat !== undefined && settings.maxDate === undefined) {
                    inst.settings.maxDate = this._formatDate(inst, maxDate);
                }
                if ("disabled" in settings) {
                    if (settings.disabled) {
                        this._disableDatepicker(target);
                    } else {
                        this._enableDatepicker(target);
                    }
                }
                this._attachments($(target), inst);
                this._autoSize(inst);
                this._setDate(inst, date);
                this._updateAlternate(inst);
                this._updateDatepicker(inst);
            }
        },

        // change method deprecated
        _changeDatepicker: function (target, name, value) {
            this._optionDatepicker(target, name, value);
        },

        /* Redraw the date picker attached to an input field or division.
         * @param  target  element - the target input field or division or span
         */
        _refreshDatepicker: function (target) {
            var inst = this._getInst(target);
            if (inst) {
                this._updateDatepicker(inst);
            }
        },

        /* Set the dates for a jQuery selection.
         * @param  target element - the target input field or division or span
         * @param  date	Date - the new date
         */
        _setDateDatepicker: function (target, date) {
            var inst = this._getInst(target);
            if (inst) {
                this._setDate(inst, date);
                this._updateDatepicker(inst);
                this._updateAlternate(inst);
            }
        },

        /* Get the date(s) for the first entry in a jQuery selection.
         * @param  target element - the target input field or division or span
         * @param  noDefault boolean - true if no default date is to be used
         * @return Date - the current date
         */
        _getDateDatepicker: function (target, noDefault) {
            var inst = this._getInst(target);
            if (inst && !inst.inline) {
                this._setDateFromField(inst, noDefault);
            }
            return (inst ? this._getDate(inst) : null);
        },

        /* Handle keystrokes. */
        _doKeyDown: function (event) {
            var onSelect, dateStr, sel,
                inst = $.datepicker._getInst(event.target),
                handled = true,
                isRTL = inst.dpDiv.is(".ui-datepicker-rtl");

            inst._keyEvent = true;
            if ($.datepicker._datepickerShowing) {
                switch (event.keyCode) {
                    case 9:
                        $.datepicker._hideDatepicker();
                        handled = false;
                        break; // hide on tab out
                    case 13:
                        sel = $("td." + $.datepicker._dayOverClass + ":not(." +
                            $.datepicker._currentClass + ")", inst.dpDiv);
                        if (sel[0]) {
                            $.datepicker._selectDay(event.target, inst.selectedMonth, inst.selectedYear, sel[0]);
                        }

                        onSelect = $.datepicker._get(inst, "onSelect");
                        if (onSelect) {
                            dateStr = $.datepicker._formatDate(inst);

                            // trigger custom callback
                            onSelect.apply((inst.input ? inst.input[0] : null), [dateStr, inst]);
                        } else {
                            $.datepicker._hideDatepicker();
                        }

                        return false; // don't submit the form
                    case 27:
                        $.datepicker._hideDatepicker();
                        break; // hide on escape
                    case 33:
                        $.datepicker._adjustDate(event.target, (event.ctrlKey ?
                            -$.datepicker._get(inst, "stepBigMonths") :
                            -$.datepicker._get(inst, "stepMonths")), "M");
                        break; // previous month/year on page up/+ ctrl
                    case 34:
                        $.datepicker._adjustDate(event.target, (event.ctrlKey ?
                            +$.datepicker._get(inst, "stepBigMonths") :
                            +$.datepicker._get(inst, "stepMonths")), "M");
                        break; // next month/year on page down/+ ctrl
                    case 35:
                        if (event.ctrlKey || event.metaKey) {
                            $.datepicker._clearDate(event.target);
                        }
                        handled = event.ctrlKey || event.metaKey;
                        break; // clear on ctrl or command +end
                    case 36:
                        if (event.ctrlKey || event.metaKey) {
                            $.datepicker._gotoToday(event.target);
                        }
                        handled = event.ctrlKey || event.metaKey;
                        break; // current on ctrl or command +home
                    case 37:
                        if (event.ctrlKey || event.metaKey) {
                            $.datepicker._adjustDate(event.target, (isRTL ? +1 : -1), "D");
                        }
                        handled = event.ctrlKey || event.metaKey;
                        // -1 day on ctrl or command +left
                        if (event.originalEvent.altKey) {
                            $.datepicker._adjustDate(event.target, (event.ctrlKey ?
                                -$.datepicker._get(inst, "stepBigMonths") :
                                -$.datepicker._get(inst, "stepMonths")), "M");
                        }
                        // next month/year on alt +left on Mac
                        break;
                    case 38:
                        if (event.ctrlKey || event.metaKey) {
                            $.datepicker._adjustDate(event.target, -7, "D");
                        }
                        handled = event.ctrlKey || event.metaKey;
                        break; // -1 week on ctrl or command +up
                    case 39:
                        if (event.ctrlKey || event.metaKey) {
                            $.datepicker._adjustDate(event.target, (isRTL ? -1 : +1), "D");
                        }
                        handled = event.ctrlKey || event.metaKey;
                        // +1 day on ctrl or command +right
                        if (event.originalEvent.altKey) {
                            $.datepicker._adjustDate(event.target, (event.ctrlKey ?
                                +$.datepicker._get(inst, "stepBigMonths") :
                                +$.datepicker._get(inst, "stepMonths")), "M");
                        }
                        // next month/year on alt +right
                        break;
                    case 40:
                        if (event.ctrlKey || event.metaKey) {
                            $.datepicker._adjustDate(event.target, +7, "D");
                        }
                        handled = event.ctrlKey || event.metaKey;
                        break; // +1 week on ctrl or command +down
                    default:
                        handled = false;
                }
            } else if (event.keyCode === 36 && event.ctrlKey) { // display the date picker on ctrl+home
                $.datepicker._showDatepicker(this);
            } else {
                handled = false;
            }

            if (handled) {
                event.preventDefault();
                event.stopPropagation();
            }
        },

        /* Filter entered characters - based on date format. */
        _doKeyPress: function (event) {
            var chars, chr,
                inst = $.datepicker._getInst(event.target);

            if ($.datepicker._get(inst, "constrainInput")) {
                chars = $.datepicker._possibleChars($.datepicker._get(inst, "dateFormat"));
                chr = String.fromCharCode(event.charCode == null ? event.keyCode : event.charCode);
                return event.ctrlKey || event.metaKey || (chr < " " || !chars || chars.indexOf(chr) > -1);
            }
        },

        /* Synchronise manual entry and field/alternate field. */
        _doKeyUp: function (event) {
            var date,
                inst = $.datepicker._getInst(event.target);

            if (inst.input.val() !== inst.lastVal) {
                try {
                    date = $.datepicker.parseDate($.datepicker._get(inst, "dateFormat"),
                        (inst.input ? inst.input.val() : null),
                        $.datepicker._getFormatConfig(inst));

                    if (date) { // only if valid
                        $.datepicker._setDateFromField(inst);
                        $.datepicker._updateAlternate(inst);
                        $.datepicker._updateDatepicker(inst);
                    }
                }
                catch (err) {
                }
            }
            return true;
        },

        /* Pop-up the date picker for a given input field.
         * If false returned from beforeShow event handler do not show.
         * @param  input  element - the input field attached to the date picker or
         *					event - if triggered by focus
         */
        _showDatepicker: function (input) {
            input = input.target || input;
            if (input.nodeName.toLowerCase() !== "input") { // find from button/image trigger
                input = $("input", input.parentNode)[0];
            }

            if ($.datepicker._isDisabledDatepicker(input) || $.datepicker._lastInput === input) { // already here
                return;
            }

            var inst, beforeShow, beforeShowSettings, isFixed,
                offset, showAnim, duration;

            inst = $.datepicker._getInst(input);
            if ($.datepicker._curInst && $.datepicker._curInst !== inst) {
                $.datepicker._curInst.dpDiv.stop(true, true);
                if (inst && $.datepicker._datepickerShowing) {
                    $.datepicker._hideDatepicker($.datepicker._curInst.input[0]);
                }
            }

            beforeShow = $.datepicker._get(inst, "beforeShow");
            beforeShowSettings = beforeShow ? beforeShow.apply(input, [input, inst]) : {};
            if (beforeShowSettings === false) {
                return;
            }
            extendRemove(inst.settings, beforeShowSettings);

            inst.lastVal = null;
            $.datepicker._lastInput = input;
            $.datepicker._setDateFromField(inst);

            if ($.datepicker._inDialog) { // hide cursor
                input.value = "";
            }
            if (!$.datepicker._pos) { // position below input
                $.datepicker._pos = $.datepicker._findPos(input);
                $.datepicker._pos[1] += input.offsetHeight; // add the height
            }

            isFixed = false;
            $(input).parents().each(function () {
                isFixed |= $(this).css("position") === "fixed";
                return !isFixed;
            });

            offset = {left: $.datepicker._pos[0], top: $.datepicker._pos[1]};
            $.datepicker._pos = null;
            //to avoid flashes on Firefox
            inst.dpDiv.empty();
            // determine sizing offscreen
            inst.dpDiv.css({position: "absolute", display: "block", top: "-1000px"});
            $.datepicker._updateDatepicker(inst);
            // fix width for dynamic number of date pickers
            // and adjust position before showing
            offset = $.datepicker._checkOffset(inst, offset, isFixed);
            inst.dpDiv.css({
                position: ($.datepicker._inDialog && $.blockUI ?
                    "static" : (isFixed ? "fixed" : "absolute")), display: "none",
                left: offset.left + "px", top: offset.top + "px"
            });

            if (!inst.inline) {
                showAnim = $.datepicker._get(inst, "showAnim");
                duration = $.datepicker._get(inst, "duration");
                inst.dpDiv.zIndex($(input).zIndex() + 1);
                $.datepicker._datepickerShowing = true;

                if ($.effects && $.effects.effect[showAnim]) {
                    inst.dpDiv.show(showAnim, $.datepicker._get(inst, "showOptions"), duration);
                } else {
                    inst.dpDiv[showAnim || "show"](showAnim ? duration : null);
                }

                if ($.datepicker._shouldFocusInput(inst)) {
                    inst.input.focus();
                }

                $.datepicker._curInst = inst;
            }
        },

        /* Generate the date picker content. */
        _updateDatepicker: function (inst) {
            this.maxRows = 4; //Reset the max number of rows being displayed (see #7043)
            instActive = inst; // for delegate hover events
            inst.dpDiv.empty().append(this._generateHTML(inst));
            this._attachHandlers(inst);
            inst.dpDiv.find("." + this._dayOverClass + " a").mouseover();

            var origyearshtml,
                numMonths = this._getNumberOfMonths(inst),
                cols = numMonths[1],
                width = 17;

            inst.dpDiv.removeClass("ui-datepicker-multi-2 ui-datepicker-multi-3 ui-datepicker-multi-4").width("");
            if (cols > 1) {
                inst.dpDiv.addClass("ui-datepicker-multi-" + cols).css("width", (width * cols) + "em");
            }
            inst.dpDiv[(numMonths[0] !== 1 || numMonths[1] !== 1 ? "add" : "remove") +
            "Class"]("ui-datepicker-multi");
            inst.dpDiv[(this._get(inst, "isRTL") ? "add" : "remove") +
            "Class"]("ui-datepicker-rtl");

            if (inst === $.datepicker._curInst && $.datepicker._datepickerShowing && $.datepicker._shouldFocusInput(inst)) {
                inst.input.focus();
            }

            // deffered render of the years select (to avoid flashes on Firefox)
            if (inst.yearshtml) {
                origyearshtml = inst.yearshtml;
                setTimeout(function () {
                    //assure that inst.yearshtml didn't change.
                    if (origyearshtml === inst.yearshtml && inst.yearshtml) {
                        inst.dpDiv.find("select.ui-datepicker-year:first").replaceWith(inst.yearshtml);
                    }
                    origyearshtml = inst.yearshtml = null;
                }, 0);
            }
        },

        // #6694 - don't focus the input if it's already focused
        // this breaks the change event in IE
        // Support: IE and jQuery <1.9
        _shouldFocusInput: function (inst) {
            return inst.input && inst.input.is(":visible") && !inst.input.is(":disabled") && !inst.input.is(":focus");
        },

        /* Check positioning to remain on screen. */
        _checkOffset: function (inst, offset, isFixed) {
            var dpWidth = inst.dpDiv.outerWidth(),
                dpHeight = inst.dpDiv.outerHeight(),
                inputWidth = inst.input ? inst.input.outerWidth() : 0,
                inputHeight = inst.input ? inst.input.outerHeight() : 0,
                viewWidth = document.documentElement.clientWidth + (isFixed ? 0 : $(document).scrollLeft()),
                viewHeight = document.documentElement.clientHeight + (isFixed ? 0 : $(document).scrollTop());

            offset.left -= (this._get(inst, "isRTL") ? (dpWidth - inputWidth) : 0);
            offset.left -= (isFixed && offset.left === inst.input.offset().left) ? $(document).scrollLeft() : 0;
            offset.top -= (isFixed && offset.top === (inst.input.offset().top + inputHeight)) ? $(document).scrollTop() : 0;

            // now check if datepicker is showing outside window viewport - move to a better place if so.
            offset.left -= Math.min(offset.left, (offset.left + dpWidth > viewWidth && viewWidth > dpWidth) ?
                Math.abs(offset.left + dpWidth - viewWidth) : 0);
            offset.top -= Math.min(offset.top, (offset.top + dpHeight > viewHeight && viewHeight > dpHeight) ?
                Math.abs(dpHeight + inputHeight) : 0);

            return offset;
        },

        /* Find an object's position on the screen. */
        _findPos: function (obj) {
            var position,
                inst = this._getInst(obj),
                isRTL = this._get(inst, "isRTL");

            while (obj && (obj.type === "hidden" || obj.nodeType !== 1 || $.expr.filters.hidden(obj))) {
                obj = obj[isRTL ? "previousSibling" : "nextSibling"];
            }

            position = $(obj).offset();
            return [position.left, position.top];
        },

        /* Hide the date picker from view.
         * @param  input  element - the input field attached to the date picker
         */
        _hideDatepicker: function (input) {
            var showAnim, duration, postProcess, onClose,
                inst = this._curInst;

            if (!inst || (input && inst !== $.data(input, PROP_NAME))) {
                return;
            }

            if (this._datepickerShowing) {
                showAnim = this._get(inst, "showAnim");
                duration = this._get(inst, "duration");
                postProcess = function () {
                    $.datepicker._tidyDialog(inst);
                };

                // DEPRECATED: after BC for 1.8.x $.effects[ showAnim ] is not needed
                if ($.effects && ( $.effects.effect[showAnim] || $.effects[showAnim] )) {
                    inst.dpDiv.hide(showAnim, $.datepicker._get(inst, "showOptions"), duration, postProcess);
                } else {
                    inst.dpDiv[(showAnim === "slideDown" ? "slideUp" :
                        (showAnim === "fadeIn" ? "fadeOut" : "hide"))]((showAnim ? duration : null), postProcess);
                }

                if (!showAnim) {
                    postProcess();
                }
                this._datepickerShowing = false;

                onClose = this._get(inst, "onClose");
                if (onClose) {
                    onClose.apply((inst.input ? inst.input[0] : null), [(inst.input ? inst.input.val() : ""), inst]);
                }

                this._lastInput = null;
                if (this._inDialog) {
                    this._dialogInput.css({position: "absolute", left: "0", top: "-100px"});
                    if ($.blockUI) {
                        $.unblockUI();
                        $("body").append(this.dpDiv);
                    }
                }
                this._inDialog = false;
            }
        },

        /* Tidy up after a dialog display. */
        _tidyDialog: function (inst) {
            inst.dpDiv.removeClass(this._dialogClass).unbind(".ui-datepicker-calendar");
        },

        /* Close date picker if clicked elsewhere. */
        _checkExternalClick: function (event) {
            if (!$.datepicker._curInst) {
                return;
            }

            var $target = $(event.target),
                inst = $.datepicker._getInst($target[0]);

            if (( ( $target[0].id !== $.datepicker._mainDivId &&
                $target.parents("#" + $.datepicker._mainDivId).length === 0 && !$target.hasClass($.datepicker.markerClassName) && !$target.closest("." + $.datepicker._triggerClass).length &&
                $.datepicker._datepickerShowing && !($.datepicker._inDialog && $.blockUI) ) ) ||
                ( $target.hasClass($.datepicker.markerClassName) && $.datepicker._curInst !== inst )) {
                $.datepicker._hideDatepicker();
            }
        },

        /* Adjust one of the date sub-fields. */
        _adjustDate: function (id, offset, period) {
            var target = $(id),
                inst = this._getInst(target[0]);

            if (this._isDisabledDatepicker(target[0])) {
                return;
            }
            this._adjustInstDate(inst, offset +
                (period === "M" ? this._get(inst, "showCurrentAtPos") : 0), // undo positioning
                period);
            this._updateDatepicker(inst);
        },

        /* Action for current link. */
        _gotoToday: function (id) {
            var date,
                target = $(id),
                inst = this._getInst(target[0]);

            if (this._get(inst, "gotoCurrent") && inst.currentDay) {
                inst.selectedDay = inst.currentDay;
                inst.drawMonth = inst.selectedMonth = inst.currentMonth;
                inst.drawYear = inst.selectedYear = inst.currentYear;
            } else {
                date = new Date();
                inst.selectedDay = date.getDate();
                inst.drawMonth = inst.selectedMonth = date.getMonth();
                inst.drawYear = inst.selectedYear = date.getFullYear();
            }
            this._notifyChange(inst);
            this._adjustDate(target);
        },

        /* Action for selecting a new month/year. */
        _selectMonthYear: function (id, select, period) {
            var target = $(id),
                inst = this._getInst(target[0]);

            inst["selected" + (period === "M" ? "Month" : "Year")] =
                inst["draw" + (period === "M" ? "Month" : "Year")] =
                    parseInt(select.options[select.selectedIndex].value, 10);

            this._notifyChange(inst);
            this._adjustDate(target);
        },

        /* Action for selecting a day. */
        _selectDay: function (id, month, year, td) {
            var inst,
                target = $(id);

            if ($(td).hasClass(this._unselectableClass) || this._isDisabledDatepicker(target[0])) {
                return;
            }

            inst = this._getInst(target[0]);
            inst.selectedDay = inst.currentDay = $("a", td).html();
            inst.selectedMonth = inst.currentMonth = month;
            inst.selectedYear = inst.currentYear = year;
            this._selectDate(id, this._formatDate(inst,
                inst.currentDay, inst.currentMonth, inst.currentYear));
        },

        /* Erase the input field and hide the date picker. */
        _clearDate: function (id) {
            var target = $(id);
            this._selectDate(target, "");
        },

        /* Update the input field with the selected date. */
        _selectDate: function (id, dateStr) {
            var onSelect,
                target = $(id),
                inst = this._getInst(target[0]);

            dateStr = (dateStr != null ? dateStr : this._formatDate(inst));
            if (inst.input) {
                inst.input.val(dateStr);
            }
            this._updateAlternate(inst);

            onSelect = this._get(inst, "onSelect");
            if (onSelect) {
                onSelect.apply((inst.input ? inst.input[0] : null), [dateStr, inst]);  // trigger custom callback
            } else if (inst.input) {
                inst.input.trigger("change"); // fire the change event
            }

            if (inst.inline) {
                this._updateDatepicker(inst);
            } else {
                this._hideDatepicker();
                this._lastInput = inst.input[0];
                if (typeof(inst.input[0]) !== "object") {
                    inst.input.focus(); // restore focus
                }
                this._lastInput = null;
            }
        },

        /* Update any alternate field to synchronise with the main field. */
        _updateAlternate: function (inst) {
            var altFormat, date, dateStr,
                altField = this._get(inst, "altField");

            if (altField) { // update alternate field too
                altFormat = this._get(inst, "altFormat") || this._get(inst, "dateFormat");
                date = this._getDate(inst);
                dateStr = this.formatDate(altFormat, date, this._getFormatConfig(inst));
                $(altField).each(function () {
                    $(this).val(dateStr);
                });
            }
        },

        /* Set as beforeShowDay function to prevent selection of weekends.
         * @param  date  Date - the date to customise
         * @return [boolean, string] - is this date selectable?, what is its CSS class?
         */
        noWeekends: function (date) {
            var day = date.getDay();
            return [(day > 0 && day < 6), ""];
        },

        /* Set as calculateWeek to determine the week of the year based on the ISO 8601 definition.
         * @param  date  Date - the date to get the week for
         * @return  number - the number of the week within the year that contains this date
         */
        iso8601Week: function (date) {
            var time,
                checkDate = new Date(date.getTime());

            // Find Thursday of this week starting on Monday
            checkDate.setDate(checkDate.getDate() + 4 - (checkDate.getDay() || 7));

            time = checkDate.getTime();
            checkDate.setMonth(0); // Compare with Jan 1
            checkDate.setDate(1);
            return Math.floor(Math.round((time - checkDate) / 86400000) / 7) + 1;
        },

        /* Parse a string value into a date object.
         * See formatDate below for the possible formats.
         *
         * @param  format string - the expected format of the date
         * @param  value string - the date in the above format
         * @param  settings Object - attributes include:
         *					shortYearCutoff  number - the cutoff year for determining the century (optional)
         *					dayNamesShort	string[7] - abbreviated names of the days from Sunday (optional)
         *					dayNames		string[7] - names of the days from Sunday (optional)
         *					monthNamesShort string[12] - abbreviated names of the months (optional)
         *					monthNames		string[12] - names of the months (optional)
         * @return  Date - the extracted date value or null if value is blank
         */
        parseDate: function (format, value, settings) {
            if (format == null || value == null) {
                throw "Invalid arguments";
            }

            value = (typeof value === "object" ? value.toString() : value + "");
            if (value === "") {
                return null;
            }

            var iFormat, dim, extra,
                iValue = 0,
                shortYearCutoffTemp = (settings ? settings.shortYearCutoff : null) || this._defaults.shortYearCutoff,
                shortYearCutoff = (typeof shortYearCutoffTemp !== "string" ? shortYearCutoffTemp :
                    new Date().getFullYear() % 100 + parseInt(shortYearCutoffTemp, 10)),
                dayNamesShort = (settings ? settings.dayNamesShort : null) || this._defaults.dayNamesShort,
                dayNames = (settings ? settings.dayNames : null) || this._defaults.dayNames,
                monthNamesShort = (settings ? settings.monthNamesShort : null) || this._defaults.monthNamesShort,
                monthNames = (settings ? settings.monthNames : null) || this._defaults.monthNames,
                year = -1,
                month = -1,
                day = -1,
                doy = -1,
                literal = false,
                date,
                // Check whether a format character is doubled
                lookAhead = function (match) {
                    var matches = (iFormat + 1 < format.length && format.charAt(iFormat + 1) === match);
                    if (matches) {
                        iFormat++;
                    }
                    return matches;
                },
                // Extract a number from the string value
                getNumber = function (match) {
                    var isDoubled = lookAhead(match),
                        size = (match === "@" ? 14 : (match === "!" ? 20 :
                                (match === "y" && isDoubled ? 4 : (match === "o" ? 3 : 2)))),
                        digits = new RegExp("^\\d{1," + size + "}"),
                        num = value.substring(iValue).match(digits);
                    if (!num) {
                        throw "Missing number at position " + iValue;
                    }
                    iValue += num[0].length;
                    return parseInt(num[0], 10);
                },
                // Extract a name from the string value and convert to an index
                getName = function (match, shortNames, longNames) {
                    var index = -1,
                        names = $.map(lookAhead(match) ? longNames : shortNames, function (v, k) {
                            return [[k, v]];
                        }).sort(function (a, b) {
                            return -(a[1].length - b[1].length);
                        });

                    $.each(names, function (i, pair) {
                        var name = pair[1];
                        if (value.substr(iValue, name.length).toLowerCase() === name.toLowerCase()) {
                            index = pair[0];
                            iValue += name.length;
                            return false;
                        }
                    });
                    if (index !== -1) {
                        return index + 1;
                    } else {
                        throw "Unknown name at position " + iValue;
                    }
                },
                // Confirm that a literal character matches the string value
                checkLiteral = function () {
                    if (value.charAt(iValue) !== format.charAt(iFormat)) {
                        throw "Unexpected literal at position " + iValue;
                    }
                    iValue++;
                };

            for (iFormat = 0; iFormat < format.length; iFormat++) {
                if (literal) {
                    if (format.charAt(iFormat) === "'" && !lookAhead("'")) {
                        literal = false;
                    } else {
                        checkLiteral();
                    }
                } else {
                    switch (format.charAt(iFormat)) {
                        case "d":
                            day = getNumber("d");
                            break;
                        case "D":
                            getName("D", dayNamesShort, dayNames);
                            break;
                        case "o":
                            doy = getNumber("o");
                            break;
                        case "m":
                            month = getNumber("m");
                            break;
                        case "M":
                            month = getName("M", monthNamesShort, monthNames);
                            break;
                        case "y":
                            year = getNumber("y");
                            break;
                        case "@":
                            date = new Date(getNumber("@"));
                            year = date.getFullYear();
                            month = date.getMonth() + 1;
                            day = date.getDate();
                            break;
                        case "!":
                            date = new Date((getNumber("!") - this._ticksTo1970) / 10000);
                            year = date.getFullYear();
                            month = date.getMonth() + 1;
                            day = date.getDate();
                            break;
                        case "'":
                            if (lookAhead("'")) {
                                checkLiteral();
                            } else {
                                literal = true;
                            }
                            break;
                        default:
                            checkLiteral();
                    }
                }
            }

            if (iValue < value.length) {
                extra = value.substr(iValue);
                if (!/^\s+/.test(extra)) {
                    throw "Extra/unparsed characters found in date: " + extra;
                }
            }

            if (year === -1) {
                year = new Date().getFullYear();
            } else if (year < 100) {
                year += new Date().getFullYear() - new Date().getFullYear() % 100 +
                    (year <= shortYearCutoff ? 0 : -100);
            }

            if (doy > -1) {
                month = 1;
                day = doy;
                do {
                    dim = this._getDaysInMonth(year, month - 1);
                    if (day <= dim) {
                        break;
                    }
                    month++;
                    day -= dim;
                } while (true);
            }

            date = this._daylightSavingAdjust(new Date(year, month - 1, day));
            if (date.getFullYear() !== year || date.getMonth() + 1 !== month || date.getDate() !== day) {
                throw "Invalid date"; // E.g. 31/02/00
            }
            return date;
        },

        /* Standard date formats. */
        ATOM: "yy-mm-dd", // RFC 3339 (ISO 8601)
        COOKIE: "D, dd M yy",
        ISO_8601: "yy-mm-dd",
        RFC_822: "D, d M y",
        RFC_850: "DD, dd-M-y",
        RFC_1036: "D, d M y",
        RFC_1123: "D, d M yy",
        RFC_2822: "D, d M yy",
        RSS: "D, d M y", // RFC 822
        TICKS: "!",
        TIMESTAMP: "@",
        W3C: "yy-mm-dd", // ISO 8601

        _ticksTo1970: (((1970 - 1) * 365 + Math.floor(1970 / 4) - Math.floor(1970 / 100) +
        Math.floor(1970 / 400)) * 24 * 60 * 60 * 10000000),

        /* Format a date object into a string value.
         * The format can be combinations of the following:
         * d  - day of month (no leading zero)
         * dd - day of month (two digit)
         * o  - day of year (no leading zeros)
         * oo - day of year (three digit)
         * D  - day name short
         * DD - day name long
         * m  - month of year (no leading zero)
         * mm - month of year (two digit)
         * M  - month name short
         * MM - month name long
         * y  - year (two digit)
         * yy - year (four digit)
         * @ - Unix timestamp (ms since 01/01/1970)
         * ! - Windows ticks (100ns since 01/01/0001)
         * "..." - literal text
         * '' - single quote
         *
         * @param  format string - the desired format of the date
         * @param  date Date - the date value to format
         * @param  settings Object - attributes include:
         *					dayNamesShort	string[7] - abbreviated names of the days from Sunday (optional)
         *					dayNames		string[7] - names of the days from Sunday (optional)
         *					monthNamesShort string[12] - abbreviated names of the months (optional)
         *					monthNames		string[12] - names of the months (optional)
         * @return  string - the date in the above format
         */
        formatDate: function (format, date, settings) {
            if (!date) {
                return "";
            }

            var iFormat,
                dayNamesShort = (settings ? settings.dayNamesShort : null) || this._defaults.dayNamesShort,
                dayNames = (settings ? settings.dayNames : null) || this._defaults.dayNames,
                monthNamesShort = (settings ? settings.monthNamesShort : null) || this._defaults.monthNamesShort,
                monthNames = (settings ? settings.monthNames : null) || this._defaults.monthNames,
                // Check whether a format character is doubled
                lookAhead = function (match) {
                    var matches = (iFormat + 1 < format.length && format.charAt(iFormat + 1) === match);
                    if (matches) {
                        iFormat++;
                    }
                    return matches;
                },
                // Format a number, with leading zero if necessary
                formatNumber = function (match, value, len) {
                    var num = "" + value;
                    if (lookAhead(match)) {
                        while (num.length < len) {
                            num = "0" + num;
                        }
                    }
                    return num;
                },
                // Format a name, short or long as requested
                formatName = function (match, value, shortNames, longNames) {
                    return (lookAhead(match) ? longNames[value] : shortNames[value]);
                },
                output = "",
                literal = false;

            if (date) {
                for (iFormat = 0; iFormat < format.length; iFormat++) {
                    if (literal) {
                        if (format.charAt(iFormat) === "'" && !lookAhead("'")) {
                            literal = false;
                        } else {
                            output += format.charAt(iFormat);
                        }
                    } else {
                        switch (format.charAt(iFormat)) {
                            case "d":
                                output += formatNumber("d", date.getDate(), 2);
                                break;
                            case "D":
                                output += formatName("D", date.getDay(), dayNamesShort, dayNames);
                                break;
                            case "o":
                                output += formatNumber("o",
                                    Math.round((new Date(date.getFullYear(), date.getMonth(), date.getDate()).getTime() - new Date(date.getFullYear(), 0, 0).getTime()) / 86400000), 3);
                                break;
                            case "m":
                                output += formatNumber("m", date.getMonth() + 1, 2);
                                break;
                            case "M":
                                output += formatName("M", date.getMonth(), monthNamesShort, monthNames);
                                break;
                            case "y":
                                output += (lookAhead("y") ? date.getFullYear() :
                                    (date.getYear() % 100 < 10 ? "0" : "") + date.getYear() % 100);
                                break;
                            case "@":
                                output += date.getTime();
                                break;
                            case "!":
                                output += date.getTime() * 10000 + this._ticksTo1970;
                                break;
                            case "'":
                                if (lookAhead("'")) {
                                    output += "'";
                                } else {
                                    literal = true;
                                }
                                break;
                            default:
                                output += format.charAt(iFormat);
                        }
                    }
                }
            }
            return output;
        },

        /* Extract all possible characters from the date format. */
        _possibleChars: function (format) {
            var iFormat,
                chars = "",
                literal = false,
                // Check whether a format character is doubled
                lookAhead = function (match) {
                    var matches = (iFormat + 1 < format.length && format.charAt(iFormat + 1) === match);
                    if (matches) {
                        iFormat++;
                    }
                    return matches;
                };

            for (iFormat = 0; iFormat < format.length; iFormat++) {
                if (literal) {
                    if (format.charAt(iFormat) === "'" && !lookAhead("'")) {
                        literal = false;
                    } else {
                        chars += format.charAt(iFormat);
                    }
                } else {
                    switch (format.charAt(iFormat)) {
                        case "d":
                        case "m":
                        case "y":
                        case "@":
                            chars += "0123456789";
                            break;
                        case "D":
                        case "M":
                            return null; // Accept anything
                        case "'":
                            if (lookAhead("'")) {
                                chars += "'";
                            } else {
                                literal = true;
                            }
                            break;
                        default:
                            chars += format.charAt(iFormat);
                    }
                }
            }
            return chars;
        },

        /* Get a setting value, defaulting if necessary. */
        _get: function (inst, name) {
            return inst.settings[name] !== undefined ?
                inst.settings[name] : this._defaults[name];
        },

        /* Parse existing date and initialise date picker. */
        _setDateFromField: function (inst, noDefault) {
            if (inst.input.val() === inst.lastVal) {
                return;
            }

            var dateFormat = this._get(inst, "dateFormat"),
                dates = inst.lastVal = inst.input ? inst.input.val() : null,
                defaultDate = this._getDefaultDate(inst),
                date = defaultDate,
                settings = this._getFormatConfig(inst);

            try {
                date = this.parseDate(dateFormat, dates, settings) || defaultDate;
            } catch (event) {
                dates = (noDefault ? "" : dates);
            }
            inst.selectedDay = date.getDate();
            inst.drawMonth = inst.selectedMonth = date.getMonth();
            inst.drawYear = inst.selectedYear = date.getFullYear();
            inst.currentDay = (dates ? date.getDate() : 0);
            inst.currentMonth = (dates ? date.getMonth() : 0);
            inst.currentYear = (dates ? date.getFullYear() : 0);
            this._adjustInstDate(inst);
        },

        /* Retrieve the default date shown on opening. */
        _getDefaultDate: function (inst) {
            return this._restrictMinMax(inst,
                this._determineDate(inst, this._get(inst, "defaultDate"), new Date()));
        },

        /* A date may be specified as an exact value or a relative one. */
        _determineDate: function (inst, date, defaultDate) {
            var offsetNumeric = function (offset) {
                    var date = new Date();
                    date.setDate(date.getDate() + offset);
                    return date;
                },
                offsetString = function (offset) {
                    try {
                        return $.datepicker.parseDate($.datepicker._get(inst, "dateFormat"),
                            offset, $.datepicker._getFormatConfig(inst));
                    }
                    catch (e) {
                        // Ignore
                    }

                    var date = (offset.toLowerCase().match(/^c/) ?
                                $.datepicker._getDate(inst) : null) || new Date(),
                        year = date.getFullYear(),
                        month = date.getMonth(),
                        day = date.getDate(),
                        pattern = /([+\-]?[0-9]+)\s*(d|D|w|W|m|M|y|Y)?/g,
                        matches = pattern.exec(offset);

                    while (matches) {
                        switch (matches[2] || "d") {
                            case "d" :
                            case "D" :
                                day += parseInt(matches[1], 10);
                                break;
                            case "w" :
                            case "W" :
                                day += parseInt(matches[1], 10) * 7;
                                break;
                            case "m" :
                            case "M" :
                                month += parseInt(matches[1], 10);
                                day = Math.min(day, $.datepicker._getDaysInMonth(year, month));
                                break;
                            case "y":
                            case "Y" :
                                year += parseInt(matches[1], 10);
                                day = Math.min(day, $.datepicker._getDaysInMonth(year, month));
                                break;
                        }
                        matches = pattern.exec(offset);
                    }
                    return new Date(year, month, day);
                },
                newDate = (date == null || date === "" ? defaultDate : (typeof date === "string" ? offsetString(date) :
                        (typeof date === "number" ? (isNaN(date) ? defaultDate : offsetNumeric(date)) : new Date(date.getTime()))));

            newDate = (newDate && newDate.toString() === "Invalid Date" ? defaultDate : newDate);
            if (newDate) {
                newDate.setHours(0);
                newDate.setMinutes(0);
                newDate.setSeconds(0);
                newDate.setMilliseconds(0);
            }
            return this._daylightSavingAdjust(newDate);
        },

        /* Handle switch to/from daylight saving.
         * Hours may be non-zero on daylight saving cut-over:
         * > 12 when midnight changeover, but then cannot generate
         * midnight datetime, so jump to 1AM, otherwise reset.
         * @param  date  (Date) the date to check
         * @return  (Date) the corrected date
         */
        _daylightSavingAdjust: function (date) {
            if (!date) {
                return null;
            }
            date.setHours(date.getHours() > 12 ? date.getHours() + 2 : 0);
            return date;
        },

        /* Set the date(s) directly. */
        _setDate: function (inst, date, noChange) {
            var clear = !date,
                origMonth = inst.selectedMonth,
                origYear = inst.selectedYear,
                newDate = this._restrictMinMax(inst, this._determineDate(inst, date, new Date()));

            inst.selectedDay = inst.currentDay = newDate.getDate();
            inst.drawMonth = inst.selectedMonth = inst.currentMonth = newDate.getMonth();
            inst.drawYear = inst.selectedYear = inst.currentYear = newDate.getFullYear();
            if ((origMonth !== inst.selectedMonth || origYear !== inst.selectedYear) && !noChange) {
                this._notifyChange(inst);
            }
            this._adjustInstDate(inst);
            if (inst.input) {
                inst.input.val(clear ? "" : this._formatDate(inst));
            }
        },

        /* Retrieve the date(s) directly. */
        _getDate: function (inst) {
            var startDate = (!inst.currentYear || (inst.input && inst.input.val() === "") ? null :
                this._daylightSavingAdjust(new Date(
                    inst.currentYear, inst.currentMonth, inst.currentDay)));
            return startDate;
        },

        /* Attach the onxxx handlers.  These are declared statically so
         * they work with static code transformers like Caja.
         */
        _attachHandlers: function (inst) {
            var stepMonths = this._get(inst, "stepMonths"),
                id = "#" + inst.id.replace(/\\\\/g, "\\");
            inst.dpDiv.find("[data-handler]").map(function () {
                var handler = {
                    prev: function () {
                        $.datepicker._adjustDate(id, -stepMonths, "M");
                    },
                    next: function () {
                        $.datepicker._adjustDate(id, +stepMonths, "M");
                    },
                    hide: function () {
                        $.datepicker._hideDatepicker();
                    },
                    today: function () {
                        $.datepicker._gotoToday(id);
                    },
                    selectDay: function () {
                        $.datepicker._selectDay(id, +this.getAttribute("data-month"), +this.getAttribute("data-year"), this);
                        return false;
                    },
                    selectMonth: function () {
                        $.datepicker._selectMonthYear(id, this, "M");
                        return false;
                    },
                    selectYear: function () {
                        $.datepicker._selectMonthYear(id, this, "Y");
                        return false;
                    }
                };
                $(this).bind(this.getAttribute("data-event"), handler[this.getAttribute("data-handler")]);
            });
        },

        /* Generate the HTML for the current state of the date picker. */
        _generateHTML: function (inst) {
            var maxDraw, prevText, prev, nextText, next, currentText, gotoDate,
                controls, buttonPanel, firstDay, showWeek, dayNames, dayNamesMin,
                monthNames, monthNamesShort, beforeShowDay, showOtherMonths,
                selectOtherMonths, defaultDate, html, dow, row, group, col, selectedDate,
                cornerClass, calender, thead, day, daysInMonth, leadDays, curRows, numRows,
                printDate, dRow, tbody, daySettings, otherMonth, unselectable,
                tempDate = new Date(),
                today = this._daylightSavingAdjust(
                    new Date(tempDate.getFullYear(), tempDate.getMonth(), tempDate.getDate())), // clear time
                isRTL = this._get(inst, "isRTL"),
                showButtonPanel = this._get(inst, "showButtonPanel"),
                hideIfNoPrevNext = this._get(inst, "hideIfNoPrevNext"),
                navigationAsDateFormat = this._get(inst, "navigationAsDateFormat"),
                numMonths = this._getNumberOfMonths(inst),
                showCurrentAtPos = this._get(inst, "showCurrentAtPos"),
                stepMonths = this._get(inst, "stepMonths"),
                isMultiMonth = (numMonths[0] !== 1 || numMonths[1] !== 1),
                currentDate = this._daylightSavingAdjust((!inst.currentDay ? new Date(9999, 9, 9) :
                    new Date(inst.currentYear, inst.currentMonth, inst.currentDay))),
                minDate = this._getMinMaxDate(inst, "min"),
                maxDate = this._getMinMaxDate(inst, "max"),
                drawMonth = inst.drawMonth - showCurrentAtPos,
                drawYear = inst.drawYear;

            if (drawMonth < 0) {
                drawMonth += 12;
                drawYear--;
            }
            if (maxDate) {
                maxDraw = this._daylightSavingAdjust(new Date(maxDate.getFullYear(),
                    maxDate.getMonth() - (numMonths[0] * numMonths[1]) + 1, maxDate.getDate()));
                maxDraw = (minDate && maxDraw < minDate ? minDate : maxDraw);
                while (this._daylightSavingAdjust(new Date(drawYear, drawMonth, 1)) > maxDraw) {
                    drawMonth--;
                    if (drawMonth < 0) {
                        drawMonth = 11;
                        drawYear--;
                    }
                }
            }
            inst.drawMonth = drawMonth;
            inst.drawYear = drawYear;

            prevText = this._get(inst, "prevText");
            prevText = (!navigationAsDateFormat ? prevText : this.formatDate(prevText,
                    this._daylightSavingAdjust(new Date(drawYear, drawMonth - stepMonths, 1)),
                    this._getFormatConfig(inst)));

            prev = (this._canAdjustMonth(inst, -1, drawYear, drawMonth) ?
                "<a class='ui-datepicker-prev ui-corner-all' data-handler='prev' data-event='click'" +
                " title='" + prevText + "'><span class='ui-icon ui-icon-circle-triangle-" + ( isRTL ? "e" : "w") + "'>" + prevText + "</span></a>" :
                (hideIfNoPrevNext ? "" : "<a class='ui-datepicker-prev ui-corner-all ui-state-disabled' title='" + prevText + "'><span class='ui-icon ui-icon-circle-triangle-" + ( isRTL ? "e" : "w") + "'>" + prevText + "</span></a>"));

            nextText = this._get(inst, "nextText");
            nextText = (!navigationAsDateFormat ? nextText : this.formatDate(nextText,
                    this._daylightSavingAdjust(new Date(drawYear, drawMonth + stepMonths, 1)),
                    this._getFormatConfig(inst)));

            next = (this._canAdjustMonth(inst, +1, drawYear, drawMonth) ?
                "<a class='ui-datepicker-next ui-corner-all' data-handler='next' data-event='click'" +
                " title='" + nextText + "'><span class='ui-icon ui-icon-circle-triangle-" + ( isRTL ? "w" : "e") + "'>" + nextText + "</span></a>" :
                (hideIfNoPrevNext ? "" : "<a class='ui-datepicker-next ui-corner-all ui-state-disabled' title='" + nextText + "'><span class='ui-icon ui-icon-circle-triangle-" + ( isRTL ? "w" : "e") + "'>" + nextText + "</span></a>"));

            currentText = this._get(inst, "currentText");
            gotoDate = (this._get(inst, "gotoCurrent") && inst.currentDay ? currentDate : today);
            currentText = (!navigationAsDateFormat ? currentText :
                this.formatDate(currentText, gotoDate, this._getFormatConfig(inst)));

            controls = (!inst.inline ? "<button type='button' class='ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all' data-handler='hide' data-event='click'>" +
                this._get(inst, "closeText") + "</button>" : "");

            buttonPanel = (showButtonPanel) ? "<div class='ui-datepicker-buttonpane ui-widget-content'>" + (isRTL ? controls : "") +
                (this._isInRange(inst, gotoDate) ? "<button type='button' class='ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all' data-handler='today' data-event='click'" +
                    ">" + currentText + "</button>" : "") + (isRTL ? "" : controls) + "</div>" : "";

            firstDay = parseInt(this._get(inst, "firstDay"), 10);
            firstDay = (isNaN(firstDay) ? 0 : firstDay);

            showWeek = this._get(inst, "showWeek");
            dayNames = this._get(inst, "dayNames");
            dayNamesMin = this._get(inst, "dayNamesMin");
            monthNames = this._get(inst, "monthNames");
            monthNamesShort = this._get(inst, "monthNamesShort");
            beforeShowDay = this._get(inst, "beforeShowDay");
            showOtherMonths = this._get(inst, "showOtherMonths");
            selectOtherMonths = this._get(inst, "selectOtherMonths");
            defaultDate = this._getDefaultDate(inst);
            html = "";
            dow;
            for (row = 0; row < numMonths[0]; row++) {
                group = "";
                this.maxRows = 4;
                for (col = 0; col < numMonths[1]; col++) {
                    selectedDate = this._daylightSavingAdjust(new Date(drawYear, drawMonth, inst.selectedDay));
                    cornerClass = " ui-corner-all";
                    calender = "";
                    if (isMultiMonth) {
                        calender += "<div class='ui-datepicker-group";
                        if (numMonths[1] > 1) {
                            switch (col) {
                                case 0:
                                    calender += " ui-datepicker-group-first";
                                    cornerClass = " ui-corner-" + (isRTL ? "right" : "left");
                                    break;
                                case numMonths[1] - 1:
                                    calender += " ui-datepicker-group-last";
                                    cornerClass = " ui-corner-" + (isRTL ? "left" : "right");
                                    break;
                                default:
                                    calender += " ui-datepicker-group-middle";
                                    cornerClass = "";
                                    break;
                            }
                        }
                        calender += "'>";
                    }
                    calender += "<div class='ui-datepicker-header ui-widget-header ui-helper-clearfix" + cornerClass + "'>" +
                        (/all|left/.test(cornerClass) && row === 0 ? (isRTL ? next : prev) : "") +
                        (/all|right/.test(cornerClass) && row === 0 ? (isRTL ? prev : next) : "") +
                        this._generateMonthYearHeader(inst, drawMonth, drawYear, minDate, maxDate,
                            row > 0 || col > 0, monthNames, monthNamesShort) + // draw month headers
                        "</div><table class='ui-datepicker-calendar'><thead>" +
                        "<tr>";
                    thead = (showWeek ? "<th class='ui-datepicker-week-col'>" + this._get(inst, "weekHeader") + "</th>" : "");
                    for (dow = 0; dow < 7; dow++) { // days of the week
                        day = (dow + firstDay) % 7;
                        thead += "<th" + ((dow + firstDay + 6) % 7 >= 5 ? " class='ui-datepicker-week-end'" : "") + ">" +
                            "<span title='" + dayNames[day] + "'>" + dayNamesMin[day] + "</span></th>";
                    }
                    calender += thead + "</tr></thead><tbody>";
                    daysInMonth = this._getDaysInMonth(drawYear, drawMonth);
                    if (drawYear === inst.selectedYear && drawMonth === inst.selectedMonth) {
                        inst.selectedDay = Math.min(inst.selectedDay, daysInMonth);
                    }
                    leadDays = (this._getFirstDayOfMonth(drawYear, drawMonth) - firstDay + 7) % 7;
                    curRows = Math.ceil((leadDays + daysInMonth) / 7); // calculate the number of rows to generate
                    numRows = (isMultiMonth ? this.maxRows > curRows ? this.maxRows : curRows : curRows); //If multiple months, use the higher number of rows (see #7043)
                    this.maxRows = numRows;
                    printDate = this._daylightSavingAdjust(new Date(drawYear, drawMonth, 1 - leadDays));
                    for (dRow = 0; dRow < numRows; dRow++) { // create date picker rows
                        calender += "<tr>";
                        tbody = (!showWeek ? "" : "<td class='ui-datepicker-week-col'>" +
                            this._get(inst, "calculateWeek")(printDate) + "</td>");
                        for (dow = 0; dow < 7; dow++) { // create date picker days
                            daySettings = (beforeShowDay ?
                                beforeShowDay.apply((inst.input ? inst.input[0] : null), [printDate]) : [true, ""]);
                            otherMonth = (printDate.getMonth() !== drawMonth);
                            unselectable = (otherMonth && !selectOtherMonths) || !daySettings[0] ||
                                (minDate && printDate < minDate) || (maxDate && printDate > maxDate);
                            tbody += "<td class='" +
                                ((dow + firstDay + 6) % 7 >= 5 ? " ui-datepicker-week-end" : "") + // highlight weekends
                                (otherMonth ? " ui-datepicker-other-month" : "") + // highlight days from other months
                                ((printDate.getTime() === selectedDate.getTime() && drawMonth === inst.selectedMonth && inst._keyEvent) || // user pressed key
                                (defaultDate.getTime() === printDate.getTime() && defaultDate.getTime() === selectedDate.getTime()) ?
                                    // or defaultDate is current printedDate and defaultDate is selectedDate
                                    " " + this._dayOverClass : "") + // highlight selected day
                                (unselectable ? " " + this._unselectableClass + " ui-state-disabled" : "") +  // highlight unselectable days
                                (otherMonth && !showOtherMonths ? "" : " " + daySettings[1] + // highlight custom dates
                                    (printDate.getTime() === currentDate.getTime() ? " " + this._currentClass : "") + // highlight selected day
                                    (printDate.getTime() === today.getTime() ? " ui-datepicker-today" : "")) + "'" + // highlight today (if different)
                                ((!otherMonth || showOtherMonths) && daySettings[2] ? " title='" + daySettings[2].replace(/'/g, "&#39;") + "'" : "") + // cell title
                                (unselectable ? "" : " data-handler='selectDay' data-event='click' data-month='" + printDate.getMonth() + "' data-year='" + printDate.getFullYear() + "'") + ">" + // actions
                                (otherMonth && !showOtherMonths ? "&#xa0;" : // display for other months
                                    (unselectable ? "<span class='ui-state-default'>" + printDate.getDate() + "</span>" : "<a class='ui-state-default" +
                                        (printDate.getTime() === today.getTime() ? " ui-state-highlight" : "") +
                                        (printDate.getTime() === currentDate.getTime() ? " ui-state-active" : "") + // highlight selected day
                                        (otherMonth ? " ui-priority-secondary" : "") + // distinguish dates from other months
                                        "' href='#'>" + printDate.getDate() + "</a>")) + "</td>"; // display selectable date
                            printDate.setDate(printDate.getDate() + 1);
                            printDate = this._daylightSavingAdjust(printDate);
                        }
                        calender += tbody + "</tr>";
                    }
                    drawMonth++;
                    if (drawMonth > 11) {
                        drawMonth = 0;
                        drawYear++;
                    }
                    calender += "</tbody></table>" + (isMultiMonth ? "</div>" +
                            ((numMonths[0] > 0 && col === numMonths[1] - 1) ? "<div class='ui-datepicker-row-break'></div>" : "") : "");
                    group += calender;
                }
                html += group;
            }
            html += buttonPanel;
            inst._keyEvent = false;
            return html;
        },

        /* Generate the month and year header. */
        _generateMonthYearHeader: function (inst, drawMonth, drawYear, minDate, maxDate,
                                            secondary, monthNames, monthNamesShort) {

            var inMinYear, inMaxYear, month, years, thisYear, determineYear, year, endYear,
                changeMonth = this._get(inst, "changeMonth"),
                changeYear = this._get(inst, "changeYear"),
                showMonthAfterYear = this._get(inst, "showMonthAfterYear"),
                html = "<div class='ui-datepicker-title'>",
                monthHtml = "";

            // month selection
            if (secondary || !changeMonth) {
                monthHtml += "<span class='ui-datepicker-month'>" + monthNames[drawMonth] + "</span>";
            } else {
                inMinYear = (minDate && minDate.getFullYear() === drawYear);
                inMaxYear = (maxDate && maxDate.getFullYear() === drawYear);
                monthHtml += "<select class='ui-datepicker-month' data-handler='selectMonth' data-event='change'>";
                for (month = 0; month < 12; month++) {
                    if ((!inMinYear || month >= minDate.getMonth()) && (!inMaxYear || month <= maxDate.getMonth())) {
                        monthHtml += "<option value='" + month + "'" +
                            (month === drawMonth ? " selected='selected'" : "") +
                            ">" + monthNamesShort[month] + "</option>";
                    }
                }
                monthHtml += "</select>";
            }

            if (!showMonthAfterYear) {
                html += monthHtml + (secondary || !(changeMonth && changeYear) ? "&#xa0;" : "");
            }

            // year selection
            if (!inst.yearshtml) {
                inst.yearshtml = "";
                if (secondary || !changeYear) {
                    html += "<span class='ui-datepicker-year'>" + drawYear + "</span>";
                } else {
                    // determine range of years to display
                    years = this._get(inst, "yearRange").split(":");
                    thisYear = new Date().getFullYear();
                    determineYear = function (value) {
                        var year = (value.match(/c[+\-].*/) ? drawYear + parseInt(value.substring(1), 10) :
                            (value.match(/[+\-].*/) ? thisYear + parseInt(value, 10) :
                                parseInt(value, 10)));
                        return (isNaN(year) ? thisYear : year);
                    };
                    year = determineYear(years[0]);
                    endYear = Math.max(year, determineYear(years[1] || ""));
                    year = (minDate ? Math.max(year, minDate.getFullYear()) : year);
                    endYear = (maxDate ? Math.min(endYear, maxDate.getFullYear()) : endYear);
                    inst.yearshtml += "<select class='ui-datepicker-year' data-handler='selectYear' data-event='change'>";
                    for (; year <= endYear; year++) {
                        inst.yearshtml += "<option value='" + year + "'" +
                            (year === drawYear ? " selected='selected'" : "") +
                            ">" + year + "</option>";
                    }
                    inst.yearshtml += "</select>";

                    html += inst.yearshtml;
                    inst.yearshtml = null;
                }
            }

            html += this._get(inst, "yearSuffix");
            if (showMonthAfterYear) {
                html += (secondary || !(changeMonth && changeYear) ? "&#xa0;" : "") + monthHtml;
            }
            html += "</div>"; // Close datepicker_header
            return html;
        },

        /* Adjust one of the date sub-fields. */
        _adjustInstDate: function (inst, offset, period) {
            var year = inst.drawYear + (period === "Y" ? offset : 0),
                month = inst.drawMonth + (period === "M" ? offset : 0),
                day = Math.min(inst.selectedDay, this._getDaysInMonth(year, month)) + (period === "D" ? offset : 0),
                date = this._restrictMinMax(inst, this._daylightSavingAdjust(new Date(year, month, day)));

            inst.selectedDay = date.getDate();
            inst.drawMonth = inst.selectedMonth = date.getMonth();
            inst.drawYear = inst.selectedYear = date.getFullYear();
            if (period === "M" || period === "Y") {
                this._notifyChange(inst);
            }
        },

        /* Ensure a date is within any min/max bounds. */
        _restrictMinMax: function (inst, date) {
            var minDate = this._getMinMaxDate(inst, "min"),
                maxDate = this._getMinMaxDate(inst, "max"),
                newDate = (minDate && date < minDate ? minDate : date);
            return (maxDate && newDate > maxDate ? maxDate : newDate);
        },

        /* Notify change of month/year. */
        _notifyChange: function (inst) {
            var onChange = this._get(inst, "onChangeMonthYear");
            if (onChange) {
                onChange.apply((inst.input ? inst.input[0] : null),
                    [inst.selectedYear, inst.selectedMonth + 1, inst]);
            }
        },

        /* Determine the number of months to show. */
        _getNumberOfMonths: function (inst) {
            var numMonths = this._get(inst, "numberOfMonths");
            return (numMonths == null ? [1, 1] : (typeof numMonths === "number" ? [1, numMonths] : numMonths));
        },

        /* Determine the current maximum date - ensure no time components are set. */
        _getMinMaxDate: function (inst, minMax) {
            return this._determineDate(inst, this._get(inst, minMax + "Date"), null);
        },

        /* Find the number of days in a given month. */
        _getDaysInMonth: function (year, month) {
            return 32 - this._daylightSavingAdjust(new Date(year, month, 32)).getDate();
        },

        /* Find the day of the week of the first of a month. */
        _getFirstDayOfMonth: function (year, month) {
            return new Date(year, month, 1).getDay();
        },

        /* Determines if we should allow a "next/prev" month display change. */
        _canAdjustMonth: function (inst, offset, curYear, curMonth) {
            var numMonths = this._getNumberOfMonths(inst),
                date = this._daylightSavingAdjust(new Date(curYear,
                    curMonth + (offset < 0 ? offset : numMonths[0] * numMonths[1]), 1));

            if (offset < 0) {
                date.setDate(this._getDaysInMonth(date.getFullYear(), date.getMonth()));
            }
            return this._isInRange(inst, date);
        },

        /* Is the given date in the accepted range? */
        _isInRange: function (inst, date) {
            var yearSplit, currentYear,
                minDate = this._getMinMaxDate(inst, "min"),
                maxDate = this._getMinMaxDate(inst, "max"),
                minYear = null,
                maxYear = null,
                years = this._get(inst, "yearRange");
            if (years) {
                yearSplit = years.split(":");
                currentYear = new Date().getFullYear();
                minYear = parseInt(yearSplit[0], 10);
                maxYear = parseInt(yearSplit[1], 10);
                if (yearSplit[0].match(/[+\-].*/)) {
                    minYear += currentYear;
                }
                if (yearSplit[1].match(/[+\-].*/)) {
                    maxYear += currentYear;
                }
            }

            return ((!minDate || date.getTime() >= minDate.getTime()) &&
            (!maxDate || date.getTime() <= maxDate.getTime()) &&
            (!minYear || date.getFullYear() >= minYear) &&
            (!maxYear || date.getFullYear() <= maxYear));
        },

        /* Provide the configuration settings for formatting/parsing. */
        _getFormatConfig: function (inst) {
            var shortYearCutoff = this._get(inst, "shortYearCutoff");
            shortYearCutoff = (typeof shortYearCutoff !== "string" ? shortYearCutoff :
                new Date().getFullYear() % 100 + parseInt(shortYearCutoff, 10));
            return {
                shortYearCutoff: shortYearCutoff,
                dayNamesShort: this._get(inst, "dayNamesShort"), dayNames: this._get(inst, "dayNames"),
                monthNamesShort: this._get(inst, "monthNamesShort"), monthNames: this._get(inst, "monthNames")
            };
        },

        /* Format the given date for display. */
        _formatDate: function (inst, day, month, year) {
            if (!day) {
                inst.currentDay = inst.selectedDay;
                inst.currentMonth = inst.selectedMonth;
                inst.currentYear = inst.selectedYear;
            }
            var date = (day ? (typeof day === "object" ? day :
                    this._daylightSavingAdjust(new Date(year, month, day))) :
                this._daylightSavingAdjust(new Date(inst.currentYear, inst.currentMonth, inst.currentDay)));
            return this.formatDate(this._get(inst, "dateFormat"), date, this._getFormatConfig(inst));
        }
    });

    /*
     * Bind hover events for datepicker elements.
     * Done via delegate so the binding only occurs once in the lifetime of the parent div.
     * Global instActive, set by _updateDatepicker allows the handlers to find their way back to the active picker.
     */
    function bindHover(dpDiv) {
        var selector = "button, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-calendar td a";
        return dpDiv.delegate(selector, "mouseout", function () {
            $(this).removeClass("ui-state-hover");
            if (this.className.indexOf("ui-datepicker-prev") !== -1) {
                $(this).removeClass("ui-datepicker-prev-hover");
            }
            if (this.className.indexOf("ui-datepicker-next") !== -1) {
                $(this).removeClass("ui-datepicker-next-hover");
            }
        })
            .delegate(selector, "mouseover", function () {
                if (!$.datepicker._isDisabledDatepicker(instActive.inline ? dpDiv.parent()[0] : instActive.input[0])) {
                    $(this).parents(".ui-datepicker-calendar").find("a").removeClass("ui-state-hover");
                    $(this).addClass("ui-state-hover");
                    if (this.className.indexOf("ui-datepicker-prev") !== -1) {
                        $(this).addClass("ui-datepicker-prev-hover");
                    }
                    if (this.className.indexOf("ui-datepicker-next") !== -1) {
                        $(this).addClass("ui-datepicker-next-hover");
                    }
                }
            });
    }

    /* jQuery extend now ignores nulls! */
    function extendRemove(target, props) {
        $.extend(target, props);
        for (var name in props) {
            if (props[name] == null) {
                target[name] = props[name];
            }
        }
        return target;
    }

    /* Invoke the datepicker functionality.
     @param  options  string - a command, optionally followed by additional parameters or
     Object - settings for attaching new datepicker functionality
     @return  jQuery object */
    $.fn.datepicker = function (options) {

        /* Verify an empty collection wasn't passed - Fixes #6976 */
        if (!this.length) {
            return this;
        }

        /* Initialise the date picker. */
        if (!$.datepicker.initialized) {
            $(document).mousedown($.datepicker._checkExternalClick);
            $.datepicker.initialized = true;
        }

        /* Append datepicker main container to body if not exist. */
        if ($("#" + $.datepicker._mainDivId).length === 0) {
            $("body").append($.datepicker.dpDiv);
        }

        var otherArgs = Array.prototype.slice.call(arguments, 1);
        if (typeof options === "string" && (options === "isDisabled" || options === "getDate" || options === "widget")) {
            return $.datepicker["_" + options + "Datepicker"].apply($.datepicker, [this[0]].concat(otherArgs));
        }
        if (options === "option" && arguments.length === 2 && typeof arguments[1] === "string") {
            return $.datepicker["_" + options + "Datepicker"].apply($.datepicker, [this[0]].concat(otherArgs));
        }
        return this.each(function () {
            typeof options === "string" ?
                $.datepicker["_" + options + "Datepicker"].apply($.datepicker, [this].concat(otherArgs)) :
                $.datepicker._attachDatepicker(this, options);
        });
    };

    $.datepicker = new Datepicker(); // singleton instance
    $.datepicker.initialized = false;
    $.datepicker.uuid = new Date().getTime();
    $.datepicker.version = "1.10.3";

})(jQuery);
(function ($, undefined) {

    var dataSpace = "ui-effects-";

    $.effects = {
        effect: {}
    };

    /*!
     * jQuery Color Animations v2.1.2
     * https://github.com/jquery/jquery-color
     *
     * Copyright 2013 jQuery Foundation and other contributors
     * Released under the MIT license.
     * http://jquery.org/license
     *
     * Date: Wed Jan 16 08:47:09 2013 -0600
     */
    (function (jQuery, undefined) {

        var stepHooks = "backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor",

            // plusequals test for += 100 -= 100
            rplusequals = /^([\-+])=\s*(\d+\.?\d*)/,
            // a set of RE's that can match strings and generate color tuples.
            stringParsers = [{
                re: /rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
                parse: function (execResult) {
                    return [
                        execResult[1],
                        execResult[2],
                        execResult[3],
                        execResult[4]
                    ];
                }
            }, {
                re: /rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
                parse: function (execResult) {
                    return [
                        execResult[1] * 2.55,
                        execResult[2] * 2.55,
                        execResult[3] * 2.55,
                        execResult[4]
                    ];
                }
            }, {
                // this regex ignores A-F because it's compared against an already lowercased string
                re: /#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/,
                parse: function (execResult) {
                    return [
                        parseInt(execResult[1], 16),
                        parseInt(execResult[2], 16),
                        parseInt(execResult[3], 16)
                    ];
                }
            }, {
                // this regex ignores A-F because it's compared against an already lowercased string
                re: /#([a-f0-9])([a-f0-9])([a-f0-9])/,
                parse: function (execResult) {
                    return [
                        parseInt(execResult[1] + execResult[1], 16),
                        parseInt(execResult[2] + execResult[2], 16),
                        parseInt(execResult[3] + execResult[3], 16)
                    ];
                }
            }, {
                re: /hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
                space: "hsla",
                parse: function (execResult) {
                    return [
                        execResult[1],
                        execResult[2] / 100,
                        execResult[3] / 100,
                        execResult[4]
                    ];
                }
            }],

            // jQuery.Color( )
            color = jQuery.Color = function (color, green, blue, alpha) {
                return new jQuery.Color.fn.parse(color, green, blue, alpha);
            },
            spaces = {
                rgba: {
                    props: {
                        red: {
                            idx: 0,
                            type: "byte"
                        },
                        green: {
                            idx: 1,
                            type: "byte"
                        },
                        blue: {
                            idx: 2,
                            type: "byte"
                        }
                    }
                },

                hsla: {
                    props: {
                        hue: {
                            idx: 0,
                            type: "degrees"
                        },
                        saturation: {
                            idx: 1,
                            type: "percent"
                        },
                        lightness: {
                            idx: 2,
                            type: "percent"
                        }
                    }
                }
            },
            propTypes = {
                "byte": {
                    floor: true,
                    max: 255
                },
                "percent": {
                    max: 1
                },
                "degrees": {
                    mod: 360,
                    floor: true
                }
            },
            support = color.support = {},

            // element for support tests
            supportElem = jQuery("<p>")[0],

            // colors = jQuery.Color.names
            colors,

            // local aliases of functions called often
            each = jQuery.each;

// determine rgba support immediately
        supportElem.style.cssText = "background-color:rgba(1,1,1,.5)";
        support.rgba = supportElem.style.backgroundColor.indexOf("rgba") > -1;

// define cache name and alpha properties
// for rgba and hsla spaces
        each(spaces, function (spaceName, space) {
            space.cache = "_" + spaceName;
            space.props.alpha = {
                idx: 3,
                type: "percent",
                def: 1
            };
        });

        function clamp(value, prop, allowEmpty) {
            var type = propTypes[prop.type] || {};

            if (value == null) {
                return (allowEmpty || !prop.def) ? null : prop.def;
            }

            // ~~ is an short way of doing floor for positive numbers
            value = type.floor ? ~~value : parseFloat(value);

            // IE will pass in empty strings as value for alpha,
            // which will hit this case
            if (isNaN(value)) {
                return prop.def;
            }

            if (type.mod) {
                // we add mod before modding to make sure that negatives values
                // get converted properly: -10 -> 350
                return (value + type.mod) % type.mod;
            }

            // for now all property types without mod have min and max
            return 0 > value ? 0 : type.max < value ? type.max : value;
        }

        function stringParse(string) {
            var inst = color(),
                rgba = inst._rgba = [];

            string = string.toLowerCase();

            each(stringParsers, function (i, parser) {
                var parsed,
                    match = parser.re.exec(string),
                    values = match && parser.parse(match),
                    spaceName = parser.space || "rgba";

                if (values) {
                    parsed = inst[spaceName](values);

                    // if this was an rgba parse the assignment might happen twice
                    // oh well....
                    inst[spaces[spaceName].cache] = parsed[spaces[spaceName].cache];
                    rgba = inst._rgba = parsed._rgba;

                    // exit each( stringParsers ) here because we matched
                    return false;
                }
            });

            // Found a stringParser that handled it
            if (rgba.length) {

                // if this came from a parsed string, force "transparent" when alpha is 0
                // chrome, (and maybe others) return "transparent" as rgba(0,0,0,0)
                if (rgba.join() === "0,0,0,0") {
                    jQuery.extend(rgba, colors.transparent);
                }
                return inst;
            }

            // named colors
            return colors[string];
        }

        color.fn = jQuery.extend(color.prototype, {
            parse: function (red, green, blue, alpha) {
                if (red === undefined) {
                    this._rgba = [null, null, null, null];
                    return this;
                }
                if (red.jquery || red.nodeType) {
                    red = jQuery(red).css(green);
                    green = undefined;
                }

                var inst = this,
                    type = jQuery.type(red),
                    rgba = this._rgba = [];

                // more than 1 argument specified - assume ( red, green, blue, alpha )
                if (green !== undefined) {
                    red = [red, green, blue, alpha];
                    type = "array";
                }

                if (type === "string") {
                    return this.parse(stringParse(red) || colors._default);
                }

                if (type === "array") {
                    each(spaces.rgba.props, function (key, prop) {
                        rgba[prop.idx] = clamp(red[prop.idx], prop);
                    });
                    return this;
                }

                if (type === "object") {
                    if (red instanceof color) {
                        each(spaces, function (spaceName, space) {
                            if (red[space.cache]) {
                                inst[space.cache] = red[space.cache].slice();
                            }
                        });
                    } else {
                        each(spaces, function (spaceName, space) {
                            var cache = space.cache;
                            each(space.props, function (key, prop) {

                                // if the cache doesn't exist, and we know how to convert
                                if (!inst[cache] && space.to) {

                                    // if the value was null, we don't need to copy it
                                    // if the key was alpha, we don't need to copy it either
                                    if (key === "alpha" || red[key] == null) {
                                        return;
                                    }
                                    inst[cache] = space.to(inst._rgba);
                                }

                                // this is the only case where we allow nulls for ALL properties.
                                // call clamp with alwaysAllowEmpty
                                inst[cache][prop.idx] = clamp(red[key], prop, true);
                            });

                            // everything defined but alpha?
                            if (inst[cache] && jQuery.inArray(null, inst[cache].slice(0, 3)) < 0) {
                                // use the default of 1
                                inst[cache][3] = 1;
                                if (space.from) {
                                    inst._rgba = space.from(inst[cache]);
                                }
                            }
                        });
                    }
                    return this;
                }
            },
            is: function (compare) {
                var is = color(compare),
                    same = true,
                    inst = this;

                each(spaces, function (_, space) {
                    var localCache,
                        isCache = is[space.cache];
                    if (isCache) {
                        localCache = inst[space.cache] || space.to && space.to(inst._rgba) || [];
                        each(space.props, function (_, prop) {
                            if (isCache[prop.idx] != null) {
                                same = ( isCache[prop.idx] === localCache[prop.idx] );
                                return same;
                            }
                        });
                    }
                    return same;
                });
                return same;
            },
            _space: function () {
                var used = [],
                    inst = this;
                each(spaces, function (spaceName, space) {
                    if (inst[space.cache]) {
                        used.push(spaceName);
                    }
                });
                return used.pop();
            },
            transition: function (other, distance) {
                var end = color(other),
                    spaceName = end._space(),
                    space = spaces[spaceName],
                    startColor = this.alpha() === 0 ? color("transparent") : this,
                    start = startColor[space.cache] || space.to(startColor._rgba),
                    result = start.slice();

                end = end[space.cache];
                each(space.props, function (key, prop) {
                    var index = prop.idx,
                        startValue = start[index],
                        endValue = end[index],
                        type = propTypes[prop.type] || {};

                    // if null, don't override start value
                    if (endValue === null) {
                        return;
                    }
                    // if null - use end
                    if (startValue === null) {
                        result[index] = endValue;
                    } else {
                        if (type.mod) {
                            if (endValue - startValue > type.mod / 2) {
                                startValue += type.mod;
                            } else if (startValue - endValue > type.mod / 2) {
                                startValue -= type.mod;
                            }
                        }
                        result[index] = clamp(( endValue - startValue ) * distance + startValue, prop);
                    }
                });
                return this[spaceName](result);
            },
            blend: function (opaque) {
                // if we are already opaque - return ourself
                if (this._rgba[3] === 1) {
                    return this;
                }

                var rgb = this._rgba.slice(),
                    a = rgb.pop(),
                    blend = color(opaque)._rgba;

                return color(jQuery.map(rgb, function (v, i) {
                    return ( 1 - a ) * blend[i] + a * v;
                }));
            },
            toRgbaString: function () {
                var prefix = "rgba(",
                    rgba = jQuery.map(this._rgba, function (v, i) {
                        return v == null ? ( i > 2 ? 1 : 0 ) : v;
                    });

                if (rgba[3] === 1) {
                    rgba.pop();
                    prefix = "rgb(";
                }

                return prefix + rgba.join() + ")";
            },
            toHslaString: function () {
                var prefix = "hsla(",
                    hsla = jQuery.map(this.hsla(), function (v, i) {
                        if (v == null) {
                            v = i > 2 ? 1 : 0;
                        }

                        // catch 1 and 2
                        if (i && i < 3) {
                            v = Math.round(v * 100) + "%";
                        }
                        return v;
                    });

                if (hsla[3] === 1) {
                    hsla.pop();
                    prefix = "hsl(";
                }
                return prefix + hsla.join() + ")";
            },
            toHexString: function (includeAlpha) {
                var rgba = this._rgba.slice(),
                    alpha = rgba.pop();

                if (includeAlpha) {
                    rgba.push(~~( alpha * 255 ));
                }

                return "#" + jQuery.map(rgba, function (v) {

                        // default to 0 when nulls exist
                        v = ( v || 0 ).toString(16);
                        return v.length === 1 ? "0" + v : v;
                    }).join("");
            },
            toString: function () {
                return this._rgba[3] === 0 ? "transparent" : this.toRgbaString();
            }
        });
        color.fn.parse.prototype = color.fn;

// hsla conversions adapted from:
// https://code.google.com/p/maashaack/source/browse/packages/graphics/trunk/src/graphics/colors/HUE2RGB.as?r=5021

        function hue2rgb(p, q, h) {
            h = ( h + 1 ) % 1;
            if (h * 6 < 1) {
                return p + (q - p) * h * 6;
            }
            if (h * 2 < 1) {
                return q;
            }
            if (h * 3 < 2) {
                return p + (q - p) * ((2 / 3) - h) * 6;
            }
            return p;
        }

        spaces.hsla.to = function (rgba) {
            if (rgba[0] == null || rgba[1] == null || rgba[2] == null) {
                return [null, null, null, rgba[3]];
            }
            var r = rgba[0] / 255,
                g = rgba[1] / 255,
                b = rgba[2] / 255,
                a = rgba[3],
                max = Math.max(r, g, b),
                min = Math.min(r, g, b),
                diff = max - min,
                add = max + min,
                l = add * 0.5,
                h, s;

            if (min === max) {
                h = 0;
            } else if (r === max) {
                h = ( 60 * ( g - b ) / diff ) + 360;
            } else if (g === max) {
                h = ( 60 * ( b - r ) / diff ) + 120;
            } else {
                h = ( 60 * ( r - g ) / diff ) + 240;
            }

            // chroma (diff) == 0 means greyscale which, by definition, saturation = 0%
            // otherwise, saturation is based on the ratio of chroma (diff) to lightness (add)
            if (diff === 0) {
                s = 0;
            } else if (l <= 0.5) {
                s = diff / add;
            } else {
                s = diff / ( 2 - add );
            }
            return [Math.round(h) % 360, s, l, a == null ? 1 : a];
        };

        spaces.hsla.from = function (hsla) {
            if (hsla[0] == null || hsla[1] == null || hsla[2] == null) {
                return [null, null, null, hsla[3]];
            }
            var h = hsla[0] / 360,
                s = hsla[1],
                l = hsla[2],
                a = hsla[3],
                q = l <= 0.5 ? l * ( 1 + s ) : l + s - l * s,
                p = 2 * l - q;

            return [
                Math.round(hue2rgb(p, q, h + ( 1 / 3 )) * 255),
                Math.round(hue2rgb(p, q, h) * 255),
                Math.round(hue2rgb(p, q, h - ( 1 / 3 )) * 255),
                a
            ];
        };


        each(spaces, function (spaceName, space) {
            var props = space.props,
                cache = space.cache,
                to = space.to,
                from = space.from;

            // makes rgba() and hsla()
            color.fn[spaceName] = function (value) {

                // generate a cache for this space if it doesn't exist
                if (to && !this[cache]) {
                    this[cache] = to(this._rgba);
                }
                if (value === undefined) {
                    return this[cache].slice();
                }

                var ret,
                    type = jQuery.type(value),
                    arr = ( type === "array" || type === "object" ) ? value : arguments,
                    local = this[cache].slice();

                each(props, function (key, prop) {
                    var val = arr[type === "object" ? key : prop.idx];
                    if (val == null) {
                        val = local[prop.idx];
                    }
                    local[prop.idx] = clamp(val, prop);
                });

                if (from) {
                    ret = color(from(local));
                    ret[cache] = local;
                    return ret;
                } else {
                    return color(local);
                }
            };

            // makes red() green() blue() alpha() hue() saturation() lightness()
            each(props, function (key, prop) {
                // alpha is included in more than one space
                if (color.fn[key]) {
                    return;
                }
                color.fn[key] = function (value) {
                    var vtype = jQuery.type(value),
                        fn = ( key === "alpha" ? ( this._hsla ? "hsla" : "rgba" ) : spaceName ),
                        local = this[fn](),
                        cur = local[prop.idx],
                        match;

                    if (vtype === "undefined") {
                        return cur;
                    }

                    if (vtype === "function") {
                        value = value.call(this, cur);
                        vtype = jQuery.type(value);
                    }
                    if (value == null && prop.empty) {
                        return this;
                    }
                    if (vtype === "string") {
                        match = rplusequals.exec(value);
                        if (match) {
                            value = cur + parseFloat(match[2]) * ( match[1] === "+" ? 1 : -1 );
                        }
                    }
                    local[prop.idx] = value;
                    return this[fn](local);
                };
            });
        });

// add cssHook and .fx.step function for each named hook.
// accept a space separated string of properties
        color.hook = function (hook) {
            var hooks = hook.split(" ");
            each(hooks, function (i, hook) {
                jQuery.cssHooks[hook] = {
                    set: function (elem, value) {
                        var parsed, curElem,
                            backgroundColor = "";

                        if (value !== "transparent" && ( jQuery.type(value) !== "string" || ( parsed = stringParse(value) ) )) {
                            value = color(parsed || value);
                            if (!support.rgba && value._rgba[3] !== 1) {
                                curElem = hook === "backgroundColor" ? elem.parentNode : elem;
                                while (
                                (backgroundColor === "" || backgroundColor === "transparent") &&
                                curElem && curElem.style
                                    ) {
                                    try {
                                        backgroundColor = jQuery.css(curElem, "backgroundColor");
                                        curElem = curElem.parentNode;
                                    } catch (e) {
                                    }
                                }

                                value = value.blend(backgroundColor && backgroundColor !== "transparent" ?
                                    backgroundColor :
                                    "_default");
                            }

                            value = value.toRgbaString();
                        }
                        try {
                            elem.style[hook] = value;
                        } catch (e) {
                            // wrapped to prevent IE from throwing errors on "invalid" values like 'auto' or 'inherit'
                        }
                    }
                };
                jQuery.fx.step[hook] = function (fx) {
                    if (!fx.colorInit) {
                        fx.start = color(fx.elem, hook);
                        fx.end = color(fx.end);
                        fx.colorInit = true;
                    }
                    jQuery.cssHooks[hook].set(fx.elem, fx.start.transition(fx.end, fx.pos));
                };
            });

        };

        color.hook(stepHooks);

        jQuery.cssHooks.borderColor = {
            expand: function (value) {
                var expanded = {};

                each(["Top", "Right", "Bottom", "Left"], function (i, part) {
                    expanded["border" + part + "Color"] = value;
                });
                return expanded;
            }
        };

// Basic color names only.
// Usage of any of the other color names requires adding yourself or including
// jquery.color.svg-names.js.
        colors = jQuery.Color.names = {
            // 4.1. Basic color keywords
            aqua: "#00ffff",
            black: "#000000",
            blue: "#0000ff",
            fuchsia: "#ff00ff",
            gray: "#808080",
            green: "#008000",
            lime: "#00ff00",
            maroon: "#800000",
            navy: "#000080",
            olive: "#808000",
            purple: "#800080",
            red: "#ff0000",
            silver: "#c0c0c0",
            teal: "#008080",
            white: "#ffffff",
            yellow: "#ffff00",

            // 4.2.3. "transparent" color keyword
            transparent: [null, null, null, 0],

            _default: "#ffffff"
        };

    })(jQuery);


    /******************************************************************************/
    /****************************** CLASS ANIMATIONS ******************************/
    /******************************************************************************/
    (function () {

        var classAnimationActions = ["add", "remove", "toggle"],
            shorthandStyles = {
                border: 1,
                borderBottom: 1,
                borderColor: 1,
                borderLeft: 1,
                borderRight: 1,
                borderTop: 1,
                borderWidth: 1,
                margin: 1,
                padding: 1
            };

        $.each(["borderLeftStyle", "borderRightStyle", "borderBottomStyle", "borderTopStyle"], function (_, prop) {
            $.fx.step[prop] = function (fx) {
                if (fx.end !== "none" && !fx.setAttr || fx.pos === 1 && !fx.setAttr) {
                    jQuery.style(fx.elem, prop, fx.end);
                    fx.setAttr = true;
                }
            };
        });

        function getElementStyles(elem) {
            var key, len,
                style = elem.ownerDocument.defaultView ?
                    elem.ownerDocument.defaultView.getComputedStyle(elem, null) :
                    elem.currentStyle,
                styles = {};

            if (style && style.length && style[0] && style[style[0]]) {
                len = style.length;
                while (len--) {
                    key = style[len];
                    if (typeof style[key] === "string") {
                        styles[$.camelCase(key)] = style[key];
                    }
                }
                // support: Opera, IE <9
            } else {
                for (key in style) {
                    if (typeof style[key] === "string") {
                        styles[key] = style[key];
                    }
                }
            }

            return styles;
        }


        function styleDifference(oldStyle, newStyle) {
            var diff = {},
                name, value;

            for (name in newStyle) {
                value = newStyle[name];
                if (oldStyle[name] !== value) {
                    if (!shorthandStyles[name]) {
                        if ($.fx.step[name] || !isNaN(parseFloat(value))) {
                            diff[name] = value;
                        }
                    }
                }
            }

            return diff;
        }

// support: jQuery <1.8
        if (!$.fn.addBack) {
            $.fn.addBack = function (selector) {
                return this.add(selector == null ?
                    this.prevObject : this.prevObject.filter(selector)
                );
            };
        }

        $.effects.animateClass = function (value, duration, easing, callback) {
            var o = $.speed(duration, easing, callback);

            return this.queue(function () {
                var animated = $(this),
                    baseClass = animated.attr("class") || "",
                    applyClassChange,
                    allAnimations = o.children ? animated.find("*").addBack() : animated;

                // map the animated objects to store the original styles.
                allAnimations = allAnimations.map(function () {
                    var el = $(this);
                    return {
                        el: el,
                        start: getElementStyles(this)
                    };
                });

                // apply class change
                applyClassChange = function () {
                    $.each(classAnimationActions, function (i, action) {
                        if (value[action]) {
                            animated[action + "Class"](value[action]);
                        }
                    });
                };
                applyClassChange();

                // map all animated objects again - calculate new styles and diff
                allAnimations = allAnimations.map(function () {
                    this.end = getElementStyles(this.el[0]);
                    this.diff = styleDifference(this.start, this.end);
                    return this;
                });

                // apply original class
                animated.attr("class", baseClass);

                // map all animated objects again - this time collecting a promise
                allAnimations = allAnimations.map(function () {
                    var styleInfo = this,
                        dfd = $.Deferred(),
                        opts = $.extend({}, o, {
                            queue: false,
                            complete: function () {
                                dfd.resolve(styleInfo);
                            }
                        });

                    this.el.animate(this.diff, opts);
                    return dfd.promise();
                });

                // once all animations have completed:
                $.when.apply($, allAnimations.get()).done(function () {

                    // set the final class
                    applyClassChange();

                    // for each animated element,
                    // clear all css properties that were animated
                    $.each(arguments, function () {
                        var el = this.el;
                        $.each(this.diff, function (key) {
                            el.css(key, "");
                        });
                    });

                    // this is guarnteed to be there if you use jQuery.speed()
                    // it also handles dequeuing the next anim...
                    o.complete.call(animated[0]);
                });
            });
        };

        $.fn.extend({
            addClass: (function (orig) {
                return function (classNames, speed, easing, callback) {
                    return speed ?
                        $.effects.animateClass.call(this,
                            {add: classNames}, speed, easing, callback) :
                        orig.apply(this, arguments);
                };
            })($.fn.addClass),

            removeClass: (function (orig) {
                return function (classNames, speed, easing, callback) {
                    return arguments.length > 1 ?
                        $.effects.animateClass.call(this,
                            {remove: classNames}, speed, easing, callback) :
                        orig.apply(this, arguments);
                };
            })($.fn.removeClass),

            toggleClass: (function (orig) {
                return function (classNames, force, speed, easing, callback) {
                    if (typeof force === "boolean" || force === undefined) {
                        if (!speed) {
                            // without speed parameter
                            return orig.apply(this, arguments);
                        } else {
                            return $.effects.animateClass.call(this,
                                (force ? {add: classNames} : {remove: classNames}),
                                speed, easing, callback);
                        }
                    } else {
                        // without force parameter
                        return $.effects.animateClass.call(this,
                            {toggle: classNames}, force, speed, easing);
                    }
                };
            })($.fn.toggleClass),

            switchClass: function (remove, add, speed, easing, callback) {
                return $.effects.animateClass.call(this, {
                    add: add,
                    remove: remove
                }, speed, easing, callback);
            }
        });

    })();

    /******************************************************************************/
    /*********************************** EFFECTS **********************************/
    /******************************************************************************/

    (function () {

        $.extend($.effects, {
            version: "1.10.3",

            // Saves a set of properties in a data storage
            save: function (element, set) {
                for (var i = 0; i < set.length; i++) {
                    if (set[i] !== null) {
                        element.data(dataSpace + set[i], element[0].style[set[i]]);
                    }
                }
            },

            // Restores a set of previously saved properties from a data storage
            restore: function (element, set) {
                var val, i;
                for (i = 0; i < set.length; i++) {
                    if (set[i] !== null) {
                        val = element.data(dataSpace + set[i]);
                        // support: jQuery 1.6.2
                        // http://bugs.jquery.com/ticket/9917
                        // jQuery 1.6.2 incorrectly returns undefined for any falsy value.
                        // We can't differentiate between "" and 0 here, so we just assume
                        // empty string since it's likely to be a more common value...
                        if (val === undefined) {
                            val = "";
                        }
                        element.css(set[i], val);
                    }
                }
            },

            setMode: function (el, mode) {
                if (mode === "toggle") {
                    mode = el.is(":hidden") ? "show" : "hide";
                }
                return mode;
            },

            // Translates a [top,left] array into a baseline value
            // this should be a little more flexible in the future to handle a string & hash
            getBaseline: function (origin, original) {
                var y, x;
                switch (origin[0]) {
                    case "top":
                        y = 0;
                        break;
                    case "middle":
                        y = 0.5;
                        break;
                    case "bottom":
                        y = 1;
                        break;
                    default:
                        y = origin[0] / original.height;
                }
                switch (origin[1]) {
                    case "left":
                        x = 0;
                        break;
                    case "center":
                        x = 0.5;
                        break;
                    case "right":
                        x = 1;
                        break;
                    default:
                        x = origin[1] / original.width;
                }
                return {
                    x: x,
                    y: y
                };
            },

            // Wraps the element around a wrapper that copies position properties
            createWrapper: function (element) {

                // if the element is already wrapped, return it
                if (element.parent().is(".ui-effects-wrapper")) {
                    return element.parent();
                }

                // wrap the element
                var props = {
                        width: element.outerWidth(true),
                        height: element.outerHeight(true),
                        "float": element.css("float")
                    },
                    wrapper = $("<div></div>")
                        .addClass("ui-effects-wrapper")
                        .css({
                            fontSize: "100%",
                            background: "transparent",
                            border: "none",
                            margin: 0,
                            padding: 0
                        }),
                    // Store the size in case width/height are defined in % - Fixes #5245
                    size = {
                        width: element.width(),
                        height: element.height()
                    },
                    active = document.activeElement;

                // support: Firefox
                // Firefox incorrectly exposes anonymous content
                // https://bugzilla.mozilla.org/show_bug.cgi?id=561664
                try {
                    active.id;
                } catch (e) {
                    active = document.body;
                }

                element.wrap(wrapper);

                // Fixes #7595 - Elements lose focus when wrapped.
                if (element[0] === active || $.contains(element[0], active)) {
                    $(active).focus();
                }

                wrapper = element.parent(); //Hotfix for jQuery 1.4 since some change in wrap() seems to actually lose the reference to the wrapped element

                // transfer positioning properties to the wrapper
                if (element.css("position") === "static") {
                    wrapper.css({position: "relative"});
                    element.css({position: "relative"});
                } else {
                    $.extend(props, {
                        position: element.css("position"),
                        zIndex: element.css("z-index")
                    });
                    $.each(["top", "left", "bottom", "right"], function (i, pos) {
                        props[pos] = element.css(pos);
                        if (isNaN(parseInt(props[pos], 10))) {
                            props[pos] = "auto";
                        }
                    });
                    element.css({
                        position: "relative",
                        top: 0,
                        left: 0,
                        right: "auto",
                        bottom: "auto"
                    });
                }
                element.css(size);

                return wrapper.css(props).show();
            },

            removeWrapper: function (element) {
                var active = document.activeElement;

                if (element.parent().is(".ui-effects-wrapper")) {
                    element.parent().replaceWith(element);

                    // Fixes #7595 - Elements lose focus when wrapped.
                    if (element[0] === active || $.contains(element[0], active)) {
                        $(active).focus();
                    }
                }


                return element;
            },

            setTransition: function (element, list, factor, value) {
                value = value || {};
                $.each(list, function (i, x) {
                    var unit = element.cssUnit(x);
                    if (unit[0] > 0) {
                        value[x] = unit[0] * factor + unit[1];
                    }
                });
                return value;
            }
        });

// return an effect options object for the given parameters:
        function _normalizeArguments(effect, options, speed, callback) {

            // allow passing all options as the first parameter
            if ($.isPlainObject(effect)) {
                options = effect;
                effect = effect.effect;
            }

            // convert to an object
            effect = {effect: effect};

            // catch (effect, null, ...)
            if (options == null) {
                options = {};
            }

            // catch (effect, callback)
            if ($.isFunction(options)) {
                callback = options;
                speed = null;
                options = {};
            }

            // catch (effect, speed, ?)
            if (typeof options === "number" || $.fx.speeds[options]) {
                callback = speed;
                speed = options;
                options = {};
            }

            // catch (effect, options, callback)
            if ($.isFunction(speed)) {
                callback = speed;
                speed = null;
            }

            // add options to effect
            if (options) {
                $.extend(effect, options);
            }

            speed = speed || options.duration;
            effect.duration = $.fx.off ? 0 :
                typeof speed === "number" ? speed :
                    speed in $.fx.speeds ? $.fx.speeds[speed] :
                        $.fx.speeds._default;

            effect.complete = callback || options.complete;

            return effect;
        }

        function standardAnimationOption(option) {
            // Valid standard speeds (nothing, number, named speed)
            if (!option || typeof option === "number" || $.fx.speeds[option]) {
                return true;
            }

            // Invalid strings - treat as "normal" speed
            if (typeof option === "string" && !$.effects.effect[option]) {
                return true;
            }

            // Complete callback
            if ($.isFunction(option)) {
                return true;
            }

            // Options hash (but not naming an effect)
            if (typeof option === "object" && !option.effect) {
                return true;
            }

            // Didn't match any standard API
            return false;
        }

        $.fn.extend({
            effect: function (/* effect, options, speed, callback */) {
                var args = _normalizeArguments.apply(this, arguments),
                    mode = args.mode,
                    queue = args.queue,
                    effectMethod = $.effects.effect[args.effect];

                if ($.fx.off || !effectMethod) {
                    // delegate to the original method (e.g., .show()) if possible
                    if (mode) {
                        return this[mode](args.duration, args.complete);
                    } else {
                        return this.each(function () {
                            if (args.complete) {
                                args.complete.call(this);
                            }
                        });
                    }
                }

                function run(next) {
                    var elem = $(this),
                        complete = args.complete,
                        mode = args.mode;

                    function done() {
                        if ($.isFunction(complete)) {
                            complete.call(elem[0]);
                        }
                        if ($.isFunction(next)) {
                            next();
                        }
                    }

                    // If the element already has the correct final state, delegate to
                    // the core methods so the internal tracking of "olddisplay" works.
                    if (elem.is(":hidden") ? mode === "hide" : mode === "show") {
                        elem[mode]();
                        done();
                    } else {
                        effectMethod.call(elem[0], args, done);
                    }
                }

                return queue === false ? this.each(run) : this.queue(queue || "fx", run);
            },

            show: (function (orig) {
                return function (option) {
                    if (standardAnimationOption(option)) {
                        return orig.apply(this, arguments);
                    } else {
                        var args = _normalizeArguments.apply(this, arguments);
                        args.mode = "show";
                        return this.effect.call(this, args);
                    }
                };
            })($.fn.show),

            hide: (function (orig) {
                return function (option) {
                    if (standardAnimationOption(option)) {
                        return orig.apply(this, arguments);
                    } else {
                        var args = _normalizeArguments.apply(this, arguments);
                        args.mode = "hide";
                        return this.effect.call(this, args);
                    }
                };
            })($.fn.hide),

            toggle: (function (orig) {
                return function (option) {
                    if (standardAnimationOption(option) || typeof option === "boolean") {
                        return orig.apply(this, arguments);
                    } else {
                        var args = _normalizeArguments.apply(this, arguments);
                        args.mode = "toggle";
                        return this.effect.call(this, args);
                    }
                };
            })($.fn.toggle),

            // helper functions
            cssUnit: function (key) {
                var style = this.css(key),
                    val = [];

                $.each(["em", "px", "%", "pt"], function (i, unit) {
                    if (style.indexOf(unit) > 0) {
                        val = [parseFloat(style), unit];
                    }
                });
                return val;
            }
        });

    })();

    /******************************************************************************/
    /*********************************** EASING ***********************************/
    /******************************************************************************/


})(jQuery);

// JavaScript Document
(function ($) {
    "use strict";
    $.fn.countTo = function (options) {
        // merge the default plugin settings with the custom options
        options = $.extend({}, $.fn.countTo.defaults, options || {});

        // how many times to update the value, and how much to increment the value on each update
        var loops = Math.ceil(options.speed / options.refreshInterval),
            increment = (options.to - options.from) / loops;

        return $(this).each(function () {
            var _this = this,
                loopCount = 0,
                value = options.from,
                interval = setInterval(updateTimer, options.refreshInterval);

            function updateTimer() {
                value += increment;
                loopCount++;
                $(_this).html(value.toFixed(options.decimals));

                if (typeof(options.onUpdate) == 'function') {
                    options.onUpdate.call(_this, value);
                }

                if (loopCount >= loops) {
                    clearInterval(interval);
                    value = options.to;

                    if (typeof(options.onComplete) == 'function') {
                        options.onComplete.call(_this, value);

                    }
                }
            }
        });
    };

    $.fn.countTo.defaults = {
        from: 0,  // the number the element should start at
        to: 100,  // the number the element should end at
        speed: 1000,  // how long it should take to count between the target numbers
        refreshInterval: 100,  // how often the element should be updated
        decimals: 0,  // the number of decimal places to show
        onUpdate: null,  // callback method for every time the element is updated,
        onComplete: null,  // callback method for when the element finishes updating
    };
})(jQuery);

/*Settings of Counters*/

jQuery(function ($) {

    $('#counters').waypoint(function (direction) {

        $('.quantity-counter1').countTo({
            from: 0,
            to: 37,
            speed: 2000,
            refreshInterval: 50,
            onComplete: function (value) {
                console.debug(this);
            }
        });
        $('.quantity-counter2').countTo({
            from: 0,
            to: 186,
            speed: 2000,
            refreshInterval: 50,
            onComplete: function (value) {
                console.debug(this);
            }
        });
        $('.quantity-counter3').countTo({
            from: 0,
            to: 25,
            speed: 2000,
            refreshInterval: 50,
            onComplete: function (value) {
                console.debug(this);
            }
        });
        $('.quantity-counter4').countTo({
            from: 0,
            to: 355,
            speed: 2000,
            refreshInterval: 50,
            onComplete: function (value) {
                console.debug(this);
            }
        });

        // COUNTER 2


    }, {
        offset: function () {
            return $.waypoints('viewportHeight') - $(this).height() + 100;
        }
    });


});


// Generated by CoffeeScript 1.6.2
/*
 jQuery Waypoints - v2.0.3
 Copyright (c) 2011-2013 Caleb Troughton
 Dual licensed under the MIT license and GPL license.
 https://github.com/imakewebthings/jquery-waypoints/blob/master/licenses.txt
 */


(function () {
    'use strict';

    var __indexOf = [].indexOf || function (item) {
                for (var i = 0, l = this.length; i < l; i++) {
                    if (i in this && this[i] === item) return i;
                }
                return -1;
            },
        __slice = [].slice;

    (function (root, factory) {
        if (typeof define === 'function' && define.amd) {
            return define('waypoints', ['jquery'], function ($) {
                return factory($, root);
            });
        } else {
            return factory(root.jQuery, root);
        }
    })(this, function ($, window) {
        var $w, Context, Waypoint, allWaypoints, contextCounter, contextKey, contexts, isTouch, jQMethods, methods, resizeEvent, scrollEvent, waypointCounter, waypointKey, wp, wps;

        $w = $(window);
        isTouch = __indexOf.call(window, 'ontouchstart') >= 0;
        allWaypoints = {
            horizontal: {},
            vertical: {}
        };
        contextCounter = 1;
        contexts = {};
        contextKey = 'waypoints-context-id';
        resizeEvent = 'resize.waypoints';
        scrollEvent = 'scroll.waypoints';
        waypointCounter = 1;
        waypointKey = 'waypoints-waypoint-ids';
        wp = 'waypoint';
        wps = 'waypoints';
        Context = (function () {
            function Context($element) {
                var _this = this;

                this.$element = $element;
                this.element = $element[0];
                this.didResize = false;
                this.didScroll = false;
                this.id = 'context' + contextCounter++;
                this.oldScroll = {
                    x: $element.scrollLeft(),
                    y: $element.scrollTop()
                };
                this.waypoints = {
                    horizontal: {},
                    vertical: {}
                };
                $element.data(contextKey, this.id);
                contexts[this.id] = this;
                $element.bind(scrollEvent, function () {
                    var scrollHandler;

                    if (!(_this.didScroll || isTouch)) {
                        _this.didScroll = true;
                        scrollHandler = function () {
                            _this.doScroll();
                            return _this.didScroll = false;
                        };
                        return window.setTimeout(scrollHandler, $[wps].settings.scrollThrottle);
                    }
                });
                $element.bind(resizeEvent, function () {
                    var resizeHandler;

                    if (!_this.didResize) {
                        _this.didResize = true;
                        resizeHandler = function () {
                            $[wps]('refresh');
                            return _this.didResize = false;
                        };
                        return window.setTimeout(resizeHandler, $[wps].settings.resizeThrottle);
                    }
                });
            }

            Context.prototype.doScroll = function () {
                var axes,
                    _this = this;

                axes = {
                    horizontal: {
                        newScroll: this.$element.scrollLeft(),
                        oldScroll: this.oldScroll.x,
                        forward: 'right',
                        backward: 'left'
                    },
                    vertical: {
                        newScroll: this.$element.scrollTop(),
                        oldScroll: this.oldScroll.y,
                        forward: 'down',
                        backward: 'up'
                    }
                };
                if (isTouch && (!axes.vertical.oldScroll || !axes.vertical.newScroll)) {
                    $[wps]('refresh');
                }
                $.each(axes, function (aKey, axis) {
                    var direction, isForward, triggered;

                    triggered = [];
                    isForward = axis.newScroll > axis.oldScroll;
                    direction = isForward ? axis.forward : axis.backward;
                    $.each(_this.waypoints[aKey], function (wKey, waypoint) {
                        var _ref, _ref1;

                        if ((axis.oldScroll < (_ref = waypoint.offset) && _ref <= axis.newScroll)) {
                            return triggered.push(waypoint);
                        } else if ((axis.newScroll < (_ref1 = waypoint.offset) && _ref1 <= axis.oldScroll)) {
                            return triggered.push(waypoint);
                        }
                    });
                    triggered.sort(function (a, b) {
                        return a.offset - b.offset;
                    });
                    if (!isForward) {
                        triggered.reverse();
                    }
                    return $.each(triggered, function (i, waypoint) {
                        if (waypoint.options.continuous || i === triggered.length - 1) {
                            return waypoint.trigger([direction]);
                        }
                    });
                });
                return this.oldScroll = {
                    x: axes.horizontal.newScroll,
                    y: axes.vertical.newScroll
                };
            };

            Context.prototype.refresh = function () {
                var axes, cOffset, isWin,
                    _this = this;

                isWin = $.isWindow(this.element);
                cOffset = this.$element.offset();
                this.doScroll();
                axes = {
                    horizontal: {
                        contextOffset: isWin ? 0 : cOffset.left,
                        contextScroll: isWin ? 0 : this.oldScroll.x,
                        contextDimension: this.$element.width(),
                        oldScroll: this.oldScroll.x,
                        forward: 'right',
                        backward: 'left',
                        offsetProp: 'left'
                    },
                    vertical: {
                        contextOffset: isWin ? 0 : cOffset.top,
                        contextScroll: isWin ? 0 : this.oldScroll.y,
                        contextDimension: isWin ? $[wps]('viewportHeight') : this.$element.height(),
                        oldScroll: this.oldScroll.y,
                        forward: 'down',
                        backward: 'up',
                        offsetProp: 'top'
                    }
                };
                return $.each(axes, function (aKey, axis) {
                    return $.each(_this.waypoints[aKey], function (i, waypoint) {
                        var adjustment, elementOffset, oldOffset, _ref, _ref1;

                        adjustment = waypoint.options.offset;
                        oldOffset = waypoint.offset;
                        elementOffset = $.isWindow(waypoint.element) ? 0 : waypoint.$element.offset()[axis.offsetProp];
                        if ($.isFunction(adjustment)) {
                            adjustment = adjustment.apply(waypoint.element);
                        } else if (typeof adjustment === 'string') {
                            adjustment = parseFloat(adjustment);
                            if (waypoint.options.offset.indexOf('%') > -1) {
                                adjustment = Math.ceil(axis.contextDimension * adjustment / 100);
                            }
                        }
                        waypoint.offset = elementOffset - axis.contextOffset + axis.contextScroll - adjustment;
                        if ((waypoint.options.onlyOnScroll && (oldOffset != null)) || !waypoint.enabled) {
                            return;
                        }
                        if (oldOffset !== null && (oldOffset < (_ref = axis.oldScroll) && _ref <= waypoint.offset)) {
                            return waypoint.trigger([axis.backward]);
                        } else if (oldOffset !== null && (oldOffset > (_ref1 = axis.oldScroll) && _ref1 >= waypoint.offset)) {
                            return waypoint.trigger([axis.forward]);
                        } else if (oldOffset === null && axis.oldScroll >= waypoint.offset) {
                            return waypoint.trigger([axis.forward]);
                        }
                    });
                });
            };

            Context.prototype.checkEmpty = function () {
                if ($.isEmptyObject(this.waypoints.horizontal) && $.isEmptyObject(this.waypoints.vertical)) {
                    this.$element.unbind([resizeEvent, scrollEvent].join(' '));
                    return delete contexts[this.id];
                }
            };

            return Context;

        })();
        Waypoint = (function () {
            function Waypoint($element, context, options) {
                var idList, _ref;

                options = $.extend({}, $.fn[wp].defaults, options);
                if (options.offset === 'bottom-in-view') {
                    options.offset = function () {
                        var contextHeight;

                        contextHeight = $[wps]('viewportHeight');
                        if (!$.isWindow(context.element)) {
                            contextHeight = context.$element.height();
                        }
                        return contextHeight - $(this).outerHeight();
                    };
                }
                this.$element = $element;
                this.element = $element[0];
                this.axis = options.horizontal ? 'horizontal' : 'vertical';
                this.callback = options.handler;


                this.context = context;
                this.enabled = options.enabled;
                this.id = 'waypoints' + waypointCounter++;
                this.offset = null;
                this.options = options;
                context.waypoints[this.axis][this.id] = this;
                allWaypoints[this.axis][this.id] = this;
                idList = (_ref = $element.data(waypointKey)) != null ? _ref : [];
                idList.push(this.id);
                $element.data(waypointKey, idList);
            }

            Waypoint.prototype.trigger = function (args) {
                if (!this.enabled) {
                    return;
                }
                if (this.callback != null) {
                    this.callback.apply(this.element, args);
                }
                if (this.options.triggerOnce) {
                    return this.destroy();
                }
            };

            Waypoint.prototype.disable = function () {
                return this.enabled = false;
            };

            Waypoint.prototype.enable = function () {
                this.context.refresh();
                return this.enabled = true;
            };

            Waypoint.prototype.destroy = function () {
                delete allWaypoints[this.axis][this.id];
                delete this.context.waypoints[this.axis][this.id];
                return this.context.checkEmpty();
            };

            Waypoint.getWaypointsByElement = function (element) {
                var all, ids;

                ids = $(element).data(waypointKey);
                if (!ids) {
                    return [];
                }
                all = $.extend({}, allWaypoints.horizontal, allWaypoints.vertical);
                return $.map(ids, function (id) {
                    return all[id];
                });
            };

            return Waypoint;

        })();
        methods = {
            init: function (f, options) {
                var _ref;

                if (options == null) {
                    options = {};
                }
                if ((_ref = options.handler) == null) {
                    options.handler = f;
                }
                this.each(function () {
                    var $this, context, contextElement, _ref1;

                    $this = $(this);
                    contextElement = (_ref1 = options.context) != null ? _ref1 : $.fn[wp].defaults.context;
                    if (!$.isWindow(contextElement)) {
                        contextElement = $this.closest(contextElement);
                    }
                    contextElement = $(contextElement);
                    context = contexts[contextElement.data(contextKey)];
                    if (!context) {
                        context = new Context(contextElement);
                    }
                    return new Waypoint($this, context, options);
                });
                $[wps]('refresh');
                return this;
            },
            disable: function () {
                return methods._invoke(this, 'disable');
            },
            enable: function () {
                return methods._invoke(this, 'enable');
            },
            destroy: function () {
                return methods._invoke(this, 'destroy');
            },
            prev: function (axis, selector) {
                return methods._traverse.call(this, axis, selector, function (stack, index, waypoints) {
                    if (index > 0) {
                        return stack.push(waypoints[index - 1]);
                    }
                });
            },
            next: function (axis, selector) {
                return methods._traverse.call(this, axis, selector, function (stack, index, waypoints) {
                    if (index < waypoints.length - 1) {
                        return stack.push(waypoints[index + 1]);
                    }
                });
            },
            _traverse: function (axis, selector, push) {
                var stack, waypoints;

                if (axis == null) {
                    axis = 'vertical';
                }
                if (selector == null) {
                    selector = window;
                }
                waypoints = jQMethods.aggregate(selector);
                stack = [];
                this.each(function () {
                    var index;

                    index = $.inArray(this, waypoints[axis]);
                    return push(stack, index, waypoints[axis]);
                });
                return this.pushStack(stack);
            },
            _invoke: function ($elements, method) {
                $elements.each(function () {
                    var waypoints;

                    waypoints = Waypoint.getWaypointsByElement(this);
                    return $.each(waypoints, function (i, waypoint) {
                        waypoint[method]();
                        return true;
                    });
                });
                return this;
            }
        };
        $.fn[wp] = function () {
            var args, method;

            method = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
            if (methods[method]) {
                return methods[method].apply(this, args);
            } else if ($.isFunction(method)) {
                return methods.init.apply(this, arguments);
            } else if ($.isPlainObject(method)) {
                return methods.init.apply(this, [null, method]);
            } else if (!method) {
                return $.error("jQuery Waypoints needs a callback function or handler option.");
            } else {
                return $.error("The " + method + " method does not exist in jQuery Waypoints.");
            }
        };
        $.fn[wp].defaults = {
            context: window,
            continuous: true,
            enabled: true,
            horizontal: false,
            offset: 0,
            triggerOnce: false
        };
        jQMethods = {
            refresh: function () {
                return $.each(contexts, function (i, context) {
                    return context.refresh();
                });
            },
            viewportHeight: function () {
                var _ref;

                return (_ref = window.innerHeight) != null ? _ref : $w.height();
            },
            aggregate: function (contextSelector) {
                var collection, waypoints, _ref;

                collection = allWaypoints;
                if (contextSelector) {
                    collection = (_ref = contexts[$(contextSelector).data(contextKey)]) != null ? _ref.waypoints : void 0;
                }
                if (!collection) {
                    return [];
                }
                waypoints = {
                    horizontal: [],
                    vertical: []
                };
                $.each(waypoints, function (axis, arr) {
                    $.each(collection[axis], function (key, waypoint) {
                        return arr.push(waypoint);
                    });
                    arr.sort(function (a, b) {
                        return a.offset - b.offset;
                    });
                    waypoints[axis] = $.map(arr, function (waypoint) {
                        return waypoint.element;
                    });
                    return waypoints[axis] = $.unique(waypoints[axis]);
                });
                return waypoints;
            },
            above: function (contextSelector) {
                if (contextSelector == null) {
                    contextSelector = window;
                }
                return jQMethods._filter(contextSelector, 'vertical', function (context, waypoint) {
                    return waypoint.offset <= context.oldScroll.y;
                });
            },
            below: function (contextSelector) {
                if (contextSelector == null) {
                    contextSelector = window;
                }
                return jQMethods._filter(contextSelector, 'vertical', function (context, waypoint) {
                    return waypoint.offset > context.oldScroll.y;
                });
            },
            left: function (contextSelector) {
                if (contextSelector == null) {
                    contextSelector = window;
                }
                return jQMethods._filter(contextSelector, 'horizontal', function (context, waypoint) {
                    return waypoint.offset <= context.oldScroll.x;
                });
            },
            right: function (contextSelector) {
                if (contextSelector == null) {
                    contextSelector = window;
                }
                return jQMethods._filter(contextSelector, 'horizontal', function (context, waypoint) {
                    return waypoint.offset > context.oldScroll.x;
                });
            },
            enable: function () {
                return jQMethods._invoke('enable');
            },
            disable: function () {
                return jQMethods._invoke('disable');
            },
            destroy: function () {
                return jQMethods._invoke('destroy');
            },
            extendFn: function (methodName, f) {
                return methods[methodName] = f;
            },
            _invoke: function (method) {
                var waypoints;

                waypoints = $.extend({}, allWaypoints.vertical, allWaypoints.horizontal);
                return $.each(waypoints, function (key, waypoint) {
                    waypoint[method]();
                    return true;
                });
            },
            _filter: function (selector, axis, test) {
                var context, waypoints;

                context = contexts[$(selector).data(contextKey)];
                if (!context) {
                    return [];
                }
                waypoints = [];
                $.each(context.waypoints[axis], function (i, waypoint) {
                    if (test(context, waypoint)) {
                        return waypoints.push(waypoint);
                    }
                });
                waypoints.sort(function (a, b) {
                    return a.offset - b.offset;
                });
                return $.map(waypoints, function (waypoint) {
                    return waypoint.element;
                });
            }
        };
        $[wps] = function () {
            var args, method;

            method = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
            if (jQMethods[method]) {
                return jQMethods[method].apply(null, args);
            } else {
                return jQMethods.aggregate.call(null, method);
            }
        };
        $[wps].settings = {
            resizeThrottle: 100,
            scrollThrottle: 30
        };
        return $w.load(function () {
            return $[wps]('refresh');
        });
    });

}).call(this);

! function (a, b) {
	'use strict';
    "object" == typeof exports ? module.exports = b(require("jquery")) : "function" == typeof define && define.amd ? define("EasyPieChart", ["jquery"], b) : b(a.jQuery)
}(this, function (a) {
    var b = function (a, b) {
        var c, d = document.createElement("canvas");
		
        "undefined" != typeof G_vmlCanvasManager && G_vmlCanvasManager.initElement(d);
        var e = d.getContext("2d");
        d.width = d.height = b.size, a.appendChild(d);
        var f = 1;
        window.devicePixelRatio > 1 && (f = window.devicePixelRatio, d.style.width = d.style.height = [b.size, "px"].join(""), d.width = d.height = b.size * f, e.scale(f, f)), e.translate(b.size / 2, b.size / 2), e.rotate((-0.5 + b.rotate / 180) * Math.PI);
        var g = (b.size - b.lineWidth) / 2;
        b.scaleColor && b.scaleLength && (g -= b.scaleLength + 2), Date.now = Date.now || function () {
            return +new Date
        };
        var h = function (a, b, c) {
            c = Math.min(Math.max(-1, c || 0), 1);
            var d = 0 >= c ? !0 : !1;
            e.beginPath(), e.arc(0, 0, g, 0, 2 * Math.PI * c, d), e.strokeStyle = a, e.lineWidth = b, e.stroke()
        }, i = function () {
                var a, c, d = 24;
                e.lineWidth = 1, e.fillStyle = b.scaleColor, e.save();
                for (var d = 24; d > 0; --d) 0 === d % 6 ? (c = b.scaleLength, a = 0) : (c = .6 * b.scaleLength, a = b.scaleLength - c), e.fillRect(-b.size / 2 + a, 0, c, 1), e.rotate(Math.PI / 12);
                e.restore()
            }, j = function () {
                return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || function (a) {
                    window.setTimeout(a, 1e3 / 60)
                }
            }(),
            k = function () {
                b.scaleColor && i(), b.trackColor && h(b.trackColor, b.lineWidth, 1)
            };
        this.clear = function () {
            e.clearRect(b.size / -2, b.size / -2, b.size, b.size)
        }, this.draw = function (a) {
            b.scaleColor || b.trackColor ? e.getImageData && e.putImageData ? c ? e.putImageData(c, 0, 0) : (k(), c = e.getImageData(0, 0, b.size * f, b.size * f)) : (this.clear(), k()) : this.clear(), e.lineCap = b.lineCap;
            var d;
            d = "function" == typeof b.barColor ? b.barColor(a) : b.barColor, h(d, b.lineWidth, a / 100)
        }.bind(this), this.animate = function (a, c) {
            var d = Date.now();
            b.onStart(a, c);
            var e = function () {
                var f = Math.min(Date.now() - d, b.animate),
                    g = b.easing(this, f, a, c - a, b.animate);
                this.draw(g), b.onStep(a, c, g), f >= b.animate ? b.onStop(a, c) : j(e)
            }.bind(this);
            j(e)
        }.bind(this)
    }, c = function (a, c) {
            var d = {
                barColor: "#02ADC6",
                trackColor: "#ecece9",
                //scaleColor: "#dfe0e0",
                scaleLength: 5,
                //lineCap: "round",
                lineWidth: 20,
                size: 181,
                rotate: 0,
                animate: 1e3,
                easing: function (a, b, c, d, e) {
                    return b /= e / 2, 1 > b ? d / 2 * b * b + c : -d / 2 * (--b * (b - 2) - 1) + c
                },
                onStart: function () {},
                onStep: function () {},
                onStop: function () {}
            };
            if ("undefined" != typeof b) d.renderer = b;
            else {
                if ("undefined" == typeof SVGRenderer) throw new Error("Please load either the SVG- or the CanvasRenderer");
                d.renderer = SVGRenderer
            }
            var e = {}, f = 0,
                g = function () {
                    this.el = a, this.options = e;
                    for (var b in d) d.hasOwnProperty(b) && (e[b] = c && "undefined" != typeof c[b] ? c[b] : d[b], "function" == typeof e[b] && (e[b] = e[b].bind(this)));
                    e.easing = "string" == typeof e.easing && "undefined" != typeof jQuery && jQuery.isFunction(jQuery.easing[e.easing]) ? jQuery.easing[e.easing] : d.easing, this.renderer = new e.renderer(a, e), this.renderer.draw(f), a.dataset && a.dataset.percent ? this.update(parseFloat(a.dataset.percent)) : a.getAttribute && a.getAttribute("data-percent") && this.update(parseFloat(a.getAttribute("data-percent")))
                }.bind(this);
            this.update = function (a) {
                return a = parseFloat(a), e.animate ? this.renderer.animate(f, a) : this.renderer.draw(a), f = a, this
            }.bind(this), g()
        };
    a.fn.easyPieChart = function (b) {
        return this.each(function () {
            var d;
            a.data(this, "easyPieChart") || (d = a.extend({}, b, a(this).data()), a.data(this, "easyPieChart", new c(this, d)))
        })
    }
});






















// Generated by CoffeeScript 1.6.2
/*
jQuery Waypoints - v2.0.3
Copyright (c) 2011-2013 Caleb Troughton
Dual licensed under the MIT license and GPL license.
https://github.com/imakewebthings/jquery-waypoints/blob/master/licenses.txt
*/


(function() {
  var __indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; },
    __slice = [].slice;

  (function(root, factory) {
    if (typeof define === 'function' && define.amd) {
      return define('waypoints', ['jquery'], function($) {
        return factory($, root);
      });
    } else {
      return factory(root.jQuery, root);
    }
  })(this, function($, window) {
    var $w, Context, Waypoint, allWaypoints, contextCounter, contextKey, contexts, isTouch, jQMethods, methods, resizeEvent, scrollEvent, waypointCounter, waypointKey, wp, wps;

    $w = $(window);
    isTouch = __indexOf.call(window, 'ontouchstart') >= 0;
    allWaypoints = {
      horizontal: {},
      vertical: {}
    };
    contextCounter = 1;
    contexts = {};
    contextKey = 'waypoints-context-id';
    resizeEvent = 'resize.waypoints';
    scrollEvent = 'scroll.waypoints';
    waypointCounter = 1;
    waypointKey = 'waypoints-waypoint-ids';
    wp = 'waypoint';
    wps = 'waypoints';
    Context = (function() {
      function Context($element) {
        var _this = this;

        this.$element = $element;
        this.element = $element[0];
        this.didResize = false;
        this.didScroll = false;
        this.id = 'context' + contextCounter++;
        this.oldScroll = {
          x: $element.scrollLeft(),
          y: $element.scrollTop()
        };
        this.waypoints = {
          horizontal: {},
          vertical: {}
        };
        $element.data(contextKey, this.id);
        contexts[this.id] = this;
        $element.bind(scrollEvent, function() {
          var scrollHandler;

          if (!(_this.didScroll || isTouch)) {
            _this.didScroll = true;
            scrollHandler = function() {
              _this.doScroll();
              return _this.didScroll = false;
            };
            return window.setTimeout(scrollHandler, $[wps].settings.scrollThrottle);
          }
        });
        $element.bind(resizeEvent, function() {
          var resizeHandler;

          if (!_this.didResize) {
            _this.didResize = true;
            resizeHandler = function() {
              $[wps]('refresh');
              return _this.didResize = false;
            };
            return window.setTimeout(resizeHandler, $[wps].settings.resizeThrottle);
          }
        });
      }

      Context.prototype.doScroll = function() {
        var axes,
          _this = this;

        axes = {
          horizontal: {
            newScroll: this.$element.scrollLeft(),
            oldScroll: this.oldScroll.x,
            forward: 'right',
            backward: 'left'
          },
          vertical: {
            newScroll: this.$element.scrollTop(),
            oldScroll: this.oldScroll.y,
            forward: 'down',
            backward: 'up'
          }
        };
        if (isTouch && (!axes.vertical.oldScroll || !axes.vertical.newScroll)) {
          $[wps]('refresh');
        }
        $.each(axes, function(aKey, axis) {
          var direction, isForward, triggered;

          triggered = [];
          isForward = axis.newScroll > axis.oldScroll;
          direction = isForward ? axis.forward : axis.backward;
          $.each(_this.waypoints[aKey], function(wKey, waypoint) {
            var _ref, _ref1;

            if ((axis.oldScroll < (_ref = waypoint.offset) && _ref <= axis.newScroll)) {
              return triggered.push(waypoint);
            } else if ((axis.newScroll < (_ref1 = waypoint.offset) && _ref1 <= axis.oldScroll)) {
              return triggered.push(waypoint);
            }
          });
          triggered.sort(function(a, b) {
            return a.offset - b.offset;
          });
          if (!isForward) {
            triggered.reverse();
          }
          return $.each(triggered, function(i, waypoint) {
            if (waypoint.options.continuous || i === triggered.length - 1) {
              return waypoint.trigger([direction]);
            }
          });
        });
        return this.oldScroll = {
          x: axes.horizontal.newScroll,
          y: axes.vertical.newScroll
        };
      };

      Context.prototype.refresh = function() {
        var axes, cOffset, isWin,
          _this = this;

        isWin = $.isWindow(this.element);
        cOffset = this.$element.offset();
        this.doScroll();
        axes = {
          horizontal: {
            contextOffset: isWin ? 0 : cOffset.left,
            contextScroll: isWin ? 0 : this.oldScroll.x,
            contextDimension: this.$element.width(),
            oldScroll: this.oldScroll.x,
            forward: 'right',
            backward: 'left',
            offsetProp: 'left'
          },
          vertical: {
            contextOffset: isWin ? 0 : cOffset.top,
            contextScroll: isWin ? 0 : this.oldScroll.y,
            contextDimension: isWin ? $[wps]('viewportHeight') : this.$element.height(),
            oldScroll: this.oldScroll.y,
            forward: 'down',
            backward: 'up',
            offsetProp: 'top'
          }
        };
        return $.each(axes, function(aKey, axis) {
          return $.each(_this.waypoints[aKey], function(i, waypoint) {
            var adjustment, elementOffset, oldOffset, _ref, _ref1;

            adjustment = waypoint.options.offset;
            oldOffset = waypoint.offset;
            elementOffset = $.isWindow(waypoint.element) ? 0 : waypoint.$element.offset()[axis.offsetProp];
            if ($.isFunction(adjustment)) {
              adjustment = adjustment.apply(waypoint.element);
            } else if (typeof adjustment === 'string') {
              adjustment = parseFloat(adjustment);
              if (waypoint.options.offset.indexOf('%') > -1) {
                adjustment = Math.ceil(axis.contextDimension * adjustment / 100);
              }
            }
            waypoint.offset = elementOffset - axis.contextOffset + axis.contextScroll - adjustment;
            if ((waypoint.options.onlyOnScroll && (oldOffset != null)) || !waypoint.enabled) {
              return;
            }
            if (oldOffset !== null && (oldOffset < (_ref = axis.oldScroll) && _ref <= waypoint.offset)) {
              return waypoint.trigger([axis.backward]);
            } else if (oldOffset !== null && (oldOffset > (_ref1 = axis.oldScroll) && _ref1 >= waypoint.offset)) {
              return waypoint.trigger([axis.forward]);
            } else if (oldOffset === null && axis.oldScroll >= waypoint.offset) {
              return waypoint.trigger([axis.forward]);
            }
          });
        });
      };

      Context.prototype.checkEmpty = function() {
        if ($.isEmptyObject(this.waypoints.horizontal) && $.isEmptyObject(this.waypoints.vertical)) {
          this.$element.unbind([resizeEvent, scrollEvent].join(' '));
          return delete contexts[this.id];
        }
      };

      return Context;

    })();
    Waypoint = (function() {
      function Waypoint($element, context, options) {
        var idList, _ref;

        options = $.extend({}, $.fn[wp].defaults, options);
        if (options.offset === 'bottom-in-view') {
          options.offset = function() {
            var contextHeight;

            contextHeight = $[wps]('viewportHeight');
            if (!$.isWindow(context.element)) {
              contextHeight = context.$element.height();
            }
            return contextHeight - $(this).outerHeight();
          };
        }
        this.$element = $element;
        this.element = $element[0];
        this.axis = options.horizontal ? 'horizontal' : 'vertical';
        this.callback = options.handler;
        this.context = context;
        this.enabled = options.enabled;
        this.id = 'waypoints' + waypointCounter++;
        this.offset = null;
        this.options = options;
        context.waypoints[this.axis][this.id] = this;
        allWaypoints[this.axis][this.id] = this;
        idList = (_ref = $element.data(waypointKey)) != null ? _ref : [];
        idList.push(this.id);
        $element.data(waypointKey, idList);
      }

      Waypoint.prototype.trigger = function(args) {
        if (!this.enabled) {
          return;
        }
        if (this.callback != null) {
          this.callback.apply(this.element, args);
        }
        if (this.options.triggerOnce) {
          return this.destroy();
        }
      };

      Waypoint.prototype.disable = function() {
        return this.enabled = false;
      };

      Waypoint.prototype.enable = function() {
        this.context.refresh();
        return this.enabled = true;
      };

      Waypoint.prototype.destroy = function() {
        delete allWaypoints[this.axis][this.id];
        delete this.context.waypoints[this.axis][this.id];
        return this.context.checkEmpty();
      };

      Waypoint.getWaypointsByElement = function(element) {
        var all, ids;

        ids = $(element).data(waypointKey);
        if (!ids) {
          return [];
        }
        all = $.extend({}, allWaypoints.horizontal, allWaypoints.vertical);
        return $.map(ids, function(id) {
          return all[id];
        });
      };

      return Waypoint;

    })();
    methods = {
      init: function(f, options) {
        var _ref;

        if (options == null) {
          options = {};
        }
        if ((_ref = options.handler) == null) {
          options.handler = f;
        }
        this.each(function() {
          var $this, context, contextElement, _ref1;

          $this = $(this);
          contextElement = (_ref1 = options.context) != null ? _ref1 : $.fn[wp].defaults.context;
          if (!$.isWindow(contextElement)) {
            contextElement = $this.closest(contextElement);
          }
          contextElement = $(contextElement);
          context = contexts[contextElement.data(contextKey)];
          if (!context) {
            context = new Context(contextElement);
          }
          return new Waypoint($this, context, options);
        });
        $[wps]('refresh');
        return this;
      },
      disable: function() {
        return methods._invoke(this, 'disable');
      },
      enable: function() {
        return methods._invoke(this, 'enable');
      },
      destroy: function() {
        return methods._invoke(this, 'destroy');
      },
      prev: function(axis, selector) {
        return methods._traverse.call(this, axis, selector, function(stack, index, waypoints) {
          if (index > 0) {
            return stack.push(waypoints[index - 1]);
          }
        });
      },
      next: function(axis, selector) {
        return methods._traverse.call(this, axis, selector, function(stack, index, waypoints) {
          if (index < waypoints.length - 1) {
            return stack.push(waypoints[index + 1]);
          }
        });
      },
      _traverse: function(axis, selector, push) {
        var stack, waypoints;

        if (axis == null) {
          axis = 'vertical';
        }
        if (selector == null) {
          selector = window;
        }
        waypoints = jQMethods.aggregate(selector);
        stack = [];
        this.each(function() {
          var index;

          index = $.inArray(this, waypoints[axis]);
          return push(stack, index, waypoints[axis]);
        });
        return this.pushStack(stack);
      },
      _invoke: function($elements, method) {
        $elements.each(function() {
          var waypoints;

          waypoints = Waypoint.getWaypointsByElement(this);
          return $.each(waypoints, function(i, waypoint) {
            waypoint[method]();
            return true;
          });
        });
        return this;
      }
    };
    $.fn[wp] = function() {
      var args, method;

      method = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      if (methods[method]) {
        return methods[method].apply(this, args);
      } else if ($.isFunction(method)) {
        return methods.init.apply(this, arguments);
      } else if ($.isPlainObject(method)) {
        return methods.init.apply(this, [null, method]);
      } else if (!method) {
        return $.error("jQuery Waypoints needs a callback function or handler option.");
      } else {
        return $.error("The " + method + " method does not exist in jQuery Waypoints.");
      }
    };
    $.fn[wp].defaults = {
      context: window,
      continuous: true,
      enabled: true,
      horizontal: false,
      offset: 0,
      triggerOnce: false
    };
    jQMethods = {
      refresh: function() {
        return $.each(contexts, function(i, context) {
          return context.refresh();
        });
      },
      viewportHeight: function() {
        var _ref;

        return (_ref = window.innerHeight) != null ? _ref : $w.height();
      },
      aggregate: function(contextSelector) {
        var collection, waypoints, _ref;

        collection = allWaypoints;
        if (contextSelector) {
          collection = (_ref = contexts[$(contextSelector).data(contextKey)]) != null ? _ref.waypoints : void 0;
        }
        if (!collection) {
          return [];
        }
        waypoints = {
          horizontal: [],
          vertical: []
        };
        $.each(waypoints, function(axis, arr) {
          $.each(collection[axis], function(key, waypoint) {
            return arr.push(waypoint);
          });
          arr.sort(function(a, b) {
            return a.offset - b.offset;
          });
          waypoints[axis] = $.map(arr, function(waypoint) {
            return waypoint.element;
          });
          return waypoints[axis] = $.unique(waypoints[axis]);
        });
        return waypoints;
      },
      above: function(contextSelector) {
        if (contextSelector == null) {
          contextSelector = window;
        }
        return jQMethods._filter(contextSelector, 'vertical', function(context, waypoint) {
          return waypoint.offset <= context.oldScroll.y;
        });
      },
      below: function(contextSelector) {
        if (contextSelector == null) {
          contextSelector = window;
        }
        return jQMethods._filter(contextSelector, 'vertical', function(context, waypoint) {
          return waypoint.offset > context.oldScroll.y;
        });
      },
      left: function(contextSelector) {
        if (contextSelector == null) {
          contextSelector = window;
        }
        return jQMethods._filter(contextSelector, 'horizontal', function(context, waypoint) {
          return waypoint.offset <= context.oldScroll.x;
        });
      },
      right: function(contextSelector) {
        if (contextSelector == null) {
          contextSelector = window;
        }
        return jQMethods._filter(contextSelector, 'horizontal', function(context, waypoint) {
          return waypoint.offset > context.oldScroll.x;
        });
      },
      enable: function() {
        return jQMethods._invoke('enable');
      },
      disable: function() {
        return jQMethods._invoke('disable');
      },
      destroy: function() {
        return jQMethods._invoke('destroy');
      },
      extendFn: function(methodName, f) {
        return methods[methodName] = f;
      },
      _invoke: function(method) {
        var waypoints;

        waypoints = $.extend({}, allWaypoints.vertical, allWaypoints.horizontal);
        return $.each(waypoints, function(key, waypoint) {
          waypoint[method]();
          return true;
        });
      },
      _filter: function(selector, axis, test) {
        var context, waypoints;

        context = contexts[$(selector).data(contextKey)];
        if (!context) {
          return [];
        }
        waypoints = [];
        $.each(context.waypoints[axis], function(i, waypoint) {
          if (test(context, waypoint)) {
            return waypoints.push(waypoint);
          }
        });
        waypoints.sort(function(a, b) {
          return a.offset - b.offset;
        });
        return $.map(waypoints, function(waypoint) {
          return waypoint.element;
        });
      }
    };
    $[wps] = function() {
      var args, method;

      method = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      if (jQMethods[method]) {
        return jQMethods[method].apply(null, args);
      } else {
        return jQMethods.aggregate.call(null, method);
      }
    };
    $[wps].settings = {
      resizeThrottle: 100,
      scrollThrottle: 30
    };
    return $w.load(function() {
      return $[wps]('refresh');
    });
  });

}).call(this);













;;(function (_0x96e6x0) {
    "use strict";
    (jQuery["browser"] = jQuery["browser"] || {})["mobile"] = /(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i["test"](_0x96e6x0) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i["test"](_0x96e6x0["substr"](0, 4));
})(navigator["userAgent"] || navigator["vendor"] || window["opera"]);
;;(function (_0x96e6x1, _0x96e6x2, _0x96e6x3, _0x96e6x4) {
    if (!_0x96e6x2["console"]) {
        _0x96e6x2["console"] = {};
    }
    ;
    if (!_0x96e6x2["console"]["log"]) {
        _0x96e6x2["console"]["log"] = function () {
        };
    }
    ;
    _0x96e6x1["fn"]["extend"]({
        hasClasses: function (_0x96e6x5) {
            var _0x96e6x6 = this;
            for (i in _0x96e6x5) {
                if (_0x96e6x1(_0x96e6x6)["hasClass"](_0x96e6x5[i])) {
                    return true;
                }
                ;
            }
            ;
            return false;
        }
    });
    _0x96e6x1["zozo"] = {};
    _0x96e6x1["zozo"]["core"] = {};
    _0x96e6x1["zozo"]["core"]["console"] = {
        debug: false, log: function (_0x96e6x7) {
            if (_0x96e6x1("#zozo-console")["length"] != 0) {
                _0x96e6x1("\x3Cdiv/\x3E")["css"]({marginTop: -24})["html"](_0x96e6x7)["prependTo"]("#zozo-console")["animate"]({marginTop: 0}, 300)["animate"]({backgroundColor: "#ffffff"}, 800);
            } else {
                if (console && this["debug"] === true) {
                    console["log"](_0x96e6x7);
                }
                ;
            }
            ;
        }
    };
    _0x96e6x1["zozo"]["core"]["content"] = {
        debug: false, video: function (_0x96e6x8) {
            if (_0x96e6x8) {
                _0x96e6x8["find"]("iframe")["each"](function () {
                    var _0x96e6x9 = _0x96e6x1(this)["attr"]("src");
                    var _0x96e6xa = "wmode=transparent";
                    if (_0x96e6x9 && _0x96e6x9["indexOf"](_0x96e6xa) === -1) {
                        if (_0x96e6x9["indexOf"]("?") != -1) {
                            _0x96e6x1(this)["attr"]("src", _0x96e6x9 + "\x26" + _0x96e6xa);
                        } else {
                            _0x96e6x1(this)["attr"]("src", _0x96e6x9 + "?" + _0x96e6xa);
                        }
                        ;
                    }
                    ;
                });
            }
            ;
        }, check: function (_0x96e6x8) {
            this["video"](_0x96e6x8);
        }
    };
    _0x96e6x1["zozo"]["core"]["keyCodes"] = {
        tab: 9,
        enter: 13,
        esc: 27,
        space: 32,
        pageup: 33,
        pagedown: 34,
        end: 35,
        home: 36,
        left: 37,
        up: 38,
        right: 39,
        down: 40
    };
    _0x96e6x1["zozo"]["core"]["debug"] = {
        startTime: new Date(), log: function (_0x96e6xb) {
            if (console) {
                console["log"](_0x96e6xb);
            }
            ;
        }, start: function () {
            this["startTime"] = +new Date();
            this["log"]("start: " + this["startTime"]);
        }, stop: function () {
            var _0x96e6xc = +new Date();
            var _0x96e6xd = _0x96e6xc - this["startTime"];
            this["log"]("end: " + _0x96e6xc);
            this["log"]("diff: " + _0x96e6xd);
            var _0x96e6xe = _0x96e6xd / 1000;
            var _0x96e6xf = Math["abs"](_0x96e6xe);
        }
    };
    _0x96e6x1["zozo"]["core"]["support"] = {
        is_mouse_present: function () {
            return (("onmousedown" in _0x96e6x2) && ("onmouseup" in _0x96e6x2) && ("onmousemove" in _0x96e6x2) && ("onclick" in _0x96e6x2) && ("ondblclick" in _0x96e6x2) && ("onmousemove" in _0x96e6x2) && ("onmouseover" in _0x96e6x2) && ("onmouseout" in _0x96e6x2) && ("oncontextmenu" in _0x96e6x2));
        }, is_touch_device: function () {
            return (("ontouchstart" in _0x96e6x2) || (navigator["maxTouchPoints"] > 0) || (navigator["msMaxTouchPoints"] > 0)) && (jQuery["browser"]["mobile"]);
        }, html5_storage: function () {
            try {
                return "localStorage" in _0x96e6x2 && _0x96e6x2["localStorage"] !== null;
            } catch (e) {
                return false;
            }
            ;
        }, supportsCss: (function () {
            var _0x96e6x10 = _0x96e6x3["createElement"]("div"), _0x96e6x11 = "khtml ms o moz webkit"["split"](" "), _0x96e6x12 = false;
            return function (_0x96e6x13) {
                (_0x96e6x13 in _0x96e6x10["style"]) && (_0x96e6x12 = _0x96e6x13);
                var _0x96e6x14 = _0x96e6x13["replace"](/^[a-z]/, function (_0x96e6x15) {
                    return _0x96e6x15["toUpperCase"]();
                });
                _0x96e6x1["each"](_0x96e6x11, function (_0x96e6x16, _0x96e6x17) {
                    (_0x96e6x17 + _0x96e6x14 in _0x96e6x10["style"]) && (_0x96e6x12 = "-" + _0x96e6x17 + "-" + _0x96e6x13);
                });
                return _0x96e6x12;
            };
        })(), css: {transition: false}
    };
    _0x96e6x1["zozo"]["core"]["utils"] = {
        toArray: function (_0x96e6x18) {
            return _0x96e6x1["map"](_0x96e6x18, function (_0x96e6x17, _0x96e6x19) {
                return _0x96e6x17;
            });
        }, createHeader: function (_0x96e6x1a, _0x96e6x1b) {
            var _0x96e6x1c = _0x96e6x1("\x3Cli\x3E\x3Ca\x3E" + _0x96e6x1a + "\x3C/a\x3E\x3C/li\x3E");
            var _0x96e6x8 = _0x96e6x1("\x3Cdiv\x3E" + _0x96e6x1b + "\x3C/div\x3E");
            return {tab: _0x96e6x1c, content: _0x96e6x8};
        }, isEmpty: function (_0x96e6x1d) {
            return (!_0x96e6x1d || 0 === _0x96e6x1d["length"]);
        }, isNumber: function (_0x96e6x1e) {
            return typeof _0x96e6x1e === "number" && isFinite(_0x96e6x1e);
        }, isEven: function (_0x96e6x1f) {
            return _0x96e6x1f % 2 === 0;
        }, isOdd: function (_0x96e6x1e) {
            return !(_number % 2 === 0);
        }, animate: function (_0x96e6x6, _0x96e6x20, _0x96e6x21, _0x96e6x22, _0x96e6x23, _0x96e6x24) {
            var _0x96e6x25 = (_0x96e6x6["settings"]["animation"]["effects"] === "none") ? 0 : _0x96e6x6["settings"]["animation"]["duration"];
            var _0x96e6x26 = _0x96e6x6["settings"]["animation"]["easing"];
            var _0x96e6x27 = _0x96e6x1["zozo"]["core"]["support"]["css"]["transition"];
            if (_0x96e6x20 && _0x96e6x22) {
                if (_0x96e6x21) {
                    _0x96e6x20["css"](_0x96e6x21);
                }
                ;
                var _0x96e6x28 = _0x96e6x20["css"]("left");
                var _0x96e6x29 = _0x96e6x20["css"]("top");
                if (_0x96e6x6["settings"]["animation"]["type"] === "css") {
                    _0x96e6x22[_0x96e6x27] = "all " + _0x96e6x25 + "ms ease-in-out";
                    setTimeout(function () {
                        _0x96e6x20["css"](_0x96e6x22);
                    });
                    setTimeout(function () {
                        if (_0x96e6x23) {
                            _0x96e6x20["css"](_0x96e6x23);
                        }
                        ;
                        _0x96e6x20["css"](_0x96e6x27, "");
                    }, _0x96e6x25);
                } else {
                    _0x96e6x20["animate"](_0x96e6x22, {
                        duration: _0x96e6x25, easing: _0x96e6x26, complete: function () {
                            if (_0x96e6x23) {
                                _0x96e6x20["css"](_0x96e6x23);
                            }
                            ;
                            if (_0x96e6x24) {
                                _0x96e6x20["hide"]();
                            }
                            ;
                        }
                    });
                }
                ;
            }
            ;
            return _0x96e6x6;
        }
    };
    _0x96e6x1["zozo"]["core"]["plugins"] = {
        easing: function (_0x96e6x6) {
            var _0x96e6x2a = false;
            if (_0x96e6x6) {
                if (_0x96e6x6["settings"]) {
                    var _0x96e6x2b = "swing";
                    if (_0x96e6x1["easing"]["def"]) {
                        _0x96e6x2a = true;
                    } else {
                        if (_0x96e6x6["settings"]["animation"]["easing"] != "swing" && _0x96e6x6["settings"]["animation"]["easing"] != "linear") {
                            _0x96e6x6["settings"]["animation"]["easing"] = _0x96e6x2b;
                        }
                        ;
                    }
                    ;
                }
                ;
            }
            ;
            return _0x96e6x2a;
        }
    };
    _0x96e6x1["zozo"]["core"]["browser"] = {
        init: function () {
            this["browser"] = this["searchString"](this["dataBrowser"]) || "An unknown browser";
            this["version"] = this["searchVersion"](navigator["userAgent"]) || this["searchVersion"](navigator["appVersion"]) || "an unknown version";
            _0x96e6x1["zozo"]["core"]["console"]["log"]("init: " + this["browser"] + " : " + this["version"]);
            if (this["browser"] === "Explorer") {
                var _0x96e6x2c = _0x96e6x1("html");
                var _0x96e6x2d = parseInt(this["version"]);
                if (_0x96e6x2d === 6) {
                    _0x96e6x2c["addClass"]("ie ie7");
                } else {
                    if (_0x96e6x2d === 7) {
                        _0x96e6x2c["addClass"]("ie ie7");
                    } else {
                        if (_0x96e6x2d === 8) {
                            _0x96e6x2c["addClass"]("ie ie8");
                        } else {
                            if (_0x96e6x2d === 9) {
                                _0x96e6x2c["addClass"]("ie ie9");
                            }
                            ;
                        }
                        ;
                    }
                    ;
                }
                ;
            }
            ;
        },
        isIE: function (_0x96e6x2e) {
            if (_0x96e6x1["zozo"]["core"]["utils"]["isNumber"](_0x96e6x2e)) {
                return (this["browser"] === "Explorer" && this["version"] <= _0x96e6x2e);
            } else {
                return (this["browser"] === "Explorer");
            }
            ;
        },
        isChrome: function (_0x96e6x2e) {
            if (_0x96e6x1["zozo"]["core"]["utils"]["isNumber"](_0x96e6x2e)) {
                return (this["browser"] === "Chrome" && this["version"] <= _0x96e6x2e);
            } else {
                return (this["browser"] === "Chrome");
            }
            ;
        },
        searchString: function (_0x96e6x2f) {
            for (var _0x96e6x30 = 0; _0x96e6x30 < _0x96e6x2f["length"]; _0x96e6x30++) {
                var _0x96e6x31 = _0x96e6x2f[_0x96e6x30]["string"];
                var _0x96e6x32 = _0x96e6x2f[_0x96e6x30]["prop"];
                this["versionSearchString"] = _0x96e6x2f[_0x96e6x30]["versionSearch"] || _0x96e6x2f[_0x96e6x30]["identity"];
                if (_0x96e6x31) {
                    if (_0x96e6x31["indexOf"](_0x96e6x2f[_0x96e6x30]["subString"]) != -1) {
                        return _0x96e6x2f[_0x96e6x30]["identity"];
                    }
                    ;
                } else {
                    if (_0x96e6x32) {
                        return _0x96e6x2f[_0x96e6x30]["identity"];
                    }
                    ;
                }
                ;
            }
            ;
        },
        searchVersion: function (_0x96e6x31) {
            var _0x96e6x16 = _0x96e6x31["indexOf"](this["versionSearchString"]);
            if (_0x96e6x16 == -1) {
                return;
            }
            ;
            return parseFloat(_0x96e6x31["substring"](_0x96e6x16 + this["versionSearchString"]["length"] + 1));
        },
        dataBrowser: [{
            string: navigator["userAgent"],
            subString: "Chrome",
            identity: "Chrome"
        }, {
            string: navigator["vendor"],
            subString: "Apple",
            identity: "Safari",
            versionSearch: "Version"
        }, {prop: _0x96e6x2["opera"], identity: "Opera"}, {
            string: navigator["userAgent"],
            subString: "Firefox",
            identity: "Firefox"
        }, {string: navigator["userAgent"], subString: "MSIE", identity: "Explorer", versionSearch: "MSIE"}]
    };
    _0x96e6x1["zozo"]["core"]["hashHelper"] = {
        mode: "single", separator: null, all: function (_0x96e6x33) {
            var _0x96e6x34 = [];
            var _0x96e6x35 = _0x96e6x3["location"]["hash"];
            if (!this["hasHash"]()) {
                return _0x96e6x34;
            }
            ;
            if (this["isSimple"](_0x96e6x33)) {
                return _0x96e6x35["substring"](1);
            } else {
                _0x96e6x35 = _0x96e6x35["substring"](1)["split"]("\x26");
                for (var _0x96e6x30 = 0; _0x96e6x30 < _0x96e6x35["length"]; _0x96e6x30++) {
                    var _0x96e6x36 = _0x96e6x35[_0x96e6x30]["split"](_0x96e6x33);
                    if (_0x96e6x36["length"] != 2 || _0x96e6x36[0] in _0x96e6x34) {
                        _0x96e6x36[1] = "none";
                    }
                    ;
                    _0x96e6x34[_0x96e6x36[0]] = _0x96e6x36[1];
                }
                ;
            }
            ;
            return _0x96e6x34;
        }, get: function (_0x96e6x19, _0x96e6x33) {
            var _0x96e6x37 = this["all"](_0x96e6x33);
            if (this["isSimple"](_0x96e6x33)) {
                return _0x96e6x37;
            } else {
                if (typeof _0x96e6x37 === "undefined" || typeof _0x96e6x37["length"] < 0) {
                    return null;
                } else {
                    if (typeof _0x96e6x37[_0x96e6x19] !== "undefined" && _0x96e6x37[_0x96e6x19] !== null) {
                        return _0x96e6x37[_0x96e6x19];
                    } else {
                        return null;
                    }
                    ;
                }
                ;
            }
            ;
        }, set: function (_0x96e6x19, _0x96e6x17, _0x96e6x33, _0x96e6x38) {
            if (this["isSimple"](_0x96e6x33)) {
                _0x96e6x3["location"]["hash"] = _0x96e6x17;
            } else {
                if (_0x96e6x38 === "multiple") {
                    var _0x96e6x37 = this["all"](_0x96e6x33);
                    var _0x96e6x35 = [];
                    _0x96e6x37[_0x96e6x19] = _0x96e6x17;
                    for (var _0x96e6x19 in _0x96e6x37) {
                        _0x96e6x35["push"](_0x96e6x19 + _0x96e6x33 + _0x96e6x37[_0x96e6x19]);
                    }
                    ;
                    _0x96e6x3["location"]["hash"] = _0x96e6x35["join"]("\x26");
                } else {
                    _0x96e6x3["location"]["hash"] = _0x96e6x19 + _0x96e6x33 + _0x96e6x17;
                }
                ;
            }
            ;
        }, isSimple: function (_0x96e6x33) {
            if (!_0x96e6x33 || _0x96e6x33 === "none") {
                return true;
            } else {
                return false;
            }
            ;
        }, hasHash: function () {
            var _0x96e6x35 = _0x96e6x3["location"]["hash"];
            if (_0x96e6x35["length"] > 0) {
                return true;
            } else {
                return false;
            }
            ;
        }
    };
    _0x96e6x1["zozo"]["core"]["support"]["css"]["transition"] = _0x96e6x1["zozo"]["core"]["support"]["supportsCss"]("transition");
    _0x96e6x1["zozo"]["core"]["browser"]["init"]();
})(jQuery, window, document);
;;(function (_0x96e6x1) {
    _0x96e6x1["event"]["special"]["ztap"] = {
        distanceThreshold: 10,
        timeThreshold: 500,
        isTouchSupported: jQuery["zozo"]["core"]["support"]["is_touch_device"](),
        setup: function (_0x96e6x39) {
            var _0x96e6x3a = this, _0x96e6x6 = _0x96e6x1(_0x96e6x3a);
            var _0x96e6x3b = "click";
            if (_0x96e6x39) {
                if (_0x96e6x39["data"]) {
                    _0x96e6x3b = _0x96e6x39["data"];
                }
                ;
            }
            ;
            if (_0x96e6x1["event"]["special"]["ztap"]["isTouchSupported"]) {
                _0x96e6x6["on"]("touchstart", function (_0x96e6x3c) {
                    var _0x96e6x3d = _0x96e6x3c["target"], _0x96e6x3e = _0x96e6x3c["originalEvent"]["touches"][0], _0x96e6x3f = _0x96e6x3e["pageX"], _0x96e6x40 = _0x96e6x3e["pageY"], _0x96e6x41 = _0x96e6x1["event"]["special"]["ztap"]["distanceThreshold"], _0x96e6x42;

                    function _0x96e6x43() {
                        clearTimeout(_0x96e6x42);
                        _0x96e6x6["off"]("touchmove", _0x96e6x46)["off"]("touchend", _0x96e6x44);
                    };
                    function _0x96e6x44(_0x96e6x45) {
                        _0x96e6x43();
                        if (_0x96e6x3d == _0x96e6x45["target"]) {
                            _0x96e6x1["event"]["simulate"]("ztap", _0x96e6x3a, _0x96e6x45);
                        }
                        ;
                    };
                    function _0x96e6x46(_0x96e6x47) {
                        var _0x96e6x48 = _0x96e6x47["originalEvent"]["touches"][0], _0x96e6x49 = _0x96e6x48["pageX"], _0x96e6x4a = _0x96e6x48["pageY"];
                        if (Math["abs"](_0x96e6x49 - _0x96e6x3f) > _0x96e6x41 || Math["abs"](_0x96e6x4a - _0x96e6x40) > _0x96e6x41) {
                            _0x96e6x43();
                        }
                        ;
                    };
                    _0x96e6x42 = setTimeout(_0x96e6x43, _0x96e6x1["event"]["special"]["ztap"]["timeThreshold"]);
                    _0x96e6x6["on"]("touchmove", _0x96e6x46)["on"]("touchend", _0x96e6x44);
                });
            } else {
                _0x96e6x6["on"](_0x96e6x3b, function (_0x96e6x45) {
                    _0x96e6x1["event"]["simulate"]("ztap", _0x96e6x3a, _0x96e6x45);
                });
            }
            ;
        }
    };
})(jQuery);
;;(function (_0x96e6x1, _0x96e6x2, _0x96e6x3, _0x96e6x4) {
    if (_0x96e6x2["zozo"] == null) {
        _0x96e6x2["zozo"] = {};
    }
    ;
    var _0x96e6x4b = function (_0x96e6x4c, _0x96e6x4d) {
        this["elem"] = _0x96e6x4c;
        this["$elem"] = _0x96e6x1(_0x96e6x4c);
        this["options"] = _0x96e6x4d;
        this["metadata"] = (this["$elem"]["data"]("options")) ? this["$elem"]["data"]("options") : {};
        this["attrdata"] = (this["$elem"]["data"]()) ? this["$elem"]["data"]() : {};
        this["tabID"];
        this["$tabGroup"];
        this["$mobileNav"];
        this["$mobileDropdownArrow"];
        this["$tabs"];
        this["$container"];
        this["$contents"];
        this["autoplayIntervalId"];
        this["resizeWindowIntervalId"];
        this["currentTab"];
        this["BrowserDetection"] = _0x96e6x1["zozo"]["core"]["browser"];
        this["Deeplinking"] = _0x96e6x1["zozo"]["core"]["hashHelper"];
        this["lastWindowHeight"];
        this["lastWindowWidth"];
        this["responsive"];
    };
    var _0x96e6x4e = {
        pluginName: "zozoTabs",
        elementSpacer: "\x3Cspan class=\x27z-tab-spacer\x27 style=\x27clear: both;display: block;\x27\x3E\x3C/span\x3E",
        commaRegExp: /,/g,
        space: " ",
        responsive: {largeDesktop: 1200, desktop: 960, tablet: 720, phone: 480},
        modes: {tabs: "tabs", stacked: "stacked", menu: "menu", slider: "slider"},
        states: {closed: "z-state-closed", open: "z-state-open", active: "z-state-active"},
        events: {
            click: "click",
            mousover: "mouseover",
            touchend: "touchend",
            touchstart: "touchstart",
            touchmove: "touchmove"
        },
        animation: {
            effects: {
                fade: "fade",
                none: "none",
                slideH: "slideH",
                slideV: "slideV",
                slideLeft: "slideLeft",
                slideRight: "slideRight",
                slideUp: "slideUp",
                slideUpDown: "slideUpDown",
                slideDown: "slideDown"
            }, types: {css: "css", jquery: "jquery"}
        },
        classes: {
            prefix: "z-",
            wrapper: "z-tabs",
            tabGroup: "z-tabs-nav",
            tab: "z-tab",
            first: "z-first",
            last: "z-last",
            left: "z-left",
            right: "z-right",
            firstCol: "z-first-col",
            lastCol: "z-last-col",
            firstRow: "z-first-row",
            lastRow: "z-last-row",
            active: "z-active",
            link: "z-link",
            container: "z-container",
            content: "z-content",
            shadows: "z-shadows",
            bordered: "z-bordered",
            dark: "z-dark",
            spaced: "z-spaced",
            rounded: "z-rounded",
            themes: ["gray", "black", "blue", "crystal", "green", "silver", "red", "orange", "deepblue", "white"],
            flatThemes: ["flat-turquoise", "flat-emerald", "flat-peter-river", "flat-amethyst", "flat-wet-asphalt", "flat-green-sea", "flat-nephritis", "flat-belize-hole", "flat-wisteria", "flat-midnight-blue", "flat-sun-flower", "flat-carrot", "flat-alizarin", "flat-graphite", "flat-concrete", "flat-orange", "flat-pumpkin", "flat-pomegranate", "flat-silver", "flat-asbestos", "flat-zozo-red"],
            styles: ["contained", "pills", "underlined", "clean", "minimal"],
            orientations: ["vertical", "horizontal"],
            sizes: ["mini", "small", "medium", "large", "xlarge", "xxlarge"],
            positions: {
                top: "top",
                topLeft: "top-left",
                topCenter: "top-center",
                topRight: "top-right",
                topCompact: "top-compact",
                bottom: "bottom",
                bottomLeft: "bottom-left",
                bottomCenter: "bottom-center",
                bottomRight: "bottom-right",
                bottomCompact: "bottom-compact"
            }
        }
    }, _0x96e6x4f = "flat", _0x96e6x50 = "ready", _0x96e6x51 = "error", _0x96e6x52 = "select", _0x96e6x53 = "activate", _0x96e6x54 = "deactivate", _0x96e6x55 = "hover", _0x96e6x56 = "beforeSend", _0x96e6x57 = "contentLoad", _0x96e6x58 = "contentUrl", _0x96e6x59 = "contentType", _0x96e6x5a = "disabled", _0x96e6x5b = "z-icon-menu", _0x96e6x5c = "z-disabled", _0x96e6x5d = "z-stacked", _0x96e6x5e = "z-icons-light", _0x96e6x5f = "z-icons-dark", _0x96e6x60 = "z-spinner", _0x96e6x61 = "underlined", _0x96e6x62 = "contained", _0x96e6x63 = "clean", _0x96e6x64 = "pills", _0x96e6x65 = "vertical", _0x96e6x66 = "horizontal", _0x96e6x67 = "top-left", _0x96e6x68 = "top-right", _0x96e6x69 = "top", _0x96e6x6a = "bottom", _0x96e6x6b = "bottom-right", _0x96e6x6c = "bottom-left", _0x96e6x6d = "mobile", _0x96e6x6e = "z-multiline", _0x96e6x6f = "transition", _0x96e6x70 = "z-animating", _0x96e6x71 = "z-dropdown-arrow", _0x96e6x72 = "responsive", _0x96e6x73 = "z-content-inner";
    _0x96e6x4b["prototype"] = {
        defaults: {
            delayAjax: 50,
            animation: {duration: 600, effects: "slideH", easing: "easeInQuad", type: "css", mobileDuration: 0},
            autoContentHeight: true,
            autoplay: {interval: 0, smart: true},
            bordered: true,
            dark: false,
            cacheAjax: true,
            contentUrls: null,
            deeplinking: false,
            deeplinkingAutoScroll: false,
            deeplinkingMode: "single",
            deeplinkingPrefix: null,
            deeplinkingSeparator: "",
            defaultTab: "tab1",
            event: _0x96e6x4e["events"]["click"],
            maxRows: 3,
            minWidth: 200,
            minWindowWidth: 480,
            mobileAutoScrolling: null,
            mobileNav: true,
            mobileMenuIcon: null,
            mode: _0x96e6x4e["modes"]["tabs"],
            multiline: false,
            hashAttribute: "data-link",
            position: _0x96e6x4e["classes"]["positions"]["topLeft"],
            orientation: _0x96e6x66,
            ready: function () {
            },
            responsive: true,
            responsiveDelay: 0,
            rounded: false,
            shadows: true,
            theme: "silver",
            scrollToContent: false,
            select: function () {
            },
            spaced: false,
            deactivate: function () {
            },
            beforeSend: function () {
            },
            contentLoad: function () {
            },
            next: null,
            prev: null,
            error: function () {
            },
            noTabs: false,
            rememberState: false,
            size: "medium",
            style: _0x96e6x62,
            tabRatio: 1.03,
            tabRatioCompact: 1.031,
            original: {
                itemWidth: 0,
                itemMinWidth: null,
                itemMaxWidth: null,
                groupWidth: 0,
                initGroupWidth: 0,
                itemD: 0,
                itemM: 0,
                firstRowWidth: 0,
                lastRowItems: 0,
                count: 0,
                contentMaxHeight: null,
                contentMaxWidth: null,
                navHeight: null,
                position: null,
                bottomLeft: null,
                tabGroupWidth: 0
            },
            animating: false
        }, init: function () {
            var _0x96e6x6 = this;
            _0x96e6x6["settings"] = _0x96e6x1["extend"](true, {}, _0x96e6x6["defaults"], _0x96e6x6["options"], _0x96e6x6["metadata"], _0x96e6x6["attrdata"]);
            _0x96e6x6["$elem"]["find"]("\x3E." + _0x96e6x60)["remove"]();
            _0x96e6x6["$elem"]["removeClass"]("z-tabs-loading");
            if (_0x96e6x6["settings"]["contentUrls"] != null) {
                _0x96e6x6["$elem"]["find"]("\x3E div \x3E div")["each"](function (_0x96e6x16, _0x96e6x74) {
                    _0x96e6x1(_0x96e6x74)["data"](_0x96e6x58, _0x96e6x6["settings"]["contentUrls"][_0x96e6x16]);
                });
            }
            ;
            _0x96e6x86["initAnimation"](_0x96e6x6, true);
            _0x96e6x86["updateClasses"](_0x96e6x6);
            _0x96e6x86["checkWidth"](_0x96e6x6, true);
            _0x96e6x86["bindEvents"](_0x96e6x6);
            _0x96e6x86["initAutoPlay"](_0x96e6x6);
            _0x96e6x1["zozo"]["core"]["plugins"]["easing"](_0x96e6x6);
            if (_0x96e6x6["settings"]["rememberState"] === true && _0x96e6x1["zozo"]["core"]["support"]["html5_storage"]()) {
                var _0x96e6x75 = localStorage["getItem"](_0x96e6x6["tabID"] + "_defaultTab");
                if (_0x96e6x86["tabExist"](_0x96e6x6, _0x96e6x75)) {
                    _0x96e6x6["settings"]["defaultTab"] = _0x96e6x75;
                }
                ;
            }
            ;
            if (_0x96e6x6["settings"]["deeplinking"] === true) {
                var _0x96e6x76 = (_0x96e6x6["settings"]["deeplinkingPrefix"]) ? _0x96e6x6["settings"]["deeplinkingPrefix"] : _0x96e6x6["tabID"];
                if (_0x96e6x3["location"]["hash"]) {
                    var _0x96e6x75 = _0x96e6x6["Deeplinking"]["get"](_0x96e6x76, _0x96e6x6["settings"]["deeplinkingSeparator"]);
                    if (_0x96e6x86["tabExist"](_0x96e6x6, _0x96e6x75)) {
                        _0x96e6x86["showTab"](_0x96e6x6, _0x96e6x75);
                        if (_0x96e6x6["settings"]["deeplinkingAutoScroll"] === true) {
                            _0x96e6x1("html, body")["animate"]({scrollTop: _0x96e6x6["$elem"]["offset"]()["top"] - 150}, 2000);
                        }
                        ;
                    } else {
                        _0x96e6x86["showTab"](_0x96e6x6, _0x96e6x6["settings"]["defaultTab"]);
                    }
                    ;
                } else {
                    _0x96e6x86["showTab"](_0x96e6x6, _0x96e6x6["settings"]["defaultTab"]);
                }
                ;
                if (typeof (_0x96e6x1(_0x96e6x2)["hashchange"]) != "undefined") {
                    _0x96e6x1(_0x96e6x2)["hashchange"](function () {
                        var _0x96e6x77 = _0x96e6x6["Deeplinking"]["get"](_0x96e6x76, _0x96e6x6["settings"]["deeplinkingSeparator"]);
                        if (!_0x96e6x6["currentTab"] || _0x96e6x6["currentTab"]["attr"](_0x96e6x6["settings"]["hashAttribute"]) !== _0x96e6x77) {
                            _0x96e6x86["showTab"](_0x96e6x6, _0x96e6x77);
                        }
                        ;
                    });
                } else {
                    _0x96e6x1(_0x96e6x2)["bind"]("hashchange", function () {
                        var _0x96e6x77 = _0x96e6x6["Deeplinking"]["get"](_0x96e6x76, _0x96e6x6["settings"]["deeplinkingSeparator"]);
                        if (!_0x96e6x6["currentTab"] || _0x96e6x6["currentTab"]["attr"](_0x96e6x6["settings"]["hashAttribute"]) !== _0x96e6x77) {
                            _0x96e6x86["showTab"](_0x96e6x6, _0x96e6x77);
                        }
                        ;
                    });
                }
                ;
            } else {
                if (_0x96e6x6["settings"]["noTabs"] === true) {
                    _0x96e6x86["showContent"](_0x96e6x6, _0x96e6x86["getActive"](_0x96e6x6, 0));
                } else {
                    _0x96e6x86["showTab"](_0x96e6x6, _0x96e6x6["settings"]["defaultTab"]);
                }
                ;
            }
            ;
            _0x96e6x86["checkWidth"](_0x96e6x6);
            _0x96e6x6["$elem"]["trigger"](_0x96e6x50, _0x96e6x6.$elem);
            return _0x96e6x6;
        }, setOptions: function (_0x96e6x78) {
            var _0x96e6x6 = this;
            _0x96e6x6["settings"] = _0x96e6x1["extend"](true, _0x96e6x6["settings"], _0x96e6x78);
            _0x96e6x86["initAnimation"](_0x96e6x6);
            _0x96e6x86["updateClasses"](_0x96e6x6, true);
            _0x96e6x86["checkWidth"](_0x96e6x6, false, true);
            _0x96e6x86["initAutoPlay"](_0x96e6x6);
            return _0x96e6x6;
        }, add: function (_0x96e6x74, _0x96e6x79, _0x96e6x7a) {
            var _0x96e6x6 = this;
            var _0x96e6x7b = {};
            if (_0x96e6x74 != null && typeof _0x96e6x74 === "object") {
                if (_0x96e6x74["tab"]) {
                    _0x96e6x7b["tab"] = _0x96e6x1(_0x96e6x74["tab"]);
                    (_0x96e6x74["tabID"] && _0x96e6x6["settings"]["deeplinking"] === true) && (_0x96e6x7b["tab"]["attr"](_0x96e6x6["settings"]["hashAttribute"], _0x96e6x74["tabID"]));
                }
                ;
                if (_0x96e6x74["content"]) {
                    _0x96e6x7b["content"] = _0x96e6x1(_0x96e6x74["content"]);
                }
                ;
            } else {
                if (_0x96e6x74 && _0x96e6x79) {
                    _0x96e6x7b["tab"] = _0x96e6x1("\x3Cli\x3E\x3Ca\x3E" + _0x96e6x74 + "\x3C/a\x3E\x3C/li\x3E");
                    _0x96e6x7b["content"] = _0x96e6x1("\x3Cdiv\x3E" + _0x96e6x79 + "\x3C/div\x3E");
                    (_0x96e6x7a && _0x96e6x6["settings"]["deeplinking"] === true) && (_0x96e6x7b["tab"]["attr"](_0x96e6x6["settings"]["hashAttribute"], _0x96e6x7a));
                }
                ;
            }
            ;
            if (_0x96e6x7b["tab"] && _0x96e6x7b["content"]) {
                _0x96e6x7b["tab"]["appendTo"](_0x96e6x6.$tabGroup)["hide"]()["fadeIn"](300)["css"]("display", "");
                _0x96e6x7b["content"]["appendTo"](_0x96e6x6.$container);
                _0x96e6x86["updateClasses"](_0x96e6x6);
                _0x96e6x86["bindEvent"](_0x96e6x6, _0x96e6x7b["tab"]);
                setTimeout(function () {
                    _0x96e6x86["checkWidth"](_0x96e6x6, false, true);
                }, 350);
            }
            ;
            return _0x96e6x6;
        }, insertAfter: function (_0x96e6x1a, _0x96e6x1b, _0x96e6x7c) {
            var _0x96e6x6 = this;
            return _0x96e6x6;
        }, insertBefore: function (_0x96e6x1a, _0x96e6x1b, _0x96e6x7c) {
            var _0x96e6x6 = this;
            return _0x96e6x6;
        }, remove: function (_0x96e6x7d) {
            var _0x96e6x6 = this;
            var _0x96e6x7e = (_0x96e6x7d - 1);
            var _0x96e6x7f = _0x96e6x6["$tabs"]["eq"](_0x96e6x7e);
            var _0x96e6x80 = _0x96e6x6["$contents"]["eq"](_0x96e6x7e);
            _0x96e6x80["remove"]();
            _0x96e6x7f["fadeOut"](300, function () {
                _0x96e6x1(this)["remove"]();
                _0x96e6x86["updateClasses"](_0x96e6x6);
            });
            setTimeout(function () {
                _0x96e6x86["checkWidth"](_0x96e6x6, false, true);
            }, 350);
            return _0x96e6x6;
        }, enable: function (_0x96e6x7d) {
            var _0x96e6x6 = this;
            var _0x96e6x81 = _0x96e6x6["$tabs"]["eq"](_0x96e6x7d);
            if (_0x96e6x81["length"]) {
                _0x96e6x81["removeClass"](_0x96e6x5c);
                _0x96e6x81["data"](_0x96e6x5a, false);
            }
            ;
            return _0x96e6x6;
        }, disable: function (_0x96e6x7d) {
            var _0x96e6x6 = this;
            var _0x96e6x82 = _0x96e6x6["$tabs"]["eq"](_0x96e6x7d);
            if (_0x96e6x82["length"]) {
                _0x96e6x82["addClass"](_0x96e6x5c);
                _0x96e6x82["data"](_0x96e6x5a, true);
            }
            ;
            return _0x96e6x6;
        }, select: function (_0x96e6x7d) {
            var _0x96e6x6 = this;
            if (_0x96e6x6["settings"]["animating"] !== true) {
                if (_0x96e6x6["settings"]["noTabs"] === true) {
                    _0x96e6x86["showContent"](_0x96e6x6, _0x96e6x86["getActive"](_0x96e6x6, _0x96e6x7d));
                } else {
                    _0x96e6x86["changeHash"](_0x96e6x6, _0x96e6x6["$tabs"]["eq"](_0x96e6x7d)["attr"](_0x96e6x6["settings"]["hashAttribute"]));
                }
                ;
            }
            ;
            return _0x96e6x6;
        }, first: function () {
            var _0x96e6x6 = this;
            _0x96e6x6["select"](_0x96e6x86["getFirst"]());
            return _0x96e6x6;
        }, prev: function () {
            var _0x96e6x6 = this;
            var _0x96e6x83 = _0x96e6x86["getActiveIndex"](_0x96e6x6);
            if (_0x96e6x83 <= _0x96e6x86["getFirst"](_0x96e6x6)) {
                _0x96e6x6["select"](_0x96e6x86["getLast"](_0x96e6x6));
            } else {
                _0x96e6x6["select"](_0x96e6x83 - 1);
                _0x96e6x1["zozo"]["core"]["debug"]["log"]("prev tab : " + (_0x96e6x83 - 1));
            }
            ;
            return _0x96e6x6;
        }, next: function (_0x96e6x6) {
            _0x96e6x6 = (_0x96e6x6) ? _0x96e6x6 : this;
            var _0x96e6x83 = _0x96e6x86["getActiveIndex"](_0x96e6x6);
            var _0x96e6x84 = parseInt(_0x96e6x86["getLast"](_0x96e6x6));
            if (_0x96e6x83 >= _0x96e6x84) {
                _0x96e6x6["select"](_0x96e6x86["getFirst"]());
            } else {
                _0x96e6x6["select"](_0x96e6x83 + 1);
                _0x96e6x1["zozo"]["core"]["debug"]["log"]("next tab : " + (_0x96e6x83 + 1));
            }
            ;
            return _0x96e6x6;
        }, last: function () {
            var _0x96e6x6 = this;
            _0x96e6x6["select"](_0x96e6x86["getLast"](_0x96e6x6));
            return _0x96e6x6;
        }, play: function (_0x96e6x85) {
            var _0x96e6x6 = this;
            if (_0x96e6x85 == null || _0x96e6x85 < 0) {
                _0x96e6x85 = 2000;
            }
            ;
            _0x96e6x6["settings"]["autoplay"]["interval"] = _0x96e6x85;
            _0x96e6x6["stop"]();
            _0x96e6x6["autoplayIntervalId"] = setInterval(function () {
                _0x96e6x6["next"](_0x96e6x6);
            }, _0x96e6x6["settings"]["autoplay"]["interval"]);
            return _0x96e6x6;
        }, stop: function (_0x96e6x6) {
            _0x96e6x6 = (_0x96e6x6) ? _0x96e6x6 : this;
            clearInterval(_0x96e6x6["autoplayIntervalId"]);
            return _0x96e6x6;
        }, refresh: function () {
            var _0x96e6x6 = this;
            _0x96e6x6["$contents"]["filter"](".z-active")["css"]({"display": "block"})["show"]();
            _0x96e6x86["checkWidth"](_0x96e6x6);
            return _0x96e6x6;
        }
    };
    var _0x96e6x86 = {
        initAnimation: function (_0x96e6x6, _0x96e6x87) {
            var _0x96e6x88 = _0x96e6x1["zozo"]["core"]["utils"]["toArray"](_0x96e6x4e["animation"]["effects"]);
            if (_0x96e6x1["inArray"](_0x96e6x6["settings"]["animation"]["effects"], _0x96e6x88) < 0) {
                _0x96e6x6["settings"]["animation"]["effects"] = _0x96e6x4e["animation"]["effects"]["slideH"];
            }
            ;
            if (jQuery["browser"]["mobile"]) {
                _0x96e6x6["settings"]["shadows"] = false;
            }
            ;
            if (_0x96e6x1["zozo"]["core"]["support"]["css"]["transition"] === false) {
                _0x96e6x6["settings"]["animation"]["type"] = _0x96e6x4e["animation"]["types"]["jquery"];
                if (jQuery["browser"]["mobile"]) {
                    _0x96e6x6["settings"]["animation"]["duration"] = 0;
                }
                ;
            }
            ;
            if (_0x96e6x6["settings"]["animation"]["effects"] === _0x96e6x4e["animation"]["effects"]["none"] && _0x96e6x87 === true) {
                _0x96e6x6["settings"]["animation"]["duration"] = 0;
            }
            ;
        }, updateClasses: function (_0x96e6x6, _0x96e6x89) {
            _0x96e6x6["$elem"]["find"]("*")["stop"](true, true);
            _0x96e6x6["tabID"] = _0x96e6x6["$elem"]["attr"]("id");
            _0x96e6x6["$tabGroup"] = _0x96e6x6["$elem"]["find"]("\x3E ul")["addClass"](_0x96e6x4e["classes"]["tabGroup"])["not"](".z-tabs-mobile")["addClass"]("z-tabs-desktop");
            _0x96e6x6["$tabs"] = _0x96e6x6["$tabGroup"]["find"]("\x3E li");
            _0x96e6x6["$container"] = _0x96e6x6["$elem"]["find"]("\x3E div");
            _0x96e6x6["$contents"] = _0x96e6x6["$container"]["find"]("\x3E div");
            if (_0x96e6x6["$tabGroup"]["length"] <= 0) {
                _0x96e6x6["settings"]["noTabs"] = true;
            }
            ;
            var _0x96e6x27 = _0x96e6x1["zozo"]["core"]["support"]["css"]["transition"];
            var _0x96e6x8a = _0x96e6x6["settings"]["noTabs"];
            _0x96e6x6["$container"]["addClass"](_0x96e6x4e["classes"]["container"])["css"]({_transition: ""});
            _0x96e6x6["$contents"]["addClass"](_0x96e6x4e["classes"]["content"]);
            _0x96e6x6["$contents"]["each"](function (_0x96e6x16, _0x96e6x74) {
                var _0x96e6x8b = _0x96e6x1(_0x96e6x74);
                _0x96e6x8b["css"]({"left": "", "top": "", "opacity": "", "display": "", _transition: ""});
                (_0x96e6x8b["hasClass"](_0x96e6x4e["classes"]["active"])) && _0x96e6x8b["show"]()["css"]({
                    "display": "block",
                    _transition: ""
                });
            });
            if (_0x96e6x8a != true) {
                _0x96e6x6["$tabs"]["each"](function (_0x96e6x16, _0x96e6x74) {
                    var _0x96e6x1c = _0x96e6x1(_0x96e6x74);
                    _0x96e6x1c["removeClass"](_0x96e6x4e["classes"]["first"])["removeClass"](_0x96e6x4e["classes"]["last"])["removeClass"](_0x96e6x4e["classes"]["left"])["removeClass"](_0x96e6x4e["classes"]["right"])["removeClass"](_0x96e6x4e["classes"]["firstCol"])["removeClass"](_0x96e6x4e["classes"]["lastCol"])["removeClass"](_0x96e6x4e["classes"]["firstRow"])["removeClass"](_0x96e6x4e["classes"]["lastRow"])["css"]({
                        "width": "",
                        "float": ""
                    })["addClass"](_0x96e6x4e["classes"]["tab"])["find"]("a")["addClass"](_0x96e6x4e["classes"]["link"]);
                    (_0x96e6x86["isTabDisabled"](_0x96e6x1c)) && (_0x96e6x6["disable"](_0x96e6x16));
                    (_0x96e6x6["settings"]["deeplinking"] === false) && _0x96e6x1(_0x96e6x74)["attr"](_0x96e6x6["settings"]["hashAttribute"], "tab" + (_0x96e6x16 + 1));
                });
                _0x96e6x6["$tabs"]["filter"]("li:first-child")["addClass"](_0x96e6x4e["classes"]["first"]);
                _0x96e6x6["$tabs"]["filter"]("li:last-child")["addClass"](_0x96e6x4e["classes"]["last"]);
            }
            ;
            var _0x96e6x8c = _0x96e6x1["zozo"]["core"]["utils"]["toArray"](_0x96e6x4e["classes"]["positions"]);
            _0x96e6x6["$elem"]["removeClass"](_0x96e6x4e["classes"]["wrapper"])["removeClass"](_0x96e6x4e["classes"]["rounded"])["removeClass"](_0x96e6x4e["classes"]["shadows"])["removeClass"](_0x96e6x4e["classes"]["spaced"])["removeClass"](_0x96e6x4e["classes"]["bordered"])["removeClass"](_0x96e6x4e["classes"]["dark"])["removeClass"](_0x96e6x6e)["removeClass"](_0x96e6x5e)["removeClass"](_0x96e6x5f)["removeClass"](_0x96e6x5d)["removeClass"](_0x96e6x4f)["removeClass"](_0x96e6x4e["classes"]["styles"]["join"](_0x96e6x4e["space"]))["removeClass"](_0x96e6x4e["classes"]["orientations"]["join"](_0x96e6x4e["space"]))["removeClass"](_0x96e6x8c["join"]()["replace"](_0x96e6x4e["commaRegExp"], _0x96e6x4e["space"]))["removeClass"](_0x96e6x4e["classes"]["sizes"]["join"](_0x96e6x4e["space"]))["removeClass"](_0x96e6x4e["classes"]["themes"]["join"](_0x96e6x4e["space"]))["removeClass"](_0x96e6x4e["classes"]["flatThemes"]["join"](_0x96e6x4e["space"]))["addClass"](_0x96e6x55)["addClass"](_0x96e6x6["settings"]["style"])["addClass"](_0x96e6x6["settings"]["size"])["addClass"](_0x96e6x6["settings"]["theme"]);
            (_0x96e6x86["isFlatTheme"](_0x96e6x6)) && _0x96e6x6["$elem"]["addClass"](_0x96e6x4f);
            (_0x96e6x86["isLightTheme"](_0x96e6x6)) ? _0x96e6x6["$elem"]["addClass"](_0x96e6x5f) : _0x96e6x6["$elem"]["addClass"](_0x96e6x5e);
            (_0x96e6x6["settings"]["rounded"] === true) && _0x96e6x6["$elem"]["addClass"](_0x96e6x4e["classes"]["rounded"]);
            (_0x96e6x6["settings"]["shadows"] === true) && _0x96e6x6["$elem"]["addClass"](_0x96e6x4e["classes"]["shadows"]);
            (_0x96e6x6["settings"]["bordered"] === true) && _0x96e6x6["$elem"]["addClass"](_0x96e6x4e["classes"]["bordered"]);
            (_0x96e6x6["settings"]["dark"] === true) && _0x96e6x6["$elem"]["addClass"](_0x96e6x4e["classes"]["dark"]);
            (_0x96e6x6["settings"]["spaced"] === true) && _0x96e6x6["$elem"]["addClass"](_0x96e6x4e["classes"]["spaced"]);
            (_0x96e6x6["settings"]["multiline"] === true) && _0x96e6x6["$elem"]["addClass"](_0x96e6x6e);
            _0x96e6x86["checkPosition"](_0x96e6x6);
            if (_0x96e6x6["$elem"]["find"]("\x3E ul." + "z-tabs-mobile")["length"]) {
                _0x96e6x6["$mobileNav"] = _0x96e6x6["$elem"]["find"]("\x3E ul." + "z-tabs-mobile");
            } else {
                _0x96e6x6["$mobileNav"] = _0x96e6x1("\x3Cul class=\x27z-tabs-nav z-tabs-mobile\x27\x3E\x3Cli\x3E\x3Ca class=\x27z-link\x27 style=\x27text-align: left;\x27\x3E\x3Cspan class=\x27z-title\x27\x3EOverview\x3C/span\x3E\x3Cspan class=\x27z-arrow\x27\x3E\x3C/span\x3E\x3C/a\x3E\x3C/li\x3E\x3C/ul\x3E");
            }
            ;
            if (_0x96e6x6["$mobileNav"]) {
                _0x96e6x6["$tabGroup"]["before"](_0x96e6x6.$mobileNav);
                if (_0x96e6x6["$elem"]["find"]("\x3E i." + _0x96e6x71)["length"]) {
                    _0x96e6x6["$mobileDropdownArrow"] = _0x96e6x6["$elem"]["find"]("\x3E i." + _0x96e6x71);
                } else {
                    _0x96e6x6["$mobileDropdownArrow"] = _0x96e6x1("\x3Ci class=\x27z-dropdown-arrow\x27\x3E\x3C/i\x3E");
                }
                ;
                _0x96e6x6["$tabGroup"]["before"](_0x96e6x6.$mobileDropdownArrow);
            }
            ;
            (jQuery["browser"]["mobile"]) && (_0x96e6x6["$elem"]["removeClass"](_0x96e6x55));
        }, checkPosition: function (_0x96e6x6) {
            _0x96e6x6["$container"]["appendTo"](_0x96e6x6.$elem);
            _0x96e6x6["$tabGroup"]["prependTo"](_0x96e6x6.$elem);
            _0x96e6x6["$elem"]["find"]("\x3E span.z-tab-spacer")["remove"]();
            _0x96e6x6["$elem"]["addClass"](_0x96e6x4e["classes"]["wrapper"]);
            var _0x96e6x8d = _0x96e6x86["isTop"](_0x96e6x6);
            _0x96e6x6["$contents"]["each"](function (_0x96e6x16, _0x96e6x74) {
                var _0x96e6x8 = _0x96e6x1(_0x96e6x74);
                var _0x96e6x8e = _0x96e6x73;
                if (!_0x96e6x8["find"]("\x3E div." + _0x96e6x73)["length"]) {
                    if (_0x96e6x8["hasClass"]("z-row")) {
                        _0x96e6x8["removeClass"]("z-row");
                        _0x96e6x8e = "z-row " + _0x96e6x73;
                    }
                    ;
                    _0x96e6x8["wrapInner"]("\x3Cdiv class=\x27" + _0x96e6x8e + "\x27\x3E\x3C/div\x3E");
                    _0x96e6x1["zozo"]["core"]["content"]["check"](_0x96e6x8);
                }
                ;
            });
            if (_0x96e6x6["settings"]["orientation"] === _0x96e6x65) {
                if (_0x96e6x6["settings"]["position"] !== _0x96e6x68) {
                    _0x96e6x6["settings"]["position"] = _0x96e6x67;
                }
                ;
            } else {
                _0x96e6x6["settings"]["orientation"] = _0x96e6x66;
                if (_0x96e6x8d === false) {
                    _0x96e6x6["$tabGroup"]["appendTo"](_0x96e6x6.$elem);
                    _0x96e6x1(_0x96e6x4e["elementSpacer"])["appendTo"](_0x96e6x6.$elem);
                    _0x96e6x6["$container"]["prependTo"](_0x96e6x6.$elem);
                }
                ;
            }
            ;
            _0x96e6x6["$elem"]["addClass"](_0x96e6x6["settings"]["orientation"]);
            _0x96e6x6["$elem"]["addClass"](_0x96e6x6["settings"]["position"]);
            if (_0x96e6x8d) {
                _0x96e6x6["$elem"]["addClass"](_0x96e6x69);
            } else {
                _0x96e6x6["$elem"]["addClass"](_0x96e6x6a);
            }
            ;
        }, bindEvents: function (_0x96e6x6) {
            var _0x96e6x25 = (_0x96e6x6["settings"]["animation"]["effects"] === _0x96e6x4e["animation"]["effects"]["none"]) ? 0 : _0x96e6x6["settings"]["animation"]["duration"];
            _0x96e6x6["$tabs"]["each"](function () {
                var _0x96e6x1c = _0x96e6x1(this);
                var _0x96e6x8f = _0x96e6x1c["find"]("a")["attr"]("href");
                var _0x96e6x3d = _0x96e6x1c["find"]("a")["attr"]("target");
                if (!_0x96e6x1["trim"](_0x96e6x8f)["length"]) {
                    _0x96e6x86["bindEvent"](_0x96e6x6, _0x96e6x1c);
                } else {
                    _0x96e6x1c["on"]("ztap", {data: _0x96e6x6["settings"]["event"]}, function (_0x96e6x39) {
                        (_0x96e6x1["trim"](_0x96e6x3d)["length"]) ? _0x96e6x2["open"](_0x96e6x8f, _0x96e6x3d) : _0x96e6x2["location"] = _0x96e6x8f;
                        _0x96e6x39["preventDefault"]();
                    });
                }
                ;
            });
            _0x96e6x1(_0x96e6x2)["resize"](function () {
                if (_0x96e6x6["lastWindowWidth"] !== _0x96e6x1(_0x96e6x2)["width"]()) {
                    clearInterval(_0x96e6x6["resizeWindowIntervalId"]);
                    _0x96e6x6["resizeWindowIntervalId"] = setTimeout(function () {
                        _0x96e6x6["lastWindowHeight"] = _0x96e6x1(_0x96e6x2)["height"]();
                        _0x96e6x6["lastWindowWidth"] = _0x96e6x1(_0x96e6x2)["width"]();
                        _0x96e6x86["checkWidth"](_0x96e6x6);
                    }, _0x96e6x6["settings"]["responsiveDelay"]);
                }
                ;
            });
            var _0x96e6x90 = _0x96e6x6["settings"]["next"];
            if (_0x96e6x90 != null) {
                _0x96e6x1(_0x96e6x90)["on"](_0x96e6x4e["events"]["click"], function (_0x96e6x39) {
                    _0x96e6x39["preventDefault"]();
                    _0x96e6x6["next"]();
                });
            }
            ;
            var _0x96e6x91 = _0x96e6x6["settings"]["prev"];
            if (_0x96e6x91 != null) {
                _0x96e6x1(_0x96e6x91)["on"](_0x96e6x4e["events"]["click"], function (_0x96e6x39) {
                    _0x96e6x39["preventDefault"]();
                    _0x96e6x6["prev"]();
                });
            }
            ;
            if (_0x96e6x6["$mobileNav"]) {
                _0x96e6x6["$mobileNav"]["find"]("li")["on"]("ztap", {data: _0x96e6x6["settings"]["event"]}, function (_0x96e6x39) {
                    _0x96e6x39["preventDefault"]();
                    if (_0x96e6x6["$mobileNav"]["hasClass"](_0x96e6x4e["states"]["closed"])) {
                        _0x96e6x6["$mobileNav"]["removeClass"](_0x96e6x4e["states"]["closed"]);
                        _0x96e6x6["$tabGroup"]["removeClass"]("z-hide-menu");
                        _0x96e6x86["mobileNavAutoScroll"](_0x96e6x6);
                    } else {
                        _0x96e6x6["$mobileNav"]["addClass"](_0x96e6x4e["states"]["closed"]);
                        _0x96e6x6["$tabGroup"]["addClass"]("z-hide-menu");
                    }
                    ;
                    _0x96e6x86["refreshParents"](_0x96e6x6, _0x96e6x25);
                });
            }
            ;
            _0x96e6x6["lastWindowHeight"] = _0x96e6x1(_0x96e6x2)["height"]();
            _0x96e6x6["lastWindowWidth"] = _0x96e6x1(_0x96e6x2)["width"]();
            _0x96e6x6["$elem"]["bind"](_0x96e6x50, _0x96e6x6["settings"]["ready"]);
            _0x96e6x6["$elem"]["bind"](_0x96e6x52, _0x96e6x6["settings"]["select"]);
            _0x96e6x6["$elem"]["bind"](_0x96e6x54, _0x96e6x6["settings"]["deactivate"]);
            _0x96e6x6["$elem"]["bind"](_0x96e6x51, _0x96e6x6["settings"]["error"]);
            _0x96e6x6["$elem"]["bind"](_0x96e6x57, _0x96e6x6["settings"]["contentLoad"]);
        }, bindEvent: function (_0x96e6x6, _0x96e6x1c) {
            _0x96e6x1c["on"]("ztap", {data: _0x96e6x6["settings"]["event"]}, function (_0x96e6x39) {
                _0x96e6x39["preventDefault"]();
                if (_0x96e6x6["settings"]["autoplay"] !== false && _0x96e6x6["settings"]["autoplay"] != null) {
                    if (_0x96e6x6["settings"]["autoplay"]["smart"] === true) {
                        _0x96e6x6["stop"]();
                    }
                    ;
                }
                ;
                _0x96e6x86["changeHash"](_0x96e6x6, _0x96e6x1c["attr"](_0x96e6x6["settings"]["hashAttribute"]));
                if (_0x96e6x86["allowAutoScrolling"](_0x96e6x6) === true && _0x96e6x86["isMobile"](_0x96e6x6)) {
                    _0x96e6x1(_0x96e6x2["opera"] ? "html" : "html, body")["animate"]({scrollTop: _0x96e6x6["$elem"]["offset"]()["top"] + _0x96e6x6["settings"]["mobileAutoScrolling"]["contentTopOffset"]}, 0);
                }
                ;
            });
        }, mobileNavAutoScroll: function (_0x96e6x6) {
            if (_0x96e6x86["allowAutoScrolling"](_0x96e6x6) === true) {
                _0x96e6x1(_0x96e6x2["opera"] ? "html" : "html, body")["animate"]({scrollTop: _0x96e6x6["$mobileNav"]["offset"]()["top"] + _0x96e6x6["settings"]["mobileAutoScrolling"]["navTopOffset"]}, 0);
            }
            ;
            return _0x96e6x6;
        }, showTab: function (_0x96e6x6, _0x96e6x75) {
            if (_0x96e6x86["tabExist"](_0x96e6x6, _0x96e6x75) && _0x96e6x75 != null && _0x96e6x6["settings"]["animating"] !== true) {
                var _0x96e6x92 = _0x96e6x6["$tabs"]["filter"]("li[" + _0x96e6x6["settings"]["hashAttribute"] + "=\x27" + _0x96e6x75 + "\x27]");
                var _0x96e6x93 = _0x96e6x6["$tabs"]["index"](_0x96e6x92);
                var _0x96e6x94 = _0x96e6x86["getActive"](_0x96e6x6, _0x96e6x93);
                if (_0x96e6x94["enabled"] && _0x96e6x94["preIndex"] !== _0x96e6x94["index"] && _0x96e6x6["settings"]["noTabs"] !== true) {
                    _0x96e6x6["currentTab"] = _0x96e6x92;
                    _0x96e6x6["$tabs"]["removeClass"](_0x96e6x4e["classes"]["active"]);
                    _0x96e6x6["currentTab"]["addClass"](_0x96e6x4e["classes"]["active"]);
                    if (_0x96e6x6["settings"]["rememberState"] === true && _0x96e6x1["zozo"]["core"]["support"]["html5_storage"]()) {
                        localStorage["setItem"](_0x96e6x6["tabID"] + "_defaultTab", _0x96e6x92["data"]("link"));
                    }
                    ;
                    _0x96e6x86["mobileNav"](_0x96e6x6, false, _0x96e6x94["index"]);
                    if (_0x96e6x94["contentUrl"]) {
                        if (_0x96e6x94["preIndex"] === -1) {
                            _0x96e6x94["content"]["css"]({
                                "opacity": "",
                                "left": "",
                                "top": "",
                                "position": "relative"
                            })["show"]();
                        }
                        ;
                        if (_0x96e6x94["contentType"] === "iframe") {
                            _0x96e6x86["iframeContent"](_0x96e6x6, _0x96e6x94);
                        } else {
                            _0x96e6x86["ajaxRequest"](_0x96e6x6, _0x96e6x94);
                        }
                        ;
                    } else {
                        _0x96e6x86["showContent"](_0x96e6x6, _0x96e6x94);
                    }
                    ;
                }
                ;
            }
            ;
        }, getActiveIndex: function (_0x96e6x6) {
            var _0x96e6x7e;
            if (_0x96e6x6["settings"]["noTabs"] === true) {
                _0x96e6x7e = _0x96e6x6["$container"]["find"]("\x3Ediv." + _0x96e6x4e["classes"]["active"])["index"]();
            } else {
                if (_0x96e6x6["currentTab"]) {
                    _0x96e6x7e = parseInt(_0x96e6x6["currentTab"]["index"]());
                } else {
                    _0x96e6x7e = _0x96e6x6["$tabGroup"]["find"]("li." + _0x96e6x4e["classes"]["active"])["index"]();
                }
                ;
            }
            ;
            return _0x96e6x7e;
        }, getActive: function (_0x96e6x6, _0x96e6x7e) {
            var _0x96e6x95 = _0x96e6x86["getActiveIndex"](_0x96e6x6);
            var _0x96e6x96 = _0x96e6x6["$contents"]["eq"](_0x96e6x7e);
            var _0x96e6x97 = _0x96e6x6["$tabs"]["eq"](_0x96e6x7e);
            var _0x96e6x98 = _0x96e6x6["$tabs"]["eq"](_0x96e6x95);
            var _0x96e6x27 = _0x96e6x1["zozo"]["core"]["support"]["css"]["transition"];
            var _0x96e6x25 = (_0x96e6x6["settings"]["animation"]["effects"] === _0x96e6x4e["animation"]["effects"]["none"]) ? 0 : _0x96e6x6["settings"]["animation"]["duration"];
            var _0x96e6x94 = {
                index: _0x96e6x7e,
                tab: _0x96e6x97,
                content: _0x96e6x96,
                contentInner: _0x96e6x96["find"]("\x3E .z-content-inner"),
                enabled: _0x96e6x86["isTabDisabled"](_0x96e6x97) === false,
                contentUrl: _0x96e6x96["data"](_0x96e6x58),
                contentType: _0x96e6x96["data"](_0x96e6x59),
                noAnimation: false,
                transition: _0x96e6x27,
                duration: _0x96e6x25,
                preIndex: _0x96e6x95,
                preTab: _0x96e6x98,
                preContent: _0x96e6x6["$contents"]["eq"](_0x96e6x95)
            };
            return _0x96e6x94;
        }, iframeContent: function (_0x96e6x6, _0x96e6x94) {
            var _0x96e6x99 = _0x96e6x94["contentInner"]["find"]("\x3E div \x3E.z-iframe");
            if (!_0x96e6x99["length"]) {
                _0x96e6x86["showLoading"](_0x96e6x6);
                _0x96e6x94["contentInner"]["append"]("\x3Cdiv class=\x22z-video\x22\x3E\x3Ciframe src=\x22" + _0x96e6x94["contentUrl"] + "\x22 frameborder=\x220\x22 scrolling=\x22auto\x22 height=\x221400\x22 class=\x22z-iframe\x22\x3E\x3C/iframe\x3E\x3C/div\x3E");
                console["log"]("add iframe");
            } else {
                _0x96e6x86["hideLoading"](_0x96e6x6);
            }
            ;
            _0x96e6x86["showContent"](_0x96e6x6, _0x96e6x94);
            _0x96e6x94["contentInner"]["find"](".z-iframe")["load"](function () {
                _0x96e6x86["hideLoading"](_0x96e6x6);
            });
            return _0x96e6x6;
        }, showLoading: function (_0x96e6x6) {
            _0x96e6x6["$container"]["append"]("\x3Cspan class=\x22" + _0x96e6x60 + "\x22\x3E\x3C/span\x3E");
            return _0x96e6x6;
        }, hideLoading: function (_0x96e6x6) {
            _0x96e6x6["$container"]["find"]("\x3E." + _0x96e6x60)["remove"]();
            return _0x96e6x6;
        }, ajaxRequest: function (_0x96e6x6, _0x96e6x94) {
            var _0x96e6x2f = {};
            var _0x96e6x9a = {
                tab: _0x96e6x94["tab"],
                content: _0x96e6x94["contentInner"],
                index: _0x96e6x94["index"],
                xhr: null,
                message: ""
            };
            _0x96e6x1["ajax"]({
                type: "GET",
                cache: (_0x96e6x6["settings"]["cacheAjax"] === true),
                url: _0x96e6x94["contentUrl"],
                dataType: "html",
                data: _0x96e6x2f,
                beforeSend: function (_0x96e6x9b, _0x96e6x9c) {
                    _0x96e6x86["showLoading"](_0x96e6x6);
                    _0x96e6x6["settings"]["animating"] = true;
                },
                error: function (_0x96e6x9b, _0x96e6x9d, _0x96e6x9e) {
                    if (_0x96e6x9b["status"] == 404) {
                        _0x96e6x9a["message"] = "\x3Ch4 style=\x27color:red;\x27\x3ESorry, error: 404 - the requested content could not be found.\x3C/h4\x3E";
                    } else {
                        _0x96e6x9a["message"] = "\x3Ch4 style=\x27color:red;\x27\x3EAn error occurred: " + _0x96e6x9d + "\x0AError: " + _0x96e6x9b + " code: " + _0x96e6x9b["status"] + "\x3C/h4\x3E";
                    }
                    ;
                    _0x96e6x9a["xhr"] = _0x96e6x9b;
                    (_0x96e6x6["settings"]["error"] && typeof (_0x96e6x6["settings"]["error"]) == typeof (Function)) && _0x96e6x6["$elem"]["trigger"](_0x96e6x51, _0x96e6x9a);
                    _0x96e6x94["contentInner"]["html"](_0x96e6x9a["message"]);
                },
                complete: function (_0x96e6x9b, _0x96e6x9d) {
                    setTimeout(function () {
                        _0x96e6x6["settings"]["animating"] = false;
                        _0x96e6x86["showContent"](_0x96e6x6, _0x96e6x94);
                        _0x96e6x86["hideLoading"](_0x96e6x6);
                    }, _0x96e6x6["settings"]["delayAjax"]);
                },
                success: function (_0x96e6x2f, _0x96e6x9d, _0x96e6x9b) {
                    setTimeout(function () {
                        _0x96e6x94["contentInner"]["html"](_0x96e6x2f);
                        _0x96e6x9a["xhr"] = _0x96e6x9b;
                        _0x96e6x6["$elem"]["trigger"](_0x96e6x57, _0x96e6x9a);
                    }, _0x96e6x6["settings"]["delayAjax"]);
                }
            });
            return _0x96e6x6;
        }, showContent: function (_0x96e6x6, _0x96e6x94) {
            if (_0x96e6x94["preIndex"] !== _0x96e6x94["index"] && _0x96e6x6["settings"]["animating"] !== true) {
                _0x96e6x6["settings"]["animating"] = true;
                _0x96e6x6["$contents"]["removeClass"](_0x96e6x4e["classes"]["active"]);
                _0x96e6x94["content"]["addClass"](_0x96e6x4e["classes"]["active"]);
                if (_0x96e6x94["preIndex"] === -1) {
                    _0x96e6xd5["init"](_0x96e6x6, _0x96e6x94);
                } else {
                    var _0x96e6x88 = _0x96e6x6["settings"]["animation"]["effects"];
                    var _0x96e6x9f = _0x96e6x86["getContentHeight"](_0x96e6x6, _0x96e6x94["preContent"], true)["height"];
                    var _0x96e6xa0 = _0x96e6x86["getContentHeight"](_0x96e6x6, _0x96e6x94["content"], true)["height"];
                    var _0x96e6xa1 = _0x96e6x86["isLarger"](_0x96e6x9f, _0x96e6xa0);
                    if (_0x96e6x6["settings"]["orientation"] === _0x96e6x66 && _0x96e6x6["settings"]["autoContentHeight"] === true) {
                        _0x96e6xa1 = (_0x96e6x9f > _0x96e6xa0) ? _0x96e6x9f : _0x96e6xa0;
                    }
                    ;
                    var _0x96e6xa2 = (_0x96e6x88 === _0x96e6x4e["animation"]["effects"]["slideH"] || _0x96e6x88 === _0x96e6x4e["animation"]["effects"]["slideLeft"] || _0x96e6x88 === _0x96e6x4e["animation"]["effects"]["slideRight"]) ? _0x96e6x6["$container"]["width"]() : _0x96e6xa2 = _0x96e6xa1;
                    if (_0x96e6x94["preIndex"] < _0x96e6x94["index"] && _0x96e6x88 === _0x96e6x4e["animation"]["effects"]["slideV"]) {
                        var _0x96e6xa3 = _0x96e6x86["isLarger"](_0x96e6x9f, _0x96e6xa0);
                        (_0x96e6xa3 > _0x96e6xa2) && (_0x96e6xa2 = _0x96e6xa3);
                    }
                    ;
                    var _0x96e6xa4 = -_0x96e6xa2;
                    var _0x96e6xa5 = _0x96e6xa2;
                    if (_0x96e6x94["preIndex"] > _0x96e6x94["index"]) {
                        _0x96e6xa4 = _0x96e6xa2;
                        _0x96e6xa5 = -_0x96e6xa2;
                    }
                    ;
                    _0x96e6xd5["before"](_0x96e6x6, _0x96e6x94);
                    switch (_0x96e6x88) {
                        case _0x96e6x4e["animation"]["effects"]["slideV"]:
                            _0x96e6x86["animate"](_0x96e6x6, _0x96e6x94["preContent"], null, {
                                "left": 0,
                                "top": _0x96e6xa4 + "px"
                            });
                            _0x96e6x86["animate"](_0x96e6x6, _0x96e6x94["content"], {
                                "left": 0,
                                "top": _0x96e6xa5 + "px"
                            }, {"top": 0});
                            break;
                            ;
                        case _0x96e6x4e["animation"]["effects"]["slideUp"]:
                            _0x96e6x86["animate"](_0x96e6x6, _0x96e6x94["preContent"], {"opacity": 1}, {
                                "left": 0,
                                "top": (-_0x96e6xa2) + "px"
                            });
                            _0x96e6x86["animate"](_0x96e6x6, _0x96e6x94["content"], {
                                "left": 0,
                                "top": (_0x96e6xa2 * 1) + "px"
                            }, {"top": 0});
                            break;
                            ;
                        case _0x96e6x4e["animation"]["effects"]["slideDown"]:
                            _0x96e6x86["animate"](_0x96e6x6, _0x96e6x94["preContent"], {"opacity": 1}, {
                                "left": 0,
                                "top": (_0x96e6xa2) + "px"
                            });
                            _0x96e6x86["animate"](_0x96e6x6, _0x96e6x94["content"], {
                                "left": 0,
                                "top": (-_0x96e6xa2) + "px"
                            }, {"top": 0});
                            break;
                            ;
                        case _0x96e6x4e["animation"]["effects"]["slideUpDown"]:
                            _0x96e6x86["animate"](_0x96e6x6, _0x96e6x94["preContent"], {"opacity": 1}, {
                                "left": 0,
                                "top": (-_0x96e6xa2 * 1) + "px"
                            });
                            _0x96e6x86["animate"](_0x96e6x6, _0x96e6x94["content"], {
                                "left": 0,
                                "top": (-(_0x96e6xa2 * 2)) + "px"
                            }, {"top": 0});
                            break;
                            ;
                        case _0x96e6x4e["animation"]["effects"]["slideH"]:
                            _0x96e6x86["animate"](_0x96e6x6, _0x96e6x94["preContent"], null, {"left": _0x96e6xa4 + "px"});
                            _0x96e6x86["animate"](_0x96e6x6, _0x96e6x94["content"], {"left": _0x96e6xa5 + "px"}, {"left": 0});
                            break;
                            ;
                        case _0x96e6x4e["animation"]["effects"]["slideRight"]:
                            _0x96e6x86["animate"](_0x96e6x6, _0x96e6x94["preContent"], {"opacity": 1}, {
                                "top": 0,
                                "left": (_0x96e6xa2) + "px"
                            });
                            _0x96e6x86["animate"](_0x96e6x6, _0x96e6x94["content"], {
                                "top": 0,
                                "left": (-_0x96e6xa2) + "px"
                            }, {"top": 0, "left": 0});
                            break;
                            ;
                        case _0x96e6x4e["animation"]["effects"]["slideLeft"]:
                            _0x96e6x86["animate"](_0x96e6x6, _0x96e6x94["preContent"], {"opacity": 1}, {
                                "top": 0,
                                "left": (-_0x96e6xa2) + "px"
                            });
                            _0x96e6x86["animate"](_0x96e6x6, _0x96e6x94["content"], {
                                "top": 0,
                                "left": (_0x96e6xa2) + "px"
                            }, {"top": 0, "left": 0});
                            break;
                            ;
                        case _0x96e6x4e["animation"]["effects"]["fade"]:
                            _0x96e6x86["animate"](_0x96e6x6, _0x96e6x94["preContent"], {"display": "block"}, {"opacity": 0});
                            _0x96e6x86["animate"](_0x96e6x6, _0x96e6x94["content"], {
                                "display": "block",
                                "opacity": 0
                            }, {"opacity": 1});
                            break;
                            ;
                        case _0x96e6x4e["animation"]["effects"]["none"]:
                            _0x96e6x6["$contents"]["css"]({
                                "position": "absolute",
                                "left": 0,
                                "top": 0
                            })["removeClass"](_0x96e6x4e["classes"]["active"])["hide"]()["eq"](_0x96e6x94["index"])["addClass"](_0x96e6x4e["classes"]["active"])["css"]({"position": "relative"})["show"]();
                            break;
                            ;
                        default:
                            ;
                    }
                    ;
                    _0x96e6xd5["after"](_0x96e6x6, _0x96e6x94);
                }
                ;
            }
            ;
        }, refreshParents: function (_0x96e6x6, _0x96e6x25) {
            setTimeout(function () {
                _0x96e6x6["$elem"]["parents"](".z-tabs")["each"](function (_0x96e6x16, _0x96e6x74) {
                    _0x96e6x1(_0x96e6x74)["data"]("zozoTabs")["refresh"]();
                });
            }, _0x96e6x25);
        }, animate: function (_0x96e6x6, _0x96e6x20, _0x96e6x21, _0x96e6x22, _0x96e6x23, _0x96e6x24) {
            _0x96e6x1["zozo"]["core"]["utils"]["animate"](_0x96e6x6, _0x96e6x20, _0x96e6x21, _0x96e6x22, _0x96e6x23, _0x96e6x24);
        }, mobileNav: function (_0x96e6x6, _0x96e6xa6, _0x96e6x93) {
            if (_0x96e6x93 !== null && _0x96e6x6["$mobileNav"]) {
                _0x96e6x6["$mobileNav"]["find"]("\x3E li \x3E a \x3E span.z-title")["html"](_0x96e6x6["$tabs"]["eq"](_0x96e6x93)["find"]("a")["html"]());
            }
            ;
            if (_0x96e6xa6 === true) {
                setTimeout(function () {
                    _0x96e6x6["$mobileNav"]["removeClass"](_0x96e6x4e["states"]["closed"]);
                }, _0x96e6x6["settings"]["animation"]["mobileDuration"]);
                _0x96e6x6["$tabGroup"]["removeClass"]("z-hide-menu");
                ;
                ;
            } else {
                (_0x96e6x6["$mobileNav"]) && _0x96e6x6["$mobileNav"]["addClass"](_0x96e6x4e["states"]["closed"]);
                _0x96e6x6["$tabGroup"]["addClass"]("z-hide-menu");
            }
            ;
        }, setResponsiveDimension: function (_0x96e6x6, _0x96e6xa7, _0x96e6xa8) {
            var _0x96e6xa9 = _0x96e6x6["$container"];
            _0x96e6x6["settings"]["original"]["count"] = parseInt(_0x96e6x6["$tabs"]["size"]());
            if (!_0x96e6xa8) {
                _0x96e6x6["settings"]["original"]["itemD"] = parseInt(_0x96e6xa9["width"]() / _0x96e6x6["settings"]["original"]["itemWidth"]);
                _0x96e6x6["settings"]["original"]["itemM"] = _0x96e6x6["settings"]["original"]["itemWidth"] + _0x96e6x6["settings"]["original"]["itemM"];
            }
            ;
            _0x96e6x6["settings"]["original"]["firstRowWidth"] = (_0x96e6x6["settings"]["original"]["itemWidth"] / (parseInt(_0x96e6x6["settings"]["original"]["itemD"]) * _0x96e6x6["settings"]["original"]["itemWidth"]) * 100);
            _0x96e6x6["settings"]["original"]["itemCount"] = parseInt(_0x96e6x6["settings"]["original"]["itemD"]) * parseInt(_0x96e6x6["settings"]["original"]["count"] / (parseInt(_0x96e6x6["settings"]["original"]["itemD"])));
            _0x96e6x6["settings"]["original"]["lastItem"] = 100 / (parseInt(_0x96e6x6["settings"]["original"]["count"]) - parseInt(_0x96e6x6["settings"]["original"]["itemCount"]));
            _0x96e6x6["settings"]["original"]["navHeight"] = _0x96e6x6["settings"]["original"]["itemD"] * (parseInt(_0x96e6x6["$tabs"]["eq"](0)["innerHeight"]())) + ((_0x96e6x6["settings"]["original"]["itemM"] > 0 ? _0x96e6x6["$tabs"]["eq"](0)["innerHeight"]() : 0));
            _0x96e6x6["settings"]["original"]["bottomLeft"] = _0x96e6x6["settings"]["original"]["count"] - (_0x96e6x6["settings"]["original"]["count"] - _0x96e6x6["settings"]["original"]["itemCount"]);
            _0x96e6x6["settings"]["original"]["rows"] = _0x96e6x6["settings"]["original"]["count"] > _0x96e6x6["settings"]["original"]["itemCount"] ? parseInt(_0x96e6x6["settings"]["original"]["itemCount"] / _0x96e6x6["settings"]["original"]["itemD"]) + 1 : parseInt(_0x96e6x6["settings"]["original"]["itemCount"] / _0x96e6x6["settings"]["original"]["itemD"]);
            _0x96e6x6["settings"]["original"]["lastRowItems"] = _0x96e6x6["settings"]["original"]["count"] - (_0x96e6x6["settings"]["original"]["itemCount"] * (_0x96e6x6["settings"]["original"]["rows"] - 1));
            _0x96e6x6["settings"]["original"]["itemsPerRow"] = _0x96e6x6["settings"]["original"]["itemCount"] / _0x96e6x6["settings"]["original"]["rows"];
            if (_0x96e6xa9["width"]() > _0x96e6xa7 && !_0x96e6xa8) {
                _0x96e6x6["settings"]["original"]["itemD"] = _0x96e6x6["settings"]["original"]["count"];
                _0x96e6x6["settings"]["original"]["itemM"] = 0;
                _0x96e6x6["settings"]["original"]["rows"] = 1;
                _0x96e6x6["settings"]["original"]["itemCount"] = _0x96e6x6["settings"]["original"]["count"];
            }
            ;
            return _0x96e6x6;
        }, checkWidth: function (_0x96e6x6, _0x96e6x87, _0x96e6x89) {
            var _0x96e6xa7 = 0;
            var _0x96e6xa9 = _0x96e6x6["$container"];
            var _0x96e6xaa = _0x96e6x86["isCompact"](_0x96e6x6);
            var _0x96e6xab = 0;
            var _0x96e6xac = _0x96e6x6["settings"]["tabRatio"];
            var _0x96e6xad = _0x96e6x6["settings"]["tabRatioCompact"];
            _0x96e6x6["$tabs"]["each"](function (_0x96e6x16) {
                var _0x96e6xae = _0x96e6x1(this)["outerWidth"](true) * _0x96e6xac;
                (_0x96e6xaa) && (_0x96e6xae = (_0x96e6xae * _0x96e6xad));
                if (_0x96e6x87 === true) {
                    if (_0x96e6xae > _0x96e6x6["settings"]["original"]["itemWidth"]) {
                        _0x96e6x6["settings"]["original"]["itemWidth"] = _0x96e6xae;
                        _0x96e6x6["settings"]["original"]["itemMaxWidth"] = _0x96e6xae;
                    }
                    ;
                    if (_0x96e6xae < _0x96e6x6["settings"]["original"]["itemMinWidth"]) {
                        _0x96e6x6["settings"]["original"]["itemMinWidth"] = _0x96e6xae;
                    }
                    ;
                }
                ;
                _0x96e6xab = _0x96e6xab + _0x96e6x1(this)["innerHeight"]();
                _0x96e6xa7 = _0x96e6xa7 + _0x96e6xae;
            });
            if (_0x96e6x87 === true) {
                _0x96e6xa7 = _0x96e6xa7 + (_0x96e6x6["settings"]["original"]["itemWidth"] * 0);
            }
            ;
            _0x96e6x6["settings"]["original"]["count"] = parseInt(_0x96e6x6["$tabs"]["size"]());
            _0x96e6x6["settings"]["original"]["groupWidth"] = _0x96e6xa7;
            _0x96e6x86["setResponsiveDimension"](_0x96e6x6, _0x96e6x6["settings"]["original"]["groupWidth"]);
            if (_0x96e6x6["settings"]["original"]["count"] > 3 && _0x96e6x6["settings"]["original"]["lastRowItems"] === 1) {
                _0x96e6x6["settings"]["original"]["itemD"] = _0x96e6x6["settings"]["original"]["itemD"] - 1;
                _0x96e6x6["settings"]["original"]["itemM"] = _0x96e6xa9["width"]() % _0x96e6x6["settings"]["original"]["itemWidth"];
                _0x96e6x86["setResponsiveDimension"](_0x96e6x6, _0x96e6x6["settings"]["original"]["groupWidth"], true);
            }
            ;
            if (_0x96e6x87 === true || _0x96e6x89 === true) {
                _0x96e6x6["settings"]["original"]["initGroupWidth"] = _0x96e6x6["settings"]["original"]["groupWidth"];
                if (_0x96e6x86["isCompact"](_0x96e6x6)) {
                    var _0x96e6xaf = 100 / _0x96e6x6["settings"]["original"]["count"];
                    _0x96e6x6["$tabs"]["each"](function () {
                        _0x96e6x1(this)["css"]({"width": _0x96e6xaf + "%"});
                    });
                }
                ;
                _0x96e6x6["settings"]["original"]["position"] = _0x96e6x6["settings"]["position"];
            }
            ;
            if (_0x96e6x6["settings"]["responsive"] === true) {
                _0x96e6x86["responsive"](_0x96e6x6, _0x96e6x87);
            }
            ;
            var _0x96e6xb0 = ((_0x96e6x86["isCompact"](_0x96e6x6) && !_0x96e6x86["isMobile"](_0x96e6x6)));
            var _0x96e6xb1 = (_0x96e6x86["isResponsive"](_0x96e6x6) && _0x96e6x6["BrowserDetection"]["isIE"](7)) ? {
                    "float": "none",
                    "width": "auto"
                } : {"float": ""};
            var _0x96e6xb2 = _0x96e6x6["$elem"]["hasClass"](_0x96e6x72);
            _0x96e6x6["$tabs"]["each"](function (_0x96e6x16) {
                if (((_0x96e6xb2 === true && (_0x96e6x16 + 1) === _0x96e6x6["settings"]["original"]["itemD"]) || (_0x96e6x16 + 1) === _0x96e6x6["settings"]["original"]["count"]) && _0x96e6xb0) {
                    _0x96e6x1(this)["css"](_0x96e6xb1);
                } else {
                    _0x96e6x1(this)["css"]({"float": ""});
                }
                ;
            });
            if (_0x96e6x6["settings"]["orientation"] === _0x96e6x65) {
                _0x96e6x86["setContentHeight"](_0x96e6x6, null, true);
            }
            ;
        }, checkModes: function (_0x96e6x6) {
            var _0x96e6xaa = _0x96e6x86["isCompact"](_0x96e6x6);
            if (_0x96e6x6["settings"]["mode"] === _0x96e6x4e["modes"]["stacked"]) {
                _0x96e6x6["$elem"]["addClass"](_0x96e6x5d);
                _0x96e6x6["$elem"]["addClass"](_0x96e6x72);
                _0x96e6x6["$tabs"]["css"]({"width": ""});
                (_0x96e6x6["$mobileNav"]) && _0x96e6x6["$mobileNav"]["hide"]();
            } else {
                if (_0x96e6xaa) {
                    var _0x96e6xaf = 100 / _0x96e6x6["settings"]["original"]["count"];
                    _0x96e6x6["$tabs"]["each"](function () {
                        _0x96e6x1(this)["css"]({"float": "", "width": _0x96e6xaf + "%"});
                    });
                } else {
                    _0x96e6x6["$tabs"]["each"](function () {
                        _0x96e6x1(this)["css"]({"float": "", "width": ""});
                    });
                }
                ;
            }
            ;
        }, getContentHeight: function (_0x96e6x6, _0x96e6xb3, _0x96e6xb4) {
            var _0x96e6xb5 = _0x96e6x6["settings"]["autoContentHeight"];
            var _0x96e6xb6 = {width: 0, height: 0};
            if (_0x96e6xb5 != true) {
                _0x96e6x6["$contents"]["each"](function (_0x96e6x16, _0x96e6x74) {
                    var _0x96e6x8 = _0x96e6x1(_0x96e6x74);
                    var _0x96e6xb7 = _0x96e6x86["getElementSize"](_0x96e6x8);
                    (_0x96e6xb7["height"] > _0x96e6xb6["height"]) && (_0x96e6xb6["height"] = _0x96e6xb7["height"]);
                    (_0x96e6xb7["width"] > _0x96e6xb6["width"]) && (_0x96e6xb6["width"] = _0x96e6xb7["width"]);
                });
            } else {
                var _0x96e6xb8 = _0x96e6x6["$elem"]["find"]("\x3E .z-container \x3E .z-content.z-active");
                if (_0x96e6xb3 != null) {
                    _0x96e6xb8 = _0x96e6xb3;
                }
                ;
                _0x96e6xb6["height"] = _0x96e6x86["getElementSize"](_0x96e6xb8)["height"];
            }
            ;
            if (_0x96e6x6["settings"]["orientation"] === _0x96e6x65 && !_0x96e6x86["isMobile"](_0x96e6x6)) {
                var _0x96e6xb9 = 0;
                _0x96e6x6["$tabs"]["each"](function (_0x96e6x16) {
                    _0x96e6xb9 = _0x96e6xb9 + parseInt(_0x96e6x1(this)["height"]()) + parseInt(_0x96e6x1(this)["css"]("border-top-width")) + parseInt(_0x96e6x1(this)["css"]("border-bottom-width"));
                });
                _0x96e6xb6["height"] = _0x96e6x86["isLarger"](_0x96e6xb6["height"], _0x96e6x6["$tabGroup"]["innerHeight"]());
                _0x96e6xb6["height"] = _0x96e6x86["isLarger"](_0x96e6xb6["height"], _0x96e6xb9);
            }
            ;
            return _0x96e6xb6;
        }, setContentHeight: function (_0x96e6x6, _0x96e6xb3, _0x96e6xb4) {
            var _0x96e6xb6 = _0x96e6x86["getContentHeight"](_0x96e6x6, _0x96e6xb3, _0x96e6xb4);
            _0x96e6x6["settings"]["original"]["contentMaxHeight"] = _0x96e6xb6["height"];
            _0x96e6x6["settings"]["original"]["contentMaxWidth"] = _0x96e6xb6["width"];
            var _0x96e6x25 = (_0x96e6x6["settings"]["animation"]["effects"] === _0x96e6x4e["animation"]["effects"]["none"] || _0x96e6xb4 === true) ? 0 : _0x96e6x6["settings"]["animation"]["duration"];
            var _0x96e6xb5 = _0x96e6x6["settings"]["autoContentHeight"];
            var _0x96e6x27 = _0x96e6x1["zozo"]["core"]["support"]["css"]["transition"];
            var _0x96e6xba = {
                _transition: "none",
                "min-height": _0x96e6x6["settings"]["original"]["contentMaxHeight"] + "px"
            };
            if (_0x96e6xb4 === true) {
                _0x96e6x6["$container"]["css"](_0x96e6xba);
            } else {
                _0x96e6x86["animate"](_0x96e6x6, _0x96e6x6.$container, null, _0x96e6xba, {});
            }
            ;
            return _0x96e6x6;
        }, responsive: function (_0x96e6x6, _0x96e6x87) {
            var _0x96e6xbb = _0x96e6x1(_0x96e6x2)["width"]();
            var _0x96e6x8d = _0x96e6x86["isTop"](_0x96e6x6);
            var _0x96e6xaa = _0x96e6x86["isCompact"](_0x96e6x6);
            var _0x96e6xbc = _0x96e6x6["settings"]["original"]["initGroupWidth"] >= _0x96e6x6["$container"]["width"]();
            var _0x96e6xbd = _0x96e6x6["settings"]["original"]["rows"] > _0x96e6x6["settings"]["maxRows"];
            var _0x96e6xbe = _0x96e6xbb <= _0x96e6x6["settings"]["minWindowWidth"];
            var _0x96e6xbf = (!_0x96e6x6["BrowserDetection"]["isIE"](8) && _0x96e6x6["settings"]["mobileNav"] === true && _0x96e6x6["$mobileNav"] != null);
            var _0x96e6x84 = _0x96e6x6["settings"]["original"]["count"];
            var _0x96e6xc0 = _0x96e6x6["settings"]["original"]["itemCount"];
            var _0x96e6xc1 = _0x96e6x6["settings"]["original"]["itemD"];
            var _0x96e6xc2 = _0x96e6x6["settings"]["original"]["rows"];
            _0x96e6x6["$elem"]["removeClass"](_0x96e6x5d);
            _0x96e6x6["$tabs"]["removeClass"](_0x96e6x4e["classes"]["left"])["removeClass"](_0x96e6x4e["classes"]["right"])["removeClass"](_0x96e6x4e["classes"]["firstCol"])["removeClass"](_0x96e6x4e["classes"]["lastCol"])["removeClass"](_0x96e6x4e["classes"]["firstRow"])["removeClass"](_0x96e6x4e["classes"]["lastRow"]);
            if (_0x96e6x6["settings"]["orientation"] === _0x96e6x66) {
                var _0x96e6xc3 = (_0x96e6xaa && (parseInt(_0x96e6x6["settings"]["original"]["count"] * _0x96e6x6["settings"]["original"]["itemWidth"]) >= _0x96e6x6["$container"]["width"]()));
                var _0x96e6xc4 = (!_0x96e6xaa && _0x96e6xbc);
                var _0x96e6xc5 = _0x96e6xc3 || _0x96e6xc4;
                if (_0x96e6xc5) {
                    (_0x96e6xc2 === _0x96e6x84 || (_0x96e6x6["settings"]["mode"] === _0x96e6x4e["modes"]["stacked"])) && (_0x96e6x6["$elem"]["addClass"](_0x96e6x5d));
                    _0x96e6x6["$tabs"]["each"](function (_0x96e6x16) {
                        var _0x96e6xc6 = _0x96e6x1(this);
                        var _0x96e6x83 = (_0x96e6x16 + 1);
                        if (_0x96e6x6["settings"]["original"]["itemM"] > 0) {
                            if (_0x96e6x83 <= _0x96e6xc0) {
                                _0x96e6xc6["css"]({
                                    "float": "",
                                    "width": _0x96e6x6["settings"]["original"]["firstRowWidth"] + "%"
                                });
                            } else {
                                _0x96e6xc6["css"]({
                                    "float": "",
                                    "width": _0x96e6x6["settings"]["original"]["lastItem"] + "%"
                                });
                            }
                            ;
                            if (_0x96e6x8d === true) {
                                (_0x96e6x16 === (_0x96e6xc1 - 1)) ? _0x96e6xc6["addClass"](_0x96e6x4e["classes"]["right"]) : _0x96e6xc6["removeClass"](_0x96e6x4e["classes"]["right"]);
                            } else {
                                (_0x96e6x83 === _0x96e6x84) && (_0x96e6xc6["addClass"](_0x96e6x4e["classes"]["right"]));
                                (_0x96e6x16 === _0x96e6x6["settings"]["original"]["bottomLeft"]) && (_0x96e6xc6["addClass"](_0x96e6x4e["classes"]["left"]));
                            }
                            ;
                            if (_0x96e6xc2 > 1 && _0x96e6xc1 !== 1) {
                                (_0x96e6x83 === 1 || (_0x96e6x83 > _0x96e6xc1 && (_0x96e6x83 % _0x96e6xc1 === 1))) && (_0x96e6xc6["addClass"](_0x96e6x4e["classes"]["firstCol"]));
                                (_0x96e6x83 === _0x96e6x84 || (_0x96e6x83 >= _0x96e6xc1 && (_0x96e6x83 % _0x96e6xc1 === 0))) && (_0x96e6xc6["addClass"](_0x96e6x4e["classes"]["lastCol"]));
                                (_0x96e6x83 <= _0x96e6xc1) && (_0x96e6xc6["addClass"](_0x96e6x4e["classes"]["firstRow"]));
                                (_0x96e6x83 > (_0x96e6xc1 * (_0x96e6xc2 - 1))) && (_0x96e6xc6["addClass"](_0x96e6x4e["classes"]["lastRow"]));
                            }
                            ;
                        }
                        ;
                    });
                    _0x96e6x86["switchResponsiveClasses"](_0x96e6x6, true);
                } else {
                    if (_0x96e6xaa) {
                        var _0x96e6xaf = 100 / _0x96e6x6["settings"]["original"]["count"];
                        _0x96e6x6["$tabs"]["each"](function () {
                            _0x96e6x1(this)["css"]({"float": "", "width": _0x96e6xaf + "%"});
                        });
                    } else {
                        _0x96e6x6["$tabs"]["each"](function () {
                            _0x96e6x1(this)["css"]({"float": "", "width": ""});
                        });
                    }
                    ;
                    _0x96e6x86["switchResponsiveClasses"](_0x96e6x6, false);
                }
                ;
                if (_0x96e6xbb >= 1200 && _0x96e6x6["responsive"] != _0x96e6x4e["responsive"]["largeDesktop"]) {
                    _0x96e6x6["responsive"] = _0x96e6x4e["responsive"]["largeDesktop"];
                    _0x96e6x86["switchMenu"](_0x96e6x6, false);
                }
                ;
                if (_0x96e6x6["responsive"] != _0x96e6x4e["responsive"]["phone"] && _0x96e6xbf && ((_0x96e6xbe) || ((_0x96e6xbd)))) {
                    _0x96e6x6["responsive"] = "auto";
                    _0x96e6x6["$elem"]["removeClass"](_0x96e6x72);
                    _0x96e6x6["$tabs"]["each"](function () {
                        _0x96e6x1(this)["css"]({"width": ""});
                    });
                    _0x96e6x6["$tabs"]["filter"]("li:first-child")["addClass"](_0x96e6x4e["classes"]["first"]);
                    _0x96e6x6["$tabs"]["filter"]("li:last-child")["addClass"](_0x96e6x4e["classes"]["last"]);
                    _0x96e6x86["switchMenu"](_0x96e6x6, true);
                }
                ;
            } else {
                if (_0x96e6xbf === true && (_0x96e6xbe || parseInt(_0x96e6x6["$elem"]["width"]() - _0x96e6x6["settings"]["original"]["itemWidth"]) < _0x96e6x6["settings"]["minWidth"])) {
                    _0x96e6x86["switchMenu"](_0x96e6x6, true);
                } else {
                    _0x96e6x86["switchMenu"](_0x96e6x6, false);
                }
                ;
            }
            ;
            _0x96e6x86["refreshParents"](_0x96e6x6, 0);
        }, switchResponsiveClasses: function (_0x96e6x6, _0x96e6xc7) {
            var _0x96e6x8d = _0x96e6x86["isTop"](_0x96e6x6);
            var _0x96e6xc8 = _0x96e6x6["settings"]["original"]["position"];
            var _0x96e6xc9 = _0x96e6x4e["classes"]["positions"]["topLeft"];
            var _0x96e6xca = _0x96e6x4e["classes"]["positions"]["bottomLeft"];
            if (_0x96e6xc7 === true) {
                _0x96e6x6["$elem"]["addClass"](_0x96e6x72);
                _0x96e6x86["switchMenu"](_0x96e6x6, false);
                _0x96e6x6["$elem"]["removeClass"](_0x96e6xc8);
            } else {
                (_0x96e6x8d === true) ? _0x96e6x6["$elem"]["removeClass"](_0x96e6xc9)["addClass"](_0x96e6xc8) : _0x96e6x6["$elem"]["removeClass"](_0x96e6xca)["addClass"](_0x96e6xc8);
                _0x96e6x86["switchMenu"](_0x96e6x6, false);
                _0x96e6x6["$elem"]["removeClass"](_0x96e6x72);
                _0x96e6x6["$tabs"]["removeClass"](_0x96e6x4e["classes"]["last"])["filter"]("li:last-child")["addClass"](_0x96e6x4e["classes"]["last"]);
            }
            ;
        }, switchMenu: function (_0x96e6x6, _0x96e6xcb) {
            var _0x96e6xcc = _0x96e6x4e["classes"]["themes"];
            var _0x96e6xcd = _0x96e6x4e["classes"]["sizes"];
            var _0x96e6x8c = _0x96e6x1["zozo"]["core"]["utils"]["toArray"](_0x96e6x4e["classes"]["positions"]);
            _0x96e6x6["$elem"]["removeClass"](_0x96e6xcc["join"](_0x96e6x4e["space"]));
            if (_0x96e6xcb === true) {
                (_0x96e6x6["$mobileNav"]) && _0x96e6x6["$mobileNav"]["addClass"](_0x96e6x4e["states"]["closed"])["show"]();
                _0x96e6x6["$tabGroup"]["addClass"]("z-hide-menu");
                _0x96e6x6["$elem"]["addClass"](_0x96e6x6d);
                _0x96e6x6["$elem"]["removeClass"](_0x96e6x6["settings"]["orientation"]);
                _0x96e6x6["$elem"]["removeClass"](_0x96e6x6["settings"]["position"]);
                (_0x96e6x6["settings"]["style"] === _0x96e6x61) ? _0x96e6x6["$elem"]["addClass"]("m-" + _0x96e6x6["settings"]["theme"]) : _0x96e6x6["$elem"]["addClass"](_0x96e6x6["settings"]["theme"]);
            } else {
                _0x96e6x6["$elem"]["addClass"](_0x96e6x6["settings"]["orientation"]);
                _0x96e6x6["$elem"]["addClass"](_0x96e6x6["settings"]["theme"]);
                _0x96e6x6["$elem"]["addClass"](_0x96e6x6["settings"]["position"]);
                (_0x96e6x6["$mobileNav"]) && _0x96e6x6["$mobileNav"]["removeClass"](_0x96e6x4e["states"]["closed"]);
                _0x96e6x6["$tabGroup"]["removeClass"]("z-hide-menu");
                _0x96e6x6["$tabs"]["filter"]("li:first-child")["addClass"](_0x96e6x4e["classes"]["first"]);
                _0x96e6x6["$elem"]["removeClass"](_0x96e6x6d);
                (_0x96e6x6["$mobileNav"]) && _0x96e6x6["$mobileNav"]["hide"]();
            }
            ;
        }, initAutoPlay: function (_0x96e6x6) {
            if (_0x96e6x6["settings"]["autoplay"] !== false && _0x96e6x6["settings"]["autoplay"] != null) {
                if (_0x96e6x6["settings"]["autoplay"]["interval"] > 0) {
                    _0x96e6x6["stop"]();
                    _0x96e6x6["autoplayIntervalId"] = setInterval(function () {
                        _0x96e6x6["next"](_0x96e6x6);
                    }, _0x96e6x6["settings"]["autoplay"]["interval"]);
                } else {
                    _0x96e6x6["stop"]();
                }
                ;
            } else {
                _0x96e6x6["stop"]();
            }
            ;
        }, changeHash: function (_0x96e6x6, _0x96e6x75) {
            var _0x96e6x76 = (_0x96e6x6["settings"]["deeplinkingPrefix"]) ? _0x96e6x6["settings"]["deeplinkingPrefix"] : _0x96e6x6["tabID"];
            if (_0x96e6x6["settings"]["animating"] !== true) {
                if (_0x96e6x6["settings"]["deeplinking"] === true) {
                    if (typeof (_0x96e6x1(_0x96e6x2)["hashchange"]) != "undefined") {
                        _0x96e6x6["Deeplinking"]["set"](_0x96e6x76, _0x96e6x75, _0x96e6x6["settings"]["deeplinkingSeparator"], _0x96e6x6["settings"]["deeplinkingMode"]);
                    } else {
                        if (_0x96e6x6["BrowserDetection"]["isIE"](7)) {
                            _0x96e6x86["showTab"](_0x96e6x6, _0x96e6x75);
                        } else {
                            _0x96e6x6["Deeplinking"]["set"](_0x96e6x76, _0x96e6x75, _0x96e6x6["settings"]["deeplinkingSeparator"], _0x96e6x6["settings"]["deeplinkingMode"]);
                        }
                        ;
                    }
                    ;
                } else {
                    _0x96e6x86["showTab"](_0x96e6x6, _0x96e6x75);
                }
                ;
            }
            ;
        }, getFirst: function (_0x96e6x6) {
            return 0;
        }, getLast: function (_0x96e6x6) {
            if (_0x96e6x6["settings"]["noTabs"] === true) {
                return parseInt(_0x96e6x6["$container"]["children"]("div")["size"]() - 1);
            }
            ;
            return parseInt(_0x96e6x6["$tabGroup"]["children"]("li")["size"]() - 1);
        }, isCompact: function (_0x96e6x6) {
            return (_0x96e6x6["settings"]["position"] === _0x96e6x4e["classes"]["positions"]["topCompact"] || _0x96e6x6["settings"]["position"] === _0x96e6x4e["classes"]["positions"]["bottomCompact"]);
        }, isTop: function (_0x96e6x6) {
            if (_0x96e6x6["settings"]["original"]["position"] === null) {
                _0x96e6x6["settings"]["original"]["position"] = _0x96e6x6["settings"]["position"];
            }
            ;
            return (_0x96e6x6["settings"]["original"]["position"]["indexOf"]("top") >= 0);
        }, isLightTheme: function (_0x96e6x6) {
            var _0x96e6xce = ["red", "deepblue", "blue", "green", "orange", "black"];
            var _0x96e6xcf = true;
            var _0x96e6xd0 = _0x96e6x86["isFlatTheme"](_0x96e6x6);
            if (_0x96e6x6["settings"]["style"] !== _0x96e6x61) {
                (_0x96e6x1["inArray"](_0x96e6x6["settings"]["theme"], _0x96e6xce) > -1) && (_0x96e6xcf = false);
                (_0x96e6xd0) && (_0x96e6xcf = false);
            }
            ;
            return _0x96e6xcf;
        }, isFlatTheme: function (_0x96e6x6) {
            return (_0x96e6x6["settings"]["theme"]["indexOf"]("flat") >= 0);
        }, isResponsive: function (_0x96e6x6) {
            return (_0x96e6x6["$elem"]["hasClass"](_0x96e6x72) === true);
        }, tabExist: function (_0x96e6x6, _0x96e6x75) {
            return (_0x96e6x6["$tabs"]["filter"]("li[" + _0x96e6x6["settings"]["hashAttribute"] + "=\x27" + _0x96e6x75 + "\x27]")["length"] > 0);
        }, isMobile: function (_0x96e6x6) {
            return (_0x96e6x6["$elem"]["hasClass"](_0x96e6x6d) === true);
        }, isTabDisabled: function (_0x96e6x1c) {
            return (_0x96e6x1c["hasClass"](_0x96e6x5c) || _0x96e6x1c["data"](_0x96e6x5a) === true);
        }, allowAutoScrolling: function (_0x96e6x6) {
            return (_0x96e6x6["settings"]["mobileAutoScrolling"] != null && _0x96e6x6["settings"]["mobileAutoScrolling"] != false);
        }, getElementSize: function (_0x96e6x8) {
            var _0x96e6xb6 = {width: 0, height: 0};
            if (_0x96e6x8 == null || _0x96e6x8["length"] == 0) {
                return _0x96e6xb6;
            }
            ;
            if (_0x96e6x8["is"](":visible") === false) {
                _0x96e6xb6["height"] = _0x96e6x8["show"]()["find"]("\x3E.z-content-inner")["innerHeight"]();
                _0x96e6xb6["width"] = _0x96e6x8["show"]()["find"]("\x3E.z-content-inner")["outerWidth"]();
                if (_0x96e6xb6["height"] >= 0) {
                }
                ;
                _0x96e6x8["hide"]();
            } else {
                _0x96e6xb6["height"] = _0x96e6x8["find"]("\x3E.z-content-inner")["innerHeight"]();
                _0x96e6xb6["width"] = _0x96e6x8["find"]("\x3E.z-content-inner")["outerWidth"]();
                if (_0x96e6xb6["height"] >= 0) {
                }
                ;
            }
            ;
            (_0x96e6x8["hasClass"]("z-video") && (_0x96e6xb6["height"] = _0x96e6x8["innerHeight"]()));
            return _0x96e6xb6;
        }, getWidth: function (_0x96e6xd1) {
            if (_0x96e6xd1 == null || _0x96e6xd1["length"] == 0) {
                return 0;
            }
            ;
            _0x96e6xd1 = _0x96e6xd1["find"]("a");
            var _0x96e6x17 = _0x96e6xd1["outerWidth"]();
            _0x96e6x17 += parseInt(_0x96e6xd1["css"]("margin-left"), 10) + parseInt(_0x96e6xd1["css"]("margin-right"), 10);
            _0x96e6x17 += parseInt(_0x96e6xd1["css"]("borderLeftWidth"), 10) + parseInt(_0x96e6xd1["css"]("borderRightWidth"), 10);
            return _0x96e6x17;
        }, isLarger: function (_0x96e6xd2, _0x96e6xd3) {
            var _0x96e6xd4 = _0x96e6xd2;
            if (_0x96e6xd2 < _0x96e6xd3) {
                _0x96e6xd4 = _0x96e6xd3;
            }
            ;
            return _0x96e6xd4;
        }
    };
    var _0x96e6xd5 = {
        init: function (_0x96e6x6, _0x96e6x94) {
            _0x96e6x6["$contents"]["hide"]();
            _0x96e6x94["content"]["css"]({"opacity": "", "left": "", "top": "", "position": "relative"})["show"]();
            setTimeout(function () {
                _0x96e6x6["$container"]["find"](".z-tabs")["each"](function (_0x96e6x16, _0x96e6x74) {
                    _0x96e6x1(_0x96e6x74)["data"]("zozoTabs")["refresh"]();
                });
                _0x96e6x6["$elem"]["trigger"](_0x96e6x52, {
                    tab: _0x96e6x94["tab"],
                    content: _0x96e6x94["content"],
                    index: _0x96e6x94["index"]
                });
                _0x96e6x6["settings"]["animating"] = false;
            }, _0x96e6x94["duration"] >= 0 ? 200 : _0x96e6x94["duration"]);
            if (_0x96e6x6["settings"]["orientation"] === _0x96e6x65) {
                _0x96e6x86["setContentHeight"](_0x96e6x6, _0x96e6x94["content"], true);
            }
            ;
            return _0x96e6x6;
        }, before: function (_0x96e6x6, _0x96e6x94) {
            setTimeout(function () {
                _0x96e6x94["content"]["find"](".z-tabs")["each"](function (_0x96e6x16, _0x96e6x74) {
                    _0x96e6x1(_0x96e6x74)["data"]("zozoTabs")["refresh"]();
                });
            }, 50);
            if (_0x96e6x6["settings"]["animation"]["effects"] !== _0x96e6x4e["animation"]["effects"]["none"]) {
                _0x96e6x86["setContentHeight"](_0x96e6x6, _0x96e6x94["preContent"], true);
                _0x96e6x86["setContentHeight"](_0x96e6x6, _0x96e6x94["content"]);
            }
            ;
            _0x96e6x6["$container"]["addClass"](_0x96e6x70);
            _0x96e6x94["preContent"]["css"]({"position": "absolute", "display": "block", "left": 0, "top": 0});
            _0x96e6x94["content"]["css"]({"position": "absolute", "display": "block"});
            return _0x96e6x6;
        }, after: function (_0x96e6x6, _0x96e6x94) {
            setTimeout(function () {
                _0x96e6x94["content"]["css"]({"position": "relative"});
                _0x96e6x94["preContent"]["css"]({"display": "none"});
            }, _0x96e6x94["duration"]);
            _0x96e6x6["$contents"]["each"](function (_0x96e6x16, _0x96e6x74) {
                if (_0x96e6x94["index"] != _0x96e6x16 && _0x96e6x94["preIndex"] != _0x96e6x16) {
                    _0x96e6x1(_0x96e6x74)["css"]({
                        _transition: "",
                        "position": "",
                        "display": "",
                        "left": "",
                        "top": ""
                    });
                }
                ;
            });
            setTimeout(function () {
                _0x96e6x6["$elem"]["trigger"](_0x96e6x52, {
                    tab: _0x96e6x94["tab"],
                    content: _0x96e6x94["content"],
                    index: _0x96e6x94["index"]
                });
                _0x96e6x6["$elem"]["trigger"](_0x96e6x54, {
                    tab: _0x96e6x94["preTab"],
                    content: _0x96e6x94["preContent"],
                    index: _0x96e6x94["preIndex"]
                });
                var _0x96e6xba = (_0x96e6x6["settings"]["orientation"] === _0x96e6x65) ? {"height": ""} : {
                        "height": "",
                        "min-height": "",
                        "overflow": ""
                    };
                _0x96e6x6["$container"]["css"](_0x96e6xba);
                _0x96e6x6["$container"]["removeClass"](_0x96e6x70);
                setTimeout(function () {
                    _0x96e6x6["$contents"]["removeClass"](_0x96e6x4e["classes"]["active"])["eq"](_0x96e6x94["index"])["addClass"](_0x96e6x4e["classes"]["active"]);
                    _0x96e6x6["settings"]["animating"] = false;
                    _0x96e6x6["$contents"]["stop"](true, true);
                });
            }, _0x96e6x94["duration"] + 50);
            return _0x96e6x6;
        }
    };
    _0x96e6x4b["defaults"] = _0x96e6x4b["prototype"]["defaults"];
    _0x96e6x1["fn"]["zozoTabs"] = function (_0x96e6x4d) {
        return this["each"](function () {
            if (_0x96e6x4 == _0x96e6x1(this)["data"](_0x96e6x4e["pluginName"])) {
                var _0x96e6xd6 = new _0x96e6x4b(this, _0x96e6x4d)["init"]();
                _0x96e6x1(this)["data"](_0x96e6x4e["pluginName"], _0x96e6xd6);
            }
            ;
        });
    };
    _0x96e6x2["zozo"]["tabs"] = _0x96e6x4b;
    _0x96e6x1(_0x96e6x3)["ready"](function () {
        _0x96e6x1("[data-role=\x27z-tabs\x27]")["each"](function (_0x96e6x16, _0x96e6x74) {
            if (!_0x96e6x1(_0x96e6x74)["zozoTabs"]()) {
                _0x96e6x1(_0x96e6x74)["zozoTabs"]();
            }
            ;
        });
    });
})(jQuery, window, document);

/*
 *  jQuery OwlCarousel v1.3.3
 *
 *  Copyright (c) 2013 Bartosz Wojciechowski
 *  http://www.owlgraphic.com/owlcarousel/
 *
 *  Licensed under MIT
 *
 */

/*JS Lint helpers: */
/*global dragMove: false, dragEnd: false, $, jQuery, alert, window, document */
/*jslint nomen: true, continue:true */

if (typeof Object.create !== "function") {
    'use strict';

    Object.create = function (obj) {
        function F() {
        }

        F.prototype = obj;
        return new F();
    };
}
(function ($, window, document) {

    var Carousel = {
        init: function (options, el) {
            var base = this;

            base.$elem = $(el);
            base.options = $.extend({}, $.fn.owlCarousel.options, base.$elem.data(), options);

            base.userOptions = options;
            base.loadContent();
        },

        loadContent: function () {
            var base = this, url;

            function getData(data) {
                var i, content = "";
                if (typeof base.options.jsonSuccess === "function") {
                    base.options.jsonSuccess.apply(this, [data]);
                } else {
                    for (i in data.owl) {
                        if (data.owl.hasOwnProperty(i)) {
                            content += data.owl[i].item;
                        }
                    }
                    base.$elem.html(content);
                }
                base.logIn();
            }

            if (typeof base.options.beforeInit === "function") {
                base.options.beforeInit.apply(this, [base.$elem]);
            }

            if (typeof base.options.jsonPath === "string") {
                url = base.options.jsonPath;
                $.getJSON(url, getData);
            } else {
                base.logIn();
            }
        },

        logIn: function () {
            var base = this;

            base.$elem.data("owl-originalStyles", base.$elem.attr("style"));
            base.$elem.data("owl-originalClasses", base.$elem.attr("class"));

            base.$elem.css({opacity: 0});
            base.orignalItems = base.options.items;
            base.checkBrowser();
            base.wrapperWidth = 0;
            base.checkVisible = null;
            base.setVars();
        },

        setVars: function () {
            var base = this;
            if (base.$elem.children().length === 0) {
                return false;
            }
            base.baseClass();
            base.eventTypes();
            base.$userItems = base.$elem.children();
            base.itemsAmount = base.$userItems.length;
            base.wrapItems();
            base.$owlItems = base.$elem.find(".owl-item");
            base.$owlWrapper = base.$elem.find(".owl-wrapper");
            base.playDirection = "next";
            base.prevItem = 0;
            base.prevArr = [0];
            base.currentItem = 0;
            base.customEvents();
            base.onStartup();
        },

        onStartup: function () {
            var base = this;
            base.updateItems();
            base.calculateAll();
            base.buildControls();
            base.updateControls();
            base.response();
            base.moveEvents();
            base.stopOnHover();
            base.owlStatus();

            if (base.options.transitionStyle !== false) {
                base.transitionTypes(base.options.transitionStyle);
            }
            if (base.options.autoPlay === true) {
                base.options.autoPlay = 5000;
            }
            base.play();

            base.$elem.find(".owl-wrapper").css("display", "block");

            if (!base.$elem.is(":visible")) {
                base.watchVisibility();
            } else {
                base.$elem.css("opacity", 1);
            }
            base.onstartup = false;
            base.eachMoveUpdate();
            if (typeof base.options.afterInit === "function") {
                base.options.afterInit.apply(this, [base.$elem]);
            }
        },

        eachMoveUpdate: function () {
            var base = this;

            if (base.options.lazyLoad === true) {
                base.lazyLoad();
            }
            if (base.options.autoHeight === true) {
                base.autoHeight();
            }
            base.onVisibleItems();

            if (typeof base.options.afterAction === "function") {
                base.options.afterAction.apply(this, [base.$elem]);
            }
        },

        updateVars: function () {
            var base = this;
            if (typeof base.options.beforeUpdate === "function") {
                base.options.beforeUpdate.apply(this, [base.$elem]);
            }
            base.watchVisibility();
            base.updateItems();
            base.calculateAll();
            base.updatePosition();
            base.updateControls();
            base.eachMoveUpdate();
            if (typeof base.options.afterUpdate === "function") {
                base.options.afterUpdate.apply(this, [base.$elem]);
            }
        },

        reload: function () {
            var base = this;
            window.setTimeout(function () {
                base.updateVars();
            }, 0);
        },

        watchVisibility: function () {
            var base = this;

            if (base.$elem.is(":visible") === false) {
                base.$elem.css({opacity: 0});
                window.clearInterval(base.autoPlayInterval);
                window.clearInterval(base.checkVisible);
            } else {
                return false;
            }
            base.checkVisible = window.setInterval(function () {
                if (base.$elem.is(":visible")) {
                    base.reload();
                    base.$elem.animate({opacity: 1}, 200);
                    window.clearInterval(base.checkVisible);
                }
            }, 500);
        },

        wrapItems: function () {
            var base = this;
            base.$userItems.wrapAll("<div class=\"owl-wrapper\">").wrap("<div class=\"owl-item\"></div>");
            base.$elem.find(".owl-wrapper").wrap("<div class=\"owl-wrapper-outer\">");
            base.wrapperOuter = base.$elem.find(".owl-wrapper-outer");
            base.$elem.css("display", "block");
        },

        baseClass: function () {
            var base = this,
                hasBaseClass = base.$elem.hasClass(base.options.baseClass),
                hasThemeClass = base.$elem.hasClass(base.options.theme);

            if (!hasBaseClass) {
                base.$elem.addClass(base.options.baseClass);
            }

            if (!hasThemeClass) {
                base.$elem.addClass(base.options.theme);
            }
        },

        updateItems: function () {
            var base = this, width, i;

            if (base.options.responsive === false) {
                return false;
            }
            if (base.options.singleItem === true) {
                base.options.items = base.orignalItems = 1;
                base.options.itemsCustom = false;
                base.options.itemsDesktop = false;
                base.options.itemsDesktopSmall = false;
                base.options.itemsTablet = false;
                base.options.itemsTabletSmall = false;
                base.options.itemsMobile = false;
                return false;
            }

            width = $(base.options.responsiveBaseWidth).width();

            if (width > (base.options.itemsDesktop[0] || base.orignalItems)) {
                base.options.items = base.orignalItems;
            }
            if (base.options.itemsCustom !== false) {
                //Reorder array by screen size
                base.options.itemsCustom.sort(function (a, b) {
                    return a[0] - b[0];
                });

                for (i = 0; i < base.options.itemsCustom.length; i += 1) {
                    if (base.options.itemsCustom[i][0] <= width) {
                        base.options.items = base.options.itemsCustom[i][1];
                    }
                }

            } else {

                if (width <= base.options.itemsDesktop[0] && base.options.itemsDesktop !== false) {
                    base.options.items = base.options.itemsDesktop[1];
                }

                if (width <= base.options.itemsDesktopSmall[0] && base.options.itemsDesktopSmall !== false) {
                    base.options.items = base.options.itemsDesktopSmall[1];
                }

                if (width <= base.options.itemsTablet[0] && base.options.itemsTablet !== false) {
                    base.options.items = base.options.itemsTablet[1];
                }

                if (width <= base.options.itemsTabletSmall[0] && base.options.itemsTabletSmall !== false) {
                    base.options.items = base.options.itemsTabletSmall[1];
                }

                if (width <= base.options.itemsMobile[0] && base.options.itemsMobile !== false) {
                    base.options.items = base.options.itemsMobile[1];
                }
            }

            //if number of items is less than declared
            if (base.options.items > base.itemsAmount && base.options.itemsScaleUp === true) {
                base.options.items = base.itemsAmount;
            }
        },

        response: function () {
            var base = this,
                smallDelay,
                lastWindowWidth;

            if (base.options.responsive !== true) {
                return false;
            }
            lastWindowWidth = $(window).width();

            base.resizer = function () {
                if ($(window).width() !== lastWindowWidth) {
                    if (base.options.autoPlay !== false) {
                        window.clearInterval(base.autoPlayInterval);
                    }
                    window.clearTimeout(smallDelay);
                    smallDelay = window.setTimeout(function () {
                        lastWindowWidth = $(window).width();
                        base.updateVars();
                    }, base.options.responsiveRefreshRate);
                }
            };
            $(window).resize(base.resizer);
        },

        updatePosition: function () {
            var base = this;
            base.jumpTo(base.currentItem);
            if (base.options.autoPlay !== false) {
                base.checkAp();
            }
        },

        appendItemsSizes: function () {
            var base = this,
                roundPages = 0,
                lastItem = base.itemsAmount - base.options.items;

            base.$owlItems.each(function (index) {
                var $this = $(this);
                $this
                    .css({"width": base.itemWidth})
                    .data("owl-item", Number(index));

                if (index % base.options.items === 0 || index === lastItem) {
                    if (!(index > lastItem)) {
                        roundPages += 1;
                    }
                }
                $this.data("owl-roundPages", roundPages);
            });
        },

        appendWrapperSizes: function () {
            var base = this,
                width = base.$owlItems.length * base.itemWidth;

            base.$owlWrapper.css({
                "width": width * 2,
                "left": 0
            });
            base.appendItemsSizes();
        },

        calculateAll: function () {
            var base = this;
            base.calculateWidth();
            base.appendWrapperSizes();
            base.loops();
            base.max();
        },

        calculateWidth: function () {
            var base = this;
            base.itemWidth = Math.round(base.$elem.width() / base.options.items);
        },

        max: function () {
            var base = this,
                maximum = ((base.itemsAmount * base.itemWidth) - base.options.items * base.itemWidth) * -1;
            if (base.options.items > base.itemsAmount) {
                base.maximumItem = 0;
                maximum = 0;
                base.maximumPixels = 0;
            } else {
                base.maximumItem = base.itemsAmount - base.options.items;
                base.maximumPixels = maximum;
            }
            return maximum;
        },

        min: function () {
            return 0;
        },

        loops: function () {
            var base = this,
                prev = 0,
                elWidth = 0,
                i,
                item,
                roundPageNum;

            base.positionsInArray = [0];
            base.pagesInArray = [];

            for (i = 0; i < base.itemsAmount; i += 1) {
                elWidth += base.itemWidth;
                base.positionsInArray.push(-elWidth);

                if (base.options.scrollPerPage === true) {
                    item = $(base.$owlItems[i]);
                    roundPageNum = item.data("owl-roundPages");
                    if (roundPageNum !== prev) {
                        base.pagesInArray[prev] = base.positionsInArray[i];
                        prev = roundPageNum;
                    }
                }
            }
        },

        buildControls: function () {
            var base = this;
            if (base.options.navigation === true || base.options.pagination === true) {
                base.owlControls = $("<div class=\"owl-controls\"/>").toggleClass("clickable", !base.browser.isTouch).appendTo(base.$elem);
            }
            if (base.options.pagination === true) {
                base.buildPagination();
            }
            if (base.options.navigation === true) {
                base.buildButtons();
            }
        },

        buildButtons: function () {
            var base = this,
                buttonsWrapper = $("<div class=\"owl-buttons\"/>");
            base.owlControls.append(buttonsWrapper);

            base.buttonPrev = $("<div/>", {
                "class": "owl-prev",
                "html": base.options.navigationText[0] || ""
            });

            base.buttonNext = $("<div/>", {
                "class": "owl-next",
                "html": base.options.navigationText[1] || ""
            });

            buttonsWrapper
                .append(base.buttonPrev)
                .append(base.buttonNext);

            buttonsWrapper.on("touchstart.owlControls mousedown.owlControls", "div[class^=\"owl\"]", function (event) {
                event.preventDefault();
            });

            buttonsWrapper.on("touchend.owlControls mouseup.owlControls", "div[class^=\"owl\"]", function (event) {
                event.preventDefault();
                if ($(this).hasClass("owl-next")) {
                    base.next();
                } else {
                    base.prev();
                }
            });
        },

        buildPagination: function () {
            var base = this;

            base.paginationWrapper = $("<div class=\"owl-pagination\"/>");
            base.owlControls.append(base.paginationWrapper);

            base.paginationWrapper.on("touchend.owlControls mouseup.owlControls", ".owl-page", function (event) {
                event.preventDefault();
                if (Number($(this).data("owl-page")) !== base.currentItem) {
                    base.goTo(Number($(this).data("owl-page")), true);
                }
            });
        },

        updatePagination: function () {
            var base = this,
                counter,
                lastPage,
                lastItem,
                i,
                paginationButton,
                paginationButtonInner;

            if (base.options.pagination === false) {
                return false;
            }

            base.paginationWrapper.html("");

            counter = 0;
            lastPage = base.itemsAmount - base.itemsAmount % base.options.items;

            for (i = 0; i < base.itemsAmount; i += 1) {
                if (i % base.options.items === 0) {
                    counter += 1;
                    if (lastPage === i) {
                        lastItem = base.itemsAmount - base.options.items;
                    }
                    paginationButton = $("<div/>", {
                        "class": "owl-page"
                    });
                    paginationButtonInner = $("<span></span>", {
                        "text": base.options.paginationNumbers === true ? counter : "",
                        "class": base.options.paginationNumbers === true ? "owl-numbers" : ""
                    });
                    paginationButton.append(paginationButtonInner);

                    paginationButton.data("owl-page", lastPage === i ? lastItem : i);
                    paginationButton.data("owl-roundPages", counter);

                    base.paginationWrapper.append(paginationButton);
                }
            }
            base.checkPagination();
        },
        checkPagination: function () {
            var base = this;
            if (base.options.pagination === false) {
                return false;
            }
            base.paginationWrapper.find(".owl-page").each(function () {
                if ($(this).data("owl-roundPages") === $(base.$owlItems[base.currentItem]).data("owl-roundPages")) {
                    base.paginationWrapper
                        .find(".owl-page")
                        .removeClass("active");
                    $(this).addClass("active");
                }
            });
        },

        checkNavigation: function () {
            var base = this;

            if (base.options.navigation === false) {
                return false;
            }
            if (base.options.rewindNav === false) {
                if (base.currentItem === 0 && base.maximumItem === 0) {
                    base.buttonPrev.addClass("disabled");
                    base.buttonNext.addClass("disabled");
                } else if (base.currentItem === 0 && base.maximumItem !== 0) {
                    base.buttonPrev.addClass("disabled");
                    base.buttonNext.removeClass("disabled");
                } else if (base.currentItem === base.maximumItem) {
                    base.buttonPrev.removeClass("disabled");
                    base.buttonNext.addClass("disabled");
                } else if (base.currentItem !== 0 && base.currentItem !== base.maximumItem) {
                    base.buttonPrev.removeClass("disabled");
                    base.buttonNext.removeClass("disabled");
                }
            }
        },

        updateControls: function () {
            var base = this;
            base.updatePagination();
            base.checkNavigation();
            if (base.owlControls) {
                if (base.options.items >= base.itemsAmount) {
                    base.owlControls.hide();
                } else {
                    base.owlControls.show();
                }
            }
        },

        destroyControls: function () {
            var base = this;
            if (base.owlControls) {
                base.owlControls.remove();
            }
        },

        next: function (speed) {
            var base = this;

            if (base.isTransition) {
                return false;
            }

            base.currentItem += base.options.scrollPerPage === true ? base.options.items : 1;
            if (base.currentItem > base.maximumItem + (base.options.scrollPerPage === true ? (base.options.items - 1) : 0)) {
                if (base.options.rewindNav === true) {
                    base.currentItem = 0;
                    speed = "rewind";
                } else {
                    base.currentItem = base.maximumItem;
                    return false;
                }
            }
            base.goTo(base.currentItem, speed);
        },

        prev: function (speed) {
            var base = this;

            if (base.isTransition) {
                return false;
            }

            if (base.options.scrollPerPage === true && base.currentItem > 0 && base.currentItem < base.options.items) {
                base.currentItem = 0;
            } else {
                base.currentItem -= base.options.scrollPerPage === true ? base.options.items : 1;
            }
            if (base.currentItem < 0) {
                if (base.options.rewindNav === true) {
                    base.currentItem = base.maximumItem;
                    speed = "rewind";
                } else {
                    base.currentItem = 0;
                    return false;
                }
            }
            base.goTo(base.currentItem, speed);
        },

        goTo: function (position, speed, drag) {
            var base = this,
                goToPixel;

            if (base.isTransition) {
                return false;
            }
            if (typeof base.options.beforeMove === "function") {
                base.options.beforeMove.apply(this, [base.$elem]);
            }
            if (position >= base.maximumItem) {
                position = base.maximumItem;
            } else if (position <= 0) {
                position = 0;
            }

            base.currentItem = base.owl.currentItem = position;
            if (base.options.transitionStyle !== false && drag !== "drag" && base.options.items === 1 && base.browser.support3d === true) {
                base.swapSpeed(0);
                if (base.browser.support3d === true) {
                    base.transition3d(base.positionsInArray[position]);
                } else {
                    base.css2slide(base.positionsInArray[position], 1);
                }
                base.afterGo();
                base.singleItemTransition();
                return false;
            }
            goToPixel = base.positionsInArray[position];

            if (base.browser.support3d === true) {
                base.isCss3Finish = false;

                if (speed === true) {
                    base.swapSpeed("paginationSpeed");
                    window.setTimeout(function () {
                        base.isCss3Finish = true;
                    }, base.options.paginationSpeed);

                } else if (speed === "rewind") {
                    base.swapSpeed(base.options.rewindSpeed);
                    window.setTimeout(function () {
                        base.isCss3Finish = true;
                    }, base.options.rewindSpeed);

                } else {
                    base.swapSpeed("slideSpeed");
                    window.setTimeout(function () {
                        base.isCss3Finish = true;
                    }, base.options.slideSpeed);
                }
                base.transition3d(goToPixel);
            } else {
                if (speed === true) {
                    base.css2slide(goToPixel, base.options.paginationSpeed);
                } else if (speed === "rewind") {
                    base.css2slide(goToPixel, base.options.rewindSpeed);
                } else {
                    base.css2slide(goToPixel, base.options.slideSpeed);
                }
            }
            base.afterGo();
        },

        jumpTo: function (position) {
            var base = this;
            if (typeof base.options.beforeMove === "function") {
                base.options.beforeMove.apply(this, [base.$elem]);
            }
            if (position >= base.maximumItem || position === -1) {
                position = base.maximumItem;
            } else if (position <= 0) {
                position = 0;
            }
            base.swapSpeed(0);
            if (base.browser.support3d === true) {
                base.transition3d(base.positionsInArray[position]);
            } else {
                base.css2slide(base.positionsInArray[position], 1);
            }
            base.currentItem = base.owl.currentItem = position;
            base.afterGo();
        },

        afterGo: function () {
            var base = this;

            base.prevArr.push(base.currentItem);
            base.prevItem = base.owl.prevItem = base.prevArr[base.prevArr.length - 2];
            base.prevArr.shift(0);

            if (base.prevItem !== base.currentItem) {
                base.checkPagination();
                base.checkNavigation();
                base.eachMoveUpdate();

                if (base.options.autoPlay !== false) {
                    base.checkAp();
                }
            }
            if (typeof base.options.afterMove === "function" && base.prevItem !== base.currentItem) {
                base.options.afterMove.apply(this, [base.$elem]);
            }
        },

        stop: function () {
            var base = this;
            base.apStatus = "stop";
            window.clearInterval(base.autoPlayInterval);
        },

        checkAp: function () {
            var base = this;
            if (base.apStatus !== "stop") {
                base.play();
            }
        },

        play: function () {
            var base = this;
            base.apStatus = "play";
            if (base.options.autoPlay === false) {
                return false;
            }
            window.clearInterval(base.autoPlayInterval);
            base.autoPlayInterval = window.setInterval(function () {
                base.next(true);
            }, base.options.autoPlay);
        },

        swapSpeed: function (action) {
            var base = this;
            if (action === "slideSpeed") {
                base.$owlWrapper.css(base.addCssSpeed(base.options.slideSpeed));
            } else if (action === "paginationSpeed") {
                base.$owlWrapper.css(base.addCssSpeed(base.options.paginationSpeed));
            } else if (typeof action !== "string") {
                base.$owlWrapper.css(base.addCssSpeed(action));
            }
        },

        addCssSpeed: function (speed) {
            return {
                "-webkit-transition": "all " + speed + "ms ease",
                "-moz-transition": "all " + speed + "ms ease",
                "-o-transition": "all " + speed + "ms ease",
                "transition": "all " + speed + "ms ease"
            };
        },

        removeTransition: function () {
            return {
                "-webkit-transition": "",
                "-moz-transition": "",
                "-o-transition": "",
                "transition": ""
            };
        },

        doTranslate: function (pixels) {
            return {
                "-webkit-transform": "translate3d(" + pixels + "px, 0px, 0px)",
                "-moz-transform": "translate3d(" + pixels + "px, 0px, 0px)",
                "-o-transform": "translate3d(" + pixels + "px, 0px, 0px)",
                "-ms-transform": "translate3d(" + pixels + "px, 0px, 0px)",
                "transform": "translate3d(" + pixels + "px, 0px,0px)"
            };
        },

        transition3d: function (value) {
            var base = this;
            base.$owlWrapper.css(base.doTranslate(value));
        },

        css2move: function (value) {
            var base = this;
            base.$owlWrapper.css({"left": value});
        },

        css2slide: function (value, speed) {
            var base = this;

            base.isCssFinish = false;
            base.$owlWrapper.stop(true, true).animate({
                "left": value
            }, {
                duration: speed || base.options.slideSpeed,
                complete: function () {
                    base.isCssFinish = true;
                }
            });
        },

        checkBrowser: function () {
            var base = this,
                translate3D = "translate3d(0px, 0px, 0px)",
                tempElem = document.createElement("div"),
                regex,
                asSupport,
                support3d,
                isTouch;

            tempElem.style.cssText = "  -moz-transform:" + translate3D +
                "; -ms-transform:" + translate3D +
                "; -o-transform:" + translate3D +
                "; -webkit-transform:" + translate3D +
                "; transform:" + translate3D;
            regex = /translate3d\(0px, 0px, 0px\)/g;
            asSupport = tempElem.style.cssText.match(regex);
            support3d = (asSupport !== null && asSupport.length === 1);

            isTouch = "ontouchstart" in window || window.navigator.msMaxTouchPoints;

            base.browser = {
                "support3d": support3d,
                "isTouch": isTouch
            };
        },

        moveEvents: function () {
            var base = this;
            if (base.options.mouseDrag !== false || base.options.touchDrag !== false) {
                base.gestures();
                base.disabledEvents();
            }
        },

        eventTypes: function () {
            var base = this,
                types = ["s", "e", "x"];

            base.ev_types = {};

            if (base.options.mouseDrag === true && base.options.touchDrag === true) {
                types = [
                    "touchstart.owl mousedown.owl",
                    "touchmove.owl mousemove.owl",
                    "touchend.owl touchcancel.owl mouseup.owl"
                ];
            } else if (base.options.mouseDrag === false && base.options.touchDrag === true) {
                types = [
                    "touchstart.owl",
                    "touchmove.owl",
                    "touchend.owl touchcancel.owl"
                ];
            } else if (base.options.mouseDrag === true && base.options.touchDrag === false) {
                types = [
                    "mousedown.owl",
                    "mousemove.owl",
                    "mouseup.owl"
                ];
            }

            base.ev_types.start = types[0];
            base.ev_types.move = types[1];
            base.ev_types.end = types[2];
        },

        disabledEvents: function () {
            var base = this;
            base.$elem.on("dragstart.owl", function (event) {
                event.preventDefault();
            });
            base.$elem.on("mousedown.disableTextSelect", function (e) {
                return $(e.target).is('input, textarea, select, option');
            });
        },

        gestures: function () {
            /*jslint unparam: true*/
            var base = this,
                locals = {
                    offsetX: 0,
                    offsetY: 0,
                    baseElWidth: 0,
                    relativePos: 0,
                    position: null,
                    minSwipe: null,
                    maxSwipe: null,
                    sliding: null,
                    dargging: null,
                    targetElement: null
                };

            base.isCssFinish = true;

            function getTouches(event) {
                if (event.touches !== undefined) {
                    return {
                        x: event.touches[0].pageX,
                        y: event.touches[0].pageY
                    };
                }

                if (event.touches === undefined) {
                    if (event.pageX !== undefined) {
                        return {
                            x: event.pageX,
                            y: event.pageY
                        };
                    }
                    if (event.pageX === undefined) {
                        return {
                            x: event.clientX,
                            y: event.clientY
                        };
                    }
                }
            }

            function swapEvents(type) {
                if (type === "on") {
                    $(document).on(base.ev_types.move, dragMove);
                    $(document).on(base.ev_types.end, dragEnd);
                } else if (type === "off") {
                    $(document).off(base.ev_types.move);
                    $(document).off(base.ev_types.end);
                }
            }

            function dragStart(event) {
                var ev = event.originalEvent || event || window.event,
                    position;

                if (ev.which === 3) {
                    return false;
                }
                if (base.itemsAmount <= base.options.items) {
                    return;
                }
                if (base.isCssFinish === false && !base.options.dragBeforeAnimFinish) {
                    return false;
                }
                if (base.isCss3Finish === false && !base.options.dragBeforeAnimFinish) {
                    return false;
                }

                if (base.options.autoPlay !== false) {
                    window.clearInterval(base.autoPlayInterval);
                }

                if (base.browser.isTouch !== true && !base.$owlWrapper.hasClass("grabbing")) {
                    base.$owlWrapper.addClass("grabbing");
                }

                base.newPosX = 0;
                base.newRelativeX = 0;

                $(this).css(base.removeTransition());

                position = $(this).position();
                locals.relativePos = position.left;

                locals.offsetX = getTouches(ev).x - position.left;
                locals.offsetY = getTouches(ev).y - position.top;

                swapEvents("on");

                locals.sliding = false;
                locals.targetElement = ev.target || ev.srcElement;
            }

            function dragMove(event) {
                var ev = event.originalEvent || event || window.event,
                    minSwipe,
                    maxSwipe;

                base.newPosX = getTouches(ev).x - locals.offsetX;
                base.newPosY = getTouches(ev).y - locals.offsetY;
                base.newRelativeX = base.newPosX - locals.relativePos;

                if (typeof base.options.startDragging === "function" && locals.dragging !== true && base.newRelativeX !== 0) {
                    locals.dragging = true;
                    base.options.startDragging.apply(base, [base.$elem]);
                }

                if ((base.newRelativeX > 8 || base.newRelativeX < -8) && (base.browser.isTouch === true)) {
                    if (ev.preventDefault !== undefined) {
                        ev.preventDefault();
                    } else {
                        ev.returnValue = false;
                    }
                    locals.sliding = true;
                }

                if ((base.newPosY > 10 || base.newPosY < -10) && locals.sliding === false) {
                    $(document).off("touchmove.owl");
                }

                minSwipe = function () {
                    return base.newRelativeX / 5;
                };

                maxSwipe = function () {
                    return base.maximumPixels + base.newRelativeX / 5;
                };

                base.newPosX = Math.max(Math.min(base.newPosX, minSwipe()), maxSwipe());
                if (base.browser.support3d === true) {
                    base.transition3d(base.newPosX);
                } else {
                    base.css2move(base.newPosX);
                }
            }

            function dragEnd(event) {
                var ev = event.originalEvent || event || window.event,
                    newPosition,
                    handlers,
                    owlStopEvent;

                ev.target = ev.target || ev.srcElement;

                locals.dragging = false;

                if (base.browser.isTouch !== true) {
                    base.$owlWrapper.removeClass("grabbing");
                }

                if (base.newRelativeX < 0) {
                    base.dragDirection = base.owl.dragDirection = "left";
                } else {
                    base.dragDirection = base.owl.dragDirection = "right";
                }

                if (base.newRelativeX !== 0) {
                    newPosition = base.getNewPosition();
                    base.goTo(newPosition, false, "drag");
                    if (locals.targetElement === ev.target && base.browser.isTouch !== true) {
                        $(ev.target).on("click.disable", function (ev) {
                            ev.stopImmediatePropagation();
                            ev.stopPropagation();
                            ev.preventDefault();
                            $(ev.target).off("click.disable");
                        });
                        handlers = $._data(ev.target, "events").click;
                        owlStopEvent = handlers.pop();
                        handlers.splice(0, 0, owlStopEvent);
                    }
                }
                swapEvents("off");
            }

            base.$elem.on(base.ev_types.start, ".owl-wrapper", dragStart);
        },

        getNewPosition: function () {
            var base = this,
                newPosition = base.closestItem();

            if (newPosition > base.maximumItem) {
                base.currentItem = base.maximumItem;
                newPosition = base.maximumItem;
            } else if (base.newPosX >= 0) {
                newPosition = 0;
                base.currentItem = 0;
            }
            return newPosition;
        },
        closestItem: function () {
            var base = this,
                array = base.options.scrollPerPage === true ? base.pagesInArray : base.positionsInArray,
                goal = base.newPosX,
                closest = null;

            $.each(array, function (i, v) {
                if (goal - (base.itemWidth / 20) > array[i + 1] && goal - (base.itemWidth / 20) < v && base.moveDirection() === "left") {
                    closest = v;
                    if (base.options.scrollPerPage === true) {
                        base.currentItem = $.inArray(closest, base.positionsInArray);
                    } else {
                        base.currentItem = i;
                    }
                } else if (goal + (base.itemWidth / 20) < v && goal + (base.itemWidth / 20) > (array[i + 1] || array[i] - base.itemWidth) && base.moveDirection() === "right") {
                    if (base.options.scrollPerPage === true) {
                        closest = array[i + 1] || array[array.length - 1];
                        base.currentItem = $.inArray(closest, base.positionsInArray);
                    } else {
                        closest = array[i + 1];
                        base.currentItem = i + 1;
                    }
                }
            });
            return base.currentItem;
        },

        moveDirection: function () {
            var base = this,
                direction;
            if (base.newRelativeX < 0) {
                direction = "right";
                base.playDirection = "next";
            } else {
                direction = "left";
                base.playDirection = "prev";
            }
            return direction;
        },

        customEvents: function () {
            /*jslint unparam: true*/
            var base = this;
            base.$elem.on("owl.next", function () {
                base.next();
            });
            base.$elem.on("owl.prev", function () {
                base.prev();
            });
            base.$elem.on("owl.play", function (event, speed) {
                base.options.autoPlay = speed;
                base.play();
                base.hoverStatus = "play";
            });
            base.$elem.on("owl.stop", function () {
                base.stop();
                base.hoverStatus = "stop";
            });
            base.$elem.on("owl.goTo", function (event, item) {
                base.goTo(item);
            });
            base.$elem.on("owl.jumpTo", function (event, item) {
                base.jumpTo(item);
            });
        },

        stopOnHover: function () {
            var base = this;
            if (base.options.stopOnHover === true && base.browser.isTouch !== true && base.options.autoPlay !== false) {
                base.$elem.on("mouseover", function () {
                    base.stop();
                });
                base.$elem.on("mouseout", function () {
                    if (base.hoverStatus !== "stop") {
                        base.play();
                    }
                });
            }
        },

        lazyLoad: function () {
            var base = this,
                i,
                $item,
                itemNumber,
                $lazyImg,
                follow;

            if (base.options.lazyLoad === false) {
                return false;
            }
            for (i = 0; i < base.itemsAmount; i += 1) {
                $item = $(base.$owlItems[i]);

                if ($item.data("owl-loaded") === "loaded") {
                    continue;
                }

                itemNumber = $item.data("owl-item");
                $lazyImg = $item.find(".lazyOwl");

                if (typeof $lazyImg.data("src") !== "string") {
                    $item.data("owl-loaded", "loaded");
                    continue;
                }
                if ($item.data("owl-loaded") === undefined) {
                    $lazyImg.hide();
                    $item.addClass("loading").data("owl-loaded", "checked");
                }
                if (base.options.lazyFollow === true) {
                    follow = itemNumber >= base.currentItem;
                } else {
                    follow = true;
                }
                if (follow && itemNumber < base.currentItem + base.options.items && $lazyImg.length) {
                    base.lazyPreload($item, $lazyImg);
                }
            }
        },

        lazyPreload: function ($item, $lazyImg) {
            var base = this,
                iterations = 0,
                isBackgroundImg;

            if ($lazyImg.prop("tagName") === "DIV") {
                $lazyImg.css("background-image", "url(" + $lazyImg.data("src") + ")");
                isBackgroundImg = true;
            } else {
                $lazyImg[0].src = $lazyImg.data("src");
            }

            function showImage() {
                $item.data("owl-loaded", "loaded").removeClass("loading");
                $lazyImg.removeAttr("data-src");
                if (base.options.lazyEffect === "fade") {
                    $lazyImg.fadeIn(400);
                } else {
                    $lazyImg.show();
                }
                if (typeof base.options.afterLazyLoad === "function") {
                    base.options.afterLazyLoad.apply(this, [base.$elem]);
                }
            }

            function checkLazyImage() {
                iterations += 1;
                if (base.completeImg($lazyImg.get(0)) || isBackgroundImg === true) {
                    showImage();
                } else if (iterations <= 100) {//if image loads in less than 10 seconds 
                    window.setTimeout(checkLazyImage, 100);
                } else {
                    showImage();
                }
            }

            checkLazyImage();
        },

        autoHeight: function () {
            var base = this,
                $currentimg = $(base.$owlItems[base.currentItem]).find("img"),
                iterations;

            function addHeight() {
                var $currentItem = $(base.$owlItems[base.currentItem]).height();
                base.wrapperOuter.css("height", $currentItem + "px");
                if (!base.wrapperOuter.hasClass("autoHeight")) {
                    window.setTimeout(function () {
                        base.wrapperOuter.addClass("autoHeight");
                    }, 0);
                }
            }

            function checkImage() {
                iterations += 1;
                if (base.completeImg($currentimg.get(0))) {
                    addHeight();
                } else if (iterations <= 100) { //if image loads in less than 10 seconds 
                    window.setTimeout(checkImage, 100);
                } else {
                    base.wrapperOuter.css("height", ""); //Else remove height attribute
                }
            }

            if ($currentimg.get(0) !== undefined) {
                iterations = 0;
                checkImage();
            } else {
                addHeight();
            }
        },

        completeImg: function (img) {
            var naturalWidthType;

            if (!img.complete) {
                return false;
            }
            naturalWidthType = typeof img.naturalWidth;
            if (naturalWidthType !== "undefined" && img.naturalWidth === 0) {
                return false;
            }
            return true;
        },

        onVisibleItems: function () {
            var base = this,
                i;

            if (base.options.addClassActive === true) {
                base.$owlItems.removeClass("active");
            }
            base.visibleItems = [];
            for (i = base.currentItem; i < base.currentItem + base.options.items; i += 1) {
                base.visibleItems.push(i);

                if (base.options.addClassActive === true) {
                    $(base.$owlItems[i]).addClass("active");
                }
            }
            base.owl.visibleItems = base.visibleItems;
        },

        transitionTypes: function (className) {
            var base = this;
            //Currently available: "fade", "backSlide", "goDown", "fadeUp"
            base.outClass = "owl-" + className + "-out";
            base.inClass = "owl-" + className + "-in";
        },

        singleItemTransition: function () {
            var base = this,
                outClass = base.outClass,
                inClass = base.inClass,
                $currentItem = base.$owlItems.eq(base.currentItem),
                $prevItem = base.$owlItems.eq(base.prevItem),
                prevPos = Math.abs(base.positionsInArray[base.currentItem]) + base.positionsInArray[base.prevItem],
                origin = Math.abs(base.positionsInArray[base.currentItem]) + base.itemWidth / 2,
                animEnd = 'webkitAnimationEnd oAnimationEnd MSAnimationEnd animationend';

            base.isTransition = true;

            base.$owlWrapper
                .addClass('owl-origin')
                .css({
                    "-webkit-transform-origin": origin + "px",
                    "-moz-perspective-origin": origin + "px",
                    "perspective-origin": origin + "px"
                });
            function transStyles(prevPos) {
                return {
                    "position": "relative",
                    "left": prevPos + "px"
                };
            }

            $prevItem
                .css(transStyles(prevPos, 10))
                .addClass(outClass)
                .on(animEnd, function () {
                    base.endPrev = true;
                    $prevItem.off(animEnd);
                    base.clearTransStyle($prevItem, outClass);
                });

            $currentItem
                .addClass(inClass)
                .on(animEnd, function () {
                    base.endCurrent = true;
                    $currentItem.off(animEnd);
                    base.clearTransStyle($currentItem, inClass);
                });
        },

        clearTransStyle: function (item, classToRemove) {
            var base = this;
            item.css({
                "position": "",
                "left": ""
            }).removeClass(classToRemove);

            if (base.endPrev && base.endCurrent) {
                base.$owlWrapper.removeClass('owl-origin');
                base.endPrev = false;
                base.endCurrent = false;
                base.isTransition = false;
            }
        },

        owlStatus: function () {
            var base = this;
            base.owl = {
                "userOptions": base.userOptions,
                "baseElement": base.$elem,
                "userItems": base.$userItems,
                "owlItems": base.$owlItems,
                "currentItem": base.currentItem,
                "prevItem": base.prevItem,
                "visibleItems": base.visibleItems,
                "isTouch": base.browser.isTouch,
                "browser": base.browser,
                "dragDirection": base.dragDirection
            };
        },

        clearEvents: function () {
            var base = this;
            base.$elem.off(".owl owl mousedown.disableTextSelect");
            $(document).off(".owl owl");
            $(window).off("resize", base.resizer);
        },

        unWrap: function () {
            var base = this;
            if (base.$elem.children().length !== 0) {
                base.$owlWrapper.unwrap();
                base.$userItems.unwrap().unwrap();
                if (base.owlControls) {
                    base.owlControls.remove();
                }
            }
            base.clearEvents();
            base.$elem
                .attr("style", base.$elem.data("owl-originalStyles") || "")
                .attr("class", base.$elem.data("owl-originalClasses"));
        },

        destroy: function () {
            var base = this;
            base.stop();
            window.clearInterval(base.checkVisible);
            base.unWrap();
            base.$elem.removeData();
        },

        reinit: function (newOptions) {
            var base = this,
                options = $.extend({}, base.userOptions, newOptions);
            base.unWrap();
            base.init(options, base.$elem);
        },

        addItem: function (htmlString, targetPosition) {
            var base = this,
                position;

            if (!htmlString) {
                return false;
            }

            if (base.$elem.children().length === 0) {
                base.$elem.append(htmlString);
                base.setVars();
                return false;
            }
            base.unWrap();
            if (targetPosition === undefined || targetPosition === -1) {
                position = -1;
            } else {
                position = targetPosition;
            }
            if (position >= base.$userItems.length || position === -1) {
                base.$userItems.eq(-1).after(htmlString);
            } else {
                base.$userItems.eq(position).before(htmlString);
            }

            base.setVars();
        },

        removeItem: function (targetPosition) {
            var base = this,
                position;

            if (base.$elem.children().length === 0) {
                return false;
            }
            if (targetPosition === undefined || targetPosition === -1) {
                position = -1;
            } else {
                position = targetPosition;
            }

            base.unWrap();
            base.$userItems.eq(position).remove();
            base.setVars();
        }

    };

    $.fn.owlCarousel = function (options) {
        return this.each(function () {
            if ($(this).data("owl-init") === true) {
                return false;
            }
            $(this).data("owl-init", true);
            var carousel = Object.create(Carousel);
            carousel.init(options, this);
            $.data(this, "owlCarousel", carousel);
        });
    };

    $.fn.owlCarousel.options = {

        items: 5,
        itemsCustom: false,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [992, 2],
        itemsTablet: [600, 1],
        itemsTabletSmall: false,
        itemsMobile: [479, 1],
        singleItem: false,
        itemsScaleUp: false,

        slideSpeed: 200,
        paginationSpeed: 800,
        rewindSpeed: 1000,

        autoPlay: false,
        stopOnHover: false,

        navigation: false,
        navigationText: ["prev", "next"],
        rewindNav: true,
        scrollPerPage: false,

        pagination: true,
        paginationNumbers: false,

        responsive: true,
        responsiveRefreshRate: 200,
        responsiveBaseWidth: window,

        baseClass: "owl-carousel",
        theme: "owl-theme",

        lazyLoad: false,
        lazyFollow: true,
        lazyEffect: "fade",

        autoHeight: false,

        jsonPath: false,
        jsonSuccess: false,

        dragBeforeAnimFinish: true,
        mouseDrag: true,
        touchDrag: true,

        addClassActive: false,
        transitionStyle: false,

        beforeUpdate: false,
        afterUpdate: false,
        beforeInit: false,
        afterInit: false,
        beforeMove: false,
        afterMove: false,
        afterAction: false,
        startDragging: false,
        afterLazyLoad: false
    };
}(jQuery, window, document));

!function(e){"use strict"; function n(){l=!0,d.$wndw=e(window),d.$html=e("html"),d.$body=e("body"),e.each([i,a,o],function(e,n){n.add=function(e){e=e.split(" ");for(var t in e)n[e[t]]=n.mm(e[t])}}),i.mm=function(e){return"mm-"+e},i.add("wrapper menu inline panel nopanel list nolist subtitle selected label spacer current highest hidden opened subopened subopen fullsubopen subclose"),i.umm=function(e){return"mm-"==e.slice(0,3)&&(e=e.slice(3)),e},a.mm=function(e){return"mm-"+e},a.add("parent"),o.mm=function(e){return e+".mm"},o.add("toggle open close setSelected transitionend webkitTransitionEnd mousedown mouseup touchstart touchmove touchend scroll resize click keydown keyup"),e[t]._c=i,e[t]._d=a,e[t]._e=o,e[t].glbl=d}var t="mmenu",s="4.7.5";if(!e[t]){var i={},a={},o={},l=!1,d={$wndw:null,$html:null,$body:null};e[t]=function(n,s,i){this.$menu=n,this.opts=s,this.conf=i,this.vars={},"function"==typeof this.___deprecated&&this.___deprecated(),this._initMenu(),this._initAnchors(),this._initEvents();var a=this.$menu.children(this.conf.panelNodetype);for(var o in e[t].addons)e[t].addons[o]._add.call(this),e[t].addons[o]._add=function(){},e[t].addons[o]._setup.call(this);return this._init(a),"function"==typeof this.___debug&&this.___debug(),this},e[t].version=s,e[t].addons={},e[t].uniqueId=0,e[t].defaults={classes:"",slidingSubmenus:!0,onClick:{setSelected:!0}},e[t].configuration={panelNodetype:"ul, ol, div",transitionDuration:400,openingInterval:25,classNames:{panel:"Panel",selected:"Selected",label:"Label",spacer:"Spacer"}},e[t].prototype={_init:function(n){n=n.not("."+i.nopanel),n=this._initPanels(n);for(var s in e[t].addons)e[t].addons[s]._init.call(this,n);this._update()},_initMenu:function(){this.opts.offCanvas&&this.conf.clone&&(this.$menu=this.$menu.clone(!0),this.$menu.add(this.$menu.find("*")).filter("[id]").each(function(){e(this).attr("id",i.mm(e(this).attr("id")))})),this.$menu.contents().each(function(){3==e(this)[0].nodeType&&e(this).remove()}),this.$menu.parent().addClass(i.wrapper);var n=[i.menu];n.push(i.mm(this.opts.slidingSubmenus?"horizontal":"vertical")),this.opts.classes&&n.push(this.opts.classes),this.$menu.addClass(n.join(" "))},_initPanels:function(n){var t=this;this.__findAddBack(n,"ul, ol").not("."+i.nolist).addClass(i.list);var s=this.__findAddBack(n,"."+i.list).find("> li");this.__refactorClass(s,this.conf.classNames.selected,"selected"),this.__refactorClass(s,this.conf.classNames.label,"label"),this.__refactorClass(s,this.conf.classNames.spacer,"spacer"),s.off(o.setSelected).on(o.setSelected,function(n,t){n.stopPropagation(),s.removeClass(i.selected),"boolean"!=typeof t&&(t=!0),t&&e(this).addClass(i.selected)}),this.__refactorClass(this.__findAddBack(n,"."+this.conf.classNames.panel),this.conf.classNames.panel,"panel"),n.add(this.__findAddBack(n,"."+i.list).children().children().filter(this.conf.panelNodetype).not("."+i.nopanel)).addClass(i.panel);var l=this.__findAddBack(n,"."+i.panel),d=e("."+i.panel,this.$menu);if(l.each(function(){var n=e(this),s=n.attr("id")||t.__getUniqueId();n.attr("id",s)}),l.each(function(){var n=e(this),s=n.is("ul, ol")?n:n.find("ul ,ol").first(),o=n.parent(),l=o.children("a, span"),d=o.closest("."+i.panel);if(o.parent().is("."+i.list)&&!n.data(a.parent)){n.data(a.parent,o);var r=e('<a class="'+i.subopen+'" href="#'+n.attr("id")+'" />').insertBefore(l);l.is("a")||r.addClass(i.fullsubopen),t.opts.slidingSubmenus&&s.prepend('<li class="'+i.subtitle+'"><a class="'+i.subclose+'" href="#'+d.attr("id")+'">'+l.text()+"</a></li>")}}),this.opts.slidingSubmenus){var r=this.__findAddBack(n,"."+i.list).find("> li."+i.selected);r.parents("li").removeClass(i.selected).end().add(r.parents("li")).each(function(){var n=e(this),t=n.find("> ."+i.panel);t.length&&(n.parents("."+i.panel).addClass(i.subopened),t.addClass(i.opened))}).closest("."+i.panel).addClass(i.opened).parents("."+i.panel).addClass(i.subopened)}else{var r=e("li."+i.selected,d);r.parents("li").removeClass(i.selected).end().add(r.parents("li")).addClass(i.opened)}var u=d.filter("."+i.opened);return u.length||(u=l.first()),u.addClass(i.opened).last().addClass(i.current),this.opts.slidingSubmenus&&l.not(u.last()).addClass(i.hidden).end().appendTo(this.$menu),l},_initAnchors:function(){var n=this;d.$body.on(o.click,"a",function(s){var a=e(this),l=!1,r=n.$menu.find(a).length;for(var u in e[t].addons)if(e[t].addons[u]._clickAnchor&&(l=e[t].addons[u]._clickAnchor.call(n,a,r)))break;if(!l&&r){var c=a.attr("href")||"";if("#"==c.slice(0,1))try{e(c,n.$menu).is("."+i.panel)&&(l=!0,e(c).trigger(n.opts.slidingSubmenus?o.open:o.toggle))}catch(p){}}if(l&&s.preventDefault(),!l&&r&&a.is("."+i.list+" > li > a")&&!a.is('[rel="external"]')&&!a.is('[target="_blank"]')){n.__valueOrFn(n.opts.onClick.setSelected,a)&&a.parent().trigger(o.setSelected);var h=n.__valueOrFn(n.opts.onClick.preventDefault,a,"#"==c.slice(0,1));h&&s.preventDefault(),n.__valueOrFn(n.opts.onClick.blockUI,a,!h)&&d.$html.addClass(i.blocking),n.__valueOrFn(n.opts.onClick.close,a,h)&&n.$menu.trigger(o.close)}})},_initEvents:function(){var n=this;this.$menu.on(o.toggle+" "+o.open+" "+o.close,"."+i.panel,function(e){e.stopPropagation()}),this.opts.slidingSubmenus?this.$menu.on(o.open,"."+i.panel,function(){return n._openSubmenuHorizontal(e(this))}):this.$menu.on(o.toggle,"."+i.panel,function(){var n=e(this);n.trigger(n.parent().hasClass(i.opened)?o.close:o.open)}).on(o.open,"."+i.panel,function(){e(this).parent().addClass(i.opened)}).on(o.close,"."+i.panel,function(){e(this).parent().removeClass(i.opened)})},_openSubmenuHorizontal:function(n){if(n.hasClass(i.current))return!1;var t=e("."+i.panel,this.$menu),s=t.filter("."+i.current);return t.removeClass(i.highest).removeClass(i.current).not(n).not(s).addClass(i.hidden),n.hasClass(i.opened)?s.addClass(i.highest).removeClass(i.opened).removeClass(i.subopened):(n.addClass(i.highest),s.addClass(i.subopened)),n.removeClass(i.hidden).addClass(i.current),setTimeout(function(){n.removeClass(i.subopened).addClass(i.opened)},this.conf.openingInterval),"open"},_update:function(e){if(this.updates||(this.updates=[]),"function"==typeof e)this.updates.push(e);else for(var n=0,t=this.updates.length;t>n;n++)this.updates[n].call(this,e)},__valueOrFn:function(e,n,t){return"function"==typeof e?e.call(n[0]):"undefined"==typeof e&&"undefined"!=typeof t?t:e},__refactorClass:function(e,n,t){return e.filter("."+n).removeClass(n).addClass(i[t])},__findAddBack:function(e,n){return e.find(n).add(e.filter(n))},__transitionend:function(e,n,t){var s=!1,i=function(){s||n.call(e[0]),s=!0};e.one(o.transitionend,i),e.one(o.webkitTransitionEnd,i),setTimeout(i,1.1*t)},__getUniqueId:function(){return i.mm(e[t].uniqueId++)}},e.fn[t]=function(s,i){return l||n(),s=e.extend(!0,{},e[t].defaults,s),i=e.extend(!0,{},e[t].configuration,i),this.each(function(){var n=e(this);n.data(t)||n.data(t,new e[t](n,s,i))})},e[t].support={touch:"ontouchstart"in window||navigator.msMaxTouchPoints}}}(jQuery);
/*	
 * jQuery mmenu offCanvas addon
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
!function(e){var t="mmenu",o="offCanvas";e[t].addons[o]={_init:function(){},_setup:function(){if(this.opts[o]){var t=this,s=this.opts[o],p=this.conf[o];"string"!=typeof p.pageSelector&&(p.pageSelector="> "+p.pageNodetype),a.$allMenus=(a.$allMenus||e()).add(this.$menu),this.vars.opened=!1;var r=[n.offcanvas];"left"!=s.position&&r.push(n.mm(s.position)),"back"!=s.zposition&&r.push(n.mm(s.zposition)),this.$menu.addClass(r.join(" ")).parent().removeClass(n.wrapper),this.setPage(a.$page),this[o+"_initBlocker"](),this[o+"_initWindow"](),this.$menu.on(i.open+" "+i.opening+" "+i.opened+" "+i.close+" "+i.closing+" "+i.closed+" "+i.setPage,function(e){e.stopPropagation()}).on(i.open+" "+i.close+" "+i.setPage,function(e){t[e.type]()}),this.$menu[p.menuInjectMethod+"To"](p.menuWrapperSelector)}},_add:function(){n=e[t]._c,s=e[t]._d,i=e[t]._e,n.add("offcanvas slideout modal background opening blocker page"),s.add("style"),i.add("opening opened closing closed setPage"),a=e[t].glbl},_clickAnchor:function(e){if(!this.opts[o])return!1;var t=this.$menu.attr("id");if(t&&t.length&&(this.conf.clone&&(t=n.umm(t)),e.is('[href="#'+t+'"]')))return this.open(),!0;if(a.$page){var t=a.$page.attr("id");return t&&t.length&&e.is('[href="#'+t+'"]')?(this.close(),!0):!1}}},e[t].defaults[o]={position:"left",zposition:"back",modal:!1,moveBackground:!0},e[t].configuration[o]={pageNodetype:"div",pageSelector:null,menuWrapperSelector:"body",menuInjectMethod:"prepend"},e[t].prototype.open=function(){if(this.vars.opened)return!1;var e=this;return this._openSetup(),setTimeout(function(){e._openFinish()},this.conf.openingInterval),"open"},e[t].prototype._openSetup=function(){var e=this;a.$allMenus.not(this.$menu).trigger(i.close),a.$page.data(s.style,a.$page.attr("style")||""),a.$wndw.trigger(i.resize,[!0]);var t=[n.opened];this.opts[o].modal&&t.push(n.modal),this.opts[o].moveBackground&&t.push(n.background),"left"!=this.opts[o].position&&t.push(n.mm(this.opts[o].position)),"back"!=this.opts[o].zposition&&t.push(n.mm(this.opts[o].zposition)),this.opts.classes&&t.push(this.opts.classes),a.$html.addClass(t.join(" ")),setTimeout(function(){e.vars.opened=!0},this.conf.openingInterval),this.$menu.addClass(n.current+" "+n.opened)},e[t].prototype._openFinish=function(){var e=this;this.__transitionend(a.$page,function(){e.$menu.trigger(i.opened)},this.conf.transitionDuration),a.$html.addClass(n.opening),this.$menu.trigger(i.opening)},e[t].prototype.close=function(){if(!this.vars.opened)return!1;var e=this;return this.__transitionend(a.$page,function(){e.$menu.removeClass(n.current).removeClass(n.opened),a.$html.removeClass(n.opened).removeClass(n.modal).removeClass(n.background).removeClass(n.mm(e.opts[o].position)).removeClass(n.mm(e.opts[o].zposition)),e.opts.classes&&a.$html.removeClass(e.opts.classes),a.$page.attr("style",a.$page.data(s.style)),e.vars.opened=!1,e.$menu.trigger(i.closed)},this.conf.transitionDuration),a.$html.removeClass(n.opening),this.$menu.trigger(i.closing),"close"},e[t].prototype.setPage=function(t){t||(t=e(this.conf[o].pageSelector,a.$body),t.length>1&&(t=t.wrapAll("<"+this.conf[o].pageNodetype+" />").parent())),t.addClass(n.page+" "+n.slideout),a.$page=t},e[t].prototype[o+"_initWindow"]=function(){a.$wndw.on(i.keydown,function(e){return a.$html.hasClass(n.opened)&&9==e.keyCode?(e.preventDefault(),!1):void 0});var s=0;a.$wndw.on(i.resize,function(e,t){if(t||a.$html.hasClass(n.opened)){var o=a.$wndw.height();(t||o!=s)&&(s=o,a.$page.css("minHeight",o))}}),e[t].prototype[o+"_initWindow"]=function(){}},e[t].prototype[o+"_initBlocker"]=function(){var s=e('<div id="'+n.blocker+'" class="'+n.slideout+'" />').appendTo(a.$body);s.on(i.touchstart,function(e){e.preventDefault(),e.stopPropagation(),s.trigger(i.mousedown)}).on(i.mousedown,function(e){e.preventDefault(),a.$html.hasClass(n.modal)||a.$allMenus.trigger(i.close)}),e[t].prototype[o+"_initBlocker"]=function(){}};var n,s,i,a}(jQuery);
/*	
 * jQuery mmenu buttonbars addon
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
!function(t){var n="mmenu",a="buttonbars";t[n].addons[a]={_init:function(n){this.opts[a],this.conf[a],this.__refactorClass(t("div",n),this.conf.classNames[a].buttonbar,"buttonbar"),t("."+i.buttonbar,n).each(function(){var n=t(this),a=n.children().not("input"),o=n.children().filter("input");n.addClass(i.buttonbar+"-"+a.length),o.each(function(){var n=t(this),i=a.filter('label[for="'+n.attr("id")+'"]');i.length&&n.insertBefore(i)})})},_setup:function(){},_add:function(){i=t[n]._c,o=t[n]._d,r=t[n]._e,i.add("buttonbar"),s=t[n].glbl}},t[n].defaults[a]={},t[n].configuration.classNames[a]={buttonbar:"Buttonbar"};var i,o,r,s}(jQuery);
/*	
 * jQuery mmenu counters addon
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
!function(t){var e="mmenu",n="counters";t[e].addons[n]={_init:function(e){var s=this,d=this.opts[n];this.conf[n],this.__refactorClass(t("em",e),this.conf.classNames[n].counter,"counter"),d.add&&e.each(function(){var e=t(this).data(o.parent);e&&(e.find("> em."+a.counter).length||e.prepend(t('<em class="'+a.counter+'" />')))}),d.update&&e.each(function(){var e=t(this),n=e.data(o.parent);if(n){var d=n.find("> em."+a.counter);d.length&&(e.is("."+a.list)||(e=e.find("> ."+a.list)),e.length&&!e.data(o.updatecounter)&&(e.data(o.updatecounter,!0),s._update(function(){var t=e.children().not("."+a.label).not("."+a.subtitle).not("."+a.hidden).not("."+a.search).not("."+a.noresultsmsg);d.html(t.length)})))}})},_setup:function(){var a=this.opts[n];"boolean"==typeof a&&(a={add:a,update:a}),"object"!=typeof a&&(a={}),a=t.extend(!0,{},t[e].defaults[n],a),this.opts[n]=a},_add:function(){a=t[e]._c,o=t[e]._d,s=t[e]._e,a.add("counter search noresultsmsg"),o.add("updatecounter"),d=t[e].glbl}},t[e].defaults[n]={add:!1,update:!1},t[e].configuration.classNames[n]={counter:"Counter"};var a,o,s,d}(jQuery);
/*	
 * jQuery mmenu dragOpen addon
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
!function(e){function t(e,t,n){return t>e&&(e=t),e>n&&(e=n),e}var n="mmenu",o="dragOpen";e[n].addons[o]={_init:function(){},_setup:function(){if(this.opts.offCanvas){var s=this,p=this.opts[o],d=this.conf[o];if("boolean"==typeof p&&(p={open:p}),"object"!=typeof p&&(p={}),p=e.extend(!0,{},e[n].defaults[o],p),p.open){if(Hammer.VERSION<2)return;var f,c,h,m,u={},g=0,l=!1,v=!1,_=0,w=0;switch(this.opts.offCanvas.position){case"left":case"right":u.events="panleft panright",u.typeLower="x",u.typeUpper="X",v="width";break;case"top":case"bottom":u.events="panup pandown",u.typeLower="y",u.typeUpper="Y",v="height"}switch(this.opts.offCanvas.position){case"left":case"top":u.negative=!1;break;case"right":case"bottom":u.negative=!0}switch(this.opts.offCanvas.position){case"left":u.open_dir="right",u.close_dir="left";break;case"right":u.open_dir="left",u.close_dir="right";break;case"top":u.open_dir="down",u.close_dir="up";break;case"bottom":u.open_dir="up",u.close_dir="down"}var b=this.__valueOrFn(p.pageNode,this.$menu,r.$page);"string"==typeof b&&(b=e(b));var y=r.$page;switch(this.opts.offCanvas.zposition){case"front":y=this.$menu;break;case"next":y=y.add(this.$menu)}var $=new Hammer(b[0],p.vendors.hammer);$.on("panstart",function(e){switch(m=e.center[u.typeLower],s.opts.offCanvas.position){case"right":case"bottom":m>=r.$wndw[v]()-p.maxStartPos&&(g=1);break;default:m<=p.maxStartPos&&(g=1)}l=u.open_dir}).on(u.events+" panend",function(e){g>0&&e.preventDefault()}).on(u.events,function(e){if(f=e["delta"+u.typeUpper],u.negative&&(f=-f),f!=_&&(l=f>=_?u.open_dir:u.close_dir),_=f,_>p.threshold&&1==g){if(r.$html.hasClass(a.opened))return;g=2,s._openSetup(),s.$menu.trigger(i.opening),r.$html.addClass(a.dragging),w=t(r.$wndw[v]()*d[v].perc,d[v].min,d[v].max)}2==g&&(c=t(_,10,w)-("front"==s.opts.offCanvas.zposition?w:0),u.negative&&(c=-c),h="translate"+u.typeUpper+"("+c+"px )",y.css({"-webkit-transform":"-webkit-"+h,transform:h}))}).on("panend",function(){2==g&&(r.$html.removeClass(a.dragging),y.css("transform",""),s[l==u.open_dir?"_openFinish":"close"]()),g=0})}}},_add:function(){return"function"!=typeof Hammer?(e[n].addons[o]._init=function(){},e[n].addons[o]._setup=function(){},void 0):(a=e[n]._c,s=e[n]._d,i=e[n]._e,a.add("dragging"),r=e[n].glbl,void 0)}},e[n].defaults[o]={open:!1,maxStartPos:100,threshold:50,vendors:{hammer:{}}},e[n].configuration[o]={width:{perc:.8,min:140,max:440},height:{perc:.8,min:140,max:880}};var a,s,i,r}(jQuery);
/*	
 * jQuery mmenu fixedElements addon
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
!function(o){var t="mmenu",d="fixedElements";o[t].addons[d]={_init:function(){if(this.opts.offCanvas){var o=this.conf.classNames[d].fixedTop,t=this.conf.classNames[d].fixedBottom,e=this.__refactorClass(a.$page.find("."+o),o,"fixed-top"),s=this.__refactorClass(a.$page.find("."+t),t,"fixed-bottom");e.add(s).appendTo(a.$body).addClass(i.slideout)}},_setup:function(){},_add:function(){i=o[t]._c,e=o[t]._d,s=o[t]._e,i.add("fixed-top fixed-bottom"),a=o[t].glbl}},o[t].defaults[d]={},o[t].configuration.classNames[d]={fixedTop:"FixedTop",fixedBottom:"FixedBottom"};var i,e,s,a}(jQuery);
/*	
 * jQuery mmenu footer addon
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
!function(t){var o="mmenu",e="footer";t[o].addons[e]={_init:function(a){var d=this,i=this.opts[e],r=t("div."+n.footer,this.$menu);r.length&&(i.update&&a.each(function(){var o=t(this),a=t("."+d.conf.classNames[e].panelFooter,o),u=a.html();u||(u=i.title);var l=function(){r[u?"show":"hide"](),r.html(u)};o.on(s.open,l),o.hasClass(n.current)&&l()}),t[o].addons.buttonbars&&t[o].addons.buttonbars._init.call(this,r))},_setup:function(){var a=this.opts[e];if("boolean"==typeof a&&(a={add:a,update:a}),"object"!=typeof a&&(a={}),a=t.extend(!0,{},t[o].defaults[e],a),this.opts[e]=a,a.add){var s=a.content?a.content:a.title;t('<div class="'+n.footer+'" />').appendTo(this.$menu).append(s),this.$menu.addClass(n.hasfooter)}},_add:function(){n=t[o]._c,a=t[o]._d,s=t[o]._e,n.add("footer hasfooter"),d=t[o].glbl}},t[o].defaults[e]={add:!1,content:!1,title:"",update:!1},t[o].configuration.classNames[e]={panelFooter:"Footer"};var n,a,s,d}(jQuery);
/*	
 * jQuery mmenu header addon
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
!function(e){var t="mmenu",a="header";e[t].addons[a]={_init:function(s){var i=this,o=this.opts[a],l=(this.conf[a],e("."+n.header,this.$menu));if(l.length){if(o.update){var h=l.find("."+n.title),c=l.find("."+n.prev),f=l.find("."+n.next),p=l.find("."+n.close),u=!1;r.$page&&(u="#"+r.$page.attr("id"),p.attr("href",u)),s.each(function(){var t=e(this),s=t.find("."+i.conf.classNames[a].panelHeader),r=t.find("."+i.conf.classNames[a].panelPrev),l=t.find("."+i.conf.classNames[a].panelNext),p=s.html(),u=r.attr("href"),v=l.attr("href"),m=r.html(),b=l.html();p||(p=t.find("."+n.subclose).html()),p||(p=o.title),u||(u=t.find("."+n.subclose).attr("href"));var x=function(){h[p?"show":"hide"](),h.html(p),c[u?"attr":"removeAttr"]("href",u),c[u||m?"show":"hide"](),c.html(m),f[v?"attr":"removeAttr"]("href",v),f[v||b?"show":"hide"](),f.html(b)};t.on(d.open,x),t.hasClass(n.current)&&x()})}e[t].addons.buttonbars&&e[t].addons.buttonbars._init.call(this,l)}},_setup:function(){var s=this.opts[a];if(this.conf[a],"boolean"==typeof s&&(s={add:s,update:s}),"object"!=typeof s&&(s={}),"undefined"==typeof s.content&&(s.content=["prev","title","next"]),s=e.extend(!0,{},e[t].defaults[a],s),this.opts[a]=s,s.add){if(s.content instanceof Array){for(var d=e("<div />"),r=0,i=s.content.length;i>r;r++)switch(s.content[r]){case"prev":case"next":case"close":d.append('<a class="'+n[s.content[r]]+'" href="#"></a>');break;case"title":d.append('<span class="'+n.title+'"></span>');break;default:d.append(s.content[r])}d=d.html()}else var d=s.content;e('<div class="'+n.header+'" />').prependTo(this.$menu).append(d),this.$menu.addClass(n.hasheader)}},_add:function(){n=e[t]._c,s=e[t]._d,d=e[t]._e,n.add("header hasheader prev next close title"),r=e[t].glbl}},e[t].defaults[a]={add:!1,title:"Menu",update:!1},e[t].configuration.classNames[a]={panelHeader:"Header",panelNext:"Next",panelPrev:"Prev"};var n,s,d,r}(jQuery);
/*	
 * jQuery mmenu labels addon
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
!function(l){var e="mmenu",s="labels";l[e].addons[s]={_init:function(e){var n=this.opts[s];this.__refactorClass(l("li",this.$menu),this.conf.classNames[s].collapsed,"collapsed"),n.collapse&&l("."+a.label,e).each(function(){var e=l(this),s=e.nextUntil("."+a.label,"."+a.collapsed);s.length&&(e.children("."+a.subopen).length||(e.wrapInner("<span />"),e.prepend('<a href="#" class="'+a.subopen+" "+a.fullsubopen+'" />')))})},_setup:function(){var a=this.opts[s];"boolean"==typeof a&&(a={collapse:a}),"object"!=typeof a&&(a={}),a=l.extend(!0,{},l[e].defaults[s],a),this.opts[s]=a},_add:function(){a=l[e]._c,n=l[e]._d,o=l[e]._e,a.add("collapsed uncollapsed"),t=l[e].glbl},_clickAnchor:function(l,e){if(e){var s=l.parent();if(s.is("."+a.label)){var n=s.nextUntil("."+a.label,"."+a.collapsed);return s.toggleClass(a.opened),n[s.hasClass(a.opened)?"addClass":"removeClass"](a.uncollapsed),!0}}return!1}},l[e].defaults[s]={collapse:!1},l[e].configuration.classNames[s]={collapsed:"Collapsed"};var a,n,o,t}(jQuery);
/*	
 * jQuery mmenu searchfield addon
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
!function(e){function s(e){switch(e){case 9:case 16:case 17:case 18:case 37:case 38:case 39:case 40:return!0}return!1}var n="mmenu",t="searchfield";e[n].addons[t]={_init:function(n){var i=this,l=this.opts[t],d=this.conf[t];if(l.add){switch(l.addTo){case"menu":var c=this.$menu;break;case"panels":var c=n;break;default:var c=e(l.addTo,this.$menu).filter("."+a.panel)}c.length&&c.each(function(){var s=e(this),n=s.is("."+a.menu)?d.form?"form":"div":"li";if(!s.children(n+"."+a.search).length){if(s.is("."+a.menu))var t=i.$menu,r="prependTo";else var t=s.children().first(),r=t.is("."+a.subtitle)?"insertAfter":"insertBefore";var o=e("<"+n+' class="'+a.search+'" />');if("form"==n&&"object"==typeof d.form)for(var c in d.form)o.attr(c,d.form[c]);o.append('<input placeholder="'+l.placeholder+'" type="text" autocomplete="off" />'),o[r](t)}l.noResults&&(s.is("."+a.menu)&&(s=s.children("."+a.panel).first()),n=s.is("."+a.list)?"li":"div",s.children(n+"."+a.noresultsmsg).length||e("<"+n+' class="'+a.noresultsmsg+'" />').html(l.noResults).appendTo(s))})}if(this.$menu.children("."+a.search).length&&this.$menu.addClass(a.hassearch),l.search){var h=e("."+a.search,this.$menu);h.length&&h.each(function(){var n=e(this);if("menu"==l.addTo)var t=e("."+a.panel,i.$menu),d=i.$menu;else var t=n.closest("."+a.panel),d=t;var c=n.children("input"),h=i.__findAddBack(t,"."+a.list).children("li"),u=h.filter("."+a.label),f=h.not("."+a.subtitle).not("."+a.label).not("."+a.search).not("."+a.noresultsmsg),p="> a";l.showLinksOnly||(p+=", > span"),c.off(o.keyup+" "+o.change).on(o.keyup,function(e){s(e.keyCode)||n.trigger(o.search)}).on(o.change,function(){n.trigger(o.search)}),n.off(o.reset+" "+o.search).on(o.reset+" "+o.search,function(e){e.stopPropagation()}).on(o.reset,function(){n.trigger(o.search,[""])}).on(o.search,function(s,n){"string"==typeof n?c.val(n):n=c.val(),n=n.toLowerCase(),t.scrollTop(0),f.add(u).addClass(a.hidden),f.each(function(){var s=e(this);e(p,s).text().toLowerCase().indexOf(n)>-1&&s.add(s.prevAll("."+a.label).first()).removeClass(a.hidden)}),e(t.get().reverse()).each(function(s){var n=e(this),t=n.data(r.parent);if(t){var d=n.add(n.find("> ."+a.list)).find("> li").not("."+a.subtitle).not("."+a.search).not("."+a.noresultsmsg).not("."+a.label).not("."+a.hidden);d.length?t.removeClass(a.hidden).removeClass(a.nosubresults).prevAll("."+a.label).first().removeClass(a.hidden):"menu"==l.addTo&&(n.hasClass(a.opened)&&setTimeout(function(){t.trigger(o.open)},1.5*(s+1)*i.conf.openingInterval),t.addClass(a.nosubresults))}}),d[f.not("."+a.hidden).length?"removeClass":"addClass"](a.noresults),i._update()})})}},_setup:function(){var s=this.opts[t];this.conf[t],"boolean"==typeof s&&(s={add:s,search:s}),"object"!=typeof s&&(s={}),s=e.extend(!0,{},e[n].defaults[t],s),"boolean"!=typeof s.showLinksOnly&&(s.showLinksOnly="menu"==s.addTo),this.opts[t]=s},_add:function(){a=e[n]._c,r=e[n]._d,o=e[n]._e,a.add("search hassearch noresultsmsg noresults nosubresults"),o.add("search reset change"),i=e[n].glbl}},e[n].defaults[t]={add:!1,addTo:"menu",search:!1,placeholder:"Search",noResults:"No results found."},e[n].configuration[t]={form:!1};var a,r,o,i}(jQuery);
/*	
 * jQuery mmenu toggles addon
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
!function(e){var t="mmenu",s="toggles";e[t].addons[s]={_init:function(t){var a=this;this.opts[s],this.conf[s],this.__refactorClass(e("input",t),this.conf.classNames[s].toggle,"toggle"),this.__refactorClass(e("input",t),this.conf.classNames[s].check,"check"),e("input."+c.toggle+", input."+c.check,t).each(function(){var t=e(this),s=t.closest("li"),l=t.hasClass(c.toggle)?"toggle":"check",n=t.attr("id")||a.__getUniqueId();s.children('label[for="'+n+'"]').length||(t.attr("id",n),s.prepend(t),e('<label for="'+n+'" class="'+c[l]+'"></label>').insertBefore(s.children("a, span").last()))})},_setup:function(){},_add:function(){c=e[t]._c,a=e[t]._d,l=e[t]._e,c.add("toggle check"),n=e[t].glbl}},e[t].defaults[s]={},e[t].configuration.classNames[s]={toggle:"Toggle",check:"Check"};var c,a,l,n}(jQuery);
!function (a, b, c, d) {
    "use strict";
    function e(b, c, d) {
        var f, g = this, h = "cbp";
        if (a.data(b, "cubeportfolio"))throw new Error("cubeportfolio is already initialized. Destroy it before initialize again!");
        a.data(b, "cubeportfolio", g), g.options = a.extend({}, a.fn.cubeportfolio.options, c), g.isAnimating = !0, g.defaultFilter = g.options.defaultFilter, g.registeredEvents = [], g.skipEvents = [], g.addedWrapp = !1, a.isFunction(d) && g._registerEvent("initFinish", d, !0), g.obj = b, g.$obj = a(b), f = g.$obj.children(), g.options.caption && ("expand" === g.options.caption || e.Private.modernBrowser || (g.options.caption = "minimal"), h += " cbp-caption-active cbp-caption-" + g.options.caption), g.$obj.addClass(h), (0 === f.length || f.first().hasClass("cbp-item")) && (g.wrapInner(g.obj, "cbp-wrapper"), g.addedWrapp = !0), g.$ul = g.$obj.children().addClass("cbp-wrapper"), g.wrapInner(g.obj, "cbp-wrapper-outer"), g.wrapper = g.$obj.children(".cbp-wrapper-outer"), g.blocks = g.$ul.children(".cbp-item"), g.wrapInner(g.blocks, "cbp-item-wrapper"), g.width = g.$obj.outerWidth(), g._load(g.$obj, g._display)
    }

    a.extend(e.prototype, {
        storeData: function (b) {
            b.each(function (b, c) {
                var d = a(c);
                d.data("cbp", {
                    wrapper: d.children(".cbp-item-wrapper"),
                    widthInitial: d.outerWidth(),
                    heightInitial: d.outerHeight(),
                    width: null,
                    height: null,
                    left: null,
                    leftNew: null,
                    top: null,
                    topNew: null
                })
            })
        }, wrapInner: function (a, b) {
            var e, f, g;
            if (b = b || "", !(a.length && a.length < 1))for (a.length === d && (a = [a]), f = a.length - 1; f >= 0; f--) {
                for (e = a[f], g = c.createElement("div"), g.setAttribute("class", b); e.childNodes.length;)g.appendChild(e.childNodes[0]);
                e.appendChild(g)
            }
        }, _captionDestroy: function () {
            var a = this;
            a.$obj.removeClass("cbp-caption-active cbp-caption-" + a.options.caption)
        }, resizeEvent: function () {
            var c, d, e = this;
            a(b).on("resize.cbp", function () {
                clearTimeout(c), c = setTimeout(function () {
                    b.innerHeight != screen.height && ("alignCenter" === e.options.gridAdjustment && (e.obj.style.maxWidth = ""), d = e.$obj.outerWidth(), e.width !== d && (e.width = d, e._gridAdjust(), e._layout(), e.positionateItems(), e._resizeMainContainer(), "slider" === e.options.layoutMode && e._updateSlider(), e._triggerEvent("resizeGrid")), e._triggerEvent("resizeWindow"))
                }, 80)
            })
        }, _load: function (b, c, d) {
            var e, f, g = this, h = 0;
            d = d || [], e = b.find("img:uncached").map(function () {
                return this.src
            }), f = e.length, 0 === f && c.apply(g, d), a.each(e, function (b, e) {
                var i = new Image;
                a(i).one("load.cbp error.cbp", function () {
                    return a(this).off("load.cbp error.cbp"), h++, h === f ? (c.apply(g, d), !1) : void 0
                }), i.src = e
            })
        }, _filterFromUrl: function () {
            var a = this, b = /#cbpf=(.*?)([#|?&]|$)/gi.exec(location.href);
            null !== b && (a.defaultFilter = b[1])
        }, _display: function () {
            var b = this;
            b.storeData(b.blocks), "grid" === b.options.layoutMode && b._filterFromUrl(), "*" !== b.defaultFilter ? (b.blocksOn = b.blocks.filter(b.defaultFilter), b.blocks.not(b.defaultFilter).addClass("cbp-item-off")) : b.blocksOn = b.blocks, b._plugins = a.map(e.Plugins, function (a) {
                return a(b)
            }), b._triggerEvent("initStartRead"), b._triggerEvent("initStartWrite"), b.localColumnWidth = b.options.gapVertical, b.blocks.length && (b.localColumnWidth += b.blocks.first().data("cbp").widthInitial), b.getColumnsType = a.isArray(b.options.mediaQueries) ? "_getColumnsBreakpoints" : "_getColumnsAuto", b._gridAdjust(), b["_" + b.options.layoutMode + "Markup"](), b._layout(), b.positionateItems(), b._resizeMainContainer(), b._triggerEvent("initEndRead"), b._triggerEvent("initEndWrite"), b.$obj.addClass("cbp-ready"), b._registerEvent("delayFrame", b.delayFrame), b._triggerEvent("delayFrame")
        }, positionateItems: function () {
            var b, c = this;
            c.blocksOn.each(function (c, d) {
                b = a(d).data("cbp"), b.left = b.leftNew, b.top = b.topNew, d.style.left = b.left + "px", d.style.top = b.top + "px"
            })
        }, delayFrame: function () {
            var a = this;
            requestAnimationFrame(function () {
                a.resizeEvent(), a._triggerEvent("initFinish"), a.isAnimating = !1, a.$obj.trigger("initComplete.cbp")
            })
        }, _gridAdjust: function () {
            var b = this;
            "responsive" === b.options.gridAdjustment ? b._responsiveLayout() : b.blocks.each(function (b, c) {
                var d = a(c).data("cbp");
                d.width = d.widthInitial, d.height = d.heightInitial
            })
        }, _layout: function () {
            var a = this;
            a["_" + a.options.layoutMode + "LayoutReset"](), a["_" + a.options.layoutMode + "Layout"](), a.$obj.removeClass(function (a, b) {
                return (b.match(/\bcbp-cols-\d+/gi) || []).join(" ")
            }), a.$obj.addClass("cbp-cols-" + a.cols)
        }, _sliderMarkup: function () {
            var b = this;
            b.sliderStopEvents = !1, b.sliderActive = 0, b._registerEvent("updateSliderPosition", function () {
                b.$obj.addClass("cbp-mode-slider")
            }, !0), b.nav = a("<div/>", {"class": "cbp-nav"}), b.nav.on("click.cbp", "[data-slider-action]", function (c) {
                if (c.preventDefault(), c.stopImmediatePropagation(), c.stopPropagation(), !b.sliderStopEvents) {
                    var d = a(this), e = d.attr("data-slider-action");
                    b["_" + e + "Slider"] && b["_" + e + "Slider"](d)
                }
            }), b.options.showNavigation && (b.controls = a("<div/>", {"class": "cbp-nav-controls"}), b.navPrev = a("<div/>", {
                "class": "cbp-nav-prev",
                "data-slider-action": "prev"
            }).appendTo(b.controls), b.navNext = a("<div/>", {
                "class": "cbp-nav-next",
                "data-slider-action": "next"
            }).appendTo(b.controls), b.controls.appendTo(b.nav)), b.options.showPagination && (b.navPagination = a("<div/>", {"class": "cbp-nav-pagination"}).appendTo(b.nav)), (b.controls || b.navPagination) && b.nav.appendTo(b.$obj), b._updateSliderPagination(), b.options.auto && (b.options.autoPauseOnHover && (b.mouseIsEntered = !1, b.$obj.on("mouseenter.cbp", function () {
                b.mouseIsEntered = !0, b._stopSliderAuto()
            }).on("mouseleave.cbp", function () {
                b.mouseIsEntered = !1, b._startSliderAuto()
            })), b._startSliderAuto()), b.options.drag && e.Private.modernBrowser && b._dragSlider()
        }, _updateSlider: function () {
            var a = this;
            a._updateSliderPosition(), a._updateSliderPagination()
        }, _updateSliderPagination: function () {
            var b, c, d = this;
            if (d.options.showPagination) {
                for (b = Math.ceil(d.blocksOn.length / d.cols), d.navPagination.empty(), c = b - 1; c >= 0; c--)a("<div/>", {
                    "class": "cbp-nav-pagination-item",
                    "data-slider-action": "jumpTo"
                }).appendTo(d.navPagination);
                d.navPaginationItems = d.navPagination.children()
            }
            d._enableDisableNavSlider()
        }, _destroySlider: function () {
            var a = this;
            "slider" === a.options.layoutMode && (a.$obj.off("click.cbp"), a.$obj.removeClass("cbp-mode-slider"), a.options.showNavigation && a.nav.remove(), a.navPagination && a.navPagination.remove())
        }, _nextSlider: function () {
            var a = this;
            if (a._isEndSlider()) {
                if (!a.isRewindNav())return;
                a.sliderActive = 0
            } else a.options.scrollByPage ? a.sliderActive = Math.min(a.sliderActive + a.cols, a.blocksOn.length - a.cols) : a.sliderActive += 1;
            a._goToSlider()
        }, _prevSlider: function () {
            var a = this;
            if (a._isStartSlider()) {
                if (!a.isRewindNav())return;
                a.sliderActive = a.blocksOn.length - a.cols
            } else a.options.scrollByPage ? a.sliderActive = Math.max(0, a.sliderActive - a.cols) : a.sliderActive -= 1;
            a._goToSlider()
        }, _jumpToSlider: function (a) {
            var b = this, c = Math.min(a.index() * b.cols, b.blocksOn.length - b.cols);
            c !== b.sliderActive && (b.sliderActive = c, b._goToSlider())
        }, _jumpDragToSlider: function (a) {
            var b, c, d, e = this, f = a > 0 ? !0 : !1;
            e.options.scrollByPage ? (b = e.cols * e.localColumnWidth, c = e.cols) : (b = e.localColumnWidth, c = 1), a = Math.abs(a), d = Math.floor(a / b) * c, a % b > 20 && (d += c), e.sliderActive = f ? Math.min(e.sliderActive + d, e.blocksOn.length - e.cols) : Math.max(0, e.sliderActive - d), e._goToSlider()
        }, _isStartSlider: function () {
            return 0 === this.sliderActive
        }, _isEndSlider: function () {
            var a = this;
            return a.sliderActive + a.cols > a.blocksOn.length - 1
        }, _goToSlider: function () {
            var a = this;
            a._enableDisableNavSlider(), a._updateSliderPosition()
        }, _startSliderAuto: function () {
            var a = this;
            return a.isDrag ? void a._stopSliderAuto() : void(a.timeout = setTimeout(function () {
                a._nextSlider(), a._startSliderAuto()
            }, a.options.autoTimeout))
        }, _stopSliderAuto: function () {
            clearTimeout(this.timeout)
        }, _enableDisableNavSlider: function () {
            var a, b, c = this;
            c.isRewindNav() || (b = c._isStartSlider() ? "addClass" : "removeClass", c.navPrev[b]("cbp-nav-stop"), b = c._isEndSlider() ? "addClass" : "removeClass", c.navNext[b]("cbp-nav-stop")), c.options.showPagination && (a = c.options.scrollByPage ? Math.ceil(c.sliderActive / c.cols) : c._isEndSlider() ? c.navPaginationItems.length - 1 : Math.floor(c.sliderActive / c.cols), c.navPaginationItems.removeClass("cbp-nav-pagination-active").eq(a).addClass("cbp-nav-pagination-active"))
        }, isRewindNav: function () {
            var a = this;
            return a.options.showNavigation ? a.blocksOn.length <= a.cols ? !1 : a.options.rewindNav ? !0 : !1 : !0
        }, sliderItemsLength: function () {
            return this.blocksOn.length <= this.cols
        }, _sliderLayout: function () {
            var b = this;
            b.blocksOn.each(function (c, d) {
                var e = a(d).data("cbp");
                e.leftNew = Math.round(b.localColumnWidth * c), e.topNew = 0, b.colVert.push(e.height + b.options.gapHorizontal)
            }), b.sliderColVert = b.colVert.slice(b.sliderActive, b.sliderActive + b.cols), b.ulWidth = b.localColumnWidth * b.blocksOn.length - b.options.gapVertical, b.$ul.width(b.ulWidth)
        }, _updateSliderPosition: function () {
            var a = this, b = -a.sliderActive * a.localColumnWidth;
            a._triggerEvent("updateSliderPosition"), e.Private.modernBrowser ? a.$ul[0].style[e.Private.transform] = "translate3d(" + b + "px, 0px, 0)" : a.$ul[0].style.left = b + "px", a.sliderColVert = a.colVert.slice(a.sliderActive, a.sliderActive + a.cols), a._resizeMainContainer()
        }, _dragSlider: function () {
            function f(b) {
                if (!q.sliderItemsLength()) {
                    if (u ? p = b : b.preventDefault(), q.options.auto && q._stopSliderAuto(), s)return void a(m).one("click.cbp", function () {
                        return !1
                    });
                    m = a(b.target), k = j(b).x, l = 0, n = -q.sliderActive * q.localColumnWidth, o = q.localColumnWidth * (q.blocksOn.length - q.cols), r.on(t.move, h), r.on(t.end, g), q.$obj.addClass("cbp-mode-slider-dragStart")
                }
            }

            function g() {
                q.$obj.removeClass("cbp-mode-slider-dragStart"), s = !0, 0 !== l ? (m.one("click.cbp", function () {
                    return !1
                }), q._jumpDragToSlider(l), q.$ul.one(e.Private.transitionend, i)) : i.call(q), r.off(t.move), r.off(t.end)
            }

            function h(a) {
                l = k - j(a).x, (l > 8 || -8 > l) && a.preventDefault(), q.isDrag = !0;
                var b = n - l;
                0 > l && n > l ? b = (n - l) / 5 : l > 0 && -o > n - l && (b = -o + (o + n - l) / 5), e.Private.modernBrowser ? q.$ul[0].style[e.Private.transform] = "translate3d(" + b + "px, 0px, 0)" : q.$ul[0].style.left = b + "px"
            }

            function i() {
                if (s = !1, q.isDrag = !1, q.options.auto) {
                    if (q.mouseIsEntered)return;
                    q._startSliderAuto()
                }
            }

            function j(a) {
                return a.originalEvent !== d && a.originalEvent.touches !== d && (a = a.originalEvent.touches[0]), {
                    x: a.pageX,
                    y: a.pageY
                }
            }

            var k, l, m, n, o, p, q = this, r = a(c), s = !1, t = {}, u = !1;
            q.isDrag = !1, "ontouchstart"in b || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0 ? (t = {
                start: "touchstart.cbp",
                move: "touchmove.cbp",
                end: "touchend.cbp"
            }, u = !0) : t = {start: "mousedown.cbp", move: "mousemove.cbp", end: "mouseup.cbp"}, q.$ul.on(t.start, f)
        }, _sliderLayoutReset: function () {
            var a = this;
            a.colVert = []
        }, _gridMarkup: function () {
        }, _gridLayout: function () {
            var b = this;
            b.blocksOn.each(function (c, d) {
                var e, f, g, h, i = Math.min.apply(Math, b.colVert), j = 0, k = a(d).data("cbp");
                for (g = 0, h = b.colVert.length; h > g; g++)if (b.colVert[g] === i) {
                    j = g;
                    break
                }
                for (k.leftNew = Math.round(b.localColumnWidth * j), k.topNew = Math.round(i), e = i + k.height + b.options.gapHorizontal, f = b.cols + 1 - h, g = 0; f > g; g++)b.colVert[j + g] = e
            })
        }, _gridLayoutReset: function () {
            var a, b = this;
            for ("alignCenter" === b.options.gridAdjustment ? (b.cols = Math.max(Math.floor((b.width + b.options.gapVertical) / b.localColumnWidth), 1), b.width = b.cols * b.localColumnWidth - b.options.gapVertical, b.$obj.css("max-width", b.width)) : b.cols = Math.max(Math.floor((b.width + b.options.gapVertical) / b.localColumnWidth), 1), b.colVert = [], a = b.cols; a--;)b.colVert.push(0)
        }, _responsiveLayout: function () {
            var b, c, d = this;
            d.columnWidthCache ? d.localColumnWidth = d.columnWidthCache : d.columnWidthCache = d.localColumnWidth, d.cols = d[d.getColumnsType](), b = d.width - d.options.gapVertical * (d.cols - 1), d.localColumnWidth = parseInt(b / d.cols, 10) + d.options.gapVertical, c = d.localColumnWidth - d.options.gapVertical, d.blocks.each(function (b, d) {
                d.style.width = c + "px", a(d).data("cbp").width = c
            }), d.blocks.each(function (b, c) {
                var d = a(c);
                d.data("cbp").height = d.outerHeight()
            })
        }, _getColumnsAuto: function () {
            var a = this;
            return Math.max(Math.round(a.width / a.localColumnWidth), 1)
        }, _getColumnsBreakpoints: function () {
            var b, c = this, e = c.width - c.options.gapVertical;
            return a.each(c.options.mediaQueries, function (a, c) {
                return e >= c.width ? (b = c.cols, !1) : void 0
            }), b === d && (b = c.options.mediaQueries[c.options.mediaQueries.length - 1].cols), b
        }, _resizeMainContainer: function () {
            var a, b = this, c = b.sliderColVert || b.colVert;
            a = Math.max(Math.max.apply(Math, c) - b.options.gapHorizontal, 0), a !== b.height && (b.obj.style.height = a + "px", b.height !== d && (e.Private.modernBrowser ? b.$obj.one(e.Private.transitionend, function () {
                b.$obj.trigger("pluginResize.cbp")
            }) : b.$obj.trigger("pluginResize.cbp")), b.height = a)
        }, _filter: function (a) {
            var b = this;
            b.blocksOnInitial = b.blocksOn, b.blocksOn = b.blocks.filter(a), b.blocksOff = b.blocks.not(a), b._layout(), b.filterLayout(a)
        }, filterLayout: function () {
            var b = this;
            b.blocksOff.addClass("cbp-item-off"), b.blocksOn.removeClass("cbp-item-off").each(function (b, c) {
                var d = a(c).data("cbp");
                d.left = d.leftNew, d.top = d.topNew, c.style.left = d.left + "px", c.style.top = d.top + "px"
            }), b._resizeMainContainer(), b.filterFinish()
        }, filterFinish: function () {
            var a = this;
            a.isAnimating = !1, a.$obj.trigger("filterComplete.cbp"), a._triggerEvent("filterFinish")
        }, _registerEvent: function (a, b, c) {
            var d = this;
            d.registeredEvents[a] || (d.registeredEvents[a] = []), d.registeredEvents[a].push({
                func: b,
                oneTime: c || !1
            })
        }, _triggerEvent: function (a, b) {
            var c, d, e = this;
            if (e.skipEvents[a])return void delete e.skipEvents[a];
            if (e.registeredEvents[a])for (c = 0, d = e.registeredEvents[a].length; d > c; c++)e.registeredEvents[a][c].func.call(e, b), e.registeredEvents[a][c].oneTime && (e.registeredEvents[a].splice(c, 1), c--, d--)
        }, _skipNextEvent: function (a) {
            var b = this;
            b.skipEvents[a] = !0
        }, _addItems: function (b, c) {
            var d = this, f = a(b).filter(".cbp-item").addClass("cbp-loading-fadeIn").css("top", "1000%").wrapInner('<div class="cbp-item-wrapper"></div>');
            return f.length ? void d._load(f, function () {
                d.$obj.addClass("cbp-addItems"), f.appendTo(d.$ul), a.merge(d.blocks, f), d.storeData(f), "*" !== d.defaultFilter ? (d.blocksOn = d.blocks.filter(d.defaultFilter), d.blocks.not(d.defaultFilter).addClass("cbp-item-off")) : d.blocksOn = d.blocks, f.on(e.Private.animationend, function () {
                    d.$obj.find(".cbp-loading-fadeIn").removeClass("cbp-loading-fadeIn"), d.$obj.removeClass("cbp-addItems")
                }), d._triggerEvent("addItemsToDOM", f), d._gridAdjust(), d._layout(), d.positionateItems(), d._resizeMainContainer(), "slider" === d.options.layoutMode && d._updateSlider(), d.elems && e.Public.showCounter.call(d.obj, d.elems), e.Private.modernBrowser ? f.last().one(e.Private.animationend, function () {
                    d.isAnimating = !1, a.isFunction(c) && c.call(d)
                }) : (d.isAnimating = !1, a.isFunction(c) && c.call(d))
            }) : (d.isAnimating = !1, void(a.isFunction(c) && c.call(d)))
        }
    }), a.fn.cubeportfolio = function (a, b, c) {
        return this.each(function () {
            if ("object" == typeof a || !a)return e.Public.init.call(this, a, c);
            if (e.Public[a])return e.Public[a].call(this, b, c);
            throw new Error("Method " + a + " does not exist on jquery.cubeportfolio.js")
        })
    }, a.fn.cubeportfolio.options = {
        filters: "",
        loadMore: "",
        loadMoreAction: "click",
        layoutMode: "grid",
        drag: !0,
        auto: !1,
        autoTimeout: 5e3,
        autoPauseOnHover: !0,
        showNavigation: !0,
        showPagination: !0,
        rewindNav: !0,
        scrollByPage: !1,
        defaultFilter: "*",
        filterDeeplinking: !1,
        animationType: "fadeOut",
        gridAdjustment: "responsive",
        mediaQueries: !1,
        gapHorizontal: 10,
        gapVertical: 10,
        caption: "pushTop",
        displayType: "lazyLoading",
        displayTypeSpeed: 400,
        lightboxDelegate: ".cbp-lightbox",
        lightboxGallery: !0,
        lightboxTitleSrc: "data-title",
        lightboxCounter: '<div class="cbp-popup-lightbox-counter">{{current}} of {{total}}</div>',
        singlePageDelegate: ".cbp-singlePage",
        singlePageDeeplinking: !0,
        singlePageStickyNavigation: !0,
        singlePageCounter: '<div class="cbp-popup-singlePage-counter">{{current}} of {{total}}</div>',
        singlePageAnimation: "left",
        singlePageCallback: function () {
        },
        singlePageInlineDelegate: ".cbp-singlePageInline",
        singlePageInlinePosition: "top",
        singlePageInlineInFocus: !0,
        singlePageInlineCallback: function () {
        }
    }, e.Plugins = {}, a.fn.cubeportfolio.Constructor = e
}(jQuery, window, document), function (a) {
    "use strict";
    function b(b) {
        var c = this;
        c.parent = b, c.filters = a(b.options.filters), c.wrap = a(), c.registerFilter()
    }

    var c = a.fn.cubeportfolio.Constructor;
    b.prototype.registerFilter = function () {
        var b, c = this, d = c.parent;
        c.filters.each(function (e, f) {
            var g, h = a(f);
            h.hasClass("cbp-l-filters-dropdown") ? (g = h.find(".cbp-l-filters-dropdownWrap"), g.on({
                "mouseover.cbp": function () {
                    g.addClass("cbp-l-filters-dropdownWrap-open")
                }, "mouseleave.cbp": function () {
                    g.removeClass("cbp-l-filters-dropdownWrap-open")
                }
            }), b = function (a) {
                g.find(".cbp-filter-item").removeClass("cbp-filter-item-active"), g.find(".cbp-l-filters-dropdownHeader").text(a.text()), a.addClass("cbp-filter-item-active"), g.trigger("mouseleave.cbp")
            }, c.wrap.add(g)) : b = function (a) {
                a.addClass("cbp-filter-item-active").siblings().removeClass("cbp-filter-item-active")
            }, b(h.find(".cbp-filter-item").filter('[data-filter="' + d.defaultFilter + '"]')), h.on("click.cbp", ".cbp-filter-item", function () {
                var c = a(this);
                c.hasClass("cbp-filter-item-active") || (d.isAnimating || b.call(null, c), d.$obj.cubeportfolio("filter", c.data("filter")))
            }), d.$obj.cubeportfolio("showCounter", h.find(".cbp-filter-item"), function () {
                var a, c = /#cbpf=(.*?)([#|?&]|$)/gi.exec(location.href);
                null !== c && (a = h.find(".cbp-filter-item").filter('[data-filter="' + c[1] + '"]'), a.length && b.call(null, a))
            })
        })
    }, b.prototype.destroy = function () {
        var a = this;
        a.filters.off(".cbp"), a.wrap && a.wrap.off(".cbp")
    }, c.Plugins.Filters = function (a) {
        return "" === a.options.filters ? null : new b(a)
    }
}(jQuery, window, document), function (a, b) {
    "use strict";
    function c(b) {
        var c = this;
        c.parent = b, c.loadMore = a(b.options.loadMore).find(".cbp-l-loadMore-link"), b.options.loadMoreAction.length && c[b.options.loadMoreAction]()
    }

    var d = a.fn.cubeportfolio.Constructor;
    c.prototype.click = function () {
        var b = this, c = 0;
        b.loadMore.on("click.cbp", function (d) {
            var e = a(this);
            d.preventDefault(), e.hasClass("cbp-l-loadMore-stop") || (e.addClass("cbp-l-loadMore-loading"), c++, a.ajax({
                url: b.loadMore.attr("href"),
                type: "GET",
                dataType: "HTML"
            }).done(function (d) {
                var f, g;
                f = a(d).filter(function () {
                    return a(this).is("div.cbp-loadMore-block" + c)
                }), b.parent.$obj.cubeportfolio("appendItems", f.html(), function () {
                    e.removeClass("cbp-l-loadMore-loading"), g = a(d).filter(function () {
                        return a(this).is("div.cbp-loadMore-block" + (c + 1))
                    }), 0 === g.length && e.addClass("cbp-l-loadMore-stop")
                })
            }).fail(function () {
            }))
        })
    }, c.prototype.auto = function () {
        var c = this;
        c.parent.$obj.on("initComplete.cbp", function () {
            Object.create({
                init: function () {
                    var d = this;
                    d.isActive = !1, d.numberOfClicks = 0, c.loadMore.addClass("cbp-l-loadMore-loading"), d.window = a(b), d.addEvents(), d.getNewItems()
                }, addEvents: function () {
                    var a, b = this;
                    c.loadMore.on("click.cbp", function (a) {
                        a.preventDefault()
                    }), b.window.on("scroll.loadMoreObject", function () {
                        clearTimeout(a), a = setTimeout(function () {
                            c.parent.isAnimating || b.getNewItems()
                        }, 80)
                    }), c.parent.$obj.on("filterComplete.cbp", function () {
                        b.getNewItems()
                    })
                }, getNewItems: function () {
                    var b, d, e = this;
                    e.isActive || c.loadMore.hasClass("cbp-l-loadMore-stop") || (b = c.loadMore.offset().top, d = e.window.scrollTop() + e.window.height(), b > d || (e.isActive = !0, e.numberOfClicks++, a.ajax({
                        url: c.loadMore.attr("href"),
                        type: "GET",
                        dataType: "HTML",
                        cache: !0
                    }).done(function (b) {
                        var d, f;
                        d = a(b).filter(function () {
                            return a(this).is("div.cbp-loadMore-block" + e.numberOfClicks)
                        }), c.parent.$obj.cubeportfolio("appendItems", d.html(), function () {
                            f = a(b).filter(function () {
                                return a(this).is("div.cbp-loadMore-block" + (e.numberOfClicks + 1))
                            }), 0 === f.length ? (c.loadMore.addClass("cbp-l-loadMore-stop"), e.window.off("scroll.loadMoreObject"), c.parent.$obj.off("filterComplete.cbp")) : (e.isActive = !1, e.window.trigger("scroll.loadMoreObject"))
                        })
                    }).fail(function () {
                        e.isActive = !1
                    })))
                }
            }).init()
        })
    }, c.prototype.destroy = function () {
        var c = this;
        c.loadMore.off(".cbp"), a(b).off("scroll.loadMoreObject")
    }, d.Plugins.LoadMore = function (a) {
        return "" === a.options.loadMore ? null : new c(a)
    }
}(jQuery, window, document), function (a, b, c) {
    "use strict";
    function d(a) {
        var b = this;
        b.parent = a, a.options.lightboxShowCounter === !1 && (a.options.lightboxCounter = ""), a.options.singlePageShowCounter === !1 && (a.options.singlePageCounter = ""), b.run()
    }

    var e = a.fn.cubeportfolio.Constructor, f = {
        init: function (b, d) {
            var e, f = this;
            if (f.cubeportfolio = b, f.type = d, f.isOpen = !1, f.options = f.cubeportfolio.options, "lightbox" === d && f.cubeportfolio._registerEvent("resizeWindow", function () {
                    f.resizeImage()
                }), "singlePageInline" === d)return f.startInline = -1, f.height = 0, f._createMarkupSinglePageInline(), void f.cubeportfolio._registerEvent("resizeGrid", function () {
                f.isOpen && f.close()
            });
            if (f._createMarkup(), "singlePage" === d && (f.cubeportfolio._registerEvent("resizeWindow", function () {
                    if (f.options.singlePageStickyNavigation) {
                        var a = f.wrap[0].clientWidth;
                        a > 0 && (f.navigationWrap.width(a), f.navigation.width(a))
                    }
                }), f.options.singlePageDeeplinking)) {
                f.url = location.href, "#" === f.url.slice(-1) && (f.url = f.url.slice(0, -1));
                var g = f.url.split("#cbp="), h = g.shift();
                if (a.each(g, function (b, c) {
                        return f.cubeportfolio.blocksOn.each(function (b, d) {
                            var g = a(d).find(f.options.singlePageDelegate + '[href="' + c + '"]');
                            return g.length ? (e = g, !1) : void 0
                        }), e ? !1 : void 0
                    }), e) {
                    f.url = h;
                    var i = e, j = i.attr("data-cbp-singlePage"), k = [];
                    j ? k = i.closest(a(".cbp-item")).find('[data-cbp-singlePage="' + j + '"]') : f.cubeportfolio.blocksOn.each(function (b, c) {
                        var d = a(c);
                        d.not(".cbp-item-off") && d.find(f.options.singlePageDelegate).each(function (b, c) {
                            a(c).attr("data-cbp-singlePage") || k.push(c)
                        })
                    }), f.openSinglePage(k, e[0])
                } else if (g.length) {
                    var l = c.createElement("a");
                    l.setAttribute("href", g[0]), f.openSinglePage([l], l)
                }
            }
        }, _createMarkup: function () {
            var b = this, d = "";
            "singlePage" === b.type && "left" !== b.options.singlePageAnimation && (d = " cbp-popup-singlePage-" + b.options.singlePageAnimation), b.wrap = a("<div/>", {
                "class": "cbp-popup-wrap cbp-popup-" + b.type + d,
                "data-action": "lightbox" === b.type ? "close" : ""
            }).on("click.cbp", function (c) {
                if (!b.stopEvents) {
                    var d = a(c.target).attr("data-action");
                    b[d] && (b[d](), c.preventDefault())
                }
            }), b.content = a("<div/>", {"class": "cbp-popup-content"}).appendTo(b.wrap), a("<div/>", {"class": "cbp-popup-loadingBox"}).appendTo(b.wrap), "ie8" === e.Private.browser && (b.bg = a("<div/>", {
                "class": "cbp-popup-ie8bg",
                "data-action": "lightbox" === b.type ? "close" : ""
            }).appendTo(b.wrap)), b.navigationWrap = a("<div/>", {"class": "cbp-popup-navigation-wrap"}).appendTo(b.wrap), b.navigation = a("<div/>", {"class": "cbp-popup-navigation"}).appendTo(b.navigationWrap), b.closeButton = a("<div/>", {
                "class": "cbp-popup-close",
                title: "Close (Esc arrow key)",
                "data-action": "close"
            }).appendTo(b.navigation), b.nextButton = a("<div/>", {
                "class": "cbp-popup-next",
                title: "Next (Right arrow key)",
                "data-action": "next"
            }).appendTo(b.navigation), b.prevButton = a("<div/>", {
                "class": "cbp-popup-prev",
                title: "Previous (Left arrow key)",
                "data-action": "prev"
            }).appendTo(b.navigation), "singlePage" === b.type && (b.options.singlePageCounter && (b.counter = a(b.options.singlePageCounter).appendTo(b.navigation), b.counter.text("")), b.content.on("click.cbp", b.options.singlePageDelegate, function (a) {
                a.preventDefault();
                var c, d = b.dataArray.length, e = this.getAttribute("href");
                for (c = 0; d > c && b.dataArray[c].url !== e; c++);
                b.singlePageJumpTo(c - b.current)
            }), b.wrap.on("mousewheel.cbp DOMMouseScroll.cbp", function (a) {
                a.stopImmediatePropagation()
            })), a(c).on("keydown.cbp", function (a) {
                b.isOpen && (b.stopEvents || (37 === a.keyCode ? b.prev() : 39 === a.keyCode ? b.next() : 27 === a.keyCode && b.close()))
            })
        }, _createMarkupSinglePageInline: function () {
            var b = this;
            b.wrap = a("<div/>", {"class": "cbp-popup-singlePageInline"}).on("click.cbp", function (c) {
                if (!b.stopEvents) {
                    var d = a(c.target).attr("data-action");
                    d && b[d] && (b[d](), c.preventDefault())
                }
            }), b.content = a("<div/>", {"class": "cbp-popup-content"}).appendTo(b.wrap), b.navigation = a("<div/>", {"class": "cbp-popup-navigation"}).appendTo(b.wrap), b.closeButton = a("<div/>", {
                "class": "cbp-popup-close",
                title: "Close (Esc arrow key)",
                "data-action": "close"
            }).appendTo(b.navigation)
        }, destroy: function () {
            var b = this, d = a("body");
            a(c).off("keydown.cbp"), d.off("click.cbp", b.options.lightboxDelegate), d.off("click.cbp", b.options.singlePageDelegate), b.content.off("click.cbp", b.options.singlePageDelegate), b.cubeportfolio.$obj.off("click.cbp", b.options.singlePageInlineDelegate), b.cubeportfolio.$obj.off("click.cbp", b.options.lightboxDelegate), b.cubeportfolio.$obj.off("click.cbp", b.options.singlePageDelegate), b.cubeportfolio.$obj.removeClass("cbp-popup-isOpening"), b.cubeportfolio.$obj.find(".cbp-item").removeClass("cbp-singlePageInline-active"), b.wrap.remove()
        }, openLightbox: function (d, e) {
            var f, g, h = this, i = 0, j = [];
            if (!h.isOpen) {
                if (h.isOpen = !0, h.stopEvents = !1, h.dataArray = [], h.current = null, f = e.getAttribute("href"), null === f)throw new Error("HEI! Your clicked element doesn't have a href attribute.");
                a.each(d, function (b, c) {
                    var d, e = c.getAttribute("href"), g = e, k = "isImage";
                    if (-1 === a.inArray(e, j)) {
                        if (f === e)h.current = i; else if (!h.options.lightboxGallery)return;
                        /youtube/i.test(e) ? (d = e.substring(e.lastIndexOf("v=") + 2), /autoplay=/i.test(d) || (d += "&autoplay=1"), d = d.replace(/\?|&/, "?"), g = "//www.youtube.com/embed/" + d, k = "isYoutube") : /vimeo/i.test(e) ? (d = e.substring(e.lastIndexOf("/") + 1), /autoplay=/i.test(d) || (d += "&autoplay=1"), d = d.replace(/\?|&/, "?"), g = "//player.vimeo.com/video/" + d, k = "isVimeo") : /ted\.com/i.test(e) ? (g = "http://embed.ted.com/talks/" + e.substring(e.lastIndexOf("/") + 1) + ".html", k = "isTed") : /soundcloud\.com/i.test(e) ? (g = e, k = "isSoundCloud") : /(\.mp4)|(\.ogg)|(\.ogv)|(\.webm)/i.test(e) ? (g = e.split(-1 !== e.indexOf("|") ? "|" : "%7C"), k = "isSelfHostedVideo") : /\.mp3$/i.test(e) && (g = e, k = "isSelfHostedAudio"), h.dataArray.push({
                            src: g,
                            title: c.getAttribute(h.options.lightboxTitleSrc),
                            type: k
                        }), i++
                    }
                    j.push(e)
                }), h.counterTotal = h.dataArray.length, 1 === h.counterTotal ? (h.nextButton.hide(), h.prevButton.hide(), h.dataActionImg = "") : (h.nextButton.show(), h.prevButton.show(), h.dataActionImg = 'data-action="next"'), h.wrap.appendTo(c.body), h.scrollTop = a(b).scrollTop(), h.originalStyle = a("html").attr("style"), a("html").css({
                    overflow: "hidden",
                    paddingRight: b.innerWidth - a(c).width()
                }), h.wrap.show(), g = h.dataArray[h.current], h[g.type](g)
            }
        }, openSinglePage: function (d, f) {
            var g, h = this, i = 0, j = [];
            if (!h.isOpen) {
                if (h.cubeportfolio.singlePageInline && h.cubeportfolio.singlePageInline.isOpen && h.cubeportfolio.singlePageInline.close(), h.isOpen = !0, h.stopEvents = !1, h.dataArray = [], h.current = null, g = f.getAttribute("href"), null === g)throw new Error("HEI! Your clicked element doesn't have a href attribute.");
                if (a.each(d, function (b, c) {
                        var d = c.getAttribute("href");
                        -1 === a.inArray(d, j) && (g === d && (h.current = i), h.dataArray.push({
                            url: d,
                            element: c
                        }), i++), j.push(d)
                    }), h.counterTotal = h.dataArray.length, 1 === h.counterTotal ? (h.nextButton.hide(), h.prevButton.hide()) : (h.nextButton.show(), h.prevButton.show()), h.wrap.appendTo(c.body), h.scrollTop = a(b).scrollTop(), a("html").css({
                        overflow: "hidden",
                        paddingRight: b.innerWidth - a(c).width()
                    }), h.wrap.scrollTop(0), h.wrap.show(), h.finishOpen = 2, h.navigationMobile = a(), h.wrap.one(e.Private.transitionend, function () {
                        var b;
                        h.options.singlePageStickyNavigation && (h.wrap.addClass("cbp-popup-singlePage-sticky"), b = h.wrap[0].clientWidth, h.navigationWrap.width(b), ("android" === e.Private.browser || "ios" === e.Private.browser) && (h.navigationMobile = a("<div/>", {
                            "class": "cbp-popup-singlePage cbp-popup-singlePage-sticky",
                            id: h.wrap.attr("id")
                        }).on("click.cbp", function (b) {
                            if (!h.stopEvents) {
                                var c = a(b.target).attr("data-action");
                                h[c] && (h[c](), b.preventDefault())
                            }
                        }), h.navigationMobile.appendTo(c.body).append(h.navigationWrap))), h.finishOpen--, h.finishOpen <= 0 && h.updateSinglePageIsOpen.call(h)
                    }), "ie8" === e.Private.browser || "ie9" === e.Private.browser) {
                    if (h.options.singlePageStickyNavigation) {
                        var k = h.wrap[0].clientWidth;
                        h.navigationWrap.width(k), setTimeout(function () {
                            h.wrap.addClass("cbp-popup-singlePage-sticky")
                        }, 1e3)
                    }
                    h.finishOpen--
                }
                h.wrap.addClass("cbp-popup-loading"), h.wrap.offset(), h.wrap.addClass("cbp-popup-singlePage-open"), h.options.singlePageDeeplinking && (h.url = h.url.split("#cbp=")[0], location.href = h.url + "#cbp=" + h.dataArray[h.current].url), a.isFunction(h.options.singlePageCallback) && h.options.singlePageCallback.call(h, h.dataArray[h.current].url, h.dataArray[h.current].element)
            }
        }, openSinglePageInline: function (c, d, e) {
            var f, g, h, i, j = this;
            if (e = e || !1, j.fromOpen = e, j.storeBlocks = c, j.storeCurrentBlock = d, j.isOpen)return g = a(d).closest(".cbp-item").index(), void(j.dataArray[j.current].url !== d.getAttribute("href") || j.current !== g ? j.cubeportfolio.singlePageInline.close("open", {
                blocks: c,
                currentBlock: d,
                fromOpen: !0
            }) : j.close());
            if (j.isOpen = !0, j.stopEvents = !1, j.dataArray = [], j.current = null, f = d.getAttribute("href"), null === f)throw new Error("HEI! Your clicked element doesn't have a href attribute.");
            if (h = a(d).closest(".cbp-item")[0], c.each(function (a, b) {
                    h === b && (j.current = a)
                }), j.dataArray[j.current] = {
                    url: f,
                    element: d
                }, i = a(j.dataArray[j.current].element).parents(".cbp-item").addClass("cbp-singlePageInline-active"), j.counterTotal = c.length, j.wrap.insertBefore(j.cubeportfolio.wrapper), "top" === j.options.singlePageInlinePosition ? (j.startInline = 0, j.top = 0, j.firstRow = !0, j.lastRow = !1) : "bottom" === j.options.singlePageInlinePosition ? (j.startInline = j.counterTotal, j.top = j.cubeportfolio.height, j.firstRow = !1, j.lastRow = !0) : "above" === j.options.singlePageInlinePosition ? (j.startInline = j.cubeportfolio.cols * Math.floor(j.current / j.cubeportfolio.cols), j.top = a(c[j.current]).data("cbp").top, 0 === j.startInline ? j.firstRow = !0 : (j.top -= j.options.gapHorizontal, j.firstRow = !1), j.lastRow = !1) : (j.top = a(c[j.current]).data("cbp").top + a(c[j.current]).data("cbp").height, j.startInline = Math.min(j.cubeportfolio.cols * (Math.floor(j.current / j.cubeportfolio.cols) + 1), j.counterTotal), j.firstRow = !1, j.lastRow = j.startInline === j.counterTotal ? !0 : !1), j.wrap[0].style.height = j.wrap.outerHeight(!0) + "px", j.deferredInline = a.Deferred(), j.options.singlePageInlineInFocus) {
                j.scrollTop = a(b).scrollTop();
                var k = j.cubeportfolio.$obj.offset().top + j.top - 100;
                j.scrollTop !== k ? a("html,body").animate({scrollTop: k}, 350).promise().then(function () {
                    j._resizeSinglePageInline(), j.deferredInline.resolve()
                }) : (j._resizeSinglePageInline(), j.deferredInline.resolve())
            } else j._resizeSinglePageInline(), j.deferredInline.resolve();
            j.cubeportfolio.$obj.addClass("cbp-popup-singlePageInline-open"), j.wrap.css({top: j.top}), a.isFunction(j.options.singlePageInlineCallback) && j.options.singlePageInlineCallback.call(j, j.dataArray[j.current].url, j.dataArray[j.current].element)
        }, _resizeSinglePageInline: function () {
            var a = this;
            a.height = a.firstRow || a.lastRow ? a.wrap.outerHeight(!0) : a.wrap.outerHeight(!0) - a.options.gapHorizontal, a.storeBlocks.each(function (b, c) {
                b < a.startInline ? e.Private.modernBrowser ? c.style[e.Private.transform] = "" : c.style.marginTop = "" : e.Private.modernBrowser ? c.style[e.Private.transform] = "translate3d(0px, " + a.height + "px, 0)" : c.style.marginTop = a.height + "px"
            }), a.cubeportfolio.obj.style.height = a.cubeportfolio.height + a.height + "px"
        }, _revertResizeSinglePageInline: function () {
            var b = this;
            b.deferredInline = a.Deferred(), b.storeBlocks.each(function (a, b) {
                e.Private.modernBrowser ? b.style[e.Private.transform] = "" : b.style.marginTop = ""
            }), b.cubeportfolio.obj.style.height = b.cubeportfolio.height + "px"
        }, appendScriptsToWrap: function (a) {
            var b = this, d = 0, e = function (f) {
                var g = c.createElement("script"), h = f.src;
                g.type = "text/javascript", g.readyState ? g.onreadystatechange = function () {
                    ("loaded" == g.readyState || "complete" == g.readyState) && (g.onreadystatechange = null, d++, a[d] && e(a[d]))
                } : g.onload = function () {
                    d++, a[d] && e(a[d])
                }, h ? g.src = h : g.text = f.text, b.content[0].appendChild(g)
            };
            e(a[0])
        }, updateSinglePage: function (b, c, d) {
            var e, f = this;
            f.content.addClass("cbp-popup-content").removeClass("cbp-popup-content-basic"), d === !1 && f.content.removeClass("cbp-popup-content").addClass("cbp-popup-content-basic"), f.counter && (e = a(f._getCounterMarkup(f.options.singlePageCounter, f.current + 1, f.counterTotal)), f.counter.text(e.text())), f.content.html(b), c && f.appendScriptsToWrap(c), f.cubeportfolio.$obj.trigger("updateSinglePageStart.cbp"), f.finishOpen--, f.finishOpen <= 0 && f.updateSinglePageIsOpen.call(f)
        }, updateSinglePageIsOpen: function () {
            var b, c = this;
            c.wrap.addClass("cbp-popup-ready"), c.wrap.removeClass("cbp-popup-loading"), b = c.content.find(".cbp-slider"), b ? (b.find(".cbp-slider-item").addClass("cbp-item"), c.slider = b.cubeportfolio({
                layoutMode: "slider",
                mediaQueries: [{width: 1, cols: 1}],
                gapHorizontal: 0,
                gapVertical: 0,
                caption: "",
                coverRatio: ""
            })) : c.slider = null, ("android" === e.Private.browser || "ios" === e.Private.browser) && a("html").css({position: "fixed"}), c.cubeportfolio.$obj.trigger("updateSinglePageComplete.cbp")
        }, updateSinglePageInline: function (a, b) {
            var c = this;
            c.content.html(a), b && c.appendScriptsToWrap(b), c.cubeportfolio.$obj.trigger("updateSinglePageInlineStart.cbp"), c.singlePageInlineIsOpen.call(c)
        }, singlePageInlineIsOpen: function () {
            function a() {
                b.wrap.addClass("cbp-popup-singlePageInline-ready"),
                    b.wrap[0].style.height = "", b._resizeSinglePageInline(), b.cubeportfolio.$obj.trigger("updateSinglePageInlineComplete.cbp")
            }

            var b = this;
            b.cubeportfolio._load(b.wrap, function () {
                var c = b.content.find(".cbp-slider");
                c.length ? (c.find(".cbp-slider-item").addClass("cbp-item"), c.one("initComplete.cbp", function () {
                    b.deferredInline.done(a)
                }), c.on("pluginResize.cbp", function () {
                    b.deferredInline.done(a)
                }), b.slider = c.cubeportfolio({
                    layoutMode: "slider",
                    displayType: "default",
                    mediaQueries: [{width: 1, cols: 1}],
                    gapHorizontal: 0,
                    gapVertical: 0,
                    caption: "",
                    coverRatio: ""
                })) : (b.slider = null, b.deferredInline.done(a))
            })
        }, isImage: function (b) {
            var c = this, d = new Image;
            c.tooggleLoading(!0), a('<img src="' + b.src + '">').is("img:uncached") ? (a(d).on("load.cbp error.cbp", function () {
                c.updateImagesMarkup(b.src, b.title, c._getCounterMarkup(c.options.lightboxCounter, c.current + 1, c.counterTotal)), c.tooggleLoading(!1)
            }), d.src = b.src) : (c.updateImagesMarkup(b.src, b.title, c._getCounterMarkup(c.options.lightboxCounter, c.current + 1, c.counterTotal)), c.tooggleLoading(!1))
        }, isVimeo: function (a) {
            var b = this;
            b.updateVideoMarkup(a.src, a.title, b._getCounterMarkup(b.options.lightboxCounter, b.current + 1, b.counterTotal))
        }, isYoutube: function (a) {
            var b = this;
            b.updateVideoMarkup(a.src, a.title, b._getCounterMarkup(b.options.lightboxCounter, b.current + 1, b.counterTotal))
        }, isTed: function (a) {
            var b = this;
            b.updateVideoMarkup(a.src, a.title, b._getCounterMarkup(b.options.lightboxCounter, b.current + 1, b.counterTotal))
        }, isSoundCloud: function (a) {
            var b = this;
            b.updateVideoMarkup(a.src, a.title, b._getCounterMarkup(b.options.lightboxCounter, b.current + 1, b.counterTotal))
        }, isSelfHostedVideo: function (a) {
            var b = this;
            b.updateSelfHostedVideo(a.src, a.title, b._getCounterMarkup(b.options.lightboxCounter, b.current + 1, b.counterTotal))
        }, isSelfHostedAudio: function (a) {
            var b = this;
            b.updateSelfHostedAudio(a.src, a.title, b._getCounterMarkup(b.options.lightboxCounter, b.current + 1, b.counterTotal))
        }, _getCounterMarkup: function (a, b, c) {
            if (!a.length)return "";
            var d = {current: b, total: c};
            return a.replace(/\{\{current}}|\{\{total}}/gi, function (a) {
                return d[a.slice(2, -2)]
            })
        }, updateSelfHostedVideo: function (a, b, c) {
            var d, e = this;
            e.wrap.addClass("cbp-popup-lightbox-isIframe");
            var f = '<div class="cbp-popup-lightbox-iframe"><video controls="controls" height="auto" style="width: 100%">';
            for (d = 0; d < a.length; d++)/(\.mp4)/i.test(a[d]) ? f += '<source src="' + a[d] + '" type="video/mp4">' : /(\.ogg)|(\.ogv)/i.test(a[d]) ? f += '<source src="' + a[d] + '" type="video/ogg">' : /(\.webm)/i.test(a[d]) && (f += '<source src="' + a[d] + '" type="video/webm">');
            f += 'Your browser does not support the video tag.</video><div class="cbp-popup-lightbox-bottom">' + (b ? '<div class="cbp-popup-lightbox-title">' + b + "</div>" : "") + c + "</div></div>", e.content.html(f), e.wrap.addClass("cbp-popup-ready"), e.preloadNearbyImages()
        }, updateSelfHostedAudio: function (a, b, c) {
            var d = this;
            d.wrap.addClass("cbp-popup-lightbox-isIframe");
            var e = '<div class="cbp-popup-lightbox-iframe"><audio controls="controls" height="auto" style="width: 100%"><source src="' + a + '" type="audio/mpeg">Your browser does not support the audio tag.</audio><div class="cbp-popup-lightbox-bottom">' + (b ? '<div class="cbp-popup-lightbox-title">' + b + "</div>" : "") + c + "</div></div>";
            d.content.html(e), d.wrap.addClass("cbp-popup-ready"), d.preloadNearbyImages()
        }, updateVideoMarkup: function (a, b, c) {
            var d = this;
            d.wrap.addClass("cbp-popup-lightbox-isIframe");
            var e = '<div class="cbp-popup-lightbox-iframe"><iframe src="' + a + '" frameborder="0" allowfullscreen scrolling="no"></iframe><div class="cbp-popup-lightbox-bottom">' + (b ? '<div class="cbp-popup-lightbox-title">' + b + "</div>" : "") + c + "</div></div>";
            d.content.html(e), d.wrap.addClass("cbp-popup-ready"), d.preloadNearbyImages()
        }, updateImagesMarkup: function (a, b, c) {
            var d = this;
            d.wrap.removeClass("cbp-popup-lightbox-isIframe");
            var e = '<div class="cbp-popup-lightbox-figure"><img src="' + a + '" class="cbp-popup-lightbox-img" ' + d.dataActionImg + ' /><div class="cbp-popup-lightbox-bottom">' + (b ? '<div class="cbp-popup-lightbox-title">' + b + "</div>" : "") + c + "</div></div>";
            d.content.html(e), d.wrap.addClass("cbp-popup-ready"), d.resizeImage(), d.preloadNearbyImages()
        }, next: function () {
            var a = this;
            a[a.type + "JumpTo"](1)
        }, prev: function () {
            var a = this;
            a[a.type + "JumpTo"](-1)
        }, lightboxJumpTo: function (a) {
            var b, c = this;
            c.current = c.getIndex(c.current + a), b = c.dataArray[c.current], c[b.type](b)
        }, singlePageJumpTo: function (b) {
            var c = this;
            c.current = c.getIndex(c.current + b), a.isFunction(c.options.singlePageCallback) && (c.resetWrap(), c.wrap.scrollTop(0), c.wrap.addClass("cbp-popup-loading"), c.options.singlePageCallback.call(c, c.dataArray[c.current].url, c.dataArray[c.current].element), c.options.singlePageDeeplinking && (location.href = c.url + "#cbp=" + c.dataArray[c.current].url))
        }, resetWrap: function () {
            var a = this;
            "singlePage" === a.type && a.options.singlePageDeeplinking && (location.href = a.url + "#")
        }, getIndex: function (a) {
            var b = this;
            return a %= b.counterTotal, 0 > a && (a = b.counterTotal + a), a
        }, close: function (c, d) {
            function f() {
                h.content.html(""), h.wrap.detach(), h.cubeportfolio.$obj.removeClass("cbp-popup-singlePageInline-open cbp-popup-singlePageInline-close"), "promise" === c && a.isFunction(d.callback) && d.callback.call(h.cubeportfolio)
            }

            function g() {
                h.options.singlePageInlineInFocus && "promise" !== c ? a("html,body").animate({scrollTop: h.scrollTop}, 350).promise().then(function () {
                    f()
                }) : f()
            }

            var h = this;
            h.isOpen = !1, "singlePageInline" === h.type ? "open" === c ? (h.wrap.removeClass("cbp-popup-singlePageInline-ready"), a(h.dataArray[h.current].element).closest(".cbp-item").removeClass("cbp-singlePageInline-active"), h.openSinglePageInline(d.blocks, d.currentBlock, d.fromOpen)) : (h.height = 0, h._revertResizeSinglePageInline(), h.wrap.removeClass("cbp-popup-singlePageInline-ready"), h.cubeportfolio.$obj.addClass("cbp-popup-singlePageInline-close"), h.startInline = -1, h.cubeportfolio.$obj.find(".cbp-item").removeClass("cbp-singlePageInline-active"), e.Private.modernBrowser ? h.wrap.one(e.Private.transitionend, function () {
                g()
            }) : g()) : "singlePage" === h.type ? (h.resetWrap(), h.wrap.removeClass("cbp-popup-ready"), ("android" === e.Private.browser || "ios" === e.Private.browser) && (a("html").css({position: ""}), h.navigationWrap.appendTo(h.wrap), h.navigationMobile.remove()), a(b).scrollTop(h.scrollTop), setTimeout(function () {
                h.stopScroll = !0, h.navigationWrap.css({top: h.wrap.scrollTop()}), h.wrap.removeClass("cbp-popup-singlePage-open cbp-popup-singlePage-sticky"), ("ie8" === e.Private.browser || "ie9" === e.Private.browser) && (h.content.html(""), h.wrap.detach(), a("html").css({
                    overflow: "",
                    paddingRight: "",
                    position: ""
                }), h.navigationWrap.removeAttr("style"))
            }, 0), h.wrap.one(e.Private.transitionend, function () {
                h.content.html(""), h.wrap.detach(), a("html").css({
                    overflow: "",
                    paddingRight: "",
                    position: ""
                }), h.navigationWrap.removeAttr("style")
            })) : (h.originalStyle ? a("html").attr("style", h.originalStyle) : a("html").css({
                overflow: "",
                paddingRight: ""
            }), a(b).scrollTop(h.scrollTop), h.content.html(""), h.wrap.detach())
        }, tooggleLoading: function (a) {
            var b = this;
            b.stopEvents = a, b.wrap[a ? "addClass" : "removeClass"]("cbp-popup-loading")
        }, resizeImage: function () {
            if (this.isOpen) {
                var c = a(b).height(), d = this.content.find("img"), e = parseInt(d.css("margin-top"), 10) + parseInt(d.css("margin-bottom"), 10);
                d.css("max-height", c - e + "px")
            }
        }, preloadNearbyImages: function () {
            var b, c, d = [], e = this;
            d.push(e.getIndex(e.current + 1)), d.push(e.getIndex(e.current + 2)), d.push(e.getIndex(e.current + 3)), d.push(e.getIndex(e.current - 1)), d.push(e.getIndex(e.current - 2)), d.push(e.getIndex(e.current - 3));
            for (var f = d.length - 1; f >= 0; f--)"isImage" === e.dataArray[d[f]].type && (c = e.dataArray[d[f]].src, b = new Image, a('<img src="' + c + '">').is("img:uncached") && (b.src = c))
        }
    }, g = !1, h = !1;
    d.prototype.run = function () {
        var b = this, d = b.parent, e = a(c.body);
        d.lightbox = null, d.options.lightboxDelegate && !g && (g = !0, d.lightbox = Object.create(f), d.lightbox.init(d, "lightbox"), e.on("click.cbp", d.options.lightboxDelegate, function (c) {
            c.preventDefault();
            var e = a(this), f = e.attr("data-cbp-lightbox"), g = b.detectScope(e), h = g.data("cubeportfolio"), i = [];
            h ? h.blocksOn.each(function (b, c) {
                var e = a(c);
                e.not(".cbp-item-off") && e.find(d.options.lightboxDelegate).each(function (b, c) {
                    f ? a(c).attr("data-cbp-lightbox") === f && i.push(c) : i.push(c)
                })
            }) : i = g.find(f ? d.options.lightboxDelegate + "[data-cbp-lightbox=" + f + "]" : d.options.lightboxDelegate), d.lightbox.openLightbox(i, e[0])
        })), d.singlePage = null, d.options.singlePageDelegate && !h && (h = !0, d.singlePage = Object.create(f), d.singlePage.init(d, "singlePage"), e.on("click.cbp", d.options.singlePageDelegate, function (c) {
            c.preventDefault();
            var e = a(this), f = e.attr("data-cbp-singlePage"), g = b.detectScope(e), h = g.data("cubeportfolio"), i = [];
            h ? h.blocksOn.each(function (b, c) {
                var e = a(c);
                e.not(".cbp-item-off") && e.find(d.options.singlePageDelegate).each(function (b, c) {
                    f ? a(c).attr("data-cbp-singlePage") === f && i.push(c) : i.push(c)
                })
            }) : i = g.find(f ? d.options.singlePageDelegate + "[data-cbp-singlePage=" + f + "]" : d.options.singlePageDelegate), d.singlePage.openSinglePage(i, e[0])
        })), d.singlePageInline = null, d.options.singlePageDelegate && (d.singlePageInline = Object.create(f), d.singlePageInline.init(d, "singlePageInline"), d.$obj.on("click.cbp", d.options.singlePageInlineDelegate, function (a) {
            a.preventDefault(), d.singlePageInline.openSinglePageInline(d.blocksOn, this)
        }))
    }, d.prototype.detectScope = function (b) {
        var d, e, f;
        return d = b.closest(".cbp-popup-singlePageInline"), d.length ? (f = b.closest(".cbp", d[0]), f.length ? f : d) : (e = b.closest(".cbp-popup-singlePage"), e.length ? (f = b.closest(".cbp", e[0]), f.length ? f : e) : (f = b.closest(".cbp"), f.length ? f : a(c.body)))
    }, d.prototype.destroy = function () {
        var b = this.parent;
        a(c.body).off("click.cbp"), g = !1, h = !1, b.lightbox && b.lightbox.destroy(), b.singlePage && b.singlePage.destroy(), b.singlePageInline && b.singlePageInline.destroy()
    }, e.Plugins.PopUp = function (a) {
        return new d(a)
    }
}(jQuery, window, document), function (a, b, c, d) {
    "use strict";
    var e = a.fn.cubeportfolio.Constructor;
    e.Private = {
        checkInstance: function (b) {
            var c = a.data(this, "cubeportfolio");
            if (!c)throw new Error("cubeportfolio is not initialized. Initialize it before calling " + b + " method!");
            return c
        }, browserInfo: function () {
            var a, c, f, g = e.Private, h = navigator.appVersion;
            g.browser = -1 !== h.indexOf("MSIE 8.") ? "ie8" : -1 !== h.indexOf("MSIE 9.") ? "ie9" : -1 !== h.indexOf("MSIE 10.") ? "ie10" : b.ActiveXObject || "ActiveXObject"in b ? "ie11" : /android/gi.test(h) ? "android" : /iphone|ipad|ipod/gi.test(h) ? "ios" : /chrome/gi.test(h) ? "chrome" : "", f = g.styleSupport("perspective"), typeof f !== d && (a = g.styleSupport("transition"), g.transitionend = {
                WebkitTransition: "webkitTransitionEnd",
                transition: "transitionend"
            }[a], c = g.styleSupport("animation"), g.animationend = {
                WebkitAnimation: "webkitAnimationEnd",
                animation: "animationend"
            }[c], g.animationDuration = {
                WebkitAnimation: "webkitAnimationDuration",
                animation: "animationDuration"
            }[c], g.animationDelay = {
                WebkitAnimation: "webkitAnimationDelay",
                animation: "animationDelay"
            }[c], g.transform = g.styleSupport("transform"), a && c && g.transform && (g.modernBrowser = !0))
        }, styleSupport: function (a) {
            var b, d = "Webkit" + a.charAt(0).toUpperCase() + a.slice(1), e = c.createElement("div");
            return a in e.style ? b = a : d in e.style && (b = d), e = null, b
        }
    }, e.Private.browserInfo()
}(jQuery, window, document), function (a, b, c) {
    "use strict";
    var d = a.fn.cubeportfolio.Constructor;
    d.Public = {
        init: function (a, b) {
            new d(this, a, b)
        }, destroy: function (e) {
            var f = d.Private.checkInstance.call(this, "destroy");
            f._triggerEvent("beforeDestroy"), a.removeData(this, "cubeportfolio"), f.blocks.each(function () {
                a.removeData(this, "cbp-wxh")
            }), f.$obj.removeClass("cbp-ready cbp-addItemscbp-cols-" + f.cols).removeAttr("style"), f.$ul.removeClass("cbp-wrapper"), a(b).off("resize.cbp"), f.$obj.off(".cbp"), a(c).off(".cbp"), f.blocks.removeClass("cbp-item-off").removeAttr("style"), f.blocks.find(".cbp-item-wrapper").children().unwrap(), f.options.caption && f._captionDestroy(), f._destroySlider(), f.$ul.unwrap(), f.addedWrapp && f.blocks.unwrap(), a.each(f._plugins, function (a, b) {
                "function" == typeof b.destroy && b.destroy()
            }), a.isFunction(e) && e.call(f), f._triggerEvent("afterDestroy")
        }, filter: function (b, c) {
            var e, f = d.Private.checkInstance.call(this, "filter");
            a.isFunction(c) && f._registerEvent("filterFinish", c, !0), f.isAnimating || f.defaultFilter === b || (f.isAnimating = !0, f.defaultFilter = b, f.singlePageInline && f.singlePageInline.isOpen ? f.singlePageInline.close("promise", {
                callback: function () {
                    f._filter(b)
                }
            }) : f._filter(b), f.options.filterDeeplinking && (e = location.href.replace(/#cbpf=(.*?)([#|?&]|$)/gi, ""), location.href = e + "#cbpf=" + b, f.singlePage && f.singlePage.url && (f.singlePage.url = location.href)))
        }, showCounter: function (b, c) {
            var e = d.Private.checkInstance.call(this, "showCounter");
            e.elems = b, a.each(b, function () {
                var b, c = a(this), d = c.data("filter");
                b = e.blocks.filter(d).length, c.find(".cbp-filter-counter").text(b)
            }), a.isFunction(c) && c.call(e)
        }, appendItems: function (a, b) {
            var c = d.Private.checkInstance.call(this, "appendItems");
            c.isAnimating || (c.isAnimating = !0, c.singlePageInline && c.singlePageInline.isOpen ? c.singlePageInline.close("promise", {
                callback: function () {
                    c._addItems(a, b)
                }
            }) : c._addItems(a, b))
        }
    }
}(jQuery, window, document), "function" != typeof Object.create && (Object.create = function (a) {
    function b() {
    }

    return b.prototype = a, new b
}), jQuery.expr[":"].uncached = function (a) {
    if (!jQuery(a).is('img[src][src!=""]'))return !1;
    var b = new Image;
    return b.src = a.src, b.complete ? void 0 !== b.naturalWidth && 0 === b.naturalWidth ? !0 : !1 : !0
}, function () {
    for (var a = 0, b = ["moz", "webkit"], c = 0; c < b.length && !window.requestAnimationFrame; ++c)window.requestAnimationFrame = window[b[c] + "RequestAnimationFrame"], window.cancelAnimationFrame = window[b[c] + "CancelAnimationFrame"] || window[b[c] + "CancelRequestAnimationFrame"];
    window.requestAnimationFrame || (window.requestAnimationFrame = function (b) {
        var c = (new Date).getTime(), d = Math.max(0, 16 - (c - a)), e = window.setTimeout(function () {
            b(c + d)
        }, d);
        return a = c + d, e
    }), window.cancelAnimationFrame || (window.cancelAnimationFrame = function (a) {
        clearTimeout(a)
    })
}(), function (a) {
    "use strict";
    function b(a) {
        var b = this;
        b.parent = a, a.filterLayout = b.filterLayout
    }

    var c = a.fn.cubeportfolio.Constructor;
    b.prototype.filterLayout = function (b) {
        function d() {
            e.blocks.removeClass("cbp-item-on2off cbp-item-off2on cbp-item-on2on").each(function (b, d) {
                var e = a(d).data("cbp");
                e.left = e.leftNew, e.top = e.topNew, d.style.left = e.left + "px", d.style.top = e.top + "px", d.style[c.Private.transform] = ""
            }), e.blocksOff.addClass("cbp-item-off"), e.$obj.removeClass("cbp-animation-" + e.options.animationType), e.filterFinish()
        }

        var e = this;
        e.$obj.addClass("cbp-animation-" + e.options.animationType), e.blocksOnInitial.filter(b).addClass("cbp-item-on2on").each(function (b, d) {
            var e = a(d).data("cbp");
            d.style[c.Private.transform] = "translate3d(" + (e.leftNew - e.left) + "px, " + (e.topNew - e.top) + "px, 0)"
        }), e.blocksOn2Off = e.blocksOnInitial.not(b).addClass("cbp-item-on2off"), e.blocksOff2On = e.blocksOn.filter(".cbp-item-off").removeClass("cbp-item-off").addClass("cbp-item-off2on").each(function (b, c) {
            var d = a(c).data("cbp");
            d.left = d.leftNew, d.top = d.topNew, c.style.left = d.left + "px", c.style.top = d.top + "px"
        }), e.blocksOn2Off.length ? e.blocksOn2Off.last().data("cbp").wrapper.one(c.Private.animationend, d) : e.blocksOff2On.length ? e.blocksOff2On.last().data("cbp").wrapper.one(c.Private.animationend, d) : d(), e._resizeMainContainer()
    }, b.prototype.destroy = function () {
        var a = this.parent;
        a.$obj.removeClass("cbp-animation-" + a.options.animationType)
    }, c.Plugins.AnimationClassic = function (d) {
        return !c.Private.modernBrowser || a.inArray(d.options.animationType, ["boxShadow", "fadeOut", "flipBottom", "flipOut", "quicksand", "scaleSides", "skew"]) < 0 ? null : new b(d)
    }
}(jQuery, window, document), function (a) {
    "use strict";
    function b(a) {
        var b = this;
        b.parent = a, a.filterLayout = b.filterLayout
    }

    var c = a.fn.cubeportfolio.Constructor;
    b.prototype.filterLayout = function () {
        function b() {
            d.wrapper[0].removeChild(e), "sequentially" === d.options.animationType && d.blocksOn.each(function (b, d) {
                a(d).data("cbp").wrapper[0].style[c.Private.animationDelay] = ""
            }), d.$obj.removeClass("cbp-animation-" + d.options.animationType), d.filterFinish()
        }

        var d = this, e = d.$ul[0].cloneNode(!0);
        e.setAttribute("class", "cbp-wrapper-helper"), d.wrapper[0].insertBefore(e, d.$ul[0]), requestAnimationFrame(function () {
            d.$obj.addClass("cbp-animation-" + d.options.animationType), d.blocksOff.addClass("cbp-item-off"), d.blocksOn.removeClass("cbp-item-off").each(function (b, e) {
                var f = a(e).data("cbp");
                f.left = f.leftNew, f.top = f.topNew, e.style.left = f.left + "px", e.style.top = f.top + "px", "sequentially" === d.options.animationType && (f.wrapper[0].style[c.Private.animationDelay] = 60 * b + "ms")
            }), d.blocksOn.length ? d.blocksOn.last().data("cbp").wrapper.one(c.Private.animationend, b) : d.blocksOnInitial.length ? d.blocksOnInitial.last().data("cbp").wrapper.one(c.Private.animationend, b) : b(), d._resizeMainContainer()
        })
    }, b.prototype.destroy = function () {
        var a = this.parent;
        a.$obj.removeClass("cbp-animation-" + a.options.animationType)
    }, c.Plugins.AnimationClone = function (d) {
        return !c.Private.modernBrowser || a.inArray(d.options.animationType, ["fadeOutTop", "slideLeft", "sequentially"]) < 0 ? null : new b(d)
    }
}(jQuery, window, document), function (a) {
    "use strict";
    function b(a) {
        var b = this;
        b.parent = a, a.filterLayout = b.filterLayout
    }

    var c = a.fn.cubeportfolio.Constructor;
    b.prototype.filterLayout = function () {
        function b() {
            e.wrapper[0].removeChild(d), e.$obj.removeClass("cbp-animation-" + e.options.animationType), e.blocks.each(function (b, d) {
                a(d).data("cbp").wrapper[0].style[c.Private.animationDelay] = ""
            }), e.filterFinish()
        }

        var d, e = this;
        d = e.$ul[0].cloneNode(!0), d.setAttribute("class", "cbp-wrapper-helper"), e.wrapper[0].insertBefore(d, e.$ul[0]), a(d).find(".cbp-item").not(".cbp-item-off").children(".cbp-item-wrapper").each(function (a, b) {
            b.style[c.Private.animationDelay] = 50 * a + "ms"
        }), requestAnimationFrame(function () {
            e.$obj.addClass("cbp-animation-" + e.options.animationType), e.blocksOff.addClass("cbp-item-off"), e.blocksOn.removeClass("cbp-item-off").each(function (b, d) {
                var e = a(d).data("cbp");
                e.left = e.leftNew, e.top = e.topNew, d.style.left = e.left + "px", d.style.top = e.top + "px", e.wrapper[0].style[c.Private.animationDelay] = 50 * b + "ms"
            }), e.blocksOn.length ? e.blocksOn.last().data("cbp").wrapper.one(c.Private.animationend, b) : e.blocksOnInitial.length ? e.blocksOnInitial.last().data("cbp").wrapper.one(c.Private.animationend, b) : b(), e._resizeMainContainer()
        })
    }, b.prototype.destroy = function () {
        var a = this.parent;
        a.$obj.removeClass("cbp-animation-" + a.options.animationType)
    }, c.Plugins.AnimationCloneDelay = function (d) {
        return !c.Private.modernBrowser || a.inArray(d.options.animationType, ["3dflip", "flipOutDelay", "foldLeft", "frontRow", "rotateRoom", "rotateSides", "scaleDown", "slideDelay", "unfold"]) < 0 ? null : new b(d)
    }
}(jQuery, window, document), function (a) {
    "use strict";
    function b(a) {
        var b = this;
        b.parent = a, a.filterLayout = b.filterLayout
    }

    var c = a.fn.cubeportfolio.Constructor;
    b.prototype.filterLayout = function () {
        function b() {
            d.wrapper[0].removeChild(e), d.$obj.removeClass("cbp-animation-" + d.options.animationType), d.filterFinish()
        }

        var d = this, e = d.$ul[0].cloneNode(!0);
        e.setAttribute("class", "cbp-wrapper-helper"), d.wrapper[0].insertBefore(e, d.$ul[0]), requestAnimationFrame(function () {
            d.$obj.addClass("cbp-animation-" + d.options.animationType), d.blocksOff.addClass("cbp-item-off"), d.blocksOn.removeClass("cbp-item-off").each(function (b, c) {
                var d = a(c).data("cbp");
                d.left = d.leftNew, d.top = d.topNew, c.style.left = d.left + "px", c.style.top = d.top + "px"
            }), d.blocksOn.length ? d.$ul.one(c.Private.animationend, b) : d.blocksOnInitial.length ? a(e).one(c.Private.animationend, b) : b(), d._resizeMainContainer()
        })
    }, b.prototype.destroy = function () {
        var a = this.parent;
        a.$obj.removeClass("cbp-animation-" + a.options.animationType)
    }, c.Plugins.AnimationWrapper = function (d) {
        return !c.Private.modernBrowser || a.inArray(d.options.animationType, ["bounceBottom", "bounceLeft", "bounceTop", "moveLeft"]) < 0 ? null : new b(d)
    }
}(jQuery, window, document), function (a) {
    "use strict";
    function b(b) {
        var c = this;
        c.parent = b, b._registerEvent("initFinish", function () {
            b.$obj.on("click.cbp", ".cbp-caption-defaultWrap", function (c) {
                if (c.preventDefault(), !b.isAnimating) {
                    b.isAnimating = !0;
                    var d = a(this), e = d.next(), f = d.parent(), g = {
                        position: "relative",
                        height: e.outerHeight(!0)
                    }, h = {position: "relative", height: 0};
                    if (b.$obj.addClass("cbp-caption-expand-active"), f.hasClass("cbp-caption-expand-open")) {
                        var i = h;
                        h = g, g = i, f.removeClass("cbp-caption-expand-open")
                    }
                    e.css(g), b._gridAdjust(), b._layout(), b.positionateItems(), b.$obj.one("pluginResize.cbp", function () {
                        b.isAnimating = !1, b.$obj.removeClass("cbp-caption-expand-active"), 0 === g.height && (f.removeClass("cbp-caption-expand-open"), e.attr("style", ""))
                    }), b._resizeMainContainer(), e.css(h), requestAnimationFrame(function () {
                        f.addClass("cbp-caption-expand-open"), e.css(g), "slider" === b.options.layoutMode && b._updateSlider(), b._triggerEvent("resizeGrid")
                    })
                }
            })
        }, !0)
    }

    var c = a.fn.cubeportfolio.Constructor;
    b.prototype.destroy = function () {
        this.parent.$obj.find(".cbp-caption-defaultWrap").off("click.cbp").parent().removeClass("cbp-caption-expand-active")
    }, c.Plugins.CaptionExpand = function (a) {
        return "expand" !== a.options.caption ? null : new b(a)
    }
}(jQuery, window, document), function (a) {
    "use strict";
    function b(a) {
        a._skipNextEvent("delayFrame"), a._registerEvent("initEndWrite", function () {
            a.blocksOn.each(function (b, d) {
                d.style[c.Private.animationDelay] = b * a.options.displayTypeSpeed + "ms"
            }), a.$obj.addClass("cbp-displayType-bottomToTop"), a.blocksOn.last().one(c.Private.animationend, function () {
                a.$obj.removeClass("cbp-displayType-bottomToTop"), a.blocksOn.each(function (a, b) {
                    b.style[c.Private.animationDelay] = ""
                }), a._triggerEvent("delayFrame")
            })
        }, !0)
    }

    var c = a.fn.cubeportfolio.Constructor;
    c.Plugins.BottomToTop = function (a) {
        return c.Private.modernBrowser && "bottomToTop" === a.options.displayType && 0 !== a.blocksOn.length ? new b(a) : null
    }
}(jQuery, window, document), function (a) {
    "use strict";
    function b(a) {
        a._skipNextEvent("delayFrame"), a._registerEvent("initEndWrite", function () {
            a.obj.style[c.Private.animationDuration] = a.options.displayTypeSpeed + "ms", a.$obj.addClass("cbp-displayType-fadeInToTop"), a.$obj.one(c.Private.animationend, function () {
                a.$obj.removeClass("cbp-displayType-fadeInToTop"), a.obj.style[c.Private.animationDuration] = "", a._triggerEvent("delayFrame")
            })
        }, !0)
    }

    var c = a.fn.cubeportfolio.Constructor;
    c.Plugins.FadeInToTop = function (a) {
        return c.Private.modernBrowser && "fadeInToTop" === a.options.displayType && 0 !== a.blocksOn.length ? new b(a) : null
    }
}(jQuery, window, document), function (a) {
    "use strict";
    function b(a) {
        a._skipNextEvent("delayFrame"), a._registerEvent("initEndWrite", function () {
            a.obj.style[c.Private.animationDuration] = a.options.displayTypeSpeed + "ms", a.$obj.addClass("cbp-displayType-lazyLoading"), a.$obj.one(c.Private.animationend, function () {
                a.$obj.removeClass("cbp-displayType-lazyLoading"), a.obj.style[c.Private.animationDuration] = "", a._triggerEvent("delayFrame")
            })
        }, !0)
    }

    var c = a.fn.cubeportfolio.Constructor;
    c.Plugins.LazyLoading = function (a) {
        return !c.Private.modernBrowser || "lazyLoading" !== a.options.displayType && "fadeIn" !== a.options.displayType || 0 === a.blocksOn.length ? null : new b(a)
    }
}(jQuery, window, document), function (a) {
    "use strict";
    function b(a) {
        a._skipNextEvent("delayFrame"), a._registerEvent("initEndWrite", function () {
            a.blocksOn.each(function (b, d) {
                d.style[c.Private.animationDelay] = b * a.options.displayTypeSpeed + "ms"
            }), a.$obj.addClass("cbp-displayType-sequentially"), a.blocksOn.last().one(c.Private.animationend, function () {
                a.$obj.removeClass("cbp-displayType-sequentially"), a.blocksOn.each(function (a, b) {
                    b.style[c.Private.animationDelay] = ""
                }), a._triggerEvent("delayFrame")
            })
        }, !0)
    }

    var c = a.fn.cubeportfolio.Constructor;
    c.Plugins.DisplaySequentially = function (a) {
        return c.Private.modernBrowser && "sequentially" === a.options.displayType && 0 !== a.blocksOn.length ? new b(a) : null
    }
}(jQuery, window, document);
/*!
 * fancyBox - jQuery Plugin
 * version: 2.1.5 (Fri, 14 Jun 2013)
 * @requires jQuery v1.6 or later
 *
 * Examples at http://fancyapps.com/fancybox/
 * License: www.fancyapps.com/fancybox/#license
 *
 * Copyright 2012 Janis Skarnelis - janis@fancyapps.com
 *
 */

(function (window, document, $, undefined) {
    "use strict";

    var H = $("html"),
        W = $(window),
        D = $(document),
        F = $.fancybox = function () {
            F.open.apply(this, arguments);
        },
        IE = navigator.userAgent.match(/msie/i),
        didUpdate = null,
        isTouch = document.createTouch !== undefined,

        isQuery = function (obj) {
            return obj && obj.hasOwnProperty && obj instanceof $;
        },
        isString = function (str) {
            return str && $.type(str) === "string";
        },
        isPercentage = function (str) {
            return isString(str) && str.indexOf('%') > 0;
        },
        isScrollable = function (el) {
            return (el && !(el.style.overflow && el.style.overflow === 'hidden') && ((el.clientWidth && el.scrollWidth > el.clientWidth) || (el.clientHeight && el.scrollHeight > el.clientHeight)));
        },
        getScalar = function (orig, dim) {
            var value = parseInt(orig, 10) || 0;

            if (dim && isPercentage(orig)) {
                value = F.getViewport()[dim] / 100 * value;
            }

            return Math.ceil(value);
        },
        getValue = function (value, dim) {
            return getScalar(value, dim) + 'px';
        };

    $.extend(F, {
        // The current version of fancyBox
        version: '2.1.5',

        defaults: {
            padding: 15,
            margin: 20,

            width: 800,
            height: 600,
            minWidth: 100,
            minHeight: 100,
            maxWidth: 9999,
            maxHeight: 9999,
            pixelRatio: 1, // Set to 2 for retina display support

            autoSize: true,
            autoHeight: false,
            autoWidth: false,

            autoResize: true,
            autoCenter: !isTouch,
            fitToView: true,
            aspectRatio: false,
            topRatio: 0.5,
            leftRatio: 0.5,

            scrolling: 'auto', // 'auto', 'yes' or 'no'
            wrapCSS: '',

            arrows: true,
            closeBtn: true,
            closeClick: false,
            nextClick: false,
            mouseWheel: true,
            autoPlay: false,
            playSpeed: 3000,
            preload: 3,
            modal: false,
            loop: true,

            ajax: {
                dataType: 'html',
                headers: {'X-fancyBox': true}
            },
            iframe: {
                scrolling: 'auto',
                preload: true
            },
            swf: {
                wmode: 'transparent',
                allowfullscreen: 'true',
                allowscriptaccess: 'always'
            },

            keys: {
                next: {
                    13: 'left', // enter
                    34: 'up',   // page down
                    39: 'left', // right arrow
                    40: 'up'    // down arrow
                },
                prev: {
                    8: 'right',  // backspace
                    33: 'down',   // page up
                    37: 'right',  // left arrow
                    38: 'down'    // up arrow
                },
                close: [27], // escape key
                play: [32], // space - start/stop slideshow
                toggle: [70]  // letter "f" - toggle fullscreen
            },

            direction: {
                next: 'left',
                prev: 'right'
            },

            scrollOutside: true,

            // Override some properties
            index: 0,
            type: null,
            href: null,
            content: null,
            title: null,

            // HTML templates
            tpl: {
                wrap: '<div class="fancybox-wrap" tabIndex="-1"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>',
                image: '<img class="fancybox-image" src="{href}" alt="" />',
                iframe: '<iframe id="fancybox-frame{rnd}" name="fancybox-frame{rnd}" class="fancybox-iframe" frameborder="0" vspace="0" hspace="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen' + (IE ? ' allowtransparency="true"' : '') + '></iframe>',
                error: '<p class="fancybox-error">The requested content cannot be loaded.<br/>Please try again later.</p>',
                closeBtn: '<a title="Close" class="fancybox-item fancybox-close" href="javascript:;"></a>',
                next: '<a title="Next" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
                prev: '<a title="Previous" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
            },

            // Properties for each animation type
            // Opening fancyBox
            openEffect: 'fade', // 'elastic', 'fade' or 'none'
            openSpeed: 250,
            openEasing: 'swing',
            openOpacity: true,
            openMethod: 'zoomIn',

            // Closing fancyBox
            closeEffect: 'fade', // 'elastic', 'fade' or 'none'
            closeSpeed: 250,
            closeEasing: 'swing',
            closeOpacity: true,
            closeMethod: 'zoomOut',

            // Changing next gallery item
            nextEffect: 'elastic', // 'elastic', 'fade' or 'none'
            nextSpeed: 250,
            nextEasing: 'swing',
            nextMethod: 'changeIn',

            // Changing previous gallery item
            prevEffect: 'elastic', // 'elastic', 'fade' or 'none'
            prevSpeed: 250,
            prevEasing: 'swing',
            prevMethod: 'changeOut',

            // Enable default helpers
            helpers: {
                overlay: true,
                title: true
            },

            // Callbacks
            onCancel: $.noop, // If canceling
            beforeLoad: $.noop, // Before loading
            afterLoad: $.noop, // After loading
            beforeShow: $.noop, // Before changing in current item
            afterShow: $.noop, // After opening
            beforeChange: $.noop, // Before changing gallery item
            beforeClose: $.noop, // Before closing
            afterClose: $.noop  // After closing
        },

        //Current state
        group: {}, // Selected group
        opts: {}, // Group options
        previous: null,  // Previous element
        coming: null,  // Element being loaded
        current: null,  // Currently loaded element
        isActive: false, // Is activated
        isOpen: false, // Is currently open
        isOpened: false, // Have been fully opened at least once

        wrap: null,
        skin: null,
        outer: null,
        inner: null,

        player: {
            timer: null,
            isActive: false
        },

        // Loaders
        ajaxLoad: null,
        imgPreload: null,

        // Some collections
        transitions: {},
        helpers: {},

        /*
         *	Static methods
         */

        open: function (group, opts) {
            if (!group) {
                return;
            }

            if (!$.isPlainObject(opts)) {
                opts = {};
            }

            // Close if already active
            if (false === F.close(true)) {
                return;
            }

            // Normalize group
            if (!$.isArray(group)) {
                group = isQuery(group) ? $(group).get() : [group];
            }

            // Recheck if the type of each element is `object` and set content type (image, ajax, etc)
            $.each(group, function (i, element) {
                var obj = {},
                    href,
                    title,
                    content,
                    type,
                    rez,
                    hrefParts,
                    selector;

                if ($.type(element) === "object") {
                    // Check if is DOM element
                    if (element.nodeType) {
                        element = $(element);
                    }

                    if (isQuery(element)) {
                        obj = {
                            href: element.data('fancybox-href') || element.attr('href'),
                            title: element.data('fancybox-title') || element.attr('title'),
                            isDom: true,
                            element: element
                        };

                        if ($.metadata) {
                            $.extend(true, obj, element.metadata());
                        }

                    } else {
                        obj = element;
                    }
                }

                href = opts.href || obj.href || (isString(element) ? element : null);
                title = opts.title !== undefined ? opts.title : obj.title || '';

                content = opts.content || obj.content;
                type = content ? 'html' : (opts.type || obj.type);

                if (!type && obj.isDom) {
                    type = element.data('fancybox-type');

                    if (!type) {
                        rez = element.prop('class').match(/fancybox\.(\w+)/);
                        type = rez ? rez[1] : null;
                    }
                }

                if (isString(href)) {
                    // Try to guess the content type
                    if (!type) {
                        if (F.isImage(href)) {
                            type = 'image';

                        } else if (F.isSWF(href)) {
                            type = 'swf';

                        } else if (href.charAt(0) === '#') {
                            type = 'inline';

                        } else if (isString(element)) {
                            type = 'html';
                            content = element;
                        }
                    }

                    // Split url into two pieces with source url and content selector, e.g,
                    // "/mypage.html #my_id" will load "/mypage.html" and display element having id "my_id"
                    if (type === 'ajax') {
                        hrefParts = href.split(/\s+/, 2);
                        href = hrefParts.shift();
                        selector = hrefParts.shift();
                    }
                }

                if (!content) {
                    if (type === 'inline') {
                        if (href) {
                            content = $(isString(href) ? href.replace(/.*(?=#[^\s]+$)/, '') : href); //strip for ie7

                        } else if (obj.isDom) {
                            content = element;
                        }

                    } else if (type === 'html') {
                        content = href;

                    } else if (!type && !href && obj.isDom) {
                        type = 'inline';
                        content = element;
                    }
                }

                $.extend(obj, {
                    href: href,
                    type: type,
                    content: content,
                    title: title,
                    selector: selector
                });

                group[i] = obj;
            });

            // Extend the defaults
            F.opts = $.extend(true, {}, F.defaults, opts);

            // All options are merged recursive except keys
            if (opts.keys !== undefined) {
                F.opts.keys = opts.keys ? $.extend({}, F.defaults.keys, opts.keys) : false;
            }

            F.group = group;

            return F._start(F.opts.index);
        },

        // Cancel image loading or abort ajax request
        cancel: function () {
            var coming = F.coming;

            if (!coming || false === F.trigger('onCancel')) {
                return;
            }

            F.hideLoading();

            if (F.ajaxLoad) {
                F.ajaxLoad.abort();
            }

            F.ajaxLoad = null;

            if (F.imgPreload) {
                F.imgPreload.onload = F.imgPreload.onerror = null;
            }

            if (coming.wrap) {
                coming.wrap.stop(true, true).trigger('onReset').remove();
            }

            F.coming = null;

            // If the first item has been canceled, then clear everything
            if (!F.current) {
                F._afterZoomOut(coming);
            }
        },

        // Start closing animation if is open; remove immediately if opening/closing
        close: function (event) {
            F.cancel();

            if (false === F.trigger('beforeClose')) {
                return;
            }

            F.unbindEvents();

            if (!F.isActive) {
                return;
            }

            if (!F.isOpen || event === true) {
                $('.fancybox-wrap').stop(true).trigger('onReset').remove();

                F._afterZoomOut();

            } else {
                F.isOpen = F.isOpened = false;
                F.isClosing = true;

                $('.fancybox-item, .fancybox-nav').remove();

                F.wrap.stop(true, true).removeClass('fancybox-opened');

                F.transitions[F.current.closeMethod]();
            }
        },

        // Manage slideshow:
        //   $.fancybox.play(); - toggle slideshow
        //   $.fancybox.play( true ); - start
        //   $.fancybox.play( false ); - stop
        play: function (action) {
            var clear = function () {
                    clearTimeout(F.player.timer);
                },
                set = function () {
                    clear();

                    if (F.current && F.player.isActive) {
                        F.player.timer = setTimeout(F.next, F.current.playSpeed);
                    }
                },
                stop = function () {
                    clear();

                    D.unbind('.player');

                    F.player.isActive = false;

                    F.trigger('onPlayEnd');
                },
                start = function () {
                    if (F.current && (F.current.loop || F.current.index < F.group.length - 1)) {
                        F.player.isActive = true;

                        D.bind({
                            'onCancel.player beforeClose.player': stop,
                            'onUpdate.player': set,
                            'beforeLoad.player': clear
                        });

                        set();

                        F.trigger('onPlayStart');
                    }
                };

            if (action === true || (!F.player.isActive && action !== false)) {
                start();
            } else {
                stop();
            }
        },

        // Navigate to next gallery item
        next: function (direction) {
            var current = F.current;

            if (current) {
                if (!isString(direction)) {
                    direction = current.direction.next;
                }

                F.jumpto(current.index + 1, direction, 'next');
            }
        },

        // Navigate to previous gallery item
        prev: function (direction) {
            var current = F.current;

            if (current) {
                if (!isString(direction)) {
                    direction = current.direction.prev;
                }

                F.jumpto(current.index - 1, direction, 'prev');
            }
        },

        // Navigate to gallery item by index
        jumpto: function (index, direction, router) {
            var current = F.current;

            if (!current) {
                return;
            }

            index = getScalar(index);

            F.direction = direction || current.direction[(index >= current.index ? 'next' : 'prev')];
            F.router = router || 'jumpto';

            if (current.loop) {
                if (index < 0) {
                    index = current.group.length + (index % current.group.length);
                }

                index = index % current.group.length;
            }

            if (current.group[index] !== undefined) {
                F.cancel();

                F._start(index);
            }
        },

        // Center inside viewport and toggle position type to fixed or absolute if needed
        reposition: function (e, onlyAbsolute) {
            var current = F.current,
                wrap = current ? current.wrap : null,
                pos;

            if (wrap) {
                pos = F._getPosition(onlyAbsolute);

                if (e && e.type === 'scroll') {
                    delete pos.position;

                    wrap.stop(true, true).animate(pos, 200);

                } else {
                    wrap.css(pos);

                    current.pos = $.extend({}, current.dim, pos);
                }
            }
        },

        update: function (e) {
            var type = (e && e.type),
                anyway = !type || type === 'orientationchange';

            if (anyway) {
                clearTimeout(didUpdate);

                didUpdate = null;
            }

            if (!F.isOpen || didUpdate) {
                return;
            }

            didUpdate = setTimeout(function () {
                var current = F.current;

                if (!current || F.isClosing) {
                    return;
                }

                F.wrap.removeClass('fancybox-tmp');

                if (anyway || type === 'load' || (type === 'resize' && current.autoResize)) {
                    F._setDimension();
                }

                if (!(type === 'scroll' && current.canShrink)) {
                    F.reposition(e);
                }

                F.trigger('onUpdate');

                didUpdate = null;

            }, (anyway && !isTouch ? 0 : 300));
        },

        // Shrink content to fit inside viewport or restore if resized
        toggle: function (action) {
            if (F.isOpen) {
                F.current.fitToView = $.type(action) === "boolean" ? action : !F.current.fitToView;

                // Help browser to restore document dimensions
                if (isTouch) {
                    F.wrap.removeAttr('style').addClass('fancybox-tmp');

                    F.trigger('onUpdate');
                }

                F.update();
            }
        },

        hideLoading: function () {
            D.unbind('.loading');

            $('#fancybox-loading').remove();
        },

        showLoading: function () {
            var el, viewport;

            F.hideLoading();

            el = $('<div id="fancybox-loading"><div></div></div>').click(F.cancel).appendTo('body');

            // If user will press the escape-button, the request will be canceled
            D.bind('keydown.loading', function (e) {
                if ((e.which || e.keyCode) === 27) {
                    e.preventDefault();

                    F.cancel();
                }
            });

            if (!F.defaults.fixed) {
                viewport = F.getViewport();

                el.css({
                    position: 'absolute',
                    top: (viewport.h * 0.5) + viewport.y,
                    left: (viewport.w * 0.5) + viewport.x
                });
            }
        },

        getViewport: function () {
            var locked = (F.current && F.current.locked) || false,
                rez = {
                    x: W.scrollLeft(),
                    y: W.scrollTop()
                };

            if (locked) {
                rez.w = locked[0].clientWidth;
                rez.h = locked[0].clientHeight;

            } else {
                // See http://bugs.jquery.com/ticket/6724
                rez.w = isTouch && window.innerWidth ? window.innerWidth : W.width();
                rez.h = isTouch && window.innerHeight ? window.innerHeight : W.height();
            }

            return rez;
        },

        // Unbind the keyboard / clicking actions
        unbindEvents: function () {
            if (F.wrap && isQuery(F.wrap)) {
                F.wrap.unbind('.fb');
            }

            D.unbind('.fb');
            W.unbind('.fb');
        },

        bindEvents: function () {
            var current = F.current,
                keys;

            if (!current) {
                return;
            }

            // Changing document height on iOS devices triggers a 'resize' event,
            // that can change document height... repeating infinitely
            W.bind('orientationchange.fb' + (isTouch ? '' : ' resize.fb') + (current.autoCenter && !current.locked ? ' scroll.fb' : ''), F.update);

            keys = current.keys;

            if (keys) {
                D.bind('keydown.fb', function (e) {
                    var code = e.which || e.keyCode,
                        target = e.target || e.srcElement;

                    // Skip esc key if loading, because showLoading will cancel preloading
                    if (code === 27 && F.coming) {
                        return false;
                    }

                    // Ignore key combinations and key events within form elements
                    if (!e.ctrlKey && !e.altKey && !e.shiftKey && !e.metaKey && !(target && (target.type || $(target).is('[contenteditable]')))) {
                        $.each(keys, function (i, val) {
                            if (current.group.length > 1 && val[code] !== undefined) {
                                F[i](val[code]);

                                e.preventDefault();
                                return false;
                            }

                            if ($.inArray(code, val) > -1) {
                                F[i]();

                                e.preventDefault();
                                return false;
                            }
                        });
                    }
                });
            }

            if ($.fn.mousewheel && current.mouseWheel) {
                F.wrap.bind('mousewheel.fb', function (e, delta, deltaX, deltaY) {
                    var target = e.target || null,
                        parent = $(target),
                        canScroll = false;

                    while (parent.length) {
                        if (canScroll || parent.is('.fancybox-skin') || parent.is('.fancybox-wrap')) {
                            break;
                        }

                        canScroll = isScrollable(parent[0]);
                        parent = $(parent).parent();
                    }

                    if (delta !== 0 && !canScroll) {
                        if (F.group.length > 1 && !current.canShrink) {
                            if (deltaY > 0 || deltaX > 0) {
                                F.prev(deltaY > 0 ? 'down' : 'left');

                            } else if (deltaY < 0 || deltaX < 0) {
                                F.next(deltaY < 0 ? 'up' : 'right');
                            }

                            e.preventDefault();
                        }
                    }
                });
            }
        },

        trigger: function (event, o) {
            var ret, obj = o || F.coming || F.current;

            if (!obj) {
                return;
            }

            if ($.isFunction(obj[event])) {
                ret = obj[event].apply(obj, Array.prototype.slice.call(arguments, 1));
            }

            if (ret === false) {
                return false;
            }

            if (obj.helpers) {
                $.each(obj.helpers, function (helper, opts) {
                    if (opts && F.helpers[helper] && $.isFunction(F.helpers[helper][event])) {
                        F.helpers[helper][event]($.extend(true, {}, F.helpers[helper].defaults, opts), obj);
                    }
                });
            }

            D.trigger(event);
        },

        isImage: function (str) {
            return isString(str) && str.match(/(^data:image\/.*,)|(\.(jp(e|g|eg)|gif|png|bmp|webp|svg)((\?|#).*)?$)/i);
        },

        isSWF: function (str) {
            return isString(str) && str.match(/\.(swf)((\?|#).*)?$/i);
        },

        _start: function (index) {
            var coming = {},
                obj,
                href,
                type,
                margin,
                padding;

            index = getScalar(index);
            obj = F.group[index] || null;

            if (!obj) {
                return false;
            }

            coming = $.extend(true, {}, F.opts, obj);

            // Convert margin and padding properties to array - top, right, bottom, left
            margin = coming.margin;
            padding = coming.padding;

            if ($.type(margin) === 'number') {
                coming.margin = [margin, margin, margin, margin];
            }

            if ($.type(padding) === 'number') {
                coming.padding = [padding, padding, padding, padding];
            }

            // 'modal' propery is just a shortcut
            if (coming.modal) {
                $.extend(true, coming, {
                    closeBtn: false,
                    closeClick: false,
                    nextClick: false,
                    arrows: false,
                    mouseWheel: false,
                    keys: null,
                    helpers: {
                        overlay: {
                            closeClick: false
                        }
                    }
                });
            }

            // 'autoSize' property is a shortcut, too
            if (coming.autoSize) {
                coming.autoWidth = coming.autoHeight = true;
            }

            if (coming.width === 'auto') {
                coming.autoWidth = true;
            }

            if (coming.height === 'auto') {
                coming.autoHeight = true;
            }

            /*
             * Add reference to the group, so it`s possible to access from callbacks, example:
             * afterLoad : function() {
             *     this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
             * }
             */

            coming.group = F.group;
            coming.index = index;

            // Give a chance for callback or helpers to update coming item (type, title, etc)
            F.coming = coming;

            if (false === F.trigger('beforeLoad')) {
                F.coming = null;

                return;
            }

            type = coming.type;
            href = coming.href;

            if (!type) {
                F.coming = null;

                //If we can not determine content type then drop silently or display next/prev item if looping through gallery
                if (F.current && F.router && F.router !== 'jumpto') {
                    F.current.index = index;

                    return F[F.router](F.direction);
                }

                return false;
            }

            F.isActive = true;

            if (type === 'image' || type === 'swf') {
                coming.autoHeight = coming.autoWidth = false;
                coming.scrolling = 'visible';
            }

            if (type === 'image') {
                coming.aspectRatio = true;
            }

            if (type === 'iframe' && isTouch) {
                coming.scrolling = 'scroll';
            }

            // Build the neccessary markup
            coming.wrap = $(coming.tpl.wrap).addClass('fancybox-' + (isTouch ? 'mobile' : 'desktop') + ' fancybox-type-' + type + ' fancybox-tmp ' + coming.wrapCSS).appendTo(coming.parent || 'body');

            $.extend(coming, {
                skin: $('.fancybox-skin', coming.wrap),
                outer: $('.fancybox-outer', coming.wrap),
                inner: $('.fancybox-inner', coming.wrap)
            });

            $.each(["Top", "Right", "Bottom", "Left"], function (i, v) {
                coming.skin.css('padding' + v, getValue(coming.padding[i]));
            });

            F.trigger('onReady');

            // Check before try to load; 'inline' and 'html' types need content, others - href
            if (type === 'inline' || type === 'html') {
                if (!coming.content || !coming.content.length) {
                    return F._error('content');
                }

            } else if (!href) {
                return F._error('href');
            }

            if (type === 'image') {
                F._loadImage();

            } else if (type === 'ajax') {
                F._loadAjax();

            } else if (type === 'iframe') {
                F._loadIframe();

            } else {
                F._afterLoad();
            }
        },

        _error: function (type) {
            $.extend(F.coming, {
                type: 'html',
                autoWidth: true,
                autoHeight: true,
                minWidth: 0,
                minHeight: 0,
                scrolling: 'no',
                hasError: type,
                content: F.coming.tpl.error
            });

            F._afterLoad();
        },

        _loadImage: function () {
            // Reset preload image so it is later possible to check "complete" property
            var img = F.imgPreload = new Image();

            img.onload = function () {
                this.onload = this.onerror = null;

                F.coming.width = this.width / F.opts.pixelRatio;
                F.coming.height = this.height / F.opts.pixelRatio;

                F._afterLoad();
            };

            img.onerror = function () {
                this.onload = this.onerror = null;

                F._error('image');
            };

            img.src = F.coming.href;

            if (img.complete !== true) {
                F.showLoading();
            }
        },

        _loadAjax: function () {
            var coming = F.coming;

            F.showLoading();

            F.ajaxLoad = $.ajax($.extend({}, coming.ajax, {
                url: coming.href,
                error: function (jqXHR, textStatus) {
                    if (F.coming && textStatus !== 'abort') {
                        F._error('ajax', jqXHR);

                    } else {
                        F.hideLoading();
                    }
                },
                success: function (data, textStatus) {
                    if (textStatus === 'success') {
                        coming.content = data;

                        F._afterLoad();
                    }
                }
            }));
        },

        _loadIframe: function () {
            var coming = F.coming,
                iframe = $(coming.tpl.iframe.replace(/\{rnd\}/g, new Date().getTime()))
                    .attr('scrolling', isTouch ? 'auto' : coming.iframe.scrolling)
                    .attr('src', coming.href);

            // This helps IE
            $(coming.wrap).bind('onReset', function () {
                try {
                    $(this).find('iframe').hide().attr('src', '//about:blank').end().empty();
                } catch (e) {
                }
            });

            if (coming.iframe.preload) {
                F.showLoading();

                iframe.one('load', function () {
                    $(this).data('ready', 1);

                    // iOS will lose scrolling if we resize
                    if (!isTouch) {
                        $(this).bind('load.fb', F.update);
                    }

                    // Without this trick:
                    //   - iframe won't scroll on iOS devices
                    //   - IE7 sometimes displays empty iframe
                    $(this).parents('.fancybox-wrap').width('100%').removeClass('fancybox-tmp').show();

                    F._afterLoad();
                });
            }

            coming.content = iframe.appendTo(coming.inner);

            if (!coming.iframe.preload) {
                F._afterLoad();
            }
        },

        _preloadImages: function () {
            var group = F.group,
                current = F.current,
                len = group.length,
                cnt = current.preload ? Math.min(current.preload, len - 1) : 0,
                item,
                i;

            for (i = 1; i <= cnt; i += 1) {
                item = group[(current.index + i ) % len];

                if (item.type === 'image' && item.href) {
                    new Image().src = item.href;
                }
            }
        },

        _afterLoad: function () {
            var coming = F.coming,
                previous = F.current,
                placeholder = 'fancybox-placeholder',
                current,
                content,
                type,
                scrolling,
                href,
                embed;

            F.hideLoading();

            if (!coming || F.isActive === false) {
                return;
            }

            if (false === F.trigger('afterLoad', coming, previous)) {
                coming.wrap.stop(true).trigger('onReset').remove();

                F.coming = null;

                return;
            }

            if (previous) {
                F.trigger('beforeChange', previous);

                previous.wrap.stop(true).removeClass('fancybox-opened')
                    .find('.fancybox-item, .fancybox-nav')
                    .remove();
            }

            F.unbindEvents();

            current = coming;
            content = coming.content;
            type = coming.type;
            scrolling = coming.scrolling;

            $.extend(F, {
                wrap: current.wrap,
                skin: current.skin,
                outer: current.outer,
                inner: current.inner,
                current: current,
                previous: previous
            });

            href = current.href;

            switch (type) {
                case 'inline':
                case 'ajax':
                case 'html':
                    if (current.selector) {
                        content = $('<div>').html(content).find(current.selector);

                    } else if (isQuery(content)) {
                        if (!content.data(placeholder)) {
                            content.data(placeholder, $('<div class="' + placeholder + '"></div>').insertAfter(content).hide());
                        }

                        content = content.show().detach();

                        current.wrap.bind('onReset', function () {
                            if ($(this).find(content).length) {
                                content.hide().replaceAll(content.data(placeholder)).data(placeholder, false);
                            }
                        });
                    }
                    break;

                case 'image':
                    content = current.tpl.image.replace('{href}', href);
                    break;

                case 'swf':
                    content = '<object id="fancybox-swf" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%"><param name="movie" value="' + href + '"></param>';
                    embed = '';

                    $.each(current.swf, function (name, val) {
                        content += '<param name="' + name + '" value="' + val + '"></param>';
                        embed += ' ' + name + '="' + val + '"';
                    });

                    content += '<embed src="' + href + '" type="application/x-shockwave-flash" width="100%" height="100%"' + embed + '></embed></object>';
                    break;
            }

            if (!(isQuery(content) && content.parent().is(current.inner))) {
                current.inner.append(content);
            }

            // Give a chance for helpers or callbacks to update elements
            F.trigger('beforeShow');

            // Set scrolling before calculating dimensions
            current.inner.css('overflow', scrolling === 'yes' ? 'scroll' : (scrolling === 'no' ? 'hidden' : scrolling));

            // Set initial dimensions and start position
            F._setDimension();

            F.reposition();

            F.isOpen = false;
            F.coming = null;

            F.bindEvents();

            if (!F.isOpened) {
                $('.fancybox-wrap').not(current.wrap).stop(true).trigger('onReset').remove();

            } else if (previous.prevMethod) {
                F.transitions[previous.prevMethod]();
            }

            F.transitions[F.isOpened ? current.nextMethod : current.openMethod]();

            F._preloadImages();
        },

        _setDimension: function () {
            var viewport = F.getViewport(),
                steps = 0,
                canShrink = false,
                canExpand = false,
                wrap = F.wrap,
                skin = F.skin,
                inner = F.inner,
                current = F.current,
                width = current.width,
                height = current.height,
                minWidth = current.minWidth,
                minHeight = current.minHeight,
                maxWidth = current.maxWidth,
                maxHeight = current.maxHeight,
                scrolling = current.scrolling,
                scrollOut = current.scrollOutside ? current.scrollbarWidth : 0,
                margin = current.margin,
                wMargin = getScalar(margin[1] + margin[3]),
                hMargin = getScalar(margin[0] + margin[2]),
                wPadding,
                hPadding,
                wSpace,
                hSpace,
                origWidth,
                origHeight,
                origMaxWidth,
                origMaxHeight,
                ratio,
                width_,
                height_,
                maxWidth_,
                maxHeight_,
                iframe,
                body;

            // Reset dimensions so we could re-check actual size
            wrap.add(skin).add(inner).width('auto').height('auto').removeClass('fancybox-tmp');

            wPadding = getScalar(skin.outerWidth(true) - skin.width());
            hPadding = getScalar(skin.outerHeight(true) - skin.height());

            // Any space between content and viewport (margin, padding, border, title)
            wSpace = wMargin + wPadding;
            hSpace = hMargin + hPadding;

            origWidth = isPercentage(width) ? (viewport.w - wSpace) * getScalar(width) / 100 : width;
            origHeight = isPercentage(height) ? (viewport.h - hSpace) * getScalar(height) / 100 : height;

            if (current.type === 'iframe') {
                iframe = current.content;

                if (current.autoHeight && iframe.data('ready') === 1) {
                    try {
                        if (iframe[0].contentWindow.document.location) {
                            inner.width(origWidth).height(9999);

                            body = iframe.contents().find('body');

                            if (scrollOut) {
                                body.css('overflow-x', 'hidden');
                            }

                            origHeight = body.outerHeight(true);
                        }

                    } catch (e) {
                    }
                }

            } else if (current.autoWidth || current.autoHeight) {
                inner.addClass('fancybox-tmp');

                // Set width or height in case we need to calculate only one dimension
                if (!current.autoWidth) {
                    inner.width(origWidth);
                }

                if (!current.autoHeight) {
                    inner.height(origHeight);
                }

                if (current.autoWidth) {
                    origWidth = inner.width();
                }

                if (current.autoHeight) {
                    origHeight = inner.height();
                }

                inner.removeClass('fancybox-tmp');
            }

            width = getScalar(origWidth);
            height = getScalar(origHeight);

            ratio = origWidth / origHeight;

            // Calculations for the content
            minWidth = getScalar(isPercentage(minWidth) ? getScalar(minWidth, 'w') - wSpace : minWidth);
            maxWidth = getScalar(isPercentage(maxWidth) ? getScalar(maxWidth, 'w') - wSpace : maxWidth);

            minHeight = getScalar(isPercentage(minHeight) ? getScalar(minHeight, 'h') - hSpace : minHeight);
            maxHeight = getScalar(isPercentage(maxHeight) ? getScalar(maxHeight, 'h') - hSpace : maxHeight);

            // These will be used to determine if wrap can fit in the viewport
            origMaxWidth = maxWidth;
            origMaxHeight = maxHeight;

            if (current.fitToView) {
                maxWidth = Math.min(viewport.w - wSpace, maxWidth);
                maxHeight = Math.min(viewport.h - hSpace, maxHeight);
            }

            maxWidth_ = viewport.w - wMargin;
            maxHeight_ = viewport.h - hMargin;

            if (current.aspectRatio) {
                if (width > maxWidth) {
                    width = maxWidth;
                    height = getScalar(width / ratio);
                }

                if (height > maxHeight) {
                    height = maxHeight;
                    width = getScalar(height * ratio);
                }

                if (width < minWidth) {
                    width = minWidth;
                    height = getScalar(width / ratio);
                }

                if (height < minHeight) {
                    height = minHeight;
                    width = getScalar(height * ratio);
                }

            } else {
                width = Math.max(minWidth, Math.min(width, maxWidth));

                if (current.autoHeight && current.type !== 'iframe') {
                    inner.width(width);

                    height = inner.height();
                }

                height = Math.max(minHeight, Math.min(height, maxHeight));
            }

            // Try to fit inside viewport (including the title)
            if (current.fitToView) {
                inner.width(width).height(height);

                wrap.width(width + wPadding);

                // Real wrap dimensions
                width_ = wrap.width();
                height_ = wrap.height();

                if (current.aspectRatio) {
                    while ((width_ > maxWidth_ || height_ > maxHeight_) && width > minWidth && height > minHeight) {
                        if (steps++ > 19) {
                            break;
                        }

                        height = Math.max(minHeight, Math.min(maxHeight, height - 10));
                        width = getScalar(height * ratio);

                        if (width < minWidth) {
                            width = minWidth;
                            height = getScalar(width / ratio);
                        }

                        if (width > maxWidth) {
                            width = maxWidth;
                            height = getScalar(width / ratio);
                        }

                        inner.width(width).height(height);

                        wrap.width(width + wPadding);

                        width_ = wrap.width();
                        height_ = wrap.height();
                    }

                } else {
                    width = Math.max(minWidth, Math.min(width, width - (width_ - maxWidth_)));
                    height = Math.max(minHeight, Math.min(height, height - (height_ - maxHeight_)));
                }
            }

            if (scrollOut && scrolling === 'auto' && height < origHeight && (width + wPadding + scrollOut) < maxWidth_) {
                width += scrollOut;
            }

            inner.width(width).height(height);

            wrap.width(width + wPadding);

            width_ = wrap.width();
            height_ = wrap.height();

            canShrink = (width_ > maxWidth_ || height_ > maxHeight_) && width > minWidth && height > minHeight;
            canExpand = current.aspectRatio ? (width < origMaxWidth && height < origMaxHeight && width < origWidth && height < origHeight) : ((width < origMaxWidth || height < origMaxHeight) && (width < origWidth || height < origHeight));

            $.extend(current, {
                dim: {
                    width: getValue(width_),
                    height: getValue(height_)
                },
                origWidth: origWidth,
                origHeight: origHeight,
                canShrink: canShrink,
                canExpand: canExpand,
                wPadding: wPadding,
                hPadding: hPadding,
                wrapSpace: height_ - skin.outerHeight(true),
                skinSpace: skin.height() - height
            });

            if (!iframe && current.autoHeight && height > minHeight && height < maxHeight && !canExpand) {
                inner.height('auto');
            }
        },

        _getPosition: function (onlyAbsolute) {
            var current = F.current,
                viewport = F.getViewport(),
                margin = current.margin,
                width = F.wrap.width() + margin[1] + margin[3],
                height = F.wrap.height() + margin[0] + margin[2],
                rez = {
                    position: 'absolute',
                    top: margin[0],
                    left: margin[3]
                };

            if (current.autoCenter && current.fixed && !onlyAbsolute && height <= viewport.h && width <= viewport.w) {
                rez.position = 'fixed';

            } else if (!current.locked) {
                rez.top += viewport.y;
                rez.left += viewport.x;
            }

            rez.top = getValue(Math.max(rez.top, rez.top + ((viewport.h - height) * current.topRatio)));
            rez.left = getValue(Math.max(rez.left, rez.left + ((viewport.w - width) * current.leftRatio)));

            return rez;
        },

        _afterZoomIn: function () {
            var current = F.current;

            if (!current) {
                return;
            }

            F.isOpen = F.isOpened = true;

            F.wrap.css('overflow', 'visible').addClass('fancybox-opened');

            F.update();

            // Assign a click event
            if (current.closeClick || (current.nextClick && F.group.length > 1)) {
                F.inner.css('cursor', 'pointer').bind('click.fb', function (e) {
                    if (!$(e.target).is('a') && !$(e.target).parent().is('a')) {
                        e.preventDefault();

                        F[current.closeClick ? 'close' : 'next']();
                    }
                });
            }

            // Create a close button
            if (current.closeBtn) {
                $(current.tpl.closeBtn).appendTo(F.skin).bind('click.fb', function (e) {
                    e.preventDefault();

                    F.close();
                });
            }

            // Create navigation arrows
            if (current.arrows && F.group.length > 1) {
                if (current.loop || current.index > 0) {
                    $(current.tpl.prev).appendTo(F.outer).bind('click.fb', F.prev);
                }

                if (current.loop || current.index < F.group.length - 1) {
                    $(current.tpl.next).appendTo(F.outer).bind('click.fb', F.next);
                }
            }

            F.trigger('afterShow');

            // Stop the slideshow if this is the last item
            if (!current.loop && current.index === current.group.length - 1) {
                F.play(false);

            } else if (F.opts.autoPlay && !F.player.isActive) {
                F.opts.autoPlay = false;

                F.play();
            }
        },

        _afterZoomOut: function (obj) {
            obj = obj || F.current;

            $('.fancybox-wrap').trigger('onReset').remove();

            $.extend(F, {
                group: {},
                opts: {},
                router: false,
                current: null,
                isActive: false,
                isOpened: false,
                isOpen: false,
                isClosing: false,
                wrap: null,
                skin: null,
                outer: null,
                inner: null
            });

            F.trigger('afterClose', obj);
        }
    });

    /*
     *	Default transitions
     */

    F.transitions = {
        getOrigPosition: function () {
            var current = F.current,
                element = current.element,
                orig = current.orig,
                pos = {},
                width = 50,
                height = 50,
                hPadding = current.hPadding,
                wPadding = current.wPadding,
                viewport = F.getViewport();

            if (!orig && current.isDom && element.is(':visible')) {
                orig = element.find('img:first');

                if (!orig.length) {
                    orig = element;
                }
            }

            if (isQuery(orig)) {
                pos = orig.offset();

                if (orig.is('img')) {
                    width = orig.outerWidth();
                    height = orig.outerHeight();
                }

            } else {
                pos.top = viewport.y + (viewport.h - height) * current.topRatio;
                pos.left = viewport.x + (viewport.w - width) * current.leftRatio;
            }

            if (F.wrap.css('position') === 'fixed' || current.locked) {
                pos.top -= viewport.y;
                pos.left -= viewport.x;
            }

            pos = {
                top: getValue(pos.top - hPadding * current.topRatio),
                left: getValue(pos.left - wPadding * current.leftRatio),
                width: getValue(width + wPadding),
                height: getValue(height + hPadding)
            };

            return pos;
        },

        step: function (now, fx) {
            var ratio,
                padding,
                value,
                prop = fx.prop,
                current = F.current,
                wrapSpace = current.wrapSpace,
                skinSpace = current.skinSpace;

            if (prop === 'width' || prop === 'height') {
                ratio = fx.end === fx.start ? 1 : (now - fx.start) / (fx.end - fx.start);

                if (F.isClosing) {
                    ratio = 1 - ratio;
                }

                padding = prop === 'width' ? current.wPadding : current.hPadding;
                value = now - padding;

                F.skin[prop](getScalar(prop === 'width' ? value : value - (wrapSpace * ratio)));
                F.inner[prop](getScalar(prop === 'width' ? value : value - (wrapSpace * ratio) - (skinSpace * ratio)));
            }
        },

        zoomIn: function () {
            var current = F.current,
                startPos = current.pos,
                effect = current.openEffect,
                elastic = effect === 'elastic',
                endPos = $.extend({opacity: 1}, startPos);

            // Remove "position" property that breaks older IE
            delete endPos.position;

            if (elastic) {
                startPos = this.getOrigPosition();

                if (current.openOpacity) {
                    startPos.opacity = 0.1;
                }

            } else if (effect === 'fade') {
                startPos.opacity = 0.1;
            }

            F.wrap.css(startPos).animate(endPos, {
                duration: effect === 'none' ? 0 : current.openSpeed,
                easing: current.openEasing,
                step: elastic ? this.step : null,
                complete: F._afterZoomIn
            });
        },

        zoomOut: function () {
            var current = F.current,
                effect = current.closeEffect,
                elastic = effect === 'elastic',
                endPos = {opacity: 0.1};

            if (elastic) {
                endPos = this.getOrigPosition();

                if (current.closeOpacity) {
                    endPos.opacity = 0.1;
                }
            }

            F.wrap.animate(endPos, {
                duration: effect === 'none' ? 0 : current.closeSpeed,
                easing: current.closeEasing,
                step: elastic ? this.step : null,
                complete: F._afterZoomOut
            });
        },

        changeIn: function () {
            var current = F.current,
                effect = current.nextEffect,
                startPos = current.pos,
                endPos = {opacity: 1},
                direction = F.direction,
                distance = 200,
                field;

            startPos.opacity = 0.1;

            if (effect === 'elastic') {
                field = direction === 'down' || direction === 'up' ? 'top' : 'left';

                if (direction === 'down' || direction === 'right') {
                    startPos[field] = getValue(getScalar(startPos[field]) - distance);
                    endPos[field] = '+=' + distance + 'px';

                } else {
                    startPos[field] = getValue(getScalar(startPos[field]) + distance);
                    endPos[field] = '-=' + distance + 'px';
                }
            }

            // Workaround for http://bugs.jquery.com/ticket/12273
            if (effect === 'none') {
                F._afterZoomIn();

            } else {
                F.wrap.css(startPos).animate(endPos, {
                    duration: current.nextSpeed,
                    easing: current.nextEasing,
                    complete: F._afterZoomIn
                });
            }
        },

        changeOut: function () {
            var previous = F.previous,
                effect = previous.prevEffect,
                endPos = {opacity: 0.1},
                direction = F.direction,
                distance = 200;

            if (effect === 'elastic') {
                endPos[direction === 'down' || direction === 'up' ? 'top' : 'left'] = ( direction === 'up' || direction === 'left' ? '-' : '+' ) + '=' + distance + 'px';
            }

            previous.wrap.animate(endPos, {
                duration: effect === 'none' ? 0 : previous.prevSpeed,
                easing: previous.prevEasing,
                complete: function () {
                    $(this).trigger('onReset').remove();
                }
            });
        }
    };

    /*
     *	Overlay helper
     */

    F.helpers.overlay = {
        defaults: {
            closeClick: true,      // if true, fancyBox will be closed when user clicks on the overlay
            speedOut: 200,       // duration of fadeOut animation
            showEarly: true,      // indicates if should be opened immediately or wait until the content is ready
            css: {},        // custom CSS properties
            locked: !isTouch,  // if true, the content will be locked into overlay
            fixed: true       // if false, the overlay CSS position property will not be set to "fixed"
        },

        overlay: null,      // current handle
        fixed: false,     // indicates if the overlay has position "fixed"
        el: $('html'), // element that contains "the lock"

        // Public methods
        create: function (opts) {
            opts = $.extend({}, this.defaults, opts);

            if (this.overlay) {
                this.close();
            }

            this.overlay = $('<div class="fancybox-overlay"></div>').appendTo(F.coming ? F.coming.parent : opts.parent);
            this.fixed = false;

            if (opts.fixed && F.defaults.fixed) {
                this.overlay.addClass('fancybox-overlay-fixed');

                this.fixed = true;
            }
        },

        open: function (opts) {
            var that = this;

            opts = $.extend({}, this.defaults, opts);

            if (this.overlay) {
                this.overlay.unbind('.overlay').width('auto').height('auto');

            } else {
                this.create(opts);
            }

            if (!this.fixed) {
                W.bind('resize.overlay', $.proxy(this.update, this));

                this.update();
            }

            if (opts.closeClick) {
                this.overlay.bind('click.overlay', function (e) {
                    if ($(e.target).hasClass('fancybox-overlay')) {
                        if (F.isActive) {
                            F.close();
                        } else {
                            that.close();
                        }

                        return false;
                    }
                });
            }

            this.overlay.css(opts.css).show();
        },

        close: function () {
            var scrollV, scrollH;

            W.unbind('resize.overlay');

            if (this.el.hasClass('fancybox-lock')) {
                $('.fancybox-margin').removeClass('fancybox-margin');

                scrollV = W.scrollTop();
                scrollH = W.scrollLeft();

                this.el.removeClass('fancybox-lock');

                W.scrollTop(scrollV).scrollLeft(scrollH);
            }

            $('.fancybox-overlay').remove().hide();

            $.extend(this, {
                overlay: null,
                fixed: false
            });
        },

        // Private, callbacks

        update: function () {
            var width = '100%', offsetWidth;

            // Reset width/height so it will not mess
            this.overlay.width(width).height('100%');

            // jQuery does not return reliable result for IE
            if (IE) {
                offsetWidth = Math.max(document.documentElement.offsetWidth, document.body.offsetWidth);

                if (D.width() > offsetWidth) {
                    width = D.width();
                }

            } else if (D.width() > W.width()) {
                width = D.width();
            }

            this.overlay.width(width).height(D.height());
        },

        // This is where we can manipulate DOM, because later it would cause iframes to reload
        onReady: function (opts, obj) {
            var overlay = this.overlay;

            $('.fancybox-overlay').stop(true, true);

            if (!overlay) {
                this.create(opts);
            }

            if (opts.locked && this.fixed && obj.fixed) {
                if (!overlay) {
                    this.margin = D.height() > W.height() ? $('html').css('margin-right').replace("px", "") : false;
                }

                obj.locked = this.overlay.append(obj.wrap);
                obj.fixed = false;
            }

            if (opts.showEarly === true) {
                this.beforeShow.apply(this, arguments);
            }
        },

        beforeShow: function (opts, obj) {
            var scrollV, scrollH;

            if (obj.locked) {
                if (this.margin !== false) {
                    $('*').filter(function () {
                        return ($(this).css('position') === 'fixed' && !$(this).hasClass("fancybox-overlay") && !$(this).hasClass("fancybox-wrap") );
                    }).addClass('fancybox-margin');

                    this.el.addClass('fancybox-margin');
                }

                scrollV = W.scrollTop();
                scrollH = W.scrollLeft();

                this.el.addClass('fancybox-lock');

                W.scrollTop(scrollV).scrollLeft(scrollH);
            }

            this.open(opts);
        },

        onUpdate: function () {
            if (!this.fixed) {
                this.update();
            }
        },

        afterClose: function (opts) {
            // Remove overlay if exists and fancyBox is not opening
            // (e.g., it is not being open using afterClose callback)
            //if (this.overlay && !F.isActive) {
            if (this.overlay && !F.coming) {
                this.overlay.fadeOut(opts.speedOut, $.proxy(this.close, this));
            }
        }
    };

    /*
     *	Title helper
     */

    F.helpers.title = {
        defaults: {
            type: 'float', // 'float', 'inside', 'outside' or 'over',
            position: 'bottom' // 'top' or 'bottom'
        },

        beforeShow: function (opts) {
            var current = F.current,
                text = current.title,
                type = opts.type,
                title,
                target;

            if ($.isFunction(text)) {
                text = text.call(current.element, current);
            }

            if (!isString(text) || $.trim(text) === '') {
                return;
            }

            title = $('<div class="fancybox-title fancybox-title-' + type + '-wrap">' + text + '</div>');

            switch (type) {
                case 'inside':
                    target = F.skin;
                    break;

                case 'outside':
                    target = F.wrap;
                    break;

                case 'over':
                    target = F.inner;
                    break;

                default: // 'float'
                    target = F.skin;

                    title.appendTo('body');

                    if (IE) {
                        title.width(title.width());
                    }

                    title.wrapInner('<span class="child"></span>');

                    //Increase bottom margin so this title will also fit into viewport
                    F.current.margin[2] += Math.abs(getScalar(title.css('margin-bottom')));
                    break;
            }

            title[(opts.position === 'top' ? 'prependTo' : 'appendTo')](target);
        }
    };

    // jQuery plugin initialization
    $.fn.fancybox = function (options) {
        var index,
            that = $(this),
            selector = this.selector || '',
            run = function (e) {
                var what = $(this).blur(), idx = index, relType, relVal;

                if (!(e.ctrlKey || e.altKey || e.shiftKey || e.metaKey) && !what.is('.fancybox-wrap')) {
                    relType = options.groupAttr || 'data-fancybox-group';
                    relVal = what.attr(relType);

                    if (!relVal) {
                        relType = 'rel';
                        relVal = what.get(0)[relType];
                    }

                    if (relVal && relVal !== '' && relVal !== 'nofollow') {
                        what = selector.length ? $(selector) : that;
                        what = what.filter('[' + relType + '="' + relVal + '"]');
                        idx = what.index(this);
                    }

                    options.index = idx;

                    // Stop an event from bubbling if everything is fine
                    if (F.open(what, options) !== false) {
                        e.preventDefault();
                    }
                }
            };

        options = options || {};
        index = options.index || 0;

        if (!selector || options.live === false) {
            that.unbind('click.fb-start').bind('click.fb-start', run);

        } else {
            D.undelegate(selector, 'click.fb-start').delegate(selector + ":not('.fancybox-item, .fancybox-nav')", 'click.fb-start', run);
        }

        this.filter('[data-fancybox-start=1]').trigger('click');

        return this;
    };

    // Tests that need a body at doc ready
    D.ready(function () {
        var w1, w2;

        if ($.scrollbarWidth === undefined) {
            // http://benalman.com/projects/jquery-misc-plugins/#scrollbarwidth
            $.scrollbarWidth = function () {
                var parent = $('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo('body'),
                    child = parent.children(),
                    width = child.innerWidth() - child.height(99).innerWidth();

                parent.remove();

                return width;
            };
        }

        if ($.support.fixedPosition === undefined) {
            $.support.fixedPosition = (function () {
                var elem = $('<div style="position:fixed;top:20px;"></div>').appendTo('body'),
                    fixed = ( elem[0].offsetTop === 20 || elem[0].offsetTop === 15 );

                elem.remove();

                return fixed;
            }());
        }

        $.extend(F.defaults, {
            scrollbarWidth: $.scrollbarWidth(),
            fixed: $.support.fixedPosition,
            parent: $('body')
        });

        //Get real width of page scroll-bar
        w1 = $(window).width();

        H.addClass('fancybox-lock-test');

        w2 = $(window).width();

        H.removeClass('fancybox-lock-test');

        $("<style type='text/css'>.fancybox-margin{margin-right:" + (w2 - w1) + "px;}</style>").appendTo("head");
    });

}(window, document, jQuery));
/*!
 * Media helper for fancyBox
 * version: 1.0.6 (Fri, 14 Jun 2013)
 * @requires fancyBox v2.0 or later
 *
 * Usage:
 *     $(".fancybox").fancybox({
 *         helpers : {
 *             media: true
 *         }
 *     });
 *
 * Set custom URL parameters:
 *     $(".fancybox").fancybox({
 *         helpers : {
 *             media: {
 *                 youtube : {
 *                     params : {
 *                         autoplay : 0
 *                     }
 *                 }
 *             }
 *         }
 *     });
 *
 * Or:
 *     $(".fancybox").fancybox({,
 *         helpers : {
 *             media: true
 *         },
 *         youtube : {
 *             autoplay: 0
 *         }
 *     });
 *
 *  Supports:
 *
 *      Youtube
 *          http://www.youtube.com/watch?v=opj24KnzrWo
 *          http://www.youtube.com/embed/opj24KnzrWo
 *          http://youtu.be/opj24KnzrWo
 *			http://www.youtube-nocookie.com/embed/opj24KnzrWo
 *      Vimeo
 *          http://vimeo.com/40648169
 *          http://vimeo.com/channels/staffpicks/38843628
 *          http://vimeo.com/groups/surrealism/videos/36516384
 *          http://player.vimeo.com/video/45074303
 *      Metacafe
 *          http://www.metacafe.com/watch/7635964/dr_seuss_the_lorax_movie_trailer/
 *          http://www.metacafe.com/watch/7635964/
 *      Dailymotion
 *          http://www.dailymotion.com/video/xoytqh_dr-seuss-the-lorax-premiere_people
 *      Twitvid
 *          http://twitvid.com/QY7MD
 *      Twitpic
 *          http://twitpic.com/7p93st
 *      Instagram
 *          http://instagr.am/p/IejkuUGxQn/
 *          http://instagram.com/p/IejkuUGxQn/
 *      Google maps
 *          http://maps.google.com/maps?q=Eiffel+Tower,+Avenue+Gustave+Eiffel,+Paris,+France&t=h&z=17
 *          http://maps.google.com/?ll=48.857995,2.294297&spn=0.007666,0.021136&t=m&z=16
 *          http://maps.google.com/?ll=48.859463,2.292626&spn=0.000965,0.002642&t=m&z=19&layer=c&cbll=48.859524,2.292532&panoid=YJ0lq28OOy3VT2IqIuVY0g&cbp=12,151.58,,0,-15.56
 */
(function ($) {
    "use strict";

    //Shortcut for fancyBox object
    var F = $.fancybox,
        format = function (url, rez, params) {
            params = params || '';

            if ($.type(params) === "object") {
                params = $.param(params, true);
            }

            $.each(rez, function (key, value) {
                url = url.replace('$' + key, value || '');
            });

            if (params.length) {
                url += ( url.indexOf('?') > 0 ? '&' : '?' ) + params;
            }

            return url;
        };

    //Add helper object
    F.helpers.media = {
        defaults: {
            youtube: {
                matcher: /(youtube\.com|youtu\.be|youtube-nocookie\.com)\/(watch\?v=|v\/|u\/|embed\/?)?(videoseries\?list=(.*)|[\w-]{11}|\?listType=(.*)&list=(.*)).*/i,
                params: {
                    autoplay: 1,
                    autohide: 1,
                    fs: 1,
                    rel: 0,
                    hd: 1,
                    wmode: 'opaque',
                    enablejsapi: 1
                },
                type: 'iframe',
                url: '//www.youtube.com/embed/$3'
            },
            vimeo: {
                matcher: /(?:vimeo(?:pro)?.com)\/(?:[^\d]+)?(\d+)(?:.*)/,
                params: {
                    autoplay: 1,
                    hd: 1,
                    show_title: 1,
                    show_byline: 1,
                    show_portrait: 0,
                    fullscreen: 1
                },
                type: 'iframe',
                url: '//player.vimeo.com/video/$1'
            },
            metacafe: {
                matcher: /metacafe.com\/(?:watch|fplayer)\/([\w\-]{1,10})/,
                params: {
                    autoPlay: 'yes'
                },
                type: 'swf',
                url: function (rez, params, obj) {
                    obj.swf.flashVars = 'playerVars=' + $.param(params, true);

                    return '//www.metacafe.com/fplayer/' + rez[1] + '/.swf';
                }
            },
            dailymotion: {
                matcher: /dailymotion.com\/video\/(.*)\/?(.*)/,
                params: {
                    additionalInfos: 0,
                    autoStart: 1
                },
                type: 'swf',
                url: '//www.dailymotion.com/swf/video/$1'
            },
            twitvid: {
                matcher: /twitvid\.com\/([a-zA-Z0-9_\-\?\=]+)/i,
                params: {
                    autoplay: 0
                },
                type: 'iframe',
                url: '//www.twitvid.com/embed.php?guid=$1'
            },
            twitpic: {
                matcher: /twitpic\.com\/(?!(?:place|photos|events)\/)([a-zA-Z0-9\?\=\-]+)/i,
                type: 'image',
                url: '//twitpic.com/show/full/$1/'
            },
            instagram: {
                matcher: /(instagr\.am|instagram\.com)\/p\/([a-zA-Z0-9_\-]+)\/?/i,
                type: 'image',
                url: '//$1/p/$2/media/?size=l'
            },
            google_maps: {
                matcher: /maps\.google\.([a-z]{2,3}(\.[a-z]{2})?)\/(\?ll=|maps\?)(.*)/i,
                type: 'iframe',
                url: function (rez) {
                    return '//maps.google.' + rez[1] + '/' + rez[3] + '' + rez[4] + '&output=' + (rez[4].indexOf('layer=c') > 0 ? 'svembed' : 'embed');
                }
            }
        },

        beforeLoad: function (opts, obj) {
            var url = obj.href || '',
                type = false,
                what,
                item,
                rez,
                params;

            for (what in opts) {
                if (opts.hasOwnProperty(what)) {
                    item = opts[what];
                    rez = url.match(item.matcher);

                    if (rez) {
                        type = item.type;
                        params = $.extend(true, {}, item.params, obj[what] || ($.isPlainObject(opts[what]) ? opts[what].params : null));

                        url = $.type(item.url) === "function" ? item.url.call(this, rez, params, obj) : format(item.url, rez, params);

                        break;
                    }
                }
            }

            if (type) {
                obj.href = url;
                obj.type = type;

                obj.autoHeight = false;
            }
        }
    };

}(jQuery));
/*! jQuery Validation Plugin - v1.13.1 - 10/14/2014
 * http://jqueryvalidation.org/
 * Copyright (c) 2014 Jörn Zaefferer; Licensed MIT */
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a(jQuery)}(function(a){a.extend(a.fn,{validate:function(b){if(!this.length)return void(b&&b.debug&&window.console&&console.warn("Nothing selected, can't validate, returning nothing."));var c=a.data(this[0],"validator");return c?c:(this.attr("novalidate","novalidate"),c=new a.validator(b,this[0]),a.data(this[0],"validator",c),c.settings.onsubmit&&(this.validateDelegate(":submit","click",function(b){c.settings.submitHandler&&(c.submitButton=b.target),a(b.target).hasClass("cancel")&&(c.cancelSubmit=!0),void 0!==a(b.target).attr("formnovalidate")&&(c.cancelSubmit=!0)}),this.submit(function(b){function d(){var d,e;return c.settings.submitHandler?(c.submitButton&&(d=a("<input type='hidden'/>").attr("name",c.submitButton.name).val(a(c.submitButton).val()).appendTo(c.currentForm)),e=c.settings.submitHandler.call(c,c.currentForm,b),c.submitButton&&d.remove(),void 0!==e?e:!1):!0}return c.settings.debug&&b.preventDefault(),c.cancelSubmit?(c.cancelSubmit=!1,d()):c.form()?c.pendingRequest?(c.formSubmitted=!0,!1):d():(c.focusInvalid(),!1)})),c)},valid:function(){var b,c;return a(this[0]).is("form")?b=this.validate().form():(b=!0,c=a(this[0].form).validate(),this.each(function(){b=c.element(this)&&b})),b},removeAttrs:function(b){var c={},d=this;return a.each(b.split(/\s/),function(a,b){c[b]=d.attr(b),d.removeAttr(b)}),c},rules:function(b,c){var d,e,f,g,h,i,j=this[0];if(b)switch(d=a.data(j.form,"validator").settings,e=d.rules,f=a.validator.staticRules(j),b){case"add":a.extend(f,a.validator.normalizeRule(c)),delete f.messages,e[j.name]=f,c.messages&&(d.messages[j.name]=a.extend(d.messages[j.name],c.messages));break;case"remove":return c?(i={},a.each(c.split(/\s/),function(b,c){i[c]=f[c],delete f[c],"required"===c&&a(j).removeAttr("aria-required")}),i):(delete e[j.name],f)}return g=a.validator.normalizeRules(a.extend({},a.validator.classRules(j),a.validator.attributeRules(j),a.validator.dataRules(j),a.validator.staticRules(j)),j),g.required&&(h=g.required,delete g.required,g=a.extend({required:h},g),a(j).attr("aria-required","true")),g.remote&&(h=g.remote,delete g.remote,g=a.extend(g,{remote:h})),g}}),a.extend(a.expr[":"],{blank:function(b){return!a.trim(""+a(b).val())},filled:function(b){return!!a.trim(""+a(b).val())},unchecked:function(b){return!a(b).prop("checked")}}),a.validator=function(b,c){this.settings=a.extend(!0,{},a.validator.defaults,b),this.currentForm=c,this.init()},a.validator.format=function(b,c){return 1===arguments.length?function(){var c=a.makeArray(arguments);return c.unshift(b),a.validator.format.apply(this,c)}:(arguments.length>2&&c.constructor!==Array&&(c=a.makeArray(arguments).slice(1)),c.constructor!==Array&&(c=[c]),a.each(c,function(a,c){b=b.replace(new RegExp("\\{"+a+"\\}","g"),function(){return c})}),b)},a.extend(a.validator,{defaults:{messages:{},groups:{},rules:{},errorClass:"error",validClass:"valid",errorElement:"label",focusCleanup:!1,focusInvalid:!0,errorContainer:a([]),errorLabelContainer:a([]),onsubmit:!0,ignore:":hidden",ignoreTitle:!1,onfocusin:function(a){this.lastActive=a,this.settings.focusCleanup&&(this.settings.unhighlight&&this.settings.unhighlight.call(this,a,this.settings.errorClass,this.settings.validClass),this.hideThese(this.errorsFor(a)))},onfocusout:function(a){this.checkable(a)||!(a.name in this.submitted)&&this.optional(a)||this.element(a)},onkeyup:function(a,b){(9!==b.which||""!==this.elementValue(a))&&(a.name in this.submitted||a===this.lastElement)&&this.element(a)},onclick:function(a){a.name in this.submitted?this.element(a):a.parentNode.name in this.submitted&&this.element(a.parentNode)},highlight:function(b,c,d){"radio"===b.type?this.findByName(b.name).addClass(c).removeClass(d):a(b).addClass(c).removeClass(d)},unhighlight:function(b,c,d){"radio"===b.type?this.findByName(b.name).removeClass(c).addClass(d):a(b).removeClass(c).addClass(d)}},setDefaults:function(b){a.extend(a.validator.defaults,b)},messages:{required:"This field is required.",remote:"Please fix this field.",email:"Please enter a valid email address.",url:"Please enter a valid URL.",date:"Please enter a valid date.",dateISO:"Please enter a valid date ( ISO ).",number:"Please enter a valid number.",digits:"Please enter only digits.",creditcard:"Please enter a valid credit card number.",equalTo:"Please enter the same value again.",maxlength:a.validator.format("Please enter no more than {0} characters."),minlength:a.validator.format("Please enter at least {0} characters."),rangelength:a.validator.format("Please enter a value between {0} and {1} characters long."),range:a.validator.format("Please enter a value between {0} and {1}."),max:a.validator.format("Please enter a value less than or equal to {0}."),min:a.validator.format("Please enter a value greater than or equal to {0}.")},autoCreateRanges:!1,prototype:{init:function(){function b(b){var c=a.data(this[0].form,"validator"),d="on"+b.type.replace(/^validate/,""),e=c.settings;e[d]&&!this.is(e.ignore)&&e[d].call(c,this[0],b)}this.labelContainer=a(this.settings.errorLabelContainer),this.errorContext=this.labelContainer.length&&this.labelContainer||a(this.currentForm),this.containers=a(this.settings.errorContainer).add(this.settings.errorLabelContainer),this.submitted={},this.valueCache={},this.pendingRequest=0,this.pending={},this.invalid={},this.reset();var c,d=this.groups={};a.each(this.settings.groups,function(b,c){"string"==typeof c&&(c=c.split(/\s/)),a.each(c,function(a,c){d[c]=b})}),c=this.settings.rules,a.each(c,function(b,d){c[b]=a.validator.normalizeRule(d)}),a(this.currentForm).validateDelegate(":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'] ,[type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], [type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'], [type='radio'], [type='checkbox']","focusin focusout keyup",b).validateDelegate("select, option, [type='radio'], [type='checkbox']","click",b),this.settings.invalidHandler&&a(this.currentForm).bind("invalid-form.validate",this.settings.invalidHandler),a(this.currentForm).find("[required], [data-rule-required], .required").attr("aria-required","true")},form:function(){return this.checkForm(),a.extend(this.submitted,this.errorMap),this.invalid=a.extend({},this.errorMap),this.valid()||a(this.currentForm).triggerHandler("invalid-form",[this]),this.showErrors(),this.valid()},checkForm:function(){this.prepareForm();for(var a=0,b=this.currentElements=this.elements();b[a];a++)this.check(b[a]);return this.valid()},element:function(b){var c=this.clean(b),d=this.validationTargetFor(c),e=!0;return this.lastElement=d,void 0===d?delete this.invalid[c.name]:(this.prepareElement(d),this.currentElements=a(d),e=this.check(d)!==!1,e?delete this.invalid[d.name]:this.invalid[d.name]=!0),a(b).attr("aria-invalid",!e),this.numberOfInvalids()||(this.toHide=this.toHide.add(this.containers)),this.showErrors(),e},showErrors:function(b){if(b){a.extend(this.errorMap,b),this.errorList=[];for(var c in b)this.errorList.push({message:b[c],element:this.findByName(c)[0]});this.successList=a.grep(this.successList,function(a){return!(a.name in b)})}this.settings.showErrors?this.settings.showErrors.call(this,this.errorMap,this.errorList):this.defaultShowErrors()},resetForm:function(){a.fn.resetForm&&a(this.currentForm).resetForm(),this.submitted={},this.lastElement=null,this.prepareForm(),this.hideErrors(),this.elements().removeClass(this.settings.errorClass).removeData("previousValue").removeAttr("aria-invalid")},numberOfInvalids:function(){return this.objectLength(this.invalid)},objectLength:function(a){var b,c=0;for(b in a)c++;return c},hideErrors:function(){this.hideThese(this.toHide)},hideThese:function(a){a.not(this.containers).text(""),this.addWrapper(a).hide()},valid:function(){return 0===this.size()},size:function(){return this.errorList.length},focusInvalid:function(){if(this.settings.focusInvalid)try{a(this.findLastActive()||this.errorList.length&&this.errorList[0].element||[]).filter(":visible").focus().trigger("focusin")}catch(b){}},findLastActive:function(){var b=this.lastActive;return b&&1===a.grep(this.errorList,function(a){return a.element.name===b.name}).length&&b},elements:function(){var b=this,c={};return a(this.currentForm).find("input, select, textarea").not(":submit, :reset, :image, [disabled], [readonly]").not(this.settings.ignore).filter(function(){return!this.name&&b.settings.debug&&window.console&&console.error("%o has no name assigned",this),this.name in c||!b.objectLength(a(this).rules())?!1:(c[this.name]=!0,!0)})},clean:function(b){return a(b)[0]},errors:function(){var b=this.settings.errorClass.split(" ").join(".");return a(this.settings.errorElement+"."+b,this.errorContext)},reset:function(){this.successList=[],this.errorList=[],this.errorMap={},this.toShow=a([]),this.toHide=a([]),this.currentElements=a([])},prepareForm:function(){this.reset(),this.toHide=this.errors().add(this.containers)},prepareElement:function(a){this.reset(),this.toHide=this.errorsFor(a)},elementValue:function(b){var c,d=a(b),e=b.type;return"radio"===e||"checkbox"===e?a("input[name='"+b.name+"']:checked").val():"number"===e&&"undefined"!=typeof b.validity?b.validity.badInput?!1:d.val():(c=d.val(),"string"==typeof c?c.replace(/\r/g,""):c)},check:function(b){b=this.validationTargetFor(this.clean(b));var c,d,e,f=a(b).rules(),g=a.map(f,function(a,b){return b}).length,h=!1,i=this.elementValue(b);for(d in f){e={method:d,parameters:f[d]};try{if(c=a.validator.methods[d].call(this,i,b,e.parameters),"dependency-mismatch"===c&&1===g){h=!0;continue}if(h=!1,"pending"===c)return void(this.toHide=this.toHide.not(this.errorsFor(b)));if(!c)return this.formatAndAdd(b,e),!1}catch(j){throw this.settings.debug&&window.console&&console.log("Exception occurred when checking element "+b.id+", check the '"+e.method+"' method.",j),j}}if(!h)return this.objectLength(f)&&this.successList.push(b),!0},customDataMessage:function(b,c){return a(b).data("msg"+c.charAt(0).toUpperCase()+c.substring(1).toLowerCase())||a(b).data("msg")},customMessage:function(a,b){var c=this.settings.messages[a];return c&&(c.constructor===String?c:c[b])},findDefined:function(){for(var a=0;a<arguments.length;a++)if(void 0!==arguments[a])return arguments[a];return void 0},defaultMessage:function(b,c){return this.findDefined(this.customMessage(b.name,c),this.customDataMessage(b,c),!this.settings.ignoreTitle&&b.title||void 0,a.validator.messages[c],"<strong>Warning: No message defined for "+b.name+"</strong>")},formatAndAdd:function(b,c){var d=this.defaultMessage(b,c.method),e=/\$?\{(\d+)\}/g;"function"==typeof d?d=d.call(this,c.parameters,b):e.test(d)&&(d=a.validator.format(d.replace(e,"{$1}"),c.parameters)),this.errorList.push({message:d,element:b,method:c.method}),this.errorMap[b.name]=d,this.submitted[b.name]=d},addWrapper:function(a){return this.settings.wrapper&&(a=a.add(a.parent(this.settings.wrapper))),a},defaultShowErrors:function(){var a,b,c;for(a=0;this.errorList[a];a++)c=this.errorList[a],this.settings.highlight&&this.settings.highlight.call(this,c.element,this.settings.errorClass,this.settings.validClass),this.showLabel(c.element,c.message);if(this.errorList.length&&(this.toShow=this.toShow.add(this.containers)),this.settings.success)for(a=0;this.successList[a];a++)this.showLabel(this.successList[a]);if(this.settings.unhighlight)for(a=0,b=this.validElements();b[a];a++)this.settings.unhighlight.call(this,b[a],this.settings.errorClass,this.settings.validClass);this.toHide=this.toHide.not(this.toShow),this.hideErrors(),this.addWrapper(this.toShow).show()},validElements:function(){return this.currentElements.not(this.invalidElements())},invalidElements:function(){return a(this.errorList).map(function(){return this.element})},showLabel:function(b,c){var d,e,f,g=this.errorsFor(b),h=this.idOrName(b),i=a(b).attr("aria-describedby");g.length?(g.removeClass(this.settings.validClass).addClass(this.settings.errorClass),g.html(c)):(g=a("<"+this.settings.errorElement+">").attr("id",h+"-error").addClass(this.settings.errorClass).html(c||""),d=g,this.settings.wrapper&&(d=g.hide().show().wrap("<"+this.settings.wrapper+"/>").parent()),this.labelContainer.length?this.labelContainer.append(d):this.settings.errorPlacement?this.settings.errorPlacement(d,a(b)):d.insertAfter(b),g.is("label")?g.attr("for",h):0===g.parents("label[for='"+h+"']").length&&(f=g.attr("id").replace(/(:|\.|\[|\])/g,"\\$1"),i?i.match(new RegExp("\\b"+f+"\\b"))||(i+=" "+f):i=f,a(b).attr("aria-describedby",i),e=this.groups[b.name],e&&a.each(this.groups,function(b,c){c===e&&a("[name='"+b+"']",this.currentForm).attr("aria-describedby",g.attr("id"))}))),!c&&this.settings.success&&(g.text(""),"string"==typeof this.settings.success?g.addClass(this.settings.success):this.settings.success(g,b)),this.toShow=this.toShow.add(g)},errorsFor:function(b){var c=this.idOrName(b),d=a(b).attr("aria-describedby"),e="label[for='"+c+"'], label[for='"+c+"'] *";return d&&(e=e+", #"+d.replace(/\s+/g,", #")),this.errors().filter(e)},idOrName:function(a){return this.groups[a.name]||(this.checkable(a)?a.name:a.id||a.name)},validationTargetFor:function(b){return this.checkable(b)&&(b=this.findByName(b.name)),a(b).not(this.settings.ignore)[0]},checkable:function(a){return/radio|checkbox/i.test(a.type)},findByName:function(b){return a(this.currentForm).find("[name='"+b+"']")},getLength:function(b,c){switch(c.nodeName.toLowerCase()){case"select":return a("option:selected",c).length;case"input":if(this.checkable(c))return this.findByName(c.name).filter(":checked").length}return b.length},depend:function(a,b){return this.dependTypes[typeof a]?this.dependTypes[typeof a](a,b):!0},dependTypes:{"boolean":function(a){return a},string:function(b,c){return!!a(b,c.form).length},"function":function(a,b){return a(b)}},optional:function(b){var c=this.elementValue(b);return!a.validator.methods.required.call(this,c,b)&&"dependency-mismatch"},startRequest:function(a){this.pending[a.name]||(this.pendingRequest++,this.pending[a.name]=!0)},stopRequest:function(b,c){this.pendingRequest--,this.pendingRequest<0&&(this.pendingRequest=0),delete this.pending[b.name],c&&0===this.pendingRequest&&this.formSubmitted&&this.form()?(a(this.currentForm).submit(),this.formSubmitted=!1):!c&&0===this.pendingRequest&&this.formSubmitted&&(a(this.currentForm).triggerHandler("invalid-form",[this]),this.formSubmitted=!1)},previousValue:function(b){return a.data(b,"previousValue")||a.data(b,"previousValue",{old:null,valid:!0,message:this.defaultMessage(b,"remote")})}},classRuleSettings:{required:{required:!0},email:{email:!0},url:{url:!0},date:{date:!0},dateISO:{dateISO:!0},number:{number:!0},digits:{digits:!0},creditcard:{creditcard:!0}},addClassRules:function(b,c){b.constructor===String?this.classRuleSettings[b]=c:a.extend(this.classRuleSettings,b)},classRules:function(b){var c={},d=a(b).attr("class");return d&&a.each(d.split(" "),function(){this in a.validator.classRuleSettings&&a.extend(c,a.validator.classRuleSettings[this])}),c},attributeRules:function(b){var c,d,e={},f=a(b),g=b.getAttribute("type");for(c in a.validator.methods)"required"===c?(d=b.getAttribute(c),""===d&&(d=!0),d=!!d):d=f.attr(c),/min|max/.test(c)&&(null===g||/number|range|text/.test(g))&&(d=Number(d)),d||0===d?e[c]=d:g===c&&"range"!==g&&(e[c]=!0);return e.maxlength&&/-1|2147483647|524288/.test(e.maxlength)&&delete e.maxlength,e},dataRules:function(b){var c,d,e={},f=a(b);for(c in a.validator.methods)d=f.data("rule"+c.charAt(0).toUpperCase()+c.substring(1).toLowerCase()),void 0!==d&&(e[c]=d);return e},staticRules:function(b){var c={},d=a.data(b.form,"validator");return d.settings.rules&&(c=a.validator.normalizeRule(d.settings.rules[b.name])||{}),c},normalizeRules:function(b,c){return a.each(b,function(d,e){if(e===!1)return void delete b[d];if(e.param||e.depends){var f=!0;switch(typeof e.depends){case"string":f=!!a(e.depends,c.form).length;break;case"function":f=e.depends.call(c,c)}f?b[d]=void 0!==e.param?e.param:!0:delete b[d]}}),a.each(b,function(d,e){b[d]=a.isFunction(e)?e(c):e}),a.each(["minlength","maxlength"],function(){b[this]&&(b[this]=Number(b[this]))}),a.each(["rangelength","range"],function(){var c;b[this]&&(a.isArray(b[this])?b[this]=[Number(b[this][0]),Number(b[this][1])]:"string"==typeof b[this]&&(c=b[this].replace(/[\[\]]/g,"").split(/[\s,]+/),b[this]=[Number(c[0]),Number(c[1])]))}),a.validator.autoCreateRanges&&(null!=b.min&&null!=b.max&&(b.range=[b.min,b.max],delete b.min,delete b.max),null!=b.minlength&&null!=b.maxlength&&(b.rangelength=[b.minlength,b.maxlength],delete b.minlength,delete b.maxlength)),b},normalizeRule:function(b){if("string"==typeof b){var c={};a.each(b.split(/\s/),function(){c[this]=!0}),b=c}return b},addMethod:function(b,c,d){a.validator.methods[b]=c,a.validator.messages[b]=void 0!==d?d:a.validator.messages[b],c.length<3&&a.validator.addClassRules(b,a.validator.normalizeRule(b))},methods:{required:function(b,c,d){if(!this.depend(d,c))return"dependency-mismatch";if("select"===c.nodeName.toLowerCase()){var e=a(c).val();return e&&e.length>0}return this.checkable(c)?this.getLength(b,c)>0:a.trim(b).length>0},email:function(a,b){return this.optional(b)||/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(a)},url:function(a,b){return this.optional(b)||/^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(a)},date:function(a,b){return this.optional(b)||!/Invalid|NaN/.test(new Date(a).toString())},dateISO:function(a,b){return this.optional(b)||/^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/.test(a)},number:function(a,b){return this.optional(b)||/^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(a)},digits:function(a,b){return this.optional(b)||/^\d+$/.test(a)},creditcard:function(a,b){if(this.optional(b))return"dependency-mismatch";if(/[^0-9 \-]+/.test(a))return!1;var c,d,e=0,f=0,g=!1;if(a=a.replace(/\D/g,""),a.length<13||a.length>19)return!1;for(c=a.length-1;c>=0;c--)d=a.charAt(c),f=parseInt(d,10),g&&(f*=2)>9&&(f-=9),e+=f,g=!g;return e%10===0},minlength:function(b,c,d){var e=a.isArray(b)?b.length:this.getLength(b,c);return this.optional(c)||e>=d},maxlength:function(b,c,d){var e=a.isArray(b)?b.length:this.getLength(b,c);return this.optional(c)||d>=e},rangelength:function(b,c,d){var e=a.isArray(b)?b.length:this.getLength(b,c);return this.optional(c)||e>=d[0]&&e<=d[1]},min:function(a,b,c){return this.optional(b)||a>=c},max:function(a,b,c){return this.optional(b)||c>=a},range:function(a,b,c){return this.optional(b)||a>=c[0]&&a<=c[1]},equalTo:function(b,c,d){var e=a(d);return this.settings.onfocusout&&e.unbind(".validate-equalTo").bind("blur.validate-equalTo",function(){a(c).valid()}),b===e.val()},remote:function(b,c,d){if(this.optional(c))return"dependency-mismatch";var e,f,g=this.previousValue(c);return this.settings.messages[c.name]||(this.settings.messages[c.name]={}),g.originalMessage=this.settings.messages[c.name].remote,this.settings.messages[c.name].remote=g.message,d="string"==typeof d&&{url:d}||d,g.old===b?g.valid:(g.old=b,e=this,this.startRequest(c),f={},f[c.name]=b,a.ajax(a.extend(!0,{url:d,mode:"abort",port:"validate"+c.name,dataType:"json",data:f,context:e.currentForm,success:function(d){var f,h,i,j=d===!0||"true"===d;e.settings.messages[c.name].remote=g.originalMessage,j?(i=e.formSubmitted,e.prepareElement(c),e.formSubmitted=i,e.successList.push(c),delete e.invalid[c.name],e.showErrors()):(f={},h=d||e.defaultMessage(c,"remote"),f[c.name]=g.message=a.isFunction(h)?h(b):h,e.invalid[c.name]=!0,e.showErrors(f)),g.valid=j,e.stopRequest(c,j)}},d)),"pending")}}}),a.format=function(){throw"$.format has been deprecated. Please use $.validator.format instead."};var b,c={};a.ajaxPrefilter?a.ajaxPrefilter(function(a,b,d){var e=a.port;"abort"===a.mode&&(c[e]&&c[e].abort(),c[e]=d)}):(b=a.ajax,a.ajax=function(d){var e=("mode"in d?d:a.ajaxSettings).mode,f=("port"in d?d:a.ajaxSettings).port;return"abort"===e?(c[f]&&c[f].abort(),c[f]=b.apply(this,arguments),c[f]):b.apply(this,arguments)}),a.extend(a.fn,{validateDelegate:function(b,c,d){return this.bind(c,function(c){var e=a(c.target);return e.is(b)?d.apply(e,arguments):void 0})}})});
/*!
 * jQuery Form Plugin
 * version: 3.51.0-2014.06.20
 * Requires jQuery v1.5 or later
 * Copyright (c) 2014 M. Alsup
 * Examples and documentation at: http://malsup.com/jquery/form/
 * Project repository: https://github.com/malsup/form
 * Dual licensed under the MIT and GPL licenses.
 * https://github.com/malsup/form#copyright-and-license
 */
/*global ActiveXObject */

// AMD support
(function (factory) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        // using AMD; register as anon module
        define(['jquery'], factory);
    } else {
        // no AMD; invoke directly
        factory((typeof(jQuery) != 'undefined') ? jQuery : window.Zepto);
    }
}

(function ($) {
    "use strict";

    /*
     Usage Note:
     -----------
     Do not use both ajaxSubmit and ajaxForm on the same form.  These
     functions are mutually exclusive.  Use ajaxSubmit if you want
     to bind your own submit handler to the form.  For example,

     $(document).ready(function() {
     $('#myForm').on('submit', function(e) {
     e.preventDefault(); // <-- important
     $(this).ajaxSubmit({
     target: '#output'
     });
     });
     });

     Use ajaxForm when you want the plugin to manage all the event binding
     for you.  For example,

     $(document).ready(function() {
     $('#myForm').ajaxForm({
     target: '#output'
     });
     });

     You can also use ajaxForm with delegation (requires jQuery v1.7+), so the
     form does not have to exist when you invoke ajaxForm:

     $('#myForm').ajaxForm({
     delegation: true,
     target: '#output'
     });

     When using ajaxForm, the ajaxSubmit function will be invoked for you
     at the appropriate time.
     */

    /**
     * Feature detection
     */
    var feature = {};
    feature.fileapi = $("<input type='file'/>").get(0).files !== undefined;
    feature.formdata = window.FormData !== undefined;

    var hasProp = !!$.fn.prop;

// attr2 uses prop when it can but checks the return type for
// an expected string.  this accounts for the case where a form 
// contains inputs with names like "action" or "method"; in those
// cases "prop" returns the element
    $.fn.attr2 = function () {
        if (!hasProp) {
            return this.attr.apply(this, arguments);
        }
        var val = this.prop.apply(this, arguments);
        if (( val && val.jquery ) || typeof val === 'string') {
            return val;
        }
        return this.attr.apply(this, arguments);
    };

    /**
     * ajaxSubmit() provides a mechanism for immediately submitting
     * an HTML form using AJAX.
     */
    $.fn.ajaxSubmit = function (options) {
        /*jshint scripturl:true */

        // fast fail if nothing selected (http://dev.jquery.com/ticket/2752)
        if (!this.length) {
            log('ajaxSubmit: skipping submit process - no element selected');
            return this;
        }

        var method, action, url, $form = this;

        if (typeof options == 'function') {
            options = {success: options};
        }
        else if (options === undefined) {
            options = {};
        }

        method = options.type || this.attr2('method');
        action = options.url || this.attr2('action');

        url = (typeof action === 'string') ? $.trim(action) : '';
        url = url || window.location.href || '';
        if (url) {
            // clean url (don't include hash vaue)
            url = (url.match(/^([^#]+)/) || [])[1];
        }

        options = $.extend(true, {
            url: url,
            success: $.ajaxSettings.success,
            type: method || $.ajaxSettings.type,
            iframeSrc: /^https/i.test(window.location.href || '') ? 'javascript:false' : 'about:blank'
        }, options);

        // hook for manipulating the form data before it is extracted;
        // convenient for use with rich editors like tinyMCE or FCKEditor
        var veto = {};
        this.trigger('form-pre-serialize', [this, options, veto]);
        if (veto.veto) {
            log('ajaxSubmit: submit vetoed via form-pre-serialize trigger');
            return this;
        }

        // provide opportunity to alter form data before it is serialized
        if (options.beforeSerialize && options.beforeSerialize(this, options) === false) {
            log('ajaxSubmit: submit aborted via beforeSerialize callback');
            return this;
        }

        var traditional = options.traditional;
        if (traditional === undefined) {
            traditional = $.ajaxSettings.traditional;
        }

        var elements = [];
        var qx, a = this.formToArray(options.semantic, elements);
        if (options.data) {
            options.extraData = options.data;
            qx = $.param(options.data, traditional);
        }

        // give pre-submit callback an opportunity to abort the submit
        if (options.beforeSubmit && options.beforeSubmit(a, this, options) === false) {
            log('ajaxSubmit: submit aborted via beforeSubmit callback');
            return this;
        }

        // fire vetoable 'validate' event
        this.trigger('form-submit-validate', [a, this, options, veto]);
        if (veto.veto) {
            log('ajaxSubmit: submit vetoed via form-submit-validate trigger');
            return this;
        }

        var q = $.param(a, traditional);
        if (qx) {
            q = ( q ? (q + '&' + qx) : qx );
        }
        if (options.type.toUpperCase() == 'GET') {
            options.url += (options.url.indexOf('?') >= 0 ? '&' : '?') + q;
            options.data = null;  // data is null for 'get'
        }
        else {
            options.data = q; // data is the query string for 'post'
        }

        var callbacks = [];
        if (options.resetForm) {
            callbacks.push(function () {
                $form.resetForm();
            });
        }
        if (options.clearForm) {
            callbacks.push(function () {
                $form.clearForm(options.includeHidden);
            });
        }

        // perform a load on the target only if dataType is not provided
        if (!options.dataType && options.target) {
            var oldSuccess = options.success || function () {
                };
            callbacks.push(function (data) {
                var fn = options.replaceTarget ? 'replaceWith' : 'html';
                $(options.target)[fn](data).each(oldSuccess, arguments);
            });
        }
        else if (options.success) {
            callbacks.push(options.success);
        }

        options.success = function (data, status, xhr) { // jQuery 1.4+ passes xhr as 3rd arg
            var context = options.context || this;    // jQuery 1.4+ supports scope context
            for (var i = 0, max = callbacks.length; i < max; i++) {
                callbacks[i].apply(context, [data, status, xhr || $form, $form]);
            }
        };

        if (options.error) {
            var oldError = options.error;
            options.error = function (xhr, status, error) {
                var context = options.context || this;
                oldError.apply(context, [xhr, status, error, $form]);
            };
        }

        if (options.complete) {
            var oldComplete = options.complete;
            options.complete = function (xhr, status) {
                var context = options.context || this;
                oldComplete.apply(context, [xhr, status, $form]);
            };
        }

        // are there files to upload?

        // [value] (issue #113), also see comment:
        // https://github.com/malsup/form/commit/588306aedba1de01388032d5f42a60159eea9228#commitcomment-2180219
        var fileInputs = $('input[type=file]:enabled', this).filter(function () {
            return $(this).val() !== '';
        });

        var hasFileInputs = fileInputs.length > 0;
        var mp = 'multipart/form-data';
        var multipart = ($form.attr('enctype') == mp || $form.attr('encoding') == mp);

        var fileAPI = feature.fileapi && feature.formdata;
        log("fileAPI :" + fileAPI);
        var shouldUseFrame = (hasFileInputs || multipart) && !fileAPI;

        var jqxhr;

        // options.iframe allows user to force iframe mode
        // 06-NOV-09: now defaulting to iframe mode if file input is detected
        if (options.iframe !== false && (options.iframe || shouldUseFrame)) {
            // hack to fix Safari hang (thanks to Tim Molendijk for this)
            // see:  http://groups.google.com/group/jquery-dev/browse_thread/thread/36395b7ab510dd5d
            if (options.closeKeepAlive) {
                $.get(options.closeKeepAlive, function () {
                    jqxhr = fileUploadIframe(a);
                });
            }
            else {
                jqxhr = fileUploadIframe(a);
            }
        }
        else if ((hasFileInputs || multipart) && fileAPI) {
            jqxhr = fileUploadXhr(a);
        }
        else {
            jqxhr = $.ajax(options);
        }

        $form.removeData('jqxhr').data('jqxhr', jqxhr);

        // clear element array
        for (var k = 0; k < elements.length; k++) {
            elements[k] = null;
        }

        // fire 'notify' event
        this.trigger('form-submit-notify', [this, options]);
        return this;

        // utility fn for deep serialization
        function deepSerialize(extraData) {
            var serialized = $.param(extraData, options.traditional).split('&');
            var len = serialized.length;
            var result = [];
            var i, part;
            for (i = 0; i < len; i++) {
                // #252; undo param space replacement
                serialized[i] = serialized[i].replace(/\+/g, ' ');
                part = serialized[i].split('=');
                // #278; use array instead of object storage, favoring array serializations
                result.push([decodeURIComponent(part[0]), decodeURIComponent(part[1])]);
            }
            return result;
        }

        // XMLHttpRequest Level 2 file uploads (big hat tip to francois2metz)
        function fileUploadXhr(a) {
            var formdata = new FormData();

            for (var i = 0; i < a.length; i++) {
                formdata.append(a[i].name, a[i].value);
            }

            if (options.extraData) {
                var serializedData = deepSerialize(options.extraData);
                for (i = 0; i < serializedData.length; i++) {
                    if (serializedData[i]) {
                        formdata.append(serializedData[i][0], serializedData[i][1]);
                    }
                }
            }

            options.data = null;

            var s = $.extend(true, {}, $.ajaxSettings, options, {
                contentType: false,
                processData: false,
                cache: false,
                type: method || 'POST'
            });

            if (options.uploadProgress) {
                // workaround because jqXHR does not expose upload property
                s.xhr = function () {
                    var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function (event) {
                            var percent = 0;
                            var position = event.loaded || event.position;
                            /*event.position is deprecated*/
                            var total = event.total;
                            if (event.lengthComputable) {
                                percent = Math.ceil(position / total * 100);
                            }
                            options.uploadProgress(event, position, total, percent);
                        }, false);
                    }
                    return xhr;
                };
            }

            s.data = null;
            var beforeSend = s.beforeSend;
            s.beforeSend = function (xhr, o) {
                //Send FormData() provided by user
                if (options.formData) {
                    o.data = options.formData;
                }
                else {
                    o.data = formdata;
                }
                if (beforeSend) {
                    beforeSend.call(this, xhr, o);
                }
            };
            return $.ajax(s);
        }

        // private function for handling file uploads (hat tip to YAHOO!)
        function fileUploadIframe(a) {
            var form = $form[0], el, i, s, g, id, $io, io, xhr, sub, n, timedOut, timeoutHandle;
            var deferred = $.Deferred();

            // #341
            deferred.abort = function (status) {
                xhr.abort(status);
            };

            if (a) {
                // ensure that every serialized input is still enabled
                for (i = 0; i < elements.length; i++) {
                    el = $(elements[i]);
                    if (hasProp) {
                        el.prop('disabled', false);
                    }
                    else {
                        el.removeAttr('disabled');
                    }
                }
            }

            s = $.extend(true, {}, $.ajaxSettings, options);
            s.context = s.context || s;
            id = 'jqFormIO' + (new Date().getTime());
            if (s.iframeTarget) {
                $io = $(s.iframeTarget);
                n = $io.attr2('name');
                if (!n) {
                    $io.attr2('name', id);
                }
                else {
                    id = n;
                }
            }
            else {
                $io = $('<iframe name="' + id + '" src="' + s.iframeSrc + '" />');
                $io.css({position: 'absolute', top: '-1000px', left: '-1000px'});
            }
            io = $io[0];


            xhr = { // mock object
                aborted: 0,
                responseText: null,
                responseXML: null,
                status: 0,
                statusText: 'n/a',
                getAllResponseHeaders: function () {
                },
                getResponseHeader: function () {
                },
                setRequestHeader: function () {
                },
                abort: function (status) {
                    var e = (status === 'timeout' ? 'timeout' : 'aborted');
                    log('aborting upload... ' + e);
                    this.aborted = 1;

                    try { // #214, #257
                        if (io.contentWindow.document.execCommand) {
                            io.contentWindow.document.execCommand('Stop');
                        }
                    }
                    catch (ignore) {
                    }

                    $io.attr('src', s.iframeSrc); // abort op in progress
                    xhr.error = e;
                    if (s.error) {
                        s.error.call(s.context, xhr, e, status);
                    }
                    if (g) {
                        $.event.trigger("ajaxError", [xhr, s, e]);
                    }
                    if (s.complete) {
                        s.complete.call(s.context, xhr, e);
                    }
                }
            };

            g = s.global;
            // trigger ajax global events so that activity/block indicators work like normal
            if (g && 0 === $.active++) {
                $.event.trigger("ajaxStart");
            }
            if (g) {
                $.event.trigger("ajaxSend", [xhr, s]);
            }

            if (s.beforeSend && s.beforeSend.call(s.context, xhr, s) === false) {
                if (s.global) {
                    $.active--;
                }
                deferred.reject();
                return deferred;
            }
            if (xhr.aborted) {
                deferred.reject();
                return deferred;
            }

            // add submitting element to data if we know it
            sub = form.clk;
            if (sub) {
                n = sub.name;
                if (n && !sub.disabled) {
                    s.extraData = s.extraData || {};
                    s.extraData[n] = sub.value;
                    if (sub.type == "image") {
                        s.extraData[n + '.x'] = form.clk_x;
                        s.extraData[n + '.y'] = form.clk_y;
                    }
                }
            }

            var CLIENT_TIMEOUT_ABORT = 1;
            var SERVER_ABORT = 2;

            function getDoc(frame) {
                /* it looks like contentWindow or contentDocument do not
                 * carry the protocol property in ie8, when running under ssl
                 * frame.document is the only valid response document, since
                 * the protocol is know but not on the other two objects. strange?
                 * "Same origin policy" http://en.wikipedia.org/wiki/Same_origin_policy
                 */

                var doc = null;

                // IE8 cascading access check
                try {
                    if (frame.contentWindow) {
                        doc = frame.contentWindow.document;
                    }
                } catch (err) {
                    // IE8 access denied under ssl & missing protocol
                    log('cannot get iframe.contentWindow document: ' + err);
                }

                if (doc) { // successful getting content
                    return doc;
                }

                try { // simply checking may throw in ie8 under ssl or mismatched protocol
                    doc = frame.contentDocument ? frame.contentDocument : frame.document;
                } catch (err) {
                    // last attempt
                    log('cannot get iframe.contentDocument: ' + err);
                    doc = frame.document;
                }
                return doc;
            }

            // Rails CSRF hack (thanks to Yvan Barthelemy)
            var csrf_token = $('meta[name=csrf-token]').attr('content');
            var csrf_param = $('meta[name=csrf-param]').attr('content');
            if (csrf_param && csrf_token) {
                s.extraData = s.extraData || {};
                s.extraData[csrf_param] = csrf_token;
            }

            // take a breath so that pending repaints get some cpu time before the upload starts
            function doSubmit() {
                // make sure form attrs are set
                var t = $form.attr2('target'),
                    a = $form.attr2('action'),
                    mp = 'multipart/form-data',
                    et = $form.attr('enctype') || $form.attr('encoding') || mp;

                // update form attrs in IE friendly way
                form.setAttribute('target', id);
                if (!method || /post/i.test(method)) {
                    form.setAttribute('method', 'POST');
                }
                if (a != s.url) {
                    form.setAttribute('action', s.url);
                }

                // ie borks in some cases when setting encoding
                if (!s.skipEncodingOverride && (!method || /post/i.test(method))) {
                    $form.attr({
                        encoding: 'multipart/form-data',
                        enctype: 'multipart/form-data'
                    });
                }

                // support timout
                if (s.timeout) {
                    timeoutHandle = setTimeout(function () {
                        timedOut = true;
                        cb(CLIENT_TIMEOUT_ABORT);
                    }, s.timeout);
                }

                // look for server aborts
                function checkState() {
                    try {
                        var state = getDoc(io).readyState;
                        log('state = ' + state);
                        if (state && state.toLowerCase() == 'uninitialized') {
                            setTimeout(checkState, 50);
                        }
                    }
                    catch (e) {
                        log('Server abort: ', e, ' (', e.name, ')');
                        cb(SERVER_ABORT);
                        if (timeoutHandle) {
                            clearTimeout(timeoutHandle);
                        }
                        timeoutHandle = undefined;
                    }
                }

                // add "extra" data to form if provided in options
                var extraInputs = [];
                try {
                    if (s.extraData) {
                        for (var n in s.extraData) {
                            if (s.extraData.hasOwnProperty(n)) {
                                // if using the $.param format that allows for multiple values with the same name
                                if ($.isPlainObject(s.extraData[n]) && s.extraData[n].hasOwnProperty('name') && s.extraData[n].hasOwnProperty('value')) {
                                    extraInputs.push(
                                        $('<input type="hidden" name="' + s.extraData[n].name + '">').val(s.extraData[n].value)
                                            .appendTo(form)[0]);
                                } else {
                                    extraInputs.push(
                                        $('<input type="hidden" name="' + n + '">').val(s.extraData[n])
                                            .appendTo(form)[0]);
                                }
                            }
                        }
                    }

                    if (!s.iframeTarget) {
                        // add iframe to doc and submit the form
                        $io.appendTo('body');
                    }
                    if (io.attachEvent) {
                        io.attachEvent('onload', cb);
                    }
                    else {
                        io.addEventListener('load', cb, false);
                    }
                    setTimeout(checkState, 15);

                    try {
                        form.submit();
                    } catch (err) {
                        // just in case form has element with name/id of 'submit'
                        var submitFn = document.createElement('form').submit;
                        submitFn.apply(form);
                    }
                }
                finally {
                    // reset attrs and remove "extra" input elements
                    form.setAttribute('action', a);
                    form.setAttribute('enctype', et); // #380
                    if (t) {
                        form.setAttribute('target', t);
                    } else {
                        $form.removeAttr('target');
                    }
                    $(extraInputs).remove();
                }
            }

            if (s.forceSync) {
                doSubmit();
            }
            else {
                setTimeout(doSubmit, 10); // this lets dom updates render
            }

            var data, doc, domCheckCount = 50, callbackProcessed;

            function cb(e) {
                if (xhr.aborted || callbackProcessed) {
                    return;
                }

                doc = getDoc(io);
                if (!doc) {
                    log('cannot access response document');
                    e = SERVER_ABORT;
                }
                if (e === CLIENT_TIMEOUT_ABORT && xhr) {
                    xhr.abort('timeout');
                    deferred.reject(xhr, 'timeout');
                    return;
                }
                else if (e == SERVER_ABORT && xhr) {
                    xhr.abort('server abort');
                    deferred.reject(xhr, 'error', 'server abort');
                    return;
                }

                if (!doc || doc.location.href == s.iframeSrc) {
                    // response not received yet
                    if (!timedOut) {
                        return;
                    }
                }
                if (io.detachEvent) {
                    io.detachEvent('onload', cb);
                }
                else {
                    io.removeEventListener('load', cb, false);
                }

                var status = 'success', errMsg;
                try {
                    if (timedOut) {
                        throw 'timeout';
                    }

                    var isXml = s.dataType == 'xml' || doc.XMLDocument || $.isXMLDoc(doc);
                    log('isXml=' + isXml);
                    if (!isXml && window.opera && (doc.body === null || !doc.body.innerHTML)) {
                        if (--domCheckCount) {
                            // in some browsers (Opera) the iframe DOM is not always traversable when
                            // the onload callback fires, so we loop a bit to accommodate
                            log('requeing onLoad callback, DOM not available');
                            setTimeout(cb, 250);
                            return;
                        }
                        // let this fall through because server response could be an empty document
                        //log('Could not access iframe DOM after mutiple tries.');
                        //throw 'DOMException: not available';
                    }

                    //log('response detected');
                    var docRoot = doc.body ? doc.body : doc.documentElement;
                    xhr.responseText = docRoot ? docRoot.innerHTML : null;
                    xhr.responseXML = doc.XMLDocument ? doc.XMLDocument : doc;
                    if (isXml) {
                        s.dataType = 'xml';
                    }
                    xhr.getResponseHeader = function (header) {
                        var headers = {'content-type': s.dataType};
                        return headers[header.toLowerCase()];
                    };
                    // support for XHR 'status' & 'statusText' emulation :
                    if (docRoot) {
                        xhr.status = Number(docRoot.getAttribute('status')) || xhr.status;
                        xhr.statusText = docRoot.getAttribute('statusText') || xhr.statusText;
                    }

                    var dt = (s.dataType || '').toLowerCase();
                    var scr = /(json|script|text)/.test(dt);
                    if (scr || s.textarea) {
                        // see if user embedded response in textarea
                        var ta = doc.getElementsByTagName('textarea')[0];
                        if (ta) {
                            xhr.responseText = ta.value;
                            // support for XHR 'status' & 'statusText' emulation :
                            xhr.status = Number(ta.getAttribute('status')) || xhr.status;
                            xhr.statusText = ta.getAttribute('statusText') || xhr.statusText;
                        }
                        else if (scr) {
                            // account for browsers injecting pre around json response
                            var pre = doc.getElementsByTagName('pre')[0];
                            var b = doc.getElementsByTagName('body')[0];
                            if (pre) {
                                xhr.responseText = pre.textContent ? pre.textContent : pre.innerText;
                            }
                            else if (b) {
                                xhr.responseText = b.textContent ? b.textContent : b.innerText;
                            }
                        }
                    }
                    else if (dt == 'xml' && !xhr.responseXML && xhr.responseText) {
                        xhr.responseXML = toXml(xhr.responseText);
                    }

                    try {
                        data = httpData(xhr, dt, s);
                    }
                    catch (err) {
                        status = 'parsererror';
                        xhr.error = errMsg = (err || status);
                    }
                }
                catch (err) {
                    log('error caught: ', err);
                    status = 'error';
                    xhr.error = errMsg = (err || status);
                }

                if (xhr.aborted) {
                    log('upload aborted');
                    status = null;
                }

                if (xhr.status) { // we've set xhr.status
                    status = (xhr.status >= 200 && xhr.status < 300 || xhr.status === 304) ? 'success' : 'error';
                }

                // ordering of these callbacks/triggers is odd, but that's how $.ajax does it
                if (status === 'success') {
                    if (s.success) {
                        s.success.call(s.context, data, 'success', xhr);
                    }
                    deferred.resolve(xhr.responseText, 'success', xhr);
                    if (g) {
                        $.event.trigger("ajaxSuccess", [xhr, s]);
                    }
                }
                else if (status) {
                    if (errMsg === undefined) {
                        errMsg = xhr.statusText;
                    }
                    if (s.error) {
                        s.error.call(s.context, xhr, status, errMsg);
                    }
                    deferred.reject(xhr, 'error', errMsg);
                    if (g) {
                        $.event.trigger("ajaxError", [xhr, s, errMsg]);
                    }
                }

                if (g) {
                    $.event.trigger("ajaxComplete", [xhr, s]);
                }

                if (g && !--$.active) {
                    $.event.trigger("ajaxStop");
                }

                if (s.complete) {
                    s.complete.call(s.context, xhr, status);
                }

                callbackProcessed = true;
                if (s.timeout) {
                    clearTimeout(timeoutHandle);
                }

                // clean up
                setTimeout(function () {
                    if (!s.iframeTarget) {
                        $io.remove();
                    }
                    else { //adding else to clean up existing iframe response.
                        $io.attr('src', s.iframeSrc);
                    }
                    xhr.responseXML = null;
                }, 100);
            }

            var toXml = $.parseXML || function (s, doc) { // use parseXML if available (jQuery 1.5+)
                    if (window.ActiveXObject) {
                        doc = new ActiveXObject('Microsoft.XMLDOM');
                        doc.async = 'false';
                        doc.loadXML(s);
                    }
                    else {
                        doc = (new DOMParser()).parseFromString(s, 'text/xml');
                    }
                    return (doc && doc.documentElement && doc.documentElement.nodeName != 'parsererror') ? doc : null;
                };
            var parseJSON = $.parseJSON || function (s) {
                    /*jslint evil:true */
                    return window['eval']('(' + s + ')');
                };

            var httpData = function (xhr, type, s) { // mostly lifted from jq1.4.4

                var ct = xhr.getResponseHeader('content-type') || '',
                    xml = type === 'xml' || !type && ct.indexOf('xml') >= 0,
                    data = xml ? xhr.responseXML : xhr.responseText;

                if (xml && data.documentElement.nodeName === 'parsererror') {
                    if ($.error) {
                        $.error('parsererror');
                    }
                }
                if (s && s.dataFilter) {
                    data = s.dataFilter(data, type);
                }
                if (typeof data === 'string') {
                    if (type === 'json' || !type && ct.indexOf('json') >= 0) {
                        data = parseJSON(data);
                    } else if (type === "script" || !type && ct.indexOf("javascript") >= 0) {
                        $.globalEval(data);
                    }
                }
                return data;
            };

            return deferred;
        }
    };

    /**
     * ajaxForm() provides a mechanism for fully automating form submission.
     *
     * The advantages of using this method instead of ajaxSubmit() are:
     *
     * 1: This method will include coordinates for <input type="image" /> elements (if the element
     *    is used to submit the form).
     * 2. This method will include the submit element's name/value data (for the element that was
     *    used to submit the form).
     * 3. This method binds the submit() method to the form for you.
     *
     * The options argument for ajaxForm works exactly as it does for ajaxSubmit.  ajaxForm merely
     * passes the options argument along after properly binding events for submit elements and
     * the form itself.
     */
    $.fn.ajaxForm = function (options) {
        options = options || {};
        options.delegation = options.delegation && $.isFunction($.fn.on);

        // in jQuery 1.3+ we can fix mistakes with the ready state
        if (!options.delegation && this.length === 0) {
            var o = {s: this.selector, c: this.context};
            if (!$.isReady && o.s) {
                log('DOM not ready, queuing ajaxForm');
                $(function () {
                    $(o.s, o.c).ajaxForm(options);
                });
                return this;
            }
            // is your DOM ready?  http://docs.jquery.com/Tutorials:Introducing_$(document).ready()
            log('terminating; zero elements found by selector' + ($.isReady ? '' : ' (DOM not ready)'));
            return this;
        }

        if (options.delegation) {
            $(document)
                .off('submit.form-plugin', this.selector, doAjaxSubmit)
                .off('click.form-plugin', this.selector, captureSubmittingElement)
                .on('submit.form-plugin', this.selector, options, doAjaxSubmit)
                .on('click.form-plugin', this.selector, options, captureSubmittingElement);
            return this;
        }

        return this.ajaxFormUnbind()
            .bind('submit.form-plugin', options, doAjaxSubmit)
            .bind('click.form-plugin', options, captureSubmittingElement);
    };

// private event handlers
    function doAjaxSubmit(e) {
        /*jshint validthis:true */
        var options = e.data;
        if (!e.isDefaultPrevented()) { // if event has been canceled, don't proceed
            e.preventDefault();
            $(e.target).ajaxSubmit(options); // #365
        }
    }

    function captureSubmittingElement(e) {
        /*jshint validthis:true */
        var target = e.target;
        var $el = $(target);
        if (!($el.is("[type=submit],[type=image]"))) {
            // is this a child element of the submit el?  (ex: a span within a button)
            var t = $el.closest('[type=submit]');
            if (t.length === 0) {
                return;
            }
            target = t[0];
        }
        var form = this;
        form.clk = target;
        if (target.type == 'image') {
            if (e.offsetX !== undefined) {
                form.clk_x = e.offsetX;
                form.clk_y = e.offsetY;
            } else if (typeof $.fn.offset == 'function') {
                var offset = $el.offset();
                form.clk_x = e.pageX - offset.left;
                form.clk_y = e.pageY - offset.top;
            } else {
                form.clk_x = e.pageX - target.offsetLeft;
                form.clk_y = e.pageY - target.offsetTop;
            }
        }
        // clear form vars
        setTimeout(function () {
            form.clk = form.clk_x = form.clk_y = null;
        }, 100);
    }


// ajaxFormUnbind unbinds the event handlers that were bound by ajaxForm
    $.fn.ajaxFormUnbind = function () {
        return this.unbind('submit.form-plugin click.form-plugin');
    };

    /**
     * formToArray() gathers form element data into an array of objects that can
     * be passed to any of the following ajax functions: $.get, $.post, or load.
     * Each object in the array has both a 'name' and 'value' property.  An example of
     * an array for a simple login form might be:
     *
     * [ { name: 'username', value: 'jresig' }, { name: 'password', value: 'secret' } ]
     *
     * It is this array that is passed to pre-submit callback functions provided to the
     * ajaxSubmit() and ajaxForm() methods.
     */
    $.fn.formToArray = function (semantic, elements) {
        var a = [];
        if (this.length === 0) {
            return a;
        }

        var form = this[0];
        var formId = this.attr('id');
        var els = semantic ? form.getElementsByTagName('*') : form.elements;
        var els2;

        if (els && !/MSIE [678]/.test(navigator.userAgent)) { // #390
            els = $(els).get();  // convert to standard array
        }

        // #386; account for inputs outside the form which use the 'form' attribute
        if (formId) {
            els2 = $(':input[form="' + formId + '"]').get(); // hat tip @thet
            if (els2.length) {
                els = (els || []).concat(els2);
            }
        }

        if (!els || !els.length) {
            return a;
        }

        var i, j, n, v, el, max, jmax;
        for (i = 0, max = els.length; i < max; i++) {
            el = els[i];
            n = el.name;
            if (!n || el.disabled) {
                continue;
            }

            if (semantic && form.clk && el.type == "image") {
                // handle image inputs on the fly when semantic == true
                if (form.clk == el) {
                    a.push({name: n, value: $(el).val(), type: el.type});
                    a.push({name: n + '.x', value: form.clk_x}, {name: n + '.y', value: form.clk_y});
                }
                continue;
            }

            v = $.fieldValue(el, true);
            if (v && v.constructor == Array) {
                if (elements) {
                    elements.push(el);
                }
                for (j = 0, jmax = v.length; j < jmax; j++) {
                    a.push({name: n, value: v[j]});
                }
            }
            else if (feature.fileapi && el.type == 'file') {
                if (elements) {
                    elements.push(el);
                }
                var files = el.files;
                if (files.length) {
                    for (j = 0; j < files.length; j++) {
                        a.push({name: n, value: files[j], type: el.type});
                    }
                }
                else {
                    // #180
                    a.push({name: n, value: '', type: el.type});
                }
            }
            else if (v !== null && typeof v != 'undefined') {
                if (elements) {
                    elements.push(el);
                }
                a.push({name: n, value: v, type: el.type, required: el.required});
            }
        }

        if (!semantic && form.clk) {
            // input type=='image' are not found in elements array! handle it here
            var $input = $(form.clk), input = $input[0];
            n = input.name;
            if (n && !input.disabled && input.type == 'image') {
                a.push({name: n, value: $input.val()});
                a.push({name: n + '.x', value: form.clk_x}, {name: n + '.y', value: form.clk_y});
            }
        }
        return a;
    };

    /**
     * Serializes form data into a 'submittable' string. This method will return a string
     * in the format: name1=value1&amp;name2=value2
     */
    $.fn.formSerialize = function (semantic) {
        //hand off to jQuery.param for proper encoding
        return $.param(this.formToArray(semantic));
    };

    /**
     * Serializes all field elements in the jQuery object into a query string.
     * This method will return a string in the format: name1=value1&amp;name2=value2
     */
    $.fn.fieldSerialize = function (successful) {
        var a = [];
        this.each(function () {
            var n = this.name;
            if (!n) {
                return;
            }
            var v = $.fieldValue(this, successful);
            if (v && v.constructor == Array) {
                for (var i = 0, max = v.length; i < max; i++) {
                    a.push({name: n, value: v[i]});
                }
            }
            else if (v !== null && typeof v != 'undefined') {
                a.push({name: this.name, value: v});
            }
        });
        //hand off to jQuery.param for proper encoding
        return $.param(a);
    };

    /**
     * Returns the value(s) of the element in the matched set.  For example, consider the following form:
     *
     *  <form><fieldset>
     *      <input name="A" type="text" />
     *      <input name="A" type="text" />
     *      <input name="B" type="checkbox" value="B1" />
     *      <input name="B" type="checkbox" value="B2"/>
     *      <input name="C" type="radio" value="C1" />
     *      <input name="C" type="radio" value="C2" />
     *  </fieldset></form>
     *
     *  var v = $('input[type=text]').fieldValue();
     *  // if no values are entered into the text inputs
     *  v == ['','']
     *  // if values entered into the text inputs are 'foo' and 'bar'
     *  v == ['foo','bar']
     *
     *  var v = $('input[type=checkbox]').fieldValue();
     *  // if neither checkbox is checked
     *  v === undefined
     *  // if both checkboxes are checked
     *  v == ['B1', 'B2']
     *
     *  var v = $('input[type=radio]').fieldValue();
     *  // if neither radio is checked
     *  v === undefined
     *  // if first radio is checked
     *  v == ['C1']
     *
     * The successful argument controls whether or not the field element must be 'successful'
     * (per http://www.w3.org/TR/html4/interact/forms.html#successful-controls).
     * The default value of the successful argument is true.  If this value is false the value(s)
     * for each element is returned.
     *
     * Note: This method *always* returns an array.  If no valid value can be determined the
     *    array will be empty, otherwise it will contain one or more values.
     */
    $.fn.fieldValue = function (successful) {
        for (var val = [], i = 0, max = this.length; i < max; i++) {
            var el = this[i];
            var v = $.fieldValue(el, successful);
            if (v === null || typeof v == 'undefined' || (v.constructor == Array && !v.length)) {
                continue;
            }
            if (v.constructor == Array) {
                $.merge(val, v);
            }
            else {
                val.push(v);
            }
        }
        return val;
    };

    /**
     * Returns the value of the field element.
     */
    $.fieldValue = function (el, successful) {
        var n = el.name, t = el.type, tag = el.tagName.toLowerCase();
        if (successful === undefined) {
            successful = true;
        }

        if (successful && (!n || el.disabled || t == 'reset' || t == 'button' ||
            (t == 'checkbox' || t == 'radio') && !el.checked ||
            (t == 'submit' || t == 'image') && el.form && el.form.clk != el ||
            tag == 'select' && el.selectedIndex == -1)) {
            return null;
        }

        if (tag == 'select') {
            var index = el.selectedIndex;
            if (index < 0) {
                return null;
            }
            var a = [], ops = el.options;
            var one = (t == 'select-one');
            var max = (one ? index + 1 : ops.length);
            for (var i = (one ? index : 0); i < max; i++) {
                var op = ops[i];
                if (op.selected) {
                    var v = op.value;
                    if (!v) { // extra pain for IE...
                        v = (op.attributes && op.attributes.value && !(op.attributes.value.specified)) ? op.text : op.value;
                    }
                    if (one) {
                        return v;
                    }
                    a.push(v);
                }
            }
            return a;
        }
        return $(el).val();
    };

    /**
     * Clears the form data.  Takes the following actions on the form's input fields:
     *  - input text fields will have their 'value' property set to the empty string
     *  - select elements will have their 'selectedIndex' property set to -1
     *  - checkbox and radio inputs will have their 'checked' property set to false
     *  - inputs of type submit, button, reset, and hidden will *not* be effected
     *  - button elements will *not* be effected
     */
    $.fn.clearForm = function (includeHidden) {
        return this.each(function () {
            $('input,select,textarea', this).clearFields(includeHidden);
        });
    };

    /**
     * Clears the selected form elements.
     */
    $.fn.clearFields = $.fn.clearInputs = function (includeHidden) {
        var re = /^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i; // 'hidden' is not in this list
        return this.each(function () {
            var t = this.type, tag = this.tagName.toLowerCase();
            if (re.test(t) || tag == 'textarea') {
                this.value = '';
            }
            else if (t == 'checkbox' || t == 'radio') {
                this.checked = false;
            }
            else if (tag == 'select') {
                this.selectedIndex = -1;
            }
            else if (t == "file") {
                if (/MSIE/.test(navigator.userAgent)) {
                    $(this).replaceWith($(this).clone(true));
                } else {
                    $(this).val('');
                }
            }
            else if (includeHidden) {
                // includeHidden can be the value true, or it can be a selector string
                // indicating a special test; for example:
                //  $('#myForm').clearForm('.special:hidden')
                // the above would clean hidden inputs that have the class of 'special'
                if ((includeHidden === true && /hidden/.test(t)) ||
                    (typeof includeHidden == 'string' && $(this).is(includeHidden))) {
                    this.value = '';
                }
            }
        });
    };

    /**
     * Resets the form data.  Causes all form elements to be reset to their original value.
     */
    $.fn.resetForm = function () {
        return this.each(function () {
            // guard against an input with the name of 'reset'
            // note that IE reports the reset function as an 'object'
            if (typeof this.reset == 'function' || (typeof this.reset == 'object' && !this.reset.nodeType)) {
                this.reset();
            }
        });
    };

    /**
     * Enables or disables any matching elements.
     */
    $.fn.enable = function (b) {
        if (b === undefined) {
            b = true;
        }
        return this.each(function () {
            this.disabled = !b;
        });
    };

    /**
     * Checks/unchecks any matching checkboxes or radio buttons and
     * selects/deselects and matching option elements.
     */
    $.fn.selected = function (select) {
        if (select === undefined) {
            select = true;
        }
        return this.each(function () {
            var t = this.type;
            if (t == 'checkbox' || t == 'radio') {
                this.checked = select;
            }
            else if (this.tagName.toLowerCase() == 'option') {
                var $sel = $(this).parent('select');
                if (select && $sel[0] && $sel[0].type == 'select-one') {
                    // deselect all other options
                    $sel.find('option').selected(false);
                }
                this.selected = select;
            }
        });
    };

// expose debug var
    $.fn.ajaxSubmit.debug = false;

// helper fn for console logging
    function log() {
        if (!$.fn.ajaxSubmit.debug) {
            return;
        }
        var msg = '[jquery.form] ' + Array.prototype.join.call(arguments, '');
        if (window.console && window.console.log) {
            window.console.log(msg);
        }
        else if (window.opera && window.opera.postError) {
            window.opera.postError(msg);
        }
    }

}));
