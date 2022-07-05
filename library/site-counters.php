<?php

header('X-Robots-Tag', 'noindex');
HTMLHeadManagement::setMetaProperty('robots', 'noindex');

switch ($designTexts['design-language-code'])
{
case 'en':
	$countersTexts = array(
		'cancel-filter' => 'cancel filter',
		'legend' => 'Legend',
		'legend-sum' => 'total number of accesses',
		'legend-IP' => 'number of accesses unique for IP – each IP is counted only once per day',
		'sums' => 'Sums',
		'already-running' => 'Sorry, currently is processing a request of another user. Please try to ask for processing later. Thank you for your understanding.',
	);
	break;

default:
	$countersTexts = array(
		'cancel-filter' => 'zrušiť filter',
		'legend' => 'Vysvetlivky',
		'legend-sum' => 'celkový počet prístupov',
		'legend-IP' => 'počet IP unikátnych prístupov – každá IP je započítaná iba raz denne',
		'sums' => 'Súčty',
		'already-running' => 'Prepáčte, práve je spracúvaná požiadavka iného používateľa. Skúste, prosím, požiadať o spracovanie neskôr. Ďakujeme za porozumenie.',
	);
}

if (isSet($parsedPath))
	define('site_counter_is_running', $parsedPath.counterBase.'-touch.php');
else
	define('site_counter_is_running', '../parsed/'.counterBase.'-touch.php');

