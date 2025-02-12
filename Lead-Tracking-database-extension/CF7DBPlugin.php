<?php
/*
    
    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

require_once('CF7DBPluginLifeCycle.php');
require_once('CFDBShortcodeTable.php');
require_once('CFDBShortcodeDataTable.php');
require_once('CFDBShortcodeValue.php');
require_once('CFDBShortcodeCount.php');
require_once('CFDBShortcodeJson.php');
require_once('CFDBShortcodeHtml.php');
require_once('CFDBShortcodeExportUrl.php');
require_once('CFDBShortCodeSavePostData.php');

/**
 * Implementation for CF7DBPluginLifeCycle.
 */
global $srno;
$srno=0;
class CF7DBPlugin extends CF7DBPluginLifeCycle {

    public function getPluginDisplayName() {
        return 'Contact Form to DB Extension';
    }

    protected function getMainPluginFileName() {
        return 'contact-form-7-db.php';
    }

    public function getOptionMetaData() {
        return array(
            //'_version' => array('Installed Version'), // For testing upgrades
            'Donated' => array(__('I have donated to this plugin', 'contact-form-7-to-database-extension'), 'false', 'true'),
            'IntegrateWithCF7' => array(__('Capture form submissions from Contact Form 7 Plugin', 'contact-form-7-to-database-extension'), 'true', 'false'),
            'IntegrateWithFSCF' => array(__('Capture form submissions from Fast Secure Contact Form Plugin', 'contact-form-7-to-database-extension'), 'true', 'false'),
            'CanSeeSubmitData' => array(__('Can See Submission data', 'contact-form-7-to-database-extension'),
                                        'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber', 'Anyone'),
            'CanSeeSubmitDataViaShortcode' => array(__('Can See Submission when using shortcodes', 'contact-form-7-to-database-extension'),
                                                    'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber', 'Anyone'),
            'CanChangeSubmitData' => array(__('Can Edit/Delete Submission data', 'contact-form-7-to-database-extension'),
                                           'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber'),
            'MaxRows' => array(__('Maximum number of rows to retrieve from the DB for the Admin display', 'contact-form-7-to-database-extension')),
            'UseDataTablesJS' => array(__('Use Javascript-enabled tables in Admin Database page', 'contact-form-7-to-database-extension'), 'true', 'false'),
            'ShowLineBreaksInDataTable' => array(__('Show line breaks in submitted data table', 'contact-form-7-to-database-extension'), 'true', 'false'),
            'UseCustomDateTimeFormat' => array(__('Use Custom Date-Time Display Format (below)', 'contact-form-7-to-database-extension'), 'true', 'false'),
            'SubmitDateTimeFormat' => array('<a target="_blank" href="http://php.net/manual/en/function.date.php">' . __('Date-Time Display Format', 'contact-form-7-to-database-extension') . '</a>'),
            'ShowFileUrlsInExport' => array(__('Export URLs instead of file names for uploaded files', 'contact-form-7-to-database-extension'), 'false', 'true'),
            'NoSaveFields' => array(__('Do not save <u>fields</u> in DB named (comma-separated list, no spaces)', 'contact-form-7-to-database-extension')),
            'NoSaveForms' => array(__('Do not save <u>forms</u> in DB named (comma-separated list, no spaces)', 'contact-form-7-to-database-extension')),
            'SaveCookieData' => array(__('Save Cookie Data with Form Submissions', 'contact-form-7-to-database-extension'), 'false', 'true'),
            'SaveCookieNames' => array(__('Save only cookies in DB named (comma-separated list, no spaces, and above option must be set to true)', 'contact-form-7-to-database-extension')),
            'ShowQuery' => array(__('Show the query used to display results', 'contact-form-7-to-database-extension'), 'false', 'true'),
            'DropOnUninstall' => array(__('Drop this plugin\'s Database table on uninstall', 'contact-form-7-to-database-extension'), 'false', 'true'),
            //'SubmitTableNameOverride' => array(__('Use this table to store submission data rather than the default (leave blank for default)', 'contact-form-7-to-database-extension'))
        );
    }

