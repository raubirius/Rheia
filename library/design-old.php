<?php

ob_start();

include_once 'library.php';

$mainMenu = '';
$leftMenu = '';
$rightMenu = '';
$bottomIcons = '';
$features = array('start' => '', 'core' => '', 'end' => '',
	'lang' => array());
$designCategory = 'index';

if (isSet($siteStructure))
{
	ConfigParser::procesHighlights();
	$mainMenu .= TAB3.'<ul class="items">'.EOL;

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
			if (is_array($menu))
			{
				if (empty($menu['hidden']))
				{
					$mainMenu .= TAB3.'<li><a href="';

					if (isSet($menu['link']))
						$mainMenu .= (!empty($menu['link']) &&
							'/' === $menu['link'][0] ? '' :
							'/'.$siteSectionPath).$menu['link'];
					else if (isSet($menu['http']))
						$mainMenu .= 'http://'.$menu['http'];
					else if (isSet($menu['https']))
						$mainMenu .= 'https://'.$menu['https'];
					else
						$mainMenu .= $siteSectionPath.('index' === $file ?
							'': $file);

					/*
					$alias = isSet($selectedItem) ? $selectedItem : $category;
					for ($i = 0; isSet($siteContentConfig
						[$alias]['alias']) && $i < 10; ++$i)
						$alias = $siteContentConfig[$alias]['alias'];

					$active = ($alias === $file || $selectedItem === $file ||
						$category === $file);
					*/

					$active = $category == $file;

					if (isSet($siteContentConfig[null][$file]) &&
						isSet($siteContentConfig[null][$file]['active']) &&
						$siteContentConfig[null][$file]['active'])
							$active = true;

					$mainMenu .= '"'.($active ? ' class="active"' :
						'').'>'.$menu['label'].'</a></li>'.EOL;
				}
			}
			else
			{
				$mainMenu .= TAB3.'<li><a href="/'.$siteSectionPath.
					('index' === $file ? '': $file).'"'.
					(($category == $file) ? ' class="active"' : '').
					'>'.$menu.'</a></li>'.EOL;
			}
		}
	}

	$mainMenu .= TAB3.'</ul>'.EOL;


	// echo '<!--';
	// echo 'debug: '; var_dump($siteContentConfig['debug']);
	// echo 'bakalarske: '; var_dump($siteContentConfig['bakalarske']);
	// echo 'kontakt: '; var_dump($siteContentConfig['kontakt']);
	// echo '-->';


	if (isSet($siteStructure['left-menu-before']))
		$leftMenu .= makeMenu($siteStructure['left-menu-before']);

	if (isSet($siteStructure[$category]) &&
		is_array($siteStructure[$category]) &&
		isSet($siteStructure[$category]['left-menu']))
		$leftMenu .= makeMenu($siteStructure[$category]['left-menu']);

	if (isSet($siteStructure['left-menu-after']))
		$leftMenu .= makeMenu($siteStructure['left-menu-after']);

	if (!empty($leftMenu))
	{
		$leftMenu = TAB3.'<div class="left-menu">'.EOL.
			TAB4.'<ul class="items">'.EOL.$leftMenu.
			TAB4.'</ul>'.EOL.TAB3.'</div>'.EOL;
	}


	if (isSet($siteStructure['right-menu-before']))
		$rightMenu .= makeMenu($siteStructure['right-menu-before']);

	if (isSet($siteStructure[$category]) &&
		is_array($siteStructure[$category]) &&
		isSet($siteStructure[$category]['right-menu']))
		$rightMenu .= makeMenu($siteStructure[$category]['right-menu']);

	if (isSet($siteStructure['right-menu-after']))
		$rightMenu .= makeMenu($siteStructure['right-menu-after']);

	if (!empty($rightMenu))
	{
		$rightMenu = TAB3.'<div class="right-menu">'.EOL.
			TAB4.'<ul class="items">'.EOL.$rightMenu.
			TAB4.'</ul>'.EOL.TAB3.'</div>'.EOL;
	}


	if (isSet($siteStructure['bottom-icons']))
		$bottomIcons .= makeMenu($siteStructure['bottom-icons']);

	if (!empty($bottomIcons))
	{
		$bottomIcons = TAB3.'<div class="bottom-icons">'.EOL.
			TAB4.'<ul class="items">'.EOL.$bottomIcons.
			TAB4.'</ul>'.EOL.TAB3.'</div>'.EOL;
	}
}


