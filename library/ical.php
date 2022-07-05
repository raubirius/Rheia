<?php

/**
 * Do NOT change any default values here. To modify the values,
 * use global variables in your configuration PHP file.
 */

///////////////////////////////////////////////////////////////////////////
// Current version of iCalendar module does only recognize events at
// specified dates or date ranges. No time (nor timezone) information
// is provided (for simplicity).
//
// text = *(TSAFE-CHAR / ":" / DQUOTE / ESCAPED-CHAR)
//	; Folded according to description above
//
// ESCAPED-CHAR = ("\\" / "\;" / "\," / "\N" / "\n")
//	; \\ encodes \, \N or \n encodes newline
//	; \; encodes ;, \, encodes ,
//
// TSAFE-CHAR = WSP / %x21 / %x23-2B / %x2D-39 / %x3C-5B / %x5D-7E /
//	NON-US-ASCII
//	; Any character except CONTROLs not needed by the current
//	; character set, DQUOTE, ";", ":", "\", ","
//
// VEVENTs:
//	UID – jednoznačný identifikátor
//
// Active:
//	SUMMARY – „predmet“ – stručné zhrnutie
//	CATEGORIES – kategórie
//	[LOCATION] – miesto konania
//	DTSTAMP – pečiatka vytvorenia (vzťahuje sa k METHOD) alebo revízie udalosti
//	DTSTART – dátum a čas začiatku udalosti
//	DTEND – dátum a čas konca udalosti
//	[DESCRIPTION] – podrobnejší opis udalosti
//	[SEQUENCE] – počet aktualizácií udalosti
//
// Cancelled:
//	STATUS:CANCELLED
//
// Sources:
//	http://tools.ietf.org/html/rfc5545
//	http://www.kanzaki.com/docs/ical/
//
// Validate:
//	http://severinghaus.org/projects/icv/
///////////////////////////////////////////////////////////////////////////

include_once 'parse-uri.php';
include_once 'RheiaMainClass.php';

if (!empty($selectedItem) && !empty($argument))
{
	$icalSource = $selectedItem;
	$icalArgument = $argument;
}
elseif (preg_match('/(?<=calendar-)(.*)\+(.*)(?=\.ics)/i',
	$_SERVER['REQUEST_URI'], $matches))
{
	if (!empty($selectedItem))
		$icalSource = $selectedItem;
	else
		$icalSource = $matches[1];

	if (!empty($argument))
		$icalArgument = $argument;
	else
		$icalArgument = $matches[2];
}
elseif (preg_match('/(?<=calendar-)(.*)(?=\.ics)/i',
	$_SERVER['REQUEST_URI'], $matches))
{
	if (!empty($selectedItem))
		$icalSource = $selectedItem;
	else
		$icalSource = $matches[1];

	if (!empty($argument))
		$icalArgument = $argument;
}
else
{
	if (!empty($selectedItem))
		$icalSource = $selectedItem;

	if (!empty($argument))
		$icalArgument = $argument;
}

if (empty(RheiaMainClass::$icalDefaultLanguage))
{
	if (isSet($icalDefaultLanguage))
		RheiaMainClass::$icalDefaultLanguage = $icalDefaultLanguage;
	else
		RheiaMainClass::$icalDefaultLanguage = 'SK';
}

if (empty(RheiaMainClass::$icalProdID))
{
	if (isSet($icalProdID))
		RheiaMainClass::$icalProdID = '-//'.$icalProdID.
			'//NONSGML Rheia by Roman Horvath//'.
			RheiaMainClass::$icalDefaultLanguage;
	else
		RheiaMainClass::$icalProdID = '-//'.RheiaMainClass::$domain.
			'//NONSGML Rheia by Roman Horvath//'.
			RheiaMainClass::$icalDefaultLanguage;
}

