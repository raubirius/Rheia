<?php

$userData = LoginManagement::reloadUserData($_SESSION['userID']);

echo '<h1 class="home">'.($title = $designTexts['login-home']).'</h1>'.EOL;

/*
$a = LoginManagement::$defaultChpwdScript;
$b = LoginManagement::checkAccess($a);
echo '<pre>';
var_dump($a);
var_dump($b);
echo '</pre>';
*/

if (!empty(LoginManagement::$defaultChpwdScript) &&
	LoginManagement::checkAccess(LoginManagement::$defaultChpwdScript))
	echo '<p class="home chpwd"><a href="'.LoginManagement::
		$defaultChpwdScript.'">'.$designTexts['chpwd-title'].'</a></p>';

echo '<div class="clear"></div>';

if (isSet($userData['homePageContent–'.$contentLanguage]))
	$userData['homePageContent'] = $userData['homePageContent–'.
		$contentLanguage];

if (isSet($userData['homeReplaceParsedPath–'.$contentLanguage]))
	$userData['homeReplaceParsedPath'] = $userData['homeReplaceParsedPath–'.
		$contentLanguage];

if (isSet($userData['homePageArticle–'.$contentLanguage]))
	$userData['homePageArticle'] = $userData['homePageArticle–'.
		$contentLanguage];

if (isSet($userData['homeSectionPath–'.$contentLanguage]))
	$userData['homeSectionPath'] = $userData['homeSectionPath–'.
		$contentLanguage];

// echo '<pre>'; var_dump($userData); echo '</pre>';

if (!empty($userData['homePageItem']) && !empty($userData['homePageLink']))
{
	echo '<ul class="home">';
	foreach ($userData['homePageItem'] as $i => $homePageItem)
	{
		echo '<li class="home"><a href="'.
			$userData['homePageLink'][$i][1].'">'.
			$homePageItem[1].'</a></li>';
	}
	echo '</ul>';
}

if (isSet($userData['homePageContent']) &&
	!empty($userData['homePageContent'][1]))
{
	if (isSet($userData['homeSectionPath']) &&
		!empty($userData['homeSectionPath'][1]))
	{
		$siteSectionPath = $userData['homeSectionPath'][1];
		if (!preg_match('~.*/$~', $siteSectionPath))
			$siteSectionPath .= '/';
		// echo '<p>homeSectionPath: '.$siteSectionPath.'</p>';
	}

	// echo '<p>homePageContent: '.$userData['homePageContent'][1].'</p>';
	include_once 'RheiaMainClass.php';

	if (isSet($userData['homePageArticle']))
	{
		$articlesSource = $userData['homePageContent'][1];
		$strrpos = strrpos($articlesSource, '/');
		if (false !== $strrpos)
		{
			$path = substr($articlesSource, 0, 1 + $strrpos);
			$articlesSource = substr($articlesSource, 1 + $strrpos);

			RheiaMainClass::$contentPath .= $path;
			RheiaMainClass::$parsedPath .= 'home/'.$path;
		}

		if (isSet($userData['homeReplaceParsedPath']))
			RheiaMainClass::$parsedPath = $userData['homeReplaceParsedPath'][1];

		// echo '<p>homePageArticle: '.$userData['homePageArticle'][1].'</p>';
		$articles = new RheiaMainClass($articlesSource, false);
		RheiaMainClass::loadObjects(); $articles->loadArticles();

		if (empty($userData['homePageArticle'][1]))
			$articles->articlesList($articlesSource.'?');
		else
			$articles->showArticle($userData['homePageArticle'][1],
				$articlesSource.'?');

		// echo '<pre>'; var_dump($articles); echo '</pre>';
	}
	else new RheiaMainClass($userData['homePageContent'][1]);
}

?>