if (isSet($siteContentConfig['site-info']))
{
	$features['core'] .= TAB3.'<a href="'.$siteContentConfig['site-info']
		['uri'].'" class="info-item feature-icon"><img src="'.$designURI.
		'null.gif" class="info" title="'.$designTexts['design-feature-info'].
		'" alt="'.$designTexts['design-feature-info'].'" />'.$designTexts
		['design-feature-info'].'</a>'.EOL;
}

$features['core'] .= TAB3.'<a href="javascript:window.print()" class="'.
	'print-item feature-icon"><img src="'.$designURI.'null.gif" class='.
	'"print" title="'.$designTexts['design-feature-print'].'" alt="'.
	$designTexts['design-feature-print'].'" />'.$designTexts
	['design-feature-print'].'</a>'.EOL;


if (isSet($siteContentConfig[$selectedItem]) &&
	isSet($siteContentConfig[$selectedItem]['sk'])/* &&
	is_array($siteContentConfig[$selectedItem]['sk'])*/)
{
	$features['lang']['sk'] = array(
		TAB3.'<a href="'.($href =
		(!empty($siteContentConfig[$selectedItem]['sk']) &&
			'/' === $siteContentConfig[$selectedItem]['sk'][0] ?
			$siteContentConfig[$selectedItem]['sk'] :
		('../'.((isSet($siteContentConfig[$category]) &&
			isSet($siteContentConfig[$category]['sk'])) ?
				($siteContentConfig[$category]['sk'].'?') :
				'').$siteContentConfig[$selectedItem]['sk']))),
		'" class="language-item flag-sk"><img src="'.$designURI.
		'null.gif" alt="" />Slovenčina</a>'.EOL, '&');
	if (isSet($siteContentConfig[$selectedItem]['sk-separator']))
		$features['lang']['sk'][2] =
			$siteContentConfig[$selectedItem]['sk-separator'];
	HTMLHeadManagement::registerLangLink($href, 'sk', '&');
}
elseif (isSet($siteContentConfig[$category]) &&
	isSet($siteContentConfig[$category]['sk']))
{
	$features['lang']['sk'] = array(
		TAB3.'<a href="'.($href =
		(!empty($siteContentConfig[$category]['sk']) &&
			'/' === $siteContentConfig[$category]['sk'][0] ? '' : '../').
			$siteContentConfig[$category]['sk']), '" class="language-item '.
		'flag-sk"><img src="'.$designURI.'null.gif" alt="" />Slovenčina</a>'.
		EOL, '?');
	if (isSet($siteContentConfig[$category]['sk-separator']))
		$features['lang']['sk'][2] =
			$siteContentConfig[$category]['sk-separator'];
	HTMLHeadManagement::registerLangLink($href);
}
elseif (isSet($translation) && 'Slovenčina' != $translation['language'])
{
	$features['lang']['sk'] = TAB3.'<a href=".." class="language-item '.
		'flag-sk"><img src="'.$designURI.'null.gif" alt="" />Slovenčina</a>'.
		EOL;
	HTMLHeadManagement::registerLangLink('..');
}
else
	$features['lang']['sk'] = TAB3.'<span class="language-item '.
		'flag-sk"><img src="'.$designURI.'null.gif" alt="" />Slovenčina</span>'.
		EOL;


if (isSet($siteContentConfig[$selectedItem]) &&
	isSet($siteContentConfig[$selectedItem]['en'])/* &&
	is_array($siteContentConfig[$selectedItem]['en'])*/)
{
	$features['lang']['en'] = array(
		TAB3.'<a href="'.($href =
		(!empty($siteContentConfig[$selectedItem]['en']) &&
			'/' === $siteContentConfig[$selectedItem]['en'][0] ?
			$siteContentConfig[$selectedItem]['en'] :
		('english/'.((isSet($siteContentConfig[$category]) &&
			isSet($siteContentConfig[$category]['en'])) ?
				($siteContentConfig[$category]['en'].'?') :
				'').$siteContentConfig[$selectedItem]['en']))),
		'" class="language-item flag-en"><img src="'.$designURI.
		'null.gif" alt="" />English</a>'.EOL, '&');
	if (isSet($siteContentConfig[$selectedItem]['en-separator']))
		$features['lang']['en'][2] =
			$siteContentConfig[$selectedItem]['en-separator'];
	HTMLHeadManagement::registerLangLink($href, 'en', '&');
}
elseif (isSet($siteContentConfig[$category]) &&
	isSet($siteContentConfig[$category]['en']))
{
	$features['lang']['en'] = array(
		TAB3.'<a href="'.($href =
			(!empty($siteContentConfig[$category]['en']) &&
			'/' === $siteContentConfig[$category]['en'][0] ? '' : 'english/').
		$siteContentConfig[$category]['en']), '" class="language-item '.
		'flag-en"><img src="'.$designURI.'null.gif" alt="" />English</a>'.
		EOL, '?');
	if (isSet($siteContentConfig[$category]['en-separator']))
		$features['lang']['en'][2] =
			$siteContentConfig[$category]['en-separator'];
	HTMLHeadManagement::registerLangLink($href, 'en');
}
else
	$features['lang']['en'] = TAB3.'<span class="language-item '.
		'flag-en"><img src="'.$designURI.'null.gif" alt="" />English</span>'.
		EOL;


