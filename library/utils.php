<?php

function handleSomeRedirects($errorCode)
{
	global $downloadPath, $downloadLevel, $downloadSubfolder,
		$downloadPathAllowDots, $downloadModifyOriginalName, $downloadScript;
	ob_start();

	if ($errorCode == 403 || $errorCode == 404)
	{
		$originalName = ltrim(str_replace('\\', '/',
			/**/ htmlspecialchars
			(rawurldecode($_SERVER['REQUEST_URI']))), '/');

		$directRedirection = (isSet($downloadSubfolder) ?
			$downloadSubfolder : '../downloads').'/'.str_replace('/', '-',
			$originalName).'.redirect';
		if (file_exists($directRedirection) && is_file($directRedirection))
		{
			global $newName;
			$newName = '/'.(isSet($downloadScript) ? $downloadScript :
				'download').'?'.file_get_contents($directRedirection);
			header('Location: '.$newName);
			include '301.php'; // obsahuje header 301
			ob_end_flush();
			exit;
		}

		if (!empty($originalName) &&
			false === strpos($originalName, '..') &&
			($downloadPathAllowDots || (false === strpos($downloadPath, '..'))))
		{
			$filePathName = null;

			if (isSet($downloadModifyOriginalName) &&
				is_array($downloadModifyOriginalName))
			{
				$i = 0; $regEx = '';
				foreach ($downloadModifyOriginalName as $data)
				{
					if (0 == ($i % 2)) $regEx = $data; else
						$originalName = preg_replace($regEx, $data, $originalName);
					++$i;
				}
			}

			foreach (explode(';', $downloadPath) as $path)
			{
				$filePathName = $downloadLevel.(isSet($downloadSubfolder) ?
					$downloadSubfolder : '../downloads').'/';
				if (!empty($path)) $filePathName .= $path.'/';
				$filePathName .= $originalName;

				if (file_exists($filePathName) && is_file($filePathName))
				{
					global $newName;
					$newName = '/'.(isSet($downloadScript) ?
						$downloadScript : 'download').'?'.$originalName;
					header('Location: '.$newName);
					include '301.php'; // obsahuje header 301
					ob_end_flush();
					exit;
				}
			}

			if (0 === strpos($originalName, '~'))
			{
				include_once 'remove-diacritics.php';
				$tildeLess = removeDiacriticsFromURI(substr($originalName, 1));

				$profileDir = '../~users/'.$tildeLess;
				if (file_exists($profileDir))
				{
					echo '<h1 style="text-align: center">Táto stránka je '.
						'presmerovaním<br />na osobný profil zamestnanca</h1>';
					echo '<p style="text-align: center"><a href="/'.$tildeLess.
						'">Prosím, kliknite na tento odkaz.</a></p>';
					header('HTTP/1.1 302 Found');
					header('Location: '.$_SERVER['REQUEST_SCHEME'].
						'://'.$_SERVER['HTTP_HOST'].'/'.$tildeLess);
					// echo('HTTP/1.1 302 Found<br />');
					// echo('Location: /'.$tildeLess);
					exit;
				}

				include_once 'RheiaMainClass.php';
				RheiaMainClass::$checkExtern = false;

				global $listAll, $mergedObjects, $designTexts;

				$listAll = array();
				// include_once 'search-texts.php';
				include_once 'search-employee.php';
				// var_dump($listAll);

				$count = 0; $persons = array();
				foreach ($listAll as $person)
				{
					if ($person['lastNamePlain'] === $tildeLess)
					{
						$persons[] = $person;
						++$count;
					}

					$length = strlen($person['firstNamePlain']);
					$extName = $person['lastNamePlain'];
					for ($i = 0; $i < $length; ++$i)
					{
						$extName .= $person['firstNamePlain'][$i];
						if ($extName === $tildeLess)
						{
							$persons[] = $person;
							++$count;
						}
					}
				}

				$refs = array();
				foreach ($persons as $person)
				{
					$link = $person['link'];
					$workplace = $person['workplace'];
					$showas = '';

					if (empty($link))
					{
						if (false !== strpos($workplace, '<a') &&
							preg_match('/href="([^"]+)"/i', $workplace, $match))
						{
							$link = $match[1];
							$showas = $person['fullName'].' – '.$workplace;
						}
						else
						{
							$showas = $person['fullName'];
						}
					}
					else $showas = '<a href="'.$link.'">'.
						$person['fullName'].'</a>';

					$refs[] = array($link, $showas);
				}

				if ($count > 1)
				{
					echo '<h1>Toto je rozlišovacia stránka '.
						'presmerovaní na profily osôb</h1>';
					echo '<p>Ak profil jestvuje, tak odkaz na osobu '.
						'v zozname nižšie je aktívny.</p>';
					echo '<p>Prosím, zvoľte si želanú osobu.</p>';
				}
				else if ($count === 1)
				{
					echo '<h1>Táto stránka je presmerovaním na profil osoby</h1>';
					echo '<p>Prosím, kliknite na nasledujúci odkaz:</p>';
				}

				foreach ($refs as $ref) echo '<p>'.$ref[1].'</p>';

				if ($count > 1)
				{
					header('HTTP/1.1 200 OK');
					// echo('HTTP/1.1 200 OK');
					exit;
				}
				else if ($count === 1)
				{
					header('HTTP/1.1 302 Found');
					header('Location: '.html_entity_decode($refs[0][0]));
					// echo('HTTP/1.1 302 Found<br />');
					// echo('Location: '.html_entity_decode($refs[0][0]));
					exit;
				}
				else if (0 === $count)
				{
					if (preg_match('/^[ck][abcefmnps][chijpsv]?u?$/i',
						$tildeLess, $match))
					{
						global $newName;
						$newName = $_SERVER['REQUEST_SCHEME'].'://'.
							$_SERVER['HTTP_HOST'].'/katedry/~'.$tildeLess;
						header('Location: '.$newName);
						include '301.php'; // obsahuje header 301
						ob_end_flush();
						exit;
					}
				}
			}
		}
	}

	$filename = $errorCode.'.php';
	if (@include $filename)
		$return = ob_get_contents();
	else
		$return = false;
	ob_end_clean();
	return $return;
}

?>