<?php

$counterLevel = $downloadLevel = '../../';
$designFilesPath = '../../../design/';
$siteSectionPath = 'katedry/ksp/english/';
$siteSection = 'The Department of School Education FoE TU';
$downloadPath = 'fakulta;fakulta/studium;fakulta/medzinarodne;;katedry';
$contentPath = '../../../content/ksp/english/';
$parsedPath = '../../../parsed/ksp/english/';
$externDownParsedPath = '../../../parsed/externdown/';
$galleryPath = 'ksp/galerie';
$galleryNavigate = 'ksp/english/galleries';
define('counterBase', 'counter-ksp');

$universityBannerPath = 'en';
$facultyBannerPath = 'english/';

$siteContentConfig[null]['departmentsX'] =
	array('active' => true, 'open' => true);
$siteContentConfig[null]['ksp'] = array('active' => true);
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