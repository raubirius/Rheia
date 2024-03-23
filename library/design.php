<?php

ob_start();

include_once 'library.php';

$mainMenu = '';
$topMenu = '';
$leftMenu = '';
$centralMenu = '';
$bottomMenu = '';
// $bottomIcons = '';
$features = array('start' => '', 'core' => '', 'end' => '', 'lang' => array());
$designCategory = 'index';

if (isSet($siteStructure))
{
	ConfigParser::procesHighlights();
	$mainMenu .= TAB3.'<ul class="menu nav">'.EOL;

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
					$active = $category == $file;

					$mainMenu .= TAB3.'<li class="leaf'.
						($active ? ' active-trail active' : '').
						'"><a href="';

					if (isSet($menu['link']))
						$mainMenu .= (!empty($menu['link']) &&
							'/' === $menu['link'][0] ? '' :
							'/'.$siteSectionPath).$menu['link'];
					else
						$mainMenu .= $siteSectionPath.
							('index' === $file ? '': $file);

					if (isSet($siteContentConfig[null][$file]) &&
						isSet($siteContentConfig[null][$file]['active']) &&
						$siteContentConfig[null][$file]['active'])
							$active = true;

					$mainMenu .= '"'.($active ? ' class="active-trail active"' :
						'').'>'.$menu['label'].'</a></li>'.EOL;
				}
			}
			else
			{
				$active = $category == $file;

				$mainMenu .= TAB3.'<li class="leaf'.($active ?
					' active-trail active' : '').'"><a href="/'.
					$siteSectionPath.('index' === $file ? '': $file).
					'"'.($active ? ' class="active-trail active"' :
					'').'>'.$menu.'</a></li>'.EOL;
			}
		}
	}

	// replaceFirst($mainMenu, 'class="leaf', 'class="first leaf');
	// replaceLast($mainMenu, 'class="leaf', 'class="last leaf');

	$mainMenu .= TAB3.'</ul>'.EOL;


	// echo '<!--';
	// echo 'debug: '; var_dump($siteContentConfig['debug']);
	// echo 'bakalarske: '; var_dump($siteContentConfig['bakalarske']);
	// echo 'kontakt: '; var_dump($siteContentConfig['kontakt']);
	// echo '-->';


	// ‼ TODO ‼

	if (isSet($siteStructure['top-menu-before']))
		$topMenu .= makeMenuFromTemplates($siteStructure['top-menu-before'],
			'', TAB2.'<a href="$link">'.EOL.TAB3.
			'<div class="col-xs-12 col-sm-15 faculty-box faculty-box-$count">'.
			EOL.TAB4.'<div class="faculty-content">'.EOL.TAB4.TAB.
			'<div class="faculty-name">$label</div>'.EOL.TAB4.'</div>'.
			EOL.TAB3.'</div>'.EOL.TAB2.'</a>'.EOL);

	// if (isSet($siteStructure[$category]) &&
	// 	is_array($siteStructure[$category]) &&
	// 	isSet($siteStructure[$category]['top-menu']))
	// 	$topMenu .= makeMenuFromTemplates($siteStructure[$category]['top-menu'],
	// 		'', TAB2.'<a href="$link">'.EOL.TAB3.
	// 		'<div class="col-xs-12 col-sm-15 faculty-box faculty-box-$count">'.
	// 		EOL.TAB4.'<div class="faculty-content">'.EOL.TAB4.TAB.
	// 		'<div class="faculty-name">$label</div>'.EOL.TAB4.'</div>'.
	// 		EOL.TAB3.'</div>'.EOL.TAB2.'</a>'.EOL);

	// if (isSet($siteStructure['top-menu-after']))
	// 	$topMenu .= makeMenuFromTemplates($siteStructure['top-menu-after'],
	// 		'', TAB2.'<a href="$link">'.EOL.TAB3.
	// 		'<div class="col-xs-12 col-sm-15 faculty-box faculty-box-$count">'.
	// 		EOL.TAB4.'<div class="faculty-content">'.EOL.TAB4.TAB.
	// 		'<div class="faculty-name">$label</div>'.EOL.TAB4.'</div>'.
	// 		EOL.TAB3.'</div>'.EOL.TAB2.'</a>'.EOL);

	// if (!empty($topMenu))
	// 	// $topMenu = TAB3.'<ul class="menu nav">'.EOL.$topMenu.TAB3.'</ul>'.EOL
	// ;
	// else
	// 	$topMenu = '<!-- DEBUG: Top menu is empty. -->';


	if (isSet($siteStructure['bottom-menu-before']))
		$bottomMenu .= makeMenuFromTemplates($siteStructure['bottom-menu-before'],
			'', TAB.'<li><a href="$link"$target$title>$label</a></li>'.EOL,
			'<li><b>$label</b></li>', TAB.'</ul></section>'.EOL2.TAB.
			'<section class="block block-menu clearfix"><ul class="menu nav">');
			// $structure, $defautClass, $itemTemplate, $labelTemplate,
			// $separatorTemplate, $cardTemplate

	// if (!empty($bottomMenu))
	// 	// $bottomMenu = TAB3.'<ul class="menu nav">'.EOL.$bottomMenu.TAB3.'</ul>'.EOL
	// ;
	// else
	// 	$bottomMenu .= '<!-- DEBUG: Bottom menu is empty. -->';


	if (isSet($siteStructure['left-menu-before']))
		$leftMenu .= makeMenu($siteStructure['left-menu-before'],
			'', TAB4.'<ul>'.EOL, TAB4.'</ul>'.EOL, true);

	if (isSet($siteStructure[$category]) &&
		is_array($siteStructure[$category]) &&
		isSet($siteStructure[$category]['left-menu']))
		$leftMenu .= makeMenu($siteStructure[$category]['left-menu'],
			'', TAB4.'<ul>'.EOL, TAB4.'</ul>'.EOL, true);

	if (isSet($siteStructure['left-menu-after']))
		$leftMenu .= makeMenu($siteStructure['left-menu-after'],
			'', TAB4.'<ul>'.EOL, TAB4.'</ul>'.EOL, true);

	if (!empty($leftMenu))
	{
		$leftMenu =
			// TAB3.'<div class="left-menu">'.EOL.
			// TAB4.'<ul class="items">'.EOL.
			TAB3.'<ul class="menu nav">'.EOL.
			$leftMenu.
			TAB3.'</ul>'.EOL;
			// TAB3.'</div>'.EOL;
	}


	if (isSet($siteStructure['central-menu-before']))
		$centralMenu .= makeMenu($siteStructure['central-menu-before'],
			'', TAB4.'<ul>'.EOL, TAB4.'</ul>'.EOL, true);

	if (isSet($siteStructure[$category]) &&
		is_array($siteStructure[$category]) &&
		isSet($siteStructure[$category]['central-menu']))
		$centralMenu .= makeMenu($siteStructure[$category]['central-menu'],
			'', TAB4.'<ul>'.EOL, TAB4.'</ul>'.EOL, true);

	if (isSet($siteStructure['central-menu-after']))
		$centralMenu .= makeMenu($siteStructure['central-menu-after'],
			'', TAB4.'<ul>'.EOL, TAB4.'</ul>'.EOL, true);

	if (!empty($centralMenu))
		$centralMenu = TAB3.'<div class="menu-block-wrapper menu-block-1 '.
				'menu-name-menu-truni-menu parent-mlid-0 menu-level-3">'.
				'<ul class="menu nav">'.EOL.$centralMenu.TAB3.'</ul></div>'.EOL;


	/*
	if (isSet($siteStructure['bottom-menu-before']))
		$bottomMenu .= makeMenu($siteStructure['bottom-menu-before']);

	if (isSet($siteStructure[$category]) &&
		is_array($siteStructure[$category]) &&
		isSet($siteStructure[$category]['bottom-menu']))
		$bottomMenu .= makeMenu($siteStructure[$category]['bottom-menu']);

	if (isSet($siteStructure['bottom-menu-after']))
		$bottomMenu .= makeMenu($siteStructure['bottom-menu-after']);

	if (!empty($bottomMenu))
	{
		$bottomMenu = TAB3.'<div class="bottom-menu">'.EOL.
			TAB4.'<ul class="items">'.EOL.$bottomMenu.
			TAB4.'</ul>'.EOL.TAB3.'</div>'.EOL;
	}
	*/


	/*
	if (isSet($siteStructure['bottom-icons']))
		$bottomIcons .= makeMenu($siteStructure['bottom-icons']);

	if (!empty($bottomIcons))
	{
		$bottomIcons = TAB3.'<div class="bottom-icons">'.EOL.
			TAB4.'<ul class="items">'.EOL.$bottomIcons.
			TAB4.'</ul>'.EOL.TAB3.'</div>'.EOL;
	}
	*/
}

