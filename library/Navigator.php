<?php

// Note: this is a module used by the RheiaMainClass.
// For more details see comments in SocialNetworks.php

/* Navigator creates navigation links between articles within one hta script. */

class Navigator
{
	static $id = null;
	static $base = null;
	static $addStyle = true;
	static $defaultStyle = false;
	static $baseSeparator = '?';
	static $contents = 'Contents';
	static $contentsLink = null;
	static $articles = null;

	static function validArticle()
	{
		$link = key(Navigator::$articles);
		if (null === $link || 'title' === $link || 'templates' === $link ||
			'warnings' === $link || 'listEmptyMessage' === $link ||
			'revalidationDate' === $link) return false;
		$article = current(Navigator::$articles);
		return !isSet($article['staticHTML']) &&
			!$article['options']['článok:odstránený'] &&
			(!isSet($article['expires']) ||
			($article['expires'] >= RheiaMainClass::$currentTime) ||
			$article['options']['zoznam:ponechajNeplatné']) &&
			!$article['options']['zoznam:skryČlánok'];
	}

	static function goArticle($key)
	{
		if (false === reset(Navigator::$articles)) return false;
		while (key(Navigator::$articles) !== $key)
			if (false === next(Navigator::$articles)) return false;
		return true;
	}

	static function prevID()
	{
		if (Navigator::goArticle(Navigator::$id))
		{
			do {
				if (false === prev(Navigator::$articles)) return false;
			} while (!Navigator::validArticle());
			return true;
		}
		return false;
	}

	static function nextID()
	{
		if (Navigator::goArticle(Navigator::$id))
		{
			do {
				if (false === next(Navigator::$articles)) return false;
			} while (!Navigator::validArticle());
			return true;
		}
		return false;
	}

	static function pareID($id = null)
	{
		if (null === $id) $id = Navigator::$id;

		if (Navigator::goArticle($id))
		{
			if (Navigator::validArticle())
			{
				$article = current(Navigator::$articles);
				if (isSet($article['listLevel']))
				{
					$level = $article['listLevel'];
					if ('level-3' == $level) $level = 'level-2';
					else // if ('level-2' == $level)
						$level = null;

					while (false !== prev(Navigator::$articles) &&
						Navigator::validArticle())
					{
						$article = current(Navigator::$articles);

						if (is_null($level))
						{
							if (!isSet($article['listLevel']))
								return true;
						}
						else if ($level === $article['listLevel'])
							return true;
					}
				}
			}
		}
		return false;
	}

