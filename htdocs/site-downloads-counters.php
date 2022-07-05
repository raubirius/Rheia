<?php
include 'pdf.php';
include 'design.php';
$counterBase = '../counter/downloads';
$designTexts['site-counters'] = 'Hodnoty počítadiel prevzatí súborov z webového sídla';

$filterURI = array('~http~');
$repairURI = array(
	'~[/\.]$~' => '',
	'~^/download\?~' => '',
	'~^/~' => '',
	'~^katedry/~' => '',
	);

$preg_match_uri_filter = '~^([a-zA-Z\-]+$|[^/]+(?=/))~';
$scriptBase = 'site-downloads-counters';

include 'site-counters.php';
?>