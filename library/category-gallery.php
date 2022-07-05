<?php
// The same as the category-html.php (yet).
if (isSet($category))
{
	$html = new RheiaMainClass($category);
	$title = $html->getTitle();
}
?>