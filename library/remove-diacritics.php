<?php
include_once 'replace-table.php';

function removeDiacriticsFromURI($text)
{
	$return = strtr(rawurldecode($text), $GLOBALS['replaceTable']);
	$return = iconv('utf-8', 'us-ascii//TRANSLIT', $return);
	$return = preg_replace('~[^-+\~/?&#a-zA-Z0-9_\.]+~', '', $return);
	return $return;
}
?>