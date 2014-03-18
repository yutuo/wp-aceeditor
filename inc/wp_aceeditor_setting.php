<?PHP
$plugin_folder_name = basename(dirname(dirname(__FILE__)));
$current_path = get_option('siteurl') . '/wp-content/plugins/' . $plugin_folder_name;

if ($_POST[WpAceeditorConfig::CONFIG_OPTIONS_KEY]) {
    $postOptions = $_POST[WpAceeditorConfig::CONFIG_OPTIONS_KEY];

    $int_items = array ('lineheight', 'fontsize', 'tabsize', 'wrap');
    $boolean_items = array ('tabtospace', 'indent', 'gutter', 'fold', 'active');
    $array_items = array ('convtag', 'convtype');
    foreach ($postOptions as $key => $value) {
        if (in_array($key, $int_items)) {
            $postOptions[$key] = intval($value);
        } else if (in_array($key, $boolean_items)) {
            $postOptions[$key] = ($value === 'true');
        } else if (in_array($key, $array_items)) {
            $postOptions[$key] = (array) $value;
        }
    }

    $this->options = array_merge($this->options, $postOptions);
    update_option(WpAceeditorConfig::CONFIG_OPTIONS_KEY, $this->options);
}
?>

<div class="wrap">
    <h2><?PHP echo __('Wp-AceEditor', 'wp_ae') ?></h2>
    <div class="narrow">
        <form method="post">
            <p><?PHP echo __('Ace Editor is a fully functional self-contained code syntax highlighter developed in JavaScript.', 'wp_ae') ?></p>
            <h3><?PHP echo __('System Settings', 'wp_ae') ?></h3>
            <p><?PHP echo __('Please enter your system config.', 'wp_ae') ?></p>

            <table class="form-table">
                <tr>
                    <th scope="row"><?PHP echo __('Convert tags', 'wp_ae') ?></th>
                    <td>
                        <?PHP $this->checkboxsHtml("wp_ae_options[convtag]", $this->options['convtag'], WpAceeditorConfig::$TAGS)?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?PHP echo __('Convert types', 'wp_ae') ?></th>
                    <td>
                        <?PHP $this->checkboxsHtml("wp_ae_options[convtype]", $this->options['convtype'], WpAceeditorConfig::$TYPES)?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?PHP echo __('Insert tag', 'wp_ae') ?></th>
                    <td><select name="wp_ae_options[inserttag]" id="inserttag">
                        <?PHP $this->optionsHtml($this->options['inserttag'], WpAceeditorConfig::$TAGS)?>
                    </select></td>
                </tr>
                <tr>
                    <th scope="row"><?PHP echo __('Insert type', 'wp_ae') ?></th>
                    <td><select name="wp_ae_options[inserttype]" id="inserttype">
                        <?PHP $this->optionsHtml($this->options['inserttype'], WpAceeditorConfig::$TYPES)?>
                    </select></td>
                </tr>
                <tr>
                    <th scope="row"><?PHP echo __('Display width', 'wp_ae') ?></th>
                    <td>
                        <input name="wp_ae_options[width]" id="width" value="<?PHP echo $this->options['width']; ?>"/>
                    </td>
                </tr>
            </table>

            <h3><?PHP echo __('Settings', 'wp_ae') ?></h3>
            <p><?PHP echo __('Please enter your default config.', 'wp_ae') ?></p>
            <p
                style="font-family: Monaco, Menlo, Ubuntu Mono, Consolas, source-code-pro, monospace">
                &lt;pre lang="?"[ theme="?"][ fontsize="?"][
                tabsize="?"]&gt;<br />
