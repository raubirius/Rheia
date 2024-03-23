<?php

include_once 'RheiaMainClass.php';
RheiaMainClass::$checkExtern = false;

$listAll = array();
include_once 'search-texts.php';
include_once 'search-employee.php';

function usort_cmp_el($a, $b)
{
	$strcmp = strcmp($a['lastNamePlain'][0], $b['lastNamePlain'][0]);
	if (0 == $strcmp)
	{
		$strcmp = strcmp($a['lastName'][0], $b['lastName'][0]);
		if (0 == $strcmp)
		{
			/*$strcmp = strlen($a['lastNamePlain']) -
				strlen($b['lastNamePlain']);
			if (0 == $strcmp)
			{*/
				$strcmp = strcmp($a['lastNamePlain'], $b['lastNamePlain']);
				if (0 == $strcmp)
				{
					$strcmp = strcmp($a['lastName'], $b['lastName']);
					if (0 == $strcmp)
						$strcmp = strcmp($a['firstNamePlain'],
							$b['firstNamePlain']);
				}
			//}
		}
	}
	return $strcmp;
}
usort($listAll, 'usort_cmp_el');

echo '<table class="shaded"><tr>'.
	'<th>'.$searchTexts['search-emplist-worker'].'</th>'.
	'<th>'.$searchTexts['search-emplist-pos'].'</th>'.
	'<th style="text-align:center">'.
		$searchTexts['search-emplist-room'].'</th>'.
	'<th style="text-align:center">'.
		$searchTexts['search-emplist-ext'].'</th>'.
	'<th>'.$searchTexts['search-emplist-email'].'</th>'.
	'</tr>';

foreach ($listAll as $person)
{
	echo '<tr><td style="text-align:left">';

	$personName = '<b>'.$person['lastName'].'</b>, '.$person['firstName'];

	if (empty($person['link']))
		echo $personName; else
		echo '<a href="'.$person['link'].'" target="_blank" rel="noopener">'.
			$personName.' <em title="'.$searchTexts['search-target-title'].
			'">»</em></a>';

	echo '<br />'.EOL.'<small>('.$person['fullName'].')</small>';

	echo '</td><td style="text-align:left">';

	if (!empty($person['workplace']))
	{
		$shorten = RheiaMainClass::filterHTML($person['workplace']);
		// Shorten it…
		if (preg_match('/^(\X{25}\pL*)/u', $shorten, $matches))
			echo $matches[1].'…'; else echo $shorten;
	}

	if (!empty($person['depid']))
		echo '<br />'.EOL.'<small>('.$person['depid'].')</small>';

	echo '</td><td style="text-align:center">';
	if (!empty($person['rooms'])) echo $person['rooms'];
	echo '</td><td style="text-align:center">';
	if (!empty($person['extens'])) echo $person['extens'];
	echo '</td><td>';
	if (!empty($person['emails'])) echo $person['emails'];
	echo '</td></tr>'.EOL;
}

echo '</table>';

?>