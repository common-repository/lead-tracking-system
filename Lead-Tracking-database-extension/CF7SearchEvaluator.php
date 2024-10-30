<?php
/*
   
    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

include_once('CF7DBEvalutator.php');

class CF7SearchEvaluator implements CF7DBEvalutator {

    var $search;

    public function setSearch($search) {
        $this->search = strtolower($search);
    }

    /**
     * Evaluate expression against input data. This is intended to mimic the search field on DataTables
     * @param  $data array [ key => value]
     * @return boolean result of evaluating $data against expression
     */
    public function evaluate(&$data) {
        if (!$this->search) {
            return true;
        }
        foreach ($data as $key => $value) {
            // Any field can match, case insensitive
            if (false !== strrpos(strtolower($value), $this->search)) {
                return true;
            }
        }
        return false;
    }

}