$features['end'] .= TAB3.'<input id="search" type="text" name="q" '.
	'value="" onkeypress="checkSubmit(\'search\')" autocomplete="on" '.
	'class="search" />'.EOL.TAB3.'<a href="javascript:search(\'search'.
	'\');" class="search">'.$designTexts['design-feature-search'].'</a>'.EOL;

if (isSet($siteContentConfig['rss']))
	$features['end'] .= TAB3.'<a href="'.$siteContentConfig['rss'].
		'" class="feature-icon" target="_blank" rel="noopener"><img src="'.
		$designURI.'null.gif" class="rss" title="RSS" alt="RSS" /></a>'.EOL;
else
	$features['end'] .= TAB3.'<span class="feature-icon"><img src="'.
		$designURI.'null.gif" class="rss" alt="RSS" /></span>'.EOL;


if (isSet($translation) && 'English' == $translation['language'])
	$contentLanguage = 'en';
else
	$contentLanguage = 'sk';

$title = null; $style = ''; $javaScript1 = ''; $javaScript2 = '';
$javaScript3 = ''; $linkStyles = array(); $linkJavaScripts = array();
$langPostfix = array();

if (empty($siteSection))
	$siteSection = $designTexts['design-faculty'].
		$designTexts['design-faculty-university-separator'].
		$designTexts['design-of-university'];
		// 'Pedagogická fakulta Trnavskej univerzity v Trnave'

header('Content-Type: text/html; charset=UTF-8');
header('Content-Language: '.$contentLanguage);

?><!DOCTYPE html>
<html lang="<?php echo $contentLanguage; ?>">
<head>
	<meta charset="UTF-8" />
<?php $header = ob_get_clean(); ob_start(); ?>

<noscript><style>
div.page-content-loading
{
	display: none;
}

div.page-content-hidden
{
	display: initial;
}
</style></noscript>
</head>


<body onload="onLoadPage();" onbeforeunload="onUnloadPage();">
<div id="page-frame" class="page-frame">
	<div class="gradient-top"><div class="logo-top"> </div></div>
	<div class="main"><?php
			$features['start'] = EOL.ob_get_clean().EOL; ob_start();
		?>
		<div class="header"><p><?php echo '© 2005 – '.date('Y').' '.
			$siteSection.', '.$designTexts['design-rights-reserved'].'. '.
			$designTexts['design-todays-date'].' '.date('j. n. Y').'.';
		?></p></div>

		<div class="banner">
			<a href="https://www.truni.sk/<?php
				if (isSet($universityBannerPath)) echo $universityBannerPath; ?>" class="banner-truni<?php
			if (!empty($designTexts['design-language-code']))
				echo ' '.$designTexts['design-language-code']; ?>"
				target="_blank" rel="noopener"></a><a href="/<?php
				if (isSet($facultyBannerPath)) echo $facultyBannerPath; ?>"
				class="banner-pdf<?php
			if (!empty($designTexts['design-language-code']))
				echo ' '.$designTexts['design-language-code']; ?>"></a><div class="clear"></div>
		</div>

		<div class="main-menu-dummy" id="main_menu_dummy" style="display: none"></div>

		<div class="main-menu" id="main_menu"><?php echo EOL.$mainMenu.EOL; ?>
		</div>
		<div class="clear"></div><?php
			echo EOL;
			if (isSet($siteContentConfig[$category]) &&
				isSet($siteContentConfig[$category]['poster']))
				echo EOL.TAB2.'<div class="poster"><img src="'.$designURI.
					$siteContentConfig[$category]['poster'].
					'.jpeg" alt="Poster" /></div>'.EOL;
			echo EOL;
		?>
		<div id="page-core" class="page-core"><?php echo EOL.$leftMenu.EOL; ?>
			<div id="page-content-loading"
				class="page-content-loading page-content"><p
				class="page-content-loading"><?php echo $designTexts
				['design-loading']; ?>…</p></div>
			<div id="page-content" class="page-content page-content-hidden"><?php
				$bodyStart = ob_get_clean(); ob_start();
			?></div><?php echo EOL2.$rightMenu.EOL; ?>
			<div class="clear"></div>
		</div>

		<div class="footer"><?php

