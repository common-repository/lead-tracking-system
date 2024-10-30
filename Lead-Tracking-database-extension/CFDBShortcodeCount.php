<?php
/*

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

require_once('CFDBShortcodeValue.php');

class CFDBShortcodeCount extends CFDBShortcodeValue {

    public function handleShortcode($atts) {
        $atts['function'] = 'count';
        unset($atts['show']);
        unset($atts['hide']);
        return parent::handleShortcode($atts);
    }
}
