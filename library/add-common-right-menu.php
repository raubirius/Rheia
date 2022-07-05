<?php

// Adds common right menu items…
if (!class_exists('ConfigParser', false))
	{ include_once 'ConfigParser.php'; }

new ConfigParser('__right-menu-before'.
	(isSet($siteSectionPath) ? '-katedry' : ''));
new ConfigParser('__right-menu-after');

?>