echo '<p>© 2005 – '.date('Y').' '.$designTexts['design-faculty'].
	$designTexts['design-faculty-university-separator'].
	'<a href="https://www.truni.sk/" target="_blank" rel="noopener">'.
	$designTexts['design-of-university'].'</a>, '.
	$designTexts['design-rights-reserved'].'. '.
	$designTexts['design-todays-date'].' '.date('j. n. Y');

include_once 'NameDays.php';
$todaysNames = NameDays::getNames();
if (!empty($todaysNames)) echo ', '.$todaysNames;
echo '.</p>';

/**if (empty($siteSectionPath))
	echo '<p><a href="https://www.facebook.com/PedagogickaFakulta.'.
		'TrnavskaUniverzita.Trnava" target="_blank" rel="noopener"><img src="'.
		$designURI.'facebook-logo.png" alt="Facebook" title="Pedagogická '.
		'fakulta TU na Facebooku" /></a></p>';/**/

if (!empty($bottomIcons)) echo EOL2.$bottomIcons.EOL.'<div class="clear"></div>';

?></div>
	</div>
	<div class="gradient-bottom"><div class="logo-bottom"> </div></div>
</div>
<div id="topmost" class="topmost" style="display: none;"></div>
<form name="sf" method="post" action="search" style="display: none;"><input type="text" name="q" value="" required="required" /></form>
</body>
</html><?php

loadStyleVersion('style-old');
loadStyleVersion('print-old');

loadScriptVersion('script');