$features['core'] .= TAB3.'<section id="block-menu-menu-top-header-menu" class="block block-menu clearfix"><ul class="menu nav">'.EOL;

// if (isSet($siteContentConfig['site-info']))
// {
// 	$features['core'] .= TAB3.'<li class="leaf"><a href="'.
// 		$siteContentConfig['site-info']['uri'].
// 		'" class="info-item feature-icon"><img src="'.$designURI.
// 		'null.gif" class="info" title="'.$designTexts['design-feature-info'].
// 		'" alt="'.$designTexts['design-feature-info'].'" />'.$designTexts
// 		['design-feature-info'].'</a></li>'.EOL;
// }

// $features['core'] .= TAB3.'<li class="leaf"><a href="javascript:window.print()"'.
// 	' class="print-item feature-icon"><img src="'.$designURI.'null.gif" class='.
// 	'"print" title="'.$designTexts['design-feature-print'].'" alt="'.
// 	$designTexts['design-feature-print'].'" />'.$designTexts
// 	['design-feature-print'].'</a></li>'.EOL;

$features['core'] .= TAB3.'<li class="leaf"><a href="https://www.truni.sk/'.
	(isSet($universityBannerPath) ? $universityBannerPath : '').'" '.
	'title="'.$designTexts['design-university'].'" target="_blank" '.
	'rel="noopener">'.$designTexts['design-university'].'</a></li>'.EOL;

