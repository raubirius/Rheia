<?php

include 'pdf.php';

if (404 == (int)$_SERVER['QUERY_STRING'])
{
	ob_start();

	$originalName = ltrim(str_replace('\\', '/',
		// htmlspecialchars
		(rawurldecode($_SERVER['REQUEST_URI']))), '/');

	if (!empty($originalName) && false === strpos($originalName, '..'))
	{
		$filePathName = '/'.preg_replace(
			'/^e-skripta/i', 'e-ucebnice', $originalName);

		if (file_exists('..'.$filePathName) ||
			file_exists('..'.$filePathName.'.php'))
		{
			$newName = $filePathName;
			header('Location: '.$newName);
			include '301.php'; // obsahuje header 301
			ob_end_flush();
			die;
		}

		foreach (explode(';', $downloadPath) as $path)
		{
			$filePathName = '../../downloads/';
			if (!empty($path)) $filePathName .= $path.'/';
			$filePathName .= $originalName;
			if (file_exists($filePathName))
			{
				$newName = '/download?'.$originalName;
				header('Location: '.$newName);
				include '301.php'; // obsahuje header 301
				die;
			}
		}
	}

	ob_end_clean();
}

include 'design.php';
include 'error.php';

// if (!empty($downloadPath)) echo '<p>downloadPath: '.$downloadPath.'</p>';
// if (!empty($originalName)) echo '<p>originalName: '.$originalName.'</p>';
// if (!empty($filePathName)) echo '<p>filePathName: '.$filePathName.'</p>';
// if (!empty($newName)) echo '<p>newName: '.$newName.'</p>';
?>