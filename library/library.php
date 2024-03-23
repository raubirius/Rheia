<?php

// $domain = 'pdf.truni.sk';
$stylesVersions = array();
$javaScriptsVersions = array();

$designTexts = array(
	'months' => array('január', 'február', 'marec', 'apríl', 'máj',
		'jún', 'júl', 'august', 'september', 'október', 'november',
		'december'),

	'design-home' => 'Domov',
	'design-faculty' => 'Pedagogická fakulta',
	'design-faculty-university-separator' => ' ',
	'design-university' => 'Trnavská univerzita v Trnave',
	'design-of-university' => 'Trnavskej univerzity v Trnave',
	'design-rights-reserved' => 'všetky práva vyhradené',
	'design-todays-date' => 'Dnes je',
	'design-contacts' => 'Kontakty',
	'design-contacts-chair' => 'katedra',
	'design-contacts-faculty' => 'fakulta',
	'design-namedays-singular' => 'meniny má',
	'design-namedays-plural' => 'meniny majú',
	'design-feature-info' => 'Informácie o stránke',
	'design-feature-print' => 'Tlačiť',
	'design-feature-search' => 'Hľadať',
	'design-feature-search-placeholder' => 'Hľadať…',
	'design-feature-search-title' => 'Zadajte text na vyhľadávanie…',
	'design-empty-search-alert' => 'Na začatie vyhľadávania je potrebné zadať hľadaný výraz, slová alebo frázu!',
	'design-tab-not-exists' => 'Požadovaná karta nejestvuje.\\nProsím, zvoľte inú.',
	'design-google-map-zoom' => 'Zobraziť väčšiu mapu',

	'design-language-code' => '',
	'design-loading' => 'Stránka sa načítava, prosím čakajte',

	'design-cookies-announce' => 'Vážený návštevník, naše stránky používajú súbory cookies. Pokračovaním v prehliadaní našich stránok vyjadrujete svoj súhlas s používaním a uchovávaním cookies vo Vašom koncovom zariadení.',
	'design-cookies-disable' => 'Používanie cookies môžete odmietnuť v nastaveniach Vášho prehliadača.',
	'design-cookies-more-info' => 'Viac informácií',
	'design-cookies-dismiss' => 'Rozumiem',

	'site-map' => 'Štruktúra položiek webového sídla',
	'site-counters' => 'Hodnoty počítadiel prístupov webového sídla',

	// 'external-link' => 'externý odkaz',
	'external-link' => "otvárané v novom okne\r\n(obvykle ide o externý odkaz)",

	'error-401-head' => 'Chyba pri overení používateľa',
	'error-401-text' => 'Na prístup k tomuto obsahu potrebujete byť prihlásený.',
	'error-401-desc' => 'Ak ste sa práve pokúšali prihlásiť, tak ste zadali nesprávne prihlasovacie údaje. Ak ste boli prihlásený, tak Vašej relácii skončila lehota platnosti. Použite prihlasovací dialóg. Uistite sa, že zadávate správne prihlasovacie údaje.',

	'error-403-head' => 'Zamietnutý prístup',
	'error-403-text' => 'Prístup k tomuto obsahu bol zamietnutý.',
	'error-403-desc' => 'Váš odkaz môže byť zastaraný. Skúste získať aktuálny odkaz alebo vyberte prislúchajúcu položku ponuky.',

	'error-404-head' => 'Chýbajúci dokument',
	'error-404-text' => 'Ľutujeme, ale požadovaný obsah nie je k dispozícii.',
	'error-404-desc' => 'Váš odkaz môže byť zastaraný. Skúste získať aktuálny odkaz alebo vyberte prislúchajúcu položku ponuky.',

	'error-410-head' => 'Odstránený dokument',
	'error-410-text' => 'Dokument, ktorý požadujete, bol odstránený.',
	'error-410-desc' => 'Váš odkaz je pravdepodobne zastaraný. Článok alebo profil osoby, na ktorú sa odkaz vzťahuje, bol odstránený. Článok mohol byť neaktuálny alebo osoba už nemusí byť zamestnancom fakulty, prípadne sa zmenilo jej meno/priezvisko. Skúste získať aktuálny odkaz alebo vyberte možnosť z hlavnej, prípadne vedľajšej ponuky.',

	'error-500-head' => 'Služba je momentálne nedostupná',
	'error-500-text' => 'Server zaznamenal chybu a nemohol vyhovieť vašej požiadavke.',

	'error-501-head' => 'Nebolo implementované',
	'error-501-text' => 'Spôsob, ktorým sa pokúšate pripojiť, nie je v súčasnosti podporovaný.',

	'error-no-access-head' => 'Nedostatočné prístupové práva',
	'error-no-access-text' => 'Ľutujeme, ale na prístup k tomuto obsahu nemáte dostatočné oprávnenie.',
	'error-no-access-desc' => 'Ak si myslíte, že by ste mali mať prístup k tomuto obsahu, kontaktujte prislúchajúcu autoritu na pridelenie práv.',

	'error-unkwn-head' => 'Informácia',
	'error-unkwn-text' => 'Počas spracovania požiadavky sa vyskytla neznáma chyba.',
	'error-unkwn-desc' => '',

	'login-home' => 'Domov',
	'login-default-go-home' => 'Prejdite na domovskú stránku.',

	// 'login-dialog-title' => 'Prihlásenie sa',
	'login-dialog-login' => 'TUID',
	'login-dialog-password' => 'Heslo',
	'login-dialog-submit' => 'Prihlásiť sa',

	'login-title' => 'Prihlásenie sa',
	'login-already-logged-1' => 'Prihlásenie sa nie je potrebné, pretože na tomto počítači je prihlásený oprávnený používateľ. ',
	'login-already-logged-2' => 'Ak to nie ste Vy, tak sa, prosím, odhláste.',
	'login-already-logged-3' => '',
	'login-successful' => 'Prihlásenie sa prebehlo v poriadku.',
	'login-failed' => 'Zadali ste nesprávne prihlasovacie údaje.',
	'login-try-again' => 'Prosím, skúste sa prihlásiť znova.',

	'logout-dialog-submit' => 'Odhlásiť sa',

	'logout-title' => 'Odhlásenie sa',
	'logout-successful' => 'Odhlásenie sa prebehlo v poriadku.',
	'logout-no-need' => 'Nebolo potrebné sa odhlásiť. Na tomto počítači nebol prihlásený žiadny používateľ.',

	'chpwd-title' => 'Zmena hesla',
	'chpwd-successful' => 'Zmena hesla bola úspešná.',
	'chpwd-failed' => 'Zmena hesla zlyhala.',
	'chpwd-explain' => 'Buď ste zadali nesprávne pôvodné heslo, alebo nové heslo nesúhlasí s opakovaním hesla, alebo staré heslo a nové heslo sa zhodujú.',
	'chpwd-try-again' => 'Prosím, skúste to znova.',

	'chpwd-dialog-password' => 'Staré heslo',
	'chpwd-dialog-chpwd1' => 'Nové heslo',
	'chpwd-dialog-chpwd2' => 'Opakovanie hesla',
	'chpwd-dialog-submit' => 'Zmeniť heslo',
);

