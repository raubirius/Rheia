<?php

// Search the site content:
$searchCategory = 'index';
$articleSeparator = null;
$rheiaClass = null;
$touch = array();

function searchRheia($file, $label, $link)
{
	global $siteContentConfig, $searchCategory, /*$downloadLevel,*/
		$articleSeparator, $rheiaClass, $touch, $galleryPath;

	$alias = $file;
	for ($i = 0; isSet($siteContentConfig[$alias]['alias']) && $i < 10;
		++$i) $alias = $siteContentConfig[$alias]['alias'];

	if (empty($alias) && empty($label) && empty($link))
	{
		// if (null !== $rheiaClass)
		// 	RheiaMainClass::logError('Empty alias for file '.$file.
		// 		', label '.$label.', and link '.$link.' has been ignored.');
		return;
	}

	/*
	 * Poznámky:
	 *
	 * Keď vyhľadávanie vyhlasuje chyby o chýbajúcich dokumentoch, môže to
	 * byť spôsobené tým, že niektoré položky ponuky nemajú správne
	 * definovaného rodiča!
	 *
	 * Podobne, dôvodom môže byť aj rozdielne definovaný index
	 * asociovaného poľa a názvu súboru s obsahom. Príklad:
	 *
	 *    'kvalita' => array(… 'link' => 'vnutorny-system-kvality' …)
	 *
	 * Index asociovaného poľa je 'kvalita', ale názov súboru s obsahom je
	 * vnutorny-system-kvality.tx?.
	 */

	if (isSet($siteContentConfig[$alias]) &&
		isSet($siteContentConfig[$alias]['type']))
		$type = $siteContentConfig[$alias]['type'];
		else $type = 'html';

	if ($alias !== $file && isSet($siteContentConfig[$alias]))
	{
		if (isSet($siteContentConfig[$alias]['parent']))
		{
			$link = $siteContentConfig[$alias]['parent'].'?'.$alias;
			$articleSeparator = '&';
		}
		else
		{
			$link = $alias;
			$articleSeparator = '?';
		}
	}

	if ('module' === $type) return;
	elseif ('gallery' === $type)
	{
		include_once 'GalleryLoader.php';
		if (isSet($galleryPath))
		{
			/* ignore yet – keep downloadLevel in mind! * /
			$gallery = new GalleryLoader($file, $galleryPath,
				null, $downloadLevel, false);
			/* ignore yet */
			return;
		}
		else
			$gallery = new GalleryLoader($file, null,
				null, null, false);
		if ($gallery->load()) $gallery->search();
		return;
	}

	$touch[$alias] = true;

	if (null === $rheiaClass)
		$rheiaClass = new RheiaMainClass($alias, false);
	else
		$rheiaClass->reset($alias);

	/* if (empty($alias))
		RheiaMainClass::logError('Empty alias for file '.$file.
			', label '.$label.', and link '.$link); */

	if ('articles' === $type)
		$rheiaClass->loadArticles();
	else
		$rheiaClass->loadHTML();

	$rheiaClass->search($label, $link, $articleSeparator);
}


function searchMenu($structure)
{
	global $siteContentConfig, $searchCategory, $articleSeparator;

	foreach ($structure as $file => $item)
	{
		if (empty($item)) { /* Separator */ }
		elseif (is_array($item))
		{
			if (!empty($item['hidden'])) continue;

			if (isSet($item['label']))
				$label = $item['label'];
			elseif (isSet($item['link']))
				$label = $item['link'];
			elseif (isSet($item['http']))
				$label = $item['http'];
			elseif (isSet($item['https']))
				$label = $item['https'];
			else $label = '';

			if (!is_numeric($file) && !isSet($item['http']) &&
				!isSet($item['https']) && !isSet($item['nosearch']) &&
				(!isSet($item['link']) || strpos($item['link'], '/')
					=== false))
			{
				if (isSet($item['link']))
				{
					$link = $item['link'];
					$articleSeparator = null;
				}
				elseif (isSet($siteContentConfig[$file]))
				{
					if (isSet($siteContentConfig[$file]['parent']))
					{
						$link = $siteContentConfig[$file]
							['parent'].'?'.$file;
						$articleSeparator = '&';
					}
					else
					{
						$searchCategory = $link = $file;
						$articleSeparator = '?';
					}
				}
				else
				{
					$link = $searchCategory.'?'.$file;
					$articleSeparator = '&';
				}

				searchRheia($file, $label, $link);
			}

			if (isSet($item['submenu']) &&
				(!isSet($siteContentConfig[$file]) ||
					!isSet($siteContentConfig[$file]['type']) ||
					'articles' !== $siteContentConfig[$file]['type']))
				searchMenu($item['submenu']);
		}
		else
		{
			if (!is_numeric($file))
			{
				if (isSet($siteContentConfig[$file]))
				{
					if (isSet($siteContentConfig[$file]['parent']))
					{
						$link = $siteContentConfig
							[$file]['parent'].'?'.$file;
						$articleSeparator = '&';
					}
					else
					{
						$searchCategory = $link = $file;
						$articleSeparator = '?';
					}
				}
				else
				{
					$link = $searchCategory.'?'.$file;
					$articleSeparator = '&';
				}

				searchRheia($file, $item, $link);
			}
		}
	}
}


