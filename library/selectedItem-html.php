<?php
if (isSet($selectedItem))
{
	$html = new RheiaMainClass($selectedItem);
	$title = $html->getTitle();
}
?>