<?php
/*
    
    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

abstract class CFDBView {

    /**
     * @abstract
     * @param  $plugin CF7DBPlugin
     * @return void
     */
    abstract function display(&$plugin);

    protected function pageHeader(&$plugin) {
        $this->sponsorLink($plugin);
        $this->headerLinks();
    }


    /**
     * @param $plugin CF7DBPlugin
     * @return void
     */
    protected function sponsorLink(&$plugin) {
    }

    protected function headerLinks() {
        ?>
    <table style="width:100%;">
        <tbody>
        <tr>
            <td width="25%" style="font-size:x-small;">
                <a target="_donate"
                   href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=NEVDJ792HKGFN&lc=US&item_name=Wordpress%20Plugin&item_number=cf7%2dto%2ddb%2dextension&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted">
                    <img src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" alt="Donate">
                </a>
            </td>
            <td width="25%" style="font-size:x-small;">
                <a target="_cf7todb"
                   href="http://wordpress.org/extend/plugins/contact-form-7-to-database-extension">
                    <?php _e('Rate this Plugin', 'contact-form-7-to-database-extension') ?>
                </a>
            </td>
            <td width="25%" style="font-size:x-small;">
                <a target="_cf7todb"
                   href="http://cfdbplugin.com/">
                    <?php _e('Documentation', 'contact-form-7-to-database-extension') ?>
                </a>
            </td>
            <td width="25%" style="font-size:x-small;">
                <a target="_cf7todb"
                   href="http://wordpress.org/tags/contact-form-7-to-database-extension">
                    <?php _e('Support', 'contact-form-7-to-database-extension') ?>
                </a>
            </td>
        </tr>
        </tbody>
    </table>
    <?php

    }
}
