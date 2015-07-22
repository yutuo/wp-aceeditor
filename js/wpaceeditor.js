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
    this.ITEM_KEY = 'data-hl-id';
    this.DIV_KEY = 'WpAceEditor_';

    this.$ = jQuery;
    this.options = options;
    this.convertedEditor = [];
    this.delayFunctions = [];
    this.convertedCount = 0;

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
    /** 转换所有项目 */
    this.convertAll = function() {
        var items = this.matchAllItems();

        for (var i = 0; i < items.length; i++) {
            var item = items.eq(i);
            if (typeof (item.attr(this.ITEM_KEY)) !== 'undefined') {
                return;
            }
            this.reConvertItem(item);
        }
    };
    /** 取得所有要转换的项目 */
    this.matchAllItems = function() {
        var matchString = 'pre[data-hl], code[data-hl]';
        return this.$(matchString);
    };
    /** 重新转换 */
    this.reConvertItem = function(item) {
        var convid = item.attr(this.ITEM_KEY);
        var options = this.getOptions(item);

        var editor;
        if (typeof (convid) === 'undefined') {
            this.convertedCount++;
            convid = '' + this.convertedCount;
            item.attr(this.ITEM_KEY, convid);

            var divItem = this.$('<div></div>');
            divItem.attr('id', this.DIV_KEY + convid);

            // 行高
            divItem.css('line-height', options['lineheight'] + '%');
            divItem.css('text-shadow', 'none');
            if (item.get(0).nodeName.toLowerCase() === 'pre') {
                // 宽度
                divItem.css('width', options['width']);
                divItem.css('position', 'relative');
            } else {
                divItem.css('position', 'absolute');
                divItem.css('left', -9999);
                divItem.css('top', -9999);
                divItem.css('width', 100);
                divItem.css('height', 100);
            }
            item.after(divItem);
            editor = ace.edit(divItem.get(0));

            this.convertedEditor[convid] = editor;

            var value = item.text().replace(/^(\s*?[\r\n]+)+/, '');
            value = value.replace(/\s+$/, '');
            if (options['tabtospace']) {
                value = value.replace(/\t/, '    ');
            }
            editor.getSession().setValue(value);
			
			editor.renderer.moveTextAreaToCursor = function(textarea) {
				 var pos = this.$cursorLayer.getPixelPosition();

				 if (!pos) return;

				 var bounds = this.content.getBoundingClientRect();
				 var offset = this.layerConfig.offset;

				 textarea.style.left = (bounds.left + pos.left) + "px";
				 textarea.style.top = (bounds.top + pos.top - this.scrollTop + offset) + "px";
			};

			editor.onFocus = function() {
				this.renderer.showCursor();
				this.renderer.visualizeFocus();
				this._emit("focus");
				this.renderer.moveTextAreaToCursor(this.textInput.getElement());
			};

            item.hide();
            divItem.show();
        } else {
            editor = this.convertedEditor[convid];
            divItem = this.$('#' + this.DIV_KEY + convid);
        }
        // 设置属性
        this.resetOptions(editor, options);
        if (item.get(0).nodeName.toLowerCase() === 'pre') {
            // 設置高度
            this.resizeEditor(divItem, editor, options);
        } else {
            this.setOneLineEdit(item, divItem, editor);
        }

        return editor;
    };
    /** 重新设置属性 */
    this.resetOptions = function(editor, options) {
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

    /** 重新设置高度 */
    this.resizeEditor = function(divItem, editor, options) {
        editor.renderer.updateFull(true);
        var changeHeight = function(height) {
            return height + 7;
        };

        var lineHeight = editor.renderer.layerConfig.lineHeight;
        var oldHeight = Math.ceil(editor.getSession().getScreenLength()
                * lineHeight);
        oldHeight = changeHeight(oldHeight);

        var resize = function() {
            var lineHeight = editor.renderer.layerConfig.lineHeight;
            var newHeight = Math.ceil(editor.getSession().getScreenLength()
                    * lineHeight);

            if (editor.renderer.$horizScroll) {
                newHeight += editor.renderer.scrollBarH.height;
            }
            newHeight = changeHeight(newHeight);

            if (oldHeight !== newHeight) {
                oldHeight = newHeight;
                divItem.height(newHeight);
                editor.resize();
            }
        };

        divItem.height(oldHeight);
        editor.resize();

        editor.renderer.on('scrollbarVisibilityChanged', resize);
        editor.getSession().on('changeFold', resize);
    };

    /** 重新行内代码 */
    this.setOneLineEdit = function(item, divItem, editor) {
        var pSpanItem = jQuery('<span></span>');
        pSpanItem.addClass('inline-hl');
        item.after(pSpanItem);

        var spanItem = jQuery('<span></span>');
        spanItem.appendTo(pSpanItem);
        spanItem.attr('class', divItem.attr('class'));
        var resize = function() {
            spanItem.attr('class', divItem.attr('class'));
            spanItem.html(divItem.find('.ace_scroller .ace_content .ace_text-layer .ace_line').html());
        };

        editor.renderer.on('afterRender', resize);
    };

    this.convertHtml = function(inserttag, inserttype, background, options,
            value) {
        var result = '<' + inserttag;
        if (background != '') {
            result += ' style="background-color: ' + background + ';"';
        }
        if (inserttype === 'lang') {
            for ( var key in options) {
                result += ' ' + key + '="' + options[key] + '"';
            }
        } else if (inserttype === 'data-hl') {
            result += ' data-hl="';
            for ( var key in options) {
                result += key + ':' + options[key] + ';';
            }
            result += '"';
        } else if (inserttype === 'data-hl-lang') {
            for ( var key in options) {
                result += ' data-hl-' + key + '="' + options[key] + '"';
            }
        }
        result += '>\n';
        result += this.htmlEncode(value);
        result += '\n</' + inserttag + '>';
        return result;
    };

    this.htmlEncode = function(value) {
        var div = document.createElement("div");
        div.appendChild(document.createTextNode(value));
        return div.innerHTML;
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