<?php

echo '<div class="article"><div class="history"><p><a href="site-info">'.
	$designTexts['design-feature-info'].'</a></p></div><div class="clear">'.
	'</div>'.EOL2;

echo '<div class="title"><h1><a href="site-map">'.($title =
	$designTexts['site-map']).'</a></h1></div>'.EOL2;


echo '<div class="article-content">';

$style .= EOL.'/* Site Map */'.EOL2.'div.site-map'.EOL.'{'.EOLT.
	'margin: 20px 20px 20px 0px;'.EOLT.'padding: 0px;'.EOL.EOLT.
	'/*border: 1px solid red;*/'.EOL.'}'.EOL2.'div.site-map ul'.EOL.'{'.
	EOLT.'margin: 0px;'.EOLT.'padding: 2px 0px;'.EOL.EOLT.
	'/*border: 1px solid green;*/'.EOL.'}'.EOL2.'div.site-map ul'.EOL.'{'.
	EOLT.'list-style: none;'.EOL.'}'.EOL2.'div.site-map ul.items li'.
	EOL.'{'.EOLT.'font-size: 14px;'.EOL.'}'.EOL2.'div.site-map '.
	'ul.items li.submenu'.EOL.'{'.EOLT.'margin-left: 20px;'.EOLT.
	'font-size: 13px;'.EOL.'}'.EOL2.'div.site-map p'.EOL.'{'.EOLT.
	'margin: 10px 0px 0px;'.EOLT.'padding: 4px 0px;'.EOLT.
	'text-align: left;'.EOLT.'/*font-family: "Arial", "Verdana", '.
	'sans-serif;*/'.EOLT.'font-size: 13px;'.EOLT.'font-weight: '.
	'bold;'.EOLT.'color: black;'.EOL.'}'.EOL2.EOL.'div.site-map '.
	'ul.items a img.card'.EOL.'{'.EOLT.'max-width: 120px;'.EOL.'}'.EOL;

function makeMap($structure, $submenu = '')
{
	global $designCategory, $category, $selectedItem,
		$siteContentConfig, $siteSectionPath;

	$menu = ''; $alias = isSet($selectedItem) ? $selectedItem : $category;
	for ($i = 0; isSet($siteContentConfig[$alias]['alias']) && $i < 10; ++$i)
		$alias = $siteContentConfig[$alias]['alias'];

	foreach ($structure as $file => $item)
	{
		$active = ($alias === $file || $selectedItem === $file ||
			$category === $file);

		if (empty($item))
			$menu .= TAB4.'<li class="'.$submenu.(empty($submenu) ?
				'' : ' ').'separator"></li>'.EOL;
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

			$label = str_replace('<br />', ' ', $label);

			if (is_numeric($file)) { /* ignore labels */ } else
			{
				if (isSet($item['http']) || isSet($item['https']))
					continue; else
				{
					if (isSet($item['link']))
						$link = /*'/'.$siteSectionPath.*/$item['link'];
					elseif (isSet($siteContentConfig[$file]))
					{
						if (isSet($siteContentConfig[$file]['parent']))
							$link = '/'.$siteSectionPath.$siteContentConfig
								[$file]['parent'].'?'.$file;
						else
						{
							if ('index' === $file)
							{
								$designCategory = $file;
								$link = '/'.$siteSectionPath;
							}
							else
								$link = '/'.$siteSectionPath.
									($designCategory = $file);
						}
					}
					else $link = '/'.$siteSectionPath.$designCategory.
						(empty($file) || '#' === $file[0] ? '' : '?').$file;
				}

				if ('/index' === $link) $link = '/';

				$menu .= TAB4.'<li'.(empty($submenu) ? '' :
					(' class="'.$submenu.'"')).'><a href="'.$link.'"'.
					($active ? ' class="active"' : '').'>'.$label.
					(isSet($item['card']) ? ' <small>(ikona)</small>' : '').
					'</a></li>'.EOL;
			}

			if (isSet($item['submenu']))
				$menu .= makeMap($item['submenu'], 'submenu');
		}
		else
		{
			$item = str_replace('<br />', ' ', $item);

			if (is_numeric($file)) { /* ignore labels */ } else
			{
				if (isSet($siteContentConfig[$file]))
				{
					if (isSet($siteContentConfig[$file]['parent']))
						$link = $siteSectionPath.$siteContentConfig
							[$file]['parent'].'?'.$file;
					else
					{
						if ('index' === $file)
						{
							$designCategory = $file;
							$link = $siteSectionPath;
						}
						else
							$link = $siteSectionPath.($designCategory = $file);
					}
				}
				else
					$link = $siteSectionPath.$designCategory.
						(empty($file) || '#' === $file[0] ? '' : '?').$file;

				if ('index' === $link) $link = '';

				$menu .= TAB4.'<li'.(empty($submenu) ? '' :
					(' class="'.$submenu.'"')).'><a href="/'.$link.'"'.
					($active ? ' class="active"' : '').'>'.$item.
					'</a></li>'.EOL;
			}
		}
	}

	return $menu;
}


