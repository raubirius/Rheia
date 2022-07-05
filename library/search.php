<?php

include_once 'RheiaMainClass.php';
RheiaMainClass::$checkExtern = false;

if (!isSet($searchString) || empty($searchString))
	$searchString = isSet($_POST['q']) ? $_POST['q'] : '';

include_once 'search-texts.php';

echo '<h1>'.($title = $searchTexts['search-title']).'</h1>'.EOL2;


if (RheiaMainClass::searchFor($searchString) && RheiaMainClass::$searchCount)
{
	echo '<p><b>'.$searchTexts['search-search-for'].':</b> '.
		htmlspecialchars(RheiaMainClass::$searchString).'</p>';

	include_once 'search-employee.php';
	include_once 'search-site.php';
	include_once 'search-google-form.php';
}
elseif (isSet($_GET['q']))
{
	include_once 'search-google.php';
}
else
{
	echo '<p class="error">'.$searchTexts['search-invalid-string'].'</p>';
}

?>