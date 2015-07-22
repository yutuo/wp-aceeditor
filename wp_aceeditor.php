<?php
/**
Plugin Name: WP AceEditor
Plugin URI: http://yutuo.net/archives/f685d2dbbb176e86.html
Description: This plugin is code syntax highlighter based on <a href="http://ace.ajax.org/">Ace Editor</a> V1.0.3. Supported languages: Bash, C++, CSS, Delphi, Java, JavaScript, Perl, PHP, Python, Ruby, SQL, VB, XML, XHTML and HTML etc.
Version: 1.0.1
Author: yutuo
Author URI: http://yutuo.net
Text Domain: wp_aceeditor
Domain Path: /wp_aceeditor
License: GPL v3 - http://www.gnu.org/licenses/gpl.html
*/
require_once (dirname(__FILE__) . '/inc/wp_aceeditor_config.php');
class WpAceeditor {
    /** 本Plugin文件夹实际目录 */
    var $pluginDir;
    /** 本Plugin的URL路径 */
    var $currentUrl;
    /** 设置 */
    var $options;
    var $options_json;

    /** 构造函数 */
    function __construct() {
        $this->pluginDir = dirname(plugin_basename(__FILE__));
        $this->currentUrl = get_option('siteurl') . '/wp-content/plugins/' . basename(dirname(__FILE__));
        $this->options = get_option(WpAceeditorConfig::CONFIG_OPTIONS_KEY);
        $this->options_json = json_encode($this->options);
    }
    /** 启用 */
    function activate() {
        update_option(WpAceeditorConfig::CONFIG_OPTIONS_KEY, WpAceeditorConfig::$DEFAULT_OPTION);
    }
    /** 停用 */
    function deActivate() {
        delete_option(WpAceeditorConfig::CONFIG_OPTIONS_KEY);
    }
    /** 初始化 */
    function init() {
        load_plugin_textdomain('wp_ae', false, $this->pluginDir . '/lang');
        wp_enqueue_script("jquery");
    }
    /** 在Wordpress头部添加CSS */
    function insertHeadCss() {
        $html = <<<HTML
<style type="text/css">
.ace_editor .ace_gutter,
.ace_editor .ace_scroller {padding-top: 3px;}
.ace_editor {margin: -9px 0 10px 0;}
.inline-hl .ace_editor {margin: 0px 2px; padding: 1px 3px 1px 4px;}
</style>
HTML;
        echo $html;
    }
    /** 在Wordpress尾部添加JavaScript */
    function insertFootJs() {
        $html = <<<HTML
<script type="text/javascript" src="{$this->currentUrl}/js/ace/ace.js"></script>
<script type="text/javascript" src="{$this->currentUrl}/js/wpaceeditor.js"></script>
<script type="text/javascript">
(function(){
    var options = {$this->options_json};
    jQuery(document).ready(function(){
        convertToAceEditor(options);
    });
})();
</script>
HTML;
        echo $html;
    }
    /** 在设置菜单添加链接 */
    function menuLink() {
        add_options_page('Wp AceEditor Settings', __('Wp-AceEditor', 'wp_ae'), 'manage_options', 'wpAceEditor',
                        array ($this, 'optionPage'));
    }
    /** 插件设置链接 */
    function actionLink($links, $file) {
        if ($file != plugin_basename(__FILE__)) {
            return $links;
        }
        $settings_link = '<a href="options-general.php?page=wpAceEditor">' . __('Settings') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
    /** 插件设置页面 */
    function optionPage() {
        $current_path = dirname(__FILE__) . '/inc/wp_aceeditor_setting.php';
        include $current_path;
    }
    /** 在添加媒体后添加一个按钮 */
    function addCodeButton() {
        $button_value = __('Add Code', 'wp_ae');
        $out_put = <<<HTML
<a href="#" id="insert-code-button" class="button add-code" data-editor="content" title="{$button_value}">
    <span class="wp-media-buttons-icon" style="background: url({$this->currentUrl}/images/icon.png) no-repeat top left;"></span>
    {$button_value}
</a>
HTML;
        echo $out_put;
    }
    /** 添加按钮的处理方法 */
    function addAdminFooter() {
        global $pagenow;
        if ($pagenow == 'post.php' || $pagenow == 'post-new.php') {
            include (dirname(__FILE__) . '/inc/wp_aceeditor_addcode.php');
        }
    }
    /** Select的Option生成 */
    function optionsHtml($selectValue, $list, $default=false) {
        $default_txt = __('Default', 'wp_ae');
        if ($default) {
            echo "<option value=\"\" selected>{$default_txt}</option>";
        }
        foreach ($list as $key => $value) {
            $selected = '';
            if (is_bool($selectValue)) {
                $selectValue = $selectValue ? 'true' : 'false';
            }
            if (!$default && $key == $selectValue) {
                $selected = ' selected';
            }
            echo "<option value=\"{$key}\"{$selected}>{$value}</option>";
        }
    }
    /** Checkbox的生成 */
    function checkboxsHtml($name, $selectValues, $list) {
        foreach ($list as $key => $value) {
            $selected = in_array($key, $selectValues) ? ' checked' : '';
            $id = $name . '_' . $key;
            echo "<input type=\"checkbox\" name=\"{$name}[]\" id=\"{$id}\" value=\"{$key}\"{$selected} id=\"\"/><label for=\"{$id}\">{$value}</label> ";
        }
    }
}

$aceeditor = new WpAceeditor();
// 启用
register_activation_hook(__FILE__, array ($aceeditor, 'activate'));
// 停用
register_deactivation_hook(__FILE__, array ($aceeditor, 'deActivate'));
// 初始化
add_action('init', array ($aceeditor, 'init'));
// 在Wordpress头部添加CSS
add_action('wp_head', array ($aceeditor, 'insertHeadCss'));
// 在Wordpress尾部添加JavaScript
add_action('wp_footer', array ($aceeditor, 'insertFootJs'));
// 管理页面
add_action('admin_menu', array ($aceeditor, 'menuLink'));
// 插件链接
add_action('plugin_action_links', array ($aceeditor, 'actionLink'), 10, 2);
// 提交画面添加按钮
add_action('media_buttons', array ($aceeditor, 'addCodeButton'), 11);
// 提交画面添加按钮
add_action('admin_footer', array ($aceeditor, 'addAdminFooter') );