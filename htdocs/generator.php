<?php
include 'pdf.php';
include 'design.php';
include 'check-access.php';

if (!LoginManagement::checkAccess($params[0], 'write'))
{
	include 'no-access.php';
	exit;
}

echo '<h1>'.($title = 'Generátor HTML obsahu').'</h1>';

if (isSet($_POST) && !empty($_POST['source']))
{
	include 'RheiaMainClass.php';
	$rheiaClass = new RheiaMainClass();
	$html = $rheiaClass->generateHTML($_POST['source']);
	echo '<h2>HTML na skopírovanie</h2><form><textarea style="width: 100%; '.
		'min-height: 150px; background-color: #eee" readonly="true">'.
		htmlspecialchars('<!DOCTYPE html>'.EOL.'<html lang="sk">'.EOL.
			'<head>'.EOL.'<meta charset="UTF-8" />'.EOL.'<title>Generovaný '.
			'HTML obsah</title>'.EOL.'<link href="https://pdf.truni.sk/'.
			'design/style.css" rel="stylesheet" type="text/css" />'.EOL.
			'</head>'.EOL2.'<body>'.EOL).htmlspecialchars($html).EOL.
			htmlspecialchars('</body></html>').'</textarea></form>';
	echo '<h2>Ukážka</h2>';
	echo $html;
	echo '<p> <br /><hr /><br /> </p>';

	//*$data = $rheiaClass->getHTMLData();
	//*echo '<pre>';
	//*var_dump($data);
	//*echo '</pre>';
}

echo EOL.'<form method="POST">'.EOL.'<textarea name="source" style="width: '.
	'100%; min-height: 300px" placeholder="Vložte zdrojový text budúceho '.
	'HTML obsahu…">'.EOL.'</textarea>'.EOL.'<input type="submit" value='.
	'"Odoslať" style="float: right" />'.EOL.'</form>'.EOL;

?>