<?php

include_once 'RheiaMainClass.php';
include_once 'counter.php';


function categoryArticles()
{
	global $category, $selectedItem, $siteContentConfig;

	$alias = $category;
	for ($i = 0; isSet($siteContentConfig[$alias]['alias']) && $i < 10;
		++$i) $alias = $siteContentConfig[$alias]['alias'];

	if (isSet($siteContentConfig[$alias]) &&
		isSet($siteContentConfig[$alias]['type']) &&
		empty($siteContentConfig[$alias]['parent']) &&
		'articles' == $siteContentConfig[$alias]['type'] &&
		(empty($siteContentConfig[$alias]['reserved']) ||
			empty($siteContentConfig[$alias]['reserved'][$selectedItem])))
	{
		$category = $alias;
		return true;
	}

	return false;
}

function selectedItemArticles()
{
	global $category, $selectedItem, $siteContentConfig;
	if (isSet($selectedItem)) return false;

	$alias = $category;
	for ($i = 0; isSet($siteContentConfig[$alias]['alias']) && $i < 10;
		++$i) $alias = $siteContentConfig[$alias]['alias'];

	if (isSet($siteContentConfig[$alias]) &&
		isSet($siteContentConfig[$alias]['type']) &&
		!empty($siteContentConfig[$alias]['parent']) &&
		'articles' == $siteContentConfig[$alias]['type'])
	{
		$category = $siteContentConfig[$alias]['parent'];
		$selectedItem = $alias;
		return true;
	}

	return false;
}


/** /
function debug($id)
{
	global $category, $selectedItem;

	echo '<p style="color: blue">';

	if (empty($selectedItem))
	{
		if (empty($category))
			echo '«error»';
		else
			echo $category;
	}
	else
	{
		echo $category.'?'.$selectedItem;
	}

	echo '<br />';
	echo $id.'<br />';

	echo '</p>';
}
/**/


try { // ——— Exception handling ———


if (categoryArticles())
{
	/** /debug('«special» category-articles.php');/**/

	include_once 'category-articles.php';
}
elseif (selectedItemArticles())
{
	/** /debug('«special» selectedItem-articles.php');/**/

	include_once 'selectedItem-articles.php';
}
elseif (isSet($selectedItem))
{
	for ($i = 0; isSet($siteContentConfig[$selectedItem]['alias']) && $i < 10;
		++$i) $selectedItem = $siteContentConfig[$selectedItem]['alias'];

	if (isSet($siteContentConfig[$selectedItem]) &&
		isSet($siteContentConfig[$selectedItem]['type']))
	{
		/** /debug('selectedItem-'.$siteContentConfig
			[$selectedItem]['type']);/**/

		include_once 'selectedItem-'.$siteContentConfig
			[$selectedItem]['type'].'.php';
	}
	else
	{
		/** /debug('selectedItem-html');/**/

		include_once 'selectedItem-html.php';
	}
}
else
{
	for ($i = 0; isSet($siteContentConfig[$category]['alias']) && $i < 10;
		++$i) $category = $siteContentConfig[$category]['alias'];

	if (isSet($siteContentConfig[$category]) &&
		isSet($siteContentConfig[$category]['type']))
	{
		/** /debug('category-'.$siteContentConfig
			[$category]['type']);/**/

		include_once 'category-'.$siteContentConfig
			[$category]['type'].'.php';
	}
	else
	{
		/** /debug('category-html');/**/

		include_once 'category-html.php';
	}
}

if (isSet($_SESSION) && isSet($params) && is_array($params))
{
	if (!isSet($_SESSION['history']) || !is_array($_SESSION['history']))
		$_SESSION['history'] = array();

	$needsave = true;
	if (isSet($siteSectionPath))
		$save = array($siteSectionPath);
	else
		$save = array('');
	for ($i = 1; $i <= 3; ++$i)
		if (isSet($params[$i - 1]))
			$save[$i] = $params[$i - 1];
		else
			$save[$i] = null;
	$save[4] = $title;

	foreach ($_SESSION['history'] as $i => $history)
	{
		if ($i > 0)
		{
			if ($history === $save)
			{
				// in case that we have foud the $save configuration:
				// swapping the order of elements 0 and $i – the $history
				// variable already contains the element $i (see ***)
				$_SESSION['history'][$i] = $_SESSION['history'][0];
				$_SESSION['history'][0] = $history;
				$needsave = false;
				break;
			}
		}
		elseif ($history === $save)
		{
			$needsave = false;
			break;
		}
	}

	// (continuing ***) in other case we will create new history item
	if ($needsave) array_unshift($_SESSION['history'], $save);
	unset($save); unset($needsave);
}


} catch (Exception $e) { // ——— Exception handling ———

	$ob_temp = ob_get_clean();
	ob_start();

	echo EOL2.'<!--'.EOL2.str_replace('-->', '- - >',
		str_replace('<!--', '< ! - -', $ob_temp)).EOL2.'-->'.
		EOL2.'<p>Ľutujeme, ale tento obsah momentálne nie je '.
		'k dispozícii z dôvodu serverovej chyby.</p>'.EOL2;

	echo '<!--'.EOL.'Caught exception: '.$e->getMessage().EOL.'-->'.EOL;

} // ——— Exception handling ———

?>