$features['core'] .= TAB3.'</ul></section>'.EOL2;


$features['core'] .= TAB3.'<section id="block-locale-language" class="block block-locale clearfix"><ul class="language-switcher-locale-url">'.EOL;


if (isSet($siteContentConfig[$selectedItem]) &&
	isSet($siteContentConfig[$selectedItem]['sk'])/* &&
	is_array($siteContentConfig[$selectedItem]['sk'])*/)
{
	$features['lang']['sk'] = array(
		TAB3.'<li><a href="'.($href =
		(!empty($siteContentConfig[$selectedItem]['sk']) &&
			'/' === $siteContentConfig[$selectedItem]['sk'][0] ?
			$siteContentConfig[$selectedItem]['sk'] :
		('../'.((isSet($siteContentConfig[$category]) &&
			isSet($siteContentConfig[$category]['sk'])) ?
				($siteContentConfig[$category]['sk'].'?') :
				'').$siteContentConfig[$selectedItem]['sk']))),
		'" class="language-item flag-sk"><img src="'.$designURI.
		'null.gif" alt="" class="language-icon img-responsive" />Slovenčina</a></li>'.EOL, '&');
	if (isSet($siteContentConfig[$selectedItem]['sk-separator']))
		$features['lang']['sk'][2] =
			$siteContentConfig[$selectedItem]['sk-separator'];
	HTMLHeadManagement::registerLangLink($href, 'sk', '&');
}
elseif (isSet($siteContentConfig[$category]) &&
	isSet($siteContentConfig[$category]['sk']))
{
	$features['lang']['sk'] = array(
		TAB3.'<li><a href="'.($href =
		(!empty($siteContentConfig[$category]['sk']) &&
			'/' === $siteContentConfig[$category]['sk'][0] ? '' : '../').
			$siteContentConfig[$category]['sk']), '" class="language-item '.
		'flag-sk"><img src="'.$designURI.'null.gif" alt="" class="language-icon img-responsive" />Slovenčina</a></li>'.
		EOL, '?');
	if (isSet($siteContentConfig[$category]['sk-separator']))
		$features['lang']['sk'][2] =
			$siteContentConfig[$category]['sk-separator'];
	HTMLHeadManagement::registerLangLink($href);
}
elseif (isSet($translation) && 'Slovenčina' != $translation['language'])
{
	$features['lang']['sk'] = TAB3.'<li><a href=".." class="language-item '.
		'flag-sk"><img src="'.$designURI.'null.gif" alt="" class="language-icon img-responsive" />Slovenčina</a></li>'.
		EOL;
	HTMLHeadManagement::registerLangLink('..');
}
else
	$features['lang']['sk'] = TAB3.'<li><span class="language-item '.
		'flag-sk"><img src="'.$designURI.'null.gif" alt="" class="language-icon img-responsive" />Slovenčina</span></li>'.
		EOL;


