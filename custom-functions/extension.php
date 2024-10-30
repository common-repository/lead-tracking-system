<?php
/*
 * Plugin Name: MyCustomFunctions
 * Plugin URI: http://yoursite.com
 * Description: This is an awesome custom plugin with functionality that I'd like to keep when switching things.
 * Author: YourName
 * Author URI: http://yoursite.com
 * Version: 0.1.0
 */

/* Place custom code below this line. */

function hiddenreferer_shortcode($tag) {

    if ( ! is_array( $tag ) )
        return '';

    $options = (array) $tag['options'];
    foreach ( $options as $option ) {
        if ( preg_match( '%^name:([-0-9a-zA-Z_]+)$%', $option, $matches ) ) {
            $name_att = $matches[1];
        }
    }

     $pageURL = 'http';
     if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
     $pageURL .= "://";
     if ($_SERVER["SERVER_PORT"] != "80") {
          $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
     } else {
          $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
     }

     $value_att = $pageURL;
	 $ref="<script> document.write(window.referrer); </script>";
     $html = '<input type="hidden" name="' . $name_att . '" value="'.$value_att.'" /> ';
    $html.=' <input type="hidden" id="referer" name="' . 'referer' . '" value="'.$ref.'" /> ';
	$html.=" <script>function abc(){document.getElementById('referer').value=document.referrer;} </script>";
	
	$html.="<script> jQuery(document).ready(function(){ abc();}); </script>";
	return $html;
}
wpcf7_add_shortcode('hiddenreferer', 'hiddenreferer_shortcode', true);





/* Place custom code above this line. */
?>
