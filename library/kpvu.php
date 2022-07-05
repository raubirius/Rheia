<?php

$counterLevel = $downloadLevel = '../';
$designFilesPath = '../../design/';
$siteSectionPath = 'katedry/kpvu/';
$siteSection = 'Katedra pedagogiky výtvarného umenia PdF TU';
$downloadPath = 'fakulta;fakulta/studium;fakulta/medzinarodne;;katedry';
$contentPath = '../../content/kpvu/';
$parsedPath = '../../parsed/kpvu/';
$externDownParsedPath = '../../parsed/externdown/';
$galleryPath = 'kpvu/galerie';
define('counterBase', 'counter-kpvu');

$siteContentConfig[null]['katedryX'] = array('active' => true, 'open' => true);
$siteContentConfig[null]['kpvu'] = array('active' => true);
$logman['navenabled'] = false;

if (!class_exists('ConfigParser', false))
	{ include_once 'ConfigParser.php'; }

new ConfigParser();

include_once 'add-common-top-menu.php';
include_once 'add-common-bottom-menu.php';
include_once 'add-common-left-menu.php';
// include_once 'add-common-right-menu.php';

?>