if (isSet($siteContentConfig[$selectedItem]) &&
	isSet($siteContentConfig[$selectedItem]['en'])/* &&
	is_array($siteContentConfig[$selectedItem]['en'])*/)
{
	$features['lang']['en'] = array(
		TAB3.'<li><a href="'.($href =
		(!empty($siteContentConfig[$selectedItem]['en']) &&
			'/' === $siteContentConfig[$selectedItem]['en'][0] ?
			$siteContentConfig[$selectedItem]['en'] :
		('english/'.((isSet($siteContentConfig[$category]) &&
			isSet($siteContentConfig[$category]['en'])) ?
				($siteContentConfig[$category]['en'].'?') :
				'').$siteContentConfig[$selectedItem]['en']))),
		'" class="language-item flag-en"><img src="'.$designURI.
		'null.gif" alt="" />English</a></li>'.EOL, '&');
	if (isSet($siteContentConfig[$selectedItem]['en-separator']))
		$features['lang']['en'][2] =
			$siteContentConfig[$selectedItem]['en-separator'];
	HTMLHeadManagement::registerLangLink($href, 'en', '&');
}
elseif (isSet($siteContentConfig[$category]) &&
	isSet($siteContentConfig[$category]['en']))
{
	$features['lang']['en'] = array(
		TAB3.'<li><a href="'.($href =
			(!empty($siteContentConfig[$category]['en']) &&
			'/' === $siteContentConfig[$category]['en'][0] ? '' : 'english/').
		$siteContentConfig[$category]['en']), '" class="language-item '.
		'flag-en"><img src="'.$designURI.'null.gif" alt="" />English</a></li>'.
		EOL, '?');
	if (isSet($siteContentConfig[$category]['en-separator']))
		$features['lang']['en'][2] =
			$siteContentConfig[$category]['en-separator'];
	HTMLHeadManagement::registerLangLink($href, 'en');
}
else
	$features['lang']['en'] = TAB3.'<li><span class="language-item '.
		'flag-en"><img src="'.$designURI.'null.gif" alt="" />English</span></li>'.
		EOL;


$features['end'] .= TAB3.'</ul></section>'.EOL2;

$features['end'] .= TAB3.'<section id="block-search-form" class="'.
	'block block-search clearfix">'.EOL;

$features['end'] .= TAB3.'<div class="form-search content-search"><div '.
	'class="input-group"><input id="search" type="text" name="q" value="" '.
	'onkeypress="checkSubmit(\'search\')" autocomplete="on" size="15" '.
	'placeholder="'.$designTexts['design-feature-search-placeholder'].'" '.
	'title="'.$designTexts['design-feature-search-title'].'" class="'.
	'form-control form-text" /><span class="input-group-btn"><button '.
	'type="submit" class="btn btn-primary" onclick="search(\'search\')">'.
	'<span class="icon glyphicon '.
	'glyphicon-search" aria-hidden="true"><img src="/design/magnifier.svgz" '.
	'onerror="this.onerror=null; this.src=\'/design/magnifier.png\';" '.
	'alt="'.$designTexts['design-feature-search'].'" title="'.
	$designTexts['design-feature-search'].'" /></span></button></span>'.
	'</div></div>'.EOL;



/*$features['end'] .= TAB3.'<input id="search" type="text" name="q" '.
	'value="" onkeypress="checkSubmit(\'search\')" autocomplete="on" '.
	'class="search form-control" />'.EOL.TAB3.
	'<a href="javascript:search(\'search\');" class="search">'.
	$designTexts['design-feature-search'].'</a>'.EOL;

if (isSet($siteContentConfig['rss']))
	$features['end'] .= TAB3.'<a href="'.$siteContentConfig['rss'].
		'" class="feature-icon" target="_blank" rel="noopener"><img src="'.
		$designURI.'null.gif" class="rss" title="RSS" alt="RSS" /></a>'.EOL;
else
	$features['end'] .= TAB3.'<span class="feature-icon"><img src="'.
		$designURI.'null.gif" class="rss" alt="RSS" /></span>'.EOL;*/

$features['end'] .= TAB3.'</section>'.EOL;


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
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="generator" content="Rheia X (https://pdf.truni.sk/rheia)" />

<!-- Global site tag (gtag.js) – Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-49575891-5"></script>

