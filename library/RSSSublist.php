<?php

// Note: this is a module used by the RheiaMainClass.
// For more information see: modules-readme.txt

include_once 'RSSLoader.php';

class RSSSublist
{
	function run($args)
	{
		global $rssLinkFormat, $rssBaseName, $rssXML, $rssParsedItems;

		$listStart = 1;
		$listEnd = 6;

		foreach ($args as $arg)
		{
			if (preg_match('/^linkFormat:(.*)/', $arg, $matches))
				$rssLinkFormat = $matches[1];
			elseif (preg_match('/^baseName:(.*)/', $arg, $matches))
				$rssBaseName = intval($matches[1]);
			elseif (preg_match('/^XML:(.*)/', $arg, $matches))
				$rssXML = intval($matches[1]);
			elseif (preg_match('/^start:(.*)/', $arg, $matches))
				$listStart = intval($matches[1]);
			elseif (preg_match('/^end:(.*)/', $arg, $matches))
				$listEnd = intval($matches[1]);
		}

		$return = ''; $count = 0;
		rssLoadParseXML();

		foreach ($rssParsedItems as $rssParsedItem)
		{
			if (!empty($rssParsedItem['title']))
			{
				++$count;
				if (($count >= $listStart) && ($count <= $listEnd))
					$return .= EOL.'<li><a href="'.$rssLinkFormat.
						$rssParsedItem['link'].'" target="_blank" '.
						'rel="noopener">'.$rssParsedItem['title'].
						'</a></li>'.EOL;
			}
		}

		return $return;
		// return '<pre>'.htmlspecialchars($return).'</pre>';
	}
}

?>