include_once 'parse-uri.php';

if (isSet($translation))
	foreach ($translation as $key => $value)
		if (isSet($designTexts[$key]))
			$designTexts[$key] = $value;

if (!isSet($designURI)) $designURI = 'design/';

function startsWithNC($haystack, $needle)
{
	$length = strlen($needle);
	return 0 === strcasecmp(substr($haystack, 0, $length), $needle);
}

function endsWithNC($haystack, $needle)
{
	$length = strlen($needle);
	if (!$length) return true;
	return 0 === strcasecmp(substr($haystack, -$length), $needle);
}

function replaceFirst(&$string, $search, $replace)
{
	$strpos = strpos($string, $search);
	if ($strpos !== false)
		$string = substr_replace($string, $replace, $strpos, strlen($search));
}

function replaceLast(&$string, $search, $replace)
{
	$strrpos = strrpos($string, $search);
	if ($strrpos !== false)
		$string = substr_replace($string, $replace, $strrpos, strlen($search));
}

function makeExternalLink($label, $link = null)
{
	global /*$externalLinkIcon, */$designTexts, $designURI;
	if (empty($link)) $link = $label;
	return '<a href="'.$link.'" target="_blank" rel="noopener" class="'.
		'external-link"><span>'.$label.'</span> <img src="'.$designURI.
		'null.gif'.//$externalLinkIcon.
		'" alt="'.$designTexts['external-link'].'" title="'.$designTexts[
			'external-link'].'" class="external-icon" /></a>';
}

function makeNonEmptyAttr($attr, $value)
{
	$value = trim($value);
	return empty($value) ? '' : (' '.$attr.'="'.$value.'"');
}

