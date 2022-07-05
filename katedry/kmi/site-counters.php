<?php
include 'kmi.php';
include 'design.php';

$filterURI = array(
	'~^/'.$siteSectionPath.'\?~',
	'~@@version~',
	'~[:%=]~', '~http~',
	'~\.\.~'
	);

$repairURI = array(
	'~^/'.$siteSectionPath.'~' => '/',
	'~/[/]+~' => '/',
	'~[\.]+\b(html|htm|php)\b~' => '',
	'~[/\.\?]$~' => '',
	'~^$~' => '/',
	);

include 'site-counters.php';
?>