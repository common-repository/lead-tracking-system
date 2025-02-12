<?php
/*

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

require_once('ShortCodeLoader.php');

class CFDBShortcodeTable extends ShortCodeLoader {

    /**
     * Shortcode callback for writing the table of form data. Can be put in a page or post to show that data.
     * Shortcode options:
     * [cfdb-table form="your-form"]                             (shows the whole table with default options)
     * Controlling the Display: Apply your CSS to the table; set the table's 'class' or 'id' attribute:
     * [cfdb-table form="your-form" class="css_class"]           (outputs <table class="css_class"> (default: class="cf7-db-table")
     * [cfdb-table form="your-form" id="css_id"]                 (outputs <table id="css_id"> (no default id)
     * [cfdb-table form="your-form" id="css_id" class="css_class"] (outputs <table id="css_id" class="css_class">
     * Filtering Columns:
     * [cfdb-table form="your-form" show="field1,field2,field3"] (optionally show selected fields)
     * [cfdb-table form="your-form" hide="field1,field2,field3"] (optionally hide selected fields)
     * [cfdb-table form="your-form" show="f1,f2,f3" hide="f1"]   (hide trumps show)
     * Filtering Rows:
     * [cfdb-table form="your-form" filter="field1=value1"]      (show only rows where field1=value1)
     * [cfdb-table form="your-form" filter="field1!=value1"]      (show only rows where field1!=value1)
     * [cfdb-table form="your-form" filter="field1=value1&&field2!=value2"] (Logical AND the filters using '&&')
     * [cfdb-table form="your-form" filter="field1=value1||field2!=value2"] (Logical OR the filters using '||')
     * [cfdb-table form="your-form" filter="field1=value1&&field2!=value2||field3=value3&&field4=value4"] (Mixed &&, ||)
     * @param  $atts array of short code attributes
     * @return HTML output of shortcode
     */
    public function handleShortcode($atts) {
        if (isset($atts['form'])) {
            $atts['canDelete'] = false;
            $atts['fromshortcode'] = true;
            require_once('ExportToHtmlTable.php');
            $export = new ExportToHtmlTable();
            return $export->export($atts['form'], $atts);
        }
    }

}