function onShutdown()
{
	global $header, $title, $features, $siteSection, $style, $javaScript1,
		$javaScript2, $javaScript3, $linkStyles, $linkJavaScripts,
		$langPostfix, $bodyStart, $bodyEnd, $siteContentConfig, $designTexts,
		$permalink, $stylesVersions, $javaScriptsVersions, $designURI;
		//, $domain;

	$bodyCore = ob_get_clean();

	echo $header;

	echo HTMLHeadManagement::buildMetas();

	echo TAB.'<title>'.(empty($title) ? '' : ($title.
		' | ')).$siteSection.'</title>'.EOL;

	if (isSet($permalink))
		// echo TAB.'<link href="'.$permalink.'" rel="bookmark" />'.EOL; // not allowed
		echo TAB.'<link href="'.$permalink.'" rel="permalink" />'.EOL;

	foreach ($features['lang'] as $code => $content)
	{
		if (is_array($content) && isSet($langPostfix[$code]))
			HTMLHeadManagement::postfixLangLink($langPostfix[$code], $code);
	}

	echo HTMLHeadManagement::buildLinks();

	if (isSet($siteContentConfig['rss']))
		echo TAB.'<link href="'.//$protocol.'://'.$domain.//not mandatory
			$siteContentConfig['rss'].
			'" rel="alternate" type="application/rss+xml" '.
			'title="Pedagogická fakulta Trnavskej univerzity v Trnave – '.
			'RSS" />'.EOL;

	echo TAB.'<link href="'.$designURI.'style-old.css?v='.
		$stylesVersions['style-old'].'" rel="stylesheet" type='.
		'"text/css" />'.EOL;
	if (!empty($linkStyles))
		foreach ($linkStyles as $key => $val)
			if ($val) echo TAB.'<link href="'.$designURI.$key.'.css?v='.
				(isSet($stylesVersions[$key]) ? $stylesVersions[$key] : '').
				'" rel="stylesheet" type="text/css" />'.EOL;
	echo TAB.'<link href="'.$designURI.'print-old.css?v='.
		$stylesVersions['print-old'].'" rel="stylesheet" type="text/css" '.
		'media="print, projection" />'.EOL;
	echo TAB.'<link href="'.$designURI.'favicon.png" rel="shortcut icon" />'.
		EOL;

	echo TAB.'<script src="'.$designURI.'script.js?v='.
		$javaScriptsVersions['script'].'" type="text/javascript" '.
		'charset="utf-8"></script>'.EOL;
	if (!empty($linkJavaScripts))
		foreach ($linkJavaScripts as $key => $val)
			if ($val) echo TAB.'<script src="'.$designURI.$key.'.js?v='.
				(isSet($javaScriptsVersions[$key]) ?
					$javaScriptsVersions[$key] : '').
				'" type="text/javascript" charset="utf-8"></script>'.EOL;

	if (!empty($style))
		echo '<style type="text/css">'.EOL.$style.'</style>'.EOL;

	echo '<script type="text/javascript"><!--'.EOL.'var myGlobals = [];'.
		EOL2.'function emptySearchAlert()'.EOL.'{'.EOLT.'alert(\''.
		$designTexts['design-empty-search-alert'].'\');'.EOL.'}'.EOL2.
		'function tabNotExistsAlert()'.EOL.'{'.EOLT.'alert(\''.
		$designTexts['design-tab-not-exists'].'\');'.EOL.'}'.EOL2.
		'function onLoadPage()'.EOL.'{'.EOLT.'initInOnLoad();'.EOL;
	if (!empty($javaScript1)) echo $javaScript1;
	echo TAB.'setClass(\'page-content\', \'page-content\');'.EOLT.
		'setClass(\'page-content-loading\', \'page-content-hidden\');'.
		EOLT.'initAfterDisplayed();'.EOL;
	if (!empty($javaScript2)) echo $javaScript2;
	echo '}'.EOL2.'function onUnloadPage()'.EOL.'{'.EOL;
	if (!empty($javaScript3)) echo $javaScript3;
	echo '}'.EOL2.'// -->'.EOL.'</script>'.EOL;

	echo $features['start'];
	echo TAB2.'<div class="page-features">'.EOL;
	echo $features['core'];
	foreach ($features['lang'] as $code => $content)
	{
		if (is_array($content))
		{
			echo InternalRedirects::solve($content[0].
				(isSet($langPostfix[$code]) ? ($content[2].
					$langPostfix[$code]) : '').$content[1]);
		}
		else echo InternalRedirects::solve($content);
	}

	echo TAB3.LoginManagement::$userHomeItem.EOL;
	echo TAB3.LoginManagement::buildNavigationItem().EOL;

	echo $features['end'];
	echo TAB2.'</div>'.EOL2;

	echo $bodyStart;

	if (isSet($_SESSION) && isSet($_SESSION['history']) &&
		is_array($_SESSION['history']))
	{
		// echo '<pre>'; var_dump($_SESSION['history']); echo '</pre>';

		echo '<div class="site-history"><p>'; $first = true;
		for ($i = 1; $i <= 4; ++$i)
			if (isSet($_SESSION['history'][$i]))
			{
				if ($first) $first = false; else echo ' <b>∙</b> ';

				$link = '/';//.$_SESSION['history'][$i][0];
				if (!empty($_SESSION['history'][$i][1]))
					$link .= $_SESSION['history'][$i][1];
				if (!empty($_SESSION['history'][$i][2]))
					$link .= '?'.$_SESSION['history'][$i][2];
				if (!empty($_SESSION['history'][$i][3]))
					$link .= '&'.$_SESSION['history'][$i][3];

				$text = $_SESSION['history'][$i][4];
				if (preg_match('/^(\X{0,30})(\pL*)/u', $text, $matches))
				{
					if (strlen($matches[2]))
						$text = trim(preg_replace(
							'/\pL*$/u', '', $matches[1]));
					else
						$text = trim($matches[1]);

					if (strlen($text) != strlen(trim(
						$_SESSION['history'][$i][4])))
						$text .= '…';
				}

				echo '<a href="'.$link.'">'.$text.'</a>';
			}
			echo '</p></div>';
	}

	echo $bodyCore;
	/** /
	if (isSet($siteContentConfig['ical']))
	{
		echo TAB.'<pre>';
		var_dump($siteContentConfig['ical']);
		echo '</pre>'.EOL;
	}
	/**/
	echo $bodyEnd;
}

register_shutdown_function('onShutdown');
$bodyEnd = ob_get_clean(); ob_start();

?>