function makeMenu($structure, $defautClass = '',
	$submenuPrefix = '', $submenuPostfix = '', $submenuIn = false)
{
	global $designCategory, $category, $selectedItem,
		$siteContentConfig, $siteSectionPath;

	$menu = ''; $alias = isSet($selectedItem) ? $selectedItem : $category;
	for ($i = 0; isSet($siteContentConfig[$alias]['alias']) && $i < 10; ++$i)
		$alias = $siteContentConfig[$alias]['alias'];
	$hasSubmenu = false;

	foreach ($structure as $file => $item)
	{
		$active = ($alias === $file || $selectedItem === $file ||
			$category === $file);

		if (isSet($siteContentConfig[null][$file]) &&
			isSet($siteContentConfig[null][$file]['active']) &&
			$siteContentConfig[null][$file]['active']) $active = true;

		if (empty($item))
			$menu .= TAB4.'<li'.makeNonEmptyAttr('class',
				$defautClass.' separator').'></li>'.EOL;
		elseif (is_array($item))
		{
			if (!empty($item['hidden'])) continue;

			$hasSubmenu = (isSet($item['submenu']) && $category === $file) ||
				(isSet($siteContentConfig[$category]) &&
					isSet($siteContentConfig[$category]['parent']) &&
					$siteContentConfig[$category]['parent'] === $file) ||
				(isSet($siteContentConfig[null]) &&
					isSet($siteContentConfig[null][$file]) &&
					isSet($siteContentConfig[null][$file]['open']));

			$classes = $defautClass.(empty($item['class']) ?
				'' : (' '.$item['class'])).($hasSubmenu ? ' has-submenu' : '');
			$styles = empty($item['style']) ? '' : $item['style'];

			if (isSet($item['label']))
				$label = $item['label'];
			elseif (isSet($item['link']))
				$label = $item['link'];
			elseif (isSet($item['http']))
				$label = $item['http'];
			elseif (isSet($item['https']))
				$label = $item['https'];
			else $label = '';

			if (isSet($item['title']))
				$title = $item['title'];
			else if (isSet($item['card']))
				$title = $label;
			else
				$title = '';

			if (is_numeric($file))
			{
				$menu .= TAB4.'<li'.makeNonEmptyAttr('class',
					$classes.' label').makeNonEmptyAttr('style',
					$styles).'><p>'. $label.'</p>';
			}
			else
			{
				if (isSet($item['http']))
				{
					$link = 'http://'.$item['http'];
					$target = ' target="_blank" rel="noopener"';
				}
				elseif (isSet($item['https']))
				{
					$link = 'https://'.$item['https'];
					$target = ' target="_blank" rel="noopener"';
				}
				else
				{
					$target = '';
					if (isSet($item['link']))
						$link = (!empty($item['link']) &&
							'/' === $item['link'][0] ? '' :
							'/'.$siteSectionPath).$item['link'];
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
						(!empty($file) && '#' === $file[0] ? '' : '?').$file;
				}

				if ('/index' === $link) $link = '/';

				if (isSet($item['card']))
					$menu .= TAB4.'<li'.makeNonEmptyAttr('class', $classes).
						makeNonEmptyAttr('style', $styles).
						'><a href="'.$link.'"'.($active ?
							' class="active-trail active"' :
							'').$target.'><img src="'.$item['card'].
						'" alt="card"'.
						(empty($title) ? '' : (' title="'.$title.'"')).
						' class="card" /></a></li>'.EOL;
				else
					$menu .= TAB4.'<li'.makeNonEmptyAttr('class', $classes).
						makeNonEmptyAttr('style', $styles).
						'><a href="'.$link.'"'.($active ?
							' class="active-trail active"' :
							'').$target.'>'.$label.'</a>';
			}

			if (!$submenuIn) $menu .= '</li>'.EOL;

			if ($hasSubmenu)
				$menu .= $submenuPrefix.makeMenu($item['submenu'], 'submenu',
					$submenuPrefix, $submenuPostfix, $submenuIn).$submenuPostfix;

			if ($submenuIn) $menu .= '</li>'.EOL;
		}
		else
		{
			if (is_numeric($file))
				$menu .= TAB4.'<li'.makeNonEmptyAttr('class', $defautClass.
					' label').'><p>'.$item.'</p></li>'.EOL;
			else
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
						(!empty($file) && '#' === $file[0] ? '' : '?').$file;

				if ('index' === $link) $link = '';

				$menu .= TAB4.'<li'.makeNonEmptyAttr('class', $defautClass).
					'><a href="/'.$link.'"'.($active ?
						' class="active-trail active"' :
						'').'>'.$item.'</a></li>'.EOL;
			}
		}
	}

	return $menu;
}


