<?php
class WpAceeditorConfig {
    /** 保存的KEY */
    const CONFIG_OPTIONS_KEY = 'wp_ae_options';
    /** 语言HTML定义 */
    static $LANGUAGES = array (
            'abap' => 'ABAP',
            'actionscript' => 'ActionScript',
            'ada' => 'ADA',
            'apache_conf' => 'Apache Conf',
            'asciidoc' => 'AsciiDoc',
            'assembly_x86' => 'Assembly x86',
            'autohotkey' => 'AutoHotKey',
            'batchfile' => 'BatchFile',
            'c9search' => 'C9Search',
            'c_cpp' => 'C/C++',
            'cirru' => 'Cirru',
            'clojure' => 'Clojure',
            'cobol' => 'Cobol',
            'coffee' => 'CoffeeScript',
            'coldfusion' => 'ColdFusion',
            'csharp' => 'C#',
            'css' => 'CSS',
            'curly' => 'Curly',
            'd' => 'D',
            'dart' => 'Dart',
            'diff' => 'Diff',
            'dot' => 'Dot',
            'erlang' => 'Erlang',
            'ejs' => 'EJS',
            'forth' => 'Forth',
            'ftl' => 'FreeMarker',
            'gherkin' => 'Gherkin',
            'glsl' => 'Glsl',
            'golang' => 'Go',
            'groovy' => 'Groovy',
            'haml' => 'HAML',
            'handlebars' => 'Handlebars',
            'haskell' => 'Haskell',
            'haxe' => 'haXe',
            'html' => 'HTML',
            'html_ruby' => 'HTML (Ruby)',
            'ini' => 'INI',
            'jack' => 'Jack',
            'jade' => 'Jade',
            'java' => 'Java',
            'javascript' => 'JavaScript',
            'json' => 'JSON',
            'jsoniq' => 'JSONiq',
            'jsp' => 'JSP',
            'jsx' => 'JSX',
            'julia' => 'Julia',
            'latex' => 'LaTeX',
            'less' => 'LESS',
            'liquid' => 'Liquid',
            'lisp' => 'Lisp',
            'livescript' => 'LiveScript',
            'logiql' => 'LogiQL',
            'lsl' => 'LSL',
            'lua' => 'Lua',
            'luapage' => 'LuaPage',
            'lucene' => 'Lucene',
            'makefile' => 'Makefile',
            'matlab' => 'MATLAB',
            'markdown' => 'Markdown',
            'mel' => 'MEL',
            'mysql' => 'MySQL',
            'mushcode' => 'MUSHCode',
            'nix' => 'Nix',
            'objectivec' => 'Objective-C',
            'ocaml' => 'OCaml',
            'pascal' => 'Pascal',
            'perl' => 'Perl',
            'pgsql' => 'pgSQL',
            'php' => 'PHP',
            'powershell' => 'Powershell',
            'prolog' => 'Prolog',
            'properties' => 'Properties',
            'protobuf' => 'Protobuf',
            'python' => 'Python',
            'r' => 'R',
            'rdoc' => 'RDoc',
            'rhtml' => 'RHTML',
            'ruby' => 'Ruby',
            'rust' => 'Rust',
            'sass' => 'SASS',
            'scad' => 'SCAD',
            'scala' => 'Scala',
            'smarty' => 'Smarty',
            'scheme' => 'Scheme',
            'scss' => 'SCSS',
            'sh' => 'SH',
            'sjs' => 'SJS',
            'space' => 'Space',
            'snippets' => 'snippets',
            'soy_template' => 'Soy Template',
            'sql' => 'SQL',
            'stylus' => 'Stylus',
            'svg' => 'SVG',
            'tcl' => 'Tcl',
            'tex' => 'Tex',
            'text' => 'Text',
            'textile' => 'Textile',
            'toml' => 'Toml',
            'twig' => 'Twig',
            'typescript' => 'Typescript',
            'vbscript' => 'VBScript',
            'velocity' => 'Velocity',
            'verilog' => 'Verilog',
            'xml' => 'XML',
            'xquery' => 'XQuery',
            'yaml' => 'YAML',
    );
    /** 显示样式HTML定义 */
    static $THEMES_BRIGHT = array (
            'chrome' => 'Chrome',
            'clouds' => 'Clouds',
            'crimson_editor' => 'Crimson Editor',
            'dawn' => 'Dawn',
            'dreamweaver' => 'Dreamweaver',
            'eclipse' => 'Eclipse',
            'github' => 'GitHub',
            'solarized_light' => 'Solarized Light',
            'textmate' => 'TextMate',
            'tomorrow' => 'Tomorrow',
            'xcode' => 'XCode',
            'kuroir' => 'Kuroir',
            'katzenmilch' => 'KatzenMilch',
    );
    static $THEMES_DARK = array (
            'ambiance' => 'Ambiance',
            'chaos' => 'Chaos',
            'clouds_midnight' => 'Clouds Midnight',
            'cobalt' => 'Cobalt',
            'idle_fingers' => 'idle Fingers',
            'kr_theme' => 'krTheme',
            'merbivore' => 'Merbivore',
            'merbivore_soft' => 'Merbivore Soft',
            'mono_industrial' => 'Mono Industrial',
            'monokai' => 'Monokai',
            'pastel_on_dark' => 'Pastel on dark',
            'solarized_dark' => 'Solarized Dark',
            'terminal' => 'Terminal',
            'tomorrow_night' => 'Tomorrow Night',
            'tomorrow_night_blue' => 'Tomorrow Night Blue',
            'tomorrow_night_bright' => 'Tomorrow Night Bright',
            'tomorrow_night_eighties' => 'Tomorrow Night 80s',
            'twilight' => 'Twilight',
            'vibrant_ink' => 'Vibrant Ink',
    );
    /** 默认设置 */
    static $DEFAULT_OPTION = array (
            'convtag' => array('pre', 'code'),                              // 要转换的Tag
            'convtype' => array('lang', 'data-wpae', 'data-wpae-lang'),     // 要转换Tag的必要属性
            'inserttag' => 'pre',                                           // 自动插入的Tag
            'inserttype' => 'data-wpae',                                    // 自动插入Tag的属性
            'background' => '#ddd',                                         // 播入代码的背景
            'maxsavecnt' => 10,                                             // 语言最大保存

            'readonly' => true,             // 代码只读
            'theme' => 'monokai',           // 显示样式
            'lang' => 'text',               // 显示语言
            'tabsize' => 4,                 // Tab宽度
            'lineheight' => 120,            // 行高 %
            'fontsize' => 12,               // 文字大小
            'wrap' => false,                // 自动换行
            'print' => 80,                  // 打印边界大小
            'width' => '99%',               // 显示宽度
            'tabtospace' => true,           // Tab转换成空格显示
            'fold' => false,                // 默认收缩
            'indent' => true,               // 缩进边界显示
            'gutter' => true,               // 显示行号
            'active' => true,               // 活动行高亮显示
            'foldstyle' => 'markbegin',     // 代码收缩样式
    );
    /** 字体数组 */
    static $FONT_SIZE = array (
            '8' => '8',
            '9' => '9',
            '10' => '10',
            '12' => '12',
            '14' => '14',
            '16' => '16',
            '16' => '16',
            '16' => '16'
    );
    /** Yes或者No */
    static $BOOLEAN = array (
            'true' => 'Yes',
            'false' => 'No'
    );
    /** Tab宽度 */
    static $TAB_SIZE = array (
            '2' => '2',
            '4' => '4',
            '6' => '6',
            '8' => '8'
    );


    static $PRINT = array (
            '-1' => 'No display',
            '80' => '80',
            '100' => '100',
            '120' => '120',
            '160' => '160'
    );

    static $LINE_HEIGHT = array (
            '80' => '80',
            '90' => '90',
            '100' => '100',
            '110' => '110',
            '120' => '120',
            '130' => '130',
            '140' => '140',
            '150' => '150',
            '160' => '160'
    );

    static $FOLD_STYLE = array (
            'none' => 'none',
            'markbegin' => 'begin',
            'beginend' => 'begin And End'
    );

    static $TAGS = array (
            'pre' => 'pre',
            'code' => 'code',
    );

    static $TYPES = array (
            'lang' => '&lt;tag lang="????" ... &gt;',
            'data-wpae' => '&lt;tag data-wpae="lang:????;..."&gt;',
            'data-wpae-lang' => '&lt;tag data-wpae-lang="????" ... &gt;',
    );
}