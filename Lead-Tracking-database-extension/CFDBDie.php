<?php
/*
    
    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

class CFDBDie {

    /**
     * Why this function? It is meant to do what wp_die() does. But in
     * Ajax mode, wp_die just does die(-1). But in this plugin we are leveraging
     * Ajax mode to put in URL hooks to do exports. So it is not really making a in-page
     * call to the url, the full page is navigating to it, then it downloads a CSV file for
     * example. So if there are errors we want the wp_die() error page. So this
     * function is a copy of wp_die without the Ajax mode check.
     * @static
     * @param string $message HTML
     * @param string $title HTML Title
     * @param array $args see wp_die
     * @return void
     */
    static function wp_die($message, $title = '', $args = array()) {
        // Code copied from wp_die without it stopping due to AJAX
        if ( function_exists( 'apply_filters' ) ) {
            $function = apply_filters( 'wp_die_handler', '_default_wp_die_handler');
        } else {
            $function = '_default_wp_die_handler';
        }
        call_user_func( $function, $message, $title, $args );
    }
}
