<?php
if (isSet($selectedItem))
{
	$articles = new RheiaMainClass($selectedItem, false);
	RheiaMainClass::loadObjects(); $articles->loadArticles();
	$articlesSource = $selectedItem;

	if (isSet($argument))
		$articles->showArticle($argument, $category.'?'.$selectedItem.'&');
	else
		$articles->articlesList($category.'?'.$selectedItem.'&');

	$title = $articles->getTitle();
}
?>