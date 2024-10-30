<?php
/*

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

require_once('CFDBExport.php');

class ExportToIqy implements CFDBExport {

    public function export($formName, $options = null) {
        header('Content-Type: text/x-ms-iqy');
        header("Content-Disposition: attachment; filename=\"$formName.iqy\"");

        $url = get_bloginfo('url');
        $encFormName = urlencode($formName);
        $uri = "?action=cfdb-export&form=$encFormName&enc=HTML";
        if (isset($options['search'])) {
           $uri = $uri . '&search=' . urlencode($options['search']);
        }
        $encRedir = urlencode('wp-admin/admin-ajax.php' . $uri);

        // To get this to work right, we have to submit to the same page that the login form does and post
        // the same parameters that the login form does. This includes "log" and "pwd" for the login and
        // also "redirect_to" which is the URL of the page where we want to end up including a "form_name" parameter
        // to tell that final page to select which contact form data is to be displayed.
        //
        // "Selection=1" references the 1st HTML table in the page which is the data table.
        // "Formatting" can be "None", "All", or "RTF"
        echo (
"WEB
1
$url/wp-login.php?redirect_to=$encRedir
log=[\"Username for $url\"]&pwd=[\"Password for $url\"]

Selection=1
Formatting=All
PreFormattedTextToColumns=True
ConsecutiveDelimitersAsOne=True
SingleBlockTextImport=False
DisableDateRecognition=False
DisableRedirections=False
");
    }
}