<script type="text/javascript">
	var blockMenuLeft;

	function blockMenuLoad()
	{
		blockMenuLeft = document.getElementById('block-menu-block-2');
		console.log(blockMenuLeft);
	}

	function blockMenuLeftToggle()
	{
		blockMenuLeft.classList.toggle('block-menu-visible');
		blockMenuLeft.classList.toggle('block-menu-hidden');
	}

	window.dataLayer = window.dataLayer || [];
	function gtag() { dataLayer.push(arguments); }
	gtag('js', new Date());

	gtag('config', 'UA-49575891-5');

</script>

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


<body role="document" class="html not-front not-logged-in one-sidebar sidebar-first page-node page-node- node-type-page" onload="onLoadPage(); initPage(event); blockMenuLoad();" onbeforeunload="onUnloadPage">

<?php /*<!-- div id="skip-link">
	<a href="#main-content"
		class="element-invisible element-focusable">Skočiť na hlavný obsah</a>
</div -->*/ ?>


<div class="top-fixed-area hidden-print">
	<div id="fixed-header">
		<div class="left"></div>

		<div class="right">
			<div class="region region-fixed-topbar-right page-features">

<?php /*
			<!-- section id="block-search-form" class="block block-search clearfix" -->
			*/ ?><?php
			$features['start'] = EOL.ob_get_clean().EOL; ob_start();
			?><?php /*
			<!--form class="form-search content-search" action="/univerzita"
				method="post" id="search-block-form"
				accept-charset="UTF-8"><div><div>
			<div class="input-group">
			<input title="Zadajte slová, ktoré chcete vyhľadať." placeholder="Hľadať" class="form-control form-text" type="text" id="edit-search-block-form--2" name="search_block_form" value="" size="15" maxlength="128" /><span class="input-group-btn"><button type="submit" class="btn btn-primary"></button></span></div>
			<div class="form-actions form-wrapper form-group" id="edit-actions"></div></div></div></form>
			</section-->

			<!-- section id="block-locale-language" class="block block-locale clearfix">
			<ul class="language-switcher-locale-url">
			<li class="en first"><a href="/en/university-management" class="language-link locale-untranslated" xml:lang="en" lang="en" title="University"><img class="language-icon img-responsive" typeof="foaf:Image" src="https://www.truni.sk/sites/all/modules/languageicons/flags/en.png" width="16" height="12" alt="English" title="English" />English</a></li>
			<li class="last active"><a href="/univerzita" class="language-link active" title="Univerzita"><img class="language-icon img-responsive" typeof="foaf:Image" src="https://www.truni.sk/sites/all/modules/languageicons/flags/sk.png" width="16" height="12" alt="Slovenčina" title="Slovenčina" /> Slovenčina</a></li>
			</ul>
			</section-->*/ ?>

			</div>

		</div>
	</div>
</div>


<div id="logo-header">
	<div class="container">
		<div class="col-xs-12">
			<a class="logo navbar-btn pull-left img-responsive" href="/<?php
				if (isSet($facultyBannerPath)) echo $facultyBannerPath; ?>"
			title="<?php echo $designTexts['design-home']; ?>"><img
			src="/design/banner-2017<?php
			if (!empty($designTexts['design-language-code']))
				echo '-'.$designTexts['design-language-code']; ?>.png" alt="<?php
			echo $designTexts['design-home']; ?>" class="img-responsive" /></a>
		</div>
	</div>
</div>


<div id="header-menu">
<?php
/*
Alternatívne položky:
<li class="first leaf"><a href="/fakulta" title="">Fakulta</a></li>
<li class="leaf"><a href="/uchadzac" title="">Uchádzač</a></li>
<li class="leaf"><a href="/student" title="">Študent</a></li>
<li class="leaf"><a href="/zamestnanec" title="">Zamestnanec</a></li>
<li class="last leaf"><a href="/verejnost" title="">Verejnosť</a></li>
*/
?>
	<div class="container">
		<div class="region region-main-header-menu">

		<!-- #menu-block-1 -->
		<section id="block-menu-menu-header-menu"
			class="block block-menu clearfix"><?php echo EOL.$mainMenu; ?>
		</section>
		<!-- /#menu-block-1 -->

		</div>
	</div>
</div>


<div id="faculties" style="min-height: 50px; background-color: #252c64">
	<div class="container" style="background-color: #252c64">
		<!-- #menu-block-1.5 -->
		<?php echo EOL.$topMenu.EOL; ?>
		<!-- /#menu-block-1.5 -->
	</div>
</div>