function makeMenuFromTemplates($structure, $defautClass = '', $itemTemplate = '',
	$labelTemplate = '', $separatorTemplate = '', $cardTemplate = '')
{
	global $designCategory, $category, $selectedItem,
		$siteContentConfig, $siteSectionPath;

	$menu = ''; $alias = isSet($selectedItem) ? $selectedItem : $category;
	for ($i = 0; isSet($siteContentConfig[$alias]['alias']) && $i < 10; ++$i)
		$alias = $siteContentConfig[$alias]['alias'];
	$count = 0; $currentItem = ''; $hasSubmenu = false;

	foreach ($structure as $file => $item)
	{
		$active = ($alias === $file || $selectedItem === $file ||
			$category === $file);

		if (isSet($siteContentConfig[null][$file]) &&
			isSet($siteContentConfig[null][$file]['active']) &&
			$siteContentConfig[null][$file]['active']) $active = true;

		if (empty($item))
		{
			$menu .=
				str_replace('$class', makeNonEmptyAttr(
					'class', $defautClass.' separator'),
				$separatorTemplate);
		}
		elseif (is_array($item))
		{
			if (!empty($item['hidden'])) continue;

			$hasSubmenu = (isSet($item['submenu']) && $category === $file) ||
				(isSet($siteContentConfig[$category]) &&
					isSet($siteContentConfig[$category]['parent']) &&
					$siteContentConfig[$category]['parent'] === $file) ||
				(isSet($siteContentConfig[null]) &&
					isSet($siteContentConfig[null][$file]) &&
					isSet($siteContentConfig[null][$file]['open']));

			$classes = $defautClass.(empty($item['class']) ?
				'' : (' '.$item['class'])).($hasSubmenu ? ' has-submenu' : '');
			$styles = empty($item['style']) ? '' : $item['style'];

			if (isSet($item['label']))
				$label = $item['label'];
			elseif (isSet($item['link']))
				$label = $item['link'];
			elseif (isSet($item['http']))
				$label = $item['http'];
			elseif (isSet($item['https']))
				$label = $item['https'];
			else $label = '';

			if (isSet($item['title']))
				$title = $item['title'];
			else if (isSet($item['card']))
				$title = $label;
			else
				$title = '';

			if (!empty($title))
				$title = ' title="'.$title.'"';

			if (is_numeric($file))
			{
				$menu .=
					str_replace('$label', $label,
					str_replace('$title', $title,
					str_replace('$class', makeNonEmptyAttr('class', $classes),
					str_replace('$style', makeNonEmptyAttr('style', $styles),
					$labelTemplate))));
			}
			else
			{
				if (isSet($item['http']))
				{
					$link = 'http://'.$item['http'];
					$target = ' target="_blank" rel="noopener"';
				}
				elseif (isSet($item['https']))
				{
					$link = 'https://'.$item['https'];
					$target = ' target="_blank" rel="noopener"';
				}
				else
				{
					$target = '';
					if (isSet($item['link']))
						$link = (!empty($item['link']) &&
							'/' === $item['link'][0] ? '' :
							'/'.$siteSectionPath).$item['link'];
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
						(!empty($file) && '#' === $file[0] ? '' : '?').$file;
				}

				if ('/index' === $link) $link = '/';

				if (isSet($item['card']))
				{
					$currentItem = str_replace('$target', $target,
						str_replace('$count', $count, str_replace('$active',
							($active ? 'active-trail active' : ''),
						str_replace('$card', $item['card'],
						str_replace('$link', $link,
						str_replace('$label', $label,
						str_replace('$title', $title,
						str_replace('$class',
							makeNonEmptyAttr('class', $classes),
						str_replace('$style',
							makeNonEmptyAttr('style', $styles),
						$cardTemplate)))))))));
				}
				else
				{
					++$count;
					$currentItem = str_replace('$target', $target,
						str_replace('$count', $count,
						str_replace('$active', ($active ?
							'active-trail active' : ''),
						str_replace('$link', $link,
						str_replace('$label', $label,
						str_replace('$title', $title,
						str_replace('$class',
							makeNonEmptyAttr('class', $classes),
						str_replace('$style',
							makeNonEmptyAttr('style', $styles),
						$itemTemplate))))))));
				}
			}

			if ($hasSubmenu)
				$menu .= str_replace('$submenu', makeMenuFromTemplates(
					$item['submenu'], 'submenu', $itemTemplate, $labelTemplate,
					$separatorTemplate, $cardTemplate), $currentItem);
			else
				$menu .= str_replace('$submenu', '', $currentItem);
		}
		else
		{
			if (empty($item))
				$title = '';
			else
				$title = ' title="'.$item.'"';

			if (is_numeric($file))
			{
				$menu .=
					str_replace('$label', $item,
					str_replace('$title', $title,
					str_replace('$class',
						makeNonEmptyAttr('class', $defautClass),
					str_replace('$style', '', $labelTemplate))));
			}
			else
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
						(!empty($file) && '#' === $file[0] ? '' : '?').$file;

				if ('index' === $link) $link = '';

				++$count;
				$menu .= str_replace('$submenu', '',
					str_replace('$target', '',
					str_replace('$count', $count,
					str_replace('$active', ($active ?
						'active-trail active' : ''),
					str_replace('$link', $link,
					str_replace('$label', $item,
					str_replace('$title', $title,
					str_replace('$class',
						makeNonEmptyAttr('class', $defautClass),
					str_replace('$style',
						makeNonEmptyAttr('style', ''),
					$itemTemplate)))))))));
			}
		}
	}

	return $menu;
}


function loadStyleVersion($cssName, $cssPath = null, $linkPath = '')
{
	global /*$downloadLevel, */$stylesVersions, $designFilesPath;

	if (null === $cssPath)
	{
		if (!isSet($designFilesPath)) $designFilesPath = '../design/';
		$cssFileName = $designFilesPath.$cssName.'.css';
	}
	else
		$cssFileName = $cssPath.$cssName.'.css';

	// var_dump($cssFileName);
	// if (isSet($downloadLevel))
	// 	$cssFileName = $downloadLevel.$cssFileName;

	if (file_exists($cssFileName))
		$stylesVersions[$linkPath.$cssName] = date('Y-n-j-G-i-s',
			filemtime($cssFileName));
	else
		$stylesVersions[$linkPath.$cssName] = '0';
}


function registerStyle($cssName, $linkPath = '', $loadPath = null)
{
	global $linkStyles;
	$linkStyles[$linkPath.$cssName] = true;
	loadStyleVersion($cssName, $loadPath, $linkPath);
}


function loadScriptVersion($jsName, $jsPath = null, $linkPath = '')
{
	global /*$downloadLevel, */$javaScriptsVersions, $designFilesPath;

	if (null === $jsPath)
	{
		if (!isSet($designFilesPath)) $designFilesPath = '../design/';
		$jsFileName = $designFilesPath.$jsName.'.js';
	}
	else
		$jsFileName = $jsPath.$jsName.'.js';

	// var_dump($jsFileName);
	// if (isSet($downloadLevel))
	// 	$jsFileName = $downloadLevel.$jsFileName;

	if (file_exists($jsFileName))
		$javaScriptsVersions[$linkPath.$jsName] = date('Y-n-j-G-i-s',
			filemtime($jsFileName));
	else
		$javaScriptsVersions[$linkPath.$jsName] = '0';
}


function registerScript($jsName, $linkPath = '', $loadPath = null)
{
	global $linkScripts;
	$linkScripts[$linkPath.$jsName] = true;
	loadScriptVersion($jsName, $loadPath, $linkPath);
}


class HTMLHeadManagement
{
	private static $metas = null;
	private static $links = null;

	public static function setMetaProperty($property, $content)
	{
		if (is_null(HTMLHeadManagement::$metas))
			HTMLHeadManagement::$metas = array();

		foreach (HTMLHeadManagement::$metas as $prop)
			if (is_array($prop) && isSet($prop['name']) &&
				$property == $prop['name']) return;

		HTMLHeadManagement::$metas[] =
			array('name' => $property, 'content' => $content);
	}

