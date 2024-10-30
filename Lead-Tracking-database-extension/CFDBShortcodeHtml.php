<?php
/*
    

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

require_once('ShortCodeLoader.php');

class CFDBShortcodeHtml extends ShortCodeLoader {

    /**
     * @param $atts array of short code attributes
     * @param $content string contents inside the shortcode tag
     * @return string value submitted to a form field as selected by $atts. See ExportToValue.php
     */
    public function handleShortcode($atts, $content = null) {
        if ($content && isset($atts['form'])) {
            $atts['fromshortcode'] = true;
            $atts['content'] = $content;
            require_once('ExportToHtmlTemplate.php');
            $export = new ExportToHtmlTemplate();
            return $export->export($atts['form'], $atts);
        }
    }

}