function searchFile($file)
{
	global $siteContentConfig, $searchCategory, $articleSeparator;

	if (isSet($siteContentConfig[$file]))
	{
		if (isSet($siteContentConfig[$file]['parent']))
		{
			$link = $siteContentConfig[$file]['parent'].'?'.$file;
			$articleSeparator = '&';
		}
		else
		{
			$searchCategory = $link = $file;
			$articleSeparator = '?';
		}
	}
	else
	{
		$link = $searchCategory.'?'.$file;
		$articleSeparator = '&';
	}

	searchRheia($file, null, $link);
}


if (isSet($siteStructure))
{
	if (isSet($siteStructure['top-menu-before']))
		searchMenu($siteStructure['top-menu-before']);

	if (isSet($siteStructure['top-menu-after']))
		searchMenu($siteStructure['top-menu-after']);

	if (isSet($siteStructure['bottom-menu-before']))
		searchMenu($siteStructure['bottom-menu-before']);

	if (isSet($siteStructure['bottom-menu-after']))
		searchMenu($siteStructure['bottom-menu-after']);

	if (isSet($siteStructure['left-menu-before']))
		searchMenu($siteStructure['left-menu-before']);

	if (isSet($siteStructure['left-menu-after']))
		searchMenu($siteStructure['left-menu-after']);

	if (isSet($siteStructure['right-menu-before']))
		searchMenu($siteStructure['right-menu-before']);

	if (isSet($siteStructure['right-menu-after']))
		searchMenu($siteStructure['right-menu-after']);

	if (isSet($siteStructure['central-menu-before']))
		searchMenu($siteStructure['central-menu-before']);

	if (isSet($siteStructure['central-menu-after']))
		searchMenu($siteStructure['central-menu-after']);

	if (isSet($siteStructure['bottom-icons']))
		searchMenu($siteStructure['bottom-icons']);

	foreach ($siteStructure as $file => $menu)
	{
		if ('top-menu' != $file && 'top-menu-before' != $file &&
			'top-menu-after' != $file &&
			'bottom-menu' != $file && 'bottom-menu-before' != $file &&
			'bottom-menu-after' != $file &&
			'left-menu' != $file && 'left-menu-before' != $file &&
			'left-menu-after' != $file &&
			'right-menu' != $file && 'right-menu-before' != $file &&
			'right-menu-after' != $file &&
			'central-menu' != $file && 'central-menu-before' != $file &&
			'central-menu-after' != $file &&
			'bottom-icons' != $file)
		{
			$searchCategory = $file;
			$articleSeparator = '?';

			if (is_array($menu))
			{
				if (!is_numeric($file) && !isSet($menu['http']) &&
					!isSet($menu['https']) && !isSet($menu['nosearch']) &&
					(!isSet($menu['link']) || strpos($menu['link'], '/')
						=== false))
					searchRheia($file, empty($menu['label']) ?
						null : $menu['label'], $file);

				if (isSet($menu['top-menu']))
					searchMenu($menu['top-menu']);

				if (isSet($menu['bottom-menu']))
					searchMenu($menu['bottom-menu']);

				if (isSet($menu['left-menu']))
					searchMenu($menu['left-menu']);

				if (isSet($menu['right-menu']))
					searchMenu($menu['right-menu']);

				if (isSet($menu['central-menu']))
					searchMenu($menu['central-menu']);
			}
			elseif (!is_numeric($file))
				searchRheia($file, $menu, $file);
		}
	}


	foreach ($siteContentConfig as $file => $config)
	{
		if (is_array($config) && empty($touch[$file]) &&
			empty($config['nosearch']))
		{
			$searchCategory = $file;
			$articleSeparator = '?';
			searchFile($file);
		}
	}
}

RheiaMainClass::searchResults();

?>