<?PHP echo __('Your codes', 'wp_ae') ?><br /> &lt;/pre&gt;
            </p>

            <table class="form-table">
                <tr>
                    <th scope="row"><?PHP echo __('Code theme', 'wp_ae') ?>(theme)</th>
                    <td><select name="wp_ae_options[theme]" id="theme">
                            <optgroup label="Bright">
                                <?PHP $this->optionsHtml($this->options['theme'], WpAceeditorConfig::$THEMES_BRIGHT)?>
                            </optgroup>
                            <optgroup label="Dark">
                                <?PHP $this->optionsHtml($this->options['theme'], WpAceeditorConfig::$THEMES_DARK)?>
                            </optgroup>
                    </select></td>
                </tr>
                <tr>
                    <th scope="row"><?PHP echo __('Line height', 'wp_ae') ?>(lineheight)</th>
                    <td><select name="wp_ae_options[lineheight]" id="lineheight">
                            <?PHP $this->optionsHtml($this->options['lineheight'], WpAceeditorConfig::$LINE_HEIGHT)?>
                        </select>%</td>
                </tr>
                <tr>
                    <th scope="row"><?PHP echo __('Font size', 'wp_ae') ?>(fontsize)</th>
                    <td><select name="wp_ae_options[fontsize]" id="fontsize">
                            <?PHP $this->optionsHtml($this->options['fontsize'], WpAceeditorConfig::$FONT_SIZE)?>
                        </select>px</td>
                </tr>
                <tr>
                    <th scope="row"><?PHP echo __('Tab size', 'wp_ae') ?>(tabsize)</th>
                    <td><select name="wp_ae_options[tabsize]" id="tabsize">
                            <?PHP $this->optionsHtml($this->options['tabsize'], WpAceeditorConfig::$TAB_SIZE)?>
                        </select></td>
                </tr>
                <tr>
                    <th scope="row"><?PHP echo __('Tab to space', 'wp_ae') ?>(tabtospace)</th>
                    <td><select name="wp_ae_options[tabtospace]" id="tabtospace">
                            <?PHP $this->optionsHtml($this->options['tabtospace'], WpAceeditorConfig::$BOOLEAN)?>
                        </select></td>
                </tr>
                <tr>
                    <th scope="row"><?PHP echo __('Auto wrap', 'wp_ae') ?>(wrap)</th>
                    <td><select name="wp_ae_options[wrap]" id="wrap">
                            <?PHP $this->optionsHtml($this->options['wrap'], WpAceeditorConfig::$BOOLEAN)?>
                        </select><?PHP echo __('Chars', 'wp_ae')?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?PHP echo __('Print margin Column', 'wp_ae') ?>(wrap)</th>
                    <td><select name="wp_ae_options[print]" id="print">
                            <?PHP $this->optionsHtml($this->options['print'], WpAceeditorConfig::$PRINT)?>
                        </select><?PHP echo __('Chars', 'wp_ae')?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?PHP echo __('Show Indent Guides', 'wp_ae') ?>(indent)</th>
                    <td><select name="wp_ae_options[indent]" id="indent">
                            <?PHP $this->optionsHtml($this->options['indent'], WpAceeditorConfig::$BOOLEAN)?>
                        </select></td>
                </tr>
                <tr>
                    <th scope="row"><?PHP echo __('Show Gutter', 'wp_ae') ?>(gutter)</th>
                    <td><select name="wp_ae_options[gutter]" id="gutter">
                            <?PHP $this->optionsHtml($this->options['gutter'], WpAceeditorConfig::$BOOLEAN)?>
                        </select></td>
                </tr>
                <tr>
                    <th scope="row"><?PHP echo __('Folding Style', 'wp_ae') ?>(foldstyle)</th>
                    <td><select name="wp_ae_options[foldstyle]" id="foldstyle">
                            <?PHP $this->optionsHtml($this->options['foldstyle'], WpAceeditorConfig::$FOLD_STYLE)?>
                        </select></td>
                </tr>
                <tr>
                    <th scope="row"><?PHP echo __('Code fold on load', 'wp_ae') ?>(fold)</th>
                    <td><select name="wp_ae_options[fold]" id="fold">
                            <?PHP $this->optionsHtml($this->options['fold'], WpAceeditorConfig::$BOOLEAN)?>
                        </select></td>
                </tr>
                <tr>
                    <th scope="row"><?PHP echo __('Highlight Active Line', 'wp_ae') ?>(active)</th>
                    <td><select name="wp_ae_options[active]" id="active">
                            <?PHP $this->optionsHtml($this->options['active'], WpAceeditorConfig::$BOOLEAN)?>
                        </select></td>
                </tr>
            </table>
            <p class="submit">
                <input name="submit" type="submit" class="button-primary"
                    value="<?PHP echo __('Save Changes', 'wp_ae') ?>" />
            </p>
        </form>
        <h3><?PHP echo __('Preview', 'wp_ae') ?></h3>
        <p><?PHP echo __('You insert source as this.', 'wp_ae') ?></p>
        <pre id="sourcePreview" style="width: 98%"></pre>
        <p><?PHP echo __('You can input source to preview the result.', 'wp_ae') ?></p>
        <table class="form-table">
            <tr>
                <th scope="row"><?PHP echo __('Language', 'wp_ae') ?>(lang)</th>
                <td><select id="lang">
                        <?PHP $this->optionsHtml($this->options['lang'], WpAceeditorConfig::$LANGUAGES)?>
                    </select></td>
            </tr>
        </table>
        <pre id="testAceEditor" style="width: 98%">var func = function() {
    console.log("Hello, world!");
}
func();
</pre>
    </div>
</div>

<script type="text/javascript"
    src="<?PHP echo $current_path ?>/js/ace/ace.js"></script>
<script type="text/javascript"
    src="<?PHP echo $current_path ?>/js/wpaceeditor.js"></script>
<script type="text/javascript">
    var editor = null;
    var wpAceEditor = null;
    function setEditorHighLight() {
        var $ = jQuery;
        var options = {};
        options['readonly'] = false;
        options['width'] = '99%';
        if (wpAceEditor == null) {
            wpAceEditor = new WpAceEditor(options);
        }

        var htmloptions = $('select[name^=wp_ae_options]');
        for (var i = 0; i < htmloptions.length; i++) {
            var id = htmloptions.eq(i).attr('id');
            if (typeof(wpAceEditor.changeOptions[id]) !== 'undefined') {
                var convtentValue = wpAceEditor.changeOptions[id](htmloptions.eq(i).val());
                if (convtentValue !== null) {
                    options[id] = convtentValue;
                }
            }
        }

        options['lang'] = $('#lang').val();
        for (var key in options) {
            wpAceEditor.options[key] = options[key];
        }

        $('#sourcePreview').text(wpAceEditor.convertHtml($('#inserttag').val(), $('#inserttype').val(), options, "Your Source"));

        if (editor) {
            wpAceEditor.resetOptions(editor, wpAceEditor.options);
        } else {
            editor = wpAceEditor.reConvertItem($('#testAceEditor'));
        }
    }
    (function(){
        var $ = jQuery;
        $(document).ready(function(){
            $('select[name^=wp_ae_options]').change(function() {
                setEditorHighLight();
            });
            $('#lang').change(function() {
                setEditorHighLight();
            });

            setEditorHighLight();
        });
    })();
</script>