if (file_exists(site_counter_is_running) &&
	filemtime(site_counter_is_running) + 30 >= time())
{
	// die('Script is already running');
	// echo '<h1>'.($title = $designTexts['site-counters']).'</h1>'.EOL;

	echo '<div class="article"><div class="history"><p><a href="site-info">'.
		$designTexts['design-feature-info'].'</a></p></div>'.
		'<div class="clear"></div>'.EOL2;

	if (!isSet($scriptBase)) $scriptBase = 'site-counters';

	echo '<div class="title"><h1><a href="'.$scriptBase.'">'.($title =
		$designTexts['site-counters']).'</a></h1></div>'.EOL2;

	echo '<div class="article-content">';
	echo '<p>'.$countersTexts['already-running'].'</p>'.EOL;
	echo '</div></div>';
}
else
{
	touch(site_counter_is_running);


	if (!isSet($counterBase))
	{
		if (!isSet($counterPath))
			$counterPath = (isSet($counterLevel) ?
				$counterLevel : '').'../counter/';
		$counterBase = $counterPath.counterBase;
	}

	if (!isSet($filterURI)) $filterURI = array();
	if (!isSet($repairURI)) $repairURI = array();

	if (!isSet($preg_match_uri_filter))
		$preg_match_uri_filter = '~^/([a-zA-Z\-]+)$~';

	if (!isSet($scriptBase)) $scriptBase = 'site-counters';

	$dateRange = null;

	@$filters = preg_split('~[\?&]~', trim($_SERVER['REQUEST_URI'], '/'), -1);
	if (is_array($filters))
	{
		if (empty($filters[1])) $filters[1] = null;
		if (empty($filters[2])) $filters[2] = null;
		else $filters[2] = strtolower($filters[2]);
	}
	else $filters = array(null, null, null);


	if (null === $filters[1]) $filterDate = null;
	else if (preg_match('~([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})~', $filters[1],
		$filterDate))
	{
		$filterDate[0] = $filterDate[1];
		$filterDate[1] = $filterDate[2];
		$filterDate[2] = $filterDate[3];
		unset($filterDate[3]);
	}
	else if (preg_match('~([0-9]{4})-([0-9]{1,2})~',
		$filters[1], $filterDate))
	{
		$filterDate[0] = $filterDate[1];
		$filterDate[1] = $filterDate[2];
		$filterDate[2] = null;
	}
	else if (preg_match('~([0-9]{4})~', $filters[1], $filterDate))
	{
		$filterDate[0] = $filterDate[1];
		$filterDate[1] = null;
		$filterDate[2] = null;
	}
	else $filterDate = null;

	if (null === $filters[2]) $selectURI = null; else
	{
		if (preg_match('~([a-zA-Z\-]+)~', $filters[2], $selectURI))
			$selectURI = '~'.$selectURI[1].'~'; else $selectURI = null;
	}

	function processAccessDay()
	{
		global $accessCounters, $accessDay, $accessDate, $selectURI,
			$filterDate, $filterURI, $repairURI, $dateRange;

		$accessDate[0] = (int)$accessDate[1];
		$accessDate[1] = (int)$accessDate[2];
		$accessDate[2] = (int)$accessDate[3];
		unset($accessDate[3]);

		if (null === $dateRange)
		{
			$dateRange = array($accessDate[0], $accessDate[1], $accessDate[2],
				$accessDate[0], $accessDate[1], $accessDate[2]);
		}
		else
		{
			if ($accessDate[0] < $dateRange[0])
			{
				$dateRange[0] = $accessDate[0];
				$dateRange[1] = $accessDate[1];
				$dateRange[2] = $accessDate[2];
			}
			else if ($accessDate[0] == $dateRange[0])
			{
				if ($accessDate[1] < $dateRange[1])
				{
					$dateRange[1] = $accessDate[1];
					$dateRange[2] = $accessDate[2];
				}
				else if ($accessDate[1] == $dateRange[1])
				{
					if ($accessDate[2] < $dateRange[2])
						$dateRange[2] = $accessDate[2];
				}
			}

			if ($accessDate[0] > $dateRange[3])
			{
				$dateRange[3] = $accessDate[0];
				$dateRange[4] = $accessDate[1];
				$dateRange[5] = $accessDate[2];
			}
			else if ($accessDate[0] == $dateRange[3])
			{
				if ($accessDate[1] > $dateRange[4])
				{
					$dateRange[4] = $accessDate[1];
					$dateRange[5] = $accessDate[2];
				}
				else if ($accessDate[1] == $dateRange[4])
				{
					if ($accessDate[2] > $dateRange[5])
						$dateRange[5] = $accessDate[2];
				}
			}
		}

		if (is_array($filterDate))
		{
			if (null !== $filterDate[0] &&
				$accessDate[0] != $filterDate[0]) return;
			else if (null !== $filterDate[1] &&
				$accessDate[1] != $filterDate[1]) return;
			else if (null !== $filterDate[2] &&
				$accessDate[2] != $filterDate[2]) return;
		}

		foreach ($accessDay as $uri => $log)
		{
			$uri = strtolower(rawurldecode($uri));
			if (null !== $selectURI &&
				!preg_match($selectURI, $uri))
				continue;

			$filter = false;
			foreach ($filterURI as $pattern)
			{
				if (preg_match($pattern, $uri))
				{
					$filter = true;
					break;
				}
			}
			if ($filter) continue;

			foreach ($repairURI as $pattern => $replace)
				$uri = preg_replace($pattern, $replace, $uri);

			foreach ($log as $ip => $count)
			{
				if (isSet($accessCounters[$uri]))
				{
					$accessCounters[$uri][0] += 1 + $count;
					$accessCounters[$uri][1]++;
				}
				else
					$accessCounters[$uri] = array(1 + $count, 1);
			}
		}

	}


	$accessCounters = array();

	if (file_exists($counterBase.'.php'))
	{
		include $counterBase.'.php';

		if (isSet($accessLog))
		{
			foreach ($accessLog as $accessKey => $accessData)
			{
				preg_match('~([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})~',
					$accessKey, $accessDate);
				$accessDay = $accessData;
				processAccessDay();
			}
			unset($accessLog);
		}
	}

	foreach (glob($counterBase.'*.php') as $counterName)
	{
		if (preg_match('~^'.$counterBase.
			'-([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})\.php$~',
			$counterName, $accessDate))
		{
			if (file_exists($counterName)) include $counterName;
			if (isSet($accessDay))
			{
				processAccessDay();
				unset($accessDay);
			}
		}
	}

	ksort($accessCounters);


	// ---


	echo '<div class="article"><div class="history"><p><a href="site-info">'.
		$designTexts['design-feature-info'].'</a></p></div>'.
		'<div class="clear"></div>'.EOL2;

	echo '<div class="title"><h1><a href="'.$scriptBase.'">'.($title =
		$designTexts['site-counters']).'</a></h1></div>'.EOL2;


	echo '<div class="article-content">';

	$style .= EOL.'/* Site Counters */'.EOL2.'table.shaded tr td.uri'.EOL.
		'{'.EOLT.'max-width: 550px;'.EOLT.'width: 550px;'.EOLT.
		'overflow: auto;'.EOL.'}'.EOL2.'p.date-filters'.EOL.'{'.EOLT.
		'margin: 0px 10px 15px;'.EOLT.'font-size: 14px;'.EOL.'}'.EOL2.
		'p.date-filters a,'.EOL.'p.date-filters span'.EOL.'{'.EOLT.
		'padding: 0px 10px;'.EOL.'}'.EOL2.'p.date-filters span'.EOL.'{'.
		EOLT.'color: gray;'.EOL.'}'.EOL;

	echo '<h3>'.$countersTexts['legend'].'</h3>'.EOL2;

	echo '<table class="blocks">'.EOL;
	echo TAB.'<tr><th>Σ</th><td>–</td><td>'.$countersTexts['legend-sum'].
		'</td></tr>'.EOL;
	echo TAB.'<tr><th>IP</th><td>–</td><td>'.$countersTexts['legend-IP'].
		'</td></tr>'.EOL;
	echo '</table>'.EOL2;

	echo '<p> </p>'.EOL2;

	if (null !== $dateRange)
	{
		if (null === $selectURI) $postfix = ''; else
		{
			if (preg_match('~([a-zA-Z\-]+)~', $selectURI, $postfix))
				$postfix = '&'.$postfix[1]; else $postfix = '';
		}

		if (is_array($filterDate))
		{
			$prefix = '';
			foreach ($filterDate as $i)
			{
				if (null != $i)
				{
					if (empty($prefix)) $prefix .= $i;
						else $prefix .= '-'.$i;
				}
			}

			echo '<p class="date-filters">';
			for ($i = $dateRange[0]; $i <= $dateRange[3]; ++$i)
				if ($i == $filterDate[0])
					echo '<span>'.$i.'</span> ';
				else
					echo '<a href="?'.$i.$postfix.'">'.$i.'</a> ';

			echo '<a href="'.$scriptBase.str_replace('&', '?&', $postfix).
				'">«'.$countersTexts['cancel-filter'].'»</a> ';
			echo '</p>';

			if (null != $filterDate[1])
			{
				echo '<p class="date-filters">';
				foreach ($designTexts['months'] as $i => $month)
					if ((1 + $i) == $filterDate[1])
						echo '<span>'.$month.'</span> ';
					else
						echo '<a href="?'.$filterDate[0].
							'-'.(1 + $i).$postfix.'">'.$month.'</a> ';

				echo '<a href="?'.$filterDate[0].$postfix.'">«'.
					$countersTexts['cancel-filter'].'»</a> ';
				echo '</p>';

				echo '<p class="date-filters">';
				if (null != $filterDate[2])
				{
					for ($i = 1; $i <= 31; ++$i)
						if ($i == $filterDate[2])
							echo '<span>'.$i.'</span> ';
						else
							echo '<a href="?'.$filterDate[0].'-'.
								$filterDate[1].'-'.$i.$postfix.'">'.$i.'</a> ';

					echo '<a href="?'.$filterDate[0].'-'.$filterDate[1].
						$postfix. '">«'.$countersTexts['cancel-filter'].
						'»</a> ';
				}
				else
				{
					for ($i = 1; $i <= 31; ++$i)
						echo '<a href="?'.$filterDate[0].'-'.
							$filterDate[1].'-'.$i.$postfix.'">'.$i.'</a> ';
				}
				echo '</p>';
			}
			else
			{
				echo '<p class="date-filters">';
				foreach ($designTexts['months'] as $i => $month)
					echo '<a href="?'.$filterDate[0].
						'-'.(1 + $i).$postfix.'">'.$month.'</a> ';
				echo '</p>';
			}
		}
		else
		{
			$prefix = '';
			echo '<p class="date-filters">';
			for ($i = $dateRange[0]; $i <= $dateRange[3]; ++$i)
				echo '<a href="?'.$i.$postfix.'">'.$i.'</a> ';
			echo '</p>';
		}
	}
	else $prefix = '';

	if (null !== $selectURI)
	{
		echo '<p class="date-filters"><a href="'.$scriptBase;
		if (!empty($prefix)) echo '?'.$prefix;
		echo '">«'.$countersTexts['cancel-filter'].' – '.
			$filters[2].'»</a></p>';
	}

	echo '<table class="shaded">'.EOL;
	echo '<tr><th>URI</th><th>Σ</th><th>IP</th></tr>'.EOL;

	$sum = array(0, 0);

	foreach ($accessCounters as $uri => $count)
	{
		echo '<tr><td class="uri">';
		if (preg_match($preg_match_uri_filter, $uri, $match))
		{
			// $splits = preg_split($preg_match_uri_filter, $uri);
			// foreach ($splits as $split)
			// 	if (empty($split))
			// 		echo '<a href="?'.$prefix.'&'.strtolower(
			// 			$match[1]).'">'.$match[1].'</a>';
			// 	else
			// 		echo $split;

			echo str_replace($match[1], '<a href="?'.$prefix.'&'.
				strtolower($match[1]).'">'.$match[1].'</a>', htmlspecialchars($uri));
		}
		else
			echo htmlspecialchars($uri);
		echo '</td><td>'.$count[0].'</td><td>'.$count[1].'</td></tr>'.EOL;
		$sum[0] += $count[0];
		$sum[1] += $count[1];
	}

	echo '<tr><th>'.$countersTexts['sums'].':</td><th>'.$sum[0].
		'</th><th>'.$sum[1].'</th></tr>'.EOL;
	echo '</table>'.EOL;

	echo '</div></div>';


	touch(site_counter_is_running, time() - 30);
	// unlink(site_counter_is_running);
}

?>