<?php
/*

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

interface CF7DBValueConverter {

    /**
     * @abstract
     * @param  $value mixed object to convert
     * @return mixed converted value
     */
    public function convert($value);
}
