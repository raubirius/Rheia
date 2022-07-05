<?php

$protocol = 'https';
$downloadPath = 'fakulta;fakulta/studium;fakulta/medzinarodne;;katedry';
define('counterBase', 'counter');

if (!class_exists('ConfigParser', false))
	{ include_once 'ConfigParser.php'; }

new ConfigParser();

include_once 'add-common-top-menu.php';
include_once 'add-common-bottom-menu.php';
include_once 'add-common-left-menu.php';
// include_once 'add-common-right-menu.php';

?>