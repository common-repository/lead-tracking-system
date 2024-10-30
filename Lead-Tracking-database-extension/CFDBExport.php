<?php
/*
   

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

interface CFDBExport {

    /**
     * @abstract
     * @param $formName string
     * @param $options array of option_name => option_value
     * @return void
     */
    public function export($formName, $options = null);

}