	public static function buildMetas()
	{
		$return = '';

		if (is_array(HTMLHeadManagement::$metas))
		{
			foreach (HTMLHeadManagement::$metas as $meta)
			{
				if (is_array($meta))
				{
					$return .= TAB.'<meta';
					foreach ($meta as $metaAttr => $metaVal)
						$return .= ' '.$metaAttr.'="'.$metaVal.'"';
					$return .= ' />'.EOL;
				}
			}
		}

		return $return;
	}


	public static function setSimpleLink($href, $rel)
	{
		if (is_null(HTMLHeadManagement::$links))
			HTMLHeadManagement::$links = array();

		foreach (HTMLHeadManagement::$links as $hLink)
			if (is_array($hLink) && isSet($hLink['rel']) &&
				$rel == $hLink['rel']) return;

		HTMLHeadManagement::$links[] =
			array('href' => $href, 'rel' => $rel);
	}

	public static function registerLangLink($href, $lang = 'sk', $sysSep = '?')
	{
		if (is_null(HTMLHeadManagement::$links))
			HTMLHeadManagement::$links = array();

		foreach (HTMLHeadManagement::$links as $hLink)
			if (is_array($hLink) && isSet($hLink['rel']) &&
				'alternate' == $hLink['rel'] &&
				isSet($hLink['hreflang']) &&
				$lang == $hLink['hreflang']) return;

		HTMLHeadManagement::$links[] = array('href' => $href,
			'rel' => 'alternate', 'hreflang' => $lang, 'sysSep' => $sysSep);
	}

	public static function postfixLangLink($postfix, $lang = 'sk')
	{
		if (!empty(HTMLHeadManagement::$links))
		{
			foreach (HTMLHeadManagement::$links as $index => $hLink)
				if (is_array($hLink) && isSet($hLink['rel']) &&
					'alternate' == $hLink['rel'] &&
					isSet($hLink['hreflang']) &&
					$lang == $hLink['hreflang'])
				{
					HTMLHeadManagement::$links
						[$index]['href'] .= HTMLHeadManagement::$links
						[$index]['sysSep'].$postfix;
					return;
				}
		}
	}

	public static function buildLinks()
	{
		$return = '';

		if (is_array(HTMLHeadManagement::$links))
		{
			// http://www.w3.org/TR/html5/links.html#linkTypes
			// http://microformats.org/wiki/existing-rel-values
			// (http://www.w3.org/QA/Tips/use-links)
			foreach (HTMLHeadManagement::$links as $hLink)
			{
				if (is_array($hLink))
				{
					$return .= TAB.'<link';
					foreach ($hLink as $hLAttr => $hLAVal)
					{
						if ('href' == $hLAttr)
							$return .= ' '.$hLAttr.'="'.
								InternalRedirects::replace($hLAVal).'"';
						elseif ('sysSep' != $hLAttr)
							$return .= ' '.$hLAttr.'="'.$hLAVal.'"';
					}
					$return .= ' />'.EOL;
				}
			}
		}

		return $return;
	}
}

class InternalRedirects
{
	private static function callback($matches)
	{
		global $internalRedirects;
		$address = $matches[1];
		$find = null;

		foreach ($internalRedirects as $replace)
		{
			if (null === $find) $find = $replace; else
			{
				$address = preg_replace($find, $replace, $address);
				$find = null;
			}
		}

		return '<a href="'.$address.'"';
	}

	public static function replace($address)
	{
		global $internalRedirects;
		if (!is_array($internalRedirects)) return $address;
		$find = null;

		foreach ($internalRedirects as $replace)
		{
			if (null === $find) $find = $replace; else
			{
				$address = preg_replace($find, $replace, $address);
				$find = null;
			}
		}

		return $address;
	}

	public static function solve($content)
	{
		global $internalRedirects;
		if (is_array($internalRedirects))
			return $content = preg_replace_callback('/<a href="([^"]+)"/i',
				array('InternalRedirects', 'callback'), $content);
		else return $content;
	}
}

include_once 'ExternalRedirects.php';

class LoginManagement
{
	public static $registerPath = '../register/';
	public static $loginTimeout = 2700; // 900 s == 15 min.
	public static $loginScript = '/login';
	public static $logoutScript = '/logout';
	public static $defaultHomeScript = '/domov';
	public static $defaultChpwdScript = '/zmena-hesla';
	public static $navigationEnabled = true;
	public static $userHomeItem = '';

	private static $sessionError = null;
	private static $loginShutdownPatchState = false;

	public static function logmanConfig()
	{
		global $logman;
		if (isSet($logman) && is_array($logman))
		{
			if (isSet($logman['regpath']))
				LoginManagement::$registerPath =
					$logman['regpath'];
			if (isSet($logman['linscript']))
				LoginManagement::$loginScript =
					$logman['linscript'];
			if (isSet($logman['loutscript']))
				LoginManagement::$logoutScript =
					$logman['loutscript'];
			if (isSet($logman['navenabled']))
				LoginManagement::$navigationEnabled =
					$logman['navenabled'];
			if (isSet($logman['defhome']))
				LoginManagement::$defaultHomeScript =
					$logman['defhome'];
			if (isSet($logman['defchpwd']))
				LoginManagement::$defaultChpwdScript =
					$logman['defchpwd'];
		}

		if (LoginManagement::$navigationEnabled)
			LoginManagement::loggedIn();
	}

