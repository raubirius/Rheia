<?php

include_once 'RheiaMainClass.php';

function rssSetDefaults()
{
	global $rssLinkFormat, $rssBaseName, $rssXML;
	if (!isSet($rssLinkFormat)) $rssLinkFormat = null;
	if (!isSet($rssBaseName)) $rssBaseName = 'aktuality-univerzita';
	if (!isSet($rssXML)) $rssXML = 'https://www.truni.sk/rss.xml';
}

function rssLoadParseXML()
{
	global $rssLinkFormat, $rssBaseName, $rssXML, $rssParsedItems;

	rssSetDefaults();
	$rssParsed = RheiaMainClass::$parsedPath.$rssBaseName.'-x.php';

	if (file_exists($rssParsed))
		{ include './'.$rssParsed; }
	else
		$rssMustRevalidate = true;

	if ($rssMustRevalidate)
	{
		$rssParsedItems = array();
		$rssChannel = simplexml_load_file($rssXML);

		foreach ($rssChannel->channel->item as $rssItem)
		{
			$rssParsedItem = array();

			$pubDate = date_parse_from_format(
				'D, d M Y H:i:s O', $rssItem->pubDate);

			$rssParsedItem['link'] = (string)$rssItem->link;
			$rssParsedItem['date'] = mktime($pubDate['hour'], $pubDate['minute'],
				$pubDate['second'], $pubDate['month'],$pubDate['day'],
				$pubDate['year']);
			$rssParsedItem['author'] =
				(string)$rssItem->children('dc', true)->creator;
			$rssParsedItem['title'] = (string)$rssItem->title;
			$rssParsedItem['preview'] = (string)$rssItem->description;

			$rssParsedItems[] = $rssParsedItem;
		}

		$rssContent = '<'.'?php'.EOL2;
		$rssContent .= '$rssParsedItems = '.var_export($rssParsedItems, true);
		$rssContent .= ';'.EOL2;
		$rssContent .= '$rssMustRevalidate = time() >= '.
			(time() + 7200).';'.EOL2;
		$rssContent .= '?'.'>';

		file_put_contents($rssParsed, $rssContent, LOCK_EX);
	}
}

?>