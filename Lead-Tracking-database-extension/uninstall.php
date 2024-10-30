<?php
/*
    
    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

require_once('CF7DBPlugin.php');
$aPlugin = new CF7DBPlugin();
$aPlugin->uninstall();