<div class="main-container container">
	<!-- #page-header -->
	<header role="banner" id="page-header">
	<?php

	// echo '<p><small style="color: #e0e0e0;"><b>Poznámka:</b> Reštrukturalizácia nášho webového sídla je takmer dokončená…</small></p>';
	// echo '<p><small style="color: #dedede;"><b>Poznámka:</b> Práve dokončujeme reštrukturalizáciu obsahu našich stránok (najmä katedrových).</small></p>';
	// echo '<p><small style="color: #ddd;"><b>Poznámka:</b> V súčasnosti dokončujeme rekonštrukciu dizajnu a reštrukturalizáciu obsahu našich stránok.</small></p>';

	echo '<div class="print-header"><p>© 2005 – '.date('Y').' '.
		$siteSection.', '.$designTexts['design-rights-reserved'].'. '.
		$designTexts['design-todays-date'].' '.date('j. n. Y').
		'.</p></div>';
	?>
	</header>
	<!-- /#page-header -->

	<div id="id-row-content" class="row">

	<!-- #sidebar-first -->
	<aside class="col-sm-3 hidden-print" role="complementary">
	<div class="region region-sidebar-first well">

	<div class="menu-left-expander" onclick="blockMenuLeftToggle();"> </div>

	<!-- #menu-block-2 -->
	<section id="block-menu-block-2" class="block block-menu-block clearfix
		block-menu-hidden">
	<div class="menu-block-wrapper menu-block-2 menu-name-menu-truni-menu
		parent-mlid-0 menu-level-1">
	<?php echo EOL.$leftMenu.EOL; ?>
	</div>
	</section>
	<!-- /#menu-block-2 -->

	</div>
	</aside>
	<!-- /#sidebar-first -->


	<section class="col-sm-8" style="margin-top:25px;">

		<a id="main-content"></a>

		<!-- #menu-block-3 -->
<?php echo EOL.$centralMenu.EOL; ?>
		<!-- /#menu-block-3 -->

<?php
// <h1 class="page-header">
// ***Nadpis článku***
// </h1>
?>

		<div class="region region-content">
		<section id="block-system-main" class="block block-system clearfix">


		<div id="page-content-loading" class="field field-name-body field-type-text-with-summary field-label-hidden"><div class="field-items"><div class="field-item even page-content-loading" property="content:encoded">

		<!-- #page-content-loading -->
		<p class="page-content-loading"><?php
			echo $designTexts['design-loading']; ?>…</p>
		<!-- /#page-content-loading -->

		</div></div></div>


		<article class="node node-page node-promoted clearfix" typeof="foaf:Document"><?php /*<!-- div id="page-core" class="page-core" -->*/ ?>

		<header><?php
		/* <span property="dc:title" content="***Nadpis článku***" class="rdf-meta element-hidden"></span><span property="sioc:num_replies" content="0" datatype="xsd:integer" class="rdf-meta element-hidden"></span> */
		?></header>

		<div id="page-content" class="field field-name-body field-type-text-with-summary field-label-hidden page-content-hidden"><div class="field-items"><div class="field-item even page-content" property="content:encoded">

		<!-- #page-content -->
		<?php $bodyStart = ob_get_clean(); ob_start(); ?>
		<!-- /#page-content -->

		</div></div></div>

		<footer></footer>

		<?php /*<!-- /div -->*/ ?></article>

		</section>
		</div>

	</section>

	</div>
</div>


<footer class="footer container">
	<div class="region region-footer">

	<section id="block-block-2" class="block block-block footer-copyright
		col-xs-12 clearfix"><?php

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

// © 2005 – 2017 Pedagogická fakulta · Trnavská univerzita v Trnave · Všetky práva vyhradené.
?></section>

	<section class="block block-menu clearfix"><ul class="menu nav">
	<?php echo EOL.$bottomMenu.EOL; ?>
	</ul></section>

