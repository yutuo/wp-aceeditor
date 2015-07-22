(function(factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as anonymous module.
        define([ 'jquery' ], factory);
    } else {
        // Browser globals.
        factory(jQuery);
    }
}(function($) {

    var pluses = /\+/g;

    function encode(s) {
        return config.raw ? s : encodeURIComponent(s);
    }

    function decode(s) {
        return config.raw ? s : decodeURIComponent(s);
    }

    function stringifyCookieValue(value) {
        return encode(config.json ? JSON.stringify(value) : String(value));
    }

    function parseCookieValue(s) {
        if (s.indexOf('"') === 0) {
            // This is a quoted cookie as according to RFC2068, unescape...
            s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
        }

        try {
            // Replace server-side written pluses with spaces.
            // If we can't decode the cookie, ignore it, it's unusable.
            s = decodeURIComponent(s.replace(pluses, ' '));
        } catch (e) {
            return;
        }

        try {
            // If we can't parse the cookie, ignore it, it's unusable.
            return config.json ? JSON.parse(s) : s;
        } catch (e) {
        }
    }

    function read(s, converter) {
        var value = config.raw ? s : parseCookieValue(s);
        return $.isFunction(converter) ? converter(value) : value;
    }

    var config = $.cookie = function(key, value, options) {

        // Write
        if (value !== undefined && !$.isFunction(value)) {
            options = $.extend({}, config.defaults, options);

            if (typeof options.expires === 'number') {
                var days = options.expires, t = options.expires = new Date();
                t.setDate(t.getDate() + days);
            }

            return (document.cookie = [
                    encode(key),
                    '=',
                    stringifyCookieValue(value),
                    options.expires ? '; expires='
                            + options.expires.toUTCString() : '', // use
                    // expires
                    // attribute,
                    // max-age
                    // is not
                    // supported
                    // by IE
                    options.path ? '; path=' + options.path : '',
                    options.domain ? '; domain=' + options.domain : '',
                    options.secure ? '; secure' : '' ].join(''));
        }

        // Read

        var result = key ? undefined : {};

        // To prevent the for loop in the first place assign an empty array
        // in case there are no cookies at all. Also prevents odd result when
        // calling $.cookie().
        var cookies = document.cookie ? document.cookie.split('; ') : [];

        for (var i = 0, l = cookies.length; i < l; i++) {
            var parts = cookies[i].split('=');
            var name = decode(parts.shift());
            var cookie = parts.join('=');

            if (key && key === name) {
                // If second argument (value) is a function it's a converter...
                result = read(cookie, value);
                break;
            }

            // Prevent storing a cookie that we couldn't decode.
            if (!key && (cookie = read(cookie)) !== undefined) {
                result[name] = cookie;
            }
        }

        return result;
    };

    config.defaults = {};

    $.removeCookie = function(key, options) {
        if ($.cookie(key) !== undefined) {
            // Must not alter options, thus extending a fresh object...
            $.cookie(key, '', $.extend({}, options, {
                expires : -1
            }));
            return true;
        }
        return false;
    };

}));
ace.config.set("basePath", "/wp-content/plugins/wp-aceeditor/js/ace");
var WpAceEditor = function(options) {
    this.$ = jQuery;
    this.options = options;

    /** 转换所有项目 */
    this.convertAll = function() {
        var items = this.matchAllItems();
        for (var i = 0; i < items.length; i++) {
            var item = items.eq(i);
            var options = this.getOptions(item);

            if (item.get(0).nodeName.toLowerCase() === 'pre') {
                // 多行代码转换
                this.convertItem(item, options);
            } else {
                // 行内代码转换
                this.heightItem(item, options);
            }
        }
    };

    /** 取得所有要转换的项目 */
    this.matchAllItems = function() {
        var matchString = 'pre[data-hl], code[data-hl]';
        return this.$(matchString);
    };

    /** 多行代码转换 */
    this.convertItem = function(item, options) {
        var value = item.text().replace(/^(\s*?[\r\n]+)+/, '');
        value = value.replace(/\s+$/, '');
        if (options['tabtospace']) {
            value = value.replace(/\t/, '    ');
        }
        var divItem = this.$('<div></div>');
        item.after(divItem);
        item.hide();
        divItem.show();

        var editor = ace.edit(divItem.get(0));
        editor.setOptions({
            autoScrollEditorIntoView: true,
            maxLines: 999999999
        });
        editor.getSession().setValue(value);
        // 设置属性
        this.setOptions(editor, options);
        editor.renderer.setScrollMargin(8, 8, 0, 0);
        return editor;
    };

    /** 单行代码转换 */
    this.heightItem = function(item, options) {
        var highlighter = ace.require("ace/ext/static_highlight");

        // 显示样式
        var theme = "ace/theme/" + options['theme'];
        // 显示语言
        var mode = 'ace/mode/' + options['lang'];
        if (options['lang'] === 'php-inline') {
            mode = {path: 'ace/mode/php', inline: true};
        }

        var divItem = this.$('<div class="onelinehl"></div>');
        item.after(divItem);
        divItem.html(item.html());
        item.hide();
        divItem.show();

        highlighter.highlight(divItem.get(0), {mode: mode, theme: theme});
    };

    /** 取得属性 */
    this.getOptions = function(item) {
        var result = [];
        for ( var key in options) {
            result[key] = options[key];
        }

        if (item == null) {
            return result;
        }

        var htmlOptions = this.getHtmlOptions(item);
        for ( var key in htmlOptions) {
            if (typeof (this.changeOptions[key]) !== 'undefined') {
                var convtentValue = this.changeOptions[key](htmlOptions[key]);
                if (convtentValue !== null) {
                    result[key] = convtentValue;
                }
            }
        }
        return result;
    };
    /** 取得HTML设置的属性 */
    this.getHtmlOptions = function(item) {
        var getAllAttr = function(htmlItem) {
            var result = [];
            for ( var index in htmlItem.attributes) {
                var attr = htmlItem.attributes[index];
                result[attr.name] = attr.value;
            }
            return result;
        };

        var result = [];
        var allAttr = getAllAttr(item[0]);
        if (typeof (allAttr['data-hl']) !== 'undefined') {
            var attrstrs = allAttr['data-hl'].split(';');
            for ( var i in attrstrs) {
                var attrs = attrstrs[i].split(':');
                if (attrs.length >= 2) {
                    result[jQuery.trim(attrs[0])] = jQuery.trim(attrs[1]);
                }
            }
        } else if (typeof (allAttr['data-hl-lang']) !== 'undefined') {
            var startLenght = 'data-hl-'.length;
            for ( var key in allAttr) {
                if (key.length > startLenght) {
                    result[key.substring(startLenght)] = allAttr[key];
                }
            }
        } else {
            result = allAttr;
        }

        return result;
    };

    /** 设置属性 */
    this.setOptions = function(editor, options) {
        // 代码只读
        editor.setReadOnly(options['readonly']);
        // 显示样式
        editor.setTheme('ace/theme/' + options['theme']);
        // 显示语言
        if (options['lang'] === 'php-inline') {
            editor.getSession().setMode({path: 'ace/mode/php', inline: true});
        } else {
            editor.getSession().setMode('ace/mode/' + options['lang']);
        }
        // Tab宽度
        editor.getSession().setTabSize(options['tabsise']);
        // 文字大小
        editor.setFontSize(options['fontsize']);
        // 自动换行
        editor.getSession().setUseWrapMode(options['wrap']);
        // 打印边界大小
        editor.renderer.setPrintMarginColumn(options['print']);
        // 默认收缩
        if (options['foldstyle'] != 'manual' && options['fold']) {
            editor.getSession().foldAll();
        }
        // 缩进边界显示
        editor.setDisplayIndentGuides(options['indent']);
        // 显示行号
        editor.renderer.setShowGutter(options['gutter']);
        // 活动行高亮显示
        editor.setHighlightActiveLine(options['active']);
        // 代码收缩样式
        editor.getSession().setFoldStyle(options['foldstyle']);

        return editor;
    };

    this.changeOptions = new function() {
        this.__int = function(value) {
            var returnVal = parseInt(value);
            return isNaN(returnVal) ? null : returnVal;
        };
        this.__boolean = function(value) {
            if (typeof (value) === 'boolean') {
                return value;
            }
            value = value.toLowerCase();
            return [ '1', 'yes', 'true' ].indexOf(value) >= 0;
        };
        this.__lowercase = function(value) {
            return value.toLowerCase();
        };
        this.__value = function(value) {
            return value;
        };
        // 代码只读
        this.readonly = this.__boolean;
        // 显示样式
        this.theme = this.__lowercase;
        // 显示语言
        this.lang = function(value) {
            value = value.toLowerCase();
            if (value == 'c' || value == 'cpp') {
                value = 'c_cpp';
            } else if (value == 'dos') {
                value = 'batchfile';
            }
            return value;
        };
        // Tab宽度
        this.tabsize = this.__int;
        // 行高
        this.lineheight = this.__int;
        // 文字大小
        this.fontsize = this.__int;
        // 自动换行
        this.wrap = this.__boolean;
        // 打印边界大小
        this.print = this.__int;
        // 显示宽度
        this.width = this.__value;
        // Tab转换成空格显示
        this.tabtospace = this.__boolean;
        // 默认收缩
        this.fold = this.__boolean;
        // 缩进边界显示
        this.indent = this.__boolean;
        // 显示行号
        this.gutter = this.__boolean;
        // 活动行高亮显示
        this.active = this.__boolean;
        // 代码收缩样式
        this.foldstyle = function(value) {
            value = value.toLowerCase();
            var valueList = [ 'none', 'manual', 'begin', 'markbegin',
                    'beginend', 'markbeginend' ];
            var index = valueList.indexOf(value);
            if (index < 0) {
                return null;
            } else {
                return valueList[Math.floor(index / 2) * 2 + 1];
            }
        };
    };
};

function convertToAceEditor(options) {
    var aceEditor = new WpAceEditor(options);
    aceEditor.convertAll();
}