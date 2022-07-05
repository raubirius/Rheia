<?php

$counterLevel = $downloadLevel = '../';
$designFilesPath = '../../design/';
$siteSectionPath = 'katedry/knj/';
$siteSection = 'Katedra nemeckého jazyka a literatúry PdF TU';
$downloadPath = 'fakulta;fakulta/studium;fakulta/medzinarodne;;katedry';
$contentPath = '../../content/knj/';
$parsedPath = '../../parsed/knj/';
$externDownParsedPath = '../../parsed/externdown/';
$galleryPath = 'knj/galerie';
define('counterBase', 'counter-knj');

$siteContentConfig[null]['katedryX'] = array('active' => true, 'open' => true);
$siteContentConfig[null]['knj'] = array('active' => true);
$logman['navenabled'] = false;

if (!class_exists('ConfigParser', false))
	{ include_once 'ConfigParser.php'; }

new ConfigParser();

include_once 'add-common-top-menu.php';
include_once 'add-common-bottom-menu.php';
include_once 'add-common-left-menu.php';
// include_once 'add-common-right-menu.php';

?>