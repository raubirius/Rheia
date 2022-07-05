<?php

$counterLevel = $downloadLevel = '../';
$designFilesPath = '../../design/';
$siteSectionPath = 'katedry/kch/';
$siteSection = 'Katedra chémie PdF TU';
$downloadPath = 'fakulta;fakulta/studium;fakulta/medzinarodne;;katedry';
$contentPath = '../../content/kch/';
$parsedPath = '../../parsed/kch/';
$externDownParsedPath = '../../parsed/externdown/';
$galleryPath = 'kch/galerie';
define('counterBase', 'counter-kch');

$siteContentConfig[null]['katedryX'] = array('active' => true, 'open' => true);
$siteContentConfig[null]['kch'] = array('active' => true);
$logman['navenabled'] = false;

if (!class_exists('ConfigParser', false))
	{ include_once 'ConfigParser.php'; }

new ConfigParser();

include_once 'add-common-top-menu.php';
include_once 'add-common-bottom-menu.php';
include_once 'add-common-left-menu.php';
// include_once 'add-common-right-menu.php';

?>