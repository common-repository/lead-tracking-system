<?php
/*
    

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * This class is an API for accessing a form and looping through its contents.
 * Common shortcode options can be used to show/hide columns, search/filter fields, limit, orderby etc.
 * Set them in the input $options array.
 * Example code:
 * <code>
 *    // Email all the "Mike"'s
 *    require_once(ABSPATH . 'wp-content/plugins/contact-form-7-to-database-extension/CFDBFormIterator.php');
 *    $exp = new CFDBFormIterator();
 *    $exp->export('my-form', new array('show' => 'name,email', 'search' => 'mike'));
 *    while ($row = $exp->nextRow()) {
 *       wp_mail($row['email'], 'Hello ' . $row['name], 'How are you doing?');
 *    }
 *
 * </code>
 */
require_once('ExportBase.php');
require_once('CFDBExport.php');

class CFDBFormIterator extends ExportBase implements CFDBExport {

    /**
     * @var string
     */
    var $formName;


    /**
     * @var CF7DBPlugin
     */
    var $plugin;

    /**
     * Intended to be used by people who what to programmatically loop over the rows
     * of a form.
     * @param $formName string
     * @param $options array of option_name => option_value
     * @return void
     */
    public function export($formName, $options = null) {
        $this->formName = $formName;
        $this->setOptions($options);
        $this->setCommonOptions();
        $this->setDataIterator($formName);
    }

    /**
     * @return array|bool associative array of the row values or false if no more row exists
     */
    public function nextRow() {
        if ($this->dataIterator->nextRow()) {
            $row = array();
            $row['submit_time'] = $this->dataIterator->row['submit_time'];

            $fields_with_file = null;
            if (isset($this->dataIterator->row['fields_with_file']) &&
                $this->dataIterator->row['fields_with_file'] != null) {
                $fields_with_file = explode(',', $this->dataIterator->row['fields_with_file']);
                if ($this->plugin == null) {
                    require_once('CF7DBPlugin.php');
                    $this->plugin = new CF7DBPlugin();
                }
            }

            foreach ($this->dataIterator->displayColumns as $aCol) {
                $row[$aCol] = $this->dataIterator->row[$aCol];
             

                // If it is a file, add in the URL for it by creating a field name appended with '_URL'
                if ($fields_with_file && in_array($aCol, $fields_with_file)) {
                    $row[$aCol . '_URL'] = $this->plugin->getFileUrl($row['submit_time'], $this->formName, $aCol);
                }
            }
            return $row;
        }
        else {
            return false;
        }
    }
}
