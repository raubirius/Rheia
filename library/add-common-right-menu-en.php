<?php

// Adds common right menu items…
if (!class_exists('ConfigParser', false))
	{ include_once 'ConfigParser.php'; }

if ('english/' == $siteSectionPath)
{
	new ConfigParser('__right-menu-before');
	new ConfigParser('__right-menu-after');
}
else
{
	new ConfigParser('english/__right-menu-before-katedry');
	new ConfigParser('english/__right-menu-after');
}

?>