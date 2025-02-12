<?php
/*
    

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

require_once('CF7DBPlugin.php');
require_once('CFDBView.php');

class CFDBViewShortCodeBuilder extends CFDBView {

    /**
     * @param  $plugin CF7DBPlugin
     * @return void
     */
    function display(&$plugin) {
        if ($plugin == null) {
            $plugin = new CF7DBPlugin;
        }
        $this->pageHeader($plugin);

        // Identify which forms have data in the database
        global $wpdb;
        $tableName = $plugin->getSubmitsTableName();
        $rows = $wpdb->get_results("select distinct `form_name` from `$tableName` order by `form_name`");
        //        if ($rows == null || count($rows) == 0) {
        //            _e('No form submissions in the database', 'contact-form-7-to-database-extension');
        //            return;
        //        }
        $currSelection = '';
        $infoImg = $plugin->getPluginFileUrl('/img/info.jpg');
        ?>
    <script type="text/javascript" language="JavaScript">

        var shortCodeDocUrls = {
            '' : 'http://cfdbplugin.com/?page_id=89',
            '[cfdb-html]' : 'http://cfdbplugin.com/?page_id=284',
            '[cfdb-table]' : 'http://cfdbplugin.com/?page_id=93',
            '[cfdb-datatable]' : 'http://cfdbplugin.com/?page_id=91',
            '[cfdb-value]' : 'http://cfdbplugin.com/?page_id=98',
            '[cfdb-count]' : 'http://cfdbplugin.com/?page_id=278',
            '[cfdb-json]' : 'http://cfdbplugin.com/?page_id=96',
            '[cfdb-export-link]' : 'http://cfdbplugin.com/?page_id=419'
        };

        function showHideOptionDivs() {
            var shortcode = jQuery('#shortcode_ctrl').val();
            jQuery('#doc_url_tag').attr('href', shortCodeDocUrls[shortcode]);
            jQuery('#doc_url_tag').html(shortcode + " <?php _e('Documentation', 'contact-form-7-to-database-extension'); ?>");
            switch (shortcode) {
                case "[cfdb-html]":
                    jQuery('#show_hide_div').show();
                    jQuery('#limitorder_div').show();
                    jQuery('#html_format_div').hide();
                    jQuery('#dt_options_div').hide();
                    jQuery('#json_div').hide();
                    jQuery('#value_div').hide();
                    jQuery('#template_div').show();
                    jQuery('#url_link_div').hide();
                    break;
                case "[cfdb-table]":
                    jQuery('#show_hide_div').show();
                    jQuery('#limitorder_div').show();
                    jQuery('#html_format_div').show();
                    jQuery('#dt_options_div').hide();
                    jQuery('#json_div').hide();
                    jQuery('#value_div').hide();
                    jQuery('#template_div').hide();
                    jQuery('#url_link_div').hide();
                    break;
                case "[cfdb-datatable]":
                    jQuery('#show_hide_div').show();
                    jQuery('#limitorder_div').show();
                    jQuery('#html_format_div').show();
                    jQuery('#dt_options_div').show();
                    jQuery('#json_div').hide();
                    jQuery('#value_div').hide();
                    jQuery('#template_div').hide();
                    jQuery('#url_link_div').hide();
                    break;
                case "[cfdb-value]":
                    jQuery('#show_hide_div').show();
                    jQuery('#limitorder_div').show();
                    jQuery('#html_format_div').hide();
                    jQuery('#dt_options_div').hide();
                    jQuery('#json_div').hide();
                    jQuery('#value_div').show();
                    jQuery('#template_div').hide();
                    jQuery('#url_link_div').hide();
                    break;
                case "[cfdb-count]":
                    jQuery('#show_hide_div').hide();
                    jQuery('#limitorder_div').hide();
                    jQuery('#html_format_div').hide();
                    jQuery('#dt_options_div').hide();
                    jQuery('#json_div').hide();
                    jQuery('#value_div').hide();
                    jQuery('#template_div').hide();
                    jQuery('#url_link_div').hide();
                    break;
                case "[cfdb-json]":
                    jQuery('#show_hide_div').show();
                    jQuery('#limitorder_div').show();
                    jQuery('#html_format_div').hide();
                    jQuery('#dt_options_div').hide();
                    jQuery('#json_div').show();
                    jQuery('#value_div').hide();
                    jQuery('#template_div').hide();
                    jQuery('#url_link_div').hide();
                    break;
                case "[cfdb-export-link]":
                    jQuery('#show_hide_div').show();
                    jQuery('#limitorder_div').show();
                    jQuery('#html_format_div').hide();
                    jQuery('#dt_options_div').hide();
                    jQuery('#json_div').hide();
                    jQuery('#value_div').hide();
                    jQuery('#template_div').hide();
                    jQuery('#url_link_div').show();
                    break;
                default:
                    jQuery('#show_hide_div').show();
                    jQuery('#limitorder_div').show();
                    jQuery('#html_format_div').hide();
                    jQuery('#dt_options_div').hide();
                    jQuery('#json_div').hide();
                    jQuery('#value_div').hide();
                    jQuery('#template_div').hide();
                    jQuery('#url_link_div').hide();
                    break;
            }
        }

        function getValue(attr, value, errors) {
            if (value) {
                if (errors && value.indexOf('"') > -1) {
                    errors.push('<?php _e('Error: "', 'contact-form-7-to-database-extension'); ?>'
                                        + attr +
                                        '<?php _e('" should not contain double-quotes (")', 'contact-form-7-to-database-extension'); ?>');
                    value = value.replace('"', "'");
                }
                return attr + '="' + value + '"';
            }
            return '';
        }

        function getValueUrl(attr, value) {
            if (value) {
                return attr + '=' + encodeURIComponent(value)
            }
            return '';
        }


        function join(arr, delim) {
            if (delim == null) {
                delim = ' ';
            }
            var tmp = [];
            for (idx=0; idx<arr.length; idx++) {
                if (arr[idx] != '') {
                    tmp.push(arr[idx]);
                }
            }
            return tmp.join(delim);
        }

        function chopLastChar(text) {
            return text.substr(0, text.length - 1);
        }

        function createShortCodeAndExportLink() {
            var scElements = [];
            var scUrlElements = [];
            var exportUrlElements = [];
            var scValidationErrors = [];
            var exportValidationErrors = [];
            var shortcode = jQuery('#shortcode_ctrl').val();
            if (shortcode == '') {
                jQuery('#shortcode_result_text').html('');
                jQuery('#url_result_link').html('').attr('href', '#');
            }
            scElements.push(chopLastChar(shortcode));

            var formName = jQuery('#form_name_cntl').val();
            if (!formName) {
                var errMsg = '<?php _e('Error: no form is chosen', 'contact-form-7-to-database-extension') ?>';
                jQuery('#form_validations_text').html(errMsg);
                scValidationErrors.push(errMsg);
                exportValidationErrors.push(errMsg);
            }
            else {
                jQuery('#form_validations_text').html('');
                scElements.push('form="' + formName + '"');
                scUrlElements.push('form=' + encodeURIComponent(formName));
                exportUrlElements.push('form=' + encodeURIComponent(formName));
            }

            if (shortcode != '[cfdb-count]') {
                scElements.push(getValue('show', jQuery('#show_cntl').val(), scValidationErrors));
                scUrlElements.push(getValueUrl('show', jQuery('#show_cntl').val()));
                exportUrlElements.push(getValueUrl('show', jQuery('#show_cntl').val()));

                scElements.push(getValue('hide', jQuery('#hide_cntl').val(), scValidationErrors));
                scUrlElements.push(getValueUrl('hide', jQuery('#hide_cntl').val()));
                exportUrlElements.push(getValueUrl('hide', jQuery('#hide_cntl').val()));
            }

            var filter = getValue('filter', jQuery('#filter_cntl').val(), scValidationErrors);
            if (filter) {
                scElements.push(filter);
                scUrlElements.push(getValueUrl('filter', jQuery('#filter_cntl').val()));
                exportUrlElements.push(getValueUrl('filter', jQuery('#filter_cntl').val()));

                if (jQuery('#search_cntl').val()) {
                    scValidationErrors.push('<?php _e('Warning: "search" field ignored because "filter" is used (use one but not both)', 'contact-form-7-to-database-extension'); ?>');
                }
            }
            else {
                scElements.push(getValue('search', jQuery('#search_cntl').val(), scValidationErrors));
                scUrlElements.push(getValueUrl('search', jQuery('#search_cntl').val()));
                exportUrlElements.push(getValueUrl('search', jQuery('#search_cntl').val()));
            }

            if (shortcode != '[cfdb-count]') {
                var limitRows = jQuery('#limit_rows_cntl').val();
                var limitStart = jQuery('#limit_start_cntl').val();
                if (limitStart && !limitRows) {
                    scValidationErrors.push('<?php _e('Error: "limit": if you provide a value for "Start Row" then you must also provide a value for "Num Rows"', 'contact-form-7-to-database-extension'); ?>');
                }
                if (limitRows) {
                    if (! /^\d+$/.test(limitRows)) {
                        scValidationErrors.push('<?php _e('Error: "limit": "Num Rows" must be a positive integer', 'contact-form-7-to-database-extension'); ?>');
                    }
                    else {
                        var limitOption = ' limit="';
                        var limitOptionUrl = ' limit=';
                        if (limitStart) {
                            if (! /^\d+$/.test(limitStart)) {
                                scValidationErrors.push('<?php _e('Error: "limit": "Start Row" must be a positive integer', 'contact-form-7-to-database-extension'); ?>');
                            }
                            else {
                                limitOption += limitStart + ",";
                                limitOptionUrl += encodeURIComponent(limitStart + ",");
                            }
                        }
                        limitOption += limitRows;
                        limitOptionUrl += limitRows;
                        scElements.push(limitOption + '"');
                        scUrlElements.push(limitOptionUrl);
                        exportUrlElements.push(limitOptionUrl);
                    }
                }
                var orderByElem = getValue('orderby', jQuery('#orderby_cntl').val(), scValidationErrors);
                if (orderByElem) {
                    var orderByElemUrl = getValueUrl('orderby', jQuery('#orderby_cntl').val());
                    var orderByDir = jQuery('#orderbydir_cntl').val();
                    if (orderByDir) {
                        orderByElem = chopLastChar(orderByElem) + ' ' + orderByDir + '"';
                        orderByElemUrl = orderByElemUrl + encodeURIComponent(' ' + orderByDir);
                    }
                    scElements.push(orderByElem);
                    scUrlElements.push(orderByElemUrl);
                    exportUrlElements.push(orderByElemUrl);
                }
            }

            var scText;
            switch (shortcode) {
                case '[cfdb-html]':
                    scElements.push(getValue('filelinks', jQuery('#filelinks_cntl').val(), scValidationErrors));
                    scUrlElements.push(getValueUrl('filelinks', jQuery('#filelinks_cntl').val()));
                    scElements.push(getValue('wpautop', jQuery('#wpautop_cntl').val(), scValidationErrors));
                    scUrlElements.push(getValueUrl('wpautop', jQuery('#wpautop_cntl').val()));
                    scElements.push(getValue('stripbr', jQuery('#stripbr_cntl').val(), scValidationErrors));
                    scUrlElements.push(getValueUrl('stripbr', jQuery('#stripbr_cntl').val()));
                    var content = jQuery('#content_cntl').val();
                    scUrlElements.push('content=' + encodeURIComponent(content));
                    scUrlElements.push('enc=HTMLTemplate');
                    scText = join(scElements) + ']' +
                            // Escape html tags for display on page
                            content.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') +
                            '[/cfdb-html]';
                    if (content == "") {
                        scValidationErrors.push('<?php _e('Error: [cfdb-html] has empty Template. It will not output anything. ', 'contact-form-7-to-database-extension'); ?>');
                    }
                    break;
                case '[cfdb-table]':
                    scElements.push(getValue('id', jQuery('#id_cntl').val(), scValidationErrors));
                    scUrlElements.push(getValueUrl('id', jQuery('#id_cntl').val()));
                    scElements.push(getValue('class', jQuery('#class_cntl').val(), scValidationErrors));
                    scUrlElements.push(getValueUrl('class', jQuery('#class_cntl').val()));
                    scElements.push(getValue('style', jQuery('#style_cntl').val(), scValidationErrors));
                    scUrlElements.push(getValueUrl('style', jQuery('#style_cntl').val()));
                    scUrlElements.push('enc=HTML');
                    scText = join(scElements) + ']';
                    break;
                case '[cfdb-datatable]':
                    scElements.push(getValue('id', jQuery('#id_cntl').val(), scValidationErrors));
                    scUrlElements.push(getValueUrl('id', jQuery('#id_cntl').val()));
                    scElements.push(getValue('class', jQuery('#class_cntl').val(), scValidationErrors));
                    scUrlElements.push(getValueUrl('class', jQuery('#class_cntl').val()));
                    scElements.push(getValue('style', jQuery('#style_cntl').val(), scValidationErrors));
                    scUrlElements.push(getValueUrl('style', jQuery('#style_cntl').val()));
                    scElements.push(getValue('dt_options', jQuery('#dt_options_cntl').val(), scValidationErrors));
                    scUrlElements.push(getValueUrl('dt_options', jQuery('#dt_options_cntl').val()));
                    scUrlElements.push('enc=DT');
                    scText = join(scElements) + ']';
                    break;
                case '[cfdb-value]':
                    scElements.push(getValue('function', jQuery('#function_cntl').val(), scValidationErrors));
                    scUrlElements.push(getValueUrl('function', jQuery('#function_cntl').val()));
                    scElements.push(getValue('delimiter', jQuery('#delimiter_cntl').val(), scValidationErrors));
                    scUrlElements.push(getValueUrl('delimiter', jQuery('#delimiter_cntl').val()));
                    scUrlElements.push('enc=VALUE');
                    scText = join(scElements) + ']';
                    break;
                case '[cfdb-count]':
                    scUrlElements.push('enc=COUNT');
                    scText = join(scElements) + ']'; // hopLastChar(scElements.join(' ')) + ']';
                    break;
                case '[cfdb-json]':
                    scElements.push(getValue('var', jQuery('#var_cntl').val(), scValidationErrors));
                    scUrlElements.push(getValueUrl('var', jQuery('#var_cntl').val()));
                    scElements.push(getValue('format', jQuery('#format_cntl').val(), scValidationErrors));
                    scUrlElements.push(getValueUrl('format', jQuery('#format_cntl').val()));
                    scUrlElements.push('enc=JSON');
                    scText = join(scElements) + ']';
                    break;
                case '[cfdb-export-link]':
                    scElements.push(getValue('enc', jQuery('#enc_cntl').val(), scValidationErrors));
                    scUrlElements.push(getValueUrl('enc', jQuery('#enc_cntl').val()));
                    scElements.push(getValue('urlonly', jQuery('#urlonly_cntl').val(), scValidationErrors));
                    scElements.push(getValue('linktext', jQuery('#linktext_cntl').val(), scValidationErrors));
                    scText = join(scElements) + ']';
                    break;
                default:
                    scText = shortcode;
                    break;
            }

            var urlBase = '<?php echo admin_url('admin-ajax.php') ?>?action=cfdb-export&';

            if (shortcode) {
                // Output short code text
                jQuery('#shortcode_result_text').html(scText);

                // Output short code test link
                var scUrl = urlBase + join(scUrlElements, '&');
                jQuery('#url_result_link').html(
                        '<a target="_cfdb_export" href="' + scUrl + '"><?php _e('(Test Short Code Results)', 'contact-form-7-to-database-extension'); ?></a>');

                // Output short code errors
                jQuery('#shortcode_validations_text').html(scValidationErrors.join('<br/>'));
            }
            else {
                // Don't report errors
                jQuery('#shortcode_validations_text').html('');
            }

            // Output export link
            if (jQuery('#export_cntl').val()) {
                exportUrlElements.push(getValueUrl('enc', jQuery('#export_cntl').val()));
                var exportUrl = urlBase + join(exportUrlElements, '&');
                jQuery('#export_result_text').html(formName ? ('<a href="' + exportUrl + '">' + exportUrl + '</a>') : '');
                // Output export errors
                jQuery('#export_validations_text').html(exportValidationErrors.join('<br/>'));
            }
            else {
                jQuery('#export_result_text').html('');
                // Don't report errors
                jQuery('#export_validations_text').html('');
            }

        }

        var getFormFieldsUrlBase = '<?php echo $plugin->getFormFieldsAjaxUrlBase() ?>';
        function getFormFields() {
            jQuery('[id^=add]').attr('disabled', 'disabled');
            jQuery('[id^=btn]').attr('disabled', 'disabled');
            var formName = jQuery('#form_name_cntl').val();
            var url = getFormFieldsUrlBase + encodeURIComponent(formName);
            jQuery.getJSON(url, function(json) {
                var optionsHtml = '<option value=""></option>';
                jQuery(json).each(function() {
                    optionsHtml += '<option value="' + this + '">' + this + '</option>';
                });
                jQuery('[id^=add]').html(optionsHtml).removeAttr('disabled');
                jQuery('[id^=btn]').removeAttr('disabled');
            });
        }

        function validateSubmitTime() {
            var url = "<?php echo $plugin->getValidateSubmitTimeAjaxUrlBase() ?>" + jQuery('#filter_val').val();
            jQuery.get(url, function(data) {
                alert(data);
            });
        }

        function showValidateSubmitTimeHelp(show) {
            if (show) {
                jQuery('#span_validate_submit_time').show();
            }
            else {
                jQuery('#span_validate_submit_time').hide();
            }
        }

        function addFieldToShow() {
            var value = jQuery('#show_cntl').val();
            if (value) {
                value += ',';
            }
            jQuery('#show_cntl').val(value + jQuery('#add_show').val());
            createShortCodeAndExportLink();
        }

        function addFieldToHide() {
            var value = jQuery('#hide_cntl').val();
            if (value) {
                value += ',';
            }
            jQuery('#hide_cntl').val(value + jQuery('#add_hide').val());
            createShortCodeAndExportLink();
        }

        function addFieldToOrderBy() {
            var value = jQuery('#orderby_cntl').val();
            if (value) {
                value += ',';
            }
            jQuery('#orderby_cntl').val(value + jQuery('#add_orderby').val());
            createShortCodeAndExportLink();
        }

        function addFieldToFilter() {
            var value = jQuery('#filter_cntl').val();
            if (value) {
                value += jQuery('#filter_bool').val();
            }
            value += jQuery('#add_filter').val() + jQuery('#filter_op').val() + jQuery('#filter_val').val();
            jQuery('#filter_cntl').val(value);
            createShortCodeAndExportLink();
        }

        function addFieldToContent() {
            jQuery('#content_cntl').val(jQuery('#content_cntl').val() + '${' + jQuery('#add_content').val() + '}');
        }

        function reset() {
            jQuery('#show_cntl').val('');
            jQuery('#hide_cntl').val('');
            jQuery('#search_cntl').val('');
            jQuery('#filter_cntl').val('');
            jQuery('#limit_rows_cntl').val('');
            jQuery('#limit_start_cntl').val('');
            jQuery('#orderby_cntl').val('');
            jQuery('#id_cntl').val('');
            jQuery('#class_cntl').val('');
            jQuery('#style_cntl').val('');
            jQuery('#dt_options_cntl').val('');
            jQuery('#var_cntl').val('');
            jQuery('#format_cntl').val('');
            jQuery('#function_cntl').val('');
            jQuery('#delimiter_cntl').val('');
            jQuery('#filelinks_cntl').val('');
            jQuery('#wpautop_cntl').val('');
            jQuery('#stripbr_cntl').val('');
            jQuery('#content_cntl').val('');
            jQuery('#enc_cntl').val('');
            jQuery('#urlonly_cntl').val('');
            jQuery('#linktext_cntl').val('');
            showValidateSubmitTimeHelp(false);
            createShortCodeAndExportLink();
        }

        jQuery.ajaxSetup({
            cache: false
        });

        jQuery(document).ready(function() {
            showHideOptionDivs();
            createShortCodeAndExportLink();
            jQuery('#shortcode_ctrl').change(showHideOptionDivs);
            jQuery('#shortcode_ctrl').change(createShortCodeAndExportLink);
            jQuery('select[id$="cntl"]').change(createShortCodeAndExportLink);
            jQuery('input[id$="cntl"]').keyup(createShortCodeAndExportLink);
            jQuery('textarea[id$="cntl"]').keyup(createShortCodeAndExportLink);
            jQuery('#form_name_cntl').change(getFormFields);
            jQuery('#btn_show').click(addFieldToShow);
            jQuery('#btn_hide').click(addFieldToHide);
            jQuery('#btn_orderby').click(addFieldToOrderBy);
            jQuery('#btn_filter').click(addFieldToFilter);
            jQuery('#btn_content').click(function() {
                addFieldToContent();
                createShortCodeAndExportLink();
            });
            jQuery('#enc_cntl').click(createShortCodeAndExportLink);
            jQuery('#urlonly_cntl').click(createShortCodeAndExportLink);
            jQuery('#reset_button').click(reset);
            jQuery('#btn_validate_submit_time').click(validateSubmitTime);
            jQuery('#add_filter').change(function() {
                showValidateSubmitTimeHelp(jQuery('#add_filter').val() == "submit_time");
            });
            jQuery('#export_cntl').change(createShortCodeAndExportLink);
            jQuery('#form_name_cntl').change(createShortCodeAndExportLink);
        });


    </script>
    <style type="text/css">
        div.shortcodeoptions {
            border: #ccccff groove;
            margin-bottom: 10px;
            padding: 5px;
        }

        div.shortcodeoptions label {
            font-weight: bold;
            font-family: Arial sans-serif;
            margin-top: 5px;
        }

        #shortcode_result_div {
            margin-top: 1em;
        }

        .label_box {
            display: inline-block;
            min-width: 50px;
            padding-left: 2px;
            padding-right: 2px;
            border: 1px;
            margin-right: 5px;
        }

        .generated {
            margin-top: 5px;
            margin-bottom: 5px;
            margin-left: 10px;
            white-space: -moz-pre-wrap;
            white-space: -pre-wrap;
            white-space: -o-pre-wrap;
            white-space: pre-wrap;
            word-wrap: break-word;
            font-size: larger;
            font-weight: bold;
            font-family: "courier new", monospace;
            background-color: #ffffc3;
        }

        .validation {
            background-color: #ffe200;
            font-style:italic;
        }
    </style>

        <h2><?php _e('Export and Short Code Builder', 'contact-form-7-to-database-extension') ?></h2>
    <?php // FORM  ?>
    <div class="shortcodeoptions" style="margin-top:10px;">
        <div class="label_box"><label for="form_name_cntl"><?php _e('form', 'contact-form-7-to-database-extension') ?></label></div>
        <select name="form_name_cntl" id="form_name_cntl">
            <option value=""><?php _e('* Select a form *', 'contact-form-7-to-database-extension') ?></option>
            <?php foreach ($rows as $aRow) {
            $formName = $aRow->form_name;
            $selected = ($formName == $currSelection) ? "selected" : "";
            ?>
            <option value="<?php echo $formName ?>" <?php echo $selected ?>><?php echo $formName ?></option>
            <?php } ?>
        </select>

        <?php // RESET  ?>
        <span style="margin-left:10px">
            <button id="reset_button"><?php echo _e('Reset', 'contact-form-7-to-database-extension') ?></button>
        </span>

        <div id="form_validations_text" class="validation"></div>
    </div>

    <?php // EXPORT  ?>
    <div class="shortcodeoptions">
        <label for="export_cntl"><?php _e('Export File', 'contact-form-7-to-database-extension') ?></label>
        <select id="export_cntl" name="export_cntl">
            <option value=""></option>
            <option value="CSVUTF8BOM">
                <?php _e('Excel CSV (UTF8-BOM)', 'contact-form-7-to-database-extension'); ?>
            </option>
            <option value="TSVUTF16LEBOM">
                <?php _e('Excel TSV (UTF16LE-BOM)', 'contact-form-7-to-database-extension'); ?>
            </option>
            <option value="CSVUTF8">
                <?php _e('Plain CSV (UTF-8)', 'contact-form-7-to-database-extension'); ?>
            </option>
            <option value="IQY">
                <?php _e('Excel Internet Query', 'contact-form-7-to-database-extension'); ?>
            </option>
        </select>

        <div id="export_result_div">
            <?php _e('Generated Export Link:', 'contact-form-7-to-database-extension'); ?>
            <br/><div class="generated" id="export_result_text"></div>
        </div>
        <div id="export_validations_text" class="validation"></div>
        <span style="font-size: x-small;">
            <a target="_docs"
               href="http://cfdbplugin.com/?page_id=444"><?php _e('(Did you know: you can create your own short code)', 'contact-form-7-to-database-extension'); ?></a>
        </span>

    </div>


    <?php // SHORT CODE  ?>
    <div class="shortcodeoptions">
        <div style="margin-bottom:10px">
            <div class="label_box"><label
                    for="shortcode_ctrl"><?php _e('Short Code', 'contact-form-7-to-database-extension') ?></label>
            </div>
            <select name="shortcode_ctrl" id="shortcode_ctrl">
                <option value=""><?php _e('* Select a short code *', 'contact-form-7-to-database-extension') ?></option>
                <option value="[cfdb-html]">[cfdb-html]</option>
                <option value="[cfdb-table]">[cfdb-table]</option>
                <option value="[cfdb-datatable]">[cfdb-datatable]</option>
                <option value="[cfdb-value]">[cfdb-value]</option>
                <option value="[cfdb-count]">[cfdb-count]</option>
                <option value="[cfdb-json]">[cfdb-json]</option>
                <option value="[cfdb-export-link]">[cfdb-export-link]</option>
            </select>
            <a id="doc_url_tag" target="_docs"
               href="http://cfdbplugin.com/?page_id=89"><?php _e('Documentation', 'contact-form-7-to-database-extension'); ?></a>
            <br/>
        </div>


        <div id="shortcode_result_div">
            <?php _e('Generated Short Code:', 'contact-form-7-to-database-extension'); ?> &nbsp;&nbsp;&nbsp;<span style="font-size: smaller;" id="url_result_link"></span>
            <br/><div class="generated" id="shortcode_result_text"></div>
        </div>
        <div id="shortcode_validations_text" class="validation"></div>
        <span style="font-size: x-small;">
            <a target="_docs"
               href="http://cfdbplugin.com/?page_id=444"><?php _e('(Did you know: you can create your own short code)', 'contact-form-7-to-database-extension'); ?></a>
        </span>

    </div>


    <?php // SHOW HIDE  ?>
    <div id="show_hide_div" class="shortcodeoptions">
        <?php _e('Which fields/columns do you want to display?', 'contact-form-7-to-database-extension'); ?>
        <div>
            <div class="label_box">
                <label for="show_cntl"><?php _e('show', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=89#show"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <select name="add_show" id="add_show"></select><button id="btn_show"">&raquo;</button>
            <input name="show_cntl" id="show_cntl" type="text" size="100"/>
        </div>
        <div>
            <div class="label_box">
                <label for="hide_cntl"><?php _e('hide', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=89#hide"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <select name="add_hide" id="add_hide"></select><button id="btn_hide">&raquo;</button>
            <input name="hide_cntl" id="hide_cntl" type="text" size="100"/>
        </div>
    </div>
    <?php // SEARCH FILTER  ?>
    <div id="filter_div" class="shortcodeoptions">
        <div><?php _e('Which rows/submissions do you want to display?', 'contact-form-7-to-database-extension'); ?></div>
        <div>
            <div class="label_box">
                <label for="search_cntl"><?php _e('search', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=89#search"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <input name="search_cntl" id="search_cntl" type="text" size="30"/>
        </div>
        <div>
            <div class="label_box">
                <label for="filter_cntl"><?php _e('filter', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=89#filter"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <select name="filter_bool" id="filter_bool">
                <option value="&&">&&</option>
                <option value="||">||</option>
            </select>
            <select name="add_filter" id="add_filter"></select>
            <select name="filter_op" id="filter_op">
                <option value="=">=</option>
                <option value="!=">!=</option>
                <option value=">">></option>
                <option value="<"><</option>
                <option value="<="><=</option>
                <option value="<="><=</option>
                <option value="===">===</option>
                <option value="!==">!==</option>
                <option value="~~">~~</option>
            </select>
            <input name="filter_val" id="filter_val" type="text" size="20"/>
            <button id="btn_filter">&raquo;</button>
            <span id="span_validate_submit_time" style="display:none;">
                <button id="btn_validate_submit_time"><?php _e('Validate submit_time', 'contact-form-7-to-database-extension'); ?></button>
                <a target="_blank" href="http://cfdbplugin.com/?page_id=553"><?php _e('Formats', 'contact-form-7-to-database-extension'); ?></a>
            </span>
            <br/>
            <input name="filter_cntl" id="filter_cntl" type="text" size="100"/>
        </div>
    </div>
    <?php // LIMIT ORDER BY  ?>
    <div id="limitorder_div" class="shortcodeoptions">
        <div>
            <div class="label_box">
                <label for="limit_rows_cntl"><?php _e('limit', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=89#limit"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <?php _e('Num Rows', 'contact-form-7-to-database-extension') ?> <input name="limit_rows_cntl" id="limit_rows_cntl" type="text" size="10"/>
            <?php _e('Start Row (0)', 'contact-form-7-to-database-extension') ?> <input name="limit_start_cntl" id="limit_start_cntl" type="text" size="10"/>
        </div>
        <div id="orderby_div">
            <div class="label_box">
                <label for="orderby_cntl"><?php _e('orderby', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=89#orderby"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <select name="add_orderby" id="add_orderby"></select><button id="btn_orderby">&raquo;</button>
            <input name="orderby_cntl" id="orderby_cntl" type="text" size="100"/>
            <select id="orderbydir_cntl" name="orderbydir_cntl">
                <option value=""></option>
                <option value="ASC"><?php _e('ASC', 'contact-form-7-to-database-extension') ?></option>
                <option value="DESC"><?php _e('DESC', 'contact-form-7-to-database-extension') ?></option>
            </select>
        </div>
    </div>
    <?php // ID, CLASS, STYLE  ?>
    <div id="html_format_div" class="shortcodeoptions">
        <div><?php _e('HTML Table Formatting', 'contact-form-7-to-database-extension'); ?></div>
        <div>
            <div class="label_box">
                <label for="id_cntl"><?php _e('id', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=93#id"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <input name="id_cntl" id="id_cntl" type="text" size="10"/>
        </div>
        <div>
            <div class="label_box">
                <label for="class_cntl"><?php _e('class', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=93#class"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <input name="class_cntl" id="class_cntl" type="text" size="10"/>
        </div>
        <div>
            <div class="label_box">
                <label for="style_cntl"><?php _e('style', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=93#style"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <input name="style_cntl" id="style_cntl" type="text" size="100"/>
        </div>
    </div>
    <?php // DT_OPTIONS  ?>
    <div id="dt_options_div" class="shortcodeoptions">
        <div><?php _e('DataTable Options', 'contact-form-7-to-database-extension'); ?></div>
        <div class="label_box">
            <label for="dt_options_cntl"><?php _e('dt_options', 'contact-form-7-to-database-extension') ?></label>
            <a target="_docs" href="http://cfdbplugin.com/?page_id=91#dt_options"><img src="<?php echo $infoImg ?>"/></a>
        </div>
        <input name="dt_options_cntl" id="dt_options_cntl" type="text" size="100"/>
    </div>
    <?php // JSON VAR, FORMAT  ?>
    <div id="json_div" class="shortcodeoptions">
        <div><?php _e('JSON Options', 'contact-form-7-to-database-extension'); ?></div>
        <div>
            <div class="label_box">
                <label for="var_cntl"><?php _e('var', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=96#var"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <input name="var_cntl" id="var_cntl" type="text" size="10"/>
        </div>
        <div>
            <div class="label_box">
                <label for="format_cntl"><?php _e('format', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=96#format"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <select id="format_cntl" name="format_cntl">
                <option value=""></option>
                <option value="map"><?php _e('map', 'contact-form-7-to-database-extension') ?></option>
                <option value="array"><?php _e('array', 'contact-form-7-to-database-extension') ?></option>
                <option value="arraynoheader"><?php _e('arraynoheader', 'contact-form-7-to-database-extension') ?></option>
            </select>
        </div>
    </div>
    <?php // VALUE FUNCTION, DELIMITER  ?>
    <div id="value_div" class="shortcodeoptions">
        <div><?php _e('VALUE Options', 'contact-form-7-to-database-extension'); ?></div>
        <div>
            <div class="label_box">
                <label for="function_cntl"><?php _e('function', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=98#function"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <select id="function_cntl" name="function_cntl">
                <option value=""></option>
                <option value="min"><?php _e('min', 'contact-form-7-to-database-extension') ?></option>
                <option value="max"><?php _e('max', 'contact-form-7-to-database-extension') ?></option>
                <option value="sum"><?php _e('sum', 'contact-form-7-to-database-extension') ?></option>
                <option value="mean"><?php _e('mean', 'contact-form-7-to-database-extension') ?></option>
                <option value="percent"><?php _e('percent', 'contact-form-7-to-database-extension') ?></option>
            </select>
        </div>
        <div>
            <div class="label_box">
                <label for="delimiter_cntl"><?php _e('delimiter', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=98#delimiter"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <input name="delimiter_cntl" id="delimiter_cntl" type="text" size="10"/>
        </div>
    </div>
    <?php // HTML TEMPLATE  ?>
    <div id="template_div" class="shortcodeoptions">
        <div>
            <div class="label_box">
                <label for="filelinks_cntl"><?php _e('filelinks', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=284#filelinks"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <select id="filelinks_cntl" name="filelinks_cntl">
                <option value=""></option>
                <option value="url"><?php _e('url', 'contact-form-7-to-database-extension') ?></option>
                <option value="name"><?php _e('name', 'contact-form-7-to-database-extension') ?></option>
                <option value="link"><?php _e('link', 'contact-form-7-to-database-extension') ?></option>
                <option value="img"><?php _e('img', 'contact-form-7-to-database-extension') ?></option>
            </select>
            <div class="label_box">
                <label for="stripbr_cntl"><?php _e('stripbr', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=284#stripbr"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <select id="stripbr_cntl" name="stripbr_cntl">
                <option value=""></option>
                <option value="false"><?php _e('false', 'contact-form-7-to-database-extension') ?></option>
                <option value="true"><?php _e('true', 'contact-form-7-to-database-extension') ?></option>
            </select>
            <div class="label_box">
                <label for="wpautop_cntl" style="text-decoration:line-through;"><?php _e('wpautop', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=284#wpautop"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <select id="wpautop_cntl" name="wpautop_cntl">
                <option value=""></option>
                <option value="false"><?php _e('false', 'contact-form-7-to-database-extension') ?></option>
                <option value="true"><?php _e('true', 'contact-form-7-to-database-extension') ?></option>
            </select>
        </div>
        <div>
            <div class="label_box">
                <label for="content_cntl"><?php _e('Template', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=284#template"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <select name="add_content" id="add_content"></select><button id="btn_content">&raquo;</button><br/>
            <textarea name="content_cntl" id="content_cntl" cols="100" rows="10"></textarea>
        </div>
    </div>
    <?php // URL ENC, URL_ONLY LINK_TEXT      ?>
    <div id="url_link_div" class="shortcodeoptions">
        <div>
            <div class="label_box">
                <label for="enc_cntl"><?php _e('enc', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=419"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <select id="enc_cntl" name="enc_cntl">
                <option value=""></option>
                <option id="CSVUTF8BOM" value="CSVUTF8BOM">
                    <?php _e('Excel CSV (UTF8-BOM)', 'contact-form-7-to-database-extension'); ?>
                </option>
                <option id="TSVUTF16LEBOM" value="TSVUTF16LEBOM">
                    <?php _e('Excel TSV (UTF16LE-BOM)', 'contact-form-7-to-database-extension'); ?>
                </option>
                <option id="CSVUTF8" value="CSVUTF8">
                    <?php _e('Plain CSV (UTF-8)', 'contact-form-7-to-database-extension'); ?>
                </option>
                <option id="IQY" value="IQY">
                    <?php _e('Excel Internet Query', 'contact-form-7-to-database-extension'); ?>
                </option>
            </select>
        </div>
        <div>
            <div class="label_box">
                <label for="urlonly_cntl"><?php _e('urlonly', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=419#urlonly"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <select id="urlonly_cntl" name="urlonly_cntl">
                <option value=""></option>
                <option value="true"><?php _e('true', 'contact-form-7-to-database-extension') ?></option>
                <option value="false"><?php _e('false', 'contact-form-7-to-database-extension') ?></option>
            </select>
        </div>
        <div>
            <div class="label_box">
                <label for="linktext_cntl"><?php _e('linktext', 'contact-form-7-to-database-extension') ?></label>
                <a target="_docs" href="http://cfdbplugin.com/?page_id=419#linktext"><img src="<?php echo $infoImg ?>"/></a>
            </div>
            <input name="linktext_cntl" id="linktext_cntl" type="text" size="30"/>
        </div>
    </div>
    <?php

    }
}