	public static function sessionIsOpen()
	{
		// if (PHP_SESSION_ACTIVE != session_status()) return false;
		return false === LoginManagement::$sessionError && isSet($_SESSION);
	}

	public static function loggedIn()
	{
		LoginManagement::$loginShutdownPatchState = false;
		LoginManagement::$userHomeItem = '';

		if (null === LoginManagement::$sessionError)
			LoginManagement::$sessionError = !session_start();

		if (LoginManagement::$sessionError || empty($_SESSION['userID']))
			return false;

		$loginFile = LoginManagement::$registerPath.
			$_SESSION['userID'].'.login';

		if (!file_exists($loginFile)) return false;

		// Load the *.login file and split the lines:
		$lines = file_get_contents($loginFile);
		$lines = explode('<br />', nl2br($lines));
		foreach ($lines as $i => $line) $lines[$i] =
			preg_replace('/[ \r\n\t]+/', ' ', trim($line, ' '.EOLT));


		if (($lines[0] + LoginManagement::$loginTimeout) <= time() ||
			$lines[1] !== $_SERVER['HTTP_USER_AGENT'] ||
			$lines[2] !== $_SERVER['REMOTE_ADDR'])
		{
			LoginManagement::logOut();
			return false;
		}

		LoginManagement::$loginShutdownPatchState = true;
		LoginManagement::$userHomeItem =
			LoginManagement::buildUserHomeItem();

		$content = time().EOL.
			$_SERVER['HTTP_USER_AGENT'].EOL.
			$_SERVER['REMOTE_ADDR'].EOL;
		file_put_contents($loginFile, $content, LOCK_EX);

		return true;
	}

	public static function checkAccess($baseName, $accessType = 'read|write')
	{
		if (null === LoginManagement::$sessionError)
			LoginManagement::$sessionError = !session_start();

		if (false === LoginManagement::$sessionError &&
			!empty($_SESSION['userID']))
		{
			$userData = LoginManagement::reloadUserData($_SESSION['userID']);
			// echo '<pre>'; var_dump($userData); echo '</pre>';
			// echo '<pre>TYPE: '; var_dump($accessType); echo '</pre>';

			if (!empty($userData['grantAccess']) &&
				!empty($userData['grantPrivilege']))
			{
				$access = false;
				foreach ($userData['grantAccess'] as $i => $grantAccess)
				{
					/*echo '<pre>GRANT-TO & PRIVILEGE: ';
					var_dump($grantAccess[1]);
					var_dump($userData['grantPrivilege'][$i][1]);
					echo '</pre>';*/

					if (preg_match($grantAccess[1], $baseName))
					{
						if (preg_match('~\b('.$accessType.')\b~',
							$userData['grantPrivilege'][$i][1]))
							$access = true;
						elseif (preg_match('~\bdeny\b~',
							$userData['grantPrivilege'][$i][1]))
							$access = false;
					}
					// echo '<pre>'; var_dump($access); echo '</pre>';
				}
				return $access;
			}
		}

		return false;
	}

	public static function logOut()
	{
		if (null === LoginManagement::$sessionError)
			LoginManagement::$sessionError = !session_start();

		if (false === LoginManagement::$sessionError &&
			!empty($_SESSION['userID']))
		{
			$userData = LoginManagement::reloadUserData($_SESSION['userID']);
			if (is_array($userData))
			{
				LoginManagement::$loginShutdownPatchState = false;
				LoginManagement::$userHomeItem = '';
				$loginFile = LoginManagement::$registerPath.
					$_SESSION['userID'].'.login';
				if (file_exists($loginFile)) unlink($loginFile);
				unset($_SESSION['userID']);
				return true;
			}
		}
		return false;
	}

	public static function logIn($userID, $password)
	{
		$userData = LoginManagement::reloadUserData($userID);
		if (is_array($userData))
		{
			if (false === LoginManagement::$sessionError &&
				!empty($_SESSION['userID'])) LoginManagement::logOut();

			$loginFile = LoginManagement::$registerPath.$userID.'.login';

			if (md5($password) === $userData['userPassword'][1])
			{
				LoginManagement::$loginShutdownPatchState = true;
				LoginManagement::$userHomeItem =
					LoginManagement::buildUserHomeItem($userID);
				$_SESSION['userID'] = $userID;
				$content = time().EOL.
					$_SERVER['HTTP_USER_AGENT'].EOL.
					$_SERVER['REMOTE_ADDR'].EOL;
				file_put_contents($loginFile, $content, LOCK_EX);
				return true;
			}
		}

		return false;
	}

