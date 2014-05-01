<?php
	function brafton_log($message) {
    if (get_option('brafton_errors') == 'on' ) {
        if (is_array($message) || is_object($message)) {
            error_log(print_r($message, true));
        } else {
            error_log($message);
        }
    }
}
?>