if (empty(RheiaMainClass::$icalTimezone))
{
	RheiaMainClass::$icalTimezone = array(
		'tzid' => 'Europe/Bratislava',
		'standard' => array(
			'dtstart' => '20041031T030000',
			'rrule' => array('freq' => 'YEARLY',
				'interval' => '1', 'byday' => '-1SU',
				'bymonth' => '10'),
			'tzoffsetfrom' => '+0200',
			'tzoffsetto' => '+0100',
			'tzname' => 'Standard Time',
			),
		'daylight' => array(
			'dtstart' => '20040328T020000',
			'rrule' => array('freq' => 'YEARLY',
				'interval' => '1', 'byday' => '-1SU',
				'bymonth' => '3'),
			'tzoffsetfrom' => '+0100',
			'tzoffsetto' => '+0200',
			'tzname' => 'Daylight Savings Time',
			),
		);

	if (isSet($icalTimezone) && is_array($icalTimezone))
	{
		foreach ($icalTimezone as $id1 => $val1)
		{
			if (is_array($val1))
			{
				foreach ($val1 as $id2 => $val2)
				{
					if (is_array($val2))
					{
						foreach ($val2 as $id3 => $val3)
						{
							if (isSet(RheiaMainClass::
								$icalTimezone[$id1][$id2][$id3]))
								RheiaMainClass::
								$icalTimezone[$id1][$id2][$id3] = $val3;
						}
					}
					elseif (isSet(RheiaMainClass::$icalTimezone[$id1][$id2]) &&
						!is_array(RheiaMainClass::$icalTimezone[$id1][$id2]))
						RheiaMainClass::$icalTimezone[$id1][$id2] = $val2;
				}
			}
			elseif (isSet(RheiaMainClass::$icalTimezone[$id1]) &&
				!is_array(RheiaMainClass::$icalTimezone[$id1]))
				RheiaMainClass::$icalTimezone[$id1] = $val1;
		}
	}
}


// if (empty(RheiaMainClass::$icalDefaultCategory))
// 	RheiaMainClass::$icalDefaultCategory = 'Kalendár';
// if (empty(RheiaMainClass::$icalDefaultSummary))
// 	RheiaMainClass::$icalDefaultSummary = '«neznáma udalosť»';

// Create iCalendar items:
$rheiaClass = null;
$touch = array();
$icalItems = null;
$uids = array();


function dumpRecursive(&$array, $tablevel = 0)
{
	foreach ($array as $debugClass => $debugItem)
	{
		if (empty($debugItem)) continue;
		if (is_array($debugItem))
		{
			if (is_numeric($debugClass))
			{
				dumpRecursive($debugItem, $tablevel);
			}
			else
			{
				for ($i = $tablevel; $i > 0; --$i) echo '  ';
				echo $debugClass.':'.EOL;
				dumpRecursive($debugItem, $tablevel + 1);
			}
			echo EOL;
		}
		else
		{
			for ($i = $tablevel; $i > 0; --$i) echo '  ';
			if (is_numeric($debugClass))
				echo $debugItem.EOL;
			else
				echo $debugClass.':'.$debugItem.EOL;
		}
	}
}

