<?php

include_once 'merged-objects.php';

loadMergedObject();

include_once 'search-texts.php';

function buildProfileLink($object)
{
	global $designTexts;

	$originValue = getPropertyValue($object, 'origin',
		$designTexts['design-language-code'], true);
	// echo '<pre>'; var_dump($originValue); echo '</pre>';

	foreach ($originValue as $key => $search)
	{
		$found = findIndexByValue($search, 'pracovisko', 'origin',
			$designTexts['design-language-code']);
		if (is_numeric($found) && $found >= 0)
		{
			// echo '<p> : '.$found.'</p>';
			if ('áno' === getPropertyValue('pracovisko',
				'zoznamPovoľOdkazProfilu',
				$designTexts['design-language-code'], $found))
			{
				$profilePrefix  = getPropertyValue('pracovisko',
					'prefixProfilu', $designTexts['design-language-code'],
					$found);
				$profilePostfix = getPropertyValue('pracovisko',
					'postfixProfilu', $designTexts['design-language-code'],
					$found);
				return $profilePrefix.$object.$profilePostfix;
			}
		}
	}

	// else
	{
		$profileLink = getPropertyValue($object, 'adresaProfilu',
			$designTexts['design-language-code'], true);
		if (!empty($profileLink)) return $profileLink;
		// echo '<pre>profileLink : '; var_dump($profileLink); echo '</pre>';
	}

	return null;
}

function getDepartmentID($object)
{
	global $designTexts;

	$originValue = getPropertyValue($object, 'origin',
		$designTexts['design-language-code'], true);

	foreach ($originValue as $key => $search)
	{
		$found = findIndexByValue($search, 'pracovisko', 'origin',
			$designTexts['design-language-code']);

		if (is_numeric($found) && $found >= 0)
			return getPropertyValue('pracovisko', 'id',
				$designTexts['design-language-code'], $found);
	}

	return null;
}


RheiaMainClass::$mailType = 'table'; $count = -1;

// RheiaMainClass::logError(print_r($mergedObjects[''], true));

foreach ($mergedObjects as $object => $data)
{
	if (empty($object))
	{
		// echo '<pre>'; var_dump($object); echo '</pre>';
		continue;
	}

	$name = getPropertyValue($object, null,
		$designTexts['design-language-code']);
	$namePrefix  = getPropertyValue($object, 'titulyPred',
		$designTexts['design-language-code']);
	$namePostfix = getPropertyValue($object, 'titulyZa',
		$designTexts['design-language-code']);

	$fullName = '';
	if (!empty($namePrefix)) $fullName .= $namePrefix.' ';
	$fullName .= $name;
	if (!empty($namePostfix)) $fullName .= ', '.$namePostfix;

	if (empty($fullName))
	{
		// echo '<pre>'; var_dump($object); echo '</pre>';
		continue;
	}
	++$count;

	$rooms  = getPropertyValue($object, 'miestnosť',
		$designTexts['design-language-code']);
	$extens = getPropertyValue($object, 'klapka',
		$designTexts['design-language-code']);
	$emails = getPropertyValue($object, 'email',
		$designTexts['design-language-code']);

	if (!empty($rooms) || !empty($extens) || !empty($emails))
	{
		$link = buildProfileLink($object);
		$depid = getDepartmentID($object);
		//getPropertyValue($object, 'origin', $designTexts['design-language-code']);

		$workplace = getPropertyValue($object, 'pozíciaVoVedení',
			$designTexts['design-language-code']);
		//if (empty($workplace))
		//	$workplace = getPropertyValue($object, 'pozíciaNaFakulte',
		//		$designTexts['design-language-code']);
		if (empty($workplace))
			$workplace = getPropertyValue($object, 'pozíciaNaKatedre',
				$designTexts['design-language-code']);
		if (empty($workplace))
			$workplace = getPropertyValue($object, 'pozícia',
				$designTexts['design-language-code']);
		if (empty($workplace))
			$workplace = getPropertyValue($object, 'funkčnéMiesto',
				$designTexts['design-language-code']);
		if (empty($workplace))
			$workplace = $searchTexts['search-emplist-empl'];
		// echo '<p>'.$fullName.' — '.$link.'</p>';

		if (isSet($listAll))
		{
			// Last name
			$strrpos = strrpos($name, ' ');
			if ($strrpos === false) $lastName = $name; else
				$lastName = substr($name, 1 + $strrpos);

			// First name
			$strpos = strpos($name, ' ');
			if ($strpos === false) $firstName = $name; else
				$firstName = substr($name, 0, $strpos);

			$listAll[] = array(
				'firstName' => $firstName,
				'firstNamePlain' => RheiaMainClass::transliterate($firstName),
				'lastName' => $lastName,
				'lastNamePlain' => RheiaMainClass::transliterate($lastName),
				'fullName' => $fullName,
				'workplace' => $workplace,
				'link' => $link,
				'depid' => $depid,
				'rooms' => $rooms,
				'extens' => $extens,
				'emails' => $emails);
		}
		else
		{
			RheiaMainClass::$searchIn = RheiaMainClass::transliterate($name);
			$rate = RheiaMainClass::getSearchRate();

			if ($rate)
				$searchResults[] = array($fullName, $workplace, $link,
					$rate, $count, $rooms, $extens, $emails);
		}
	}
}


if (isSet($searchResults))
{
	function usort_cmp_er($a, $b)
	{
		if ($b[3] == $a[3]) return $b[4] - $a[4];
		return $b[3] - $a[3];
	}
	usort($searchResults, 'usort_cmp_er');

	echo '<p><b>'.$searchTexts['search-employees-found'].
		':</b> '.count($searchResults).'</p>'.EOL.
		'<table class="shaded"><tr>'.
		'<th>'.$searchTexts['search-emplist-worker'].'</th>'.
		'<th>'.$searchTexts['search-emplist-pos'].'</th>'.
		'<th style="text-align:center">'.
			$searchTexts['search-emplist-room'].'</th>'.
		'<th style="text-align:center">'.
			$searchTexts['search-emplist-ext'].'</th>'.
		'<th>'.$searchTexts['search-emplist-email'].'</th>'.
		'</tr>';

	foreach ($searchResults as $result)
	{
		echo TAB.'<tr><td style="text-align: left;">';

		if (empty($result[2])) echo $result[0]; else
			echo '<a href="'.$result[2].'" target="_blank">'.
				$result[0].' <em title="'.$searchTexts
				['search-target-title'].'">»</em></a>';

		echo '</td><td>';
		if (!empty($result[1])) echo $result[1];
		echo '</td><td style="text-align:center">';
		if (!empty($result[5])) echo $result[5];
		echo '</td><td style="text-align:center">';
		if (!empty($result[6])) echo $result[6];
		echo '</td><td>';
		if (!empty($result[7])) echo $result[7];
		echo '</td></tr>'.EOL;
	}
	echo '</table>';
}

?>