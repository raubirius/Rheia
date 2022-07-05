<?php

if (isSet($selectedItem))
{
	include_once 'RheiaMainClass.php';
	include_once 'counter.php';
	include_once 'GalleryLoader.php';
	new GalleryLoader($selectedItem, $galleryPath,
		isSet($galleryNavigate) ? $galleryNavigate :
			null, isSet($downloadLevel) ? $downloadLevel : '../');
}
else include_once 'content.php';

?>