    protected function getOptionValueI18nString($optionValue) {
        switch ($optionValue) {
            case 'true':
                return __('true', 'contact-form-7-to-database-extension');
            case 'false':
                return __('false', 'contact-form-7-to-database-extension');

            case 'Administrator':
                return __('Administrator', 'contact-form-7-to-database-extension');
            case 'Editor':
                return __('Editor', 'contact-form-7-to-database-extension');
            case 'Author':
                return __('Author', 'contact-form-7-to-database-extension');
            case 'Contributor':
                return __('Contributor', 'contact-form-7-to-database-extension');
            case 'Subscriber':
                return __('Subscriber', 'contact-form-7-to-database-extension');
            case 'Anyone':
                return __('Anyone', 'contact-form-7-to-database-extension');
        }
        return $optionValue;
    }

    public function upgrade() {
        global $wpdb;
        $upgradeOk = true;
        $savedVersion = $this->getVersionSaved();
        if (!$savedVersion) { // Prior to storing version in options (pre 1.2)
            // DB Schema Upgrade to support i18n using UTF-8
            $tableName = $this->getSubmitsTableName();
            $wpdb->show_errors();
            $upgradeOk &= false !== $wpdb->query("ALTER TABLE `$tableName` MODIFY form_name VARCHAR(127) CHARACTER SET utf8");
            $upgradeOk &= false !== $wpdb->query("ALTER TABLE `$tableName` MODIFY field_name VARCHAR(127) CHARACTER SET utf8");
            $upgradeOk &= false !== $wpdb->query("ALTER TABLE `$tableName` MODIFY field_value longtext CHARACTER SET utf8");
            $wpdb->hide_errors();

            // Remove obsolete options
            $this->deleteOption('_displayName');
            $this->deleteOption('_metatdata');
            $savedVersion = '1.0';
        }

        if ($this->isVersionLessThan($savedVersion, '2.2')) {
            if ($this->isVersionLessThan($savedVersion, '2.0')) {
                if ($this->isVersionLessThan($savedVersion, '1.8')) {
                    if ($this->isVersionLessThan($savedVersion, '1.4.5')) {
                        if ($this->isVersionLessThan($savedVersion, '1.3.1')) {
                            // Version 1.3.1 update
                            $tableName = $this->getSubmitsTableName();
                            $wpdb->show_errors();
                            $upgradeOk &= false !== $wpdb->query("ALTER TABLE `$tableName` ADD COLUMN `field_order` INTEGER");
                            $upgradeOk &= false !== $wpdb->query("ALTER TABLE `$tableName` ADD COLUMN `file` LONGBLOB");
                            $upgradeOk &= false !== $wpdb->query("ALTER TABLE `$tableName` ADD INDEX `submit_time_idx` ( `submit_time` )");
                            $wpdb->hide_errors();
                        }

                        // Version 1.4.5 update
                        if (!$this->getOption('CanSeeSubmitDataViaShortcode')) {
                            $this->addOption('CanSeeSubmitDataViaShortcode', 'Anyone');
                        }

                        // Misc
                        $submitDateTimeFormat = $this->getOption('SubmitDateTimeFormat');
                        if (!$submitDateTimeFormat || $submitDateTimeFormat == '') {
                            $this->addOption('SubmitDateTimeFormat', 'Y-m-d H:i:s P');
                        }

                    }
                    // Version 1.8 update
                    if (!$this->getOption('MaxRows')) {
                        $this->addOption('MaxRows', '100');
                    }
                    $tableName = $this->getSubmitsTableName();
                    $wpdb->show_errors();
                    /* $upgradeOk &= false !== */
                    $wpdb->query("ALTER TABLE `$tableName` MODIFY COLUMN submit_time DECIMAL(16,4) NOT NULL");
                    /* $upgradeOk &= false !== */
                    $wpdb->query("ALTER TABLE `$tableName` ADD INDEX `form_name_idx` ( `form_name` )");
                    /* $upgradeOk &= false !== */
                    $wpdb->query("ALTER TABLE `$tableName` ADD INDEX `form_name_field_name_idx` ( `form_name`, `field_name` )");
                    $wpdb->hide_errors();
                }

                // Version 2.0 upgrade
                $tableName = $this->getSubmitsTableName();
                $oldTableName = $this->prefixTableName('SUBMITS');
                $wpdb->query("RENAME TABLE `$oldTableName` TO `$tableName`");
            }

            // Version 2.2 upgrade
            $tableName = $this->getSubmitsTableName();
            $wpdb->query("ALTER TABLE `$tableName` DROP INDEX `form_name_field_name_idx`");
            $wpdb->query("ALTER TABLE `$tableName` ADD INDEX `field_name_idx` ( `field_name` )");
        }


        // Post-upgrade, set the current version in the options
        $codeVersion = $this->getVersion();
        if ($upgradeOk && $savedVersion != $codeVersion) {
            $this->saveInstalledVersion();
        }
    }

