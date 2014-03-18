var WpAceEditor = function(options) {
    this.ITEM_KEY = 'data-wpae-id';
    this.DIV_KEY = 'WpAceEditor_';

    this.$ = jQuery;
    this.options = options;
    this.convertedEditor = [];
    this.delayFunctions = [];
    this.convertedCount = 0;

    /** 取得属性 */
    this.getOptions = function(item) {
        var result = [];
        for (var key in options) {
            result[key] = options[key];
        }

        var htmlOptions = this.getHtmlOptions(item);
        for (var key in htmlOptions) {
            if (typeof(this.changeOptions[key]) !== 'undefined') {
                var convtentValue = this.changeOptions[key](htmlOptions[key]);
                if (convtentValue !== null) {
                    result[key] = convtentValue;
                }
            }
        }
        return result;
    }
    /** 取得HTML设置的属性 */
    this.getHtmlOptions = function(item) {
        var getAllAttr = function (htmlItem) {
            var result = [];
            for (var index in htmlItem.attributes) {
                var attr = htmlItem.attributes[index];
                result[attr.name] = attr.value;
            }
            return result;
        }

        var result = [];
        var allAttr = getAllAttr(item[0]);
        if (typeof(allAttr['data-wpae']) !== 'undefined') {
            var attrstrs = allAttr['data-wpae'].split(';');
            for (var i in attrstrs) {
                var attrs = attrstrs[i].split(':');
                if (attrs.length >= 2) {
                    result[$.trim(attrs[0])] = $.trim(attrs[1]);
                }
            }
        } else if (typeof(allAttr['data-wpae-lang']) !== 'undefined') {
            var startLenght = 'data-wpae-'.length;
            for (var key in allAttr) {
                if (key.length > startLenght) {
                    result[key.substring(startLenght)] = allAttr[key];
                }
            }
        } else {
            result = allAttr;
        }

        return result;
    }
    /** 转换所有项目 */
    this.convertAll = function() {
        var items = this.matchAllItems();

        for (i = 0; i < items.length; i++) {
            var item = items.eq(i);
            if (typeof(item.attr(this.ITEM_KEY)) !== 'undefined') {
                return;
            }
            this.reConvertItem(item);
        }

        var delayFuns = this.delayFunctions;
        function delay() {
            for (var key in delayFuns) {
                delayFuns[key]();
            }
        }

        window.setTimeout(delay, 30);
    }
    /** 取得所有要转换的项目 */
    this.matchAllItems = function() {
        var matchString = '';
        for (var tagi in this.options['convtag']) {
            for (var typei in this.options['convtype']) {
                if (matchString.length > 0) {
                    matchString += ',';
                }
                matchString += this.options['convtag'][tagi];
                matchString += '[' + this.options['convtype'][typei] + ']';
            }
        }
        return this.$(matchString);
    }
    /** 重新转换 */
    this.reConvertItem = function(item) {
        var convid = item.attr(this.ITEM_KEY);
        var options = this.getOptions(item);

        var editor;
        var divItem;
        if (typeof(convid) === 'undefined') {
            this.convertedCount++;
            convid = '' + this.convertedCount;
            item.attr(this.ITEM_KEY, convid);

            var divItem = $('<div></div>');
            divItem.attr('id', this.DIV_KEY + convid);
            // 宽度
            divItem.css('width', options['width']);
            // 行高
            divItem.css('line-height', options['lineheight'] + '%');
            divItem.css('position', 'relative');
            divItem.css('text-shadow', 'none');
            item.after(divItem);

            editor = ace.edit(divItem.get(0));
            editor.getSession().setUseWrapMode(false);
            this.convertedEditor[convid] = editor;

            var value = item.text();
            var value = item.text().replace(/^(\s*?[\r\n]+)+/, '');
            value = value.replace(/\s+$/, '')
            if (options['tabtospace']) {
                value = value.replace(/\t/, '    ');
            }
            editor.getSession().setValue(value);

            item.hide();
            divItem.show();
        } else {
            editor = this.convertedEditor[convid];
            divItem = $('#' + this.DIV_KEY + convid);
        }
        // 设置属性
        this.resetOptions(editor, options);
        // 設置高度
        this.delayFunctions[convid] = this.resizeEditor(divItem, editor, options);

        return editor;
    }
    /** 重新设置属性 */
    this.resetOptions = function(editor, options) {
        // 代码只读
        editor.setReadOnly(options['readonly']);
        // 显示样式
        editor.setTheme('ace/theme/' + options['theme']);
        // 显示语言
        editor.getSession().setMode('ace/mode/' + options['lang']);
        // Tab宽度
        editor.getSession().setTabSize(options['tabsise']);
        // 文字大小
        editor.setFontSize(options['fontsize']);
        // 打印边界大小
        if (options['wrap'] > 0) {
            editor.renderer.setPrintMarginColumn(options['wrap']);
        }
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
    }

    /** 重新设置高度 */
    this.resizeEditor = function  (divItem, editor, options) {
        var changeHeight = function(height) {
            if (typeof(this.$.browser.mozilla) !== 'undefined') {
                return height + 1;
            } else {
                return height;
            }
        }

        var lineHeight = Math.round(options['fontsize'] * options['lineheight'] / 100);
        var scrollBarWidth = editor.renderer.scrollBar.getWidth();
        var oldHeight = editor.getSession().getScreenLength() * lineHeight;
        oldHeight = changeHeight(oldHeight);


        var resize = function() {
            var newHeight = editor.getSession().getScreenLength() * lineHeight;
            if (editor.renderer.$horizScroll) {
                newHeight += scrollBarWidth;
            }
            newHeight = changeHeight(newHeight);
            if (oldHeight !== newHeight) {
                oldHeight = newHeight;
                divItem.height(newHeight);
                editor.resize();
            }
        }


        var resizedelay = function() {
            window.setTimeout(resize, 30);
        }

        divItem.height(oldHeight);
        editor.resize();

        editor.getSession().on('change', resizedelay);
        editor.getSession().on('changeFold', resizedelay);

        return resize;
    }


    this.changeOptions = new function() {
        this.__int = function (value) {
            var returnVal = parseInt(value);
            return isNaN(returnVal) ? null : returnVal;
        };
        this.__boolean = function (value) {
            value = value.toLowerCase();
            return ['1', 'yes', 'true'].indexOf(value) >= 0;
        };
        this.__lowercase = function (value) {
            return value.toLowerCase();
        };
        this.__value = function (value) {
            return value;
        };
        // 代码只读
        this.readonly = this.__boolean;
        // 显示样式
        this.theme = this.__lowercase;
        // 显示语言
        this.lang = function (value) {
            value = value.toLowerCase();
            if (value == 'c' || value == 'cpp') {
                value = 'c_cpp';
            } else if (value == 'dos') {
                value = 'batchfile'
            }
            return value;
        };
        // Tab宽度
        this.tabsize = this.__int;
        // 行高
        this.lineheight = this.__int;
        // 文字大小
        this.fontsize = this.__int;
        // 打印边界大小
        this.wrap = this.__int;
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
        this.foldstyle = function (value) {
            value = value.toLowerCase();
            var valueList = ['none', 'manual', 'begin', 'markbegin', 'beginend', 'markbeginend'];
            var index = valueList.indexOf(value);
            if (index < 0) {
                return null;
            } else {
                return valueList[Math.floor(index / 2) * 2 + 1];
            }
        }
    }
}


function convertToAceEditor(options) {
    var aceEditor = new WpAceEditor(options);
    aceEditor.convertAll();
}