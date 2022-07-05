<?php
include 'pdf.php';
// header('Content-Type: text/html; charset=utf-8');
include 'design.php';
include 'check-access.php';
echo '<h1>'.($title = 'Nezrovnalosti').'</h1>';
ob_start();
include 'inconsistency.php';
getAllItems(); walk();
showResult(array('actafp/', 'astu/', 'dsz/', 'fakulta/english/', 'jop/',
	'katedry/', 'kt8g/', 'puz/', 'vsr/', 'zbornik/', 'manual/'));
$inconsistency = ob_get_clean();
echo '<pre>'.EOL.htmlspecialchars($inconsistency).EOL.'</pre>';
?>