// $topMap = '';
$bottomMap = '';
$leftMap = '';
// $rightMap = '';
$centralMap = '';

if (isSet($siteStructure))
{
	// if (isSet($siteStructure['top-menu-before']))
	// 	$topMap .= makeMap($siteStructure['top-menu-before']);
	// 
	// if (isSet($siteStructure[$category]) &&
	// 	is_array($siteStructure[$category]) &&
	// 	isSet($siteStructure[$category]['top-menu']))
	// 	$topMap .= makeMap($siteStructure[$category]['top-menu']);
	// 
	// if (isSet($siteStructure['top-menu-after']))
	// 	$topMap .= makeMap($siteStructure['top-menu-after']);
	// 
	// if (!empty($topMap))
	// {
	// 	$topMap = TAB3.'<div class="top-map">'.EOL.
	// 		TAB4.'<ul class="items">'.EOL.$topMap.
	// 		TAB4.'</ul>'.EOL.TAB3.'</div>'.EOL;
	// }


	if (isSet($siteStructure['bottom-menu-before']))
		$bottomMap .= makeMap($siteStructure['bottom-menu-before']);

	if (isSet($siteStructure[$category]) &&
		is_array($siteStructure[$category]) &&
		isSet($siteStructure[$category]['bottom-menu']))
		$bottomMap .= makeMap($siteStructure[$category]['bottom-menu']);

	if (isSet($siteStructure['bottom-menu-after']))
		$bottomMap .= makeMap($siteStructure['bottom-menu-after']);

	if (isSet($siteStructure['bottom-icons']))
		$bottomMap .= makeMap($siteStructure['bottom-icons']);

	if (!empty($bottomMap))
	{
		$bottomMap = TAB3.'<div class="bottom-map">'.EOL.
			TAB4.'<ul class="items">'.EOL.$bottomMap.
			TAB4.'</ul>'.EOL.TAB3.'</div>'.EOL;
	}


	if (isSet($siteStructure['left-menu-before']))
		$leftMap .= makeMap($siteStructure['left-menu-before']);

	if (isSet($siteStructure[$category]) &&
		is_array($siteStructure[$category]) &&
		isSet($siteStructure[$category]['left-menu']))
		$leftMap .= makeMap($siteStructure[$category]['left-menu']);

	if (isSet($siteStructure['left-menu-after']))
		$leftMap .= makeMap($siteStructure['left-menu-after']);

	if (!empty($leftMap))
	{
		$leftMap = TAB3.'<div class="left-map">'.EOL.
			TAB4.'<ul class="items">'.EOL.$leftMap.
			TAB4.'</ul>'.EOL.TAB3.'</div>'.EOL;
	}


	// if (isSet($siteStructure['right-menu-before']))
	// 	$rightMap .= makeMap($siteStructure['right-menu-before']);
	// 
	// if (isSet($siteStructure[$category]) &&
	// 	is_array($siteStructure[$category]) &&
	// 	isSet($siteStructure[$category]['right-menu']))
	// 	$rightMap .= makeMap($siteStructure[$category]['right-menu']);
	// 
	// if (isSet($siteStructure['right-menu-after']))
	// 	$rightMap .= makeMap($siteStructure['right-menu-after']);
	// 
	// if (!empty($rightMap))
	// {
	// 	$rightMap = TAB3.'<div class="right-map">'.EOL.
	// 		TAB4.'<ul class="items">'.EOL.$rightMap.
	// 		TAB4.'</ul>'.EOL.TAB3.'</div>'.EOL;
	// }


	if (isSet($siteStructure['central-menu-before']))
		$centralMap .= makeMap($siteStructure['central-menu-before']);

	if (isSet($siteStructure[$category]) &&
		is_array($siteStructure[$category]) &&
		isSet($siteStructure[$category]['central-menu']))
		$centralMap .= makeMap($siteStructure[$category]['central-menu']);

	if (isSet($siteStructure['central-menu-after']))
		$centralMap .= makeMap($siteStructure['central-menu-after']);

	if (!empty($centralMap))
	{
		$centralMap = TAB3.'<div class="central-map">'.EOL.
			TAB4.'<ul class="items">'.EOL.$centralMap.
			TAB4.'</ul>'.EOL.TAB3.'</div>'.EOL;
	}


	if ('en' == $contentLanguage)
	{
		//echo '<p class="note"><b>Note:</b> next two paragraphs are not '.
		//	'translated, yet???</p>';

		echo '<p>The <a href="/english/">main page</a> is dedicated to '.
			'show <a href="/english/news"><i>News</i></a>. (<em>However, '.
			'this part of English version of our pages is not very rich???'.
			'</em>) Upper items '.
			/*Prv?? polo??ka hornej ponuky je rezervovan?? na '.
			'zverejnenie dokumentov, ktor?? s?? z??poh??adu fakulty pova??ovan?? '.
			'za <a href="/zasadne-dokumenty">z??sadn??</a>. ??al??ie polo??ky???*/
			'are shortcuts to selected items in left menu. (For example '.
			'upper item <a href="/english/student">Student</a> is shortcut '.
			'for left item <a href="/english/student">Information for '.
			'Students</a>.)</p>'.
			'<p>Some information is available through several paths within '.
			'our web site???s structure. (For example information about <a '.
			'href="/english/student?study-programmes">study programmes</a> '.
			'is accessible through the section <a href="/english/applicant">'.
			'applicant</a> and also the section <a href="/english/student">'.
			'student</a>. The reason of this is the improvement of '.
			'accessibility for some information.)</p>'.EOL;

		echo EOL.'<div class="site-map">'.EOL;

		if (!empty($leftMap))
		{
			echo '<p>Left menu structure</p>'.EOL;
			echo $leftMap;
		}

		// if (!empty($topMap))
		// {
		// 	echo '<p>Selected items from top menu</p>'.EOL;
		// 	echo $topMap;
		// }

		if (!empty($bottomMap))
		{
			echo '<p>Selected items from bottom menu</p>'.EOL;
			echo $bottomMap;
		}

		// if (!empty($rightMap))
		// {
		// 	echo '<p>Selected items from right menu</p>'.EOL;
		// 	echo $rightMap;
		// }

		if (!empty($centralMap))
		{
			echo '<p>Selected items from central menu</p>'.EOL;
			echo $centralMap;
		}

		echo EOL.'</div>'.EOL;

		echo '<p>The other items (and icons) within the bottom and below '.
			'the left menu are linked to different places, mostly external '.
			'sites (university sites or portals) whose will be open in '.
			'a new browser window (tab).</p>'.EOL;
	}
	else
	{
		echo '<p><a href="/">??vodn?? str??nka</a> je ur??en?? na zobrazenie '.
			'v??beru z??<a href="/aktuality"><i>aktual??t</i></a> a??r??znych '.
			'inform??ci??. Polo??ky '.
			/*Prv?? polo??ka hornej ponuky je rezervovan?? na '.
			'zverejnenie dokumentov, ktor?? s?? z??poh??adu fakulty pova??ovan?? '.
			'za <a href="/zasadne-dokumenty">z??sadn??</a>. ??al??ie polo??ky???*/
			'hornej ponuky s?? skratkami na prisl??chaj??ce polo??ky v????avej '.
			'ponuke. (Napr??kad polo??ka <a href="/student">??tudent</a> je '.
			'skratkou polo??ky <a href="/student">Inform??cie pre '.
			'??tudentov</a>.)</p>'.
			'<p>Niektor?? inform??cie s?? spr??stupnen?? prostredn??ctvom '.
			'viacer??ch polo??iek v??r??mci ??trukt??ry webov??ho s??dla. '.
			'(Napr??klad inform??cie o??<a href="/student?studijne-programy">'.
			'??tudijn??ch programoch</a> s?? zverejnen?? polo??kami v??sekcii '.
			'<a href="/uchadzac">uch??dza??</a> aj <a href="/student">'.
			'??tudent</a>. D??vodom je zlep??enie dostupnosti niektor??ch '.
			'inform??ci??.)</p>'.EOL;

		echo EOL.'<div class="site-map">'.EOL;

		if (!empty($leftMap))
		{
			echo '<p>??trukt??ra ??avej ponuky</p>'.EOL;
			echo $leftMap;
		}

		// if (!empty($topMap))
		// {
		// 	echo '<p>Niektor?? polo??ky hornej ponuky</p>'.EOL;
		// 	echo $topMap;
		// }

		if (!empty($bottomMap))
		{
			echo '<p>Niektor?? polo??ky dolnej ponuky</p>'.EOL;
			echo $bottomMap;
		}

		// if (!empty($rightMap))
		// {
		// 	echo '<p>Niektor?? polo??ky pravej ponuky</p>'.EOL;
		// 	echo $rightMap;
		// }

		if (!empty($centralMap))
		{
			echo '<p>Niektor?? polo??ky strednej ponuky</p>'.EOL;
			echo $centralMap;
		}

		echo EOL.'</div>'.EOL;

		echo '<p>Ostatn?? polo??ky (a??ikony) v??dolnej a??pod ??avou ponukou '.
			's?? zv??????a odkazmi na extern?? str??nky (univerzitn?? str??nky '.
			'alebo port??ly), ktor?? s?? otv??ran?? v??novom okne (na novej '.
			'karte) prehliada??a.</p>'.EOL;
	}
}

echo '</div></div>';

?>