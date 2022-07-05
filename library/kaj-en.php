<?php

$counterLevel = $downloadLevel = '../../';
$designFilesPath = '../../../design/';
$siteSectionPath = 'katedry/kaj/english/';
$siteSection = 'The Department of English Language and Literature FoE TU';
$downloadPath = 'fakulta;fakulta/studium;fakulta/medzinarodne;;katedry';
$contentPath = '../../../content/kaj/english/';
$parsedPath = '../../../parsed/kaj/english/';
$externDownParsedPath = '../../../parsed/externdown/';
$galleryPath = 'kaj/galerie';
$galleryNavigate = 'kaj/english/galleries';
define('counterBase', 'counter-kaj');

$universityBannerPath = 'en';
$facultyBannerPath = 'english/';

$siteContentConfig[null]['departmentsX'] =
	array('active' => true, 'open' => true);
$siteContentConfig[null]['kaj'] = array('active' => true);
include_once 'english.php';
$logman['navenabled'] = false;

if (!class_exists('ConfigParser', false))
	{ include_once 'ConfigParser.php'; }

new ConfigParser();

include_once 'add-common-top-menu-en.php';
include_once 'add-common-bottom-menu-en.php';
include_once 'add-common-left-menu-en.php';
// include_once 'add-common-right-menu-en.php';

?>