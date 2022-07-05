<?php

include_once 'RSSLoader.php';
rssLoadParseXML();

$rssHTML = new RheiaMainClass($rssBaseName);
$title = $rssHTML->getTitle();

foreach ($rssParsedItems as $rssParsedItem)
{
	echo '<div class="article-preview">';
	$previewTopDots = ' preview-top-dots';

	if (!empty($rssParsedItem['date']))
		echo '<div class="date"><p>'.date(date_format,
			$rssParsedItem['date']).', '.RheiaMainClass::getWhen(
			$rssParsedItem['date']).'</p></div>';

	if (!empty($rssParsedItem['author']))
		echo '<div class="author"><p>'.
			$rssParsedItem['author'].'</p></div>';

	if (!empty($rssParsedItem['date']) || !empty($rssParsedItem['author']))
		echo '<div class="clear"></div>'; else $previewTopDots = '';

	if (!empty($rssParsedItem['title']))
	{
		echo '<div class="title'.$previewTopDots.
			'"><h2><a href="'.$rssLinkFormat.$rssParsedItem['link'].
			'" target="_blank">'.$rssParsedItem['title'].
			'</a></h2></div>';
		$previewTopDots = '';
	}

	if (!empty($rssParsedItem['preview']))
		echo '<div class="preview'.$previewTopDots.
			'"><p>'.$rssParsedItem['preview'].'<a href="'.
			$rssLinkFormat.$rssParsedItem['link'].
			'" class="read-more" target="_blank">'.
			RheiaMainClass::getText('article-list-read-more').
			' »</a></p></div>';

	echo '</div>';
}

?>