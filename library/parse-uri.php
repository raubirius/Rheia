<?php

include_once 'whitespace-constants.php';

@$params = preg_split('#[\?&]#', trim($_SERVER
	['REQUEST_URI'], '/'), -1, PREG_SPLIT_NO_EMPTY);
$articleID = $articlesSource = '';

if (!isSet($siteContentConfig))
	$siteContentConfig = array();
if (!isSet($siteSectionPath))
	$siteSectionPath = '';
if (!isSet($category))
{
	$category = isSet($params[0]) ? $params[0] : null;
	$strrpos = strrpos($category, '/');
	if (false !== $strrpos)
		$category = substr($category, 1 + $strrpos);
}
if (!isSet($selectedItem))
	$selectedItem = isSet($params[1]) ? $params[1] : null;
if (!isSet($argument))
	$argument = isSet($params[2]) ? $params[2] : null;

if (isSet($category)) $category = strtolower($category);
if (isSet($selectedItem)) $selectedItem = strtolower($selectedItem);

?>