    /**
     * Called by install()
     * You should: Prefix all table names with $wpdb->prefix
     * Also good: additionally use the prefix for this plugin:
     * $table_name = $wpdb->prefix . $this->prefix('MY_TABLE');
     * @return void
     */
    protected function installDatabaseTables() {
        global $wpdb;
        $tableName = $this->getSubmitsTableName();
        $wpdb->show_errors();
        $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
            `submit_time` DECIMAL(16,4) NOT NULL,
            `form_name` VARCHAR(127) CHARACTER SET utf8,
            `field_name` VARCHAR(127) CHARACTER SET utf8,
            `field_value` LONGTEXT CHARACTER SET utf8,
            `field_order` INTEGER,
            `file` LONGBLOB)");
        $wpdb->query("ALTER TABLE `$tableName` ADD INDEX `submit_time_idx` ( `submit_time` )");
        $wpdb->query("ALTER TABLE `$tableName` ADD INDEX `form_name_idx` ( `form_name` )");
        $wpdb->query("ALTER TABLE `$tableName` ADD INDEX `field_name_idx` ( `field_name` )");

        $wpdb->hide_errors();
    }


    /**
     * Called by uninstall()
     * You should: Prefix all table names with $wpdb->prefix
     * Also good: additionally use the prefix for this plugin:
     * $table_name = $wpdb->prefix . $this->prefix('MY_TABLE');
     * @return void
     */
    protected function unInstallDatabaseTables() {
        if ('true' == $this->getOption('DropOnUninstall', 'false')) {
            global $wpdb;
            $tableName = $this->getSubmitsTableName();
            $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
            //        $tables = array('SUBMITS');
            //        foreach ($tables as $aTable) {
            //            $tableName = $this->prefixTableName($aTable);
            //            $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
            //        }
        }
    }

    public function addActionsAndFilters() {
        // Add the Admin Config page for this plugin

        // Add Config page as a top-level menu item on the Admin page
        add_action('admin_menu', array(&$this, 'createAdminMenu'));

        // Add Database Options page
        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));

        // Hook into Contact Form 7 when a form post is made to save the data to the DB
        if ($this->getOption('IntegrateWithCF7', 'true') == 'true') {
            add_action('wpcf7_before_send_mail', array(&$this, 'saveFormData'));
        }

        // Hook into Fast Secure Contact Form
        if ($this->getOption('IntegrateWithFSCF', 'true') == 'true') {
            add_action('fsctf_mail_sent', array(&$this, 'saveFormData'));
            add_action('fsctf_menu_links', array(&$this, 'fscfMenuLinks'));
        }

        // Have our own hook to publish data independent of other plugins
        add_action('cfdb_submit', array(&$this, 'saveFormData'));

        // Register Export URL
        add_action('wp_ajax_nopriv_cfdb-export', array(&$this, 'ajaxExport'));
        add_action('wp_ajax_cfdb-export', array(&$this, 'ajaxExport'));

        // Register Get File URL
        add_action('wp_ajax_nopriv_cfdb-file', array(&$this, 'ajaxFile'));
        add_action('wp_ajax_cfdb-file', array(&$this, 'ajaxFile'));

        // Register Get Form Fields URL
        add_action('wp_ajax_nopriv_cfdb-getFormFields', array(&$this, 'ajaxGetFormFields'));
        add_action('wp_ajax_cfdb-getFormFields', array(&$this, 'ajaxGetFormFields'));

        // Register Validate submit_time value (used in short code builder page)
        add_action('wp_ajax_nopriv_cfdb-validate-submit_time', array(&$this, 'ajaxValidateSubmitTime'));
        add_action('wp_ajax_cfdb-validate-submit_time', array(&$this, 'ajaxValidateSubmitTime'));

        // Shortcode to add a table to a page
        $sc = new CFDBShortcodeTable();
        $sc->register(array('cf7db-table', 'cfdb-table')); // cf7db-table is deprecated

        // Shortcode to add a DataTable
        $sc = new CFDBShortcodeDataTable();
        $sc->register('cfdb-datatable');

        // Shortcode to add a JSON to a page
        $sc = new CFDBShortcodeJson();
        $sc->register('cfdb-json');

        // Shortcode to add a value (just text) to a page
        $sc = new CFDBShortcodeValue();
        $sc->register('cfdb-value');

        // Shortcode to add entry count to a page
        $sc = new CFDBShortcodeCount();
        $sc->register('cfdb-count');

        // Shortcode to add values wrapped in user-defined html
        $sc = new CFDBShortcodeHtml();
        $sc->register('cfdb-html');

        // Shortcode to generate Export URLs
        $sc = new CFDBShortcodeExportUrl();
        $sc->register('cfdb-export-link');

        // Shortcode to save data from non-CF7/FSCF forms
        $sc = new CFDBShortCodeSavePostData();
        $sc->register('cfdb-save-form-post');
    }

    public function ajaxExport() {
        require_once('CF7DBPluginExporter.php');
        CF7DBPluginExporter::doExportFromPost();
        die();
    }

    public function ajaxFile() {
        require_once('CFDBDie.php');
        //if (!$this->canUserDoRoleOption('CanSeeSubmitData')) {
        if (!$this->canUserDoRoleOption('CanSeeSubmitDataViaShortcode')) {
            CFDBDie::wp_die(__('You do not have sufficient permissions to access this page.', 'contact-form-7-to-database-extension'));
        }
        $submitTime = $_REQUEST['s'];
        $formName = $_REQUEST['form'];
        $fieldName = $_REQUEST['field'];
        if (!$submitTime || !$formName || !$fieldName) {
            CFDBDie::wp_die(__('Missing form parameters', 'contact-form-7-to-database-extension'));
        }
        $fileInfo = (array)$this->getFileFromDB($submitTime, $formName, $fieldName);
        if ($fileInfo == null) {
            CFDBDie::wp_die(__('No such file.', 'contact-form-7-to-database-extension'));
        }

        require_once('CFDBMimeTypeExtensions.php');
        $mimeMap = new CFDBMimeTypeExtensions();
        $mimeType = $mimeMap->get_type_by_filename($fileInfo[0]);
        if ($mimeType) {
            header('Content-Type: ' . $mimeType);
            header("Content-Disposition: inline; filename=\"$fileInfo[0]\"");
        }
        else {
            header("Content-Disposition: attachment; filename=\"$fileInfo[0]\"");
        }

        echo($fileInfo[1]);
        die();
    }


    public function ajaxGetFormFields() {
        if (!$this->canUserDoRoleOption('CanSeeSubmitData') || !isset($_REQUEST['form'])) {
            die();
        }



        header('Content-Type: application/json; charset=UTF-8');
        header("Pragma: no-cache");
        header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
        global $wpdb;
        $tableName = $this->getSubmitsTableName();
        $formName = $_REQUEST['form'];
        $rows = $wpdb->get_results("SELECT DISTINCT `field_name` FROM `$tableName` WHERE `form_name` = '$formName' ORDER BY field_order ");
        $fields = array();
        if (!empty($rows)) {
            $fields[] = 'Submitted';
            foreach ($rows as $aRow) {
                  
                 if($aRow->field_name!="_wpcf7")
		$fields[] = 'id';
                $fields[] = $aRow->field_name;
            }
            $fields[] = 'submit_time';
        }
        echo json_encode($fields);
        die();
    }

    public function ajaxValidateSubmitTime() {
        header('Content-Type: text/plain; charset=UTF-8');
        header("Pragma: no-cache");
        header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
        $submitTime = $_REQUEST['submit_time'];

        $invalid = false;
        $time = $submitTime;
        if (!is_numeric($submitTime)) {
            if (version_compare(phpversion(), '5.1.0') == -1) {
                $invalid = -1;
            }
            // Use times in local timezone
            date_default_timezone_set(get_option('timezone_string'));
            $time = strtotime($submitTime);
        }
        if ($invalid === $time) {
            _e('Invalid: ', 'contact-form-7-to-database-extension');
        }
        else {
            _e('Valid: ', 'contact-form-7-to-database-extension');
        }

        echo "'$submitTime' = $time";

        if ($invalid !== $time) {
            echo " = " . $this->formatDate($time);
        }
        die();
    }

    public function addSettingsSubMenuPage() {
        $this->requireExtraPluginFiles();
        $displayName = $this->getPluginDisplayName();
        add_submenu_page('wpcf7', //$this->getDBPageSlug(),
                         $displayName . ' Options',
                         __('Database Options', 'contact-form-7-to-database-extension'),
                         'manage_options',
                         get_class($this) . 'Settings',
                         array(&$this, 'settingsPage'));
    }


    /**
     * Function courtesy of Mike Challis, author of Fast Secure Contact Form.
     * Displays Admin Panel links in FSCF plugin menu
     * @return void
     */
    public function fscfMenuLinks() {
        $displayName = $this->getPluginDisplayName();
        echo '
        <p>
      ' . $displayName .
                ' | <a href="admin.php?page=' . $this->getDBPageSlug() . '">' .
                __('Database', 'contact-form-7-to-database-extension') .
                '</a>  | <a href="admin.php?page=CF7DBPluginSettings">' .
                __('Database Options', 'contact-form-7-to-database-extension') .
                '</a>  | <a href="admin.php?page=' . $this->getSortCodeBuilderPageSlug() . '">' .
                __('Build Short Code', 'contact-form-7-to-database-extension') .
                '</a> | <a href="http://wordpress.org/extend/plugins/contact-form-7-to-database-extension/faq/">' .
                __('FAQ', 'contact-form-7-to-database-extension') . '</a>
       </p>
      ';
    }

    /**
     * Callback from Contact Form 7. CF7 passes an object with the posted data which is inserted into the database
     * by this function.
     * Also callback from Fast Secure Contact Form
     * @param $cf7 WPCF7_ContactForm|object the former when coming from CF7, the latter $fsctf_posted_data object variable
     * if coming from FSCF
     * @return void
     */
    public function saveFormData($cf7) {
	    global $srno;
        try {
            $title = stripslashes($cf7->title);
            if (in_array($title, $this->getNoSaveForms())) {
                return; // Don't save in DB
            }

            global $wpdb;
            $time = function_exists('microtime') ? microtime(true) : time();

            $ip = (isset($_SERVER['X_FORWARDED_FOR'])) ? $_SERVER['X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
            $tableName = $this->getSubmitsTableName();
            $parametrizedQuery = "INSERT INTO `$tableName` (`submit_time`, `form_name`, `field_name`, `field_value`, `field_order`) VALUES (%s, %s, %s, %s, %s)";
            $parametrizedFileQuery = "UPDATE `$tableName` SET `file` = '%s' WHERE `submit_time` = %F AND `form_name` = '%s' AND `field_name` = '%s' AND `field_value` = '%s'";

            $order = 0;
            $noSaveFields = $this->getNoSaveFields();
            $foundUploadFiles = array();
			
			$query = "SELECT field_name,field_value FROM `$tableName` where field_name='Sr.no' ";
 
                   $row = $wpdb->get_results($query);
				  
				   
                 if(count($row)>0)
                     {	
					    $val=$row[count($row)-1]->field_value;
						$val++;
					 }
				  else
                  {
				    $val=($srno+1);
                  }		
                  				  
				 $quer = "INSERT INTO `$tableName` (`submit_time`, `form_name`, `field_name`, `field_value`, `field_order`) VALUES ('$time','$title', 'Sr.no','$val','$order')";						
               $wpdb->query($quer);
			   $srno++;
			   $order++;
			
			
			
			
            foreach ($cf7->posted_data as $name => $value) {
                $nameClean = stripslashes($name);
                if (in_array($nameClean, $noSaveFields)) {
                    continue; // Don't save in DB
                }

                $value = is_array($value) ? implode($value, ', ') : $value;
                $valueClean = stripslashes($value);
                $wpdb->query($wpdb->prepare($parametrizedQuery,
                                            $time,
                                            $title,
                                            $nameClean,
                                            $valueClean,
                                            $order++));

                // Store uploaded files - Do as a separate query in case it fails due to max size or other issue
                if ($cf7->uploaded_files && isset($cf7->uploaded_files[$nameClean])) {
                    $foundUploadFiles[] = $nameClean;
                    $filePath = $cf7->uploaded_files[$nameClean];
                    if ($filePath) {
                        $content = file_get_contents($filePath);
                        $wpdb->query($wpdb->prepare($parametrizedFileQuery,
                                                    $content,
                                                    $time,
                                                    $title,
                                                    $nameClean,
                                                    $valueClean));
                    }
                }
            }

            // Since Contact Form 7 version 3.1, it no longer puts the names of the files in $cf7->posted_data
            // So check for them only only in $cf7->uploaded_files
            foreach ($cf7->uploaded_files as $field => $filePath) {
                if (!in_array($field, $foundUploadFiles) && $filePath) {
                    $fileName = basename($filePath);
                    $wpdb->query($wpdb->prepare($parametrizedQuery,
                                                $time,
                                                $title,
                                                $field,
                                                $fileName,
                                                $order++));
                    $content = file_get_contents($filePath);
                    $wpdb->query($wpdb->prepare($parametrizedFileQuery,
                                                $content,
                                                $time,
                                                $title,
                                                $field,
                                                $fileName));
                }
            }

            // Save Cookie data if that option is true
            if ($this->getOption('SaveCookieData', 'false') == 'true' && is_array($_COOKIE)) {
                $saveCookies = $this->getSaveCookies();
                foreach ($_COOKIE as $cookieName => $cookieValue) {
                    if (!empty($saveCookies) && !in_array($cookieName, $saveCookies)) {
                        continue;
                    }
                    $wpdb->query($wpdb->prepare($parametrizedQuery,
                                                $time,
                                                $title,
                                                'Cookie ' . $cookieName,
                                                $cookieValue,
                                                $order++));
                }
            }

            // If the submitter is logged in, capture his id
            if (is_user_logged_in()) {
                $order = ($order < 9999) ? 9999 : $order + 1; // large order num to try to make it always next-to-last
                $current_user = wp_get_current_user(); // WP_User
                $wpdb->query($wpdb->prepare($parametrizedQuery,
                                            $time,
                                            $title,
                                            'Submitted Login',
                                            $current_user->user_login,
                                            $order));
            }

            // Capture the IP Address of the submitter
            $order = ($order < 10000) ? 10000 : $order + 1; // large order num to try to make it always last
            $wpdb->query($wpdb->prepare($parametrizedQuery,
                                        $time,
                                        $title,
                                        'Submitted From',
                                        $ip,
                                        $order));
						
					
        }
        catch (Exception $ex) {
            error_log(sprintf('CFDB Error: %s:%s %s  %s', $ex->getFile(), $ex->getLine(), $ex->getMessage(), $ex->getTraceAsString()));
        }
    }

    /**
     * @param  $time string form submit time
     * @param  $formName string form name
     * @param  $fieldName string field name (should be an upload file field)
     * @return array of (file-name, file-contents) or null if not found
     */
    public function getFileFromDB($time, $formName, $fieldName) {
        global $wpdb;
        $tableName = $this->getSubmitsTableName();
        $parametrizedQuery = "SELECT `field_value`, `file` FROM `$tableName` WHERE `submit_time` = %F AND `form_name` = %s AND `field_name` = '%s'";
        $rows = $wpdb->get_results($wpdb->prepare($parametrizedQuery, $time, $formName, $fieldName));
        if ($rows == null || count($rows) == 0) {
            return null;
        }

        return array($rows[0]->field_value, $rows[0]->file);
    }

    /**
     * Install page for this plugin in WP Admin
     * @return void
     */
    public function createAdminMenu() {
        $displayName = $this->getPluginDisplayName();
        $roleAllowed = $this->getRoleOption('CanSeeSubmitData');

        //create new top-level menu
//        add_menu_page($displayName . ' Plugin Settings',
//                      'Contact Form Submissions',
//                      'administrator', //$roleAllowed,
//                      $this->getDBPageSlug(),
//                      array(&$this, 'whatsInTheDBPage'));

        // Needed for dialog in whatsInTheDBPage
        if (strpos($_SERVER['REQUEST_URI'], $this->getDBPageSlug()) !== false) {
            $pluginUrl = $this->getPluginFileUrl() . '/';
            wp_enqueue_script('jquery');
//            wp_enqueue_style('jquery-ui.css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/themes/base/jquery-ui.css');
            wp_enqueue_style('jquery-ui.css', $pluginUrl . 'jquery-ui/jquery-ui.css');
            wp_enqueue_script('jquery-ui-dialog', false, array('jquery'));
            wp_enqueue_script('CF7DBdes', $pluginUrl . 'des.js');

            // Datatables http://www.datatables.net
            if ($this->getOption('UseDataTablesJS', 'true') == 'true') {
//                wp_enqueue_style('datatables-demo', 'http://www.datatables.net/release-datatables/media/css/demo_table.css');
//                wp_enqueue_script('datatables', 'http://www.datatables.net/release-datatables/media/js/jquery.dataTables.js', array('jquery'));
                wp_enqueue_style('datatables-demo', $pluginUrl .'DataTables/media/css/demo_table.css');
                wp_enqueue_script('datatables', $pluginUrl . 'DataTables/media/js/jquery.dataTables.min.js', array('jquery'));

                if ($this->canUserDoRoleOption('CanChangeSubmitData')) {
                    do_action_ref_array('cfdb_edit_enqueue', array());
                }

                // Would like to add ColReorder but it causes slowness and display issues with DataTable footer
                //wp_enqueue_style('datatables-ColReorder', $pluginUrl .'DataTables/extras/ColReorder/media/css/ColReorder.css');
                //wp_enqueue_script('datatables-ColReorder', $pluginUrl . 'DataTables/extras/ColReorder/media/js/ColReorder.min.js', array('datatables', 'jquery'));
            }
        }

        if (strpos($_SERVER['REQUEST_URI'], $this->getSortCodeBuilderPageSlug()) !== false) {
            wp_enqueue_script('jquery');
        }

        // Put page under CF7's "Contact" page
        add_submenu_page('wpcf7',
                         $displayName . ' Submissions',
                         __('Database', 'contact-form-7-to-database-extension'),
                         $this->roleToCapability($roleAllowed),
                         $this->getDBPageSlug(),
                         array(&$this, 'whatsInTheDBPage'));

        // Put page under CF7's "Contact" page
        add_submenu_page('wpcf7',
                         $displayName . ' Short Code Builder',
                         __('Database Short Code', 'contact-form-7-to-database-extension'),
                         $this->roleToCapability($roleAllowed),
                         $this->getSortCodeBuilderPageSlug(),
                         array(&$this, 'showShortCodeBuilderPage'));
    }

    /**
     * @return string WP Admin slug for page to view DB data
     */
    public function getDBPageSlug() {
        return get_class($this) . 'Submissions';
    }

    public function getSortCodeBuilderPageSlug() {
        return get_class($this) . 'ShortCodeBuilder';
    }

    public function showShortCodeBuilderPage() {
        require_once('CFDBViewShortCodeBuilder.php');
        $view = new CFDBViewShortCodeBuilder;
        $view->display($this);
    }

    /**
     * Display the Admin page for this Plugin that show the form data saved in the database
     * @return void
     */
    public function whatsInTheDBPage() {
        require_once('CFDBViewWhatsInDB.php');
        $view = new CFDBViewWhatsInDB;
        $view->display($this);
    }

    static $checkForCustomDateFormat = true;
    static $customDateFormat = null;
    static $dateFormat = null;
    static $timeFormat = null;

    /**
     * Format input date string
     * @param  $time int same as returned from PHP time()
     * @return string formatted date according to saved options
     */
    public function formatDate($time) {
        // This method gets executed in a loop. Cache some variable to avoid
        // repeated get_option calls to the database
        if (CF7DBPlugin::$checkForCustomDateFormat) {
            if ($this->getOption('UseCustomDateTimeFormat', 'true') == 'true') {
                CF7DBPlugin::$customDateFormat = $this->getOption('SubmitDateTimeFormat', 'Y-m-d H:i:s P');
            }
            else {
               CF7DBPlugin::$dateFormat = get_option('date_format');
               CF7DBPlugin::$timeFormat = get_option('time_format');
            }
            // Convert time to local timezone
            date_default_timezone_set(get_option('timezone_string'));
            CF7DBPlugin::$checkForCustomDateFormat = false;
        }

        // Support Jalali dates but looking for wp-jalali plugin and
        // using its 'jdate' function
        if (!function_exists('is_plugin_active') && @file_exists(ABSPATH . 'wp-admin/includes/plugin.php')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        if (function_exists('is_plugin_active') && is_plugin_active('wp-jalali/wp-jalali.php')) {
            $jDateFile = WP_PLUGIN_DIR . '/wp-jalali/inc/jalali-core.php';
            if(@file_exists($jDateFile)) {
                include_once($jDateFile);
                if (function_exists('jdate')) {
                    //return jdate('l, F j, Y');
                    if (CF7DBPlugin::$customDateFormat) {
                        return jdate(CF7DBPlugin::$customDateFormat, $time);
                    }
                    else {
                        return jdate(CF7DBPlugin::$dateFormat . ' ' . CF7DBPlugin::$timeFormat, $time);
                    }
                }
            }
        }

        if (CF7DBPlugin::$customDateFormat) {
            return date(CF7DBPlugin::$customDateFormat, $time);
        }
        else {
            return date_i18n(CF7DBPlugin::$dateFormat . ' ' . CF7DBPlugin::$timeFormat, $time);
        }
    }

    /**
     * @param  $submitTime string PK for form submission
     * @param  $formName string
     * @param  $fieldName string
     * @return string URL to download file
     */
    public function getFileUrl($submitTime, $formName, $fieldName) {
        return sprintf('%s?action=cfdb-file&s=%s&form=%s&field=%s',
                       admin_url('admin-ajax.php'),
                       $submitTime,
                       urlencode($formName),
                       urlencode($fieldName));
    }

    public function getFormFieldsAjaxUrlBase() {
        return admin_url('admin-ajax.php') . '?action=cfdb-getFormFields&form=';
    }

    public function getValidateSubmitTimeAjaxUrlBase() {
        return admin_url('admin-ajax.php') . '?action=cfdb-validate-submit_time&submit_time=';
    }

    /**
     * @return array of string
     */
    public function getNoSaveFields() {
        return preg_split('/,|;/', $this->getOption('NoSaveFields'), -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @return array of string
     */
    public function getNoSaveForms() {
        return preg_split('/,|;/', $this->getOption('NoSaveForms'), -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @return array of string
     */
    public function getSaveCookies() {
        return preg_split('/,|;/', $this->getOption('SaveCookieNames'), -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @return string
     */
    public function getSubmitsTableName() {
        //        $overrideTable = $this->getOption('SubmitTableNameOverride');
        //        if ($overrideTable && "" != $overrideTable) {
        //            return $overrideTable;
        //        }
        return strtolower($this->prefixTableName('SUBMITS'));
    }

    /**
     * @return string URL to the Plugin directory. Includes ending "/"
     */
    public function getPluginDirUrl() {
        //return WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), '', plugin_basename(__FILE__));
        return $this->getPluginFileUrl('/');
    }


    /**
     * @param string $pathRelativeToThisPluginRoot points to a file with relative path from
     * this plugin's root dir. I.e. file "des.js" in the root of this plugin has
     * url = $this->getPluginFileUrl('des.js');
     * If it was in a sub-folder "js" then you would use
     *    $this->getPluginFileUrl('js/des.js');
     * @return string full url to input file
     */
    public function getPluginFileUrl($pathRelativeToThisPluginRoot = '') {
        return plugins_url($pathRelativeToThisPluginRoot, __FILE__);
    }


    /**
     * @return string URL of the language translation file for DataTables oLanguage.sUrl parameter
     * or null if it does not exist.
     */
    public function getDataTableTranslationUrl() {
        $url = null;
        $locale = get_locale();
        $i18nDir = dirname(__FILE__) . '/dt_i18n/';

        // See if there is a local file
        if (is_readable($i18nDir . $locale . '.json')) {
            $url = $this->getPluginFileUrl() . "/dt_i18n/$locale.json";
        }
        else {
            // Pull the language code from the $local string
            // which is expected to look like "en_US"
            // where the first 2 or 3 letters are for lang followed by '_'
            $lang = null;
            if (substr($locale, 2, 1) == '_') {
                // 2-letter language codes
                $lang = substr($locale, 0, 2);
            }
            else if (substr($locale, 3, 1) == '_') {
                // 3-letter language codes
                $lang = substr($locale, 0, 3);
            }
            if ($lang && is_readable($i18nDir . $lang . '.json')) {
                $url = $this->getPluginFileUrl() . "/dt_i18n/$lang.json";
            }
        }
        return $url;
    }


}
