<?php
include_once 'parse-uri.php';
$filename = '../downloads/html/'.$selectedItem.
	(empty($selectedItem) ? '' : '/').$argument.'.html';

if (file_exists($filename))
{
	echo file_get_contents($filename);
}
else
{
	echo '<!DOCTYPE html>'.EOL.'<html lang="sk">'.EOL.'<head>'.EOL.
		'<meta charset="UTF-8" />'.EOL.'<title>Chyba</title>'.EOL.
		'</head>'.EOL.'<body>'.EOL;
	include '404.php';

	// echo '<p>Category: '.$category.'</p>';
	// echo '<p>Selected item: '.$selectedItem.'</p>';
	// echo '<p>Argument: '.$argument.'</p>';
	// $exists = file_exists($filename);
	// echo '<p>File name: '.print_r($filename, true).'</p>';
	// echo '<p>Exists: '.print_r($exists, true).'</p>';

	echo '</body>'.EOL.'</html>';
}
?>