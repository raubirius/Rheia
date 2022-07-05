<?php

$protocol = 'https';
$counterLevel = $downloadLevel = '../';
$designFilesPath = '../../design/';
$siteSectionPath = 'english/';
$siteSection = 'Faculty of Education of Trnava University in Trnava';
$downloadPath = 'fakulta;fakulta/studium;fakulta/medzinarodne;;katedry';
$contentPath = '../../content/english/';
$parsedPath = '../../parsed/english/';
$externDownParsedPath = '../../parsed/externdown/';
// $galleryPath = '../galerie';
define('counterBase', 'counter-en');

$universityBannerPath = 'en';
$facultyBannerPath = 'english/';

include_once 'english.php';
$logman = array(
	'regpath' => '../../register/',
	'linscript' => '/english/login',
	'loutscript' => '/english/logout',
	'defhome' => '/english/home',
	'defchpwd' => '/english/change-password',
);

if (!class_exists('ConfigParser', false))
	{ include_once 'ConfigParser.php'; }

new ConfigParser();

include_once 'add-common-top-menu-en.php';
include_once 'add-common-bottom-menu-en.php';
include_once 'add-common-left-menu-en.php';
// include_once 'add-common-right-menu-en.php';

?>