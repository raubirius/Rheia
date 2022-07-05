<h1 class="error"><?php
if (isSet($GLOBALS['designTexts']) &&
	!empty($GLOBALS['designTexts']['error-no-access-head']))
	echo $GLOBALS['designTexts']['error-no-access-head'];
else
	echo 'Nedostatočné prístupové práva';
?></h1>
<p class="error"><?php
if (isSet($GLOBALS['designTexts']) &&
	!empty($GLOBALS['designTexts']['error-no-access-text']))
		echo $GLOBALS['designTexts']['error-no-access-text'];
	else
		echo 'Ľutujeme, ale na prístup k tomuto obsahu nemáte dostatočné oprávnenie.';
?></p>
<p><?php
if (isSet($GLOBALS['designTexts']) &&
	!empty($GLOBALS['designTexts']['error-no-access-desc']))
		echo $GLOBALS['designTexts']['error-no-access-desc'];
	else
		echo 'Ak si myslíte, že by ste mali mať prístup k tomuto obsahu, kontaktujte prislúchajúcu autoritu na pridelenie práv.';
?></p>