	public static function deployLoginDialog($divs = true)
	{
		global $designTexts, $javaScript2;

		if ($divs)
		{
			echo EOL.'<form name="login" method="post"><div class='.
				'"login-dialog">'.EOLT./*'<p class="title">'.$designTexts
				['login-dialog-title'].'</p>'.EOLT.*/'<p class="login">'.
				'<input type="text" name="login" value="" required='.
				'"required" autocomplete="on" placeholder="'.$designTexts
				['login-dialog-login'].'" class="login" /></p>'.EOLT.
				'<p class="password"><input type="password" name="password" '.
				'value="" required="required" placeholder="'.$designTexts
				['login-dialog-password'].'" class="password" /></p>'.EOLT.
				'<p class="submit"><input type="submit" value="'.
				$designTexts['login-dialog-submit'].'" class="submit" /></p>'.
				EOL.'</div></form>'.EOL.'<div class="clear"></div>'.EOL;
			$javaScript2 .= EOLT.'login.login.focus();'.EOL;
		}
		else
			echo EOL.'<form name="login" method="post"><table class='.
				'"login-dialog">'.EOLT./*'<tr><th colspan="2">'.$designTexts
				['login-dialog-title'].'</th></tr>'.EOLT.*/'<tr><td>'.
				$designTexts['login-dialog-login'].':</td><td><input type='.
				'"text" name="login" value="" required="required" />'.
				'</td></tr>'.EOLT.'<tr><td>'.$designTexts
				['login-dialog-password'].':</td><td><input type="password" '.
				'name="password" value="" required="required" /></td></tr>'.
				EOLT.'<tr><td colspan="2"><input type="submit" value="'.
				$designTexts['login-dialog-submit'].'" /></td></tr>'.
				EOL.'</table></form>'.EOL;
	}

	public static function deployChpwdDialog($divs = true)
	{
		global $designTexts, $javaScript2;

		if ($divs)
		{
			echo EOL.'<form name="chpwd" method="post"><div class='.
				'"chpwd-dialog">'.EOLT.'<p class="password"><input type='.
				'"password" name="password" value="" required="required" '.
				'placeholder="'.$designTexts['chpwd-dialog-password'].
				'" class="password" /></p>'.EOLT.'<p class="chpwd1"><input '.
				'type="password" name="chpwd1" value="" required="required" '.
				'autocomplete="on" placeholder="'.$designTexts
				['chpwd-dialog-chpwd1'].'" class="chpwd1" /></p>'.EOLT.
				'<p class="chpwd2"><input type="password" name="chpwd2" '.
				'value="" required="required" autocomplete="on" placeholder='.
				'"'.$designTexts['chpwd-dialog-chpwd2'].'" class="chpwd2" '.
				'/></p>'.EOLT.'<p class="submit"><input type="submit" '.
				'value="'.$designTexts['chpwd-dialog-submit'].'" class='.
				'"submit" /></p>'.EOL.'</div></form>'.EOL.
				'<div class="clear"></div>'.EOL;
			$javaScript2 .= EOLT.'chpwd.password.focus();'.EOL;
		}
		else
			echo EOL.'<form name="chpwd" method="post"><table class='.
				'"chpwd-dialog">'.EOLT.'<tr><td>'.$designTexts
				['chpwd-dialog-password'].':</td><td><input type="password" '.
				'name="password" value="" required="required" /></td></tr>'.
				EOLT.'<tr><td>'.$designTexts['chpwd-dialog-chpwd1'].
				':</td><td><input type="password" name="chpwd1" value="" '.
				'required="required" /></td></tr>'.EOLT.'<tr><td>'.
				$designTexts['chpwd-dialog-chpwd2'].':</td><td><input type='.
				'"password" name="chpwd2" value="" required="required" />'.
				'</td></tr>'.EOLT.'<tr><td colspan="2"><input type="submit" '.
				'value="'.$designTexts['chpwd-dialog-submit'].
				'" /></td></tr>'.EOL.'</table></form>'.EOL;
	}

	public static function reloadUserData($userID, $newPassword = null)
	{
		$source = LoginManagement::$registerPath.$userID.'.txu';
		$parsed = LoginManagement::$registerPath.$userID.'-parsed.php';

		if (!file_exists($source)) return false;

		$parsedDate = null;
		$sourceDate = filemtime($source);

		if (null === $newPassword && file_exists($parsed) &&
			($sourceDate <= ($parsedDate = filemtime($parsed))))
		{
			include './'.$parsed;
		}
		else
		{
			// Load contents and split it to lines
			$lines = file_get_contents($source);
			$lines = explode('<br />', nl2br($lines));

			// Trim whitespace
			foreach ($lines as $i => $line)
				$lines[$i] = preg_replace('/[ \r\n\t]+/', ' ',
					trim($line, ' '.EOLT));

			// Initialize variables
			$userData = array();
			$matches1 = $matches2 = null;
			$saveSource = false;

			// Parse user data
			foreach ($lines as $i => $line)
			{
				if (preg_match('/^([^;][^=\s]+)\s*=\s*(.*)$/',
					$line, $matches1))
				{
					if (preg_match('/^(.*)\[\]$/', $matches1[1], $matches2))
					{
						if ('grantAccess' === $matches2[1])
						{
							if ('all' === $matches1[2])
								$matches1[2] = '~.*~';
							else
							{
								$matches1[2] = '~^/?('.preg_replace(
									'/[,\s]+/', '|', $matches1[2]).')$~i';
							}
						}

						$userData[$matches2[1]][] =
							array($i, $matches1[2]);
					}
					else
					{
						$userData[$matches1[1]] =
							array($i, $matches1[2]);
					}
				}
				/*else
					$userData[] = array($i, $line); */
			}

			if (!isSet($userData['userPassword']))
			{
				$i++; $lines[$i] = 'userPassword=000000';
				$userData['userPassword'] = array($i, '000000');
				$saveSource = true;
			}

			if (!isSet($userData['passwordEncoded']))
			{
				$i++; $lines[$i] = 'passwordEncoded=no';
				$userData['passwordEncoded'] = array($i, 'no');
				$saveSource = true;
			}

			if ('yes' !== $userData['passwordEncoded'][1])
			{
				$userData['passwordEncoded'][1] = 'yes';
				$lines[$userData['passwordEncoded'][0]] =
					'passwordEncoded=yes';
				$userData['userPassword'][1] =
					md5($userData['userPassword'][1]);
				$lines[$userData['userPassword'][0]] =
					'userPassword='.$userData['userPassword'][1];
				$saveSource = true;
			}

			if (null !== $newPassword)
			{
				$userData['userPassword'][1] = md5($newPassword);
				$lines[$userData['userPassword'][0]] =
					'userPassword='.$userData['userPassword'][1];
				$saveSource = true;
			}

			// Save source data
			if ($saveSource)
			{
				$content = '';
				foreach ($lines as $line) $content .= $line.EOL;
				file_put_contents($source, rtrim($content).EOL, LOCK_EX);
			}

			// Save parsed data
			$content = '<'.'?php'.EOL2;
			$content .= '$userData = '.var_export($userData, true);
			$content .= ';'.EOL2.'?'.'>';

			file_put_contents($parsed, $content, LOCK_EX);
		}

		return $userData;
	}

