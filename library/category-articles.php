<?php
if (isSet($category))
{
	$articles = new RheiaMainClass($category, false);
	RheiaMainClass::loadObjects(); $articles->loadArticles();
	$articlesSource = $category;

	if (isSet($selectedItem))
		$articles->showArticle($selectedItem, $category.'?');
	else
		$articles->articlesList($category.'?');

	$title = $articles->getTitle();
}
?>