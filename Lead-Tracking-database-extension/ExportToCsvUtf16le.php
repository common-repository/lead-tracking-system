<?php
/*
    

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

require_once('ExportBase.php');
require_once('CFDBExport.php');

class ExportToCsvUtf16le extends ExportBase implements CFDBExport {

    public function export($formName, $options = null) {
        $this->setOptions($options);
        $this->setCommonOptions();

        // Security Check
        if (!$this->isAuthorized()) {
            $this->assertSecurityErrorMessage();
            return;
        }

        // Headers
        $this->echoHeaders(
            array('Content-Type: text/csv; charset=UTF-16LE',
                 "Content-Disposition: attachment; filename=\"$formName.csv\""));
        // todo: make this work
//        $fileName = $formName . '.csv';
//        $fileName = $this->encodeWordRfc2231($formName) . '.csv';
//        header("Content-Disposition: attachment; filename*=UTF-8''$fileName");

        //Bytes FF FE (UTF-16LE BOM)
        echo chr(255) . chr(254);
        $eol = $this->encode(utf8_encode("\n"));
        $delimiter = $this->encode(utf8_encode("\t"));

        // Query DB for the data for that form
        $submitTimeKeyName = 'Submit_Time_Key';
        $this->setDataIterator($formName, $submitTimeKeyName);

        // Column Headers
        foreach ($this->dataIterator->displayColumns as $aCol) {
            echo $this->prepareCsvValue($aCol);
            echo $delimiter;
        }
        echo $eol;

        // Rows
        $showFileUrlsInExport = $this->plugin->getOption('ShowFileUrlsInExport') == 'true';
        while ($this->dataIterator->nextRow()) {
            $fields_with_file = null;
            if ($showFileUrlsInExport &&
                    isset($this->dataIterator->row['fields_with_file']) &&
                    $this->dataIterator->row['fields_with_file'] != null) {
                $fields_with_file = explode(',', $this->dataIterator->row['fields_with_file']);
            }
            foreach ($this->dataIterator->displayColumns as $aCol) {
                $cell = isset($this->dataIterator->row[$aCol]) ? $this->dataIterator->row[$aCol] : '';
                if ($showFileUrlsInExport &&
                        $fields_with_file &&
                        $cell &&
                        in_array($aCol, $fields_with_file)) {
                    $cell = $this->plugin->getFileUrl($this->dataIterator->row[$submitTimeKeyName], $formName, $aCol);
                }
                echo $this->prepareCsvValue($cell);
                echo $delimiter;
            }
            echo $eol;
        }
    }


    protected function &prepareCsvValue($text) {
        // Excel does not like \n characters in UTF-16LE, so we replace with a space
        $text = str_replace("\n", ' ', $text);

        // In CSV, escape double-quotes by putting two double quotes together
        $text = str_replace('"', '""', $text);

        // Quote it to escape delimiters
        $text = '"' . $text . '"';

        // Encode UTF-16LE
        $text = $this->encode($text);

        return $text;
    }

    protected function encode($text) {
        return mb_convert_encoding($text, 'UTF-16LE', 'UTF-8');
    }

    protected function &encodeWordRfc2231($word) {
        $binArray = unpack("C*", $word);
        $hex = '';
        foreach ($binArray as $chr) {
            $hex .= '%' . sprintf("%02X", base_convert($chr, 2, 16));
        }
        return $hex;
    }

}
