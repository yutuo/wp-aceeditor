<?PHP
$plugin_folder_name = basename(dirname(dirname(__FILE__)));
$current_path = get_option('siteurl') . '/wp-content/plugins/' . $plugin_folder_name;
?>
<div tabindex="0" id="__wp-ae-add-code-div" class="supports-drag-drop"
    style="display: none;">
    <div class="media-modal wp-core-ui">
        <a id="__wp-ae-add-code-close" class="media-modal-close" href="#"
            title="Close"><span class="media-modal-icon"></span></a>
        <div class="media-modal-content">
            <div class="media-frame wp-core-ui" id="__wp-ae-add-code-div-0">
                <div class="media-frame-title" style="left: 0px">
                    <h1>Insert Code</h1>
                </div>
                <div class="media-frame-router" style="left: 0px; height: 0px"></div>
                <div class="media-frame-content" style="left: 0px; top: 50px;">
                    <div style="position: absolute;">
                    <?PHP echo __('Language', 'wp_ae')?>
                    <select id="lang">
                        <?PHP options('', $wp_ae_lang)?>
                    </select>

                    <?PHP echo __('Code theme', 'wp_ae')?>
                    <select name="wp_ae_options[theme]" id="theme">
                            <optgroup label="Bright">
                            <?PHP options($options['theme'], $wp_ae_themes_bright)?>
                        </optgroup>
                            <optgroup label="Dark">
                            <?PHP options($options['theme'], $wp_ae_themes_dark)?>
                        </optgroup>
                        </select>
                    </div>
                    <div id="insertCodePre"
                        style="top: 30px; position: absolute; bottom: 10px; right: 5px; left: 10px;"></div>
                </div>
                <div class="media-frame-toolbar" style="left: 0px">
                    <div class="media-toolbar">
                        <div class="media-toolbar-primary">
                            <a href="#" id="__wp-ae-insert-code"
                                class="button media-button button-primary button-large media-button-insert">Insert
                                into post</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="media-modal-backdrop"></div>
</div>

<script type="text/javascript" src="<?PHP echo $current_path ?>/js/ace/ace.js"></script>
<script type="text/javascript" src="<?PHP echo $current_path ?>/js/wpaceeditor.js"></script>
<script type="text/javascript">
    var options = <?PHP echo $options_json ?>;
    for (var id in options) {
        options[id] = changeOptions[id](options[id], options[id]);
    }

    var editor = null;
    function setEditorHighLight() {
        var $ = jQuery;
        options['readOnly'] = false;
        var htmloptions = $('select[name^=wp_ae_options]');
        for (var i = 0; i < htmloptions.length; i++) {
            var id = htmloptions.eq(i).attr('id');
            options[id] = changeOptions[id](htmloptions.eq(i).val(), options[id]);
        }
        options['lang'] = $('#lang').val();
        if (!editor) {
            editor = ace.edit('insertCodePre');
        }
        setEditorOptions(editor, options);
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
                value = value.replace('</pre>', '</pre&#062;');
                var out = '<pre lang="' + $('#lang').val() +'">';
                out += value;
                out += '</pre>';
                send_to_editor(out);
                $('#__wp-ae-add-code-div').hide();
                return false;
            });


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