<?php /*
	<!--section id="block-menu-menu-footer-menu" class="block block-menu clearfix">
	<ul class="menu nav">
	<li class="first leaf"><a href="https://www.truni.sk/univerzitne-informacne-systemy" title="" target="_blank" rel="noopener">Univ. informačné systémy</a></li>
	<li class="leaf"><a href="https://idmportal.truni.sk/" title="Identity management portal" target="_blank" rel="noopener">Správa identít používateľov</a></li>
	<li class="leaf"><a href="https://www.truni.sk/zistenie-tuid" title="" target="_blank" rel="noopener">Identifikácia TUID</a></li>
	<li class="leaf"><a href="https://mais.truni.sk/" title="Modulárny akademický informačný systém" target="_blank" rel="noopener">MAIS</a></li>
	<li class="leaf"><a href="https://webmail.tvu.sk/" target="_blank" rel="noopener" title="">WebMail študent</a></li>
	<li class="leaf"><a href="https://webmail.truni.sk/" target="_blank" rel="noopener" title="">WebMail zamestnanec</a></li>
	<li class="last leaf"><a href="http://ezp.truni.sk/ezp" title="" target="_blank" rel="noopener">Evidencia záver. prác</a></li>
	</ul>
	</section>

	<section id="block-menu-menu-footer-menu-2" class="block block-menu clearfix">
	<ul class="menu nav">
	<li class="first leaf"><a href="http://ezp.truni.sk/opac?fn=main" title="Online katalóg Univerzitnej knižnice" target="_blank" rel="noopener">Online katalóg UK</a></li>
	<li class="leaf"><a href="http://elearning.truni.sk/" title="Vzdelávací portál univerzity" target="_blank" rel="noopener">Vzdelávací portál</a></li>
	<li class="leaf"><a href="https://strava.truni.sk/" title="Stravovací systém univerzity" target="_blank" rel="noopener">Stravovací systém</a></li>
	<li class="leaf"><a href="https://www.truni.sk/informacie-o-zhotoveni-preukazu-studenta" title="" target="_blank" rel="noopener">Preukaz študenta</a></li>
	<li class="leaf"><a href="https://www.truni.sk/preukaz-ucitela-zamestnanca" title="" target="_blank" rel="noopener">Preukaz zamestnanca</a></li>
	<li class="leaf"><a href="https://www.truni.sk/telefonny-zoznam" title="Telefónny zoznam " target="_blank" rel="noopener">Telefónny zoznam univerzity</a></li>
	<li class="last leaf"><a href="https://www.truni.sk/universitas-tyrnaviensis-casopis-trnavskej-univerzity-v-trnave" title="" target="_blank" rel="noopener">Univerzitný časopis</a></li>
	</ul>
	</section>

	<section id="block-menu-menu-footer-menu-3" class="block block-menu clearfix">
	<ul class="menu nav">
	<li class="first leaf"><a href="https://www.truni.sk/sme-vitazom-narodnej-ceny-sr-za-kvalitu-2016-v-kategorii-organizacie-verejneho-sektora" title="" target="_blank" rel="noopener">Národná cena SR za kvalitu</a></li>
	<li class="leaf"><a href="https://www.truni.sk/zasadne-dokumenty" title="" target="_blank" rel="noopener">Zásadné dokumenty</a></li>
	<li class="leaf"><a href="https://truni.isportsystem.sk/" target="_blank" rel="noopener" title="">Pro TRUNI Activity</a></li>
	<li class="leaf"><a href="http://sfeu.truni.sk/" title="Realizované európske projekty" target="_blank" rel="noopener">Projekty EÚ</a></li>
	<li class="leaf"><a href="https://www.truni.sk/centrum-jazykov" title="" target="_blank" rel="noopener">Centrum jazykov</a></li>
	<li class="leaf"><a href="https://www.truni.sk/centrum-podpory-studentov" title="" target="_blank" rel="noopener">Centrum podpory študentov</a></li>
	<li class="last leaf"><a href="https://www.truni.sk/univerzita-tretieho-veku" title="Univerzita tretieho veku" target="_blank" rel="noopener">Univerzita tretieho veku</a></li>
	</ul>
	</section-->

	<!--section id="block-block-16" class="block block-block col-xs-12 col-sm-6 col-md-3 footer-kontakt clearfix">
	<p><img src="https://www.truni.sk/sites/default/files/pictures/logo.png" alt="Trnavská univerzita v Trnave" class="img-responsive" width="468" height="100" /></p>
	<p><b>Trnavská univerzita v Trnave</b><br /><span style="font-size: 13.008px;">Hornopotočná 23<br /></span><span style="font-size: 13.008px;">918 43 TRNAVA<br /></span><span style="font-size: 13.008px;">tel.: 033/5939 111<br /></span><span style="font-size: 13.008px;">IČO: 318 25 249<br /></span><span style="font-size: 13.008px;">IČ DPH: SK2021177202</span></p>
	</section-->
*/ ?>

	<section id="block-block-16" class="block block-block col-xs-12 col-sm-6
		col-md-3 footer-kontakt clearfix">
	<p><b><?php echo $designTexts['design-faculty'].'<br />'.
		$designTexts['design-of-university']; ?></b></p>
	<p>Priemyselná 4<br />P. O. BOX 9<br />918 43  Trnava</p>
	<p><img src="/design/phone.svgz" onerror="this.onerror=null;
		this.src='/design/phone.png';" alt="tel.:" /> <a href="tel:+421-33-5939-500" rel="nofollow">+421 33 5939 500</a></p>
	<p>IČO: 318 25 249<br />IČ DPH: SK2021177202</p>
	</section>

	</div>