function processIcalItems($toProcess)
{
	global $uids, $params;

	if (is_array($toProcess) && isSet($toProcess['ical']) &&
		is_array($toProcess['ical']) && isSet($toProcess['ical']['events']))
	{
		// http://localhost/calendar-harmonogram-studia+2014-2015.ics
		//	http://localhost/calendar?harmonogram-studia&2014-2015&debug
		// http://localhost/calendar-tests+2014-2015.ics
		//	http://localhost/calendar?tests&2014-2015&debug
		// http://localhost/calendar-harmonogram-studia+test.ics
		//	http://localhost/calendar?harmonogram-studia&test&debug
		if (in_array('debug', $params))
		{
			dumpRecursive($toProcess['ical']['debug']);
			// var_dump($toProcess['ical']);
			// return;
		}

		$events = $toProcess['ical']['events'];

		foreach ($events as $uid => $event)
		{
			echo 'BEGIN:VEVENT'.EOL;
			echo 'UID:'.$uid.EOL;

			if (!is_array($event))
			{
				echo 'COMMENT:Error, no data!'.EOL;
			}
			elseif (isSet($uids[$uid]))
			{
				echo 'COMMENT:Error, duplicate UID – '.$uid.EOL;
			}
			else
			{
				$uids[$uid] = true;
				foreach ($event as $data => $value)
				{
					if (preg_match('/^status$/i', $data))
					{
						if (preg_match('/^tentative|confirmed|cancelled$/i',
							$value)) echo 'STATUS:'.strtoupper($value).EOL;
					}
					/*elseif (preg_match('/^method$/i', $data))
					{
						echo 'METHOD:'.strtoupper(
							RheiaMainClass::transliterate($value, '-')).EOL;
					}*/
					elseif (preg_match('/^dt(start|end|stamp)$/i', $data))
					{
						if (preg_match('/^[0-9]{8}$/i', $value))
						{
							echo strtoupper($data);
							if (isSet(RheiaMainClass::$icalTimezone['tzid']))
								echo ';TZID='.RheiaMainClass::$icalTimezone['tzid'];
							echo ';VALUE=DATE:'.$value.EOL;
						}
						elseif (preg_match('/^[0-9]{8}T[0-9]{4,6}Z$/i', $value))
							echo strtoupper($data).':'.$value.EOL;
						elseif (preg_match('/^[0-9]{8}T[0-9]{4,6}$/i', $value))
						{
							echo strtoupper($data);
							if (isSet(RheiaMainClass::$icalTimezone['tzid']))
								echo ';TZID='.RheiaMainClass::$icalTimezone['tzid'];
							echo ':'.$value.EOL;
						}
						else
							echo 'COMMENT:Error, invalid '.
								$data.' value! ('.$value.')'.EOL;
					}
					elseif (preg_match('/^sequence$/i', $data))
					{
						if (preg_match('/([0-9]+)/', $value, $matches))
							echo 'SEQUENCE:'.((int)$matches[1]).EOL;
					}
					else
					{
						echo strtoupper($data).':'.$value.EOL;
					}
				}
			}

			echo 'END:VEVENT'.EOL;
		}
	}
}

function walkContent($file, $articleID = null)
{
	global $siteContentConfig, $rheiaClass, $touch, $icalItems;

	$alias = $file;
	for ($i = 0; isSet($siteContentConfig[$alias]['alias']) && $i < 10;
		++$i) $alias = $siteContentConfig[$alias]['alias'];

	if (isSet($siteContentConfig[$alias]) &&
		isSet($siteContentConfig[$alias]['type']))
		$type = $siteContentConfig[$alias]['type'];
		else $type = 'html';

	if ('module' === $type || 'gallery' === $type)
		return true; // true means “error”

	$touch[$alias] = true;

	if (null === $rheiaClass)
		$rheiaClass = new RheiaMainClass($alias, false);
	else
		$rheiaClass->reset($alias);

	ob_start();

	if ('articles' === $type || !empty($articleID))
	{
		$rheiaClass->loadArticles();
		$error = ob_get_clean();
		if (!empty($error))
			return true; // true means “error”
			// See: if (!empty($error)) below…

		ob_start();

		if (null !== $rheiaClass->getArticlesData())
		{
			if (empty($articleID))
			{
				foreach ($rheiaClass->getArticlesData() as $link => $article)
				{
					if ('title' === $link || 'templates' === $link ||
						'warnings' === $link || 'listEmptyMessage' === $link ||
						'revalidationDate' === $link) continue;
	
					if (isSet($article['staticHTML']))
						processIcalItems($article);
				}
	
				foreach ($rheiaClass->getArticlesData() as $link => $article)
				{
					if ('title' === $link || 'templates' === $link ||
						'warnings' === $link || 'listEmptyMessage' === $link ||
						'revalidationDate' === $link) continue;
	
					if (!isSet($article['staticHTML']) &&
						(!isSet($article['expires']) ||
							($article['expires'] >=
								RheiaMainClass::$currentTime)) &&
						(!isSet($article['date']) ||
							($article['date'] <= RheiaMainClass::$currentTime)) &&
						(!$article['options']['zoznam:skryČlánok'] ||
							!empty($article['title'])) &&
						!$article['options']['článok:odstránený'])
						processIcalItems($article);
				}
			}
			elseif ('title' === $articleID || 'templates' === $articleID ||
				'warnings' === $articleID || 'listEmptyMessage' === $articleID ||
				'revalidationDate' === $articleID)
			{
				return true; // true means “error”
			}
			else
			{
				$article = $rheiaClass->getArticlesData();
				if (isSet($article[$articleID]))
				{
					$article = $article[$articleID];
					if (!isSet($article['staticHTML']) &&
						(!isSet($article['expires']) ||
							($article['expires'] >=
								RheiaMainClass::$currentTime)) &&
						(!isSet($article['date']) ||
							($article['date'] <= RheiaMainClass::$currentTime)) &&
						(!$article['options']['zoznam:skryČlánok'] ||
							!empty($article['title'])) &&
						!$article['options']['článok:odstránený'])
						processIcalItems($article);
						else return true; // true means “error”
				}
				else return true; // true means “error”
			}
		}
	}
	else
	{
		// if (!empty($articleID)) return true; // true means “error”

		$rheiaClass->loadHTML();
		$error = ob_get_clean();

		if (!empty($error))
		{
			////////////////////////////////////////////////////////////
			// readfile is not suitable, because
			// it cannot pass through 404 erros
			// readfile('http://'.$_SERVER["HTTP_HOST"].
			//	preg_replace('#(.*)/.*$#i', '$1/error?404',
			//		$_SERVER["REQUEST_URI"]));
			// Include is not usable here!!
			// (There is too much global variables.)
			////////////////////////////////////////////////////////////
			return true; // true means “error”
		}

		ob_start();

		if (null !== $rheiaClass->getHTMLData())
			processIcalItems($rheiaClass->getHTMLData());
	}

	$icalItems = ob_get_clean();
	return false;
}


