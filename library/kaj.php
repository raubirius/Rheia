<?php

$counterLevel = $downloadLevel = '../';
$designFilesPath = '../../design/';
$siteSectionPath = 'katedry/kaj/';
$siteSection = 'Katedra anglického jazyka a literatúry PdF TU';
$downloadPath = 'fakulta;fakulta/studium;fakulta/medzinarodne;;katedry';
$contentPath = '../../content/kaj/';
$parsedPath = '../../parsed/kaj/';
$externDownParsedPath = '../../parsed/externdown/';
$galleryPath = 'kaj/galerie';
define('counterBase', 'counter-kaj');

$siteContentConfig[null]['katedryX'] = array('active' => true, 'open' => true);
$siteContentConfig[null]['kaj'] = array('active' => true);
$logman['navenabled'] = false;

if (!class_exists('ConfigParser', false))
	{ include_once 'ConfigParser.php'; }

new ConfigParser();

include_once 'add-common-top-menu.php';
include_once 'add-common-bottom-menu.php';
include_once 'add-common-left-menu.php';
// include_once 'add-common-right-menu.php';

?>