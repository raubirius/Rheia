<?php // Sample: http://localhost/remove-diacritics?bakalárske
include_once 'remove-diacritics.php';
echo removeDiacriticsFromURI($_SERVER['QUERY_STRING']);
?>