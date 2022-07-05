<?php

// Adds common left menu items…
if (!class_exists('ConfigParser', false))
	{ include_once 'ConfigParser.php'; }

if ('english/' == $siteSectionPath)
{
	new ConfigParser('__left-menu-before-en');
}
else
{
	new ConfigParser('__left-menu-before-katedry-en');
}

?>