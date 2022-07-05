<?php

$counterLevel = $downloadLevel = '../../';
$designFilesPath = '../../../design/';
$siteSectionPath = 'katedry/kmi/english/';
$siteSection = 'The Department of Mathematics and Computer Science FoE TU';
$downloadPath = 'fakulta;fakulta/studium;fakulta/medzinarodne;;katedry';
$contentPath = '../../../content/kmi/english/';
$parsedPath = '../../../parsed/kmi/english/';
$externDownParsedPath = '../../../parsed/externdown/';
$galleryPath = 'kmi/galerie';
$galleryNavigate = 'kmi/english/galleries';
define('counterBase', 'counter-kmi');

$universityBannerPath = 'en';
$facultyBannerPath = 'english/';

$siteContentConfig[null]['departmentsX'] =
	array('active' => true, 'open' => true);
$siteContentConfig[null]['kmi'] = array('active' => true);
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