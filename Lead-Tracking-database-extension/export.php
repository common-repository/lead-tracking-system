<?php
/*

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * @deprecated This file is deprecated as of version 1.8.
 * But it remains here for backward compatibility with Excel IQuery and Google Live Data
 * exports from older versions which reference this file directly via URL.
 */

include_once('../../../wp-load.php');
require_wp_db();

require_once('CF7DBPluginExporter.php');
CF7DBPluginExporter::doExportFromPost();
