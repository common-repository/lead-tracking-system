<?php
/*
    
    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

require_once('ShortCodeLoader.php');

class CFDBShortcodeJson extends ShortCodeLoader {

    /**
     * @param  $atts array of short code attributes
     * @return string JSON. See ExportToJson.php
     */
    public function handleShortcode($atts) {
        if (isset($atts['form'])) {
            $atts['html'] = true;
            $atts['fromshortcode'] = true;
            require_once('ExportToJson.php');
            $export = new ExportToJson();
            return $export->export($atts['form'], $atts);
        }
    }
}
