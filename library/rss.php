<?php

/**
 * Do NOT change any default values here. To modify the values,
 * use global variables in your configuration PHP file.
 */


include_once 'whitespace-constants.php';
include_once 'RheiaMainClass.php';

if (empty(RheiaMainClass::$rssCore))
	RheiaMainClass::$rssCore =
		RheiaMainClass::$protocol.'://'.RheiaMainClass::$domain.'/';
if (empty(RheiaMainClass::$rssTitle))
	RheiaMainClass::$rssTitle = 'Pedagogická fakulta Trnavskej univerzity v Trnave – RSS';
if (empty(RheiaMainClass::$rssDescription))
	RheiaMainClass::$rssDescription = 'RSS kanál fakulty. Zahŕňa články z aktualít oznamov a podobne, aktualizácie o publikovaní informácií na fakultnom webe.';
RheiaMainClass::$checkExtern = false;
header('Content-Type: text/xml; charset=UTF-8');
ob_start();

// Create RSS items:
$rssItemCategory = 'index';
$articleSeparator = null;
$rheiaClass = null;
$buildDate = null;


function rssItemRheia($file, $label, $link)
{
	global $siteContentConfig, $rssItemCategory,
		$articleSeparator, $rheiaClass, $buildDate;

	if (isSet($siteContentConfig[$file]['alias'])) return;

	if (isSet($siteContentConfig[$file]) &&
		isSet($siteContentConfig[$file]['type']))
		$type = $siteContentConfig[$file]['type'];
		else $type = 'html';

	/*if ($file !== $file && isSet($siteContentConfig[$file]))
	{
		if (isSet($siteContentConfig[$file]['parent']))
		{
			$link = $siteContentConfig[$file]['parent'].'?'.$file;
			$articleSeparator = '&';
		}
		else
		{
			$link = $file;
			$articleSeparator = '?';
		}
	}*/ // – nedáva zmysel

	if ('module' === $type || 'gallery' === $type) return;

	if (null === $rheiaClass)
	{
		$rheiaClass = new RheiaMainClass($file, false);
		$buildDate = $rheiaClass->getSourceDate();
	}
	else
	{
		$rheiaClass->reset($file);
		if ($buildDate < $rheiaClass->getSourceDate())
			$buildDate = $rheiaClass->getSourceDate();
	}

	if ('articles' === $type)
		$rheiaClass->loadArticles();
	else
		$rheiaClass->loadHTML();

	$rheiaClass->rssItem($label, $link, $articleSeparator);
}


function rssItemMenu($structure)
{
	global $siteContentConfig, $rssItemCategory,
		$articleSeparator, $rheiaClass;

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
						$rssItemCategory = $link = $file;
						$articleSeparator = '?';
					}
				}
				else
				{
					$link = $rssItemCategory.'?'.$file;
					$articleSeparator = '&';
				}

				rssItemRheia($file, $label, $link);
			}

			if (isSet($item['submenu']) &&
				(!isSet($siteContentConfig[$file]) ||
					!isSet($siteContentConfig[$file]['type']) ||
					'articles' !== $siteContentConfig[$file]['type']))
				rssItemMenu($item['submenu']);
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
						$rssItemCategory = $link = $file;
						$articleSeparator = '?';
					}
				}
				else
				{
					$link = $rssItemCategory.'?'.$file;
					$articleSeparator = '&';
				}

				rssItemRheia($file, $item, $link);
			}
		}
	}
}


if (isSet($siteStructure))
{
	if (isSet($siteStructure['top-menu-before']))
		rssItemMenu($siteStructure['top-menu-before']);

	if (isSet($siteStructure['top-menu-after']))
		rssItemMenu($siteStructure['top-menu-after']);

	if (isSet($siteStructure['bottom-menu-before']))
		rssItemMenu($siteStructure['bottom-menu-before']);

	if (isSet($siteStructure['bottom-menu-after']))
		rssItemMenu($siteStructure['bottom-menu-after']);

	if (isSet($siteStructure['left-menu-before']))
		rssItemMenu($siteStructure['left-menu-before']);

	if (isSet($siteStructure['left-menu-after']))
		rssItemMenu($siteStructure['left-menu-after']);

	if (isSet($siteStructure['right-menu-before']))
		rssItemMenu($siteStructure['right-menu-before']);

	if (isSet($siteStructure['right-menu-after']))
		rssItemMenu($siteStructure['right-menu-after']);

	if (isSet($siteStructure['central-menu-before']))
		rssItemMenu($siteStructure['central-menu-before']);

	if (isSet($siteStructure['central-menu-after']))
		rssItemMenu($siteStructure['central-menu-after']);

	if (isSet($siteStructure['bottom-icons']))
		rssItemMenu($siteStructure['bottom-icons']);

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
			$rssItemCategory = $file;
			$articleSeparator = '?';

			if (is_array($menu))
			{
				if (empty($menu['hidden']))
				{
					if (!is_numeric($file) && !isSet($menu['http']) &&
						!isSet($menu['https']) && !isSet($menu['nosearch']) &&
						(!isSet($menu['link']) || strpos($menu['link'], '/')
							=== false))
						rssItemRheia($file, $menu['label'], $file);

					if (isSet($menu['top-menu']))
						rssItemMenu($menu['top-menu']);

					if (isSet($menu['bottom-menu']))
						rssItemMenu($menu['bottom-menu']);

					if (isSet($menu['left-menu']))
						rssItemMenu($menu['left-menu']);

					if (isSet($menu['right-menu']))
						rssItemMenu($menu['right-menu']);

					if (isSet($menu['central-menu']))
						rssItemMenu($menu['central-menu']);
				}
			}
			elseif (!is_numeric($file))
				rssItemRheia($file, $menu, $file);
		}
	}
}


$rssItems = ob_get_contents();
ob_end_clean();

echo '<'.'?xml version="1.0" encoding="UTF-8"?'.'>'; ?>

<rss version="2.0">

<channel>
	<title><?php echo RheiaMainClass::$rssTitle; ?></title>
	<link><?php echo RheiaMainClass::$rssCore; ?>rss.xml</link>
	<description><?php echo RheiaMainClass::$rssDescription; ?></description>

	<language>sk</language>
	<pubDate><?php echo date(gmtdate_format, $buildDate); ?></pubDate>
	<lastBuildDate><?php echo date(gmtdate_format, $buildDate);
	?></lastBuildDate>
	<generator>Rheia RSS for pdf.truni.sk by Roman Horváth</generator>
	<docs>http://www.rssboard.org/rss-specification</docs>

<?php echo $rssItems; ?>
</channel>

</rss>