	function run($args)
	{
		global $style, $category, $selectedItem, $articles;

		if (null === Navigator::$id)
			Navigator::$id = $selectedItem;
		if (null === Navigator::$base)
			Navigator::$base = '/'.$category;
		if (null === Navigator::$articles)
			Navigator::$articles = $articles->getArticlesData();

		foreach ($args as $arg)
		{
			if ('defaultStyle' == $arg)
				Navigator::$defaultStyle = true;
			else if ('noDefaultStyle' == $arg)
				Navigator::$defaultStyle = false;
			else if (preg_match('/^base:(.*)/', $arg, $matches))
				Navigator::$base = $matches[1];
			else if (preg_match('/^id:(.*)/', $arg, $matches))
				Navigator::$id = $matches[1];
			else if (preg_match('/^baseSeparator:(.*)/', $arg, $matches))
				Navigator::$baseSeparator = $matches[1];
			else if (preg_match('/^contents:(.*)/', $arg, $matches))
				Navigator::$contents = $matches[1];
			else if (preg_match('/^contentsLink:(.*)/', $arg, $matches))
				Navigator::$contentsLink = $matches[1];
		}

		if (null === Navigator::$contentsLink)
			Navigator::$contentsLink = Navigator::$base;

		HTMLHeadManagement::setSimpleLink(RheiaMainClass::$protocol.'://'.
			RheiaMainClass::$domain.Navigator::$contentsLink, 'contents');

		if (Navigator::$addStyle && Navigator::$defaultStyle)
		{
			Navigator::$addStyle = false;

			$style .= EOL.'div.article-header, div.article-footer'.EOL.'{'.
				EOLT.'display: table;'.EOLT.'margin: 10px 0px;'.EOLT.
				'width: 100%;'.EOL.'}'.EOL2.
				'div.article-header p, div.article-footer p'.EOL.'{'.EOLT.
				'display: table-row;'.EOLT.'font-size: 12px;'.EOL.'}'.EOL2.
				'div.article-header p span.left-cell,'.EOL.
				'div.article-footer p span.left-cell,'.EOL.
				'div.article-header p span.right-cell,'.EOL.
				'div.article-footer p span.right-cell'.EOL.'{'.EOLT.
				'display: table-cell;'.EOL.'}'.EOL2.
				'div.article-header p span.left-cell,'.EOL.
				'div.article-footer p span.left-cell'.EOL.'{'.EOLT.
				'text-align: left;'.EOL.'}'.EOL2.
				'div.article-header p span.right-cell,'.EOL.
				'div.article-footer p span.right-cell'.EOL.'{'.EOLT.
				'text-align: right;'.EOL.'}'.EOL2.'div.article-header'.EOL.
				'{'.EOLT.'margin-bottom: 20px;'.EOLT.'padding-bottom: 10px;'.
				EOLT.'border-bottom: 1px solid #aaa;'.EOL.'}'.EOL2.
				'div.article-footer'.EOL.'{'.EOLT.'margin-top: 40px;'.EOLT.
				'padding-top: 10px;'.EOLT.'border-top: 1px solid #aaa;'.
				EOL.'}'.EOL2;
		}

		// Get IDs
		$prevID = Navigator::prevID() ? key(Navigator::$articles) : false;
		$nextID = Navigator::nextID() ? key(Navigator::$articles) : false;
		$pareID = Navigator::pareID() ? key(Navigator::$articles) : false;

		// Init – Left half of navigation
		$return = '<span class="left-cell">';

		// Contents
		if (!empty(Navigator::$contents))
			$return .= '<span> <a href="'.Navigator::$contentsLink.'">'.
				Navigator::$contents.'</a> </span>';

		if ($pareID)
		{
			$pare = '<span>|</span><span> <a href="'.Navigator::$base.
				Navigator::$baseSeparator.$pareID.'">'.RheiaMainClass::
				replaceObjects(Navigator::$articles[$pareID]['title']).
				'</a> </span>';

			$pareID = Navigator::pareID($pareID) ?
				key(Navigator::$articles) : false;

			if ($pareID) $pare = '<span>|</span><span> <a href="'.
				Navigator::$base.Navigator::$baseSeparator.$pareID.'">'.
				RheiaMainClass::replaceObjects(Navigator::$articles[$pareID]
					['title']).'</a> </span>'.$pare;

			$return .= $pare;
		}

		// Right half of navigation
		$return .= '</span><span class="right-cell">';

		if ($prevID)
		{
			HTMLHeadManagement::setSimpleLink(RheiaMainClass::$protocol.'://'.RheiaMainClass::$domain.
				Navigator::$base.Navigator::$baseSeparator.$prevID, 'prev');
			$return .= '<span> <a href="'.Navigator::$base.Navigator::
				$baseSeparator.$prevID.'">« '.RheiaMainClass::replaceObjects(
					Navigator::$articles[$prevID]['title']).' </a> </span>';
		}

		if ($prevID && $nextID) $return .= '<span>|</span>';

		if ($nextID)
		{
			HTMLHeadManagement::setSimpleLink(RheiaMainClass::$protocol.'://'.RheiaMainClass::$domain.
				Navigator::$base.Navigator::$baseSeparator.$nextID, 'next');
			$return .= '<span> <a href="'.Navigator::$base.Navigator::
				$baseSeparator.$nextID.'"> '.RheiaMainClass::replaceObjects(
					Navigator::$articles[$nextID]['title']).' »</a> </span>';
		}

		$return .= '</span>';

		return $return;

		/* $url = $_SERVER['REQUEST_URI'];
		if (empty($url)) $url = '/';
		else if ('/' != $url[0]) $url = '/'.$url;
		$url = RheiaMainClass::$protocol.'://'.RheiaMainClass::$domain.$url; */
	}
}

?>