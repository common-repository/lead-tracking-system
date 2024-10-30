<?php
/*
    

   


    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

interface CF7DBEvalutator {

    /**
     * Evaluate expression against input data
     * @param  $data array [ key => value]
     * @return boolean result of evaluating $data against expression
     */
    public function evaluate(&$data);

}
