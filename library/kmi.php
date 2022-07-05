<?php

$counterLevel = $downloadLevel = '../';
$designFilesPath = '../../design/';
$siteSectionPath = 'katedry/kmi/';
$siteSection = 'Katedra matematiky a informatiky PdF TU';
$downloadPath = 'fakulta;fakulta/studium;fakulta/medzinarodne;;katedry';
$contentPath = '../../content/kmi/';
$parsedPath = '../../parsed/kmi/';
$externDownParsedPath = '../../parsed/externdown/';
$galleryPath = 'kmi/galerie';
define('counterBase', 'counter-kmi');

$siteContentConfig[null]['katedryX'] = array('active' => true, 'open' => true);
$siteContentConfig[null]['kmi'] = array('active' => true);
$logman['navenabled'] = false;

if (!class_exists('ConfigParser', false))
	{ include_once 'ConfigParser.php'; }

new ConfigParser();

include_once 'add-common-top-menu.php';
include_once 'add-common-bottom-menu.php';
include_once 'add-common-left-menu.php';
// include_once 'add-common-right-menu.php';

?>