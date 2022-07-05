<?php

// Adds common left menu items…
if (!class_exists('ConfigParser', false))
	{ include_once 'ConfigParser.php'; }

new ConfigParser('__left-menu-before'.
	(isSet($siteSectionPath) ? '-katedry' : ''));

?>