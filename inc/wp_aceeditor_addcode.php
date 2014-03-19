<style type="text/css">
#__wp-ae-add-code-div .form-table td {padding: 0}
</style>
<div tabindex="0" id="__wp-ae-add-code-div" class="supports-drag-drop" style="display: none;">
    <div class="media-modal wp-core-ui">
        <a id="__wp-ae-add-code-close" class="media-modal-close" href="#" title="Close"><span
            class="media-modal-icon"></span></a>
        <div class="media-modal-content">
            <div class="media-frame wp-core-ui">
                <div class="media-frame-menu" style="width: 299px;">
                    <div class="media-menu">
                        <a href="#" class="media-menu-item active"><?php echo __('Setting', 'wp_ae')?></a>
                        <div class="separator"></div>
                        <table class="form-table" style="width: 280px;">
                            <tr>
                                <th scope="row"><?php echo __('Language', 'wp_ae') ?></th>
                                <td><select name="wp_ae_options[lang]" id="lang">
                                         <?php $this->optionsHtml($this->options['lang'], WpAceeditorConfig::$LANGUAGES)?>
                                </select></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div id="insertedLang"></div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Code theme', 'wp_ae') ?></th>
                                <td><select name="wp_ae_options[theme]" id="theme">
                                        <option value="" selected><?php echo __('Default', 'wp_ae') ?></option>
                                        <optgroup label="Bright">
                                            <?php $this->optionsHtml('', WpAceeditorConfig::$THEMES_BRIGHT)?>
                                        </optgroup>
                                        <optgroup label="Dark">
                                            <?php $this->optionsHtml('', WpAceeditorConfig::$THEMES_DARK)?>
                                        </optgroup>
                                </select></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Line height', 'wp_ae') ?></th>
                                <td><select name="wp_ae_options[lineheight]" id="lineheight">
                                        <?php $this->optionsHtml($this->options['lineheight'], WpAceeditorConfig::$LINE_HEIGHT, true)?>
                                    </select>%</td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Font size', 'wp_ae') ?></th>
                                <td><select name="wp_ae_options[fontsize]" id="fontsize">
                                        <?php $this->optionsHtml($this->options['fontsize'], WpAceeditorConfig::$FONT_SIZE, true)?>
                                    </select>px</td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Tab size', 'wp_ae') ?></th>
                                <td><select name="wp_ae_options[tabsize]" id="tabsize">
                                        <?php $this->optionsHtml($this->options['tabsize'], WpAceeditorConfig::$TAB_SIZE, true)?>
                                    </select></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Tab to space', 'wp_ae') ?></th>
                                <td><select name="wp_ae_options[tabtospace]" id="tabtospace">
                                        <?php $this->optionsHtml($this->options['tabtospace'], WpAceeditorConfig::$BOOLEAN, true)?>
                                    </select></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Auto wrap', 'wp_ae') ?></th>
                                <td><select name="wp_ae_options[wrap]" id="wrap">
                                        <?php $this->optionsHtml($this->options['wrap'], WpAceeditorConfig::$BOOLEAN, true)?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Print margin Column', 'wp_ae') ?></th>
                                <td><select name="wp_ae_options[print]" id="print">
                                        <?php $this->optionsHtml($this->options['print'], WpAceeditorConfig::$PRINT, true)?>
                                    </select><?php echo __('Chars', 'wp_ae')?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Show Indent Guides', 'wp_ae') ?></th>
                                <td><select name="wp_ae_options[indent]" id="indent">
                                        <?php $this->optionsHtml($this->options['indent'], WpAceeditorConfig::$BOOLEAN, true)?>
                                    </select></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Show Gutter', 'wp_ae') ?></th>
                                <td><select name="wp_ae_options[gutter]" id="gutter">
                                        <?php $this->optionsHtml($this->options['gutter'], WpAceeditorConfig::$BOOLEAN, true)?>
                                    </select></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Folding Style', 'wp_ae') ?></th>
                                <td><select name="wp_ae_options[foldstyle]" id="foldstyle">
                                        <?php $this->optionsHtml($this->options['foldstyle'], WpAceeditorConfig::$FOLD_STYLE, true)?>
                                    </select></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Code fold on load', 'wp_ae') ?></th>
                                <td><select name="wp_ae_options[fold]" id="fold">
                                        <?php $this->optionsHtml($this->options['fold'], WpAceeditorConfig::$BOOLEAN, true)?>
                                    </select></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Highlight Active Line', 'wp_ae') ?></th>
                                <td><select name="wp_ae_options[active]" id="active">
                                        <?php $this->optionsHtml($this->options['active'], WpAceeditorConfig::$BOOLEAN, true)?>
                                    </select></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="media-frame-title" style="left: 300px;">
                    <h1><?php echo __('Insert Code', 'wp_ae')?></h1>
                </div>
                <div class="media-frame-router" style="left: 300px; height: 0px"></div>
                <div class="media-frame-content" style="left: 300px; top: 30px;">
                    <div id="insertCodePre"
                        style="top: 30px; position: absolute; bottom: 10px; right: 5px; left: 10px;"></div>
                </div>
                <div class="media-frame-toolbar" style="left: 300px;">
                    <div class="media-toolbar">
                        <div class="media-toolbar-primary">
                            <a href="#" id="__wp-ae-insert-code"
                                class="button media-button button-primary button-large media-button-insert"
                                disabled="disabled"><?php echo __('Insert into post', 'wp_ae')?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="media-modal-backdrop"></div>
</div>
<script type="text/javascript"
    src="<?php echo $this->currentUrl ?>/js/ace/ace.js"></script>
<script type="text/javascript"
    src="<?php echo $this->currentUrl ?>/js/wpaceeditor.js"></script>
<script type="text/javascript">
    var options = <?php echo $this->options_json ?>;
    wpAceEditor = new WpAceEditor(options);

    var editor = null;

    function getHtmlOptions() {
        var options = {};
        var htmloptions = jQuery('select[name^=wp_ae_options]');
        for (var i = 0; i < htmloptions.length; i++) {
            var id = htmloptions.eq(i).attr('id');
            if (htmloptions.eq(i).val() === '') {
                continue;
            }
            if (typeof(wpAceEditor.changeOptions[id]) !== 'undefined') {
                var convtentValue = wpAceEditor.changeOptions[id](htmloptions.eq(i).val());
                if (convtentValue !== null) {
                    options[id] = convtentValue;
                }
            }
        }
        return options;
    }

    function setEditorHighLight() {
        var options = wpAceEditor.getOptions(null);
        options['readonly'] = false;
        var htmloptions = getHtmlOptions();
        for (var key in htmloptions) {
            options[key] = htmloptions[key];
        }
        if (!editor) {
            editor = ace.edit('insertCodePre');
        }
        wpAceEditor.resetOptions(editor, options);
    }

    (function(){
        var $ = jQuery;
        $(document).ready(function(){
            $('#insert-code-button').click(function() {
                $('#__wp-ae-add-code-div').show();
            });
            $('#__wp-ae-add-code-close').click(function() {
                $('#__wp-ae-add-code-div').hide();
            });
            $('#__wp-ae-insert-code').click(function() {
                var value = editor.session.getValue();
                var html = wpAceEditor.convertHtml(options['inserttag'], options['inserttype'], options['background'], getHtmlOptions(), value);
                send_to_editor(html);
                $('#__wp-ae-add-code-div').hide();
                return false;
            });

            $('select[name^=wp_ae_options]').change(function() {
               setEditorHighLight();
            });
            setEditorHighLight();
        });
    })();
</script>