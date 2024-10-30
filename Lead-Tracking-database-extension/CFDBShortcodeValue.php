<?php
/*
    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

require_once('ShortCodeLoader.php');

class CFDBShortcodeValue extends ShortCodeLoader {

    /**
     * @param  $atts array of short code attributes
     * @return string value submitted to a form field as selected by $atts. See ExportToValue.php
     */
    public function handleShortcode($atts) {
        if (isset($atts['form'])) {
            $atts['fromshortcode'] = true;
            require_once('ExportToValue.php');
            $export = new ExportToValue();
            return $export->export($atts['form'], $atts);
        }
    }

}
