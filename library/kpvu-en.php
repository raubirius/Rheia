<?php

$counterLevel = $downloadLevel = '../../';
$designFilesPath = '../../../design/';
$siteSectionPath = 'katedry/kpvu/english/';
$siteSection = 'The Department of Art Education FoE TU';
$downloadPath = 'fakulta;fakulta/studium;fakulta/medzinarodne;;katedry';
$contentPath = '../../../content/kpvu/english/';
$parsedPath = '../../../parsed/kpvu/english/';
$externDownParsedPath = '../../../parsed/externdown/';
$galleryPath = 'kpvu/galerie';
$galleryNavigate = 'kpvu/english/galleries';
define('counterBase', 'counter-kpvu');

$universityBannerPath = 'en';
$facultyBannerPath = 'english/';

$siteContentConfig[null]['departmentsX'] =
	array('active' => true, 'open' => true);
$siteContentConfig[null]['kpvu'] = array('active' => true);
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