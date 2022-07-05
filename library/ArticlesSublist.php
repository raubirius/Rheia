<?php

// Note: this is a module used by the RheiaMainClass.
// For more information see: modules-readme.txt

class ArticlesSublist
{
	function run($args)
	{
		global $articlesSource;

		$listCategory = 'aktuality';
		$listStart = 1;
		$listEnd = 6;
		$linkFormat = 'aktuality?';

		foreach ($args as $arg)
		{
			if (preg_match('/^category:(.*)/', $arg, $matches))
				$listCategory = $matches[1];
			elseif (preg_match('/^start:(.*)/', $arg, $matches))
				$listStart = intval($matches[1]);
			elseif (preg_match('/^end:(.*)/', $arg, $matches))
				$listEnd = intval($matches[1]);
			elseif (preg_match('/^linkFormat:(.*)/', $arg, $matches))
				$linkFormat = intval($matches[1]);
		}

		$return = '';

		$articles = new RheiaMainClass($listCategory, false);
		RheiaMainClass::loadObjects(); $articles->loadArticles();
		$articlesSource = $listCategory; $count = 0;

		foreach ($articles->getArticlesData() as $link => $article)
		{
			$preview = $articles->getArticlePreview(
				$article, $link, $linkFormat);
			if (!empty($preview))
			{
				++$count;
				if (($count >= $listStart) && ($count <= $listEnd))
					$return .= EOL.$preview.EOL;
			}
		}

		return $return;
		// return '<pre>'.htmlspecialchars($return).'</pre>';
	}
}

?>