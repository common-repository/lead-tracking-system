<?php
/*

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

class CF7DBPluginExporter {

    static function doExportFromPost() {

        // Consolidate GET and POST parameters. Allow GET to override POST.
        $params = array_merge($_POST, $_GET);

        //print_r($params);

        if (!isset($params['form'])) {
            include_once('CFDBDie.php');
            CFDBDie::wp_die(__('Error: No "form" parameter submitted', 'contact-form-7-to-database-extension'));
            return;
        }

        // Assumes coming from CF7DBPlugin::whatsInTheDBPage()
        $key = '3fde789a'; //substr($_COOKIE['PHPSESSID'], - 5); // session_id() doesn't work
        if (isset($params['guser'])) {
            $params['guser'] = mcrypt_decrypt(MCRYPT_3DES, $key, CF7DBPluginExporter::hexToStr($params['guser']), 'ecb');
        }
        if (isset($params['gpwd'])) {
            $params['gpwd'] = mcrypt_decrypt(MCRYPT_3DES, $key, CF7DBPluginExporter::hexToStr($params['gpwd']), 'ecb');
        }

        if (!isset($params['enc'])) {
            $params['enc'] = 'CSVUTF8';
        }
        CF7DBPluginExporter::export(
            $params['form'],
            $params['enc'],
            $params);
    }

// Taken from http://ditio.net/2008/11/04/php-string-to-hex-and-hex-to-string-functions/
    static function hexToStr($hex) {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }


    static function export($formName, $encoding, $options) {

        switch ($encoding) {
            case 'HTML':
                require_once('ExportToHtmlTable.php');
                $exporter = new ExportToHtmlTable();
                $exporter->export($formName, $options);
                break;
            case 'DT':
                require_once('ExportToHtmlTable.php');
                if (!is_array($options)) {
                    $options = array();
                }
                $options['useDT'] = true;
                if (!isset($options['printScripts'])) {
                    $options['printScripts'] = true;
                }
                if (!isset($options['printStyles'])) {
                    $options['printStyles'] = 'true';
                }
                $exporter = new ExportToHtmlTable();
                $exporter->export($formName, $options);
                break;
            case 'HTMLTemplate':
                require_once('ExportToHtmlTemplate.php');
                $exporter = new ExportToHtmlTemplate();
                $exporter->export($formName, $options);
                break;
            case 'IQY':
                require_once('ExportToIqy.php');
                $exporter = new ExportToIqy();
                $exporter->export($formName, $options);
                break;
            case 'CSVUTF8BOM':
                $options['unbuffered'] = 'true';
                require_once('ExportToCsvUtf8.php');
                $exporter = new ExportToCsvUtf8();
                $exporter->setUseBom(true);
                $exporter->export($formName, $options);
                break;
            case 'TSVUTF16LEBOM':
                $options['unbuffered'] = 'true';
                require_once('ExportToCsvUtf16le.php');
                $exporter = new ExportToCsvUtf16le();
                $exporter->export($formName, $options);
                break;
            case 'GLD':
                require_once('ExportToGoogleLiveData.php');
                $exporter = new ExportToGoogleLiveData();
                $exporter->export($formName, $options);
                break;
            case 'GSS':
                $options['unbuffered'] = 'true';
                require_once('ExportToGoogleSS.php');
                $exporter = new ExportToGoogleSS();
                $exporter->export($formName, $options);
                break;
            case 'JSON':
                require_once('ExportToJson.php');
                $exporter = new ExportToJson();
                $exporter->export($formName, $options);
                break;
            case 'VALUE':
                require_once('ExportToValue.php');
                $exporter = new ExportToValue();
                $exporter->export($formName, $options);
                break;
            case 'COUNT':
                require_once('ExportToValue.php');
                if (!is_array($options)) {
                    $options = array();
                }
                $options['function'] = 'count';
                unset($options['show']);
                unset($options['hide']);
                $exporter = new ExportToValue();
                $exporter->export($formName, $options);
                break;
            case 'CSVUTF8':
            default:
                require_once('ExportToCsvUtf8.php');
                $exporter = new ExportToCsvUtf8();
                $exporter->setUseBom(false);
                $exporter->export($formName, $options);
                break;
        }
    }
}
