<?php
/**
Plugin Name: WP AceEditor
Plugin URI: http://yutuo.net
Description: This plugin is code syntax highlighter based on <a href="http://ace.ajax.org/">Ace Editor</a> V1.0.3. Supported languages: Bash, C++, CSS, Delphi, Java, JavaScript, Perl, PHP, Python, Ruby, SQL, VB, XML, XHTML and HTML etc.
Version: 1.0.0
Author: yutuo
Author URI: http://yutuo.net
Text Domain: wp_ytace
Domain Path: /languages
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
        load_plugin_textdomain('wp_ae', false, $plugin_dir . '/lang');
        wp_enqueue_script("jquery");
    }
    /** 在Wordpress行尾添加JavaScript */
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
    function menuLink() {
        add_options_page('Wp AceEditor Settings', __('Wp-AceEditor', 'wp_ae'), 'manage_options', 'wpAceEditor',
                        array ($this, 'optionPage'));
    }
    function actionLink($links, $file) {
        if ($file != plugin_basename(__FILE__)) {
            return $links;
        }
        $settings_link = '<a href="options-general.php?page=wpAceEditor">' . __('Settings') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
    function optionPage() {
        $current_path = dirname(__FILE__) . '/inc/wp_aceeditor_setting.php';
        include $current_path;
    }
    function optionsHtml($selectValue, $list) {
        foreach ($list as $key => $value) {
            $selected = '';
            if (is_bool($selectValue)) {
                $selectValue = $selectValue ? 'true' : 'false';
            }
            if ($key == $selectValue) {
                $selected = ' selected';
            }
            echo "<option value=\"{$key}\"{$selected}>{$value}</option>";
        }
    }
    function checkboxsHtml($name, $selectValues, $list) {
        foreach ($list as $key => $value) {
            $selected = in_array($key, $selectValues) ? ' checked' : '';
            $id = $name . '_' . $key;
            echo "<input type=\"checkbox\" name=\"{$name}[]\" id=\"{$id}\" value=\"{$key}\"{$selected} id=\"\"/><label for=\"{$id}\">{$value}</label> ";
        }
    }
    function addCodeButton() {
        $button_value = __('Add Code');
        $out_put = <<<HTML
<a href="#" id="insert-code-button" class="button add-code add_media" data-editor="content" title="{$button_value}">
    <span class="wp-media-buttons-icon"></span> {$button_value}
</a>
HTML;
        echo $out_put;
    }

}

$aceeditor = new WpAceeditor();
// 启用
register_activation_hook(__FILE__, array ($aceeditor, 'activate'));
// 停用
register_deactivation_hook(__FILE__, array ($aceeditor, 'deActivate'));
// 初始化
add_action('init', array ($aceeditor, 'init'));
// 在Wordpress行尾添加JavaScript
add_action('wp_footer', array ($aceeditor, 'insertFootJs'));
// 管理页面
add_action('admin_menu', array ($aceeditor, 'menuLink'));
// 插件链接
add_action('plugin_action_links', array ($aceeditor, 'actionLink'), 10, 2);
// 提交画面添加按钮
add_action('media_buttons', array ($aceeditor, 'addCodeButton'), 11);

// 替换代码里的HTML
// add_filter('the_content',  'wp_ae_html_encode_code');


// function wp_ae_html_encode_code($content) {
//     $result = preg_replace_callback('/(<pre[^<>]+lang=[^<>]+>)(.+?)(<\/pre>)/si', function ($matches) {
//         $value = str_replace('&#038;', '&', $matches[2]);
//         $value = str_replace('&#062;', '>', $matches[2]);
//         return $matches[1] . htmlspecialchars($value) . $matches[3];
//     }, $content);
//     return $result;
// }

function wp_ae_admin_footer() {
    include (dirname(__FILE__) . '/inc/wp_aceeditor_addcode.php');
}


add_action('admin_footer', 'wp_ae_admin_footer' );


//

//