	/**
	 * Builds item (link) navigating on login or logout page (script) which is
	 * depending on current login state.
	 * 
	 * It is based on $loginShutdownPatchState variable’s value… The problem
	 * is that in PHP 5.3.27 after the shutdown is lunched the function
	 * loggedIn() does not return true value even if it should‼
	 * This is not normal – there must be a bug somewhere in th PHP core.
	 * Until this bug is not patched, we must do it us such way…
	 */
	public static function buildNavigationItem()
	{
		global $designTexts;

		if (LoginManagement::$navigationEnabled)
		{
			// $x = LoginManagement::loggedIn();

			if (LoginManagement::$loginShutdownPatchState)
				return '<a href="'.LoginManagement::$logoutScript.
					'" class="logout-item">'.$designTexts
					['logout-dialog-submit'].'</a>';
			else
				return '<a href="'.LoginManagement::$loginScript.
					'" class="login-item">'.$designTexts
					['login-dialog-submit'].'</a>';
		}
		else return '';
	}

	private static function buildUserHomeItem($userID = null)
	{
		global $contentLanguage, $designTexts;

		if (null === $userID && isSet($_SESSION['userID']))
			$userID = $_SESSION['userID'];

		if (!empty($userID))
		{
			$userData = LoginManagement::reloadUserData($userID);
			if (is_array($userData))
			{
				$linkText = isSet($userData['userName']) ?
					$userData['userName'][1] : $designTexts['login-home'];

				if (isSet($userData['home–'.$contentLanguage]))
				{
					if (!empty($userData['home–'.$contentLanguage][1]))
						return '<a href="'.$userData['home–'.
							$contentLanguage][1].'" class="home-item">'.
							$linkText.'</a>';
				}
				elseif (isSet($userData['home']))
				{
					if (!empty($userData['home'][1]))
						return '<a href="'.$userData['home'][1].
							'" class="home-item">'.$linkText.'</a>';
				}
				elseif (!empty(LoginManagement::$defaultHomeScript))
				{
					return '<a href="'.LoginManagement::$defaultHomeScript.
						'" class="home-item">'.$linkText.'</a>';
				}
			}
		}

		return '';
	}

	public static function handleStandardLogin($deployDialog = true)
	{
		if (isSet($_POST) && isSet($_POST['login']) &&
			isSet($_POST['password']))
		{
			return LoginManagement::logIn($_POST['login'],
				$_POST['password']);
		}

		if ($deployDialog)
			LoginManagement::deployLoginDialog();
		return null;
	}

	public static function handleStandardChpwd($deployDialog = true)
	{
		if (isSet($_POST) && isSet($_POST['password']) &&
			isSet($_POST['chpwd1']) && isSet($_POST['chpwd2']))
		{
			if ($_POST['chpwd1'] === $_POST['chpwd2'] &&
				$_POST['password'] !== $_POST['chpwd1'] &&
				isSet($_SESSION['userID']))
			{
				$userData = LoginManagement::
					reloadUserData($_SESSION['userID']);

				if (md5($_POST['password']) === $userData['userPassword'][1])
				{
					LoginManagement::reloadUserData(
						$_SESSION['userID'], $_POST['chpwd1']);
					return true;
				}
			}
			return false;
		}

		if ($deployDialog)
			LoginManagement::deployChpwdDialog();
		return null;
	}

	public static function getSessionProperty($propname)
	{
		if ($propname !== 'userID')
		{
			if (null === LoginManagement::$sessionError)
				LoginManagement::$sessionError = !session_start();

			if (false === LoginManagement::$sessionError &&
				!empty($_SESSION[$propname])) return $_SESSION[$propname];
		}

		return null;
	}

	public static function setSessionProperty($propname, $propvalue)
	{
		if ($propname !== 'userID')
		{
			if (null === LoginManagement::$sessionError)
				LoginManagement::$sessionError = !session_start();

			if (false === LoginManagement::$sessionError)
				$_SESSION[$propname] = $propvalue;
		}
	}

	public static function saveSessionTime($timename)
	{
		if ($timename !== 'userID')
		{
			if (null === LoginManagement::$sessionError)
				LoginManagement::$sessionError = !session_start();

			if (false === LoginManagement::$sessionError)
				$_SESSION[$timename] = time();
		}
	}
}

LoginManagement::logmanConfig();

?>