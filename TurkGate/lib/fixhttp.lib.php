<?php
    function fix_http($url) {
        if (strlen($url) > 0 && strpos($url, "http") !== 0) {
            $url = "http://$url";
        }
		
		return $url;
	}
?>