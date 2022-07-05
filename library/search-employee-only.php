<?php

include_once 'RheiaMainClass.php';
RheiaMainClass::$checkExtern = false;

if (!isSet($searchString) || empty($searchString))
	$searchString = isSet($_POST['q']) ? $_POST['q'] : '';

include_once 'search-texts.php';

if (RheiaMainClass::searchFor($searchString) && RheiaMainClass::$searchCount)
{
	echo '<p> </p><p><b>'.$searchTexts['search-search-for'].':</b> '.
		htmlspecialchars(RheiaMainClass::$searchString).'</p>';

	include_once 'search-employee.php';

	if (!isSet($searchResults))
		echo '<p>'.$searchTexts['search-employees-not-found'].'.</p>';
}

?>