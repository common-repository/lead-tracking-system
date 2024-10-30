<?php
/*
   

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

require_once('ShortCodeLoader.php');

class CFDBShortcodeExportUrl extends ShortCodeLoader {

    /**
     * @param  $atts array of short code attributes
     * @return string JSON. See ExportToJson.php
     */
    public function handleShortcode($atts) {
        $params = array();
        $params[] = admin_url('admin-ajax.php');
        $params[] = '?action=cfdb-export';
        if (isset($atts['form'])) {
            $params[] = '&form=' . urlencode($atts['form']);
        }
        if (isset($atts['show'])) {
            $params[] = '&show=' . urlencode($atts['show']);
        }
        if (isset($atts['hide'])) {
            $params[] = '&hide=' . urlencode($atts['hide']);
        }
        if (isset($atts['limit'])) {
            $params[] = '&limit=' . urlencode($atts['limit']);
        }
        if (isset($atts['search'])) {
            $params[] = '&search=' . urlencode($atts['search']);
        }
        if (isset($atts['filter'])) {
            $params[] = '&filter=' . urlencode($atts['filter']);
        }
        if (isset($atts['enc'])) {
            $params[] = '&enc=' . urlencode($atts['enc']);
        }

        $url = implode($params);

        if (isset($atts['urlonly']) && $atts['urlonly'] == 'true') {
            return $url;
        }

        $linkText = __('Export', 'contact-form-7-to-database-extension');
        if (isset($atts['linktext'])) {
            $linkText = $atts['linktext'];
        }

        return sprintf('<a href="%s">%s</a>', $url, $linkText);
    }
}
