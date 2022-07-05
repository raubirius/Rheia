<?php
include_once 'remove-diacritics.php';

if (($newName = ExternalRedirects::replace(removeDiacriticsFromURI(
	$_SERVER['REQUEST_URI']))) !== $_SERVER['REQUEST_URI'])
{
	// Samples:
	// http://localhost/uch%C3%A1dza%C4%8D?prij%C3%ADmacie-konanie&inform%C3%A1cie#bakalárske
	// http://localhost/uchadzac?prijimacie-konanie&informacie#bakalárske
	// echo '<pre>'; var_dump($_SERVER);//['REQUEST_URI']);
	// var_dump($newName); echo '</pre>';
	header('Location: '.$newName);
	include '301.php'; // obsahuje header 301
	die();
}
else if (($newName = ExternalRedirects::replace(strtolower(
	$_SERVER['REQUEST_URI']))) !== $_SERVER['REQUEST_URI'])
{
	// Samples:
	// http://localhost/~Jakubovska
	// https://pdf.truni.sk/~Jakubovska
	// echo '<pre>'; var_dump($_SERVER);//['REQUEST_URI']);
	// var_dump($newName); echo '</pre>';
	header('Location: '.$newName);
	include '301.php'; // obsahuje header 301
	die();
}


header('HTTP/1.1 404 Not Found'); ?><h1 class="error"><?php if (isSet($GLOBALS['designTexts']) && !empty($GLOBALS['designTexts']['error-404-head'])) echo $GLOBALS['designTexts']['error-404-head']; else echo 'Chýbajúci dokument'; ?></h1>
<p class="error"><?php if (isSet($GLOBALS['designTexts']) && !empty($GLOBALS['designTexts']['error-404-text'])) echo $GLOBALS['designTexts']['error-404-text']; else echo 'Ľutujeme, ale požadovaný obsah nie je k dispozícii.'; ?></p>
<p><?php if (isSet($GLOBALS['designTexts']) && !empty($GLOBALS['designTexts']['error-404-desc'])) echo $GLOBALS['designTexts']['error-404-desc']; else echo 'Váš odkaz môže byť zastaraný. Skúste získať aktuálny odkaz alebo vyberte možnosť z hlavnej, prípadne vedľajšej ponuky.'; ?></p>
<!-- Not found – The document that has been requested either no longer exists, or has never existed on the server. -->