</footer>

<div id="topmost" class="topmost" style="display: none;"></div>
<?php

$cookiesAnnounceTime = 'cookiesAnnounceTime';
if (!empty($designTexts['design-language-code']))
	$cookiesAnnounceTime .= strtoupper($designTexts['design-language-code']);

$showCookiesAnnounce = LoginManagement::
	getSessionProperty($cookiesAnnounceTime);

if (empty($showCookiesAnnounce)) $showCookiesAnnounce = true; else
{
	$showCookiesAnnounce = time() - $showCookiesAnnounce;
	$showCookiesAnnounce = $showCookiesAnnounce >= 86400; // 24 h
}

if ($showCookiesAnnounce)
{
	echo EOL.'<div id="cookies-announce" class="cookies-announce"'.EOL.
		'style="display: none; visibility: hidden;">'.EOL.
		// '<p><img src="/design/cookies.png" alt="cookies" /></p>'.EOL.
		'<p>'.$designTexts['design-cookies-announce'].'<br />'.
			$designTexts['design-cookies-disable'].'</p>'.EOL.
		'<p>'.makeExternalLink($designTexts['design-cookies-more-info'].'.',
			('en' == $designTexts['design-language-code'] ? '/english' : '').
			'/site-info?cookies').'</p>'.EOL.
		'<p><a href="javascript:void(0)" '.
			'onclick="hideItem(\'cookies-announce\');'.EOL.
		'saveSessionTime(\''.$cookiesAnnounceTime.'\');" '.
			'style="text-decoration: none;"><span'.EOL.
		'class="button">'.$designTexts['design-cookies-dismiss'].
			'</span></a></p>'.EOL.'</div>'.EOL;
	$javaScript1 .= TAB.'showItem(\'cookies-announce\');'.EOLT.
		'setItemVisible(\'cookies-announce\', true);'.EOL;
}

?>

<form name="sf" method="post" action="search" style="display: none;"><input type="text" name="q" value="" required="required" /></form>

</body>
</html><?php

loadStyleVersion('style');
loadStyleVersion('print');

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

	echo TAB.'<link href="'.$designURI.'style.css?v='.$stylesVersions['style'].
		'" rel="stylesheet" type="text/css" />'.EOL;
	if (!empty($linkStyles))
		foreach ($linkStyles as $key => $val)
			if ($val) echo TAB.'<link href="'.$designURI.$key.'.css?v='.
				(isSet($stylesVersions[$key]) ? $stylesVersions[$key] : '').
				'" rel="stylesheet" type="text/css" />'.EOL;
	echo TAB.'<link href="'.$designURI.'print.css?v='.$stylesVersions['print'].
		'" rel="stylesheet" type="text/css" media="print, projection" />'.EOL;
	echo TAB.'<link href="'.$designURI.'favicon.png" rel="shortcut icon" />'.
		EOL;

	// <link type="text/css" rel="stylesheet" href="/design/style.css" />

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

	echo '<script type="text/javascript"><!--'.EOL2.'var myGlobals = [];'.
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
	// echo TAB2.'<div class="page-features">'.EOL;
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

	// echo TAB3.LoginManagement::$userHomeItem.EOL;
	// echo TAB3.LoginManagement::buildNavigationItem().EOL;

	echo $features['end'];
	// echo TAB2.'</div>'.EOL2;

	echo $bodyStart;

	if (isSet($_SESSION) && isSet($_SESSION['history']) &&
		is_array($_SESSION['history']))
	{
		// echo '<pre>'; var_dump($_SESSION['history']); echo '</pre>';

		echo '<ol class="breadcrumb site-history"><li>'; $first = true;
		for ($i = 1; $i <= 4; ++$i)
			if (isSet($_SESSION['history'][$i]))
			{
				if ($first) $first = false; else echo '</li> <b>∙</b> <li>';

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
			echo '</li></ol>';
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