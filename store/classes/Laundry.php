<?php
/**
 * Laundry class.
 * clean html for html & sql injection / database inclusion
 * decode entities and strip slashes for display
 */
class Laundry{

	public static function htmlClean($html) {
		$cleaned = htmlentities($html, ENT_QUOTES, "UTF-8");
		if (!get_magic_quotes_gpc()) {
			$cleaned = addslashes($cleaned);
		}
		// add more to escape newline, null, and ctl-z
		return $cleaned;
	}

	public static function htmlDirty($html) {
    	$dirtied = html_entity_decode($html, ENT_QUOTES, "UTF-8");
    	if (!get_magic_quotes_gpc()) {
    		$dirtied = stripslashes($dirtied);
    	}
    	// add more to unescape newline, null, and ctl-z
    	return $dirtied;
    }

	public static function htmlBetter($html) {

	    $search = array('<i>', '</i>', '<b>', '</b>', '<br>', '--', '—', '–', ' "', '" ', '...');
	    $replace = array('<em>', '</em>', '<strong>', '</strong>', '<br />', '&mdash;', '&mdash;', '&ndash;', '&ldquo;', '&rdquo;', '&hellip;');
    	$html = str_replace($search, $replace, $html);

    	return $html;
    }

}
?>