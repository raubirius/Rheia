<?php
if (isSet($category))
{
	$html = new RheiaMainClass($category);
	$title = $html->getTitle();
}
?>