if (empty($icalSource))
{
	include_once 'design.php';
	switch ($designTexts['design-language-code'])
	{
	case 'en':
		echo '<h1>Calendar</h1>';
		echo '<p><i>«Global view is not implemented, yet.»</i></p>';
		break;

	default:
		echo '<h1>Kalendár</h1>';
		echo '<p><i>«Globálne zobrazenie zatiaľ nie je implementované.»</i></p>';
	}
	die;
}


if (isSet($icalArgument))
{
	if (walkContent($icalSource, $icalArgument)) // true means “error”
	{
		include_once 'design.php';
		include '404.php';
		die;
	}
}
elseif (walkContent($icalSource)) // true means “error”
{
	include_once 'design.php';
	include '404.php';
	die;
}

include_once 'counter.php';

if (isSet($icalArgument))
	header('Content-Disposition: attachment; filename="calendar-'.
		$icalSource.'+'.$icalArgument.'.ics"');
else
	header('Content-Disposition: attachment; filename="calendar-'.
		$icalSource.'.ics"');
header('Content-Type: text/calendar; charset=UTF-8');


?>BEGIN:VCALENDAR
PRODID:<?php echo RheiaMainClass::$icalProdID.EOL; ?>
VERSION:2.0
CALSCALE:GREGORIAN
BEGIN:VTIMEZONE
<?php
foreach (RheiaMainClass::$icalTimezone as $id1 => $val1)
{
	if (is_array($val1))
	{
		echo 'BEGIN:'.strtoupper($id1).EOL;
		foreach ($val1 as $id2 => $val2)
		{
			if (is_array($val2))
			{
				$first = true;
				echo strtoupper($id2).':';
				foreach ($val2 as $id3 => $val3)
				{
					if ($first) $first = false; else echo ';';
					echo strtoupper($id3).'='.$val3;
				}
				echo EOL;
			}
			else
				echo strtoupper($id2).':'.$val2.EOL;
		}
		echo 'END:'.strtoupper($id1).EOL;
	}
	else
		echo strtoupper($id1).':'.$val1.EOL;
}
?>
END:VTIMEZONE
<?php echo $icalItems; ?>
END:VCALENDAR
